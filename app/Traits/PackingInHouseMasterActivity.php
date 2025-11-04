<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
date_default_timezone_set("Asia/Kolkata");

trait PackingInHouseMasterActivity
{
    public static function bootLogsActivity()
    {
        //logger('bootLogsActivity called');
        
        // static::created(function ($model) {
        //     DB::table('employeemaster_activity_log')->insert([
        //         'action_type' => 'INSERT',
        //         'old_data' => null,
        //         'new_data' => json_encode($model->getAttributes()),
        //         'action_timestamp' => now(),
        //         'changed_by_user_id' => Session::get('userId')
        //     ]);
        // });
        
        
        //     static::created(function ($model) {
        //     DB::table('master_activity_log')->insert([
        //         'action_type' => 'INSERT',
        //         'old_data' => json_encode(['brand_id' =>'New Branch Created']),              
        //         'new_data' => json_encode(['brand_id' => $model->brand_id]),
        //         'action_timestamp' => now(),
        //         'changed_by_user_id' => Session::get('userId')
        //     ]);
        // });    


//         static::updated(function ($model) {
//     if ($model->isDirty()) {
//         $changedFields = $model->getChanges(); // only new values for changed fields
//         $originalData = $model->getOriginal();

//         // Extract only the old values that match the changed fields
//         $oldValues = array_intersect_key($originalData, $changedFields);

//         DB::table('employeemaster_activity_log')->insert([
//             'action_type' => 'UPDATE',
//             'old_data' => json_encode($oldValues),
//             'new_data' => json_encode($changedFields),
//             'action_timestamp' => now(),
//             'changed_by_user_id' => Session::get('userId')
//         ]);
//     }
// });



static::updated(function ($model) {
    if ($model->isDirty()) {
        $changes = $model->getChanges();
        $original = $model->getOriginal();

        $filteredOld = [];
        $filteredNew = [];

        foreach ($changes as $field => $newValue) {
            $oldValue = $original[$field] ?? null;

            // Normalize both old and new if they're date-like
                if ($oldValue !== $newValue) {
                    $filteredOld[$field] = $oldValue;
                    $filteredNew[$field] = $newValue;
                }
        }

        // Only log if actual value differences exist
        if (!empty($filteredNew)) {
            DB::table('packing_inhouse_activity_log')->insert([
                'action_type' => 'UPDATE',
                'old_data' => json_encode($filteredOld),
                'new_data' => json_encode($filteredNew),
                'action_timestamp' => now(),
                'changed_by_user_id' => Session::get('vendorId')
            ]);
        }
    }
});





        // static::deleted(function ($model) {
        //     DB::table('employeemaster_activity_log')->insert([
        //         'action_type' => 'DELETE',
        //         'old_data' => $model->getOriginal(),
        //         'new_data' => null,
        //         'action_timestamp' => now(),
        //         'changed_by_user_id' => Session::get('userId')
        //     ]);
        // });
        
        
        
        
    }
    
    protected static function isDateLike($value): bool
{
    return strtotime($value) !== false;
}
}
