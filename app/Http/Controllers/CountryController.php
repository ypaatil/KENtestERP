<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //return view('Country_Master_List');

        //$Countrys = Country::all();

        $chekform = DB::table('form_auth')
->where('emp_id', Session::get('userId'))
->where('form_id', '1')
->first();




        $Countrys = Country::join('usermaster', 'usermaster.userId', '=', 'country_master.user_id')
        ->where('country_master.delflag','=', '0')
        ->get(['country_master.*','usermaster.username']);



       // $Countrys = Country::where('delflag','=', '0')->get();   

        return view('Country_Master_List', compact('Countrys','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('Country_Master');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $this->validate($request, [
            'c_name' => 'required',
        ]);

        $input = $request->all();

        Country::create($input);

        return redirect()->route('Country.index');

// Country.index  Country is Url name  and index is above method

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
     


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit($c_id)
    {
        //

        $country = Country::find($c_id);
        
        return view('Country_Master', compact('country','country'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $country = Country::findOrFail($id);

        $this->validate($request, [
            'c_name' => 'required',
        ]);

        $input = $request->all();

        $country->fill($input)->save();

        return redirect()->route('Country.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy($c_id)
    {
        //$country = Country::findOrFail($c_id);

      // $country->delete();
      

        Country::where('c_id', $c_id)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}
