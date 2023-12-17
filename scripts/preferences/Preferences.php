<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User\PreferencesModel;

class Preferences
{
    public static function menu(): array
	{
		return PreferencesModel::get(6);
    }  
}
