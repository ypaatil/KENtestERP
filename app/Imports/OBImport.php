<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\EmployeeModel;
use DB;
use Session;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\OBMasterModel;

class OBImport implements ToCollection,WithStartRow
{
    /**
    * @param Collection $collection
    */
    
        protected $mainstyle_id;
    
    
    public function __construct($mainstyle_id)
    {
          
          $this->mainstyle_id=$mainstyle_id;
          
           
    }
    
    
    
    public function collection(Collection $rows)
    {
        
        
          $id = $data['id'] ?? null;
        
  
            $IODetails = OBMasterModel::updateOrCreate(
                ['ob_id'=> $id],
                ['sub_company_id'=>Session::get('vendorId'),
                 'mainstyle_id'=>$this->mainstyle_id,
                 'is_deleted'=>0,
                 'userId'=>Session::get('vendorId')
                ]);
                
                  $ob_id=OBMasterModel::max('ob_id');
                  
                  
        DB::table('ob_details')->where('ob_id',$ob_id)->delete();      
      
 
  $exist=[]; $msg="";
  
  foreach ($rows as $row) 
  {
    
            if(isset($exist[$row[0]])) {
            
            $msg.=$row[0].',';

            
            } else {
            
            $exist[$row[0]] = $row[0];
            }
          
     
          
//     DB::table('ob_details')->insert([
//     'ob_id' => $ob_id,  
//     'sub_company_id' => Session::get('vendorId'),   
//     'mainstyle_id' => $this->mainstyle_id,
//      'operation_id' => $row[0],
//      'operation_name'=> $row[1],
//      'sam'=> $row[2] 
// ]);


   $data2[]=array(
    'ob_id' => $ob_id,  
    'sub_company_id' => Session::get('vendorId'),   
    'mainstyle_id' => $this->mainstyle_id,
     'operation_id' => $row[0],
     'operation_name'=> $row[1],
     'sam'=> $row[2]  
       );

   }
   
   
   if($msg)
   {
       echo rtrim($msg,",").' - Operations already exist';
       
       exit;
   } 
   
    DB::table('ob_details')->insert($data2);


}

  public function startRow(): int
    {
        return 2;
    }
 

}
