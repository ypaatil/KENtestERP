<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\QualityModel;

class QualityImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        
    foreach ($collection as $row) 
        {
            QualityModel::create([
                'quality_name' => $row[0],
                'userId' => 1,
                'delflag' => 0,
            ]);
        }


    }
}
