<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    use HasFactory;

    public function possessions() {
        return $this->hasMany('App\Models\Possession');
    }
    public function incomes() {
        return $this->hasMany('App\Models\Income');
    }
    public function expenditures() {
        return $this->hasMany('App\Models\Expenditure');
    }
    public function main_categories() {
        return $this->hasMany('App\Models\Main_category');
    }
    public function sub_categories() {
        return $this->hasMany('App\Models\Sub_category');
    }
}
