<?php

namespace App\Http\Controllers;

use App\Models\ColorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Imports\ColorImport;
use Maatwebsite\Excel\Facades\Excel;
use Image;
use DataTables;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '47')
        ->first();
        
         $ColorList = ColorModel::join('usermaster', 'usermaster.userId', '=', 'color_master.userId')
        ->where('color_master.delflag','=', '0')
        ->get(['color_master.*','usermaster.username']);

        if ($request->ajax()) 
        {
            return Datatables::of($ColorList)
            ->addIndexColumn()
            ->addColumn('imagePath',function ($row) {
        
                 $path = "thumbnail/".$row->style_img_path;
                 $imagePath = '<a href="'.$path.'" target="_blank"><img src=""'.$path.'"" alt=""'.$path.'"" ></a>';
                 return $imagePath;
            }) 
            ->addColumn('action1', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('Color.edit', $row->color_id).'" >
                                <i class="fas fa-pencil-alt"></i>
                           </a>';
                }
                else
                { 
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>';   
                }
                return $btn3;
            })
            ->addColumn('action2', function ($row) use ($chekform){
         
                if($chekform->delete_access==1)
                {      
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->color_id.'"  data-route="'.route('Color.destroy', $row->color_id).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            })
            ->addColumn('action3', function ($row)  use ($chekform) 
            {  
                if($row->status == 1)
                {
                    $status = 'Active';
                    $color = 'success';
                }
                else
                {
                    $status = 'In Active';
                    $color = 'danger';
                }
                
                $btn1 = '<a class="btn btn-'.$color.' btn-sm" href="javascript:void(0);" color_id="'.$row->color_id.'" status="'.$row->status.'" onclick="ChangeStatus(this);" title="status">
                        '.$status.'
                    </a>';
           
                return $btn1;
            }) 
            ->rawColumns(['imagePath','action1','action2','action3'])
            ->make(true);
        }
        return view('ColorMasterList', compact('ColorList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ColorMaster');
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
            'color_name' => 'required',
        ]);


             // Upload style_pic_path
                $style_pic_path=$request->file('style_img_path');
              //  print_r($style_pic_path);
                if($style_pic_path) 
                {
               
                $image = $request->file('style_img_path');
                $input['imagename'] = time().'CLR.'.$image->getClientOriginalExtension();
                $request->style_img_path  = $input['imagename'];
                $destinationPath = public_path('/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$input['imagename']);
                $destinationPath1 = public_path('/images/');
                $image->move($destinationPath1, $input['imagename']);
                $StyleImageName=$input['imagename'];
                 
            }
            else
            {
                $StyleImageName='';
                 $request->style_img_path  = $StyleImageName;
            }

           
            
 

       // $input = $request->all();

        $data=array(
          
                    
                    'color_name'=>$request->color_name,
                    'style_img_path'=>$request->style_img_path,
                    'userId'=>$request->userId,
                     
         );

        ColorModel::insert($data);

       return redirect()->route('Color.index');
    }

        public function importcolor(Request $request)
    {


      Excel::import(new ColorImport,request()->file('colorfile'));


         return redirect()->route('Color.index');

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ColorModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function show(ColorModel $colorModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ColorModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        $ColorList = ColorModel::find($id);
        
        return view('ColorMaster',compact('ColorList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ColorModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ColorList = ColorModel::findOrFail($id);

        $this->validate($request, [
            'color_name' => 'required',
        ]);



             // Upload style_pic_path
                $style_pic_path=$request->file('style_img_path');
                if($style_pic_path) 
                {
               
                $image = $request->file('style_img_path');
                $input['imagename'] = time().'CLR.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$input['imagename']);
                $destinationPath1 = public_path('/images/');
                $image->move($destinationPath1, $input['imagename']);
                $StyleImageName=$input['imagename'];
                $request->style_img_path=$input['imagename'];
                if($request->style_img_pathold!='')
                {
                unlink('thumbnail/'.$request->style_img_pathold);
                unlink('images/'.$request->style_img_pathold);
                }
                
                
            }
            else
            {   $request->style_img_path=$request->style_img_pathold;
                 
            }

        
         $data=array(
          
                    
                    'color_name'=>$request->color_name,
                    'style_img_path'=>$request->style_img_path,
                    'userId'=>$request->userId,
                    'created_at'=>$request->created_at
                     
         );
        
 
        $ColorList->fill($data)->save();

        return redirect()->route('Color.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ColorModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        ColorModel::where('color_id', $id)->update(array('delflag' => 1));
         Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function changeColorStatus(Request $request)
    {    
         DB::table('color_master')->where('color_id',$request->color_id)->update(['status'=>$request->status]);
         return 1;
    } 
}
