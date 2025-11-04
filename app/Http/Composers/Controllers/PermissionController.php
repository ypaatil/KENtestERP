<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PermissionController extends Controller
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
->where('form_id', '4')
->first();


      $userlist = Permission::join('user_type','user_type.utype_id','=','usermaster.user_type')
      ->where('usermaster.delflag','=', '0')
      ->get('usermaster.*','user_type.user_type');

         return view('User_Management_List',compact('userlist','chekform'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //


    $maxuserid = DB::select("select ifnull(MAX(userId),0) + 1 as 'userId' from usermaster");


        $VendorList = DB::table('ledger_master')->where('ac_code', '>',39)->get();    
      $workerlist = DB::table('job_worker_master')->get();

      $user_typelist = DB::table('user_type')->get();

      $formlist = UserManagement::where('delflag','=', '0')->get();

        return view('User_Management', compact('formlist','VendorList','workerlist','user_typelist','maxuserid'));
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
                'w_id' => 'required',
                'user_type' => 'required',
                'contact' => 'required',
                'address' => 'required',
                'username' => 'required',
                'password' => 'required',
                 'vendorId' => 'required',
        ]);

        $input = $request->all();

        Permission::create($input);


$row=$_POST['row'];
   $n=1;
// ECHO 'AS ROW' .$row;
while($n<=$row)
{
    if (isset($_POST['chk'.$n]))
     {

         $form_id=$_POST['form_id'.$n];


if (isset($_POST['chkw'.$n])){ $write=1;}else{$write=0;}
if (isset($_POST['chke'.$n])){ $edit=1;}else{$edit=0;}
if (isset($_POST['chkd'.$n])){ $delete=1;}else{$delete=0;}



DB::table('form_auth')->insert([
    'form_id' => $form_id,
    'emp_id' => $request->post('userId'),
    'w_id' => $request->post('w_id'),
    'write_access' => $write,
    'edit_access' => $edit,
    'delete_access' => $delete,
]);

  }
   $n = $n+1;
} 



        return redirect()->route('User_Management.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {




$formlistbyuser = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
->where('form_auth.emp_id','=',$id)
->get(['form_master.form_code', 'form_auth.write_access', 'form_auth.edit_access','form_auth.delete_access']);

/*
DB::enableQueryLog();
$query = DB::getQueryLog();
$query = end($query);
dd($query);*/

    $VendorList = DB::table('ledger_master')->where('ac_code', '>',39)->get();    
     $workerlist = DB::table('job_worker_master')->where('delflag','=', '0')->get();

      $user_typelist = DB::table('user_type')->where('delflag','=', '0')->get();

      $formlist = UserManagement::where('delflag','=', '0')->get();
      
         $permissions = Permission::find($id);
        
        return view('User_Management', compact('permissions','VendorList','workerlist','user_typelist','formlist','formlistbyuser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $emp_id)
    {
        //
  $permission = Permission::findOrFail($emp_id);

        $this->validate($request, [
           'w_id' => 'required',
             'user_type' => 'required',
              'contact' => 'required',
               'address' => 'required',
                'username' => 'required',
                'password' => 'required',
                'vendorId' => 'required',
        ]);

        $input = $request->all();

        $permission->fill($input)->save();


     DB::table('form_auth')->where('emp_id', $emp_id)->delete();


      $row=$_POST['row'];
   $n=1;
// ECHO 'AS ROW' .$row;
while($n<=$row)
{
    if (isset($_POST['chk'.$n]))
     {

         $form_id=$_POST['form_id'.$n];


if (isset($_POST['chkw'.$n])){ $write=1;}else{$write=0;}
if (isset($_POST['chke'.$n])){ $edit=1;}else{$edit=0;}
if (isset($_POST['chkd'.$n])){ $delete=1;}else{$delete=0;}



DB::table('form_auth')->insert([
    'form_id' => $form_id,
    'emp_id' => $request->post('userId'),
    'w_id' => $request->post('w_id'),
    'write_access' => $write,
    'edit_access' => $edit,
    'delete_access' => $delete,
]);

  }
   $n = $n+1;
} 




        return redirect()->route('User_Management.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
