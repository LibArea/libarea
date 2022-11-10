<?php

/* 
*  (c) Foma Tuturov
*  https://github.com/phphleb/hleb
*  https://ocrmnolblog.ru/post/36/libarea-dobavlenie-komandy-na-udalenie-neispolzuemyh-izobrazheniy 
*/

namespace App\Commands;

class RemoveUnusedPostImages extends \Hleb\Scheme\App\Commands\MainTask
{
    /** php console remove-unused-post-images [--all] **/

    const DESCRIPTION = "Remove unused images";

    protected function execute($arg = null)
    {

        $allPosts = \DB::run("SELECT * FROM posts")->fetchAll();

        $allAnswers = \DB::run("SELECT * FROM answers")->fetchAll();

        $data = array_merge($allPosts, $allAnswers);

        foreach ($data as $key => $value) {
            $data[$key]['content'] = $value['post_content'] ?? $value['answer_content'];
            $data[$key]['is_deleted'] = $value['post_is_deleted'] ?? $value['answer_is_deleted'];
        }

        if (!$data) {
            echo  PHP_EOL . "Posts and answers not found!" . PHP_EOL;

            return;
        }

        $countPosts = count($data);

        $images = [];

        $imagePath = "/uploads/posts/content/" . date("Y") . "/" . date("n") . "/";
        $dir = HLEB_PUBLIC_DIR . $imagePath;

        if (!file_exists($dir)) {
            echo  PHP_EOL . "Images directory not found!" . PHP_EOL;

            return;
        }

        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $images[] = $imagePath . $file;
                }
            }
        }

        $countImages = count($images);

        if (!$images) {
            echo  PHP_EOL . "Images not found!" . PHP_EOL;

            return;
        }

        foreach ($data as $post) {
            if ($post['content']) {
                foreach ($images as $key => $image) {
                    if ($post["is_deleted"] === 1 && $arg === "--all") {
                        continue;
                    }
                    if (strpos($post['content'], $image) !== false) {
                        unset($images[$key]);
                    }
                }
            }
        }
        // Если удаляются все изображения, то явно что-то пошло не так.
        if (count($images) === $countImages) {
            echo  PHP_EOL . "Something went wrong!" . PHP_EOL;

            return;
        }

        foreach ($images as $key => $image) {
            unlink(HLEB_PUBLIC_DIR . $image);
            unset($images[$key]);
        }

        echo PHP_EOL . "POSTS: $countPosts | IMAGES: $countImages | DELETED: " . ($countImages - count($images)) . PHP_EOL;


        echo PHP_EOL . __CLASS__ . " done." . PHP_EOL;
    }
}
