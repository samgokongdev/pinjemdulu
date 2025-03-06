<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $guarded = [
        'id'
    ];

    public function asets(): HasOne
    {
        return $this->hasOne(Aset::class);
    }
}
