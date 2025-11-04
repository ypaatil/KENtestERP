<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyBudgetMasterModel extends Model
{
    use HasFactory;


    protected $table='monthly_budget_masters';

    protected $primaryKey = 'monthly_budget_id';
	
    
    protected $fillable = [
    'monthly_budget_id',
    'monthId',
    'year',
    'total_lpc_sale',
    'total_fob_sale',
    'total_rs_cr_sale',
    'total_lmin_sale',
    'total_cmohp',
    'total_lpc_production',
    'total_fob_production',
    'total_rs_cr_production',
    'total_l_min_production',
    'total_cmohp_production',
    'total_l_mtr_purchase_fabric',
    'total_rate_purchase_fabric',
    'total_rs_cr_purchase_fabric',
    'total_days_purchase_fabric',
    'total_rs_cr_purchase_trims',
    'total_days_purchase_trims',
    'total_l_pc_purchase_job_work',
    'total_rate_purchase_job_work',
    'total_rs_cr_job_work',
    'total_l_min_job_work',
    'grand_total_os',
    'total_rs_cr_collection',
    'userId',
    'created_at',
    'updated_at'
];

    

    protected $attributes = [
        'is_deleted' => 0,
     ];  
}
