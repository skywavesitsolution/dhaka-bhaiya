<?php

namespace App\Enums;

enum StockTransferTypeEnum: string
{
    case IN = 'in';

    case OUT  = 'out';

    case IMPORT  = 'import';

    case ADJUSTMENT  = 'adjustment';
}
