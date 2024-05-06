<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Panoscape\History\HasHistories;

class Branch extends Model
{
    use HasFactory, SoftDeletes;
    use HasHistories;

    public function getModelLabel()
    {
        return $this->display_name;
    }

    protected  $guarded = [];
}
