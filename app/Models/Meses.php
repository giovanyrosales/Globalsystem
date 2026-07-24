<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meses extends Model
{
    protected $table = 'meses';
    public $timestamps = false;
    use HasFactory;
}
