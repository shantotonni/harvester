<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartsDetail extends Model
{
    use HasFactory;

    protected $connection = 'WarrantyMonitoringProcess';

    protected $table='PartsDetail';
    public $primaryKey = 'Id';
    protected $guarded=[];
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    public function warranty_claim()
    {
        return $this->belongsTo(WarrantyClaimInfo::class, 'WarrantyclaiminfoId', 'Id');
    }
}
