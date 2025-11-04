<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAssociationModel extends Model
{
    use HasFactory;

    protected $table='stock_association';
    protected $primaryKey = 'stockAssociationId';
	
	protected $fillable = [
        'po_code', 'po_date', 'tr_code', 'tr_date', 'bom_code','sales_order_no','cat_id','class_id','item_code','unit_id','qty','tr_type'
    ];

}
