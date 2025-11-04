<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProductionEntryDetailModel extends Model
{
    use HasFactory; 

    protected $table='daily_production_entry_details'; 
	
	protected $fillable = [
       'dailyProductionEntryId','dailyProductionEntryDate','employeeCode','operationNameId','bundle_track_code','lotNo','sales_order_no', 'bundleNo','slipNo','line_no','stiching_qty','cut_panel_issue_qty','rate','amount','color_id','size_id','vendorId'
    ];

}
 