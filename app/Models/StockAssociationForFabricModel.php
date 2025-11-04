<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAssociationForFabricModel extends Model
{
    use HasFactory;

    protected $table='stock_association_for_fabric';
    protected $primaryKey = 'stockAssociationForFabricId';
	
	protected $fillable = [
        'po_code', 'po_date', 'tr_code', 'tr_date', 'bom_code','sales_order_no','cat_id','class_id','item_code','unit_id','qty','tr_type'
    ];

}
