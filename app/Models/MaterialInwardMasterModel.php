<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialInwardMasterModel extends Model
{
    use HasFactory;

    protected $table='materialInwardMaster';
    protected $primaryKey = 'sr_no';


	protected $fillable = [ 'materiralInwardCode','po_code', 'materiralInwardDate', 'is_opening','invoice_no','invoice_date','Ac_code','po_type_id','totalqty','total_amount','remark','is_opening','location_id','delflag','userId','created_at','updated_at'];
    
    protected $attributes = [
        'delflag' => 0,
     ];
     

}
