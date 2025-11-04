<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\OBMasterModel;
use App\Models\OBDetailModel;
use DB;
use DataTables;
use Session;
use Excel;
use App\Imports\OBImport;


class OBMasterController extends Controller
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
        ->where('form_id', '309')
        ->first();     

        
         if ($request->ajax()) 
        {
            $data=OBMasterModel::select("ob_masters.ob_id","total_sam","total_rate","total_rate3","total_rate4","total_rate5","total_rate6","usermaster.username",
             "main_style_master_operation.mainstyle_name")
             ->leftJoin('usermaster','usermaster.userId','=','ob_masters.userId')
            ->leftJoin('main_style_master_operation','main_style_master_operation.mainstyle_id','=','ob_masters.mainstyle_id')
            ->orderBy('ob_masters.ob_id','DESC');

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action1', function($row) use($chekform)
            {
                  if($chekform->edit_access==1)
                {   
                
                $btn = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('ob.edit', $row['ob_id']).'" ><i class="feather feather-edit" data-toggle="tooltip" data-original-title="Edit"></i></a>';
                
                } else{
                    
                    $btn = '
                    <a class="btn btn-primary btn-icon btn-sm">
                    <i class="feather feather-lock" data-toggle="tooltip" data-original-title="Edit"></i>
                    </a>
                    ';         
                    
                }
                
                
                return $btn;
            })
            ->addColumn('action2', function($row) use($chekform)
            {
                
                         
                if($chekform->delete_access==1)
                {
                
                $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row['ob_id'].'"  data-route="'.route('ob.destroy', $row['ob_id']).'"><i class="feather feather-trash-2"></i></a>';
                
                } else{
                    
                
                $btn3 = '
                <a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"><i class="feather feather-lock"></i></a>
                ';   
                }
                
                return $btn3;
            })
                        ->addColumn('action3', function($row) use($chekform)
            {
                
                
                       if(Session::get('vendorId')==115 || Session::get('vendorId')==56)
                       {
                       $btn3 = '<span>'.$row['total_rate'].'<span>';
                       }
                        
                         if(Session::get('vendorId')==110)
                         {
                        $btn3 = '<span>'.$row['total_rate3'].'<span>';
                              }
                        
                         if(Session::get('vendorId')==628)
                         {
                     $btn3 = '<span>'.$row['total_rate4'].'<span>';
                       }
                        
                        if(Session::get('vendorId')==686)
                        {
                   $btn3 = '<span>'.$row['total_rate5'].'<span>';
                     }
                        
                         if(Session::get('vendorId')==113)
                         {
                    $btn3 = '<span>'.$row['total_rate6'].'<span>';
                          }
                
         
               
                
                return $btn3;
            })
            ->rawColumns(['action1','action2','action3'])
            ->make(true);
        }
        
        
       $styleList = DB::table('main_style_master_operation')->whereRaw("mainstyle_id NOT IN(select mainstyle_id  from ob_masters)")->Select('*')->get(); 
       
       
        $vendorId=Session::get('vendorId');
            
            

            return view('Operation.ob_master_list',compact('styleList','vendorId'));
}
/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/
public function create(Request $request)
{ 
    
$categoryList = DB::table('item_category')->Select('*')->get();    

$subCategoryList = DB::table('sub_category_masters')->Select('*')->get();

$groupList = DB::table('group_masters')->Select('*')->get();  

$machineTypeList = DB::table('machine_type_masters')->Select('*')->get();  


$styleList = DB::table('main_style_master_operation')->whereRaw("mainstyle_id NOT IN(select mainstyle_id  from ob_masters)")->Select('*')->get();    


return view('Operation.ob_master',compact('categoryList','subCategoryList','groupList','machineTypeList','styleList'));

}


