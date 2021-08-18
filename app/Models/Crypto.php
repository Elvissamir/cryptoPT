<?php

namespace App\Models;

use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Crypto extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function portfolios()
    {
        return $this->belongsToMany(Portfolio::class)->withPivot('amount')->withTimestamps();
    }

}
