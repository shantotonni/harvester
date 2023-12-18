<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserArea extends Model
{
    use HasFactory;

    protected $table = 'user_areas';
    //public $primaryKey = 'InvoiceNo';
    protected $guarded =[];
    protected $keyType = 'string';
}
