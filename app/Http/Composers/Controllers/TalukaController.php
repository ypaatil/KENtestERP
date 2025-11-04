<?php

namespace App\Http\Controllers;

use App\Models\Taluka;
use Illuminate\Http\Request;
use App\Models\DistrictModel;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use Session;

class TalukaController extends Controller
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
->where('form_id', '6')
->first();


 // DB::enableQueryLog();

    $data = Taluka::join('country_master', 'country_master.c_id', '=', 'taluka_master.country_id')
        ->join('usermaster','usermaster.userId', '=','taluka_master.userId')
        ->join('state_master','state_master.state_id', '=','taluka_master.state_id')
         ->join('district_master','district_master.d_id', '=','taluka_master.dist_id')
        ->where('taluka_master.delflag','=', '0')
        ->get(['taluka_master.*','usermaster.username','country_master.c_name','state_master.state_name','district_master.d_name']);

 //         $query = DB::getQueryLog();
 // $query = end($query);
 // dd($query);

  return view('Taluka_Master_List', compact('data','chekform'));

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

        return view('Taluka_Master',compact('Countrylist','statelist','districtlist'));
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
             'taluka' => 'required',
        ]);

        $input = $request->all();

        Taluka::create($input);

        return redirect()->route('Taluka.index');
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

      $Taluka = Taluka::find($id);

        return view('Taluka_Master', compact('Taluka','Countrylist','statelist','districtlist'));
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
        
         $taluka = Taluka::findOrFail($id);

        $this->validate($request, [
            'country_id' => 'required',
              'state_id' => 'required',
            'dist_id' => 'required',
        ]);

        $input = $request->all();

        $taluka->fill($input)->save();

        return redirect()->route('Taluka.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Taluka  $taluka
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        Taluka::where('tal_id', $id)->update(array('delflag' => 1));

  Session::flash('delete', 'Deleted record successfully'); 
    }
}
