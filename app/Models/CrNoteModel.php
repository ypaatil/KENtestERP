<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrNoteModel extends Model
{
    use HasFactory;


  protected $table='cr_note_master';
        protected $primaryKey='sr_no';

	protected $fillable = [
    'CrNote_Code','firm_id','date','tax_type_id','DrCode','CrCode','gst_no','party_ref_no','ag_bill_no','bill_date','hsn_no','narration','basic_amount','cgst_per','cgst_amount','sgst_per','sgst_amount','igst_per','igst_amount','gst_amount','cr_amount','br_id','c_code','created_at','updated_at','branch_id','user_id','ref_date','user_id',
    ];
    
    protected $attributes = [
        'delflag' => 0,
     ];
 

}
