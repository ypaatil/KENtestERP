<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\BrandModel;
use App\Models\SeasonModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\SizeModel;
use Illuminate\Support\Facades\DB;
use App\Models\MaterialReturnModel;
use App\Models\MaterialReturnDetailModel;
use App\Models\SparePurchaseOrderModel;
use App\Models\LocationModel;
use Session;

class MaterialReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '306')
        ->first();
        
        $ReturnList = MaterialReturnModel::join('usermaster', 'usermaster.userId', '=', 'materialReturnMaster.userId')
                ->leftJoin('location_master', 'location_master.loc_id', '=', 'materialReturnMaster.loc_id')
                ->orderBy('materialReturnMaster.materialReturnCode', 'DESC')
                ->get(['materialReturnMaster.*', 'usermaster.username', 'location_master.location']);
                
        return view('materialReturnList', compact('chekform', 'ReturnList'));



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $machineList= DB::table('machine_master')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $itemlist= DB::table('spare_item_master')->where('delflag', '=', 0)->get(); 
        $statusList= DB::table('spare_return_material_status')->where('delflag','=', '0')->get();
        return view('materialReturn',compact('machineList', 'LocationList', 'itemlist', 'statusList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
              ->where('c_name','=','C1')
              ->where('type','=','Material_Return')
               ->where('firm_id','=',1)
              ->first();
              
        $materialReturnCode=$codefetch->code.'-'.$codefetch->tr_no; 
        
        $data1=array(
                    'materialReturnCode'=>$materialReturnCode,
                    'materialReturnDate'=>$request->materialReturnDate, 
                    'loc_id'=>$request->loc_id, 
                    'totalqty'=>$request->totalqty, 
                    'remark'=>$request->remark, 
                    'delflag'=>0,
                    'userId'=>$request->userId, 
                    'created_at'=>date("Y-m-d H:i:s"),  
                );
             
        MaterialReturnModel::insert($data1);
            
        $spare_item_codes= $request->spare_item_codes;
                     
        for($x=0; $x<count($spare_item_codes); $x++) 
        {
           if($request->spare_item_codes[$x]!=0)
           {
        
                $data2=array(
                         'materialReturnCode' =>$materialReturnCode,
                         'materialReturnDate' => $request->materialReturnDate,
                         'machine_id' => $request->machine_id[$x],
                         'spare_item_code' => $request->spare_item_codes[$x],
                         'item_qty' => $request->item_qtys[$x],
                         'spare_return_material_status_id' => $request->spare_return_material_status_id[$x],
                         'loc_id'=>$request->loc_id, 
                     );
                    
                    MaterialReturnDetailModel::insert($data2);
            }
        }  
        
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='Material_Return'");
        
        return redirect()->route('MaterialReturn.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialReturnModel  $materialReturnModel
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialReturnModel $materialReturnModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialReturnModel  $materialReturnModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $machineList= DB::table('machine_master')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $itemlist= DB::table('spare_item_master')->where('delflag', '=', 0)->get(); 
        $statusList= DB::table('spare_return_material_status')->where('delflag','=', '0')->get();

        $materialReturnCode=base64_decode($id);
        $MaterialReturnMasterList = MaterialReturnModel::find($materialReturnCode); 

        $materialReturnDetailslist = DB::table('materialReturnDetails')
            ->select('materialReturnDetails.*','spare_item_master.item_description', 'spare_item_master.spare_item_code')
            ->join('spare_item_master', 'spare_item_master.spare_item_code', '=', 'materialReturnDetails.spare_item_code')
            ->where('materialReturnCode','=',$materialReturnCode)
            ->get();

 
        return view('materialReturnEdit',compact('MaterialReturnMasterList','machineList','LocationList','itemlist','materialReturnDetailslist', 'statusList'));    
 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialReturnModel  $materialReturnModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $materialReturnCode)
    { 
            $materialReturnCode= $request->materialReturnCode;
            
            $data1=array(
                'materialReturnCode'=>$materialReturnCode,
                'materialReturnDate'=>$request->materialReturnDate, 
                'loc_id'=>$request->loc_id, 
                'totalqty'=>$request->totalqty, 
                'remark'=>$request->remark, 
                'delflag'=>0,
                'userId'=>$request->userId, 
                'updated_at'=>date("Y-m-d H:i:s"),  
            );
            
            $Return = MaterialReturnModel::findOrFail($materialReturnCode);  
            $Return->fill($data1)->save();
            
            
            DB::table('materialReturnDetails')->where('materialReturnCode',$materialReturnCode)->delete(); 
            
            $spare_item_codes = $request->spare_item_codes;
                     
            for($x=0; $x<count($spare_item_codes); $x++) 
            {
               if($request->spare_item_codes[$x]!=0)
               {
            
                    $data2=array(
                         'materialReturnCode' =>$materialReturnCode,
                         'materialReturnDate' => $request->materialReturnDate,
                         'machine_id' => $request->machine_id[$x],
                         'spare_item_code' => $request->spare_item_codes[$x],
                         'item_qty' => $request->item_qtys[$x],
                         'spare_return_material_status_id' => $request->spare_return_material_status_id[$x],
                         'loc_id'=>$request->loc_id, 
                         );
                        
                        MaterialReturnDetailModel::insert($data2);
                }
            }  
            return redirect()->route('MaterialReturn.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialReturnModel  $materialReturnModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($materialReturnCode)
    {  
        $materialReturnCode=base64_decode($materialReturnCode);
        
        MaterialReturnModel::where('materialReturnCode',$materialReturnCode)->delete();
        MaterialReturnDetailModel::where('materialReturnCode',$materialReturnCode)->delete();
     
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function GetMachineDetails(Request $request)
    {
        $machine_id = $request->machine_id;
        $machineData = DB::table('machine_master')->where('MachineID', '=', $machine_id)->first();
        return $machineData;
    }
    
    public function GetItemDescriptionForMachine(Request $request)
    {
        $itemlist=DB::table('spare_item_master')->where('spare_item_code','=',$request->spare_item_code)->first();
        
        return response()->json(['spare_item_code'=> $itemlist->spare_item_code ,'item_description' => $itemlist->item_description]); 
    }
    
    public function GetPOListFromSpareItemCode(Request $request)
    { 
        
        $Data = DB::SELECT("SELECT pur_code FROM purchaseorder_detail WHERE spare_item_code=".$request->spare_item_code." GROUP BY pur_code");

        $html = '<option value="">--Select--</option>';
        foreach($Data as $row)
        {
            $html .='<option value="'.$row->pur_code.'">'.$row->pur_code.'</option>';
        } 
        return response()->json(['html' => $html]);
    } 

}
