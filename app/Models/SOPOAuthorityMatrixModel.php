<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SOPOAuthorityMatrixModel extends Model
{
    use HasFactory;
    
    protected $table='so_po_authority_matrix';
    protected $primaryKey = 'so_po_authority_id';     

    
    
    protected $fillable = [
        'so_po_authority_id','so_po_authority_date','sales_order_no','ac_code','brand_id','cat_id','item_code','class_id','bom_qty','level1_percentage','level1_po_qty',
       'level2_percentage','level2_po_qty','level3_percentage','level3_po_qty','userId'];
 
 
  protected $attributes = [
        'is_deleted' => 0,
     ];
     
 
}

