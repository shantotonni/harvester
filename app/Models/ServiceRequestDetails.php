<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequestDetails extends Model
{
    protected $fillable=["job_card_id","user_id"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function job_card(){
        return $this->belongsTo('App\Models\JobCard');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
