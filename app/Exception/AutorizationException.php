<?php

declare(strict_types=1);

namespace App\Exception;

class AutorizationException extends \Exception
{
    public static function Smtp(string $value): self
    {
        return new self("Send (smtp) error: `{$value}`.");
    }
}
