<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Module;
use App\Bootstrap\Services\Auth\RegType;
use Modules\Search\Models\SearchModel;
use Modules\Admin\Models\ConsoleModel;

use SendEmail, Msg;

use S2\Rose\Stemmer\PorterStemmerRussian;
use S2\Rose\Stemmer\PorterStemmerEnglish;
use S2\Rose\Entity\Indexable;
use S2\Rose\Indexer;


class ConsoleController extends Module
{
    public static function index()
    {
        $choice  = Request::post('type')->value();
        $allowed = ['css', 'topic', 'post', 'up', 'tl', 'allContents', 'allFacets', 'allIndex', 'newIndex'];
        if (!in_array($choice, $allowed, true)) {
            redirect(url('admin.tools'));
        }
        self::$choice();
    }

    public static function topic()
    {
        ConsoleModel::recalculateTopic();

        self::consoleRedirect();
    }

    public static function post()
    {
        ConsoleModel::recalculateCountCommentPost();

        self::consoleRedirect();
    }

    public static function up()
    {
        $users = ConsoleModel::allUsers();
        foreach ($users as $row) {
            $row['count']   =  ConsoleModel::allUp($row['id']);
            ConsoleModel::setAllUp($row['id'], $row['count']);
        }

        self::consoleRedirect();
    }

    /**
     * If the user has a 1 level of trust (tl) but he has UP > 2, then we raise it to 2
     * Если пользователь имеет 1 уровень доверия (tl) но ему UP > 2, то повышаем до 2
     *
     * @return void
     */
    public static function tl()
    {
        $users = ConsoleModel::getTrustLevel(RegType::USER_FIRST_LEVEL);
        foreach ($users as $row) {
            if ($row['up_count'] > 2) {
                ConsoleModel::setTrustLevel($row['id'], RegType::USER_SECOND_LEVEL);
            }
        }

        self::consoleRedirect();
    }

    public static function testMail()
    {
        $email  = Request::post('mail')->value();
        SendEmail::mailText(1, 'admin.test', ['email' => $email, 'link' => false]);

        Msg::add(__('admin.completed'), 'success');

        redirect(url('admin.tools'));
    }

    public static function css()
    {
        (new \Modules\Admin\Controllers\SassController)->collect();

        self::consoleRedirect();
    }

    public static function consoleRedirect()
    {
        if (PHP_SAPI !== 'cli') {
            Msg::add(__('admin.completed'), 'success');
        }
        return true;
    }

    public static function migrations()
    {
        return true;
    }
	
	public static function allIndex()
	{
		// Удалим и заново построим таблицы при полной индексации
		$storage = SearchModel::PdoStorage();
		$storage->erase(); 
		
		self::allContents();
		self::allFacets();
	}
	
    public static function allContents()
    {
		$indexer = self::indexer();
		 
		// Индексируем контент 
		$contents = SearchModel::getContentsAll(); 
		foreach ($contents as $item) {

			$indexable = new Indexable(
				(string)$item['post_id'], 
				$item['post_title'],
				markdown($item['post_content'], 'text'),
				1 // 1 - статьи
			);

			$url =  post_slug($item['post_type'], $item['post_id'], $item['post_slug']) ?? 'null';

			$indexable->setUrl((string)$url);

			$indexer->index($indexable);
		} 
		 
		self::consoleRedirect(); 
    }
	
    public static function allFacets()
    {
		$indexer = self::indexer();
		 
		// Индексируем Фасеты  
		$facets = SearchModel::getFacetsAll();
		foreach ($facets as $facet) {
			
			$indexableCat = new Indexable(
				(string)$facet['facet_id'],
				$facet['facet_title'],
				$facet['facet_info'] ?? '-',
				2 // 2 - фасеты
			);
			
			$indexableCat->setUrl($facet['facet_slug']);				
			
			$indexer->index($indexableCat);
		}
		 
		self::consoleRedirect(); 
    }
	
    public static function newIndex()
    {
		$indexer = self::indexer();
		 
		$lastId = SearchModel::getLastIDContent();

		$contents = SearchModel::newIndexContent($lastId);
		
		foreach ($contents as $item) {
			
			$indexable = new Indexable(
				(string)$item['post_id'], 
				$item['post_title'],
				markdown($item['post_content'], 'text'),
				1 // 1 - статьи
			);

			$url =  post_slug($item['post_type'], $item['post_id'], $item['post_slug']) ?? 'null';

			$indexable->setUrl((string)$url);			
			
			$indexer->index($indexable);
		}
		 
		self::consoleRedirect(); 
    }
	
	public static function indexer()
	{
		$storage = SearchModel::PdoStorage();
		 
		$stemmer = new PorterStemmerRussian(new PorterStemmerEnglish());
		
		return new Indexer($storage, $stemmer);
	}
	
}
