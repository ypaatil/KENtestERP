<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrNoteModel extends Model
{
    use HasFactory;

    protected $table='dr_note_master';
        protected $primaryKey='sr_no';

	protected $fillable = [
    'DrNote_Code','firm_id','date','tax_type_id','CrCode','DrCode','gst_no','party_ref_no','ag_bill_no','bill_date','hsn_no','narration','basic_amount','cgst_per','cgst_amount','sgst_per','sgst_amount','igst_per','igst_amount','gst_amount','dr_amount','br_id','c_code','created_at','updated_at','branch_id','user_id',
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];

}
