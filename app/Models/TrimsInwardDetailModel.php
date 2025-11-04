<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimsInwardDetailModel extends Model
{
    use HasFactory;
    
     protected $table='trimsInwardDetail';
    
	protected $fillable = [
        'trimCode','trimDate','Ac_code','item_code','hsn_code','unit_id','item_qty','is_opening','location_id', 'item_rate','amount','rack_id','created_at','updated_at', 'buyer_id','tge_code'];
}
