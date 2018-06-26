<?php

namespace App\Enums;

use MabeEnum\Enum;

final class Chronology extends Enum
{
    const PAST = -1;
    const NOW = 0;
    const FUTURE = 1;
}
