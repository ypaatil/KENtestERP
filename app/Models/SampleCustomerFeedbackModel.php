<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleCustomerFeedbackModel extends Model
{
    use HasFactory;

    protected $table='sample_customer_feedback_master';
    protected $primaryKey = 'sample_cust_feed_id';
	
	protected $fillable = [
        'sample_cust_feed_id','sample_indent_code','sample_cust_feed_date','Ac_code','mainstyle_id','substyle_id','style_description','sam','sample_type_id','dept_type_id','sz_code','delflag','userId','created_at','updated_at','sample_cad_dept_id','sample_qc_dept_id','cust_feed_status_id','cust_comments'
    ];

    protected $attributes = [
        'delflag' => 0,
     ];
}
