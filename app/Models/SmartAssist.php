<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartAssist extends Model
{
    use HasFactory;

    protected $table = 'smart_assist';
    public $primaryKey = 'chassis_no';

    protected $keyType = 'string';
}
