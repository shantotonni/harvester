<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MirrorDistrict extends Model
{
    use HasFactory;

    protected $connection = 'MotorBrInvoiceMirror';

    protected $table = 'District';
    public $primaryKey = 'DistrictCode';
    protected $guarded = [];
    protected $keyType = 'string';
}
