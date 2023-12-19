<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $connection = 'MotorBrInvoiceMirror';

    protected $table = 'Invoice';
    public $primaryKey = 'InvoiceNo';
    protected $guarded =[];
    protected $keyType = 'string';

    public function customer(){
        return $this->belongsTo(MirrorCustomer::class,'CustomerCode','CustomerCode');
    }
}
