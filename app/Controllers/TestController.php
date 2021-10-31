<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;

class TestController
{
    public function index()
    {
         $type = 'mysql';
         if ($type == 'mysql') {
             $qa    =  SearchModel::getSearch(Request::getPost('q'));
             foreach ($qa as $ind => $row) {
                 $html = '<a class="block" href="/post/'. $row['post_id'] .'"> '. $row['post_title'] .'</a>';
                 $html .= '<div class="size-14 gray mb20">'. mb_substr($row['post_content'],0,76,'UTF-8') .'...</div>';
                  echo $html; 
             }
         } elseif ($type == 'json') { 
         
            $qa    =  SearchModel::getSearch(Request::getPost('q'));
            $result_post = [];
            foreach ($qa as $ind => $row) {
                $result_post[$ind]  = $row;
            } 
             
            //$test = json_encode (json_decode ($result_post), JSON_PRETTY_PRINT);
            return json_encode ($result_post, JSON_PRETTY_PRINT); 
             
         } else {
             $qa    =  SearchModel::getSearchPostServer(Request::getPost('q'));
             
             foreach ($qa as $ind => $row) {
                 $html = '<a class="block mt20 size-18" href="/post/'. $row['post_id'] .'"> '. $row['_title'] .'</a>';
                 $html .= '<div class="size-14 gray mb10">'. mb_substr($row['_content'],0,76,'UTF-8') .'...</div>';
                 $html .= '<div class="flex flex-row items-center justify-between mt5 size-14 gray">
                            <a class="flex flex-row items-center black mr15 gray" href="/u/'. $row['user_login'] .'">
                            <img class="w21 mr10" src="/uploads/users/avatars/'. $row['user_avatar'] .'" alt="Adre">'. $row['user_login'] .'</a>
                            <div class="flex flex-row items-center gray size-14 lowercase">
                            <i class="bi bi-heart blue mr5"></i> '. $row['post_votes'] .' <i class="bi bi-eye mr5 ml15"></i> '. $row['post_hits_count'] .' </div></div>';
                 echo $html; 
             }   
         }
          
    }
    
    
 
   
} 