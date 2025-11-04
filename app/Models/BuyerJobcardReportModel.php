<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerJobcardReportModel extends Model
{
    use HasFactory;
     protected $table='job_card_master';

    protected $primaryKey = 'po_code';
	
	protected $fillable = [
        'po_code', 'po_date','cp_id', 'Ac_code', 'fg_id', 'style_no', 'style_pic_path', 'doc_path1',
        'doc_path_2', 'comment_guidance', 'start_date', 'end_date', 'job_status_id', 'brand_id',
        'season_id',   'rate_per_piece','prod_qty', 'total_amount','ppk_ratio','color_id', 'piece_avg', 'narration', 'userId',  'created_at', 'updated_at','c_code',
    ];

    protected $attributes = [
        'delflag' => 0,
     ];

     protected $casts = [
        'po_code' => 'string'
    ];

}
