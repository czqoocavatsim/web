<?php

namespace App\Models\Events;

use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Parsedown;
use Illuminate\Support\HtmlString;
use Auth;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Event extends Model
{
    protected $fillable = [
        'id', 'name', 'start_timestamp', 'end_timestamp', 'user_id', 'description', 'image_url', 'controller_applications_open', 'departure_icao', 'arrival_icao', 'slug'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updates()
    {
        return $this->hasMany(EventUpdate::class);
    }

    public function controllerApplications()
    {
        return $this->hasMany(ControllerApplication::class);
    }

    public function starts_in_pretty()
    {
        $t = Carbon::create($this->start_timestamp);
        return $t->diffForHumans();
    }

    public function start_timestamp_pretty()
    {
        $t = Carbon::create($this->start_timestamp);
        return $t->day . ' ' . $t->monthName . ' ' . $t->year . ' ' . $t->format('H:i') . ' Zulu';
    }

    public function flatpickr_limits()
    {
        $start = Carbon::create($this->start_timestamp);
        $end = Carbon::create($this->end_timestamp);
        return array(
            $start->format('H:i'),
            $end->format('H:i')
        );
    }

    public function end_timestamp_pretty()
    {
        $t = Carbon::create($this->end_timestamp);
        return $t->day . ' ' . $t->monthName . ' ' . $t->year . ' ' . $t->format('H:i') . ' Zulu';
    }

    public function departure_icao_data()
    {
        if (!$this->departure_icao) {return null;}

        $output = Cache::remember('events.data.'.$this->departure_icao, 172800, function () {
            $url = 'https://api.flightplandatabase.com/nav/airport/'.$this->departure_icao;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $json = curl_exec($ch);
            error_log('Grabbing info from API');
            Log::info('Grabbing '.$this->departure_icao.' info from API '.date('Y-m-d H:i:s'));
            curl_close($ch);

            return json_decode($json);
        });

        return $output;
    }

    public function arrival_icao_data()
    {
        if (!$this->arrival_icao) {return null;}

        $output = Cache::remember('events.data.'.$this->arrival_icao, 172800, function () {
            $url = 'https://api.flightplandatabase.com/nav/airport/'.$this->arrival_icao;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $json = curl_exec($ch);
            curl_close($ch);
            error_log('Grabbing info from API');
            Log::info('Grabbing '.$this->arrival_icao.' info from API '.date('Y-m-d H:i:s'));
            return json_decode($json);
        });

        return $output;
    }

    public function event_in_past()
    {
        $end = Carbon::create($this->end_timestamp);
        if (!$end->isPast())
        {
            return false;
        }
        return true;
    }

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->description));
    }

    public function userHasApplied()
    {
        if (ControllerApplication::where('event_id', $this->id)->where('user_id', Auth::id())->first()) {
            return true;
        }
        return false;
    }
}
