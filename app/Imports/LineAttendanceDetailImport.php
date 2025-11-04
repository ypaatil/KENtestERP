<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LineAttendanceDetailImport implements ToCollection,WithStartRow
{
    /**
    * @param array $row
    *
    * @return ¥Illuminate¥Database¥Eloquent¥Model|null
    */

public function collection(Collection $rows)
{
    
    $attendancedatenew=array();

foreach ($rows as $row) 
{
    
    
    
 $delete=DB::table('line_wise_attendancelogs')->where('lineAttendanceDate',date('Y-m-d',strtotime($row[0])))->delete();     
    
    
    
$data[]=array(
    
'enteryDate' => date('Y-m-d'),    
'lineAttendanceDate' => date('Y-m-d',strtotime($row[0])),
'EmployeeCode' => $row[1],
'employeeName' => $row[2],
'Company'=> $row[3],
'Department'=>$row[4],
'Category'=> $row[5],
'Degination'=> $row[6],
'Grade'=> $row[7],
'Team'=> $row[8],
'Shift'=> $row[9], 
'InTime'=> $row[10],
'OutTime'=> $row[11],
'Duration'=> $row[12],
'LateBy'=> $row[13], 
'EarlyBy'=> $row[14], 
'Status'=> 14,
'Punch_Records'=> $row[16],
'OverTime'=> $row[17],
'attendanceFlag'=>1,
);


}

    DB::table('line_wise_attendancelogs')->insert($data);

}

  public function startRow(): int
    {
        return 2;
    }


}
