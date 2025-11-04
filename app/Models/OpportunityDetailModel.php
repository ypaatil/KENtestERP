<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunityDetailModel extends Model
{
    use HasFactory;
    protected $table='opportunity_details';
    protected $primaryKey = 'opportunity_detail_id';
	protected $fillable = [
        'opportunity_id', 'main_style_id','style_name', 'style_description','product_image','product_url','gender_id','fabric_details',
        'size_range','sam','quantity','cur_id','fob_rate','exchange_rate','fob_rate_inr','CM','OH','P','CMOHP_value','CMOHP_min','total_amount_inr','total_minutes','opportunity_stage_id','remark'
    ]; 


}
