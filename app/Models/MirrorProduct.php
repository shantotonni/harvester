<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MirrorProduct extends Model
{
    use HasFactory;

    protected $connection= 'MotorBrInvoiceMirror';

    protected $table = 'product';
    public $primaryKey = 'ProductCode';
    protected $keyType = 'string';

    public function MirrorProducts(){
        return $this->hasMany(MirrorProduct::class,'ProductCode','ProductCode');

    }
}
