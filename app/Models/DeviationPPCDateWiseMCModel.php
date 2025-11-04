<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviationPPCDateWiseMCModel extends Model
{
    use HasFactory;

    protected $table='deviation_ppc_date_wise_mc';
 
	protected $fillable = [
         'deviation_PPC_Master_Id','noOfMC','efficiency','monthDate','vendorId', 'line_id'
    ];

}
