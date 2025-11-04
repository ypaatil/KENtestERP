<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\CategoryModel;
use App\Models\QualityModel;
use App\Models\ClassificationModel;
use Image;
use App\Models\MaterialTypeModel;
use Illuminate\Support\Facades\DB;
use Session;
use App\Imports\ItemImport;
use Maatwebsite\Excel\Facades\Excel;


class ItemMasterController extends Controller
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
        ->where('form_id', '8')
        ->first();


        $data = ItemModel::join('item_category', 'item_category.cat_id', '=', 'item_master.cat_id')
        ->join('classification_master', 'classification_master.class_id', '=', 'item_master.class_id', 'left outer')
        ->join('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
        ->join('usermaster', 'usermaster.userId', '=', 'item_master.userId')
        ->get(['item_master.*','usermaster.username','item_category.cat_name','classification_master.class_name','quality_master.quality_name']);

        return view('Item_Master_List', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $Categorylist = CategoryModel::where('delflag','=', '0')->get();
        $Classificationlist = ClassificationModel::where('delflag','=', '0')->get();
        $MaterialTypeList = MaterialTypeModel::where('delflag','=', '0')->get();
        $UnitList = UnitModel::where('delflag','=', '0')->get();
        $QualityList = QualityModel::where('delflag','=', '0')->get();
     return view('ItemMaster',compact('Classificationlist','Categorylist','MaterialTypeList','UnitList','QualityList'));
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
            'cat_id' => 'required',
            'class_id' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
            
         
        ]);


    $item_image_path=$request->file('item_image_path');
    if($item_image_path) 
    {
   
        $image = $request->file('item_image_path');
        $input['imagename'] = time().'I1.'.$image->getClientOriginalExtension();
     
        $destinationPath = public_path('/thumbnail');
        $img = Image::make($image->getRealPath());
        $img->resize(50, 50, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath.'/'.$input['imagename']);

    $destinationPath1 = public_path('/images/');
   // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $image->move($destinationPath1, $input['imagename']);
    $ItemImageName=$input['imagename'];
}
else
{
    $ItemImageName='';
}


        $input = $request->all();
        $input['item_image_path'] = $ItemImageName;
          $input['delflag'] = $request->active;
        ItemModel::create($input);

        return redirect()->route('Item.index');

    }



        public function itemimport(Request $request)
    {


      Excel::import(new ItemImport,request()->file('itemfile'));


        return redirect()->route('Item.index');

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
    
    
       public function activeDeactiveList($id)
    {
        
         $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '8')
        ->first();


        $data = ItemModel::join('item_category', 'item_category.cat_id', '=', 'item_master.cat_id')
        ->join('classification_master', 'classification_master.class_id', '=', 'item_master.class_id', 'left outer')
        ->join('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
        ->join('usermaster', 'usermaster.userId', '=', 'item_master.userId')
        ->where('item_master.delflag','=', $id)
        ->get(['item_master.*','usermaster.username','item_category.cat_name','classification_master.class_name']);

        return view('itemListAD', compact('data','chekform'));
      
    }
    

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $Classificationlist = ClassificationModel::where('delflag','=', '0')->get();
        $Categorylist = CategoryModel::where('delflag','=', '0')->get();
        $UnitList = UnitModel::where('delflag','=', '0')->get();
        $QualityList = QualityModel::where('delflag','=', '0')->get();
        $MaterialTypeList = MaterialTypeModel::where('delflag','=', '0')->get();
        $items = ItemModel::find($id);

        return view('ItemMaster', compact('items','Classificationlist','Categorylist','MaterialTypeList','UnitList','QualityList'));
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
    
        $item = ItemModel::findOrFail($id);

        $this->validate($request, [
            'cat_id' => 'required',
            'class_id' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
            
           
        ]);



    $item_image_path=$request->file('item_image_path');
    if($item_image_path) 
    {
       
        $image = $request->file('item_image_path');
        $input['imagename'] = time().'I1.'.$image->getClientOriginalExtension();
        
        $destinationPath = public_path('/thumbnail');
        $img = Image::make($image->getRealPath());
        $img->resize(50, 50, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath.'/'.$input['imagename']);

    $destinationPath1 = public_path('/images/');
   // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $image->move($destinationPath1, $input['imagename']);
  
   $ItemImageName=$input['imagename'];
   
}
else
{
    $ItemImageName= $request->old_item_image_path;
}

// print_r($ItemImageName);
// exit();


        $input = $request->all();
        $input['item_image_path'] = $ItemImageName;
        $input['delflag'] = $request->active;
        $item->fill($input)->save();
       ItemModel::where('item_code', $id)->update(array('delflag' => $request->active));
       return redirect()->route('Item.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        ItemModel::where('item_code', $id)->update(array('delflag' => 1));

        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    
   public function GetClassList(Request $request)
    {  $html = '';
          if (!$request->cat_id) {
        $html = '<option value="">-Classification-</option>';
        } 
        else
        {
            $html = '<option value="">-Classification-</option>';
            $ClassList = DB::table('classification_master')->where('cat_id', $request->cat_id)->get();
            foreach ($ClassList as $row) 
            {
                    $html .= '<option value="'.$row->class_id.'">'.$row->class_name.'</option>';
            }
        }
       return response()->json(['html' => $html]);
    } 
    
    
      
public function itemexist(Request $request)
{

    $item_name = $request->item_name;


$item = DB::table('item_master')->where('item_name', '=', $request->item_name)->where('delflag','=',0)

->first();



if ($item != null) {


      $html ='<span style="color:#FF0000; weight:bold;">Item is already exist</span>';

$html .= '
<div class="mb-3" id="itemshow">
<label for="formrow-email-input" class="form-label">Item Name</label>
<input type="text" name="item_name" class="form-control" id="formrow-email-input" value="" onBlur="itemExist(this.value)" required>
</div>
';

}

else {
 
$html = '
<div class="mb-3" id="itemshow">
<label for="formrow-email-input" class="form-label">Item Name</label>
<input type="text" name="item_name" class="form-control" id="formrow-email-input" value="'.$item_name.'" onBlur="itemExist(this.value)" required>
</div>
';
  
}


return response()->json(['html' => $html]);

}
    
    
}
