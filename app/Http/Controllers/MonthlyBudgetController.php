<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Taluka;
use Illuminate\Http\Request;
use App\Models\MonthlyBudgetMasterModel;
 use App\Models\MonthlyBudgetDetailModel;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use Session;
use App\Models\LedgerModel;

class MonthlyBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $Data = MonthlyBudgetMasterModel::
             leftJoin('usermaster', 'usermaster.userId', '=', 'monthly_budget_masters.userId')
            ->leftJoin('monthMaster', 'monthMaster.monthId', '=', 'monthly_budget_masters.monthId')   
            ->where('monthly_budget_masters.is_deleted', '=', '0')
            ->orderBy('monthly_budget_masters.monthly_budget_id', 'asc')
            ->get();
                
        return view('MonthlyBudgetList', compact('Data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
       $monthlyList= DB::table('monthMaster')->get();

       

      
        
          $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        
        return view('MonthlyBudget', compact('monthlyList','Ledger'));
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
               
               
                  $MonthlyDate=date($request->year.'-'.$request->monthId.'-'.'01');
        
        
            $data1=array( 
                'monthly_budget_date'=>$MonthlyDate,
                'monthId'=>$request->monthId, 
                'year'=>$request->year,
                'total_lpc_sale'=>$request->total_lpc_sale,
                'total_fob_sale'=>$request->total_fob_sale,
                'total_rs_cr_sale'=>$request->total_rs_cr_sale,
                'total_lmin_sale'=>$request->total_lmin_sale,
                'total_cmohp'=>$request->total_cmohp,
                'total_lpc_production'=>$request->total_lpc_production,
                'total_fob_production'=>$request->total_fob_production,
                'total_rs_cr_production'=>$request->total_rs_cr_production,
                'total_l_min_production'=>$request->total_l_min_production,
                'total_cmohp_production'=>$request->total_cmohp_production,
                'total_l_mtr_purchase_fabric'=>$request->total_l_mtr_purchase_fabric,
                'total_rate_purchase_fabric'=>$request->total_rate_purchase_fabric,
                'total_rs_cr_purchase_fabric'=>$request->total_rs_cr_purchase_fabric,
                'total_days_purchase_fabric'=>$request->total_days_purchase_fabric,
                'total_rs_cr_purchase_trims'=>$request->total_rs_cr_purchase_trims,
                'total_days_purchase_trims'=>$request->total_days_purchase_trims,
                'total_l_pc_purchase_job_work'=>$request->total_l_pc_purchase_job_work,
                'total_rate_purchase_job_work'=>$request->total_rate_purchase_job_work,
                'total_rs_cr_job_work'=>$request->total_rs_cr_job_work,
                 'total_l_min_job_work'=>$request->total_l_min_job_work,
                 'grand_total_os'=>$request->grand_total_os,
                 'total_rs_cr_collection'=>$request->total_rs_cr_collection,
                 'userId'=>$request->userId,
                'created_at'=>date("Y-m-d H:i:s")
            );
         
            MonthlyBudgetMasterModel::insert($data1);
            
            $monthly_budget_id = DB::table('monthly_budget_masters')->max('monthly_budget_id');
            $lpc_sale = $request->lpc_sale;
    
            
            
            
            if(count($lpc_sale)>0)
            {
            
                for($x=0; $x<count($lpc_sale); $x++) 
                { 
                    $data2[] = array(
                        'monthly_budget_id'=>$monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                         'Ac_code'=>$request->Ac_code_sale[$x], 
                        'lpc'=>$request->lpc_sale[$x], 
                        'fob'=>$request->fob_sale[$x],
                        'rs_cr'=>$request->rs_cr_sale[$x], 
                        'l_min'=>$request->l_min_sale[$x],
                        'cmohp'=>$request->cmohp_sale[$x],
                        'remark'=>$request->remark_sale[$x],  
                        'flag'=>1
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data2);
            }
            
            $lpc_production = $request->lpc_production;
            if(count($lpc_production)>0)
            {
            
                for($x=0; $x<count($lpc_production); $x++) 
                { 
                  $data3[] = array(
                        'monthly_budget_id'=>$monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                         'Ac_code'=>$request->Ac_code_production[$x],   
                        'lpc'=>$request->lpc_production[$x], 
                        'fob'=>$request->fob_production[$x],
                        'rs_cr'=>$request->rs_cr_production[$x], 
                        'l_min'=>$request->l_min_production[$x],
                        'cmohp'=>$request->cmohp_production[$x],
                        'remark'=>$request->remark_production[$x],  
                        'flag'=>2
                    );
                   
                } 
                
                 MonthlyBudgetDetailModel::insert($data3);
            }
            
            $l_mtr_purchase_fabric = $request->l_mtr_purchase_fabric;
            if(count($l_mtr_purchase_fabric)>0)
            {
            
                for($x=0; $x<count($l_mtr_purchase_fabric); $x++) 
                { 
                     $data4[] = array(
                        'monthly_budget_id'=>$monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                          'Ac_code'=>$request->Ac_code_purchase_fabric[$x],    
                        'l_mtr'=>$request->l_mtr_purchase_fabric[$x], 
                        'rate'=>$request->rate_purchase_fabric[$x],
                        'rs_cr'=>$request->rs_cr_purchase_fabric[$x], 
                        'days'=>$request->days_purchase_fabric[$x],
                        'remark'=>$request->remark_purchase_fabric[$x],  
                        'flag'=>3
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data4);
            }
            


            $rs_cr_purchase_trims = $request->rs_cr_purchase_trims;
            if(count($rs_cr_purchase_trims)>0)
            {
            
                for($x=0; $x<count($rs_cr_purchase_trims); $x++) 
                { 
                     $data5[] = array(
                        'monthly_budget_id'=>$monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                         'Ac_code'=>$request->Ac_code_purchase_trims[$x],        
                        'rs_cr'=>$request->rs_cr_purchase_trims[$x], 
                        'days'=>$request->days_purchase_trims[$x],
                        'remark'=>$request->remark_purchase_trims[$x],  
                        'flag'=>4
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data5);
            }
            
            
            
                    $l_pc_purchase_job_work = $request->l_pc_purchase_job_work;
            if(count($l_pc_purchase_job_work)>0)
            {
            
                for($x=0; $x<count($l_pc_purchase_job_work); $x++) 
                { 
                     $data6[] = array(
                        'monthly_budget_id'=>$monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                           'Ac_code'=>$request->Ac_code_purchase_job_work[$x],       
                        'lpc'=>$request->l_pc_purchase_job_work[$x], 
                         'rate'=>$request->rate_purchase_job_work[$x], 
                         'rs_cr'=>$request->rs_cr_job_work[$x],      
                         'l_min'=>$request->l_min_job_work[$x],   
                         'remark'=>$request->remark_job_work[$x],  
                        'flag'=>5
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data6);
            }
  
  
  
                   $total_os = $request->total_os;
            if(count($total_os)>0)
            {
            
                for($x=0; $x<count($total_os); $x++) 
                { 
                     $data7[] = array(
                        'monthly_budget_id'=>$monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                         'Ac_code'=>$request->Ac_code_collection[$x],         
                        'total_os'=>$request->total_os[$x], 
                         'rs_cr'=>$request->rs_cr_collection[$x],      
                         'remark'=>$request->remark_collection[$x],  
                        'flag'=>6
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data7);
            }
            
            
             DB::commit();
             
             return redirect()->route('monthly_budget.index');
 
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
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function show(Taluka $taluka)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 

      
       
       
            $monthlyList= DB::table('monthMaster')->get();
        
            $MonthlyMasterList = MonthlyBudgetMasterModel::find($id);
        
            $sales=MonthlyBudgetDetailModel::where('monthly_budget_id',$MonthlyMasterList->monthly_budget_id)->where('flag',1)->get();
            $productionData=MonthlyBudgetDetailModel::select(DB::raw('Ac_code as Ac_code_production'),'lpc','fob','rs_cr','l_min','cmohp','l_mtr','rate','days','total_os','remark')->where('monthly_budget_id',$MonthlyMasterList->monthly_budget_id)->where('flag',2)->get();
            $purchase_fabric=MonthlyBudgetDetailModel::where('monthly_budget_id',$MonthlyMasterList->monthly_budget_id)->where('flag',3)->get();
            $purchase_trim=MonthlyBudgetDetailModel::where('monthly_budget_id',$MonthlyMasterList->monthly_budget_id)->where('flag',4)->get();
            $purchase_job_work=MonthlyBudgetDetailModel::where('monthly_budget_id',$MonthlyMasterList->monthly_budget_id)->where('flag',5)->get();
            $collection=MonthlyBudgetDetailModel::where('monthly_budget_id',$MonthlyMasterList->monthly_budget_id)->where('flag',6)->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        
        
        return view('MonthlyBudgetEdit', compact('MonthlyMasterList','monthlyList','Ledger','sales','productionData','purchase_fabric','purchase_trim','purchase_job_work','collection'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        
        
           try {  
               
                $MonthlyDate=date($request->year.'-'.$request->monthId.'-'.'01');
             
             
               DB::beginTransaction();
             
            $MonthlyBudgetList = MonthlyBudgetMasterModel::findOrFail($id); 
    
                    $data1=array( 
                 'monthly_budget_date'=>$MonthlyDate,         
                'monthId'=>$request->monthId, 
                'year'=>$request->year,
                'total_lpc_sale'=>$request->total_lpc_sale,
                'total_fob_sale'=>$request->total_fob_sale,
                'total_rs_cr_sale'=>$request->total_rs_cr_sale,
                'total_lmin_sale'=>$request->total_lmin_sale,
                'total_cmohp'=>$request->total_cmohp,
                'total_lpc_production'=>$request->total_lpc_production,
                'total_fob_production'=>$request->total_fob_production,
                'total_rs_cr_production'=>$request->total_rs_cr_production,
                'total_l_min_production'=>$request->total_l_min_production,
                'total_cmohp_production'=>$request->total_cmohp_production,
                'total_l_mtr_purchase_fabric'=>$request->total_l_mtr_purchase_fabric,
                'total_rate_purchase_fabric'=>$request->total_rate_purchase_fabric,
                'total_rs_cr_purchase_fabric'=>$request->total_rs_cr_purchase_fabric,
                'total_days_purchase_fabric'=>$request->total_days_purchase_fabric,
                'total_rs_cr_purchase_trims'=>$request->total_rs_cr_purchase_trims,
                'total_days_purchase_trims'=>$request->total_days_purchase_trims,
                'total_l_pc_purchase_job_work'=>$request->total_l_pc_purchase_job_work,
                'total_rate_purchase_job_work'=>$request->total_rate_purchase_job_work,
                'total_rs_cr_job_work'=>$request->total_rs_cr_job_work,
                 'total_l_min_job_work'=>$request->total_l_min_job_work,
                 'grand_total_os'=>$request->grand_total_os,
                 'total_rs_cr_collection'=>$request->total_rs_cr_collection,
                 'userId'=>$request->userId,
                'created_at'=>date("Y-m-d H:i:s")
            );
            
            $MonthlyBudgetList->fill($data1)->save();
            //dd(DB::getQueryLog()); 
     
              $lpc_sale = $request->lpc_sale;
    
            
            
            
            if(count($lpc_sale)>0)
            {
                
                DB::table('monthly_budget_details')->where(['monthly_budget_id'=>$request->monthly_budget_id,'flag'=>1])->delete();
            
                for($x=0; $x<count($lpc_sale); $x++) 
                { 
                    $data2[] = array(
                        'monthly_budget_id'=>$request->monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                         'Ac_code'=>$request->Ac_code_sale[$x], 
                        'lpc'=>$request->lpc_sale[$x], 
                        'fob'=>$request->fob_sale[$x],
                        'rs_cr'=>$request->rs_cr_sale[$x], 
                        'l_min'=>$request->l_min_sale[$x],
                        'cmohp'=>$request->cmohp_sale[$x],
                        'remark'=>$request->remark_sale[$x],  
                        'flag'=>1
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data2);
            }
            
            $lpc_production = $request->lpc_production;
            if(count($lpc_production)>0)
            {
                
                 DB::table('monthly_budget_details')->where(['monthly_budget_id'=>$request->monthly_budget_id,'flag'=>2])->delete();
            
                for($x=0; $x<count($lpc_production); $x++) 
                { 
                  $data3[] = array(
                        'monthly_budget_id'=>$request->monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                         'Ac_code'=>$request->Ac_code_production[$x],   
                        'lpc'=>$request->lpc_production[$x], 
                        'fob'=>$request->fob_production[$x],
                        'rs_cr'=>$request->rs_cr_production[$x], 
                        'l_min'=>$request->l_min_production[$x],
                        'cmohp'=>$request->cmohp_production[$x],
                        'remark'=>$request->remark_production[$x],  
                        'flag'=>2
                    );
                   
                } 
                
                 MonthlyBudgetDetailModel::insert($data3);
            }
            
            $l_mtr_purchase_fabric = $request->l_mtr_purchase_fabric;
            if(count($l_mtr_purchase_fabric)>0)
            {
                
                 DB::table('monthly_budget_details')->where(['monthly_budget_id'=>$request->monthly_budget_id,'flag'=>3])->delete();
            
                for($x=0; $x<count($l_mtr_purchase_fabric); $x++) 
                { 
                     $data4[] = array(
                        'monthly_budget_id'=>$request->monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                          'Ac_code'=>$request->Ac_code_purchase_fabric[$x],    
                        'l_mtr'=>$request->l_mtr_purchase_fabric[$x], 
                        'rate'=>$request->rate_purchase_fabric[$x],
                        'rs_cr'=>$request->rs_cr_purchase_fabric[$x], 
                        'days'=>$request->days_purchase_fabric[$x],
                        'remark'=>$request->remark_purchase_fabric[$x],  
                        'flag'=>3
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data4);
            }
            


            $rs_cr_purchase_trims = $request->rs_cr_purchase_trims;
            if(count($rs_cr_purchase_trims)>0)
            {
                
                 DB::table('monthly_budget_details')->where(['monthly_budget_id'=>$request->monthly_budget_id,'flag'=>4])->delete();
            
                for($x=0; $x<count($rs_cr_purchase_trims); $x++) 
                { 
                     $data5[] = array(
                        'monthly_budget_id'=>$request->monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                         'Ac_code'=>$request->Ac_code_purchase_trims[$x],        
                        'rs_cr'=>$request->rs_cr_purchase_trims[$x], 
                        'days'=>$request->days_purchase_trims[$x],
                        'remark'=>$request->remark_purchase_trims[$x],  
                        'flag'=>4
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data5);
            }
            
            
            
                    $l_pc_purchase_job_work = $request->l_pc_purchase_job_work;
            if(count($l_pc_purchase_job_work)>0)
            {
                
              DB::table('monthly_budget_details')->where(['monthly_budget_id'=>$request->monthly_budget_id,'flag'=>5])->delete();   
            
                for($x=0; $x<count($l_pc_purchase_job_work); $x++) 
                { 
                     $data6[] = array(
                        'monthly_budget_id'=>$request->monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                         'Ac_code'=>$request->Ac_code_purchase_job_work[$x],       
                        'lpc'=>$request->l_pc_purchase_job_work[$x], 
                         'rate'=>$request->rate_purchase_job_work[$x], 
                         'rs_cr'=>$request->rs_cr_job_work[$x],      
                         'l_min'=>$request->l_min_job_work[$x],   
                         'remark'=>$request->remark_job_work[$x],  
                        'flag'=>5
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data6);
            }
  
  
  
                   $total_os = $request->total_os;
            if(count($total_os)>0)
            {
                
                 DB::table('monthly_budget_details')->where(['monthly_budget_id'=>$request->monthly_budget_id,'flag'=>6])->delete();
            
                for($x=0; $x<count($total_os); $x++) 
                { 
                     $data7[] = array(
                        'monthly_budget_id'=>$request->monthly_budget_id, 
                        'monthId'=>$request->monthId, 
                        'year'=>$request->year, 
                         'Ac_code'=>$request->Ac_code_collection[$x],         
                        'total_os'=>$request->total_os[$x], 
                         'rs_cr'=>$request->rs_cr_collection[$x],      
                         'remark'=>$request->remark_collection[$x],  
                        'flag'=>6
                    );
                    
                } 
                
                MonthlyBudgetDetailModel::insert($data7);
            }
            
            
             DB::commit();
             
             return redirect()->route('monthly_budget.index');
             
    } catch (\Exception $e) {
  
    // If an exception occurs, rollback the transaction and handle the exception
     \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
  
      DB::rollBack();
  
    return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
 
    }
             
        
             
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('buyer_costing_master')->where('sr_no', $id)->delete();
        DB::table('fabric_buyer_costing_details')->where('sr_no',$id)->delete();
        DB::table('sewing_buyer_costing_details')->where('sr_no', $id)->delete();
        DB::table('packing_buyer_costing_details')->where('sr_no', $id)->delete();
        DB::table('buyer_costing_attachments')->where('sr_no', $id)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
      
    public function BuyerCostingPrint($sr_no)
    {
        
          //DB::enableQueryLog();
        $BuyerCostingMaster = BuyerCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_costing_master.userId')
            ->join('currency_master', 'currency_master.cur_id', '=', 'buyer_costing_master.cur_id')
            ->join('order_group_master', 'order_group_master.og_id', '=', 'buyer_costing_master.og_id')
            ->where('buyer_costing_master.delflag','=', '0')
            ->where('buyer_costing_master.sr_no','=', $sr_no)
            ->get(['buyer_costing_master.*', 'usermaster.username','currency_master.currency_name','order_group_master.*']);
          //    $query = DB::getQueryLog();
            //  $query = end($query);
            //   dd($query);
            
            
        return view('BuyerCostingPrint',compact('BuyerCostingMaster'));  
            
    }
    
    public function RepeatBuyerCostingEdit(Request $request)
    { 
        $id = $request->srno;
       

      
        
        $BuyerCostingMasterList = BuyerCostingMasterModel::find($id);
        $BuyerCostingFabricList = BuyerFabricCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingSewingList = BuyerSewingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingPackingList = BuyerPackingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingAttachementList = BuyerCostingAttachementModel::where('sr_no','=',$id)->get();
        return view('RepeatBuyerCostingEdit', compact('BuyerCostingMasterList', 'BuyerCostingFabricList', 'BuyerCostingSewingList', 'BuyerCostingPackingList','BuyerCostingAttachementList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function RepeatBuyerCostingUpdate(Request $request)
    {  
        
            $data1=array( 
                'entry_date'=>$request->entry_date, 
                'buyer_name'=>$request->buyer_name,
                'brand_name'=>$request->brand_name,
                'inr_rate'=>$request->inr_rate, 
                'exchange_rate'=>$request->exchange_rate, 
                'fob_rate'=>$request->fob_rate, 
                'total_qty'=>$request->total_qty,
                'total_value'=>$request->total_value, 
                'style_name'=>$request->style_name,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'sam'=>$request->sam,   
                'fabric_value'=>$request->fabric_value,
                'cur_id'=>$request->cur_id,
                'og_id'=>$request->og_id, 
                'fabric_per'=>$request->fabric_per,
                'sewing_trims_value'=>$request->sewing_trims_value,
                'sewing_trims_per'=>$request->sewing_trims_per,
                'packing_trims_value'=>$request->packing_trims_value, 
                'packing_trims_per'=>$request->packing_trims_per, 
                'production_value'=>$request->production_value,
                'production_per'=>$request->production_per,
                'other_value'=>$request->other_value,
                'other_per'=>$request->other_per,      
                'transport_value'=>$request->transport_value,      
                'transport_per'=>$request->transport_per,      
                'agent_commission_value'=>$request->agent_commission_value,
                'agent_commission_per'=>$request->agent_commission_per,
                'dbk_value'=>$request->dbk_value, 
                'dbk_per'=>$request->dbk_per, 
                'dbk_value1'=>$request->dbk_value1,
                'dbk_per1'=>$request->dbk_per1, 
                'printing_value'=>$request->printing_value,
                'printing_per'=>$request->printing_per,
                'embroidery_value'=>$request->embroidery_value,
                'embroidery_per'=>$request->embroidery_per,
                'ixd_value'=>$request->ixd_value,
                'ixd_per'=>$request->ixd_per,
                'total_making_value'=>$request->total_making_value,
                'total_making_per'=>$request->total_making_per,
                'garment_reject_value'=>$request->garment_reject_value,  
                'garment_reject_per'=>$request->garment_reject_per,   
                'testing_charges_value'=>$request->testing_charges_value, 
                'testing_charges_per'=>$request->testing_charges_per,    
                'finance_cost_value'=>$request->finance_cost_value,   
                'finance_cost_per'=>$request->finance_cost_per,   
                'extra_value'=>$request->extra_value,   
                'extra_per'=>$request->extra_per,   
                'total_cost_value'=>isset($request->total_cost_value) ? ($request->total_cost_value) : 0,
                'total_cost_per'=>isset($request->total_cost_per) ? ($request->total_cost_per) : 0,
                'profit_value'=>isset($request->profit_value) ? ($request->profit_value) : 0,
                'profit_per'=>isset($request->profit_per) ? ($request->profit_per) : 0,
                'narration'=>$request->narration,
                'userId'=>$request->userId,
                'delflag'=>'0', 
                'created_at'=>date("Y-m-d H:i:s")
            );
         
            BuyerCostingMasterModel::insert($data1);
            
            $retId = DB::table('buyer_costing_master')->max('sr_no');
            $item_name = $request->item_name;
            if(count($item_name)>0)
            {
            
                for($x=0; $x<count($item_name); $x++) 
                { 
                    $data2 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_name[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumption[$x], 
                        'rate_per_unit'=>$request->rate_per_unit[$x],
                        'wastage'=>$request->wastage[$x],
                        'total_amount'=>$request->total_amount[$x] 
                    );
                    BuyerFabricCostingDetailModel::insert($data2);
                } 
            }
            
            $item_names = $request->item_names;
            if(count($item_names)>0)
            {
            
                for($x=0; $x<count($item_names); $x++) 
                { 
                    $data3 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_names[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptions[$x], 
                        'rate_per_unit'=>$request->rate_per_units[$x],
                        'wastage'=>$request->wastages[$x],
                        'total_amount'=>$request->total_amounts[$x] 
                    );
                    BuyerSewingCostingDetailModel::insert($data3);
                } 
            }
            
            $item_namess = $request->item_namess;
            if(count($item_namess)>0)
            {
            
                for($x=0; $x<count($item_namess); $x++) 
                { 
                    $data4 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_namess[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptionss[$x], 
                        'rate_per_unit'=>$request->rate_per_unitss[$x],
                        'wastage'=>$request->wastagess[$x],
                        'total_amount'=>$request->total_amountss[$x] 
                    );
                    BuyerPackingCostingDetailModel::insert($data4);
                } 
            }
            
            $style_image = $request->style_image;
            
            if ($style_image != "") 
            {
                $fileName1 = '';
        
                if ($request->hasFile('style_image')) 
                {
                    $file = $request->file('style_image');
                    $fileName1 = $file->getClientOriginalName();
                    $file->move(public_path('uploads/BuyerCosting/'), $fileName1);
                } 
                else 
                {
                    $fileName1 = $request->input('style_image') ?? '';
                }
                
                DB::table('buyer_costing_master')->where('sr_no', '=', $retId)->update(['style_image' => $fileName1]);
            }
            
            $attachmentNames = $request->attachment_name;
            if (count($attachmentNames) > 0) 
            {
                foreach ($attachmentNames as $index => $attachmentName) 
                {
                    if ($request->hasFile('attachment_image.' . $index)) {
                        $attachment = $request->file('attachment_image')[$index];
                        $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                        $location = public_path('uploads/BuyerCosting/');
                        if (file_exists('uploads/BuyerCosting/'.$fileName))
                        {
                             $url = "uploads/BuyerCosting/".$fileName;
                             unlink($url);
                        }
                        $attachment->move($location,$fileName);
                  
                        if($fileName != '')
                        {  
                            $files = new File();
                            $files->sr_no = $retId;
                            $files->attachment_name =  $request->attachment_name[$index];
                            $files->attachment_image = $fileName;
                            $files->save(); 
                        }
                    }
                }
            }
  
            return redirect()->route('BuyerCosting.index');
    }

   
    public function ReviseBuyerCostingEdit(Request $request)
    { 
        $id = $request->srno;
       

   
        
        $BuyerCostingMasterList = BuyerCostingMasterModel::find($id);
        $BuyerCostingFabricList = BuyerFabricCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingSewingList = BuyerSewingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingPackingList = BuyerPackingCostingDetailModel::where('sr_no','=',$id)->get();
        $BuyerCostingAttachementList = BuyerCostingAttachementModel::where('sr_no','=',$id)->get();
        return view('ReviseBuyerCostingEdit', compact('BuyerCostingMasterList', 'BuyerCostingFabricList', 'BuyerCostingSewingList', 'BuyerCostingPackingList','OrderGroupList','BuyerCostingAttachementList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function ReviseBuyerCostingUpdate(Request $request)
    {  
            if($request->repeat_id == '')
            {
                $repeat = $request->sr_no;
            }
            else
            {
                $repeat = $request->repeat_id;
            }
            $data1=array( 
                'entry_date'=>$request->entry_date, 
                'buyer_name'=>$request->buyer_name,
                'brand_name'=>$request->brand_name,
                'inr_rate'=>$request->inr_rate, 
                'exchange_rate'=>$request->exchange_rate, 
                'fob_rate'=>$request->fob_rate, 
                'total_qty'=>$request->total_qty,
                'total_value'=>$request->total_value, 
                'style_name'=>$request->style_name,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'sam'=>$request->sam,   
                'fabric_value'=>$request->fabric_value,
                'cur_id'=>$request->cur_id,
                'og_id'=>$request->og_id, 
                'fabric_per'=>$request->fabric_per,
                'sewing_trims_value'=>$request->sewing_trims_value,
                'sewing_trims_per'=>$request->sewing_trims_per,
                'packing_trims_value'=>$request->packing_trims_value, 
                'packing_trims_per'=>$request->packing_trims_per, 
                'production_value'=>$request->production_value,
                'production_per'=>$request->production_per,
                'other_value'=>$request->other_value,
                'other_per'=>$request->other_per,      
                'transport_value'=>$request->transport_value,      
                'transport_per'=>$request->transport_per,      
                'agent_commission_value'=>$request->agent_commission_value,
                'agent_commission_per'=>$request->agent_commission_per,
                'dbk_value'=>$request->dbk_value, 
                'dbk_per'=>$request->dbk_per, 
                'dbk_value1'=>$request->dbk_value1,
                'dbk_per1'=>$request->dbk_per1, 
                'printing_value'=>$request->printing_value,
                'printing_per'=>$request->printing_per,
                'embroidery_value'=>$request->embroidery_value,
                'embroidery_per'=>$request->embroidery_per,
                'ixd_value'=>$request->ixd_value,
                'ixd_per'=>$request->ixd_per,
                'total_making_value'=>$request->total_making_value,
                'total_making_per'=>$request->total_making_per,
                'garment_reject_value'=>$request->garment_reject_value,  
                'garment_reject_per'=>$request->garment_reject_per,   
                'testing_charges_value'=>$request->testing_charges_value, 
                'testing_charges_per'=>$request->testing_charges_per,    
                'finance_cost_value'=>$request->finance_cost_value,   
                'finance_cost_per'=>$request->finance_cost_per,   
                'extra_value'=>$request->extra_value,   
                'extra_per'=>$request->extra_per,   
                'total_cost_value'=>isset($request->total_cost_value) ? ($request->total_cost_value) : 0,
                'total_cost_per'=>isset($request->total_cost_per) ? ($request->total_cost_per) : 0,
                'profit_value'=>isset($request->profit_value) ? ($request->profit_value) : 0,
                'profit_per'=>isset($request->profit_per) ? ($request->profit_per) : 0,
                'narration'=>$request->narration,
                'userId'=>$request->userId,
                'delflag'=>'0', 
                'repeat_id'=>$repeat,
                'revised_id'=>$request->revised_id,
                'created_at'=>date("Y-m-d H:i:s"), 
            );
         
            BuyerCostingMasterModel::insert($data1); 

            $retId = DB::table('buyer_costing_master')->max('sr_no');
            DB::table('buyer_costing_master')->where('repeat_id', '=', $repeat)->where('sr_no', '!=', $retId)->update(['isDisabled' => 1]);
            DB::table('buyer_costing_master')->where('sr_no', '=', $repeat)->where('sr_no', '!=', $retId)->update(['isDisabled' => 1]);
            $countData = DB::SELECT("SELECT count(*) as total_count FROM buyer_costing_master WHERE repeat_id=".$repeat);
            
            $total_count = isset($countData[0]->total_count) ? $countData[0]->total_count : 0;
            DB::table('buyer_costing_master')->where('sr_no','=',$retId)->update(['revised_id'=> $request->revised_id."-".$total_count]); 
            $item_name = $request->item_name;
            if(count($item_name)>0)
            {
            
                for($x=0; $x<count($item_name); $x++) 
                { 
                    $data2 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_name[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumption[$x], 
                        'rate_per_unit'=>$request->rate_per_unit[$x],
                        'wastage'=>$request->wastage[$x],
                        'total_amount'=>$request->total_amount[$x] 
                    );
                    BuyerFabricCostingDetailModel::insert($data2);
                } 
            }
            
            $item_names = $request->item_names;
            if(count($item_names)>0)
            {
            
                for($x=0; $x<count($item_names); $x++) 
                { 
                    $data3 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_names[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptions[$x], 
                        'rate_per_unit'=>$request->rate_per_units[$x],
                        'wastage'=>$request->wastages[$x],
                        'total_amount'=>$request->total_amounts[$x] 
                    );
                    BuyerSewingCostingDetailModel::insert($data3);
                } 
            }
            
            $item_namess = $request->item_namess;
            if(count($item_namess)>0)
            {
            
                for($x=0; $x<count($item_namess); $x++) 
                { 
                    $data4 = array(
                        'sr_no'=>$retId, 
                        'item_name'=>$request->item_namess[$x], 
                        'entry_date'=>$request->entry_date,
                        'consumption'=>$request->consumptionss[$x], 
                        'rate_per_unit'=>$request->rate_per_unitss[$x],
                        'wastage'=>$request->wastagess[$x],
                        'total_amount'=>$request->total_amountss[$x] 
                    );
                    BuyerPackingCostingDetailModel::insert($data4);
                } 
            }
            
             $style_image = $request->file('style_image');
            
            if ($style_image) {
                $fileName1 = '';
            
                if ($style_image->isValid()) {
                    $style_image = $style_image->getClientOriginalName();
                    $style_image->move(public_path('uploads/BuyerCosting/'), $style_image);
                }
            
                DB::table('buyer_costing_master')->where('sr_no', '=', $retId)->update(['style_image' => $style_image]);
            } else {
                $style_image = $request->input('style_image') ?? '';
                DB::table('buyer_costing_master')->where('sr_no', '=', $retId)->update(['style_image' => $style_image]);
            }

            
            $attachmentNames = $request->attachment_name;
            if (count($attachmentNames) > 0) 
            {
                foreach ($attachmentNames as $index => $attachmentName) 
                {
                    if ($request->hasFile('attachment_image.' . $index)) {
                        $attachment = $request->file('attachment_image')[$index];
                        $fileName = time() . '_' . $attachment->getClientOriginalName(); 
                        $location = public_path('uploads/BuyerCosting/');
                        if (file_exists('uploads/BuyerCosting/'.$fileName))
                        {
                             $url = "uploads/BuyerCosting/".$fileName;
                             unlink($url);
                        }
                        $attachment->move($location,$fileName);
                 
                        if($fileName != '')
                        {  
                            $files = new File();
                            $files->sr_no = $retId;
                            $files->attachment_name =  $request->attachment_name[$index];
                            $files->attachment_image = $fileName;
                            $files->save();
                        }
                    }
                }
            }
  
            return redirect()->route('BuyerCosting.index');
    }
}
