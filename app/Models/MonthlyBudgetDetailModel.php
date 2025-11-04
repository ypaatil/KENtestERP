<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyBudgetDetailModel extends Model
{
    use HasFactory;

    protected $table='monthly_budget_details';
    
    
  protected $fillable = [
    'sr_no',
    'monthly_budget_id',
    'monthId',
    'year',
    'Ac_code',
    'lpc',
    'fob',
    'rs_cr',
    'l_min',
    'cmohp',
    'l_mtr',
    'rate',
    'days',
    'total_os',
    'remark',
    'flag',
    'created_at',
    'updated_at'
];


}
