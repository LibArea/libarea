<?php

namespace App\Controllers;
use App\Models\TagModel;
use Hleb\Constructor\Handlers\Request;
use Base;

class TagController extends \MainController
{

    // Все теги сайта
    public function index()
    {


       /* $result = Array();
        foreach($query->getResult()as $ind => $row){

            $row->name        = $row->tags_name;
            $row->slug        = $row->tags_slug;
            $row->description = $row->tags_description;
            $row->tip         = $row->tags_tip;
            $result[$ind]          = $row;
         
        } */

        $data = [
            'tags'  => TagModel::getTagHome(),
            'title' => 'Теги сайта',
            'msg'   => Base::getMsg(),
        ];

        return view("tag/all", ['data' => $data]);
    }

    // Посты по тегу
    public function tagPosts()
    {
 
        $tag = Request::get('tag');
 
        $posts = TagModel::getTagPosts($tag);
 
        // Покажем 404
        if(!$posts) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $result = Array();
        foreach($posts as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar']  = 'noavatar.png';
            }  
            $row['avatar']        = $row['avatar'];
            $row['post_votes']    = $row['post_votes'];
            $row['date']          = $row['post_date'];
            $row['num_comments']  = $row['post_comments']; 
            $row['post_comments'] = Base::ru_num('comm', $row['post_comments']);
            $result[$ind]         = $row;
         
        }  


        $data = [
            'posts' => $result,
            'tag'   => $tag,
            'title' => 'Посты по тегу',
            'msg'   => Base::getMsg(),
        ];

 
        return view("tag/tagposts", ['data' => $data]);
        
    }

    // Изменение тега
    public function tagForma()
    {
        
        $data = [
            'tags' => TagModel::getTagsHome(),
            'msg'   => Base::getMsg(),
        ]; 
 
        return view("tag/formatag", ['data' => $data]);
        
    }

}
