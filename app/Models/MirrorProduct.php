<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MirrorProduct extends Model
{
    use HasFactory;

    protected $connection= 'MotorSparePartsMirror';
    protected $table = 'Product';
    public $primaryKey = 'ProductCode';
    protected $keyType = 'string';

}
