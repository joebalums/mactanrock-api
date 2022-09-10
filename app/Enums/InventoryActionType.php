<?php

namespace App\Enums;

enum InventoryActionType: string
{
    case Auto = 'auto';
    case Manual = 'manual';
}