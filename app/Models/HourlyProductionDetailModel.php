<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourlyProductionDetailModel extends Model
{
    use HasFactory;

    protected $table='hourly_production_entry_details';
    protected $primaryKey = 'sr_no';
    
    
    protected $fillable = [
        'hourlyProductionId','hourlyEntryDate','sub_company_id','mainstyle_id','dept_id','employeeCode','operationNameId','operation_type','nine_ten','ten_eleven','eleven_twelve','twelve_one','oneThirty_twoThirty','twoThirty_threeThirty','threeThirty_fourefourty','fourefourty_fiveFourty','total_output','remark','other_remark','nine_ten_down_time_min', 'nine_ten_reason', 'ten_eleven_down_time_min', 'ten_eleven_reason', 'eleven_twelve_down_time_min', 'eleven_twelve_reason', 'twelve_one_dtm', 'twelve_one_reason', 'oneThirty_twoThirty_dtm', 'oneThirty_twoThirty_reason', 'twoThirty_threeThirty_dtm', 'twoThirty_threeThirty_reason', 'threeThirty_fourefourty_dtm', 'threeThirty_fourefourty_reason', 'fourefourty_fiveFourty_dtm', 'fourefourty_fiveFourty_reason','created_at','updated_at'];
 
      }

