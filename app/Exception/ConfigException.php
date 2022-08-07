<?php

declare(strict_types=1);

namespace App\Exception;

class ConfigException extends \Exception
{
    public static function NotFoundException(string $file): self
    {
        return new self("Configuration file does not exist: `{$file}`.");
    }
}
