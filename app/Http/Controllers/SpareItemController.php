<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpareItemModel;
use App\Models\UnitModel;
use App\Models\CategoryModel;
use App\Models\QualityModel;
use App\Models\ClassificationModel;
use App\Models\TrimsInwardDetailModel;
use Image;
use App\Models\MaterialTypeModel;
use Illuminate\Support\Facades\DB;
use Session;
use App\Imports\ItemImport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;

class SpareItemController extends Controller
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
        ->where('form_id', '323')
        ->first();


        $data = SpareItemModel::join('item_category', 'item_category.cat_id', '=', 'spare_item_master.cat_id')
        ->join('classification_master', 'classification_master.class_id', '=', 'spare_item_master.class_id', 'left outer')
        ->join('usermaster', 'usermaster.userId', '=', 'spare_item_master.userId', 'left outer')
        ->join('machine_type_master', 'machine_type_master.machinetype_id', '=', 'spare_item_master.machinetype_id', 'left outer')
        ->join('machine_make_master', 'machine_make_master.mc_make_Id', '=', 'spare_item_master.mc_make_Id', 'left outer')
        ->join('machine_model_master', 'machine_model_master.mc_model_id', '=', 'spare_item_master.mc_model_id', 'left outer') 
        ->get(['spare_item_master.*','usermaster.username','item_category.cat_name','classification_master.class_name','machine_type_master.machinetype_name','machine_make_master.machine_make_name','machine_model_master.mc_model_name']);
        
        if ($request->ajax()) 
        {
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action1', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('SpareItem.edit', $row->spare_item_code).'" >
                                <i class="fas fa-pencil-alt"></i>
                           </a>';
                }
                else
                { 
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>';   
                }
                return $btn3;
            })
            ->addColumn('action2', function ($row) use ($chekform){
         
                if($chekform->delete_access==1)
                {      
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->spare_item_code.'"  data-route="'.route('SpareItem.destroy', $row->spare_item_code).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            })
            ->rawColumns(['action1','action2'])
    
            ->make(true);
        }
        return view('SpareItemMasterList', compact('data','chekform'));
        
         
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Categorylist = CategoryModel::where('delflag','=', '0')->where('cat_id','=', '5')->get();
        $Classificationlist = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '5')->get();
        $MaterialTypeList = MaterialTypeModel::where('delflag','=', '0')->get();
        $UnitList = UnitModel::where('delflag','=', '0')->get();
        $QualityList = QualityModel::where('delflag','=', '0')->get();
        $MachineTypelist = DB::table('machine_type_master')->where('delflag','=', '0')->get();
        $MachineModellist = DB::table('machine_model_master')->where('delflag','=', '0')->get();
        $MachineMakelist = DB::table('machine_make_master')->where('delflag','=', '0')->get();
        $Categorylist = CategoryModel::where('delflag','=', '0')->where('cat_id','=', '5')->get();
        
        return view('SpareItemMaster',compact('Classificationlist','Categorylist','MaterialTypeList','UnitList','QualityList', 'MachineTypelist', 'MachineModellist', 'MachineMakelist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
         $this->validate($request, [
            'cat_id' => 'required',
            'class_id' => 'required',
            'item_name' => 'required', 
        ]);

        $input = $request->all();
        SpareItemModel::create($input);

        return redirect()->route('SpareItem.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $Classificationlist = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '5')->get();
        $Categorylist = CategoryModel::where('delflag','=', '0')->where('cat_id','=', '5')->get();
        $UnitList = UnitModel::where('delflag','=', '0')->get();
        $QualityList = QualityModel::where('delflag','=', '0')->get();
        $MaterialTypeList = MaterialTypeModel::where('delflag','=', '0')->get();
        $MachineTypelist = DB::table('machine_type_master')->where('delflag','=', '0')->get();
        $MachineModellist = DB::table('machine_model_master')->where('delflag','=', '0')->get();
        $MachineMakelist = DB::table('machine_make_master')->where('delflag','=', '0')->get();
        $items = SpareItemModel::find($id);

        return view('SpareItemMaster', compact('items','Classificationlist','Categorylist','MaterialTypeList','UnitList','QualityList', 'MachineTypelist', 'MachineModellist', 'MachineMakelist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    
        $item = SpareItemModel::findOrFail($id);

        $this->validate($request, [
            'cat_id' => 'required',
            'class_id' => 'required',
            'item_name' => 'required', 
            
           
        ]);

 
        $input = $request->all();
        $input['delflag'] = $request->active;
        $item->fill($input)->save();
        
       return redirect()->route('SpareItem.index')->with('message', 'Update Record Succesfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        SpareItemModel::where('spare_item_code', $id)->delete();

        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    
    public function GetClassList(Request $request)
    {  
        $html = '';
        if (!$request->cat_id) 
        {
            $html = '<option value="">-Classification-</option>';
        } 
        else
        {
            $html = '<option value="">-Classification-</option>';
            $ClassList = DB::table('classification_master')->where('cat_id', $request->cat_id)->get();
            foreach ($ClassList as $row) 
            {
                    $html .= '<option value="'.$row->class_id.'">'.$row->class_name.'</option>';
            }
        }
       return response()->json(['html' => $html]);
    } 
    
    
      
    public function itemexist(Request $request)
    {
    
        $item_name = $request->item_name;
    
        $item = DB::table('spare_item_master')->where('item_name', '=', $request->item_name)->where('delflag','=',0)->first();
        
        
        if ($item != null) 
        {
        
        
            $html ='<span style="color:#FF0000; weight:bold;">Item is already exist</span>';
            $html .= '
            <div class="mb-3" id="itemshow">
            <label for="formrow-email-input" class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" id="formrow-email-input" value="" onBlur="itemExist(this.value)" required>
            </div>
            ';
        
        }
        
        else 
        {
            $html = '<div class="mb-3" id="itemshow">
            <label for="formrow-email-input" class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" id="formrow-email-input" value="'.$item_name.'" onBlur="itemExist(this.value)" required>
            </div>';
          
        }
        
        return response()->json(['html' => $html]);
    }
    
    public function Get_Ledger_Item_Report()
    {
        $Categorylist = CategoryModel::where('delflag','=', '0')->get();
        
        return view('Get_Ledger_Item_Report',compact('Categorylist'));
    }
    
    public function GetClassifictionData(Request $request)
    {
    
        $cat_id = $request->cat_id;
        
        if($cat_id > 0)
        {
            $Classificationlist = ClassificationModel::where('delflag','=', '0')->where('cat_id', '=', $cat_id)->get();
        }
        else
        {
             $Classificationlist = ClassificationModel::where('delflag','=', '0')->get();
        }
        $html = '<option value="All">--All--</option>';
        
        foreach($Classificationlist as $row)
        {
            $html .= '<option value="'.$row->class_id .'">'.$row->class_name .'</option>';
        }
        return response()->json(['html' => $html]);
    
    }  
    
    public function GetItemData(Request $request)
    {
    
        $cat_id = $request->cat_id;
        $class_id = $request->class_id;
        
        if($cat_id > 0)
        {
            $cId = " AND cat_id=".$cat_id;
        }
        else
        {
            $cId ="";
        }
        
        if($class_id > 0)
        {
            $clId = " AND class_id=".$class_id;
        }
        else
        {
            $clId = "";
        }
        //DB::enableQueryLog();
        $Itemlist = DB::select("SELECT spare_item_code,item_name FROM spare_item_master WHERE delflag=0".$clId."".$cId);
        //dd(DB::getQueryLog());
        $html = '<option value="All">--All--</option>';
        
        foreach($Itemlist as $row)
        {
            $html .= '<option value="'.$row->spare_item_code .'">'.$row->item_name .'-('.$row->spare_item_code .')</option>';
        }
        return response()->json(['html' => $html]);
    
    }
    
    public function GetItemUnits(Request $request)
    {
        $unitData = DB::SELECT("SELECT unit_name FROM spare_item_master INNER JOIN unit_master ON unit_master.unit_id = spare_item_master.unit_id WHERE spare_item_code=".$request->spare_item_code);
        $unit_name = isset($unitData[0]->unit_name) ? $unitData[0]->unit_name : 0;
        return response()->json(['unit_name' => $unit_name]);
    }
    
} 
