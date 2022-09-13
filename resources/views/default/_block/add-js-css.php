<?php

use Hleb\Constructor\Handlers\Request;

Request::getResources()->addBottomScript('/assets/js/uploads.js');
Request::getResources()->addBottomStyles('/assets/js/editor/easymde.min.css');
Request::getResources()->addBottomScript('/assets/js/editor/easymde.min.js');
Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');
