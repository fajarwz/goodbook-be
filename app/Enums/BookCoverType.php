<?php

namespace App\Enums;

enum BookCoverType: int
{
    case HARDCOVER = 1;
    case PAPERBACK = 2;
    case LEATHER_BOUND = 3;

    public function description(): string
    {
        return match($this) 
        {
            self::HARDCOVER => 'Hardcover',   
            self::PAPERBACK => 'Paperback',   
            self::LEATHER_BOUND => 'Leather-bound',
        };
    }
}
