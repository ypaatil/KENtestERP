<?php
  
namespace App\Exports;
  
use DB;
use Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
  
class FGSTOCKExport implements FromCollection ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    
    
    public function __construct()
    {
         
    }
    
    
    
    public function collection()
    {

            
           $FinishedGoodsStock = DB::table('packing_inhouse_size_detail2')
            ->select('Ac_name','sales_order_no', 'packing_inhouse_size_detail2.style_no','order_rate','color_master.color_name','brand_master.brand_name', 
             'size_detail.size_name',DB::raw('ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as packing_grn_qty'),
             'mainstyle_name', 'job_status_master.job_status_name',DB::raw('(SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
            inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
            where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
            carton_packing_inhouse_size_detail2.sales_order_no=buyer_purchse_order_master.tr_code 
            and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
            and carton_packing_inhouse_master.endflag=1
 
            ) as carton_pack_qty'))
            ->leftJoin('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','packing_inhouse_size_detail2.sales_order_no')
            ->leftJoin('ledger_master','ledger_master.ac_code','=','packing_inhouse_size_detail2.Ac_code')
            ->leftJoin('job_status_master','job_status_master.job_status_id','=','buyer_purchse_order_master.job_status_id') 
            ->leftJoin('brand_master','brand_master.brand_id','=','buyer_purchse_order_master.brand_id')  
            ->leftJoin('color_master','color_master.color_id','=','packing_inhouse_size_detail2.color_id')    
            ->leftJoin('size_detail','size_detail.size_id','=','packing_inhouse_size_detail2.size_id')      
            ->leftJoin('main_style_master','main_style_master.mainstyle_id','=','packing_inhouse_size_detail2.mainstyle_id')  
            ->groupBy('buyer_purchse_order_master.tr_code')
            ->groupBy('packing_inhouse_size_detail2.color_id')
            ->groupBy('packing_inhouse_size_detail2.size_id')
            ->get();
            
 
     

     return $FinishedGoodsStock;


    }


    public function headings(): array
    {
        return ["Ac_name","sales_order_no","style_no","order_rate","color","brand","size","packing","mainstyle_name","Job","carton_pack_qty"];
    }


}

?>