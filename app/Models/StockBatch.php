<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBatch extends Model
{
    protected $connection= 'MotorBrInvoiceMirror';

    protected $table = 'StockBatch';
}
