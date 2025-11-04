<?php

namespace App\Http\Controllers;

use App\Models\FinishedGoodModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use Image;

use Session; 

class FinishedGoodController extends Controller
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
        ->where('form_id', '26')
        ->first();  
        
        
        $FGList = FinishedGoodModel::join('usermaster', 'usermaster.userId', '=', 'fg_master.userId')
         ->join('main_style_master', 'main_style_master.mainstyle_id','=','fg_master.mainstyle_id')
         ->join('sub_style_master', 'sub_style_master.substyle_id','=','fg_master.substyle_id')
         ->where('fg_master.delflag','=', '0')
         ->get(['fg_master.*','usermaster.username', 'mainstyle_name','substyle_name']);
  
        return view('FinishedGoodsMasterList', compact('FGList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
         $MainStyleList = MainStyleModel::where('main_style_master.delflag','=','0')->get();
         $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=','0')->get();
        
        return view('FinishedGoodsMaster', compact('MainStyleList','SubStyleList'));
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
             
              'mainstyle_id'=> 'required',
              'substyle_id'=> 'required',
            'fg_name'=> 'required',
            
            
              
    ]);

    $input = $request->all();

    FinishedGoodModel::create($input);

    return redirect()->route('FinishedGood.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FinishedGoodModel  $finishedGoodModel
     * @return \Illuminate\Http\Response
     */
    public function show(FinishedGoodModel $finishedGoodModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FinishedGoodModel  $finishedGoodModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=','0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=','0')->get();
        
        
        $FGList = FinishedGoodModel::find($id);
        // select * from business_type where Bt_id=$id;
        return view('FinishedGoodsMaster', compact('FGList','MainStyleList','SubStyleList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinishedGoodModel  $finishedGoodModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $FGList = FinishedGoodModel::findOrFail($id);

        $this->validate($request, [
            'mainstyle_id'=> 'required',
            'substyle_id'=> 'required',
            'fg_name'=> 'required',
          
        ]);

        $input = $request->all();

        $FGList->fill($input)->save();

        return redirect()->route('FinishedGood.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinishedGoodModel  $finishedGoodModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FinishedGoodModel::where('fg_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function deleteFGImage(Request $request)
    {
        $data = explode('-', $request->id);
        $src = $data[1];
        if (file_exists($src))
        {
             unlink($src);
        }
        return response()->json(1);
    }
    
    public function uploadFileForFG(Request $request)
    {
          $filepath = "";
          $fg_id = $request->fg_id;
          $type = $request->type;
          $FGList = FinishedGoodModel::findOrFail($fg_id);
          
          if($request->file('file')) 
          {

             $file = $request->file('file');
             $filename = time().'_'.$file->getClientOriginalName();
             $extension = $file->getClientOriginalExtension();
             $location = public_path('uploads/FGImages/');

             if($type=="risk")
             {   
                 if (file_exists('uploads/FGImages/'.$FGList->risk_assessment))
                 {
                     $url = "uploads/FGImages/".$FGList->risk_assessment;
                     unlink($url);
                 }
                 $file->move($location,$filename);
                 DB::table('fg_master')->where('fg_id',$fg_id)->update(['risk_assessment'=>$filename]);
             }
             else if($type=="style")
             { 
                 if (file_exists('uploads/FGImages/'.$FGList->style_feasibility))
                 {
                     $url = "../uploads/FGImages/".$FGList->style_feasibility;
                     unlink($url);
                 }
                 $file->move($location,$filename);
                 DB::table('fg_master')->where('fg_id',$fg_id)->update(['style_feasibility'=>$filename]);
             }
             else if($type=="skill")
             {
                 if (file_exists('uploads/FGImages/'.$FGList->skill_mapping))
                 {
                     $url = "../uploads/FGImages/".$FGList->skill_mapping;
                     unlink($url);
                 }
                 $file->move($location,$filename);
                 DB::table('fg_master')->where('fg_id',$fg_id)->update(['skill_mapping'=>$filename]);
             }
             $filepath = url('https://ken.korbofx.com/uploads/FGImages/'.$filename);
          }
          return response()->json(['path' => $filepath]);
    }
    
    public function GetFGInOutStockReportForm()
    {  
        return view('GetFGInOutStockReportForm');
    }
    
    public function getBetweenDates($startDate, $endDate)
    {
        $rangArray = [];
    
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
    
        for (
            $currentDate = $startDate;
            $currentDate <= $endDate;
            $currentDate += (86400)
        ) {
    
            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
    
        return $rangArray;
    }
    
    public function FGInOutStockReport(Request $request)
    {
        
        $fdate= $request->fdate;
        $tdate= $request->tdate;
        
        if($tdate>date('Y-m-d')){$tdate=date('Y-m-d');}
         
         
        $period = $this->getBetweenDates($fdate, $tdate);
           
        $FirmDetail =  DB::table('firm_master')->first();
      
        return view('FGInOutStockReport', compact('period','fdate', 'tdate','FirmDetail'));
      
    }
    
    
    public function changeFGCategoryStatus(Request $request)
    {    
         DB::table('fg_master')->where('fg_id',$request->fg_id)->update(['status'=>$request->status]);
         return 1;
    }
}
