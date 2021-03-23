<?php

function get_avatar_url($uid, $size = 'min')
{
	$uid = intval($uid);
	if (!$uid)
	{
        
        if ($size == 'max' ) {
            $size_user = '150';
        } elseif ($size == 'mid') {
            $size_user = '50';
        } else {
            $size_user = '50';
        }
        
 
     
        return base_url() . '/dev/ava.php?size=' . $size_user . '&hash=' . hash('ripemd160', $uid);
		//return G_STATIC_URL . '/common/avatar-' . $size . '-img.png';
	}
 
	foreach (AWS_APP::config()->get('image')->avatar_thumbnail as $key => $val)
	{
 
		$all_size[] = $key;
	}

	$size = in_array($size, $all_size) ? $size : $all_size[0];
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);

	if (file_exists(get_setting('upload_dir') . '/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, - 2) . '_avatar_' . $size . '.jpg'))
	{
		return get_setting('upload_url') . '/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, - 2) . '_avatar_' . $size . '.jpg';
	}
	else
	{
 
        
        
        if ($size == 'max' ) {
            $size_user = '150';
        } elseif ($size == 'mid') {
            $size_user = '50';
        } else {
            $size_user = '50';
        }
        
 
         
        return base_url()  . '/dev/ava.php?size=' . $size_user . '&hash=' . hash('ripemd160', $uid);
		//return G_STATIC_URL . '/common/avatar-' . $size . '-img.png';
	}
}

//evg для ЛО
function mynum()
{
    return AWS_APP::session()->client_info['__CLIENT_UID'];
}


function http_type(){
	$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
	return $http_type;
}
 
function download_url($file_name, $url)
{
	return get_js_url('/file/download/file_name-' . base64_encode($file_name) . '__url-' . base64_encode($url));
}
 
function xml_parser($str){
    $xml_parser = xml_parser_create();
    if(!xml_parse($xml_parser,$str,true)){
      xml_parser_free($xml_parser);
      return false;
    }else {
      return (json_decode(json_encode(simplexml_load_string($str)),true));
    }
}
// Новая капча?
function human_valid($permission_tag)
{
	if (! is_array(AWS_APP::session()->human_valid))
	{
		return FALSE;
	}

	if (! AWS_APP::session()->human_valid[$permission_tag] or ! AWS_APP::session()->permission[$permission_tag])
	{
		return FALSE;
	}

	foreach (AWS_APP::session()->human_valid[$permission_tag] as $time => $val)
	{
		if (date('H', $time) != date('H', time()))
		{
			unset(AWS_APP::session()->human_valid[$permission_tag][$time]);
		}
	}

	if (sizeof(AWS_APP::session()->human_valid[$permission_tag]) >= AWS_APP::session()->permission[$permission_tag])
	{
		return TRUE;
	}

	return FALSE;
}

 


function summa($summa){
    
    if ($summa > 1001) { 
    
        $tys = floor($summa / 1000);
        $ost = $summa % 1000;
        $ostatok =  floor($ost * 10) / 100;
        $ostatok = ceil($ostatok);
        $itogo = $tys.','.$ostatok;
    
    } else {
        $itogo = $summa;
    }
    return $itogo;
}


function recurse_copy($src,$dst) {  // Исходный каталог, скопированный в каталог
 
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

 
function mb_chunk_split($q,$string, $length, $end='\r\n', $once = false){ 
    // $string = iconv('gb2312', 'utf-8', $string); 
    $array = array(); 
    $strlen = mb_strlen($string); 
    $b=0;
    while($strlen){ 
        $array[] = mb_substr($string, 0, $length, "utf-8"); 
        if($once) 
            return $array[0] . $end; 
        $string = mb_substr($string, $length, $strlen, "utf-8"); 
        $strlen = mb_strlen($string); 
    } 
	 foreach ($array as $key => $value) {
	 	if(stripos($value,$q)){
	 		$b=$key;
	 	    break;
	 	}
	 }
	 $data=array_slice($array,$b,3);	 
    return count($data)>1?implode(" ", $data).'...':implode(" ", $data); 
}   

function substr_text($str, $start=0, $length, $charset="utf-8", $suffix="...")
{
if(function_exists("mb_substr")){
return mb_substr($str, $start, $length, $charset).$suffix;
}
elseif(function_exists('iconv_substr')){
return iconv_substr($str,$start,$length,$charset).$suffix;
}
$re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
preg_match_all($re[$charset], $str, $match);
$slice = join("",array_slice($match[0], $start, $length));
return $slice.$suffix;
}
 
 //используется и для загрузки
function set_human_valid($permission_tag)
{
	if (! is_array(AWS_APP::session()->human_valid))
	{
		return FALSE;
	}

	AWS_APP::session()->human_valid[$permission_tag][time()] = TRUE;

	return count(AWS_APP::session()->human_valid[$permission_tag]);
}
 

function get_user_name_by_uid($uid){
	return AWS_APP::model('account')->get_name($uid);
}
/**
 * 仅附件处理中的preg_replace_callback()的每次搜索时的回调
 * @param  array $matches preg_replace_callback()搜索时返回给第二参数的结果
 * @return string  取出附件的加载模板字符串
 */
function parse_attachs_callback($matches)
{
	if ($attach = AWS_APP::model('publish')->get_attach_by_id($matches[1]))
	{
		TPL::assign('attach', $attach);

		return TPL::output('question/ajax/load_attach', false);
	}
}

function parse_imgs_callback($matches)
{
	if ($matches[1])
	{
		TPL::assign('attach', $matches[1]);
		return TPL::output('question/ajax/load_img', false);
	}
}

 
function get_topic_pic_url($size = null, $pic_file = null)
{
 

	if ($sized_file = AWS_APP::model('topic')->get_sized_file($size, $pic_file)  and file_exists(get_setting('upload_dir') . '/topic/' . $sized_file))
	{
		return get_setting('upload_url') . '/topic/' . $sized_file;
	}

	if (! $size)
	{
		return G_STATIC_URL . '/common/topic-max-img.png';
	}

	return G_STATIC_URL . '/common/topic-' . $size . '-img.png';
}

 
function get_feature_pic_url($size = null, $pic_file = null)
{
	if (! $pic_file)
	{
		return false;
	}
	else
	{
		if ($size)
		{
			$pic_file = str_replace(AWS_APP::config()->get('image')->feature_thumbnail['min']['w'] . '_' . AWS_APP::config()->get('image')->feature_thumbnail['min']['h'], AWS_APP::config()->get('image')->feature_thumbnail[$size]['w'] . '_' . AWS_APP::config()->get('image')->feature_thumbnail[$size]['h'], $pic_file);
		}
	}

	return get_setting('upload_url') . '/feature/' . $pic_file;
}

function get_host_top_domain()
{
	$host = strtolower($_SERVER['HTTP_HOST']);

	if (strpos($host, '/') !== false)
	{
		$parse = @parse_url($host);
		$host = $parse['host'];
	}

	$top_level_domain_db = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me', 'jp', 'uk', 'ws', 'eu', 'pw', 'kr', 'io', 'us', 'cn', 'ru');

	foreach ($top_level_domain_db as $v)
	{
		$str .= ($str ? '|' : '') . $v;
	}

	$matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";

	if (preg_match('/' . $matchstr . '/ies', $host, $matchs))
	{
		$domain = $matchs['0'];
	}
	else
	{
		$domain = $host;
	}

	return $domain;
}

function parse_link_callback($matches)
{
	if (preg_match('/^(?!http).*/i', $matches[1]))
	{
		$url = 'http://' . $matches[1];
	}
	else
	{
		$url = $matches[1];
	}

	if (stristr($url, 'http://%'))
	{
		return false;
	}

	if (stristr($url, 'http://&'))
	{
		return false;
	}

	if (!preg_match('#^(http|https)://(?:[^<>\"]+|[a-z0-9/\._\- !&\#;,%\+\?:=]+)$#iU', $url))
	{
		return false;
	}

	if (is_inside_url($url))
	{
		return '<a href="' . $url . '">' . FORMAT::sub_url($matches[1], 50) . '</a>';
	}
	else
	{
		return '<a href="' . $url . '" rel="nofollow" target="_blank">' . FORMAT::sub_url($matches[1], 50) . '</a>';
	}
}

function parse_link_callback_bbcode($matches)
{
	if (preg_match('/^(?!http).*/i', $matches[1]))
	{
		$url = 'http://' . $matches[1];
	}
	else
	{
		$url = $matches[1];
	}

	if (stristr($url, 'http://%'))
	{
		return false;
	}

	if (stristr($url, 'http://&'))
	{
		return false;
	}

	if (!preg_match('#^(http|https)://(?:[^<>\"]+|[a-z0-9/\._\- !&\#;,%\+\?:=]+)$#iU', $url))
	{
		return false;
	}

	return '[url=' . $url . ']' . FORMAT::sub_url($matches[1], 50) . '[/url]';
}

function is_inside_url($url)
{
	if (!$url)
	{
		return false;
	}

	if (preg_match('/^(?!http).*/i', $url))
	{
		$url = 'http://' . $url;
	}

	$domain = get_host_top_domain();

	if (preg_match('/^http[s]?:\/\/([-_a-zA-Z0-9]+[\.])*?' . $domain . '(?!\.)[-a-zA-Z0-9@:;%_\+.~#?&\/\/=]*$/i', $url))
	{
		return true;
	}

	return false;
}


function get_chapter_icon_url($id, $size = 'max', $default = true)
{
	if (file_exists(get_setting('upload_dir') . '/chapter/' . $id . '-' . $size . '.jpg'))
	{
		return get_setting('upload_url') . '/chapter/' . $id . '-' . $size . '.jpg';
	}
	else if ($default)
	{
		return G_STATIC_URL . '/common/help-chapter-' . $size . '-img.png';
	}

	return false;
}

function base64_url_encode($parm)
{
	if (!is_array($parm))
	{
		return false;
	}

	return strtr(base64_encode(json_encode($parm)), '+/=', '-_,');
}

function base64_url_decode($parm)
{
	return json_decode(base64_decode(strtr($parm, '-_,', '+/=')), true);
}

function remove_assoc($from, $type, $id)
{
	if (!$from OR !$type OR !is_digits($id))
	{
		return false;
	}

	return $this->query('UPDATE ' . $this->get_table($from) . ' SET `' . $type . '_id` = NULL WHERE `' . $type . '_id` = ' . $id);
}
/**
 * 字符串截取，支持中文和其他编码（可去除HTML标签之后再截取）
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @param string $strip_tags 是否去除HTML标签
 * @return string
 */
function chinese_msubstr($str, $start=0, $length=20, $charset="utf-8", $suffix=true,$strip_tags=true) {
    if($strip_tags)
    {
        $str = strip_tags($str);
    }
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
 
    if($suffix==true)
    {
        if(function_exists('mb_strlen'))
        {
            if(mb_strlen($str,$charset)>$length)
                $slice = $slice.'...';
        }
        else
        {$slice = 'mb_strlen!';}
    }
    return  $slice;
}

function getpbtns ($cate, $name = 'children', $pid = 0) {
    $arr = array();
    foreach ($cate as $v) {
        if ($v['parent_id'] == $pid) {
            $v[$name] = getpbtns($cate, $name, $v['topic_id']);
            $arr[] = $v;
        }
    }
    return $arr;
}

	 function foc_topic($my_id, $top_id)
	{
		return $info = AWS_APP::model('account')->fetch_one('topic_focus', 'focus_id', "uid = " . intval($my_id) . " AND topic_id = " . intval($top_id));
	}
/** 
 * 替换fckedit中的图片 添加域名 
 * @param  string $content 要替换的内容 
 * @param  string $strUrl 内容中图片要加的域名 
 * @return string  
 * @eg  
 */  
function replacePicUrl($content = null, $strUrl = null,$osd=0) {  
    if ($strUrl) {  
        //提取图片路径的src的正则表达式 并把结果存入$matches中    
        preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU",$content,$matches);  
        $img = "";    
        if(!empty($matches)) {    
        //注意，上面的正则表达式说明src的值是放在数组的第三个中    
        $img = $matches[2];    
        }else {    
           $img = "";    
        }  
          if (!empty($img)) {    
                $patterns= array();    
                $replacements = array();    
                foreach($img as $imgItem){    
                    $final_imgUrl = $strUrl.$imgItem;    
                    // $final_imgUrl=AWS_APP::model('api')->check_img($final_imgUrl);
                    if($osd==1){
	                	if(strstr($imgItem, 'files_user')){
	                    	$replacements[] = $final_imgUrl;    
	                	}else{
	                    	$replacements[] = $imgItem;    
	                	}
                    		$img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";    
		                    $patterns[] = $img_new;   
                    }elseif($osd==2){
                    		if(strstr($imgItem, 'aliyuncs.com')){
                    			// $imgItem=preg_replace("http:\/\/[^\/]*",'',$imgItem);
                    			$_imgItem=parse_url($imgItem)['path'];
	                    		$replacements[] = $_imgItem;    
		                    	$img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";    
		                    	$patterns[] = $img_new;    

                    		}
                    }else{
	                    	$replacements[] = $final_imgUrl;    
		                    $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";    
		                    $patterns[] = $img_new;    

                    }
                }    
                //让数组按照key来排序    
                ksort($patterns);    
                ksort($replacements);    
    
                //替换内容    
                $vote_content = preg_replace($patterns, $replacements, $content);  
          
                return $vote_content;  
        }else {  
            return $content;  
        }                     
    } else {  
        return $content;  
    }  
}  

 /** 
 * 替换fckedit中的视频 添加域名 
 * @param  string $content 要替换的内容 
 * @param  string $strUrl 内容中图片要加的域名 
 * @return string  
 * @eg  
 */  
function replaceVideoUrl($content = null, $strUrl = null,$osd=false) {  
    if ($strUrl) {  
        //Извлеките регулярное выражение src пути изображения и поместите результат в $ matches    
        preg_match_all('/<video[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/i',$content,$matches);  
        $img = "";    
        if(!empty($matches)) {    
        //Обратите внимание, что в приведенном выше регулярном выражении указано, что значение src помещается в третью часть массива   
        $img = $matches[1];    
        }else {    
           $img = "";    
        }  
          if (!empty($img)) {    
                $patterns= array();    
                $replacements = array();    
                foreach($img as $imgItem){    
                    $final_imgUrl = $strUrl.$imgItem;    
                    // $final_imgUrl=AWS_APP::model('api')->check_img($final_imgUrl);
                    if($osd==1){
	                	if(strstr($imgItem, 'files_user')){
	                    	$replacements[] = $final_imgUrl;    
	                	}else{
	                    	$replacements[] = $imgItem;    
	                	}
                    		$img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";    
		                    $patterns[] = $img_new;   
                    }elseif($osd==2){
                    		if(strstr($imgItem, 'aliyuncs.com')){
                    			// $imgItem=preg_replace("http:\/\/[^\/]*",'',$imgItem);
                    			$_imgItem=parse_url($imgItem)['path'];
	                    		$replacements[] = $_imgItem;    
		                    	$img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";    
		                    	$patterns[] = $img_new;    

                    		}
                    }else{
	                    	$replacements[] = $final_imgUrl;    
		                    $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";    
		                    $patterns[] = $img_new;    

                    }
                }  
    
                //让数组按照key来排序    
                ksort($patterns);    
                ksort($replacements);    
    
                //替换内容    
                $vote_content = preg_replace($patterns, $replacements, $content);  
          
                return $vote_content;  
        }else {  
            return $content;  
        }                     
    } else {  
        return $content;  
    }  
}


function replaceFileUrl($content = null, $strUrl = null,$osd=false) {  
    if ($strUrl) {  
        //提取图片路径的src的正则表达式 并把结果存入$matches中    
        preg_match_all('/<a[^>]*href=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/i',$content,$matches);  
        $img = "";    
        if(!empty($matches)) {    
        //注意，上面的正则表达式说明src的值是放在数组的第三个中    
        $img = $matches[1];    
        }else {    
           $img = "";    
        }  
          if (!empty($img)) {    
                $patterns= array();    
                $replacements = array();    
                foreach($img as $imgItem){    
                    $final_imgUrl = $strUrl.$imgItem;    
                    // $final_imgUrl=AWS_APP::model('api')->check_img($final_imgUrl);
                    if($osd==1){
	                	if(strstr($imgItem, 'files_user')){
	                    	$replacements[] = $final_imgUrl;    
	                	}else{
	                    	$replacements[] = $imgItem;    
	                	}
                    		$img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";    
		                    $patterns[] = $img_new;   
                    }elseif($osd==2){
                    		if(strstr($imgItem, 'aliyuncs.com')){
                    			// $imgItem=preg_replace("http:\/\/[^\/]*",'',$imgItem);
                    			$_imgItem=parse_url($imgItem)['path'];
	                    		$replacements[] = $_imgItem;    
		                    	$img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";    
		                    	$patterns[] = $img_new;    

                    		}
                    }else{
	                    	$replacements[] = $final_imgUrl;    
		                    $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";    
		                    $patterns[] = $img_new;    

                    }
                }  
    
                //让数组按照key来排序    
                ksort($patterns);    
                ksort($replacements);    
    
                //替换内容    
                $vote_content = preg_replace($patterns, $replacements, $content);  
          
                return $vote_content;  
        }else {  
            return $content;  
        }                     
    } else {  
        return $content;  
    }
}

function noXSS($content) {
  
    require_once   (AWS_PATH . 'htmlpurifier/library/HTMLPurifier.auto.php');
    $config = HTMLPurifier_Config::createDefault();
 
    $config->set('AutoFormat', 'AutoParagraph', true);
    //$config->set('HTML', 'Allowed', 'p,ul,li,b,i,a[href],pre,code');
    $config->set('AutoFormat', 'Linkify', true);
    $config->set('HTML', 'Nofollow', true);
    $config->set('Core', 'EscapeInvalidTags', false); //преобразовывать в сущности
     
     
     
    $purifier = new HTMLPurifier($config);
    $clean_html = $purifier->purify($content);
    
    return $clean_html;
}

//https://github.com/taufik-nurrohman/parsedown-extra-plugin
function Parsedown($text) {
  
    require_once   (AWS_PATH . 'parsedown-extra-plugin/test/Parsedown.php');
    require_once   (AWS_PATH . 'parsedown-extra-plugin/test/ParsedownExtra.php');
    require_once   (AWS_PATH . 'parsedown-extra-plugin/ParsedownExtraPlugin.php');
  
  
  
    $Parsedown = new ParsedownExtraPlugin();
    $Parsedown->linkAttributes = array(
        'rel' => 'nofollow noreferrer' 
    );

    $Parsedown->setMarkupEscaped(true);
    $Parsedown->blockCodeAttributesOnParent = true;
 
    $m_html = $Parsedown->text($text);
    
    return $m_html;
}
 
 
function DiscordPHP($text) {
  
    require_once (AWS_PATH . 'DiscordPHP/discord.api.php');
  
  //  > Create a Discord Message with a content, username and avatarURL (you can also '$msg->setTTS( bool )')
$msg = new Discord_Message($text);
$msg->setUsername("QaBot");
$msg->setAvatarURL("https://qamy.ru/static/QaBot.jpg");

//  > Send the message to a Discord webhook
$webhook = new Discord_WebHook("https://discordapp.com/api/webhooks/706903010416132116/c0cdT_e2z9By4p4z319h2PG_KpzePzTcW77SA9xvmXCEKQTgGSnvMlS3TCZ85sgZvFDy");
$webhook->send($msg);
} 