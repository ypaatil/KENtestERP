<?php
  
namespace App\Exports;
use DB;
use Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
  
class EmployeeDetailedSalaryReportExport implements FromCollection ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $fromDate;
    protected $toDate;
    protected $sales_order_no;
    protected $vendorId;
    protected $employeeCode;
    
    public function __construct($fromDate, $toDate, $sales_order_no, $vendorId, $employeeCode)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->sales_order_no = $sales_order_no;
        $this->vendorId = $vendorId;
        $this->employeeCode = $employeeCode;
    }
    
    public function collection()
    {
        
 	  
 	  
         $filter = DB::table('daily_production_entry_details AS dps')
            ->select(
            'em.fullName',
             'em.employeeCode',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate', 
             'dps.sales_order_no', 
            'ob_details.operation_name',
            'ob_details.rate', 
            'ob_details.sam','dps.dailyProductionEntryId',
            'main_style_master_operation.mainstyle_name','ledger_master.ac_name',
            DB::raw('sum(dps.stiching_qty) as stiching_qty,dps.vendorId,ob_details.rate3,ob_details.rate4,ob_details.rate5,ob_details.rate6'))
            ->join('employeemaster_operation AS em', 'em.employeeCode', '=', 'dps.employeeCode')
            ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
            ->join('main_style_master_operation', 'main_style_master_operation.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation')  
           ->join('ledger_master', 'ledger_master.ac_code', '=', 'dps.vendorId')     
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            });
            
            
        
    
        
        if($this->fromDate != "null" && $this->toDate != "null")
        {
            
            $filter->whereBetween('dps.dailyProductionEntryDate',[$this->fromDate,$this->toDate]);
        }
        
        if($this->sales_order_no != "null")
        {
             
              $filter->where('dps.sales_order_no',$this->sales_order_no);
        }
        
          if($this->vendorId != "null")
        {
             
              $filter->where('dps.vendorId',$this->vendorId);
        }
        
        if($this->employeeCode != "null")
        {
             $filter->where('dps.employeeCode', $this->employeeCode);
        }
        
        if(Session::get('user_type')!=1)
        {
            
        $filter->where('dps.vendorId',Session::get('vendorId')); 
        
        } 
        
        
   
        
        
           
            $filter->groupBy('dps.dailyProductionEntryDate','dps.bundleNo','dps.employeeCode','dps.operationNameId','dps.color_id');
            $data=$filter->get();
            
         
    // return $data;
    
     
            $associatedArray = $data->map(function ($item, $index) {
                
                
                   $rateMap = [
                        115 => 'rate',   
                        110 => 'rate3',  
                        628 => 'rate4',  
                        686 => 'rate5',  
                        113 => 'rate6'  
                        ];
                        
                        
                        
                        
                        $rateKey = $item->vendorId;
                        
                     
                        $rateProperty = $rateMap[$rateKey] ?? 'rate'; 
                        $rate = isset($item->{$rateProperty}) ? $item->{$rateProperty} : $item->rate;  
                            
                          
                            
          
            return [
            'Sr No' => $index + 1,  
            'dailyProductionEntryId' => $item->dailyProductionEntryId,
            'dailyProductionEntryDate' => $item->dailyProductionEntryDate, 
            'ac_name' => $item->ac_name,
            'mainstyle_name' => $item->mainstyle_name,
            'sales_order_no' => $item->sales_order_no,  
            'employeeCode' => $item->employeeCode,
            'fullName' => $item->fullName,
             'operation_name' => $item->operation_name, 
             'stiching_qty' => $item->stiching_qty,
             'rate' => $rate,
             'Amount' => ($item->stiching_qty * $rate)
            ];
            });  
            
            return $associatedArray;

    }


    public function headings(): array
    {
        return ["Sr No","ID","Date","Unit","Style","Order No.","Employee Code","Employee Name","Operation Name","Qty","Rate","Amount"];
    }


}

?>