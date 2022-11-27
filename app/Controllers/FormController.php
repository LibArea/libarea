<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;

class FormController extends Controller
{
    private string $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = Request::get('type');
    }

    // GET
    // Pages (forms) adding content / facets
    // Страницы (формы) добавления контента / фасетов
    public function add()
    {
        if (in_array($this->type, ['post'])) {
            return (new Post\AddPostController)->index($this->type);
        }

        if (in_array($this->type, config('facets.permitted'))) {
            return (new Facets\AddFacetController)->index($this->type);
        }

        if ($this->type === 'item') {
            return (new Item\AddItemController)->index();
        }

        self::error404();
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

        if ($this->type === 'item') {
            return (new Item\EditItemController)->index();
        }

        self::error404();
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

        if ($this->type === 'reply') {
            return (new Item\ReplyController)->create();
        }

        if ($this->type === 'item') {
            return (new Item\AddItemController)->create();
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

        if ($this->type === 'reply') {
            return (new Item\ReplyController)->change();
        }

        if ($this->type === 'item') {
            return (new Item\EditItemController)->change();
        }

        return false;
    }
}
