<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTermsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class DeliveryTermsController extends Controller
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
->where('form_id', '77')
->first();  
        
        
        $DeliveryTermsList = DeliveryTermsModel::join('usermaster', 'usermaster.userId', '=', 'delivery_terms_master.userId')
        ->where('delivery_terms_master.delflag','=', '0')
        ->get(['delivery_terms_master.*','usermaster.username']);
  
        return view('DeliveryTermsMasterList', compact('DeliveryTermsList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
         
        return view('DeliveryTermsMaster');
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
             
             
            'delivery_term_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    DeliveryTermsModel::create($input);

    return redirect()->route('DeliveryTerms.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryTermsModel  $DeliveryTermsModel
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryTermsModel $DeliveryTermsModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryTermsModel  $DeliveryTermsModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $DeliveryTermsList = DeliveryTermsModel::find($id);
         
        return view('DeliveryTermsMaster', compact('DeliveryTermsList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryTermsModel  $DeliveryTermsModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $DeliveryTermsList = DeliveryTermsModel::findOrFail($id);

        $this->validate($request, [
            
            'delivery_term_name'=> 'required',
           
        ]);

        $input = $request->all();

        $DeliveryTermsList->fill($input)->save();

        return redirect()->route('DeliveryTerms.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryTermsModel  $DeliveryTermsModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DeliveryTermsModel::where('dterm_id', $id)->update(array('delflag' => 1));
     Session::flash('delete', 'Deleted record successfully'); 
    }
}
