<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIssueMasterModel extends Model
{
    use HasFactory;

    protected $table='material_issue_master';
    protected $primaryKey = 'material_issue_id';
    
    protected $fillable = [
        'vendorId', 'process_date', 'sales_order_no', 'material_type_name', 'process_no', 'order_qty','issue_status_id', 'remark', 'userId','created_at','updated_at'
    ];
}
