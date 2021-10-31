<?php

declare(strict_types=1);

namespace Radjax\Src;

class RCreator
{
    private $includeFile = '';

    function __construct(string $include) {
        $this->includeFile = $include;
    }

    public function view() {
        if (file_exists($this->includeFile)) {
            include $this->includeFile;
        }
    }
}