/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(Request $request)
{
   
        // $this->validate($request, [
        // 'sub_company_id'=>'required',
        // 'efficiency'=>'required',
        // 'cpm'=>'required',
        // 'cpam'=>'required',
        // 'avg_operators'=>'required',
        // 'over_time'=>'required',
        // 'surplus'=>'required',
        // 'mmr'=>'required',
        
        // ]);

         try {  
             
          $data = $request->all();
          
           DB::beginTransaction();
           
           $id = $data['id'] ?? null;
        
            $IODetails = OBMasterModel::updateOrCreate(
                ['ob_id'=> $id],
                $data);
                
                
                          
                  if($id == null){
             $ob_id=OBMasterModel::max('ob_id');
            } else{

                $ob_id=$id; 
            }
                
                
           $operation_id = $request->input('operation_id');
         
           
        //     if(!empty($operation_id))
        //     {

        //          OBDetailModel::where('ob_id',$ob_id)->delete();

               
        //         $data1=array();
        //         $EMPArray=[];
        //     for($x=0; $x<count($operation_id); $x++) {
                
                
        //     $data1[]=array(
        //       'ob_id'=>$ob_id,  
        //       'mainstyle_id'=>$request->mainstyle_id,
        //       'sub_company_id'=>$request->sub_company_id,
        //       'operation_id'=>$request->operation_id[$x],
        //       'operation_name'=>$request->operation_name[$x], 
        //         'group_id'=>$request->group_id[$x],  
        //       'machine_type_id'=>$request->machine_type_id[$x],
        //       'sam'=>$request->sam[$x],  
        //         'rate'=>$request->rate[$x],    
        //         'rate3'=>$request->rate3[$x],  
        //         'rate4'=>$request->rate4[$x],  
        //         'rate5'=>$request->rate5[$x],  
        //         'rate6'=>$request->rate6[$x],  
        //       'required_skill_set'=>$request->required_skill_set[$x]
            
        //     );
            
           
          
        //     }
    
            
             
        //     OBDetailModel::insert($data1);
          
        // }
        
        
      
        
             
               if(!empty($operation_id)) {
            
            // Prepare an array to hold new records to be inserted
            $data1 = array();
            
            for($x = 0; $x < count($operation_id); $x++) {
                
           
            $vendorRates = [
            115 => 'rate',
            110 => 'rate3',
            628 => 'rate4',
            686 => 'rate5',
            113 => 'rate6',
            ];
            
            $rateColumn = 'rate'; // Default for session user type 1
            
            if(Session::get('user_type') == 1) {
            // User type 1 gets all rate fields
            $rateColumn = 'rate';
            
           $existingRecord = OBDetailModel::updateOrCreate(
            [
            'ob_id' => $ob_id,  
            'sr_no' => $request->auto_id[$x]
            ],
            [
            'mainstyle_id' => $request->mainstyle_id,
            'sub_company_id' => $request->sub_company_id,
            'operation_id' => $request->operation_id[$x],
            'operation_name' => $request->operation_name[$x], 
            'group_id' => $request->group_id[$x],  
            'machine_type_id' => $request->machine_type_id[$x],
            'sam' => $request->sam[$x],
            'required_skill_set' => $request->required_skill_set[$x],
            'rate'=>$request->rate[$x],    
            'rate3'=>$request->rate3[$x],  
            'rate4'=>$request->rate4[$x],  
            'rate5'=>$request->rate5[$x],  
            'rate6'=>$request->rate6[$x],  
            ]
            ); 
            
            } else {
            // Check if session contains valid vendorId and assign corresponding rate field
            $vendorId = Session::get('vendorId');
            if (array_key_exists($vendorId, $vendorRates)) {
            $rateColumn = $vendorRates[$vendorId];
            }
            
                    $existingRecord = OBDetailModel::updateOrCreate(
            [
            'ob_id' => $ob_id,  
            'sr_no' => $request->auto_id[$x]
            ],
            [
            'mainstyle_id' => $request->mainstyle_id,
            'sub_company_id' => $request->sub_company_id,
            'operation_id' => $request->operation_id[$x],
            'operation_name' => $request->operation_name[$x], 
            'group_id' => $request->group_id[$x],  
            'machine_type_id' => $request->machine_type_id[$x],
            'sam' => $request->sam[$x],
            'required_skill_set' => $request->required_skill_set[$x],
            $rateColumn => $request->$rateColumn[$x], // Dynamically assign rate column
            ]
            );  
            
            
            }
            
     
            }
         
            }

            DB::commit();   
             
            
            $msg = "";

            if($id == null){
                $msg = 'OB record saved successfully';
            } else {
                $msg = 'OB record updated successfully';
            }

         

         return redirect()->route('ob.index')->with('message', $msg);
         
         } catch (\Exception $e) {
    // If an exception occurs, rollback the transaction and handle the exception
     \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
     
     DB::rollBack();
  
    return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
} 

}
/**
* Display the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function show($mis_target_id )
{


$categoryList = DB::table('item_category')->Select('*')->get();    

$styleList = DB::table('main_style_master_operation')->Select('*')->get();    

$subCategoryList = DB::table('sub_company_master')->Select('*')->get();

$groupList = DB::table('group_masters')->Select('*')->get();  

$machineTypeList = DB::table('machine_type_masters')->Select('*')->get(); 

//DB::enableQueryLog();
$subData =OBMasterModel::find($mis_target_id );
//dd(DB::getQueryLog());
return view('CompanyEfficiencyMasterEdit', compact('subcomData','subData'));
}
/**
* Show the form for editing the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/


    public function ob_import(Request $request)
    {


      Excel::import(new OBImport($request->mainstyle_id),request()->file('ob_file'));


        return redirect()->route('ob.index');

    }


    public function get_cat_sub_cat_by_style(Request $request)
    {
    
    $stylefetch = DB::table('main_style_master_operation')->Select('cat_id','sub_cat_id')->where('mainstyle_id',$request->mainstyle_id)->first();  
    
     return response()->json($stylefetch);
    
    }


public function edit($ob_id)
{
 
 $categoryList = DB::table('item_category')->Select('*')->get();    



$subCategoryList = DB::table('sub_category_masters')->Select('*')->get();

$groupList = DB::table('group_masters')->Select('*')->get();  

$machineTypeList = DB::table('machine_type_masters')->Select('*')->get();  
//DB::enableQueryLog();
  $obFetch =OBMasterModel::find($ob_id);
//dd(DB::getQueryLog());


$styleList = DB::table('main_style_master_operation')->Select('*')->where('mainstyle_id',$obFetch->mainstyle_id)->get();    


  $obFetchDetail=OBDetailModel::where('ob_id',$ob_id)->get();



return view('Operation.ob_master',compact('categoryList','subCategoryList','groupList','machineTypeList','obFetch','styleList','obFetchDetail'));
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

$subcompList = OBMasterModel::findOrFail($id);

$this->validate($request, [
    'sub_company_id'=>'required',
    'efficiency'=>'required',
    'cpm'=>'required',
    'cpam'=>'required',
    'avg_operators'=>'required',
    'over_time'=>'required',
    'surplus'=>'required',
    'mmr'=>'required',
]);
$input = $request->all();

$subcompList->fill($input)->save();

return redirect()->route('CompanyEfficiencyMaster.index')->with('message', 'Update Record Succesfully');
}

/**
* Remove the specified resource from storage.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/

public function destroy($ob_id)
{
OBMasterModel::where('ob_id',$ob_id )->update(array('is_deleted' => 1));
Session::flash('delete', 'Deleted record successfully'); 
}
public function delete_operation(Request $request)
{
    
    echo $request->dataId;

}



}