<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POTypeModel extends Model
{
    use HasFactory;

    protected $table='po_type_master';
    protected $primaryKey = 'po_type_id';
	
	protected $fillable = [
       'po_type_name','description', 'userId', 'created_at','updated_at',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
