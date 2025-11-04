<?php

namespace App\Http\Controllers;

use App\Models\PaymentTermsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PaymentTermsController extends Controller
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
->where('form_id', '76')
->first();      
        
        
        
        $PaymentTermsList = PaymentTermsModel::join('usermaster', 'usermaster.userId', '=', 'payment_term.userId')
        ->where('payment_term.delflag','=', '0')
        ->get(['payment_term.*','usermaster.username']);
  
        return view('PaymentTermsMasterList', compact('PaymentTermsList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
         
        return view('PaymentTermsMaster');
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
             
             
            'ptm_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    PaymentTermsModel::create($input);

    return redirect()->route('PaymentTerms.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentTermsModel  $PaymentTermsModel
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentTermsModel $PaymentTermsModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentTermsModel  $PaymentTermsModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $PaymentTermsList = PaymentTermsModel::find($id);
         
        return view('PaymentTermsMaster', compact('PaymentTermsList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentTermsModel  $PaymentTermsModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $PaymentTermsList = PaymentTermsModel::findOrFail($id);

        $this->validate($request, [
            
            'ptm_name'=> 'required',
           
        ]);

        $input = $request->all();

        $PaymentTermsList->fill($input)->save();

        return redirect()->route('PaymentTerms.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentTermsModel  $PaymentTermsModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PaymentTermsModel::where('ptm_id', $id)->update(array('delflag' => 1));
       Session::flash('delete', 'Deleted record successfully'); 
    }
}
