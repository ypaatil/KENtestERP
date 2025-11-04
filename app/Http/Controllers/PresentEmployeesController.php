<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PresentEmployeesModel;
use Illuminate\Support\Facades\DB;
use Session;

class PresentEmployeesController extends Controller
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
        ->where('form_id', '99')
        ->first();
        //  DB::enableQueryLog();
        $presentEmployeesList = PresentEmployeesModel::join('usermaster', 'usermaster.userId', '=', 'presentEmployees.userId')
        ->get(['presentEmployees.*','usermaster.username']);
        return view('PresentEmployeesList',compact('presentEmployeesList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('PresentEmployeesMaster');    
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
        'pe_date' => 'required',
        'operators' => 'required',
        ]);
        $input = $request->all();
        PresentEmployeesModel::create($input);
        return redirect()->route('PresentEmployees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $PresentEmployee = PresentEmployeesModel::find($id);
        // dd(DB::getQueryLog());
        return view('PresentEmployeesMaster', compact('PresentEmployee'));    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pe_id = PresentEmployeesModel::findOrFail($id);

        $this->validate($request, [
            'pe_date' => 'required',
            'operators' => 'required',
        ]);
         // DB::enableQueryLog();
        $input = $request->all();
        $pe_id->fill($input)->save();
        // dd(DB::getQueryLog());

       return redirect()->route('PresentEmployees.index')->with('message', 'Update Record Succesfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('presentemployees')->where('pe_id', $id)->delete();       
        Session::flash('delete', 'Deleted record successfully');   
    }
}
