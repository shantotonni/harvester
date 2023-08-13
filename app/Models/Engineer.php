<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engineer extends Model
{
    protected $fillable=["date","user_id","report_actual","app_dashboard_actual","team_coordination_actual"];
    protected $dateFormat = 'Y-m-d H:i:s.v';

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

}
