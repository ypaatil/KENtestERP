<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
$chekform = DB::table('form_auth')
->where('emp_id', Session::get('userId'))
->where('form_id', '2')
->first();


        $data = State::join('country_master', 'country_master.c_id', '=', 'state_master.country_id')
        ->join('usermaster', 'usermaster.userId', '=', 'state_master.userId')
        ->where('state_master.delflag','=', '0')
        ->get(['state_master.*','usermaster.username','country_master.c_name']);

        return view('State_Master_List', compact('data','chekform'));
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
        return view('State_Master',compact('Countrylist'));
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
            'country_id' => 'required',
            'state_name' => 'required',
        ]);

        $input = $request->all();

        State::create($input);

        return redirect()->route('State.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $Countrylist = Country::where('delflag','=', '0')->get();
        $State = State::find($id);
        return view('State_Master', compact('State','Countrylist'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $state = State::findOrFail($id);

        $this->validate($request, [
            'country_id' => 'required',
            'state_name' => 'required',
        ]);

        $input = $request->all();

        $state->fill($input)->save();

        return redirect()->route('State.index')->with('message', 'Update Record Succesfully');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    
        State::where('state_id', $id)->update(array('delflag' => 1));

         Session::flash('delete', 'Deleted record successfully'); 


    }
}
