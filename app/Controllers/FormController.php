<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\FormModel;
use Meta, Html;

class FormController extends Controller
{
    // Related posts, content author change, facets 
    // Связанные посты, изменение автора контента, фасеты
    public function index()
    {
        $type       = $this->validateInput(Request::param('type'));
        $search     = Request::post('q')->value();

        return FormModel::get($search, $type);
    }
	
    private function validateInput($input)
    {
        return preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $input->asString());
    }
}
