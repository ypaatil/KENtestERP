<?php

namespace App\Http\Controllers;

use App\Models\StyleModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Session;

class StyleMasterController extends Controller
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
        ->where('form_id', '330')
        ->first();        

            

  
  if ($request->ajax()) {
            

$data=StyleModel::select("main_style_master_operation.mainstyle_id","main_style_master_operation.mainstyle_name","usermaster.username","item_category.cat_name","sub_category_masters.sub_cat_name")
 ->join('usermaster','usermaster.userId','=','main_style_master_operation.userId')
  ->leftJoin('item_category','item_category.cat_id','=','main_style_master_operation.cat_id')
  ->leftJoin('sub_category_masters','sub_category_masters.sub_cat_id','=','main_style_master_operation.sub_cat_id')
  ->where('main_style_master_operation.delflag','=', '0')
  ->orderBy('main_style_master_operation.mainstyle_id','DESC'); 


 return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action1', function ($row) use ($chekform){
                        
                        
        if($chekform->edit_access==1)
       {
     
                           $btn = '
 <a class="btn btn-primary btn-icon btn-sm"  href="'.route('Style.edit', $row['mainstyle_id']).'" >
                                                                <i class="feather feather-edit" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                           ';
                           
       } else{
           
           
                    $btn = '
 <a class="btn btn-primary btn-icon btn-sm">
                                                                <i class="feather feather-lock" data-toggle="tooltip" data-original-title="Edit"></i>
                                                            </a>
                           ';    
           
       }
                           
                           
    
                            return $btn;
                    })
                     ->addColumn('action2', function ($row) use ($chekform){
                         
                         
                         
                                                              if($chekform->delete_access==1)
       {
     
                           $btn2 = '
 <a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row['mainstyle_id'].'"  data-route="'.route('Style.destroy', $row['mainstyle_id']).'"><i class="feather feather-trash-2"></i></a>
                           ';
       } else{
           
           
                $btn2 = '
 <a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"><i class="feather feather-lock"></i></a>
                           ';
           
       }
                           
    
                            return $btn2;
                    })
                    
             ->addColumn('action3', function ($row) use ($chekform){
                         
                         
                         
         if($chekform->edit_access==1)
       {
     
                           $btn3 = '
 <a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"  data-id="'.$row['mainstyle_id'].'" href="/rate_chart/'.$row['mainstyle_id'].'"><i class="feather feather-printer"></i></a>
                           ';
       } else{
           
           
                $btn3 = '
 <a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"><i class="feather feather-lock"></i></a>
                           ';
           
       }
                           
    
                            return $btn3;
                    })             
                    
                    ->rawColumns(['action1','action2','action3'])

 ->make(true);


        }


        
     return view('Operation.styleMasterList',compact('chekform'));



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $categoryList = DB::table('item_category')->Select('*')->get();    
        $subCategoryList = DB::table('sub_category_masters')->Select('*')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get(); 
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();  
     
       return view('Operation.styleMaster',compact('categoryList','subCategoryList','MainStyleList','SubStyleList'));
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
            'mainstyle_name' => 'required',
        ]);

        $input = $request->all();

        StyleModel::create($input);

        return redirect()->route('Style.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GroupModel  $groupModel
     * @return \Illuminate\Http\Response
     */
    public function show(GroupModel $groupModel)
    {
        //
    }
    
    
        public function rate_chart($mainstyle_id)
        {

        
        
        $data = DB::table('ob_details')
        ->select(
        'ob_details.operation_name',
        'ob_details.rate', 
        'ob_details.sam'
        )
        ->where('mainstyle_id',$mainstyle_id)
        ->get();
        
       $styleFetch= DB::table('main_style_master_operation')->where('mainstyle_id',$mainstyle_id)->first();
       
       $mainstyle_name= $styleFetch->mainstyle_name;
           $FirmDetail =  DB::table('firm_master')->first();
        
        return view('Operation.RateChartPrint',compact('data','mainstyle_name','FirmDetail'));
        }  
    
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GroupModel  $groupModel
     * @return \Illuminate\Http\Response
     */
    public function edit($style_id)
    {
         $stylefetch = StyleModel::find($style_id);
               

         $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get(); 
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();  
        
        return view('Operation.styleMaster', compact('stylefetch','MainStyleList','SubStyleList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GroupModel  $groupModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
    
    $country = StyleModel::findOrFail($id);

        $this->validate($request, [
            'mainstyle_name' => 'required',
        ]);

        $input = $request->all();

        $country->fill($input)->save();

        return redirect()->route('Style.index')->with('message', 'Update Record Succesfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroupModel  $groupModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($groupid)
    {


 $master =StyleModel::where('mainstyle_id',$groupid)->delete();      

Session::flash('delete', 'Deleted record successfully'); 




    }
}
