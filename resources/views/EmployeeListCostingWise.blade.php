@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .text-right
    {
        text-align:right;
    }
</style>
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Employee List</h4> 
      </div>
   </div>
</div> 
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="col-md-3 mb-5">
                <button type="button" id="export_button" class="btn btn-warning">Export</button>
            </div>
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="EmployeeList" class="table table-bordered dt-responsive nowrap w-100">
               <thead >
                  <tr  class="text-center">
                     <th>Sr. No.</th> 
                     <th>Employee Name</th>
                     <th>MIS Rate </th>
                     <th>Status</th> 
                  </tr>
               </thead>
               <tbody>
                   @php
                        $srno = 1;
                        $totalMISRate = 0;
                   @endphp
                   @foreach($empList as $rows)
                   <tr>
                       <td class="text-center">{{$srno++}}</td> 
                       <td>({{$rows->employeeCode}}) {{$rows->fullName}}</td>
                       <td class="text-center">{{money_format("%!.0n",$rows->misRate)}}</td>
                       <td class="text-center">{{$rows->status}}</td>
                   </tr>
                   @php
                     $totalMISRate += $rows->misRate;
                   @endphp
                   @endforeach
               </tbody>
               <tfoot>
                   <tr>
                       <th colspan="2" class="text-right"><b>Total:</b></th>
                       <th class="text-center"><b>{{money_format("%!.0n",$totalMISRate)}}</b></th>
                       <th class="text-center">-</th>
                   </tr>
               </tfoot>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
<script type="text/javascript"> 
        function html_table_to_excel(type)
     {
        var data = document.getElementById('EmployeeList');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Employee Cost Report.' + type);
      }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
</script>  
@endsection