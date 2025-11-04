<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\POAuthorityMatrixModel;
use App\Models\NewJobOpeningDetailModel;
use App\Models\DailyProductionEntryDetailOperationModel;
use App\Models\OBMasterModel;
use Illuminate\Http\Request;
use DataTables;
use Session;
use DB;
use App\Traits\EmployeeTrait;
use DatePeriod;
use DateTime;
use DateInterval;
use App\Models\BrandModel;

class POAuthorityMatrixController extends Controller
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
        ->where('form_id', '31')
        ->first();
        
        
       if($request->ajax()) 
        {
            $data=POAuthorityMatrixModel::select("po_authority_matrix.po_authority_id",
            "po_authority_matrix.po_authority_date","usermaster.username","po_authority_matrix.ac_code",'order_qty','shipping_allowance','fabric_extra_order',
            'level1_sewing_trim_extra_order','level1_packing_trim_extra_order','level2_sewing_trim_extra_order','level2_packing_trim_extra_order',
            'level3_sewing_trim_extra_order','level3_packing_trim_extra_order','remarks','ledger_master.ac_name','brand_master.brand_name')
            ->join('usermaster','usermaster.userId','=','po_authority_matrix.userId') 
            ->join('brand_master','brand_master.Ac_code','=','po_authority_matrix.ac_code')
            ->join('ledger_master','ledger_master.Ac_code','=','po_authority_matrix.ac_code')
            ->groupBy('po_authority_matrix.po_authority_id');  
            
            return Datatables::of($data)
            ->addIndexColumn()
  
            ->addColumn('action1', function($row)
            {
                $btn = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('po_authority_matrix.edit', $row['po_authority_id']).'" >  <i class="fas fa-pencil-alt"></i></a>';
                return $btn;
            })
            ->addColumn('action2', function($row)
            {
                
                  if(Session::get('user_type')==1)
            {
                
                $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row['po_authority_id'].'"  data-route="'.route('po_authority_matrix.destroy', $row['po_authority_id']).'"><i class="fas fa-trash"></i></a>';
                return $btn3;
                
            } else{
                
               $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete" ><i class="feather feather-lock-2"></i></a>';
                return $btn3;   
                
            }
                
                
            })
            ->rawColumns(['action1','action2'])
            ->make(true);
        }
          
        
            return view('POAuthorityMatrixList',compact('chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

          
       // $operationList=DB::table('ob_masters')->select('operation_id','operation_name')->get();  
        
  
         
          $brandList = BrandModel::where('brand_master.delflag','=', '0')->get();
         
        
         $BuyerList=DB::table('brand_master')
         ->join('ledger_master','ledger_master.ac_code','=','brand_master.Ac_code')
         ->select('brand_master.brand_id','brand_master.Ac_code','ledger_master.ac_name')->groupBy('brand_master.Ac_code')->get(); 
        
        return view('POAuthorityMatrix',compact('brandList','BuyerList'));

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
           
             
          $data = $request->all();
          
          //dd($data); exit;
          
          DB::beginTransaction();
           
           $id = $data['id'] ?? null;
           
           
           $data['po_authority_date']=date('Y-m-d');
     
        
            $IODetails = POAuthorityMatrixModel::updateOrCreate(
                ['po_authority_id'=> $id],
                $data);
                
                
           
            DB::commit();
            $msg = "";

            if($id == null){
                $msg = 'Purchase order authority matrix saved successfully';
            } else {
                $msg = 'Purchase order authority matrix updated successfully';
            }


         return redirect()->route('po_authority_matrix.index')->with('message', $msg);
         
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
     * @param  \App\Models\POAuthorityMatrixModel  $POAuthorityMatrixModel
     * @return \Illuminate\Http\Response
     */
    public function show(POAuthorityMatrixModel $POAuthorityMatrixModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\POAuthorityMatrixModel  $POAuthorityMatrixModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         
       $POAMFetch =POAuthorityMatrixModel::find($id);
       
       
          $brandList = BrandModel::where('brand_master.delflag','=', '0')->get();
      
          $BuyerList=DB::table('brand_master')
          ->join('ledger_master','ledger_master.ac_code','=','brand_master.Ac_code')
          ->select('brand_master.brand_id','brand_master.Ac_code','ledger_master.ac_name')->groupBy('brand_master.Ac_code')->get(); 

        
        return view('POAuthorityMatrix',compact('brandList','BuyerList','POAMFetch'));
   
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\POAuthorityMatrixModel  $POAuthorityMatrixModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
   
   


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\POAuthorityMatrixModel  $POAuthorityMatrixModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        POAuthorityMatrixModel::where('daily_pr_entry_id',$id)->delete();
      
       // return redirect()->route('daily_production_entry.index')->with('message', 'Delete Record Succesfully');


    }

    
}
