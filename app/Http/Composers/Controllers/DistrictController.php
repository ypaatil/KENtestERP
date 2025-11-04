<?php

namespace App\Http\Controllers;

use App\Models\DistrictModel;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Session;

class DistrictController extends Controller
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
->where('form_id', '5')
->first();


// DB::enableQueryLog();


        $data = DistrictModel::join('country_master', 'country_master.c_id', '=', 'district_master.c_id')
        ->join('usermaster','usermaster.userId', '=', 'district_master.userId')
        ->join('state_master','state_master.state_id', '=', 'district_master.state_id')
        ->where('district_master.delflag','=', '0')
        ->get(['district_master.*','usermaster.username','country_master.c_name','state_master.state_name']);

// $query = DB::getQueryLog();
// $query = end($query);
// dd($query);

        return view('District_Master_List', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
       $Countrylist = Country::where('delflag','=', '0')->get();
       $statelist = DB::table('state_master')->where('delflag','=', '0')->get();

        return view('District_Master',compact('Countrylist','statelist'));

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
            'c_id' => 'required',
            'state_id' => 'required',
            'd_name' => 'required',
        ]);

        $input = $request->all();

        DistrictModel::create($input);

        return redirect()->route('District.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DistrictModel  $districtModel
     * @return \Illuminate\Http\Response
     */
    public function show(DistrictModel $districtModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DistrictModel  $districtModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $statelist = DB::table('state_master')->where('delflag','=', '0')->get();
       $Countrylist = Country::where('delflag','=', '0')->get();
      $District = DistrictModel::find($id);

        return view('District_Master', compact('District','Countrylist','statelist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DistrictModel  $districtModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $District = DistrictModel::findOrFail($id);

        $this->validate($request, [
            'c_id' => 'required',
              'state_id' => 'required',
            'd_name' => 'required',
        ]);

        $input = $request->all();

        $District->fill($input)->save();

        return redirect()->route('District.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DistrictModel  $districtModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DistrictModel::where('d_id', $id)->update(array('delflag' => 1));

        Session::flash('delete', 'Deleted record successfully'); 
    }
}
