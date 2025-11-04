<?php

namespace App\Http\Controllers;

use App\Models\MainStyleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;
use Session;

class MainStyleController extends Controller
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
        ->where('form_id', '86')
        ->first();  
        
        $MainStyleList = MainStyleModel::join('usermaster', 'usermaster.userId', '=', 'main_style_master.userId')
        ->where('main_style_master.delflag','=', '0')
        ->get(['main_style_master.*','usermaster.username']);
  
        return view('MainStyleMasterList', compact('MainStyleList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('MainStyleMaster');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validate the required fields
            $this->validate($request, [
                'mainstyle_name' => 'required',
            ]);
    
            // Create the main style record
            $input = $request->all();
            $mainStyle = MainStyleModel::create($input);
            $mainstyle_id = $mainStyle->mainstyle_id;
    
            // Check if a file was uploaded
            if ($request->hasFile('mainstyle_image')) {
                $file = $request->file('mainstyle_image');
    
                if ($file->isValid()) {
                    $filename = time().'_'.$file->getClientOriginalName();
                    $location = public_path('uploads/MainStyleImages/');
    
                    // Create directory if it doesn’t exist
                    if (!file_exists($location)) {
                        mkdir($location, 0777, true);
                    }
    
                    // Delete old image if exists
                    $oldFile = $location . $mainstyle_id;
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
    
                    // Move the uploaded file
                    $file->move($location, $filename);
    
                    // Update image name in database
                    DB::table('main_style_master')
                        ->where('mainstyle_id', $mainstyle_id)
                        ->update(['mainstyle_image' => $filename]);
                }
            }
    
            return redirect()->route('MainStyle.index')->with('success', 'Main Style created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainStyleModel  $MainStyleModel
     * @return \Illuminate\Http\Response
     */
    public function show(MainStyleModel $MainStyleModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainStyleModel  $MainStyleModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // DB::enableQueryLog();
        $MainStyleList = MainStyleModel::find($id);
        //  dd(DB::getQueryLog());
        return view('MainStyleMaster', compact('MainStyleList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MainStyleModel  $MainStyleModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $MainStyleList = MainStyleModel::findOrFail($id);
    
            $this->validate($request, [
                'mainstyle_name' => 'required',
            ]);
    
            $input = $request->all();
            $MainStyleList->fill($input)->save();
    
            $mainstyle_id = $id;
            $file = $request->file('mainstyle_image');
    
            if ($file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $location = public_path('uploads/MainStyleImages/');
    
                // Check if old image exists
                $oldImage = 'uploads/MainStyleImages/' . $MainStyleList->mainstyle_image;
                if (file_exists(public_path($oldImage)) && !is_dir($oldImage)) {
                    unlink(public_path($oldImage));
                }
    
                // Move new file
                $file->move($location, $filename);
    
                // Update DB with new filename
                DB::table('main_style_master')
                    ->where('mainstyle_id', $mainstyle_id)
                    ->update(['mainstyle_image' => $filename]);
            }
    
            return redirect()
                ->route('MainStyle.index')
                ->with('message', 'Record updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainStyleModel  $MainStyleModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MainStyleModel::where('mainstyle_id', $id)->update(array('delflag' => 1));
     Session::flash('delete', 'Deleted record successfully'); 
    }
 
    public function changeMainStyleCategoryStatus(Request $request)
    {    
         DB::table('main_style_master')->where('mainstyle_id',$request->mainstyle_id)->update(['status'=>$request->status]);
         return 1;
    } 
}
