<?php

// Localization
function lang($text){ 
    if(isset(LANG[$text])){ 
        return LANG[$text];
    }
    return $text;
}

// User's Avatar
function user_avatar_url($file, $size) 
{
    if($size == 'small') {  
        return '/uploads/users/avatars/small/' . $file;
    } 
    return '/uploads/users/avatars/' . $file;
}

// User's Cover art
function user_cover_url($file) 
{
    return '/uploads/users/cover/' . $file;
}

// Space Logo
function spase_logo_url($file, $size='small') 
{
    if($size == 'small') {
        return '/uploads/spaces/logos/small/' . $file;
    } 
    return  '/uploads/spaces/logos/' . $file;
}


// Favicon 
function favicon_url($link_id) 
{
    if(file_exists(HLEB_PUBLIC_DIR. '/uploads/favicons/' . $link_id . '.png')) {
        return '/uploads/favicons/' . $link_id . '.png';
    }
    return '/uploads/favicons/no-link.png';
}

// Localization of dates and events....
function lang_date($string) 
{
    $monn = array(
        '',
        lang('january'),
        lang('february'),
        lang('martha'),
        lang('april'),
        lang('may'),
        lang('june'),
        lang('july'),
        lang('august'),
        lang('september'),
        lang('october'),
        lang('november'),
        lang('december')
    );
    //Разбиваем дату в массив
    $a = preg_split('/[^\d]/',$string); 
    
    $today = date('Ymd');  //20210421
    if(($a[0].$a[1].$a[2])==$today) {
        //Если сегодня
        return(lang('Today').' '.$a[3].':'.$a[4]);

    } else {
            $b = explode('-',date("Y-m-d"));
            $tom = date('Ymd',mktime(0,0,0,$b[1],$b[2]-1,$b[0]));
            if(($a[0].$a[1].$a[2])==$tom) {
            //Если вчера
            return(lang('Yesterday').' '.$a[3].':'.$a[4]);
        } else {
            //Если позже
            $mm = intval($a[1]);
            return($a[2]." ".$monn[$mm]." ".$a[0]." ".$a[3].":".$a[4]);
        }
    }
}

// Declensions
function word_form($num, $form_for_1, $form_for_2, $form_for_5){
    $num = abs($num) % 100; 
    $num_x = $num % 10; 
    if ($num > 10 && $num < 20)   // отрезок [11;19]
        return $form_for_5;
    if ($num_x > 1 && $num_x < 5) //  2,3,4
        return $form_for_2;
    if ($num_x == 1)              // оканчивается на 1
        return $form_for_1;
    return $form_for_5;
}
