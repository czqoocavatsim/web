<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Community\Discord\DiscordBan;
use App\Models\Users\User;
use App\Notifications\Discord\BanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use NotificationChannels\Discord\DiscordChannel;
use RestCord\DiscordClient;

class DiscordController extends Controller
{
    public function createDiscordBan(Request $request)
    {
        //Define validator messages
        $messages = [
            'reason.required' => 'Please provide a ban reason.',
            'start_time.required' => 'Please provide a ban start time',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'start_time' => 'required',
            'user_id' => 'required'
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->back()->withInput()->withErrors($validator, 'createDiscordBanErrors');
        }

        //Create discord client
        $discord = new DiscordClient(['token' => config('services.discord.token')]);

        //Get the user object
        $user = User::whereId($request->get('user_id'))->first();

        //Create the ban
        $ban = new DiscordBan([
            'user_id' => $user->id,
            'moderator_id' => Auth::id(),
            'reason' => $request->get('reason'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'discord_id' => $user->discord_user_id
        ]);

        $ban->save();

        //Notify user via bot and email
        $user->notify(new BanNotification($user, $ban), [DiscordChannel::class, 'mail']);

        dd($ban);
    }
}
