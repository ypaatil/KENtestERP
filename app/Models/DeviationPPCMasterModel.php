<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviationPPCMasterModel extends Model
{
    use HasFactory;

    protected $table='deviation_ppc_master';

    protected $primaryKey = 'deviation_PPC_Master_Id';
	
	protected $fillable = [
         'vendorId', 'lineNo','noOfMC','efficiency','monthlyPlan', 'day_count', 'userId'
    ];

}
