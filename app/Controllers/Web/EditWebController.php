<?php

namespace App\Controllers\Web;

use Hleb\Constructor\Handlers\Request;
use App\Models\WebModel;
use Lori\Base;

class EditWebController extends \MainController
{
    // Изменение домена
    public function index()
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }
        
        $link_id            = \Request::getPostInt('link_id');
        
        $redirect = '/web';
        if (!$link = WebModel::getLinkId($link_id)) {
            redirect($redirect);
        }
 
        $link_url           = \Request::getPost('link_url');
        $link_title         = \Request::getPost('link_title');
        $link_content       = \Request::getPost('link_content');
        $url_domain         = \Request::getPost('link_domain');
        $link_status        = \Request::getPostInt('link_status');

        Base::Limits($link_title , lang('Title'), '24', '250', $redirect);
        Base::Limits($link_content, lang('Description'), '24', '1500', $redirect);
        
        $data = [
                    'link_id'           => $link['link_id'],
                    'link_url'          => $link_url,
                    'link_url_domain'   => $url_domain,
                    'link_title'        => $link_title,
                    'link_content'      => $link_content,
                    'link_user_id'      => $uid['id'],
                    'link_type'         => 0,
                    'link_status'       => 200,
                    'link_cat_id'       => 1,
                ];
        
        WebModel::editLink($data);
        
        redirect($redirect);
    }
    
   // Форма редактирование домена
    public function edit()
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }
        
        $domain_id  = \Request::getInt('id');
        
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        $domain = WebModel::getLinkId($domain_id);

        $data = [
            'meta_title'    => lang('Change the site') .' | '. $domain['link_url_domain'],
            'sheet'         => 'admin',
        ]; 

        return view(PR_VIEW_DIR . '/web/edit', ['data' => $data, 'uid' => $uid, 'domain' => $domain]);
    }

}
