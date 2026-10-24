<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Personal vNAAATS V2 plugin key + Flight Data Display password for certified, active roster members.
 * The key and password are created and stored (hashed) on the vNAAATS server; nothing secret is stored here.
 */
class VnaaatsKeyController extends Controller
{
    private function eligible($user): bool
    {
        $roster = $user->rosterProfile;

        return $roster && $roster->certification === 'certified' && (int) $roster->active === 1;
    }

    private function call(string $what, array $body)
    {
        return Http::withHeaders(['X-Issuer-Secret' => (string) config('vnaaats.issuer_secret')])
            ->timeout(15)
            ->post(config('vnaaats.url').'/admin/member/'.$what, $body);
    }

    public function show()
    {
        $user = auth()->user();
        $eligible = $this->eligible($user);
        $info = null;
        if ($eligible && config('vnaaats.issuer_secret')) {
            try {
                $r = $this->call('info', ['cid' => $user->id]);
                $info = $r->successful() ? $r->json() : null;
            } catch (\Throwable $e) {
                Log::warning('vNAAATS info failed: '.$e->getMessage());
            }
        }

        return view('my.vnaaats', ['eligible' => $eligible, 'info' => $info, 'configured' => (bool) config('vnaaats.issuer_secret')]);
    }

    public function issue()
    {
        $user = auth()->user();
        if (! $this->eligible($user)) {
            return redirect()->route('my.vnaaats')->with('error', 'You need to be an active, certified Gander Oceanic controller to request a vNAAATS key.');
        }
        if (! config('vnaaats.issuer_secret')) {
            return redirect()->route('my.vnaaats')->with('error', 'vNAAATS keys are not set up on this website yet.');
        }
        try {
            $r = $this->call('issue', ['cid' => $user->id, 'name' => $user->fullName('FL')]);
        } catch (\Throwable $e) {
            Log::warning('vNAAATS issue failed: '.$e->getMessage());

            return redirect()->route('my.vnaaats')->with('error', 'The vNAAATS server could not be reached. Please try again in a few minutes.');
        }
        if (! $r->successful()) {
            return redirect()->route('my.vnaaats')->with('error', 'The vNAAATS server refused the request. Please tell the web team.');
        }
        $d = $r->json();

        // Shown once, from the session flash; nothing is kept on this website.
        return redirect()->route('my.vnaaats')->with('vnaaats_new', [
            'key' => $d['key'], 'password' => $d['password'], 'name' => $d['name'], 'cid' => $d['cid'], 'expires' => (int) $d['expires'],
        ]);
    }
}
