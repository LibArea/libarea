<?php

// Localization
function lang($text) { 
    if (isset(LANG[$text])) { 
        return LANG[$text];
    }
    return $text;
}

// Topic for posts
function html_topic($topic, $css) 
{
    if (!$topic) { return ''; }
 
    if (!is_array($topic)){
        $topic = preg_split('/(@)/', $topic);
    }
 
    $result = Array();
    foreach (array_chunk($topic, 2) as $ind => $row) {
        $result[] = '<a class="'. $css .'" href="/topic/'. $row[0] .'">'. $row[1] .'</a>';
    }
    return implode($result);
}

// Topic logo img
function topic_logo_img($file, $size, $alt, $style)
{
    $src = '/uploads/topics/' . $file;
    if ($size == 'small') {  
        $src = '/uploads/topics/small/' . $file;
    } 
    
    $img = '<img class="'.$style.'" src="'.$src.'" alt="'.$alt.'">';
    
    return $img;
}

// Space logo img
function spase_logo_img($file, $size='small', $alt, $style) 
{
    $src = '/uploads/spaces/logos/' . $file;
    if ($size == 'small') {
        $src = '/uploads/spaces/logos/small/' . $file;
    }
    
    $img = '<img class="'.$style.'" src="'.$src.'" alt="'.$alt.'">';
    
    return $img;
}

// User's Avatar
function user_avatar_img($file, $size='small', $alt, $style) 
{
    $src = '/uploads/users/avatars/' . $file;
    if ($size == 'small') {  
        $src = '/uploads/users/avatars/small/' . $file;
    }
    
    $img = '<img class="'.$style.'" src="'.$src.'" alt="'.$alt.'">';
    
    return $img;
}

// The cover of the post
function post_cover_img($file, $alt, $style) 
{
    $src = '/uploads/posts/cover/' . $file;
    $img = '<img class="'.$style.'" src="'.$src.'" alt="'.$alt.'">';
    
    return $img;
}

// User's Cover art
function user_cover_url($file) 
{
    return '/uploads/users/cover/' . $file;
}

// Favicon 
function favicon_img($link_id, $alt) 
{
    if (file_exists(HLEB_PUBLIC_DIR. '/uploads/favicons/' . $link_id . '.png')) {
        $img = '<img class="favicon" src="/uploads/favicons/'.$link_id.'.png" alt="'.$alt.'">';
        return $img;
    }

    $img = '<img class="favicon" src="/uploads/favicons/no-link.png" alt="'.$alt.'">';
    return $img;
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
    if (($a[0].$a[1].$a[2])==$today) {
        //Если сегодня
        return(lang('Today').' '.$a[3].':'.$a[4]);

    } else {
            $b = explode('-',date("Y-m-d"));
            $tom = date('Ymd',mktime(0,0,0,$b[1],$b[2]-1,$b[0]));
            if (($a[0].$a[1].$a[2])==$tom) {
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
function word_form($num, $form_for_1, $form_for_2, $form_for_5) {
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

//******************** Доступ ********************//

// Права для TL
// $trust_leve - уровень доверие участника
// $allowed_tl - с какого TL разрешено
// $count_content - сколько уже создал
// $count_total - сколько разрешено
function validTl($trust_level, $allowed_tl, $count_content, $count_total) {
    
    if ($trust_level < $allowed_tl) {
        return false;
    }
    
    if ($count_content >= $count_total) {
        return false;
    }  

    return true;
}
    
// Отправки личных сообщений (ЛС)
// $uid - кто отправляет
// $user_id - кому
// $add_tl -  с какого уровня доверия
function accessPm($uid, $user_id, $add_tl)
{
    // Запретим отправку себе
    if ($uid['id'] == $user_id) {
        return false;
    }

    // Если уровень доверия меньше установленного
    if ($add_tl > $uid['trust_level']) {
        return false;
    }
    
    return true;
}    
    
// Проверка доступа
// $content
// $type -  post / answer / comment
// $after - есть ли ответы
// $stop_time - разрешенное время
function accessСheck($content, $type, $uid, $after, $stop_time)
{
    if (!$content) {
        return false;
    }
      
    // Редактировать может только автор и админ
    if ($content[$type . '_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
        return false;
    }
 
    // Запретим удаление если есть ответ
    // И если прошло 30 минут
    if ($uid['trust_level'] != 5) {
        
        if ($after > 0) {
            if ($content[$type . '_after'] > 0) {
                return false;
            }
        }
              
        if ($stop_time > 0) { 
            $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($content[$type . '_date']);
            $time = floor($diff / 60);
          
            if ($time > $stop_time) {
                return false;
            }

        }   
    }

    return true;
}

function pagination($pNum, $pagesCount, $sheet, $other)
{
    if ($pNum > $pagesCount) {
        return null;
    }

    $other = empty($other) ? '' : $other;

    if ($sheet == 'all' || $sheet == 'top' ) {
        $page = $other . '/' . $sheet;
    } else {
        $page = $other . '';
    }

    $html = '<div class="pagination">';
    
    if ($pNum != 1) {  
        if (($pNum - 1) == 1) { 
             $html .= '<a class="link" href="'. $page .'"> << '.lang('Page').' '. ($pNum - 1) .'</a>';
        } else {
             $html .= '<a class="link" href="'. $page .'/page/'.($pNum - 1).'"> << '. lang('Page') .' '. ($pNum - 1) .'</a>';
        }
    } 
    
    if ($pagesCount != $pNum && $pNum != 1) { 
        $html .= ' | ';
    }
    
    if ($pagesCount > $pNum) { 
        $html .= '<a class="link" href="'. $page .'/page/'. ($pNum + 1) .'">'. lang('Page') .' '. ($pNum + 1) .' >></a>';
    }

    $html .= '</div>';
        
    return $html; 
}

function breadcrumb($path_home, $title_home, $path_intermediate, $title_intermediate, $title_page)
{
    
    $html = '<ul class="breadcrumb">';
    $html .= '<li class="breadcrumb-item">
                <a title="'. $title_home .'" href="'. $path_home .'">'. $title_home .'</a>
                </li>';
    
    if ($path_intermediate) {
        $html .= '<li class="breadcrumb-item">
                    <a title="'. $title_intermediate .'" href="'. $path_intermediate .'">'. $title_intermediate .'</a>
                    </li>';
    }
    
    $html .= '<li class="breadcrumb-item">
                <span class="red">'.$title_page.'</span>
                </li>
                </ul>
                <h1>'.$title_page.'</h1>';       

    return $html;
}

function votes($user_id, $content, $type)
{
    $html = '';
    if ($user_id > 0) {
        
        $count = '';
        if ($content[$type.'_votes'] > 0 ) {
            $count = '+'.$content[$type.'_votes'];
        }
        
        if ($content['votes_'.$type.'_user_id'] || $user_id == $content[$type . '_user_id']) { 
            $html .= '<div class="voters active">
                        <div class="up-id"></div>
                        <div class="score">
                            '. $count .'
                        </div></div>';
        } else { 
            $html .= '<div id="up' . $content[$type.'_id'].'" class="voters">
                        <div data-id="'.$content[$type.'_id'].'" data-type="'.$type.'" class="up-id"></div>
                        <div class="score">
                            '. $count .'
                        </div></div>';
        } 
        
    } else {    
        $html .= '333<div class="voters">
                    <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                    <div class="score">
                        +'.$content[$type . '_count'].'                
                    </div></div>';
    }

    return $html;
}