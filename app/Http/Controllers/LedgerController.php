<?php

namespace App\Http\Controllers;


use App\Models\LedgerModel;
use App\Models\Country;
use App\Models\State;
use App\Models\DistrictModel;
use App\Models\TalukaModel;
use App\Models\AcGroupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LedgerDetailModel;
use Session;

class LedgerController extends Controller
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
            ->where('form_id', '21')
            ->first();

        
        $Ledger = LedgerModel::join('usermaster', 'usermaster.userId', '=', 'ledger_master.userId', 'left outer')
            ->join('accountgroup', 'accountgroup.Group_code', '=', 'ledger_master.group_code', 'left outer')
            ->join('country_master', 'country_master.c_id', '=', 'ledger_master.c_id', 'left outer')
            ->join('state_master', 'state_master.state_id', '=', 'ledger_master.state_id', 'left outer')
            ->join('district_master', 'district_master.d_id', '=', 'ledger_master.dist_id', 'left outer')
            ->join('business_type', 'business_type.Bt_id', '=', 'ledger_master.bt_id', 'left outer')
            ->where('ledger_master.delflag','=', '0') 
            ->get(['ledger_master.*','usermaster.username','country_master.c_name','state_master.state_name','district_master.d_name', 'business_type.Bt_name','accountgroup.Group_name' ]);
        
        return view('Ledger_Master_List', compact('Ledger','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   $AcGroup = DB::table('accountgroup')->get();
         
        $Countrys = Country::where('country_master.delflag','=', '0')->get();
        $State = State::where('state_master.delflag','=', '0')->get();
        $District = DB::table('district_master')->get();
        $Taluka = DB::table('taluka_master')->get();
        $BusinessType = DB::table('business_type')->get();
        $Account_Type = DB::table('account_type_master')->get();
        $statusList = DB::table('status_master')->get();

        return view('Ledger_Master',compact('AcGroup','Countrys','State','District','Taluka','BusinessType','Account_Type','statusList'));
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
             
            'ac_name'=> 'required',
            // 'group_code'=> 'required',
            // 'op_bal'=> 'required', 
            // 'op_dc'=> 'required', 
            // 'address'=> 'required',
            // 'c_id'=> 'required',
            // 'state_id'=> 'required',
            // 'dist_id'=> 'required', 
            // 'taluka_id'=> 'required', 
            // 'city_name'=> 'required',
            // 'phone'=> 'required', 
            // 'mobile'=> 'required',
            // 'email'=> 'required',
            // 'pan_no'=> 'required',
            // 'gst_no'=> 'required', 
            // 'adhar_no'=> 'required', 
            // 'bt_id'=> 'required',
            // 'bank_name'=> 'required',
            // 'account_name'=> 'required',
            // 'account_no'=> 'required',
            // 'ac_id'=> 'required', 
            // 'ifsc_code'=> 'required', 
            // 'tds_type'=> 'required',
            // 'tds_per'=> 'required',
            // 'note'=> 'required',
              
    ]);

     

    $data1=array(
           
            'ac_code'=>$request->ac_code,
            'ac_name'=>$request->ac_name,
            'trade_name'=>$request->trade_name,
            'ac_short_name'=>$request->ac_short_name,
            'group_code'=>$request->group_code ,
            'group_main'=>$request->group_main ,
            'op_bal'=>$request->op_bal,
            'op_dc'=>$request->op_dc ,
            'address'=>$request->address ,
            'c_id'=>$request->c_id,
            'state_id'=>$request->state_id,
            'dist_id'=>$request->dist_id ,
            'taluka_id'=>$request->taluka_id,
            'city_name'=>$request->city_name,
            'phone'=>$request->phone,
            'mobile'=>$request->mobile,
            'status_id'=>$request->status_id,
            'email'=>$request->email,
            'pan_no'=>$request->pan_no ,
            'gst_no'=>$request->gst_no ,
            'note'=>$request->note,
            'adhar_no'=>$request->adhar_no,
            'bt_id'=>$request->bt_id ,
            'bt_id1'=>$request->bt_id1,
            'bt_id2'=>$request->bt_id2,
            'bank_name'=>$request->bank_name, 
            'account_name'=>$request->account_name,
            'ac_id'=>$request->ac_id,
            'account_no'=>$request->account_no,
            'ifsc_code'=>$request->ifsc_code ,
            'tds_type'=>$request->tds_type ,
            'tds_per'=>$request->tds_per ,
            'userId'=>$request->userId ,
            'pin_code'=>$request->pin_code ,
            'msme_code'=>$request->msme_code ,
            'cin_no'=>$request->cin_no ,
            'branch_name'=>$request->branch_name ,
            'delflag'=>'0',
            'isPackingInward'=>$request->isPackingInwardValue,
    ); 
 
     try {
        LedgerModel::insert($data1);
    } catch (\Exception $e) {
        dd("Insert failed:", $e->getMessage());
    }
    //   LedgerModel::insert($data1);
 
    // DB::enableQueryLog(); 
    $Ac_code = LedgerModel::max('ac_code');
    //    $query = DB::getQueryLog();
    //            $query = end($query);
    //            dd($query);
    //print_r($Ac_code) ;
   
    $site_code = $request->input('site_code');
