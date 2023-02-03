<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sub_category extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    public function expenditures() {
        return $this->hasMany('App\Models\Expenditure');
    }
    public function icon() {
        return $this->belongsTo('App\Models\Icon');
    }
}
