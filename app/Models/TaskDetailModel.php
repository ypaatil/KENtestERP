<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDetailModel extends Model
{
    use HasFactory;

    protected $table='task_details';
 
	protected $fillable = [
        'task_id', 'task_date', 
        'vendorId', 'vpo_code', 'mainstyle_id', 'substyle_id', 'fg_id', 'style_no', 'style_description','item_code',
        'table_id', 'style_no', 'table_avg','part_id',    'sz_code', 'ratio'
    ];
 
     protected $casts = [
        'task_id' => 'string',
        
    ];


}
