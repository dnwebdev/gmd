<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FinanceOption extends Enum
{
    const status0 = 'need_approval';
    const status1 = 'approved';
    const status2 = 'rejected';
}
