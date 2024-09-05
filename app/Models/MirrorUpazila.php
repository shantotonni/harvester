<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MirrorUpazila extends Model
{
    use HasFactory;

    protected $connection = 'MotorBrInvoiceMirror';

    protected $table = 'Upazilla';
    public $primaryKey = 'UpazillaCode';
    protected $guarded = [];
    protected $keyType = 'string';
}
