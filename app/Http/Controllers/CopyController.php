<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CopyController extends Controller
{
    public function showForm()
    {
        
         $FormList = DB::table('form_master')->where('delflag','=', '0')->where('head_id','=', '2')->get();
        return view('YearDBTransfer', compact('FormList'));
    }

    // public function handleForm(Request $request)
    // {
       
       
       
    //     $validated = $request->validate([
    //         'source_db' => 'required|string',
    //         'target_db' => 'required|string',
    //         'target_table' => 'required|string',
    //         'primary_key_column' => 'required|string',
    //         'ids_to_copy' => 'required|string',
    //     ]);



    //       $quotedIds = null;

    // if (!empty($validated['ids_to_copy'])) {
    //     $ids = array_filter(array_map('trim', explode(',', $validated['ids_to_copy'])));

    //     $quotedIds = "'" . implode("','", array_map('addslashes', $ids)) . "'";  
    //     // Output: 'FOUT-1','FOUT-2','FOUT-3'
    // }


 
    //     DB::statement('CALL copy_and_delete_records(?, ?, ?, ?, ?, ?)', [
    //         $validated['target_table'],
    //         $validated['target_table'],
    //         $validated['source_db'],
    //         $validated['target_db'],
    //         $validated['primary_key_column'],
    //         $quotedIds,
    //     ]);

    //     return back()->with('success', 'Records copied and deleted successfully.');
    // }
    
public function handleForm(Request $request)
{
    $validated = $request->validate([
        'source_db' => 'required|string',
        'target_db' => 'required|string',
        'form_id' => 'required|integer', // assuming it's numeric
        'ids_to_copy' => 'required|string',
    ]);

    $quotedIds = null;

    if (!empty($validated['ids_to_copy'])) {
        $ids = array_filter(array_map('trim', explode(',', $validated['ids_to_copy'])));
        $quotedIds = "'" . implode("','", array_map('addslashes', $ids)) . "'";
    }

    // Fetch target_table and primary_key_column for selected form_id from DB
    $tableDetails = DB::table('year_end_form_table_detail')
        ->where('form_id', $validated['form_id'])
        ->select('table_name', 'p_key_name')
        ->get();

    if ($tableDetails->isEmpty()) {
        return back()->with('error', 'No table details found for selected form.');
    }

    // Call stored procedure for each table/column config
    foreach ($tableDetails as $detail) {
        DB::statement('CALL copy_and_delete_records(?, ?, ?, ?, ?, ?)', [
            $detail->table_name,              // source_table (if applicable)
            $detail->table_name,              // target_table
            $validated['source_db'],
            $validated['target_db'],
            $detail->p_key_name,
            $quotedIds,
        ]);
    }

    return back()->with('success', 'Records copied and deleted successfully.');
}

    
    
    
    
    
    
    
    
    
    
    
    
    
}



?>