<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MirrorCustomer extends Model
{
    use HasFactory;

    protected $connection = 'MotorBrInvoiceMirror';

    protected $table = 'Customer';
    public $primaryKey = 'CustomerCode';
    protected $guarded = [];
    protected $keyType = 'string';
}
