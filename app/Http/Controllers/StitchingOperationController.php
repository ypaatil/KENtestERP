<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DHUStichingOperationModel;
use App\Models\DHUStichingDefectTypeModel;
use App\Models\MainStyleModel;
use Session;
use DataTables;

class StitchingOperationController extends Controller
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
        
        $dhuList = DB::table('dhu_stiching_defect_type')
            ->select('dhu_stiching_defect_type.*','main_style_master.mainstyle_name')
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'dhu_stiching_defect_type.mainstyle_id')
            ->get();
       
        return view('DHU_Stitiching_Defect_Type_Master_List',compact('dhuList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
            
        return view('DHU_Stitiching_Defect_Type_Master',compact('MainStyleList'));
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
                'dhu_sdt_Name'=>$request->dhu_sdt_Name,  
                'dhu_sdt_marathi_Name'=>$request->dhu_sdt_marathi_Name,  
                'mainstyle_id'=>$request->mainstyle_id, 
                'userId'=>$request->userId,
                'created_at'=>date('Y-m-d H:i:s'),
        );
            
        DHUStichingDefectTypeModel::insert($data);
        return redirect()->route('StitchingOperation.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(DHUStichingDefectTypeModel $DHUStichingDefectTypeModel)
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
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $dhuList = DHUStichingDefectTypeModel::find($id);
     
        return view('DHU_Stitiching_Defect_Type_Master',compact('dhuList','MainStyleList'));
   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DHUStichingOperationModel  $DHUStichingOperationModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DHUStichingDefectTypeModel $DHUStichingDefectTypeModel)
    {
            $data=array(
                'dhu_sdt_Name'=>$request->dhu_sdt_Name,  
                'dhu_sdt_marathi_Name'=>$request->dhu_sdt_marathi_Name,  
                'mainstyle_id'=>$request->mainstyle_id, 
                'userId'=>$request->userId,
                'updated_at'=>date('Y-m-d H:i:s'),
            );
            
            $dhuList = DHUStichingDefectTypeModel::findOrFail($request->input('dhu_sdt_Id')); 
            $dhuList->fill($data)->save();
            return redirect()->route('StitchingOperation.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DHUStichingOperationModel  $DHUStichingOperationModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('dhu_stiching_defect_type')->where('dhu_sdt_Id', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
    public function GetDHUDefectList(Request $request)
    {
        $html = "<option>--Select--</option>"; 
        $DHUDefectList= DHUStichingDefectTypeModel::select('dhu_sdt_Id','dhu_sdt_Name')->Where('dhu_so_Id','=',$request->dhu_so_Id)->get();
        foreach($DHUDefectList as $row)
        {
            $html .='<option value="'.$row->dhu_sdt_Id.'">'.$row->dhu_sdt_Name.'</option>';
        }
                                
        return response()->json(['html' => $html]);
    }
}
