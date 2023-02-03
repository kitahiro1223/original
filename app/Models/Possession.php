<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Possession extends Model
{
    use HasFactory;

    public function icon() {
        return $this->belongsTo('App\Models\Icon');
    }
    public function main_category() {
        return $this->belongsTo('App\Models\Main_category');
    }
}
