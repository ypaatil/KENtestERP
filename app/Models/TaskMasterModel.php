<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskMasterModel extends Model
{
    use HasFactory; 

    protected $table='task_master';
    protected $primaryKey = 'task_id';
	
	protected $fillable = [
       'task_id', 'task_date', 'vendorId', 'vpo_code', 'mainstyle_id', 'substyle_id', 'fg_id', 'style_no', 'style_description',
       'item_code', 'table_id', 'table_avg', 'layers', 'part_id','width',  'narration', 'userId', 'delflag', 'endflag', 'created_at', 'updated_at', 'c_code','size_counter'
    ];

    protected $attributes = [
        'delflag' => 0,
         
     ];

     protected $casts = [
        'task_id' => 'string',
        
    ];


}
