<?php
  
namespace App\Exports;
  
use DB;
use Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\DailyProductionEntryModel; 

  
class EmployeeProductionEntryExport implements FromCollection ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

     protected $fromDate;
     protected $toDate;
     protected $employeeCode; 
     protected $dailyProductionEntryId;
  
    
    public function __construct($fromDate, $toDate,$employeeCode,$dailyProductionEntryId)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->employeeCode = $employeeCode;     
       $this->dailyProductionEntryId =$dailyProductionEntryId;
    }
    
    public function collection()
    {
        
        

        
                $data = DailyProductionEntryModel::select(
                'daily_production_entry.*',
                'usermaster.username',
                'employeemaster_operation.fullName',
                DB::raw('GROUP_CONCAT(DISTINCT daily_production_entry_details.sales_order_no ORDER BY daily_production_entry_details.sales_order_no ASC SEPARATOR ", ") as salesOrders'),
                DB::raw('GROUP_CONCAT(DISTINCT color_master.color_name ORDER BY color_master.color_name ASC SEPARATOR ", ") as colors,GROUP_CONCAT(DISTINCT ob_details.operation_name ORDER BY ob_details.operation_name ASC SEPARATOR ", ") as operation_name'),
                DB::raw('SUM(daily_production_entry_details.stiching_qty) as total_stiching_qty'),
                DB::raw('SUM(daily_production_entry_details.amount) as total_amount')
            )
            ->join('usermaster', 'usermaster.userId', '=', 'daily_production_entry.userId', 'left outer')
            ->join('employeemaster_operation', 'employeemaster_operation.employeeCode', '=', 'daily_production_entry.employeeCode', 'left outer')
            ->leftJoin('daily_production_entry_details', 'daily_production_entry_details.dailyProductionEntryId', '=', 'daily_production_entry.dailyProductionEntryId')
                ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'daily_production_entry_details.sales_order_no')
                ->join('ob_details', function ($join) {
                $join->on('ob_details.operation_id', '=', 'daily_production_entry_details.operationNameId')
                ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
                })
            ->leftJoin('color_master', 'color_master.color_id', '=', 'daily_production_entry_details.color_id');
          
       
       
            if($this->fromDate != "null" && $this->toDate != "null")
            {
            
            $data->whereBetween('daily_production_entry_details.dailyProductionEntryDate', [$this->fromDate,$this->toDate]);
            }
                if($this->employeeCode != "null")
            {
            
            $data->where('daily_production_entry_details.employeeCode',$this->employeeCode);
            }
            
                   if($this->dailyProductionEntryId != "null")
        {
             $data->where('daily_production_entry_details.dailyProductionEntryId', $this->dailyProductionEntryId);
        }

            if(Session::get('user_type')==1)
            {
         
            } else{
                
              $data->where('daily_production_entry.vendorId',Session::get('vendorId'));
            }
            
              $data->where('daily_production_entry.delflag', '=', '0');
              $data->groupBy('daily_production_entry.dailyProductionEntryId', 'usermaster.username', 'employeemaster_operation.fullName');
              $data->orderBy('daily_production_entry.dailyProductionEntryId', 'DESC');   
              $DailyProductionEntryList=$data->get();
        
 	  
 

    // return $data;
     
            $associatedArray = $DailyProductionEntryList->map(function ($item, $index) {
          
            return [
            'Sr No' => $index + 1, 
            'dailyProductionEntryId' => $item->dailyProductionEntryId,
            'dailyProductionEntryDate' => date('d-m-Y', strtotime($item->dailyProductionEntryDate)), 
            'employeeCode' => $item->employeeCode,
            'fullName' => $item->fullName,
            'sales_order_no' => rtrim($item->salesOrders,","), 
            'colors' => rtrim($item->colors,","),
            'operation_name' => rtrim($item->operation_name,","), 
            'total_stiching_qty' => $item->total_stiching_qty,  
            'username' => $item->username,
            ];
            });  
            
            return $associatedArray;

    }


    public function headings(): array
    {
        return ["Sr No","ID","Date","Employee Code","Employee","#KDPL","Colors","Operations","Total Stiching Qty","Username"];
    }


}

?>