<?php

class Telegram
{
    
    // https://api.telegram.org/bot<YourBOTToken>/sendMessage?chat_id=<YourCHATID>&text=<YourMESSAGE>
    // Stub for Telegram
    public static function addWebhook($text, $title, $url)
    {
        $url = 'https://api.telegram.org';
        $token = config('integration.telegram_token');
        $chat_id = config('integration.chat_id');
        
        file_get_contents($url . '/bot'. $token .'/sendMessage?chat_id='. $chat_id .'&disable_notification=true&parse_mode=HTML&text=' . $text); 
                
        
        
      /*  $text = $title . '\n' . $text;
        
        
        $url = 'https://api.telegram.org';
        $webhookurl = $url . '/bot' . config('integration.telegram_token') . '/sendMessage?chat_id=' . config('integration.chat_id') . '&text=' . $text;

        $params  = [
            'parse_mode'    => 'HTML',
        ];  

      //  Curl::index($webhookurl, $params, ['Content-type: application/json']); 
      
      $text = $title . '\n' . $text;
      
      $url = 'https://api.telegram.org';
      $webhookurl = $url . '/bot' . config('integration.telegram_token') . '/sendMessage?chat_id=' . config('integration.chat_id') . '&text=' . $text;
     
     $params  = [
            'parse_mode'    => 'HTML',
        ];
      
     Curl::index($webhookurl, $params, ['Content-type: application/json']); */
      
    }
}
