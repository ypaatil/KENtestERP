<?php

namespace App\Http\Controllers;
use App\Models\T_And_A_TemplateMasterModel;
use App\Models\T_And_A_TemplateDetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\DeliveryTermsModel;
use Illuminate\Http\Request;
use DB;
use Session;

class T_And_A_TemplateMasterController extends Controller
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
        ->where('form_id', '154')
        ->first();

        $data = T_And_A_TemplateMasterModel:: join('usermaster', 'usermaster.userId', '=', 't_and_a_templatemaster.userId')
        ->join('delivery_terms_master', 'delivery_terms_master.dterm_id', '=', 't_and_a_templatemaster.dterm_id')
        ->where('t_and_a_templatemaster.delflag','=', '0')
        ->get(['t_and_a_templatemaster.*','usermaster.username', 'delivery_terms_master.delivery_term_name' ]);

        return view('T_And_A_TemplateMaster_List', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
        $ActList = DB::table('activity_master')->where('delflag','=','0')->get();
        return view('T_And_A_TemplateMaster',compact('DeliveryTermsList', 'ActList' ));
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
      ->where('type','=','T_and_ATemplate')
      ->where('firm_id','=',1)
      ->first();

      $TrNo=$codefetch->code.'-'.$codefetch->tr_no;

      $data1=array(
        't_and_a_tid'=>$TrNo,
        'dterm_id'=>$request->dterm_id,
        'userId'=>$request->userId,
        'delflag'=>0
    );

      T_And_A_TemplateMasterModel::insert($data1);

      DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='T_and_ATemplate'");

      $act_ids = $request->input('act_ids');

      if(count($act_ids)>0)
      {     
        for($x=0; $x<count($act_ids); $x++)
        {
            $data2=array(
                'sr_no'=>$request->id[$x],
                't_and_a_tid'=>$TrNo,
                'act_id'=>$request->act_ids[$x],
                'days'=>$request->days[$x],
               'dact_id'=>$request->dact_ids[$x],
            );

        T_and_A_TemplateDetailModel::insert($data2);
        } 
    }
    return redirect()->route('T_And_A_TemplateMaster.index')->with('message', 'Added Record Succesfully');

}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
     $T_And_A_TemplateMasterList = T_And_A_TemplateMasterModel::find($id);
     $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
     $ActList = DB::table('activity_master')->where('delflag','=','0')->get();
     $T_And_A_TemplateDetailfetch = T_And_A_TemplateDetailModel::
         where('t_and_a_templatedetail.t_and_a_tid','=', $T_And_A_TemplateMasterList->t_and_a_tid)
         ->get(['t_and_a_templatedetail.*']);
    
    
    
    
     return view('T_And_A_TemplateMaster_Edit',compact('DeliveryTermsList', 'ActList', 'T_And_A_TemplateDetailfetch','T_And_A_TemplateMasterList'));
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
        // echo '<pre>'; print_r($_POST);exit;
        $data1=array(
            't_and_a_tid'=>$request->t_and_a_tid,
            'dterm_id'=>$request->dterm_id,
            'userId'=>$request->userId,
            'delflag'=>0
        );

        $t_and_a=T_And_A_TemplateMasterModel::findOrFail($id);
        $t_and_a->fill($data1)->save();
        DB::table('t_and_a_templatedetail')->where('t_and_a_tid', $request->input('t_and_a_tid'))->delete();
        $act_id = $request->act_ids;
        //echo count($act_id);exit;
        if(count($act_id)>0)
        {     
            for($x=0; $x<count($act_id); $x++)
            {
                $data2=array(
                    'sr_no'=>$request->id[$x],
                    't_and_a_tid'=>$request->t_and_a_tid,
                    'act_id'=>$request->act_ids[$x],
                    'days'=>$request->days[$x],
                    'dact_id'=>$request->dact_ids[$x],
                   
                );
               // DB::enableQueryLog();
                T_and_A_TemplateDetailModel::insert($data2);
           // dd(DB::getQueryLog());
            } 
        }
        return redirect()->route('T_And_A_TemplateMaster.index')->with('message', 'Record Updated Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($t_and_a_tid)
    {
        DB::table('t_and_a_templatemaster')->where('t_and_a_tid', $t_and_a_tid)->delete();
        DB::table('t_and_a_templatedetail')->where('t_and_a_tid', $t_and_a_tid)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
    }
    public function getSalesOrderDetail2(Request $request)
    {

      $tr_code=$request->tr_code;

      $data=BuyerPurchaseOrderMasterModel::select('Ac_code','order_received_date','mainstyle_id','substyle_id','fg_id','style_no','style_description','shipment_date')
      ->where('tr_code','=',$tr_code)
      ->get();
      return $data;
  }

  public function Timeline2()
  {
        // DB::enableQueryLog();

        $data = DB::table('t_and_a_templatemaster')->distinct()->
        select('t_and_a_templatemaster.*','usermaster.username','delivery_terms_master.delivery_term_name' )
        ->join('usermaster', 'usermaster.userId', '=', 't_and_a_templatemaster.userId')
       ->join('delivery_terms_master', 'delivery_terms_master.dterm_id', '=', 't_and_a_templatemaster.dterm_id')
        ->where('t_and_a_templatemaster.delflag','=', '0')
        ->get(['t_and_a_templatemaster.*','usermaster.username','t_and_a_templatedetail.*']);
        // dd(DB::getQueryLog());

        $id = $data->pluck('t_and_a_tid');
       // DB::enableQueryLog();
        $details = DB::table('t_and_a_templatedetail')->distinct()->select('activity_master.act_name')->join('activity_master','activity_master.act_id','=','t_and_a_templatedetail.act_id')->whereIn('t_and_a_tid', $id)->get();
        // dd(DB::getQueryLog());
        return view('Timeline2', compact('data','details'));
  }
}
