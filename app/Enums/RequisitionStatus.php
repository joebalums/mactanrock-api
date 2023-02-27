<?php

namespace App\Enums;

enum RequisitionStatus: string
{

    case Pending = "pending";
    case Approved = "approved";
    case Accepted = "accepted";
    case Cancelled = "cancelled";
    case Returned = "returned";
    case Completed = "completed";
}