<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DHUStichingOperationModel;
use Session;
use DataTables;

class StitchingDefectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '52')
            ->first();
        
        $dhuList = DB::table('dhu_stiching_operation')->
            select('dhu_stiching_operation.*','usermaster.username')
            ->join('usermaster', 'usermaster.userId', '=', 'dhu_stiching_operation.userId')
            ->get();
       
        return view('DHU_Stiching_Operation_Master_List',compact('dhuList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('DHU_Stiching_Operation_Master');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $data=array(
                'dhu_so_Name'=>$request->dhu_so_Name, 
                'dhu_so_marathi_Name'=>$request->dhu_so_marathi_Name, 
                'userId'=>$request->userId,
                'created_at'=>date('Y-m-d H:i:s'), 
        );
            
        DHUStichingOperationModel::insert($data);
        return redirect()->route('StitchingDefect.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricOutwardModel $fabricOutwardModel)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $dhuList = DHUStichingOperationModel::find($id);
     
        return view('DHU_Stiching_Operation_Master',compact('dhuList'));
   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DHUStichingOperationModel  $DHUStichingOperationModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DHUStichingOperationModel $DHUStichingOperationModel)
    {
            $data=array(
                'dhu_so_Name'=>$request->dhu_so_Name, 
                'dhu_so_marathi_Name'=>$request->dhu_so_marathi_Name, 
                'userId'=>$request->userId,
                'updated_at'=>date('Y-m-d H:i:s'),
            );
            
            $dhuList = DHUStichingOperationModel::findOrFail($request->input('dhu_so_Id')); 
            $dhuList->fill($data)->save();
            return redirect()->route('StitchingDefect.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DHUStichingOperationModel  $DHUStichingOperationModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('dhu_stiching_operation')->where('dhu_so_Id', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
 
}
