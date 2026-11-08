<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Network\SessionLog;
use App\Models\Statistics\FlightLog;

/**
 * Numbers for the homepage strip: what happened in the last 24 hours and a rough
 * forecast of oceanic traffic from filed flight plans on the VATSIM data feed.
 */
class OceanicStats
{
    /** Oceanic entry/exit fixes: a filed route containing one of these is treated as an oceanic flight. */
    const FIXES = ['ADARA', 'ADODO', 'AGORI', 'ALLRY', 'ATSIX', 'AVPUT', 'AVUTI', 'BALIX', 'BEDRA', 'BEGAS', 'BERUX', 'BILTO', 'BOBTU', 'BOKTO', 'BUDAR', 'BUNAV', 'CLAVY', 'CUDDY', 'DINIM', 'DIVAT', 'DIXIS', 'DOGAL', 'DORYY', 'ELSIR', 'EMBOK', 'ENNSO', 'ERAKA', 'ETARI', 'ETIKI', 'ETILO', 'GELPO', 'GOMUP', 'GONAN', 'HOIST', 'IBERG', 'IRLOK', 'JANJO', 'JAROM', 'JOOPY', 'KETLA', 'KODIK', 'KOGAD', 'LASNO', 'LIBOR', 'LIMRI', 'LOMSI', 'LUSEN', 'MALOT', 'MAXAR', 'MELDI', 'MUSAK', 'NASBA', 'NEBIN', 'NEEKO', 'NERTU', 'NICSO', 'NIFTY', 'NILAV', 'OMOKO', 'OMSAT', 'ORTAV', 'PASAS', 'PELTU', 'PIDSO', 'PIKIL', 'PITAX', 'PORTI', 'RADUN', 'RAFIN', 'RATSU', 'RELIC', 'RESNO', 'RIKAL', 'RODEL', 'SAVRY', 'SAXAN', 'SEPAL', 'SIVIR', 'SOMAX', 'SUNOT', 'SUPRY', 'TAMEL', 'TOBOR', 'TOXIT', 'TUDEP', 'UMESI', 'UMLER', 'URTAK', 'VENER', 'VESMI'];

    /** Last 24 hours: flights that spent 15+ minutes in the oceanic areas, and ATC provided. */
    public static function last24h(): array
    {
        return Cache::remember('home_last24h', 120, function () {
            $now = Carbon::now();
            $from = $now->copy()->subHours(24);

            $movements = FlightLog::whereNotNull('save_details')->where('created_at', '>=', $from)->count();

            $minutes = 0;
            $positions = [];
            $sessions = SessionLog::where('session_start', '<=', $now)
                ->where(function ($q) use ($from) {
                    $q->whereNull('session_end')->orWhere('session_end', '>=', $from);
                })->get();
            foreach ($sessions as $s) {
                $start = $s->session_start->greaterThan($from) ? $s->session_start : $from;
                $end = $s->session_end && $s->session_end->lessThan($now) ? $s->session_end : $now;
                if ($end->greaterThan($start)) {
                    $minutes += $start->diffInMinutes($end);
                    $positions[$s->callsign] = true;
                }
            }

            return [
                'movements' => $movements,
                'atc_hours' => round($minutes / 60, 1),
                'positions' => count($positions),
            ];
        });
    }

    /** Rough forecast for the next $hours hours from filed flight plans. */
    public static function forecast(int $hours = 6): array
    {
        return Cache::remember('home_forecast_' . $hours, 300, function () use ($hours) {
            try {
                $data = Cache::remember('vatsimdata', 15, function () {
                    return (new VATSIMClient())->getVATSIMData();
                });
                $crossed = FlightLog::whereNotNull('exited_oca')->get(['cid', 'callsign'])
                    ->map(function ($f) { return $f->cid . '|' . $f->callsign; })->all();
                return self::analyse($data->pilots ?? [], $data->prefiles ?? [], Carbon::now('UTC'), $crossed, $hours);
            } catch (\Throwable $e) {
                return ['departing' => null, 'approaching' => null, 'total' => null];
            }
        });
    }

    /** Does this route use an oceanic entry/exit fix, a NAT track or a lat/long oceanic point? */
    public static function isOceanicRoute(?string $route): bool
    {
        foreach (preg_split('/\s+/', strtoupper(trim((string) $route))) as $token) {
            $token = explode('/', $token)[0];
            if ($token === '') continue;
            if (in_array($token, self::FIXES, true)) return true;
            if (preg_match('/^NAT[A-Z]?$/', $token)) return true;
            if (preg_match('/^\d{2}(\d{2})?N\d{3}(\d{2})?W$/', $token)) return true;
        }
        // 5030/30 style points: the whole token keeps its slash
        return (bool) preg_match('/(^|\s)\d{2}(\d{2})?\/\d{2}(\s|$)/', strtoupper((string) $route));
    }

    /**
     * Pure function so it can be tested without a network.
     * departing  = not yet airborne, departure time within the next $hours hours
     * approaching = airborne, oceanic route, not yet seen in oceanic airspace, still en route
     */
    public static function analyse(array $pilots, array $prefiles, Carbon $now, array $crossed, int $hours = 6): array
    {
        $departing = 0;
        $approaching = 0;

        $depTime = function ($fp) use ($now) {
            $t = str_pad((string) ($fp->deptime ?? ''), 4, '0', STR_PAD_LEFT);
            if (!preg_match('/^([01]\d|2[0-3])([0-5]\d)$/', $t, $m)) return null;
            $d = $now->copy()->setTime((int) $m[1], (int) $m[2], 0);
            if ($d->lessThan($now->copy()->subHours(12))) $d->addDay();
            if ($d->greaterThan($now->copy()->addHours(12))) $d->subDay();
            return $d;
        };
        $enroute = function ($fp) {
            $t = str_pad((string) ($fp->enroute_time ?? ''), 4, '0', STR_PAD_LEFT);
            return preg_match('/^(\d{2})(\d{2})$/', $t, $m) ? ((int) $m[1] * 60 + (int) $m[2]) : null;
        };

        foreach (array_merge($prefiles, $pilots) as $p) {
            $fp = $p->flight_plan ?? null;
            if (!$fp || !self::isOceanicRoute($fp->route ?? '')) continue;
            $airborne = isset($p->groundspeed) && $p->groundspeed > 60;
            $dt = $depTime($fp);
            if ($dt === null) continue;

            if (!$airborne) {
                if ($dt->greaterThanOrEqualTo($now->copy()->subMinutes(15)) && $dt->lessThanOrEqualTo($now->copy()->addHours($hours))) $departing++;
                continue;
            }
            if (!isset($p->cid) || in_array($p->cid . '|' . $p->callsign, $crossed, true)) continue;
            $eet = $enroute($fp);
            if ($eet === null || $dt->copy()->addMinutes($eet)->lessThan($now)) continue;
            // Already inside an oceanic area is counted in the live numbers, so skip pilots already over the North Atlantic
            if ($p->longitude < -8 && $p->longitude > -70 && $p->latitude > 44) continue;
            $approaching++;
        }

        return ['departing' => $departing, 'approaching' => $approaching, 'total' => $departing + $approaching];
    }
}
