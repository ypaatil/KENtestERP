<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProductionEntryModel extends Model
{
    use HasFactory; 

    protected $table='daily_production_entry';
    protected $primaryKey = 'dailyProductionEntryId';
	
	protected $fillable = [
       'dailyProductionEntryDate','employeeCode','employeeName','delflag', 'created_at', 'updated_at','userId','vendorId'
    ];

    protected $attributes = [
        'delflag' => 0,
         
    ];


}
 