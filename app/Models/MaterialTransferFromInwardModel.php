<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTransferFromInwardModel extends Model
{
    use HasFactory;

    protected $table='materialTransferFromInward';
    protected $primaryKey ='materialTransferFromInwardCode';

	protected $fillable = [
        'materialTransferFromInwardCode','materialTransferFromCode', 'materialTransferFromInwardDate', 'from_loc_id','to_loc_id','driver_name','vehical_no','totalqty','remark','delflag','userId','created_at','updated_at',
    ];

    protected $casts = [
        'materialTransferFromInwardCode' => 'string'
    ];



}
