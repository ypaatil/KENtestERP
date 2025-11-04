<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardModel extends Model
{
    use HasFactory;

    protected $table='dashboard_master';
    protected $primaryKey = 'db_id';
	
	protected $fillable = [
        'BK_VOL_TD_P','BK_VOL_M_TO_Dt_P', 'BK_VOL_Yr_TO_Dt_P','BK_VAL_TD_P','BK_VAL_M_TO_Dt_P','BK_VAL_Yr_TO_Dt_P','SAL_VOL_TD_P','SAL_VOL_M_TO_Dt_P','SAL_VOL_Yr_TO_Dt_P','SAL_VAL_TD_P','SAL_VAL_M_TO_Dt_P','SAL_VAL_Yr_TO_Dt_P','BOK_SAH_TD_P','BOK_SAH_M_TO_Dt_P','BOK_SAH_Y_TO_Dt_P','SAL_SAH_TD_P','SAL_SAH_M_TO_Dt_P','SAL_SAH_Yr_TO_Dt_P','userId','created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
