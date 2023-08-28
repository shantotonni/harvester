<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HarvestingCost extends Model
{
    use HasFactory;

    protected $table = 'harvesting_cost';
    public $primaryKey = 'id';
    protected $guarded =[];
    public $timestamps = false;
}
