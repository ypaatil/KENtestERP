<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");

class TransferPackingInhouseActivityLog
{
public function logIfChangedTransfer(string $table, $recordId, $color_id,array $olddata, array $newData,string $size_array, string $action, string $AttendanceDate, string $module)
{
    // logger("🔍 Starting logIfChanged...");
    // logger("📋 New Data:");
    // logger($newData);

// logger('OLD DATA:', $olddata);
// logger('NEW DATA:', $newData);

// Find old item by matching color_id
$oldItem = collect($olddata)->first(function ($item) use ($newData) {
    return (string)$item['color_id'] === (string)$newData['color_id'];
});

if ($oldItem) {
    $oldChanged = [];
    $newChanged = [];

    for ($i = 1; $i <= 20; $i++) {
        $key = 's' . $i;

        $oldValue = isset($oldItem[$key]) ? (string)$oldItem[$key] : null;
        $newValue = isset($newData[$key]) ? (string)$newData[$key] : null;

        if ($oldValue !== $newValue) {
            $oldChanged[$key] = $oldValue;
            $newChanged[$key] = $newValue;
        }
    }
    
    
     

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
            // Extract number from key like "s2" → 2 → index 1
            $index = intval(substr($key, 1)) - 1;
            
            // Get corresponding size from sizes array
            if (isset($sizes[$index])) {
            $selectedSizes[] = $sizes[$index];
            }
            }
            
            // Convert to comma-separated string
            $sizeString = implode(', ', $selectedSizes);

        
        

        DB::table('transfer_packing_inhouse_activity_log')->insert([
            'action_type' => 'UPDATE',
            'tpki_code'=> $newData['tpki_code'],
            'color_id' => $newData['color_id'],
            'old_data' => json_encode($oldChanged, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($newChanged, JSON_UNESCAPED_UNICODE),
            'size_array'=>$sizeString,
            'action_timestamp' => now(),
            'changed_by_user_id' => Session::get('userId'), // replace with Auth::id() if needed
        ]);
    }
}




}


}
