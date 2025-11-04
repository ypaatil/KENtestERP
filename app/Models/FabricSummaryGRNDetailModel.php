<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FabricSummaryGRNDetailModel extends Model
{
    use HasFactory;

    protected $table='fabric_summary_grn_detail';
 
	protected $fillable = [
       'fsg_code', 'fsg_date','po_code', 'challan_no', 'challan_date', 'invoice_no', 'invoice_date', 'item_code', 'item_rate', 'color_id', 'item_qty'
 
    ];

    protected $casts = [
        'fsg_code' => 'string'
    ];

}
?>