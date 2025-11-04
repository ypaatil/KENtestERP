<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KDPLWiseSetPercentageModel;
use DB;
use Session;

class KDPLWiseSetPercentageController extends Controller
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
            ->where('form_id', '49')
            ->first();
        $job_status_id = $request->job_status_id ? $request->job_status_id : 1;
   
        if($job_status_id > 0)
        {
            $SalesOrderList=DB::select('SELECT kdpl_wise_set_percentage.*, buyer_purchse_order_master.tr_code as sales_order_no FROM buyer_purchse_order_master 
                                    LEFT JOIN kdpl_wise_set_percentage ON kdpl_wise_set_percentage.sales_order_no = buyer_purchse_order_master.tr_code 
                                    where buyer_purchse_order_master.delflag=0 and og_id!=4 AND buyer_purchse_order_master.job_status_id='.$job_status_id.' order by tr_code desc');
        }
        else
        {    
            $SalesOrderList=DB::select('SELECT kdpl_wise_set_percentage.*, buyer_purchse_order_master.tr_code as sales_order_no FROM buyer_purchse_order_master 
                                    LEFT JOIN kdpl_wise_set_percentage ON kdpl_wise_set_percentage.sales_order_no = buyer_purchse_order_master.tr_code 
                                    where buyer_purchse_order_master.delflag=0 and og_id!=4 order by tr_code desc');
        }
        
        $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id IN(1,2)');
        
        return view('KDPLWiseSetPercentage', compact('SalesOrderList','JobStatusList','job_status_id','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $job_status_id = $request->job_status_id ? $request->job_status_id : 0;
        
        if($job_status_id > 0)
        {
            //DB::enableQueryLog();
            $SalesOrderList=DB::select('SELECT kdpl_wise_set_percentage.*, buyer_purchse_order_master.tr_code as sales_order_no FROM buyer_purchse_order_master 
                                    LEFT JOIN kdpl_wise_set_percentage ON kdpl_wise_set_percentage.sales_order_no = buyer_purchse_order_master.tr_code 
                                    where buyer_purchse_order_master.delflag=0 and og_id!=4 AND buyer_purchse_order_master.job_status_id='.$job_status_id.' order by tr_code desc');
           //dd(DB::getQueryLog());
        }
        else
        {    
            $SalesOrderList=DB::select('SELECT kdpl_wise_set_percentage.*, buyer_purchse_order_master.tr_code as sales_order_no FROM buyer_purchse_order_master 
                                    LEFT JOIN kdpl_wise_set_percentage ON kdpl_wise_set_percentage.sales_order_no = buyer_purchse_order_master.tr_code 
                                    where buyer_purchse_order_master.delflag=0 and og_id!=4 order by tr_code desc');
        }
        
        $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id IN(1,2)');
        
        return view('KDPLWiseSetPercentage', compact('SalesOrderList','JobStatusList','job_status_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        
        KDPLWiseSetPercentageModel::where('job_status_id', '=',$request->job_status_id)->delete(); 
        foreach($request->sales_order_no as $key => $value)
        { 
            
            $data1=array(
              'sales_order_no'=> $request->sales_order_no[$key],
              'job_status_id'=> $request->job_status_id,
              'leftover_fabric_value'=> $request->leftover_fabric_value[$key], 
              'leftover_trims_value'=> $request->leftover_trims_value[$key],
              'left_pcs_value'=> $request->left_pcs_value[$key],
              'rejection_pcs_value'=> $request->rejection_pcs_value[$key],
              "delflag"=> 0,
          );  
           //DB::enableQueryLog();
            KDPLWiseSetPercentageModel::create($data1);
           // dd(DB::getQueryLog());
        } 
    
        return redirect()->route('KDPLWiseSetPercentage.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KDPLWiseSetPercentageModel  $KDPLWiseSetPercentageModel
     * @return \Illuminate\Http\Response
     */
    public function show(KDPLWiseSetPercentageModel $KDPLWiseSetPercentageModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KDPLWiseSetPercentageModel  $KDPLWiseSetPercentageModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $JobPartList = KDPLWiseSetPercentageModel::find($id); 
        return view('jobPartEdit', compact('JobPartList','FGList','detailparts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KDPLWiseSetPercentageModel  $KDPLWiseSetPercentageModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
       
        for($x=0;$x<count($request->sales_order_no);$x++)  
        {
            
            $data1=array(
              'sales_order_no'=> $request->sales_order_no[$x],
              'job_status_id'=> $request->job_status_id[$x],
              'leftover_fabric_value'=> $request->leftover_fabric_value[$x], 
              'leftover_trims_value'=> $request->leftover_trims_value[$x],
              'left_pcs_value'=> $request->left_pcs_value[$x],
              'rejection_pcs_value'=> $request->rejection_pcs_value[$x],
              "delflag"=> 0,
           );  
           
            KDPLWiseSetPercentageModel::create($data1);
        } 
     
        return redirect()->route('KDPLWiseSetPercentage.index')->with('message', 'Updated Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KDPLWiseSetPercentageModel  $KDPLWiseSetPercentageModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       KDPLWiseSetPercentageModel::where('kwspId', $id)->delete();
       Session::flash('delete', 'Deleted record successfully'); 
    }
}
