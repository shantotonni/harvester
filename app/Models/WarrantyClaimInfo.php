<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaimInfo extends Model
{
    use HasFactory;

    protected $connection = 'WarrantyMonitoringProcess';

    protected $table='WarrantyClaimInfo';
    public $primaryKey = 'Id';
    protected $guarded=[];
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    public function parts()
    {
        return $this->hasMany(PartsDetail::class, 'WarrantyclaiminfoId', 'Id');
    }

}
