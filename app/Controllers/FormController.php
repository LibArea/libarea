<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\ActionModel;
use Html, UserData;

class FormController extends Controller
{
    private string $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = Request::get('type');
    }

    // GET
    // Pages (forms) adding content (facets) 
    // Страницы (формы) добавления контента (фасетов)
    public function add()
    {
        if (in_array($this->type, ['post', 'page'])) {
            return (new Post\AddPostController)->index($this->type);
        }

        if (in_array($this->type, ['topic', 'blog', 'category', 'section'])) {
            return (new Facets\AddFacetController)->index($this->type);
        }

        return false;
    }

    // GET
    // Pages (forms) change content and facets (navigation)
    // Страницы (формы) изменения контента и фасетов (навигации)
    public function edit()
    {
        if (in_array($this->type, ['post', 'page'])) {
            return (new Post\EditPostController)->index($this->type);
        }

        if (in_array($this->type, ['topic', 'blog', 'category', 'section'])) {
            return (new Facets\EditFacetController)->index($this->type);
        }

        if ($this->type === 'answer') {
            return (new Answer\EditAnswerController)->index();
        }

        return false;
    }

    // POST
    // Creating Content
    // Создание контента
    public function create()
    {
        if (in_array($this->type, ['post', 'page'])) {
            return (new Post\AddPostController)->create($this->type);
        }

        if (in_array($this->type, ['topic', 'blog', 'category', 'section'])) {
            return (new Facets\AddFacetController)->create($this->type);
        }

        if ($this->type === 'answer') {
            return (new Answer\AddAnswerController)->create();
        }

        if ($this->type === 'comment') {
            return (new Comment\AddCommentController)->create();
        }

        if ($this->type === 'invitation') {
            return (new User\InvitationsController)->create();
        }

        if ($this->type === 'message') {
            return (new MessagesController)->create();
        }

        if ($this->type === 'folder') {
            return (new FolderController)->create();
        }

        if ($this->type === 'team') {
            return (new \Modules\Team\App\Add)->create();
        }

        if ($this->type === 'reply') {
            return (new \Modules\Catalog\App\Reply)->create();
        }

        if ($this->type === 'item') {
            return (new \Modules\Catalog\App\Add)->create();
        }

        return false;
    }

    // POST
    // Content changes
    // Изменения контента
    public function change()
    {
        if (in_array($this->type, ['post', 'page'])) {
            return (new Post\EditPostController)->change($this->type);
        }

        if (in_array($this->type, ['topic', 'blog', 'category', 'section'])) {
            return (new Facets\EditFacetController)->change($this->type);
        }

        if ($this->type === 'answer') {
            return (new Answer\EditAnswerController)->change();
        }

        if ($this->type === 'comment') {
            return (new Comment\EditCommentController)->change();
        }

        if ($this->type === 'team') {
            return (new \Modules\Team\App\Edit)->change();
        }

        if ($this->type === 'reply') {
            return (new \Modules\Catalog\App\Reply)->change();
        }

        if ($this->type === 'item') {
            return (new \Modules\Catalog\App\Edit)->change();
        }

        return false;
    }
}
