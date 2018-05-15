<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Chronology extends Enum
{
    const Past = -1;
    const Now = 0;
    const Future = 1;
}
