<?php

namespace App\Http\Controllers;

use App\Models\transportModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class transportController extends Controller
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
        ->where('form_id', '22')
        ->first();
        
        
        $Transports = transportModel::join('usermaster', 'usermaster.userId', '=', 'transport_master.userId')
        ->where('transport_master.delflag','=', '0')
        ->get(['transport_master.*','usermaster.username']);
  
        return view('transport_master_list', compact('Transports','chekform'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transport_master');
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
             
                'transport_name'=> 'required',
                'transport_contact'=> 'required',
                'transport_address'=> 'required', 
                'transport_email'=> 'required', 
                'gst_number'=> 'required',
                  
        ]);

        $input = $request->all();

        transportModel::create($input);

        return redirect()->route('Transport.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\transportModel  $transportModel
     * @return \Illuminate\Http\Response
     */
    public function show(transportModel $transportModel)
    {
         
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transportModel  $transportModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Transport = transportModel::find($id);
        // select * from transport_master where transport_id=$id;
        return view('transport_master', compact('Transport'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\transportModel  $transportModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $Transport = transportModel::findOrFail($id);

        $this->validate($request, [
                'transport_name'=> 'required',
                'transport_contact'=> 'required',
                'transport_address'=> 'required', 
                'transport_email'=> 'required', 
                'gst_number'=> 'required',
        ]);

        $input = $request->all();

        $Transport->fill($input)->save();

        return redirect()->route('Transport.index')->with('message', 'Update Record Succesfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\transportModel  $transportModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        transportModel::where('transport_id', $id)->update(array('delflag' => 1));
         Session::flash('delete', 'Deleted record successfully'); 
    }
}
