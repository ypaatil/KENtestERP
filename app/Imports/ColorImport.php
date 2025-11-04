<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\ColorModel;

class ColorImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
       
 foreach ($collection as $row) 
        {
            ColorModel::create([
                'color_name' => $row[0],
                'userId' => 1,
                'delflag' => 0,
            ]);
        }


    }
}
