<?php

namespace App\Http\Controllers;

use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class UserManagementController extends Controller
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
        ->where('form_id', '3')
        ->first();


        $forms = UserManagement::join('usermaster', 'usermaster.userId', '=', 'form_master.user_id')
        ->where('form_master.delflag','=', '0')
        ->get(['form_master.*','usermaster.username']);

        return view('Form_Master_List', compact('forms','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
     return view('Form_Master');

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
            'form_name' => 'required',
            'form_label' => 'required',
            'head_id' => 'required',
        ]);

        $input = $request->all();

        UserManagement::create($input);

        return redirect()->route('Form.index');



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function show(UserManagement $userManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $form = UserManagement::find($id);
        
        return view('Form_Master', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
            $form = UserManagement::findOrFail($id);

        $this->validate($request, [
           'form_name' => 'required',
            'form_label' => 'required',
             'head_id' => 'required',
        ]);

        $input = $request->all();

        $form->fill($input)->save();

        return redirect()->route('Form.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        UserManagement::where('form_code', $id)->update(array('delflag' => 1));

         Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function CheckFormSequence(Request $request)
    {
        $formData = DB::select("
            SELECT seq_no, form_label, COUNT(*) AS count 
            FROM form_master 
            WHERE head_id = ? AND seq_no = ?
        ", [$request->head_id, $request->seq_no]);
    
        if (!empty($formData)) {
            $count = $formData[0]->count ?? 0;
            $form_label = $formData[0]->form_label ?? '';
        } else {
            $count = 0;
            $form_label = '';
        }
    
        return response()->json([
            'form_label' => $form_label,
            'count' => $count
        ]);
    }

    
}
