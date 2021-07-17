<?php

namespace App\Controllers\Web;

use Hleb\Constructor\Handlers\Request;
use App\Models\WebModel;
use Lori\Base;

class AddWebController extends \MainController
{
    // Добавление домена
    public function index()
    {   
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }

        $link_url           = \Request::getPost('link_url');
        $link_title         = \Request::getPost('link_title');
        $link_content       = \Request::getPost('link_content');

        $parse              = parse_url($link_url);
        $link_url_domain    = $parse['host']; 
        $link_url           = $parse['scheme'] . '://' . $parse['host'];

        $redirect = '/web';
        $link = WebModel::getLinkOne($link_url_domain, $uid['id']);
        if ($link) {
            Base::addMsg(lang('The site is already there'), 'error');
            redirect($redirect);
        } 

        Base::Limits($link_title , lang('Title'), '24', '250', $redirect);
        Base::Limits($link_content, lang('Description'), '24', '1500', $redirect);
        
        $data = [
                    'link_url'          => $link_url,
                    'link_url_domain'   => $link_url_domain,
                    'link_title'        => $link_title,
                    'link_content'      => $link_content,
                    'link_user_id'      => $uid['id'],
                    'link_type'         => 0,
                    'link_status'       => 200,
                    'link_cat_id'       => 1,
                ]; 
                
        WebModel::addLink($data);
        
        redirect($redirect);
    }
    
   // Форма добавление домена
    public function add()
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }
        
        $data = [
            'meta_title'    => lang('Add a website'),
            'sheet'         => 'web-add',
        ]; 

        return view(PR_VIEW_DIR . '/web/add', ['data' => $data, 'uid' => $uid]);
    }

}
