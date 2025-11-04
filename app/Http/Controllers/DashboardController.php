<?php

namespace App\Http\Controllers;

use App\Models\DashboardModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class DashboardController extends Controller
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
      ->where('form_id', '91')
      ->first();
      
      
      $dashboard_master1 = DashboardModel::join('usermaster', 'usermaster.userId', '=', 'dashboard_master.userId')
      ->where('dashboard_master.delflag','=', '0')
      ->get(['dashboard_master.*','usermaster.username']);
      
      return view('DashboardMasterList', compact('dashboard_master1','chekform'));
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('DashboardMaster');
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
           
            'BK_VOL_TD_P'=> 'required',
            'BK_VOL_M_TO_Dt_P'=> 'required',
            'BK_VOL_Yr_TO_Dt_P'=> 'required',
            'BK_VAL_TD_P'=> 'required',
            'BK_VAL_M_TO_Dt_P'=> 'required',
            'BK_VAL_Yr_TO_Dt_P'=> 'required',
            'SAL_VOL_TD_P'=> 'required',
            'SAL_VOL_M_TO_Dt_P'=> 'required',
            'SAL_VOL_Yr_TO_Dt_P'=> 'required',
            'SAL_VAL_TD_P'=> 'required',
            'SAL_VAL_M_TO_Dt_P'=> 'required',
            'SAL_VAL_Yr_TO_Dt_P'=> 'required',
            'BOK_SAH_TD_P'=> 'required',
            'BOK_SAH_M_TO_Dt_P'=> 'required',
            'BOK_SAH_Y_TO_Dt_P'=> 'required',
            'SAL_SAH_TD_P'=> 'required',
            'SAL_SAH_M_TO_Dt_P'=> 'required',
            'SAL_SAH_Yr_TO_Dt_P'=> 'required',

            
        ]);

        $input = $request->all();

        DashboardModel::create($input);

        return redirect()->route('DashboardMaster.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DashboardModel  $DashboardModel
     * @return \Illuminate\Http\Response
     */
    public function show(DashboardModel $DashboardModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DashboardModel  $DashboardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dashboard_master1 = DashboardModel::find($id);
        // select * from dashboard_master where Bt_id=$id;
        return view('DashboardMaster', compact('dashboard_master1'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DashboardModel  $DashboardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dashboard_master1 = DashboardModel::findOrFail($id);

        $this->validate($request, [
            'BK_VOL_TD_P'=> 'required',
            'BK_VOL_M_TO_Dt_P'=> 'required',
            'BK_VOL_Yr_TO_Dt_P'=> 'required',
            'BK_VAL_TD_P'=> 'required',
            'BK_VAL_M_TO_Dt_P'=> 'required',
            'BK_VAL_Yr_TO_Dt_P'=> 'required',
            'SAL_VOL_TD_P'=> 'required',
            'SAL_VOL_M_TO_Dt_P'=> 'required',
            'SAL_VOL_Yr_TO_Dt_P'=> 'required',
            'SAL_VAL_TD_P'=> 'required',
            'SAL_VAL_M_TO_Dt_P'=> 'required',
            'SAL_VAL_Yr_TO_Dt_P'=> 'required',
            'BOK_SAH_TD_P'=> 'required',
            'BOK_SAH_M_TO_Dt_P'=> 'required',
            'BOK_SAH_Y_TO_Dt_P'=> 'required',
            'SAL_SAH_TD_P'=> 'required',
            'SAL_SAH_M_TO_Dt_P'=> 'required',
            'SAL_SAH_Yr_TO_Dt_P'=> 'required',
        ]);

        $input = $request->all();

        $dashboard_master1->fill($input)->save();

        return redirect()->route('DashboardMaster.index')->with('message', 'Update Record Succesfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DashboardModel  $DashboardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DashboardModel::where('db_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function ERPDashboard()
    {  
              
        $key1 = 'Fabric - Moving Value'; 
        $key2 = 'Fabric - Non - Moving Value';  
        
        $key3 = 'Trims - Moving Value'; 
        $key4 = 'Trims - Non - Moving Value';  
        
        $key5 = 'FG - Moving Value'; 
        $key6 = 'FG - Non - Moving Value'; 
        
        $key7 = 'WIP -  Value'; 
        
        
        $MovingFabRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head=10 AND key_Indicators='".$key1."'");
        $NonMovingFabRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head=10 AND key_Indicators='".$key2."'");

        $MovingTrimRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head=10 AND key_Indicators='".$key3."'");
        $NonMovingTrimRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head=10 AND key_Indicators='".$key4."'");
   
        $MovingFGRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head=10 AND key_Indicators='".$key5."'");
        $NonMovingFGRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head=10 AND key_Indicators='".$key6."'");
   
        $MovingWIPRecordsData = DB::select("SELECT today FROM  temp_order_sales_dashboard WHERE table_head=10 AND key_Indicators='".$key7."'");


   
        $fabmoving = isset($MovingFabRecordsData[0]->today) ? $MovingFabRecordsData[0]->today : 0;
        $fabnon_moving = isset($NonMovingFabRecordsData[0]->today) ? $NonMovingFabRecordsData[0]->today : 0;
        
        
        $trimmoving = isset($MovingTrimRecordsData[0]->today) ? $MovingTrimRecordsData[0]->today : 0;
        $trimnon_moving = isset($NonMovingTrimRecordsData[0]->today) ? $NonMovingTrimRecordsData[0]->today : 0;
        
        
        $fgmoving = isset($MovingFGRecordsData[0]->today) ? $MovingFGRecordsData[0]->today : 0;
        $fgnon_moving = isset($NonMovingFGRecordsData[0]->today) ? $NonMovingFGRecordsData[0]->today : 0;
        
        $WIPmoving = isset($MovingWIPRecordsData[0]->today) ? $MovingWIPRecordsData[0]->today : 0;
        $WIPtotal = $WIPmoving;
        
        $movingArr = $fabmoving.",".$trimmoving.",".$fgmoving.",".$WIPmoving;
        $non_movingArr = $fabnon_moving.",".$trimnon_moving.",".$fgnon_moving.",0";
        
        
        $vendorData = DB::select("SELECT * FROM  ledger_master WHERE ac_code IN(56,115,69) order BY ac_code DESC");
        $lineArr = array();  
        $dataArr = "";
        $totalArr = "";
        $array1 = array();  
        $array2 = array(); 
        $array3 = array();
        $array4 = array();
        
        foreach($vendorData as $row)
        {
            $LineList=DB::select("select line_id,line_name from line_master where Ac_code='".$row->ac_code."'");
            
            $colspan = count($LineList);
              
            $totalPieces = 0;
            $overallSAM = 0;
            $overallWorker = 0;
            $overallPMin = 0;
            $overallMinAvaliable = 0;
            $overEffi = 0;
            $overallrejectDHU = 0; 
            $overallDHU = 0;
            
            foreach($LineList as $lines)
            {
                $piecesData = DB::select("select sum(total_qty) as  qty FROM stitching_inhouse_master 
                WHERE vendorId=".$row->ac_code." AND line_id=".$lines->line_id." 
                AND sti_date='".date("Y-m-d",strtotime("-1 days"))."'"); 
                        
                $StichingData = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min
                    from stitching_inhouse_size_detail2
                    INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                    where stitching_inhouse_size_detail2.vendorId='".$row->ac_code."' and stitching_inhouse_size_detail2.line_id='".$lines->line_id."' and 
                    stitching_inhouse_size_detail2.sti_date = '".date("Y-m-d",strtotime("-1 days"))."'");
                    
                $pieces = isset($piecesData[0]->qty) ? $piecesData[0]->qty : 0; 
                
                if(count($StichingData) > 0)
                {
                    $totalPMin = $StichingData[0]->total_min;
                    
                }
                else
                {
                    $totalPMin = 0;
                    
                }
                if($totalPMin > 0 && $pieces > 0)
                {
                    $avgSAM = $totalPMin/$pieces;
                }
                else
                {
                    $avgSAM = 0;
                } 
               
                $overallSAM += $avgSAM;
                
                if($overallSAM > 0 && count($LineList) > 0)
                { 
                    $totalSAM = round($overallSAM/count($LineList),2);
                }
                else
                {
                    $totalSAM = 0;
                }
                $totalPieces += $pieces;
                
                        
                $workerData = DB::select("select sum(total_workers) as total_workers FROM stitching_inhouse_master 
                WHERE vendorId=".$row->ac_code." AND line_id=".$lines->line_id." AND sti_date = '".date("Y-m-d",strtotime("-1 days"))."'");
                
                $total_worker = isset($workerData[0]->total_workers) ? $workerData[0]->total_workers : 0;
                $overallWorker += $total_worker;
                     
           
                $minData = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                    from stitching_inhouse_size_detail2
                    INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                    where stitching_inhouse_size_detail2.vendorId='".$row->ac_code."' and stitching_inhouse_size_detail2.line_id='".$lines->line_id."' and 
                    stitching_inhouse_size_detail2.sti_date = '".date("Y-m-d",strtotime("-1 days"))."'");
              
                if(count($minData) > 0)
                {
                    $totalPMin = $minData[0]->total_min;
                    
                }
                else
                {
                    $totalPMin = 0;
                    
                }
                $overallPMin += $totalPMin;
                
                
                $avaliable_min = ($total_worker*480);
                
                $overallMinAvaliable += ($total_worker*480);
               
                if($total_worker > 0 && $totalPMin > 0)
                {
                    $TotalOperator = money_format('%!.0n',round((($totalPMin)/($total_worker * 480)),2) * 100);
                }
                else
                {
                    $TotalOperator = 0;
                    
                }
                $overEffi += $TotalOperator;  
                
                $defectQtyData = DB::select("select ifnull(sum(dhu_details.defect_qty),0) as defect_qty FROM dhu_details 
                        LEFT JOIN dhu_master ON dhu_master.dhu_code = dhu_details.dhu_code
                        WHERE dhu_master.vendorId=".$row->ac_code." AND dhu_master.line_no = ".$lines->line_id."
                        AND dhu_master.dhu_date = '".date("Y-m-d",strtotime("-1 days"))."'");
                        
                $passData = DB::select("select ifnull(SUM(size_qty_total),0) as pass_qty FROM qcstitching_inhouse_detail 
                                        WHERE qcstitching_inhouse_detail.vendorId=".$row->ac_code." 
                                        AND qcsti_date='".date("Y-m-d",strtotime("-1 days"))."' AND line_id=".$lines->line_id);    
                
                $rejectData = DB::select("select ifnull(SUM(size_qty_total),0) as reject_qty FROM qcstitching_inhouse_reject_detail 
                                    WHERE qcstitching_inhouse_reject_detail.vendorId=".$row->ac_code." 
                                    AND qcsti_date='".date("Y-m-d",strtotime("-1 days"))."' AND line_id=".$lines->line_id);    
                         
                   
                $reje =  isset($rejectData[0]->reject_qty) ? $rejectData[0]->reject_qty : 0; 
                $pass =  isset($passData[0]->pass_qty) ? $passData[0]->pass_qty : 0;
                $deft =  isset($defectQtyData[0]->defect_qty) ? $defectQtyData[0]->defect_qty : 0; 
                if(($deft + $reje) > 0 && ($pass + $deft + $reje) > 0)
                {
                    $dhu = round(($deft + $reje)/($pass + $deft + $reje) * 100,2);   
                }
                else
                {
                    $dhu = 0;
                }
                
                $overallDHU += $dhu;         
                 
                if(($reje) > 0 && ($pass + $deft + $reje) > 0)
                {
                    $rejected_dhu = round(($reje)/($pass + $deft + $reje) * 100,2);   
                }
                else
                {
                    $rejected_dhu = 0;
                } 
               
                $overallrejectDHU += $rejected_dhu;
                $array2 = array($pieces,$totalPMin,$avgSAM,$total_worker,$TotalOperator,$dhu,$rejected_dhu);
                $array1[] = array($lines->line_name,$array2);    
            }
            $array3[] = array($row->ac_name, $array1);
        }
        
        return view('ERPDashboard',compact('movingArr','non_movingArr','array3'));
    }

}
