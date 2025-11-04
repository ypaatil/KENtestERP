<?php

namespace App\Http\Controllers;

use App\Models\PurposeMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PurposeMasterController extends Controller
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
        ->where('form_id', '1')
        ->first();




        $PurposeMasterList = PurposeMasterModel::join('usermaster','usermaster.userId', '=','purpose_master.userId')
        ->where('purpose_master.delflag','=', '0')
        ->get(['purpose_master.*','usermaster.username']);


        return view('Purpose_Master_List', compact('PurposeMasterList','chekform'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('Purpose_Master');
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
            'Purpose_Name' => 'required',
        ]);

        $input = $request->all();

        PurposeMasterModel::create($input);

        return redirect()->route('PurposeMaster.index')->with('message', 'New Record Saved Succesfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(PurposeMaster $Purpose)
    {
        //
     


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit($Purpose_ID)
    {
        //

        $purposemaster = PurposeMasterModel::find($Purpose_ID);
        
        return view('Purpose_Master', compact('purposemaster'));



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
        $purposemaster = PurposeMasterModel::findOrFail($id);

        $this->validate($request, [
            'Purpose_Name' => 'required',
        ]);

        $input = $request->all();

        $purposemaster->fill($input)->save();

        return redirect()->route('PurposeMaster.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy($Purpose_ID)
    {
        //$country = Country::findOrFail($c_id);

      // $country->delete();
      

      PurposeMasterModel::where('Purpose_ID', $Purpose_ID)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}
