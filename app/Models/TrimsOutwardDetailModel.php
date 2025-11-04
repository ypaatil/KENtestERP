<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimsOutwardDetailModel extends Model
{
    use HasFactory;
    
    
  protected $table='trimsOutwardDetail';
    
	protected $fillable = [
        'trimOutCode','tout_date','out_type_id','vendorId','trim_type','vpo_code','vw_code','sample_indent_code','po_code','item_code','hsn_code','unit_id','item_qty','item_rate','created_at','updated_at', 'buyer_id','ship_to'];
    
    
}
