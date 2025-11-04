<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuttingEntryDetailModel extends Model
{
    use HasFactory; 

    protected $table='cutting_entry_details'; 
    protected $primaryKey = 'srno';
	
	protected $fillable = [
       'cuttingEntryDate','bundleNo','slipNo','lotNo','size','cut_panel_issue_qty','color_id','vendorId'
    ];

}
 