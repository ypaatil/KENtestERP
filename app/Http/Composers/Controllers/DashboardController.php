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
}
