<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;

    protected $connection = 'MotorBrInvoiceMirror';

    protected $table = 'InvoiceDetails';
    public $primaryKey = 'Invoiceno';
    protected $guarded =[];
    protected $keyType = 'string';

    public function invoice(){
        return $this->belongsTo(Invoice::class,'Invoiceno','InvoiceNo');
    }
}
