<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\ERPQueryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/codex-command', [ERPQueryController::class, 'processPrompt']);

// ✅ Get all table names
// Route::get('/get-table-names', function () {
//     try {
//         $latestDb = DB::table('ken_year_databases')
//             ->orderByDesc('year_db_id')
//             ->first();

//         if (!$latestDb || !isset($latestDb->database_name)) {
//             return response()->json(['success' => false, 'message' => '❌ No database name found.']);
//         }

//         $dbName = $latestDb->database_name;

//         $tablesRaw = DB::select("SHOW TABLES FROM `$dbName`");
//         $key = "Tables_in_$dbName";

//         $tableNames = array_map(function ($row) use ($key) {
//             return $row->$key ?? null;
//         }, $tablesRaw);

//         return response()->json([
//             'success' => true,
//             'used_database' => $dbName,
//             'tables' => array_filter($tableNames)
//         ]);
//     } catch (\Exception $e) {
//         return response()->json(['success' => false, 'error' => $e->getMessage()]);
//     }
// });

Route::get('/get-table-names', function () {
    try {
        $latestDb = DB::table('ken_year_databases')
            ->orderByDesc('year_db_id')
            ->first();

        if (!$latestDb || !isset($latestDb->database_name)) {
            return response()->json(['success' => false, 'message' => '❌ No database name found.']);
        }

        $dbName = $latestDb->database_name;

        $tablesRaw = DB::select("SHOW TABLES FROM `$dbName`");
        $key = "Tables_in_$dbName";

        $allowedTables = ['sale_transaction_master', 'stitching_inhouse_master'];

        $tableNames = array_filter(array_map(function ($row) use ($key, $allowedTables) {
            return in_array($row->$key, $allowedTables) ? $row->$key : null;
        }, $tablesRaw));

        return response()->json([
            'success' => true,
            'used_database' => $dbName,
            'tables' => array_values($tableNames) // Reset array keys
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});


// ✅ Helper to fix malformed UTF-8
function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } elseif (is_object($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed->$key = utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        return mb_convert_encoding($mixed, 'UTF-8', 'UTF-8');
    }
    return $mixed;
}

Route::get('/get-table-data/{table}', function (Request $request, $table) {
    date_default_timezone_set('Asia/Kolkata');
    $userQuery = strtolower($request->query('q', ''));

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        return response()->json(['success' => false, 'error' => '❌ Invalid table name.']);
    }

    $latestDb = DB::table('ken_year_databases')->orderByDesc('year_db_id')->first();
    if (!$latestDb || !isset($latestDb->database_name)) {
        return response()->json(['success' => false, 'error' => '❌ Latest database not found.']);
    }

    $dbName = $latestDb->database_name;

    $checkTable = DB::select("SHOW TABLES FROM `$dbName` LIKE '$table'");
    if (empty($checkTable)) {
        return response()->json(['success' => false, 'error' => '❌ Table not found.']);
    }

    try {
        $idArr = ['Ac_code', 'mainstyle_id', 'vendorId', 'substyle_id', 'fg_id', 'sz_code', 'job_status_id', 'brand_id', 'cur_id', 'userId', 'season_id'];
        $qualifiedMain = "$dbName.$table as t";
        $query = DB::table(DB::raw($qualifiedMain));
        $selectFields = ['t.*'];
        $joins = [];

        $latestRow = DB::table(DB::raw($qualifiedMain))->first();
        if ($latestRow) {
            $rowArray = (array)$latestRow;
            $matchedColumns = array_intersect($idArr, array_keys($rowArray));
            if (!empty($matchedColumns)) {
                $joins = DB::table("$dbName.table_joins")
                    ->whereIn('join_column', $matchedColumns)
                    ->get();
            }
        }

        foreach ($joins as $join) {
            $query->leftJoin(
                "$dbName.{$join->table_name} as {$join->join_table_alias}",
                "{$join->join_table_alias}.{$join->main_column}",
                '=',
                "t.{$join->main_column}"
            );

            $selectFields[] = "{$join->join_table_alias}.*";

            if (!empty($join->select_column) && !empty($join->select_alias)) {
                $selectFields[] = "{$join->join_table_alias}.{$join->select_column} as {$join->select_alias}";
            }
        }

        $columnsInfo = DB::select("SHOW COLUMNS FROM `$dbName`.`$table`");
        $columns = [];

        if (!empty($columnsInfo) && is_array($columnsInfo)) {
            $columns = array_filter(array_map(function ($col) {
                return is_object($col) && isset($col->Field) ? $col->Field : null;
            }, $columnsInfo));
        }

        // 👉 Add auto order by latest
        $orderByColumn = null;
        foreach (['created_at', 'entry_date', 'updated_at', 'id'] as $col) {
            if (in_array($col, $columns)) {
                $orderByColumn = $col;
                break;
            }
        }

        if ($orderByColumn) {
            $query->orderByDesc("t.$orderByColumn");
        }

        $rows = $query->select($selectFields)->get();

        if ($rows->isEmpty()) {
            return response()->json(['success' => false, 'error' => '❌ No data found.']);
        }

        $safeRows = $rows->map(function ($item) {
            return collect((array)$item)->map(function ($value) {
                return is_string($value) ? mb_convert_encoding($value, 'UTF-8', 'UTF-8') : $value;
            });
        });

        if (!empty($userQuery)) {
            $keywords = preg_split('/\s+/', $userQuery);
            $matchedFields = [];

            foreach ($safeRows as $row) {
                foreach ($row as $key => $value) {
                    $normalizedKey = str_replace('_', ' ', strtolower($key));
                    foreach ($keywords as $word) {
                        if (stripos($normalizedKey, $word) !== false || stripos($key, $word) !== false) {
                            if (!is_null($value)) {
                                $matchedFields[$key] = $value;
                            }
                        }
                    }
                }

                if (!empty($matchedFields)) break;
            }

            if (!empty($matchedFields)) {
                return response()->json([
                    'success' => true,
                    'used_database' => $dbName,
                    'matched_fields' => $matchedFields,
                    'note' => 'Fields matched from user query.'
                ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => '❌ No matching column found in query.',
                    'available_fields' => $safeRows->first()->keys(),
                    'sample_row' => $safeRows->first(),
                    'searched' => $userQuery
                ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
            }
        }

        return response()->json([
            'success' => true,
            'used_database' => $dbName,
            'data_count' => $safeRows->count(),
            'columns' => $columns,
            'data' => $safeRows
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => '❌ Query failed: ' . $e->getMessage()
        ]);
    }
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
