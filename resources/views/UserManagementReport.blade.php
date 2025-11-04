@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">User Management Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">User Management Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>                     
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th nowrap>Sr No</th>
                        <th nowrap>User Id</th>
                        <th nowrap>User Name</th>
                        <th nowrap>Form Label</th>
                        <th nowrap>Form Name</th>
                        <th nowrap>Read Access</th> 
                        <th nowrap>Write Access</th>
                        <th nowrap>Edit Access</th>
                        <th nowrap>Delete Access</th> 
                     </tr>
                  </thead>
                  <tbody>
                    @php $srno = 1; @endphp
                    @foreach($UserMgmtData as $row) 
                        <tr>
                            <td style="text-align:center; white-space:nowrap"> {{ $srno++ }} </td>
                            <td style="white-space:nowrap"> {{ $row->userId }} </td>
                            <td style="white-space:nowrap"> {{ $row->username }} </td>
                            <td style="white-space:nowrap"> {{ $row->form_label }} </td>
                            <td style="white-space:nowrap"> {{ $row->form_name }} </td>
                            <td style="white-space:nowrap"> {{ $row->write_access == 1 ?  'Yes' : 'No'  }} </td>
                            <td style="white-space:nowrap"> {{ $row->write_access == 1 ?  'Yes' : 'No'  }} </td>
                            <td style="white-space:nowrap"> {{ $row->edit_access == 1 ?  'Yes' : 'No'  }}</td>
                            <td style="white-space:nowrap"> {{ $row->delete_access == 1 ?  'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>

    function exportTableToExcel(tableID, filename = '') 
    {
        let table = document.getElementById(tableID);
        let wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    
        XLSX.writeFile(wb, filename ? `${filename}.xlsx` : 'export.xlsx');
    }

</script>

@endsection