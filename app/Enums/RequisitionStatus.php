<?php

namespace App\Enums;

enum RequisitionStatus: string
{

    case Pending = "pending";
    case Approved = "approved";
    case Cancelled = "cancelled";
    case Returned = "returned";
}