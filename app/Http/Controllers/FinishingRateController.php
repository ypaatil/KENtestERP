<?php

namespace App\Http\Controllers;
 
use App\Models\ItemModel;
use App\Models\FinishingRateDetailModel;
use App\Models\FinishingRateMasterModel;
use App\Models\LedgerModel;
use App\Models\BrandModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class FinishingRateController extends Controller
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
        ->where('form_id', '283')
        ->first();

        $finishingData = FinishingRateMasterModel::leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'finishing_rate_master.ac_code')
            ->leftjoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'finishing_rate_master.substyle_id')
            ->leftjoin('brand_master', 'brand_master.brand_id', '=', 'finishing_rate_master.brand_id')
            ->where('finishing_rate_master.delflag', '=', '0')
            ->select(
                'finishing_rate_master.*', 'ledger_master.ac_short_name','sub_style_master.substyle_name','brand_master.brand_name')
            ->orderBy('finishing_rate_master.finishing_rate_code', 'DESC')
            ->get();


        return view('FinishingRateMasterList', compact('finishingData','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->where('ledger_master.bt_id','=', '2')->get(); 
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('delflag','=', '0')->get();   
        
        return view('FinishingRateMaster',compact('Ledger','MainStyleList', 'SubStyleList'));
    
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
                'Ac_code'=>$request->Ac_code,
                'brand_id'=>$request->brand_id, 
                'substyle_id'=>$request->substyle_id,
                'userId'=>Session::get('userId'), 
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'delflag'=>0
             );
         
            FinishingRateMasterModel::insert($data1); 
            $maxId = FinishingRateMasterModel::max('finishing_rate_code');
            $finishing_rate_date = $request->finishing_rate_date;
            if(count($finishing_rate_date)>0)
            {   
                for($x=0; $x<count($finishing_rate_date); $x++) 
                {
                    
                    $data4 =array
                    ( 
                        'finishing_rate_code'=>$maxId, 
                        'finishing_rate_date'=>$request->finishing_rate_date[$x],
                        'finishing_rate'=>$request->finishing_rate[$x],
                        'packing_rate'=>$request->packing_rate[$x],  
                        'kaj_button_rate'=>$request->kaj_button_rate[$x],  
                    );
                   
                    FinishingRateDetailModel::insert($data4);
                }
            }
    
        
        return redirect()->route('FinishingRate.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FinishingRate  $FinishingRate
     * @return \Illuminate\Http\Response
     */
    public function show(FinishingRate $FinishingRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FinishingRate  $FinishingRate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $FinishingRateMaster = FinishingRateMasterModel::find($id);  
        $FinishingRateDetailList = FinishingRateDetailModel::where('finishing_rate_code','=', $FinishingRateMaster->finishing_rate_code)->get();  
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->where('ledger_master.bt_id','=', '2')->get(); 
        $BrandList = BrandModel::select('*')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get(); 
        $SubStyleList = SubStyleModel::where('delflag','=', '0')->get();  
        
        return view('FinishingRateMasterEdit',compact('FinishingRateMaster', 'FinishingRateDetailList', 'Ledger','BrandList','MainStyleList','SubStyleList')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinishingRate  $FinishingRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
         $data1=array
            (  
                'Ac_code'=>$request->Ac_code,
                'brand_id'=>$request->brand_id, 
                'substyle_id'=>$request->substyle_id,
                'userId'=>Session::get('userId'), 
                'updated_at'=>date("Y-m-d H:i:s"),
                'delflag'=>0
             );
         
        $FinishingRateList = FinishingRateMasterModel::findOrFail($request->finishing_rate_code);
        $FinishingRateList->fill($data1)->save(); 
         
        $finishing_rate_date = $request->finishing_rate_date;
        FinishingRateDetailModel::where('finishing_rate_code', $request->finishing_rate_code)->delete();
        if(count($finishing_rate_date)>0)
        {   
            for($x=0; $x<count($finishing_rate_date); $x++) 
            {
                
                $data4 =array
                ( 
                    'finishing_rate_code'=>$request->finishing_rate_code, 
                    'finishing_rate_date'=>$request->finishing_rate_date[$x],
                    'finishing_rate'=>$request->finishing_rate[$x],
                    'packing_rate'=>$request->packing_rate[$x],  
                    'kaj_button_rate'=>$request->kaj_button_rate[$x],  
                );
               
                FinishingRateDetailModel::insert($data4);
            }
        }
        return redirect()->route('FinishingRate.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinishingRate  $FinishingRate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        FinishingRateMasterModel::where('finishing_rate_code', $id)->delete();  
        FinishingRateDetailModel::where('finishing_rate_code', $id)->delete();  

        return redirect()->route('FinishingRate.index')->with('message', 'Deleted Record Succesfully');
    } 
    
     
}
