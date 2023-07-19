<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;
    protected $table = 'service_types';
    public $primaryKey = 'id';
    protected $guarded =[];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
