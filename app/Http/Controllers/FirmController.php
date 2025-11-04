<?php

namespace App\Http\Controllers;

use App\Models\FirmModel;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\DistrictModel;
use App\Models\TalukaModel;
use Illuminate\Support\Facades\DB;
class FirmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
  
        $FirmList = FirmModel::join('usermaster', 'usermaster.userId', '=', 'firm_master.userId')
        ->join('country_master', 'country_master.c_id', '=', 'firm_master.c_id')
        ->join('state_master', 'state_master.state_id', '=', 'firm_master.state_id')
        ->join('district_master', 'district_master.d_id', '=', 'firm_master.dist_id')
        ->join('taluka_master', 'taluka_master.tal_id', '=', 'firm_master.taluka_id')
        ->where('firm_master.delflag','=', '0')
        ->get(['firm_master.*','usermaster.username','country_master.c_name','state_master.state_name','district_master.d_name', 'taluka_master.taluka']);
        return view('FirmMasterList', compact('FirmList'));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Account_Type = DB::table('account_type_master')->get();
        $Countrys = Country::where('country_master.delflag','=', '0')->get();
        $State = State::where('state_master.delflag','=', '0')->get();
        $District = DB::table('district_master')->get();
        $Taluka = DB::table('taluka_master')->get();
        return view('FirmMaster',compact('Countrys','State','District','Taluka','Account_Type'));
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
             
            'firm_name'=> 'required',
            'Address'=> 'required',
            'c_id'=> 'required',
            'state_id'=> 'required',
            'dist_id'=> 'required',
            'city_name'=> 'required',
            'gst_no'=> 'required',
            'pan_no'=> 'required',
            'mobile_no'=> 'required',
            'email_id'=> 'required',
            'reg_id'=> 'required',
            'bank_name'=> 'required',
            'account_name'=> 'required',
            'ac_id'=> 'required',
            'account_no'=> 'required',
            'ifsc_code'=> 'required',
            'm_id'=> 'required',
            'bank_name'=> 'required',
    ]);

    $input = $request->all();

    FirmModel::create($input);

    return redirect()->route('Firm.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FirmModel  $firmModel
     * @return \Illuminate\Http\Response
     */
    public function show(FirmModel $firmModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FirmModel  $firmModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Account_Type = DB::table('account_type_master')->get();
        $Countrys = Country::where('country_master.delflag','=', '0')->get();
        $State = State::where('state_master.delflag','=', '0')->get();
        $District = DB::table('district_master')->get();
        $Taluka = DB::table('taluka_master')->get();
        $FirmList = FirmModel::find($id);
        // select * from firm_master where firm_id=$id;
        return view('FirmMaster', compact('FirmList', 'Countrys','State','District','Taluka','Account_Type' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FirmModel  $firmModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $FirmList = FirmModel::findOrFail($id);

        $this->validate($request, [
            'firm_name'=> 'required',
            'Address'=> 'required',
            'c_id'=> 'required',
            'state_id'=> 'required',
            'dist_id'=> 'required',
            'city_name'=> 'required',
            'owner_name'=>'required',
            'gst_no'=> 'required',
            'pan_no'=> 'required',
            'mobile_no'=> 'required',
            'email_id'=> 'required',
            'reg_id'=> 'required',
            'bank_name'=> 'required',
            'account_name'=> 'required',
            'ac_id'=> 'required',
            'account_no'=> 'required',
            'ifsc_code'=> 'required',
            'm_id'=> 'required',
            'bank_name'=> 'required',
        ]);

        $input = $request->all();

        $FirmList->fill($input)->save();

        return redirect()->route('Firm.index')->with('message', 'Update Record Succesfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FirmModel  $firmModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FirmModel::where('firm_id', $id)->update(array('delflag' => 1));
        return redirect()->route('Firm.index')->with('messagedelete', 'Delete Record Succesfully');
    }
}