if(count($site_code)>0)
{

for($x=0; $x<count($site_code); $x++) 
{

$data2=array(
            
    'ac_code'=>$Ac_code,
    'site_code' => $request->site_code[$x],
    'pan_no'=>$request->pan_nos[$x],
    'gst_no'=>$request->gst_nos[$x], 
    'trade_name'=>$request->trade_names[$x],
    'addr1'=>$request->addr1[$x],
    'state_id'=>$request->state_ids[$x],
    'pin_code'=>$request->pin_codes[$x],
  
     );
     
    LedgerDetailModel::insert($data2);
  
    }
}
 

    return redirect()->route('Ledger.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LedgerModel  $ledgerModel
     * @return \Illuminate\Http\Response
     */
    public function show(LedgerModel $ledgerModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LedgerModel  $ledgerModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $AcGroup = DB::table('accountgroup')->get();
        $Ledger = LedgerModel::find($id);
        // select * from transport_master where transport_id=$id;
        $AcGroup = DB::table('accountgroup')->get();
        $Account_Type = DB::table('account_type_master')->get();
        $Countrys = Country::where('country_master.delflag','=', '0')->get();
        $State = State::where('state_master.delflag','=', '0')->get();
        $District = DB::table('district_master')->get();
        $Taluka = DB::table('taluka_master')->get();
        $BusinessType = DB::table('business_type')->get();
        $statusList = DB::table('status_master')->get();
 
        $LedgerDetailList = LedgerDetailModel::where('ledger_details.ac_code','=', $Ledger->ac_code)->get(['ledger_details.*']);
    
        return view('Ledger_Master',compact('Ledger','AcGroup','Countrys','State','District','Taluka','BusinessType','Account_Type','LedgerDetailList','statusList'));
   

        //return view('Ledger_Master', compact('Ledger','AcGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LedgerModel  $ledgerModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       //echo '<pre>'; print_r($_POST);exit;
       //// $Ledger = LedgerModel::findOrFail($id);

        $this->validate($request, [
            'ac_name'=> 'required',
            // 'group_code'=> 'required',
            // 'op_bal'=> 'required', 
            // 'op_dc'=> 'required', 
            // 'address'=> 'required',
            // 'c_id'=> 'required',
            // 'state_id'=> 'required',
            // 'dist_id'=> 'required', 
            // 'taluka_id'=> 'required', 
            // 'city_name'=> 'required',
            // 'phone'=> 'required', 
            // 'mobile'=> 'required',
            // 'email'=> 'required',
            // 'pan_no'=> 'required',
            // 'gst_no'=> 'required', 
            // 'adhar_no'=> 'required', 
            // 'bt_id'=> 'required',
            // 'bank_name'=> 'required',
            // 'account_name'=> 'required',
            // 'account_no'=> 'required',
            // 'ac_id'=> 'required', 
            // 'ifsc_code'=> 'required', 
            // 'tds_type'=> 'required',
            // 'tds_per'=> 'required',
            // 'note'=> 'required',
        ]);

        // $input = $request->all();

        // $Ledger->fill($input)->save();


        $data1=array(
           
            'ac_code'=>$request->ac_code,
            'ac_name'=>$request->ac_name,
            'trade_name'=>$request->trade_name,
            'ac_short_name'=>$request->ac_short_name,
            'group_code'=>$request->group_code ,
            'group_main'=>$request->group_main ,
            'op_bal'=>$request->op_bal,
            'op_dc'=>$request->op_dc ,
            'address'=>$request->address ,
            'c_id'=>$request->c_id,
            'state_id'=>$request->state_id,
            'dist_id'=>$request->dist_id ,
            'taluka_id'=>$request->taluka_id,
            'city_name'=>$request->city_name,
            'phone'=>$request->phone,
            'mobile'=>$request->mobile,
            'status_id'=>$request->status_id,
            'email'=>$request->email,
            'pan_no'=>$request->pan_no ,
            'gst_no'=>$request->gst_no ,
            'note'=>$request->note,
            'adhar_no'=>$request->adhar_no,
            'bt_id'=>$request->bt_id ,
            'bt_id1'=>$request->bt_id1,
            'bt_id2'=>$request->bt_id2,
            'bank_name'=>$request->bank_name, 
            'account_name'=>$request->account_name,
            'ac_id'=>$request->ac_id,
            'account_no'=>$request->account_no,
            'ifsc_code'=>$request->ifsc_code ,
            'pin_code'=>$request->pin_code ,
            'msme_code'=>$request->msme_code ,
            'cin_no'=>$request->cin_no ,
            'branch_name'=>$request->branch_name ,
            'tds_type'=>$request->tds_type ,
            'tds_per'=>$request->tds_per ,
            'userId'=>$request->userId ,
            'delflag'=>'0',
            'isPackingInward'=>$request->isPackingInwardValue,
            
      
);

 
$LedgerList = LedgerModel::findOrFail($request->input('ac_code'));  
$LedgerList->fill($data1)->save();
 
DB::table('ledger_details')->where('ac_code', $request->input('ac_code'))->delete();

$site_code = $request->site_code;
if(count($site_code)>0)
{

for($x=0; $x<count($site_code); $x++) {

    $data2=array( 
        'ac_code'=>$request->ac_code,
        'site_code' => $request->site_code[$x],
        'pan_no'=>$request->pan_nos[$x],
        'gst_no'=>$request->gst_nos[$x], 
        'trade_name'=>$request->trade_names[$x],
        'addr1'=>$request->addr1[$x],
        'state_id'=>$request->state_ids[$x],
        'pin_code'=>$request->pin_codes[$x],
     );
     
     LedgerDetailModel::insert($data2);

    }
}
 
        return redirect()->route('Ledger.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LedgerModel  $ledgerModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LedgerModel::where('ac_code', $id)->update(array('delflag' => 1));
        return redirect()->route('Ledger.index')->with('messagedelete', 'Delete Record Succesfully');
    }

public function GetStateList(Request $request)
{
    if (!$request->country_id) {
        $html = '<option value="">--Select State--</option>';
        } else {
        $html = '';
        $states = State::where('country_id', $request->country_id)->get();
        foreach ($states as $row) {
                $html .= '<option value="'.$row->state_id.'">'.$row->state_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
}

public function GetDistrictList(Request $request)
{
    if (!$request->state_id) {
        $html = '<option value="">--Select District--</option>';
        } else {
        $html = '';
        $states = DistrictModel::where('state_id', $request->state_id)->get();
        foreach ($states as $row) {
                $html .= '<option value="'.$row->d_id.'">'.$row->d_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
}

public function GetTalukaList(Request $request)
{
    if (!$request->dist_id) {
        $html = '<option value="">--Select Taluka--</option>';
        } else {
        $html = '';
        
        $states = DB::table('taluka_master')->where('dist_id', $request->dist_id)->get();
        //$states = TalukaModel::where('dist_id', $request->dist_id)->get();
        foreach ($states as $row) {
                $html .= '<option value="'.$row->tal_id.'">'.$row->taluka.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
}


}
