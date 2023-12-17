<?php

namespace App\Models\User;

use Hleb\Constructor\Handlers\Request;
use UserData;
use DB;

class PreferencesModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function get()
    {
		$sql = "SELECT 
                    f.facet_id, 
                    f.facet_slug, 
                    f.facet_title,
                    f.facet_user_id,
                    f.facet_img,
                    f.facet_type,
					fs.signed_facet_id,
					sb.facet_id as facet_output
                        FROM facets f
                           LEFT JOIN facets_signed fs ON fs.signed_facet_id = f.facet_id 
						   LEFT JOIN users_sidebar sb ON f.facet_id = sb.facet_id
                                WHERE fs.signed_user_id = :user_id AND (f.facet_type = 'topic' OR f.facet_type = 'blog')
                                    ORDER BY facet_output DESC LIMIT 20";

        return DB::run($sql, ['user_id' => UserData::getUserId()])->fetchAll();
    }

    public static function edit($data)
    {
		self::removal();	
		
		foreach ($data as $favet_id) {
            DB::run("INSERT INTO users_sidebar(facet_id, user_id, type) VALUES (:facet_id, :user_id, :type)", ['facet_id' => $favet_id, 'user_id' => UserData::getUserId(), 'type' => 1]);
        }
		
		return;
    }

    public static function removal()
    {
		return DB::run("DELETE FROM users_sidebar WHERE user_id = ?", [UserData::getUserId()]);
    }

}
 