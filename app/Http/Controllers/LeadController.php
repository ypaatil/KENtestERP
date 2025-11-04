<?php

namespace App\Http\Controllers;
 
use App\Models\ItemModel; 
use App\Models\LeadModel;
use App\Models\LeadDetailModel;
use App\Models\PerticularModel;
use App\Models\LedgerModel;
use App\Models\BrandModel;
use App\Models\MainStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class LeadController extends Controller
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
        ->where('form_id', '272')
        ->first();

        $crmData = LeadModel::select('crm_master.*','order_group_master.order_group_name','currency_master.currency_name',
            'country_master.c_name','lead_status.lead_status_name','usermaster.username')
            ->leftjoin('order_group_master', 'order_group_master.og_id', '=', 'crm_master.order_group_id') 
            ->leftjoin('currency_master', 'currency_master.cur_id', '=', 'crm_master.cur_id') 
            ->leftjoin('country_master', 'country_master.c_id', '=', 'crm_master.country_id') 
            ->leftjoin('lead_status', 'lead_status.lead_status_id', '=', 'crm_master.lead_status_id') 
            ->leftjoin('usermaster', 'usermaster.userId', '=', 'crm_master.userId') 
            ->where('crm_master.delflag', '=', '0')
            ->orderBy('crm_master.crm_id', 'DESC')
            ->get();

        return view('LeadMasterList', compact('crmData','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $BuyerTypeList = DB::SELECT("SELECT * FROM buyer_type WHERE delflag=0");
        $OrderGroupList = DB::SELECT("SELECT * FROM order_group_master WHERE delflag=0 AND og_id IN(1,2)");
        $countryList = DB::SELECT("SELECT * FROM country_master WHERE delflag=0");
        $StateList = DB::SELECT("SELECT * FROM state_master WHERE delflag=0");
        $CityList = DB::SELECT("SELECT * FROM city_master WHERE delflag=0");
        $StatgeList = DB::SELECT("SELECT * FROM stage_master WHERE delflag=0");
        $LeadStatusList = DB::SELECT("SELECT * FROM lead_status WHERE delflag=0");  
        $CurrencyList = DB::SELECT("SELECT * FROM currency_master WHERE delflag=0"); 
        $CompliantList = DB::SELECT("SELECT * FROM compliant_status_master WHERE delflag=0"); 
        
        return view('LeadMaster',compact('BuyerTypeList','OrderGroupList', 'countryList','StateList', 'CityList', 'StatgeList', 'LeadStatusList', 'CurrencyList', 'CompliantList'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
            $data1=array
            (   
                'buyer_name'=>$request->buyer_name, 
                'buyer_brand'=>$request->buyer_brand, 
                'buyer_type_id'=>$request->buyer_type_id, 
                'order_group_id'=>$request->order_group_id,
                'country_id'=>$request->country_id,
                'state_id'=>$request->state_id,
                'city_name'=>$request->city_name,
                'zip_code'=>$request->zip_code,
                'street_name'=>$request->street_name,
                'stage_id'=>$request->stage_id,
                'lead_status_id'=>$request->lead_status_id,
                'compliant_status_id'=>$request->compliant_status_id,
                'cur_id'=>$request->cur_id,
                'ownership_name'=>$request->ownership_name,
                'userId'=>Session::get('userId'), 
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'delflag'=>0
             );
         
            LeadModel::insert($data1);  
            $maxId = LeadModel::max('crm_id');
            $contactName = $request->contactName;
            if(count($contactName)>0)
            {   
                    
                for($x=0; $x<count($contactName); $x++) 
                {
                   
                    $data2 =array
                    ( 
                    'crm_id'=>$maxId, 
                    'contactName'=>$request->contactName[$x],
                    'contactNo'=>$request->contactNo[$x],
                    'email'=>$request->email[$x],  
                   );
                  
                    LeadDetailModel::insert($data2); 
                } 
            }
        
        return redirect()->route('Lead.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\crm  $crm
     * @return \Illuminate\Http\Response
     */
    public function show(crm $crm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\crm  $crm
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $CRMMaster = LeadModel::find($id);   
        $CRMDetails = LeadDetailModel::where('crm_id', $id)->get();   
       
        $BuyerTypeList = DB::SELECT("SELECT * FROM buyer_type WHERE delflag=0");
        $OrderGroupList = DB::SELECT("SELECT * FROM order_group_master WHERE delflag=0 AND og_id IN(1,2)");
        $countryList = DB::SELECT("SELECT * FROM country_master WHERE delflag=0");
        $StateList = DB::SELECT("SELECT * FROM state_master WHERE delflag=0");
        $CityList = DB::SELECT("SELECT * FROM city_master WHERE delflag=0");
        $StatgeList = DB::SELECT("SELECT * FROM stage_master WHERE delflag=0");
        $LeadStatusList = DB::SELECT("SELECT * FROM lead_status WHERE delflag=0"); 
        $CurrencyList = DB::SELECT("SELECT * FROM currency_master WHERE delflag=0"); 
        $CompliantList = DB::SELECT("SELECT * FROM compliant_status_master WHERE delflag=0"); 
        
        return view('LeadMasterEdit',compact('CRMMaster', 'BuyerTypeList','OrderGroupList', 'countryList','StateList', 'CityList', 'StatgeList', 'LeadStatusList', 'CurrencyList', 'CompliantList', 'CRMDetails')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\crm  $crm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $data1=array
        (  
            'crm_id'=>$request->crm_id,
            'buyer_name'=>$request->buyer_name, 
            'buyer_brand'=>$request->buyer_brand, 
            'buyer_type_id'=>$request->buyer_type_id, 
            'order_group_id'=>$request->order_group_id,
            'country_id'=>$request->country_id,
            'state_id'=>$request->state_id,
            'city_name'=>$request->city_name,
            'zip_code'=>$request->zip_code,
            'street_name'=>$request->street_name,
            'stage_id'=>$request->stage_id,
            'lead_status_id'=>$request->lead_status_id,
            'compliant_status_id'=>$request->compliant_status_id,
            'cur_id'=>$request->cur_id,
            'ownership_name'=>$request->ownership_name,
            'userId'=>Session::get('userId'),  
            'updated_at'=>date("Y-m-d H:i:s"),
            'delflag'=>0
         );
         
        $crmList = LeadModel::findOrFail($request->crm_id);
        $crmList->fill($data1)->save();
        
        $contactName = $request->contactName;
        
        DB::table('crm_details')->where('crm_id', $request->crm_id)->delete();
        
        if(count($contactName)>0)
        {   
                
            for($x=0; $x<count($contactName); $x++) 
            {
               
                $data2 =array
                ( 
                'crm_id'=>$request->crm_id, 
                'contactName'=>$request->contactName[$x],
                'contactNo'=>$request->contactNo[$x],
                'email'=>$request->email[$x],  
               );
              
                LeadDetailModel::insert($data2); 
            } 
        }
        
        return redirect()->route('Lead.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\crm  $crm
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        LeadModel::where('crm_id', $id)->delete();  
        LeadDetailModel::where('crm_id', $id)->delete();  
        return 1;
    } 
   
    public function crmPrint($crm_id)
    {
        
        $CRMMaster = LeadModel::leftjoin('perticular_master', 'perticular_master.perticular_id', '=', 'crm_master.perticular_id') 
            ->select('crm_master.*', 'perticular_master.perticular_name') 
            ->where('crm_master.delflag', '=', '0')
            ->where('crm_master.crm_id', '=', $crm_id)
            ->first();
            
        $crmDetail = LeadDetailModel::leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'crm_details.Ac_code') 
                                ->leftjoin('brand_master', 'brand_master.brand_id', '=', 'crm_details.brand_id') 
                                ->select('crm_details.*', 'brand_master.brand_name', 'ledger_master.ac_short_name')  
                                ->where('crm_details.crm_id', '=', $crm_id)
                                ->get();
            
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
       return view('crmPrint', compact('CRMMaster','crmDetail','FirmDetail'));
      
    }
    
    public function CRMReportPrint(Request $request)
    {
        $CRMMasterData = DB::SELECT("SELECT opportunity_master.*,opportunity_details.*, gender_master.gender_name,currency_master.currency_name,crm_master.*,
                    order_group_master.order_group_name,state_master.state_name,crm_details.*,main_style_master.mainstyle_name,opportunity_stage.opportunity_stage_name FROM opportunity_master 
                    LEFT JOIN opportunity_details ON opportunity_details.opportunity_id = opportunity_master.opportunity_id
                    LEFT JOIN crm_master ON crm_master.crm_id = opportunity_master.Ac_code
                    LEFT JOIN crm_details ON crm_details.crm_id = crm_master.crm_id
                    LEFT JOIN state_master ON state_master.state_id = crm_master.state_id
                    LEFT JOIN main_style_master ON main_style_master.mainstyle_id = opportunity_details.main_style_id
                    LEFT JOIN gender_master ON gender_master.gender_id = opportunity_details.gender_id
                    LEFT JOIN currency_master ON currency_master.cur_id = opportunity_details.cur_id
                    LEFT JOIN opportunity_stage ON opportunity_stage.opportunity_stage_id = opportunity_details.opportunity_stage_id
                    LEFT JOIN order_group_master ON order_group_master.og_id = crm_master.order_group_id");
                    
                    
        // $CRMMaster = LeadModel::leftjoin('perticular_master', 'perticular_master.perticular_id', '=', 'crm_master.perticular_id') 
        //     ->select('crm_master.*', 'perticular_master.perticular_name') 
        //     ->where('crm_master.delflag', '=', '0')
        //     ->where('crm_master.crm_id', '=', $crm_id)
        //     ->first();
            
        // $crmDetail = LeadDetailModel::leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'crm_details.Ac_code') 
        //                         ->leftjoin('brand_master', 'brand_master.brand_id', '=', 'crm_details.brand_id') 
        //                         ->select('crm_details.*', 'brand_master.brand_name', 'ledger_master.ac_short_name')  
        //                         ->where('crm_details.crm_id', '=', $crm_id)
        //                         ->get();
            
        // $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
       return view('CRMReportPrint', compact('CRMMasterData')); 
      
    }
}
