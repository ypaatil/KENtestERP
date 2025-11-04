<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\OBMasterModel;
use App\Models\OBDetailModel;
use DB;
use DataTables;
use Session;
use App\Traits\EmployeeTrait;

class AssignToOrderController extends Controller
{
/**
* Display a listing of the resource.
*
* @return \Illuminate\Http\Response
*/

   use EmployeeTrait;  

public function index(Request $request)
{
   
            
            
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '312')
        ->first();  
            
        if ($request->ajax()) 
        {
             $data=DB::table('assigned_to_orders')
            ->join('main_style_master_operation','main_style_master_operation.mainstyle_id','=','assigned_to_orders.mainstyle_id_operation');
            

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action1', function($row) use ($chekform)
            {
               
                
                if($chekform->edit_access==1)
                {   
                
                $btn = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('assign_to_order.edit', $row->assigned_to_order_id).'" ><i class="feather feather-edit" data-toggle="tooltip" data-original-title="Edit"></i></a>';
                
                } else{
                    
                    $btn = '
                    <a class="btn btn-primary btn-icon btn-sm">
                    <i class="feather feather-lock" data-toggle="tooltip" data-original-title="Edit"></i>
                    </a>
                    ';         
                    
                }
                
                   return $btn;
                
                
            })
            ->addColumn('action2', function($row) use ($chekform)
            {
                
                 if($chekform->delete_access==1)
                {
                $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->assigned_to_order_id.'"  data-route="'.route('assign_to_order.destroy', $row->assigned_to_order_id).'"><i class="feather feather-trash-2"></i></a>';
                
                } else{
                    
                
                $btn3 = '
                <a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"><i class="feather feather-lock"></i></a>
                ';   
                }
                
                return $btn3;
            })
            ->rawColumns(['action1','action2'])
            ->make(true);
        }
            
        

        return view('Operation.assign_style_to_order_master_list');
}
/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/
public function create(Request $request)
{ 
    
    $categoryList = DB::table('item_category')->Select('*')->get();    
    
    $styleList = DB::table('main_style_master_operation')->Select('*')->get();    
    
    $subCategoryList = DB::table('sub_category_masters')->Select('*')->get();
    
    $groupList = DB::table('group_masters')->Select('*')->get();  
    
    $machineTypeList = DB::table('machine_type_masters')->Select('*')->get();  
    
    $orderList = DB::table('buyer_purchse_order_master')->Select('tr_code')->get(); 
    
    return view('Operation.assign_style_to_order_master',compact('categoryList','subCategoryList','groupList','machineTypeList','styleList','orderList'));

}



/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(Request $request)
{
    
             try {  
             
     
          
           DB::beginTransaction();
           
           $data=array(
                'mainstyle_id_operation'=> $request->mainstyle_id,
                'sales_order_no'=> $request->sales_order_no
                );
           
             
              $Assign = DB::table('assigned_to_orders')->insert($data);
             
            DB::commit();   
  

                $msg = 'Assign To Order record saved successfully';
          
         

         return redirect()->route('assign_to_order.index')->with('message', $msg);
         
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





    public function get_cat_sub_cat_by_style(Request $request)
    {
    
    $stylefetch = DB::table('main_style_master_operation')->Select('cat_id','sub_cat_id')->where('mainstyle_id',$request->mainstyle_id)->first();  
    
     return response()->json($stylefetch);
    
    }


public function edit($ob_id)
{
 
 $categoryList = DB::table('item_category')->Select('*')->get();    

$styleList = DB::table('main_style_master_operation')->Select('*')->get();    

$subCategoryList = DB::table('sub_category_masters')->Select('*')->get();

$groupList = DB::table('group_masters')->Select('*')->get();  

$machineTypeList = DB::table('machine_type_masters')->Select('*')->get();  
//DB::enableQueryLog();
  $obFetch =OBMasterModel::find($ob_id);
//dd(DB::getQueryLog());


  $obFetchDetail=OBDetailModel::where('ob_id',$ob_id)->get();



return view('Operation.ob_master',compact('categoryList','subCategoryList','groupList','machineTypeList','obFetch','styleList','obFetchDetail'));
}



        public function get_sales_order_by_style(Request $request)
    {
        
      
        return response()->json(['html' => $this->get_sales_order_by_style_trait()]);

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

public function destroy($assigned_to_order_id)
{
        DB::table('assigned_to_orders')->where('assigned_to_order_id',$assigned_to_order_id )->delete();
       
        Session::flash('delete', 'Deleted record successfully'); 
}
}