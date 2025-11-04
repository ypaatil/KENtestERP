<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POAuthorityMatrixModel extends Model
{
    use HasFactory;
    
    protected $table='po_authority_matrix';
    protected $primaryKey = 'po_authority_id';       
    
    
    protected $fillable = [
        'po_authority_id','po_authority_date','ac_code','brand_id','order_qty','shipping_allowance','fabric_extra_order','level1_sewing_trim_extra_order','level1_packing_trim_extra_order',
        'remarks','level2_sewing_trim_extra_order','level2_packing_trim_extra_order','level3_sewing_trim_extra_order','level3_packing_trim_extra_order','userId'];
 
 
  protected $attributes = [
        'is_deleted' => 0,
     ];
     
 
}

