<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\ItemModel;

class ItemImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
       
   foreach ($collection as $row) 
        {
            ItemModel::create([
'class_id' => $row[0],
'cat_id' => $row[1],  
'material_type_id' => $row[2],
'item_name' => $row[3],
'quality_code' => $row[4],
'item_description' => $row[5],
'color_name' => $row[6],
'unit_id' => $row[7],
'dimension' => $row[8],
'item_image_path' => $row[9],
'moq' => $row[10],
'item_rate' => $row[11],
'item_mrp' => $row[12],
'cgst_per' => $row[13],
'sgst_per' => $row[14],
'igst_per' => $row[15],
'hsn_code' => $row[16],                
'pur_rate' => $row[17],                 
'sale_rate' => $row[18],                   
'userId' => $row[19],     
'delflag' => $row[20], 
            ]);
        }


    }
}
