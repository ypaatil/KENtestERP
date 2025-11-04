<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");
use Illuminate\Support\Arr;

class SalesOrderMasterActivityLog
{
   
    
public function logIfChangedSalesOrderMaster(string $table, $recordId,array $oldData, array $newData,string $action, string $AttendanceDate, string $module)
{


   logger('OLD DATA:', $oldData);
   logger('NEW DATA:', $newData);


        $changed = array_diff_assoc($newData, $oldData);

        if (!empty($changed)) {
        $new_changes = array_intersect_key($newData, $changed);
        $old_changes = array_intersect_key($oldData, $changed);

           
            
           DB::table('sales_order_detail_activity_log')->insert([
                'action_type' => 'UPDATE',
                'tr_code' => $recordId,
                'color_id'=> '',
                'old_data' => json_encode($old_changes, JSON_UNESCAPED_UNICODE),
                'new_data' => json_encode($new_changes, JSON_UNESCAPED_UNICODE),
                'action_timestamp' => now(),
                'changed_by_user_id' => Session::get('userId'),
            ]);
            
            return true;
            

            
        }



}


}
