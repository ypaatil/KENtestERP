<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

    public function UnderMaintance()
    {
         return view('UnderMaintance');
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 

        $maxuserid = DB::select("select ifnull(MAX(userId),0) + 1 as 'userId' from usermaster"); 

        $VendorList = DB::table('ledger_master')->where('ac_code', '>',39)->get();    
        $workerlist = DB::table('job_worker_master')->get();

        $user_typelist = DB::table('user_type')->get();
        
        $processlist = DB::table('process_master')->get();

        $potypelist = DB::table('po_type_master')->get();
        
        $GPOApprovelist = DB::table('gpo_approval_master')->where('delflag','=', '0')->get();
        
        $formlist = UserManagement::where('delflag','=', '0')->get();

        return view('User_Management', compact('formlist','VendorList','workerlist','user_typelist','maxuserid','processlist','GPOApprovelist','potypelist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
           // echo '<pre>';print_r($_POST);exit;
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
    
        DB::table('process_auth')->where('username', $request->username)->delete();
        
        foreach($request->process_id as $key => $process)
        {
            $read = 'subProcessRead'.$process;
            $write = 'subProcessWrite'.$process;
            $edit = 'subProcessEdit'.$process;
            $delete = 'subProcessDelete'.$process;
            
            $username = $request->username;
            $process_id = $process;
            $read = isset($request->$read[0]) ? $request->$read[0] : 0;
            $write = isset($request->$write[0]) ? $request->$write[0] : 0;
            $edit = isset($request->$edit[0]) ? $request->$edit[0] : 0;
            $delete = isset($request->$delete[0]) ? $request->$delete[0] : 0;
            
            DB::table('process_auth')->insert([ 
                'username' => $username,
                'process_id' => $process_id,
                'isRead' => $read,
                'isWrite' => $write,
                'isEdit' => $edit,
                'isDelete' => $delete,
            ]);
        }
  
        $username = isset($request->username) ? $request->username : "";
        DB::table('packing_auth')->where('username', $request->username)->delete();       
        for($i=1;$i<=4;$i++)
        { 
            $read1 = 'packingRead'.$i;
            $write1 = 'packingWrite'.$i;
            $edi1 = 'packingEdit'.$i;
            $delete1 = 'packingDelete'.$i;
            
            $packingRead = isset($request->$read1[0]) ? $request->$read1[0] : 0;
            $packingWrite = isset($request->$write1[0]) ? $request->$write1[0] : 0;
            $packingEdit = isset($request->$edi1[0]) ? $request->$edi1[0] : 0;
            $packingDelete = isset($request->$delete1[0]) ? $request->$delete1[0] : 0;
           
            DB::table('packing_auth')->insert([ 
                'username' => $username,
                'packing_type_auth_id' => $i,
                'isRead' => $packingRead,
                'isWrite' => $packingWrite,
                'isEdit' => $packingEdit,
                'isDelete' => $packingDelete,
            ]);
        }
          
        DB::table('order_group_auth')->where('username', $request->username)->delete();    
        if(count($request->og_id) > 0)
        {
            foreach($request->og_id as $row)
            { 
                //DB::enableQueryLog();
                DB::table('order_group_auth')->insert([ 
                    'username' => $username,
                    'og_id' => $row
                ]);
                //dd(DB::getQueryLog());
            }
        }
              
        DB::table('po_type_auth')->where('username', $request->username)->delete();    
        if(count($request->po_type_id) > 0)
        {
            foreach($request->po_type_id as $row)
            { 
                //DB::enableQueryLog();
                DB::table('po_type_auth')->insert([ 
                    'username' => $username,
                    'po_type_id' => $row
                ]);
                //dd(DB::getQueryLog());
            }
        }
        
        
        DB::table('sales_head_auth')->where('username', $request->username)->delete();    
        if(count($request->sales_head_id) > 0)
        {
            foreach($request->sales_head_id as $row1)
            { 
                //DB::enableQueryLog();
                DB::table('sales_head_auth')->insert([ 
                    'username' => $username,
                    'sales_head_id' => $row1
                ]);
                //dd(DB::getQueryLog());
            }
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
        $processlist = DB::SELECT("SELECT * FROM process_master");
        
        $formlistbyuser = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
            ->where('form_auth.emp_id','=',$id)
            ->get(['form_master.form_code', 'form_auth.write_access', 'form_auth.edit_access','form_auth.delete_access']);
     
    
        $VendorList = DB::table('ledger_master')->where('ac_code', '>',39)->get();    
        $workerlist = DB::table('job_worker_master')->where('delflag','=', '0')->get();

        $user_typelist = DB::table('user_type')->where('delflag','=', '0')->get();

        $formlist = UserManagement::where('delflag','=', '0')->get();
      
        $GPOApprovelist = DB::table('gpo_approval_master')->where('delflag','=', '0')->get();
        
        $potypelist = DB::table('po_type_master')->get();

        $permissions = Permission::find($id);
        
        return view('User_Management', compact('permissions','VendorList','workerlist','user_typelist','formlist','formlistbyuser','processlist','GPOApprovelist','potypelist'));
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
        //echo '<pre>';print_r($_POST);exit;
        // //
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
        DB::table('process_auth')->where('username', $request->username)->delete();
        if($request->process_id != "")
        {
            foreach($request->process_id as $key => $process)
            {
                $read = 'subProcessRead'.$process;
                $write = 'subProcessWrite'.$process;
                $edit = 'subProcessEdit'.$process;
                $delete = 'subProcessDelete'.$process;
                
                $username = $request->username;
                $process_id = $process;
                $read = isset($request->$read[0]) ? $request->$read[0] : 0;
                $write = isset($request->$write[0]) ? $request->$write[0] : 0;
                $edit = isset($request->$edit[0]) ? $request->$edit[0] : 0;
                $delete = isset($request->$delete[0]) ? $request->$delete[0] : 0;
                
                DB::table('process_auth')->insert([ 
                    'username' => $username,
                    'process_id' => $process_id,
                    'isRead' => $read,
                    'isWrite' => $write,
                    'isEdit' => $edit,
                    'isDelete' => $delete,
                ]);
            }
        }

        $username = isset($request->username) ? $request->username : "";
        DB::table('packing_auth')->where('username', $request->username)->delete();       
        for($i=1;$i<=4;$i++)
        { 
            $read1 = 'packingRead'.$i;
            $write1 = 'packingWrite'.$i;
            $edi1 = 'packingEdit'.$i;
            $delete1 = 'packingDelete'.$i;
            
            $packingRead = isset($request->$read1[0]) ? $request->$read1[0] : 0;
            $packingWrite = isset($request->$write1[0]) ? $request->$write1[0] : 0;
            $packingEdit = isset($request->$edi1[0]) ? $request->$edi1[0] : 0;
            $packingDelete = isset($request->$delete1[0]) ? $request->$delete1[0] : 0;
            //DB::enableQueryLog();
            DB::table('packing_auth')->insert([ 
                'username' => $username,
                'packing_type_auth_id' => $i,
                'isRead' => $packingRead,
                'isWrite' => $packingWrite,
                'isEdit' => $packingEdit,
                'isDelete' => $packingDelete,
            ]);
            //dd(DB::getQueryLog());
        }  
        
        
        DB::table('order_group_auth')->where('username', $request->username)->delete();       
        if(!empty($request->og_id))
        {
            foreach($request->og_id as $row)
            { 
                //DB::enableQueryLog();
                DB::table('order_group_auth')->insert([ 
                    'username' => $username,
                    'og_id' => $row
                ]);
                //dd(DB::getQueryLog());
            }
        }
        
        DB::table('po_type_auth')->where('username', $request->username)->delete();    
        if(count($request->po_type_id) > 0)
        {
            foreach($request->po_type_id as $row)
            { 
                //DB::enableQueryLog();
                DB::table('po_type_auth')->insert([ 
                    'username' => $username,
                    'po_type_id' => $row
                ]);
                //dd(DB::getQueryLog());
            }
        }
        
        DB::table('sales_head_auth')->where('username', $request->username)->delete();   
        if($request->sales_head_id != "")
        {
            if(count($request->sales_head_id) > 0)
            {
                foreach($request->sales_head_id as $row1)
                { 
                    //DB::enableQueryLog();
                    DB::table('sales_head_auth')->insert([ 
                        'username' => $username,
                        'sales_head_id' => $row1
                    ]);
                    //dd(DB::getQueryLog());
                }
            }
        }
        return redirect()->route('User_Management.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('usermaster')->where('userId', $id)->delete();
        return redirect()->route('User_Management.index')->with('message', 'Deleted record successfully'); 
    }
    
    public function UserManagementReport(Request $request)
    { 
         $UserMgmtData = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
            ->join('usermaster', 'usermaster.userId', '=', 'form_auth.emp_id')
            ->get(['usermaster.userId','usermaster.username','form_master.form_label','form_master.form_name','form_master.form_code', 'form_auth.write_access', 'form_auth.edit_access','form_auth.delete_access']);
             

        return view('UserManagementReport',compact('UserMgmtData'));

    }
}
