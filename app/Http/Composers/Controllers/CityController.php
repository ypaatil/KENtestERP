<?php

namespace App\Http\Controllers;

use App\Models\Taluka;
use Illuminate\Http\Request;
use App\Models\DistrictModel;
 use App\Models\State;
use App\Models\CityModel;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use Session;

class CityController extends Controller
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
->where('form_id', '80')
->first();


 // DB::enableQueryLog();

    $data = CityModel::join('country_master', 'country_master.c_id', '=', 'city_master.country_id')
        ->join('usermaster','usermaster.userId', '=','city_master.userId')
        ->join('state_master','state_master.state_id', '=','city_master.state_id')
         ->join('district_master','district_master.d_id', '=','city_master.dist_id')
         ->join('taluka_master','taluka_master.tal_id', '=','city_master.taluka_id')
        ->where('city_master.delflag','=', '0')
        ->get(['city_master.*','usermaster.username','country_master.c_name','state_master.state_name','district_master.d_name','taluka_master.taluka']);

 //         $query = DB::getQueryLog();
 // $query = end($query);
 // dd($query);

  return view('CityMasterList', compact('data','chekform'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $districtlist = DistrictModel::where('delflag','=', '0')->get();
        $Countrylist = Country::where('delflag','=', '0')->get();
        $statelist = DB::table('state_master')->where('delflag','=', '0')->get();
        $talukalist = DB::table('taluka_master')->where('delflag','=', '0')->get();

        return view('CityMaster',compact('Countrylist','statelist','districtlist', 'talukalist'));
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
            'country_id' => 'required',
            'state_id' => 'required',
            'dist_id' => 'required',
             'taluka_id' => 'required',
              'city_name' => 'required',
        ]);

        $input = $request->all();

        CityModel::create($input);

        return redirect()->route('City.index');
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
        $statelist = DB::table('state_master')->where('delflag','=', '0')->get();
        $Countrylist = Country::where('delflag','=', '0')->get();
        $districtlist = DistrictModel::where('delflag','=', '0')->get();
        $talukalist = DB::table('taluka_master')->where('delflag','=', '0')->get();
        $CityList = CityModel::find($id);

        return view('CityMaster', compact('CityList','Countrylist','statelist','districtlist','talukalist'));
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
        
         $CityList = CityModel::findOrFail($id);

        $this->validate($request, [
            'country_id' => 'required',
              'state_id' => 'required',
            'dist_id' => 'required',
             'taluka_id' => 'required',
              'city_name' => 'required',
        ]);

        $input = $request->all();

        $CityList->fill($input)->save();

        return redirect()->route('City.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CityModel::where('city_id', $id)->update(array('delflag' => 1));
       Session::flash('delete', 'Deleted record successfully'); 
    }
}
