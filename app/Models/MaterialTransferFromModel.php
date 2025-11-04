<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTransferFromModel extends Model
{
    use HasFactory;

    protected $table='materialTransferFromMaster';
    protected $primaryKey ='materialTransferFromCode';

	protected $fillable = [
        'materialTransferFromCode', 'materialTransferFromDate','from_loc_id','to_loc_id','driver_name','vehical_no','totalqty','remark','delflag','userId','created_at','updated_at',
    ];

    protected $casts = [
        'materialTransferFromCode' => 'string'
    ];



}
