<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function icon() {
        return $this->belongsTo('App\Models\Icon');
    }
    public function main_category() {
        return $this->belongsTo('App\Models\Main_category');
    }
    public function sub_category() {
        return $this->belongsTo('App\Models\Sub_category');
    }
}
