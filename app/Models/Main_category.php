<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main_category extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function possessions() {
        return $this->hasMany('App\Models\Possession');
    }
    public function incomes() {
        return $this->hasMany('App\Models\Income');
    }
    public function expenditures() {
        return $this->hasMany('App\Models\Expenditure');
    }
    public function icon() {
        return $this->belongsTo('App\Models\Icon');
    }
}
