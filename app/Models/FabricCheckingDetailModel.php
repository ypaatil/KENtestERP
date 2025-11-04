<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricCheckingDetailModel extends Model
{
    use HasFactory;
    protected $table='fabric_checking_details';
	protected $fillable = [
        'chk_code', 'chk_date','cp_id', 'Ac_code','po_code', 'item_code','part_id',
        'roll_no','old_meter', 'meter','width','kg','shade_id','status_id','defect_id','reject_short_meter','short_meter','extra_meter','shrinkage','track_code','item_rate','rack_id', 'usedflag','is_opening'
    ];
     protected $casts = [
        'chk_code' => 'string',
        ];



}
