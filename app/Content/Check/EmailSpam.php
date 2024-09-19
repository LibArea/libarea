<?php

declare(strict_types=1);

namespace App\Content\Сheck;

use StopSpam\Request;
use StopSpam\Query;

class EmailSpam
{
    public static function index(string $email) :bool
    {
        if (config('integration', 'stopforumspam') === false) {
            return false;
        }
        
        $query = new Query();
        $query->addEmail($email);

        $request = new Request();
        $response = $request->send($query);
        $item = $response->getFlowingEmail();
        
        return $item->isAppears();
    }
}
