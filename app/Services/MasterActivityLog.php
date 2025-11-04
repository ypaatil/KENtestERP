<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");

class MasterActivityLog
{
    
    
    public function logIfChangedMaster(string $table, $recordId,$color_id, array $newData, string $action, string $AttendanceDate,string $module)
    {
        
      
  $record = DB::table($table)
    ->select('pki_date','location_id')
    ->where('pki_code',$recordId)
    ->first();

if (!$record) {
    return false; // Record not found
}

// Convert stdClass to associative array safely
$oldFetch = (array) $record;

    
$oldData = [
    'pki_date' => $oldFetch['pki_date'] ?? null,
    'location_id'=> $oldFetch['location_id'] ?? null
 
];    
    


   // logger($oldData);
    


        // Compare old and new data
        $changed = array_diff_assoc($newData, $oldData);

        if (!empty($changed)) {
        $new_changes = array_intersect_key($newData, $changed);
        $old_changes = array_intersect_key($oldData, $changed);

           
            
               DB::table('packing_inhouse_activity_log')->insert([
                'action_type' => $action,
                'pki_code'=> $recordId,
                'color_id'=> $color_id, 
                'old_data' => json_encode($old_changes),              
                'new_data' => json_encode($new_changes),
                'size_array'=>'',
                'action_timestamp' => now(),
                'changed_by_user_id' => Session::get('userId')
            ]);
            
            return true;
            

            
        }
        



        return false;
    }
}
