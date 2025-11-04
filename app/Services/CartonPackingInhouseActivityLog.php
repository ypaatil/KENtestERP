<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");
use Illuminate\Support\Arr;

class CartonPackingInhouseActivityLog
{
public function logIfChangedCarton(string $table, $recordId, $color_id,$sales_order_no,array $olddata, array $newData,string $size_array, string $action, string $AttendanceDate, string $module)
{


// logger('OLD DATA:', $olddata);
//  logger('NEW DATA:', $newData);

// Find old item by matching color_id
$oldItem = collect($olddata)->first(function ($item) use ($newData) {
    return (string)$item['sales_order_no'] === (string)$newData['sales_order_no'];
});



 
 

$newDataArray = Arr::isAssoc($newData) ? [$newData] : $newData;
$oldDataArray = Arr::isAssoc($olddata) ? [$olddata] : $olddata;


if ($oldItem) {
    
    
        $changed = array_diff_assoc($newData, $oldItem);

        $newChanged = array_intersect_key($newData, $changed);
        $oldChanged = array_intersect_key($oldItem, $changed);
    

    if (!empty($oldChanged)) {
        // logger("Changed OLD VALUES for color_id {$newData['color_id']}:", $oldChanged);
        // logger("Changed NEW VALUES for color_id {$newData['color_id']}:", $newChanged);
        
        
            $sizes = explode(',', $size_array);
            
            // Example incoming data (e.g., from request)
            $data = $newChanged;
            
            // Initialize result array
            $selectedSizes = [];
            
            // Loop through each key in data
            foreach ($data as $key => $value) {
            // Extract number from key like "s2" â†’ 2 â†’ index 1
            $index = intval(substr($key, 1)) - 1;
            
            // Get corresponding size from sizes array
            if (isset($sizes[$index])) {
            $selectedSizes[] = $sizes[$index];
            }
            }
            
            // Convert to comma-separated string
            $sizeString = implode(', ', $selectedSizes);

        
        

        DB::table('carton_packing_inhouse_activity_log')->insert([
            'action_type' => 'UPDATE',
            'cpki_code'=> $newData['cpki_code'],
            'sales_order_no'=>$newData['sales_order_no'],
            'color_id' => $newData['color_id'],
            'old_data' => json_encode($oldChanged, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($newChanged, JSON_UNESCAPED_UNICODE),
            'size_array'=>$sizeString,
            'action_timestamp' => now(),
            'changed_by_user_id' => Session::get('userId'), // replace with Auth::id() if needed
        ]);
    }
    
    
    
    
    
    
    
}  




 // logger('All new SOs:', array_column($newDataArray, 'sales_order_no'));

  //logger('All old SOs:', array_column($oldDataArray, 'sales_order_no'));



    
    



foreach ($newDataArray as $newItem) {
    
    $existsInOld = collect($oldDataArray)->contains('sales_order_no', (string)$newItem['sales_order_no']);


    if (!$existsInOld) {


        
        $oldDataDeleted=['cpki_code'=> $newItem['cpki_code'],'sales_order_no'=>$newItem['sales_order_no'],'color_id'=> $newItem['color_id'],
      's1'=>0,'s2'=>0,'s3'=>0,'s4'=>0,'s5'=>0,'s6'=>0,'s7'=>0,'s8'=>0,'s9'=>0,
            's10'=>0,'s11'=>0,'s12'=>0,'s13'=>0,'s14'=>0,'s15'=>0,'s16'=>0,'s17'=>0,'s18'=>0,'s19'=>0,'s20'=>0];
            
            
            
              $sizesNew = explode(',', $size_array);
            
           
            $dataNew = $newItem;
            
           
            $selectedSizesNew = [];
            
           
            foreach ($dataNew as $keyNew => $value) {
          
            $indexNew = intval(substr($keyNew, 1)) - 1;
            
         
            if (isset($sizesNew[$indexNew])) {
            $selectedSizesNew[] = $sizesNew[$indexNew];
            }
            }
            
          
            $sizeStringNew = implode(', ', $selectedSizesNew);

        
        
        
        DB::table('carton_packing_inhouse_activity_log')->insert([
            'action_type' =>'INSERT',
            'cpki_code' => $newItem['cpki_code'],
            'sales_order_no' => $newItem['sales_order_no'],
            'color_id' => $newItem['color_id'],
            'old_data' => json_encode($newItem, JSON_UNESCAPED_UNICODE), 
            'new_data' => json_encode($oldDataDeleted, JSON_UNESCAPED_UNICODE),
            'size_array' => $sizeStringNew,
            'action_timestamp' => now(),
            'changed_by_user_id' => Session::get('userId'),
        ]);



        // INSERT CREATE logic here...
    }
}    
    
    
    
    




}


}
