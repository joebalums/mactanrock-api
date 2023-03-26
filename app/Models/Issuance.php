<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issuance extends Model
{
    use HasFactory;
    
    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IssuanceDetail::class);
    }

    public function requester(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function acceptor()
    {
        return $this->belongsTo(User::class, 'accepted_by_id');
    }

    public function location()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }

}
