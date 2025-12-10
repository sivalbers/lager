<?php

namespace App\Repositories;

use App\Models\Abladestelle;


class AbladestelleRepository
{

    public static function existiertAbladestelleName(string $name): bool
    {
        return Abladestelle::whereRaw('LOWER(name) = ?', [mb_strtolower(trim($name))])
            ->exists();
    }


}
