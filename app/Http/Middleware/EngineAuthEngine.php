<?php namespace App\Http\Middleware;

use App\Models\CentralChannel;
use Closure;
use Illuminate\Support\Facades\Session;

class EngineAuthEngine
{

    public function handle($request, Closure $next){
        $channelsRoutes = ['createChannel','deleteChannel','transferDays',null];
        if (!isset($_SERVER['HTTP_CHANNELID'])  && $request->segment(2) != 'channels' && !in_array($request->segment(3), $channelsRoutes)) {
            return \TraitsFunc::ErrorMessage("Channel ID is invalid", 401);
        }

        if (!isset($_SERVER['HTTP_CHANNELTOKEN']) && $request->segment(2) != 'channels' && !in_array($request->segment(3), $channelsRoutes)) {
            return \TraitsFunc::ErrorMessage("Channel Token is invalid", 401);
        }

        if($request->segment(2) != 'channels' && !in_array($request->segment(3), $channelsRoutes)){
            $channelId = $_SERVER['HTTP_CHANNELID'];
            $channelToken = $_SERVER['HTTP_CHANNELTOKEN'];

            $checkChannel = CentralChannel::getOne($channelId);
            if ($checkChannel == null || $checkChannel->token != $channelToken) {
                return \TraitsFunc::ErrorMessage("Invalid Channel, Please Check Your Credentials", 401);
            }
            define('CHANNEL_ID', $checkChannel->instanceId);
            define('CHANNEL_TOKEN', $checkChannel->instanceToken);
            return $next($request);
        }
    }
}
