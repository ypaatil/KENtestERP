@extends('layouts.master') 
@section('content')   
<style>
   .text-right
   {
   text-align:right;
   }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trim Gate Entry</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Trim Gate Entry</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)   
<div class="row">
   <div class="col-md-4">
      <a href="{{ Route('TrimGateEntry.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
   <div class="col-lg-4 text-center">
      <h4><b>Note : </b> Showing last 3 month records. If you want to all click on <a href="TrimGateEntryShowAll">Show All Data</a> button</h4>
   </div>
   <div class="col-lg-4 text-right">
      <a href="TrimGateEntryShowAll"><button type="button" class="btn btn-warning w-md float-right">Show All Data</button></a> &nbsp; &nbsp; &nbsp;
      <a href="TrimGateEntry"><button type="button" class="btn btn-danger w-md float-right">Back</button></a> &nbsp; &nbsp; &nbsp;
   </div>
</div>
@endif
@if(session()->has('message'))
<div class="col-md-3">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('messagedelete'))
<div class="col-md-3">
   <div class="alert alert-danger">
      {{ session()->get('messagedelete') }}
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr No</th>
                     <th>TGE Code</th>
                     <th>TGE Date</th>
                     <th>PO Code</th>
                     <th>Supplier Name</th>
                     <th>DC No.</th>
                     <th>DC Date.</th>
                     <th>Invoice No</th>
                     <th>Invoice Date</th>
                     <th>Total Qty</th>
                     <th>Total Amount</th>
                     <th>User Name</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $sr_no = 1;
                  @endphp
                  @foreach($TrimGateEntryList as $row)    
                  <tr>
                     <td>{{ $sr_no++ }}</td>
                     <td>{{ $row->tge_code }}</td>
                     <td>{{ $row->tge_date }}</td>
                     <td>{{ $row->po_code ?? $row->po_code2 }}</td>
                     <td>{{ $row->Ac_name }}</td>
                     <td>{{ $row->dc_no }}</td>
                     <td>{{ $row->dc_date }}</td>
                     <td>{{ $row->invoice_no }}</td>
                     <td>{{ $row->invoice_date }}</td>
                     <td>{{ $row->total_qty }}</td>
                     <td>{{ $row->total_amt }}</td> 
                     <td>{{ $row->username }}</td> 
                     @if($chekform->edit_access==1 && $row->userId == Session::get('userId') || Session::get('user_type') == 1)
                     <td> 
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('TrimGateEntry.edit', $row->tge_code)}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a> 
                     </td>
                     @else
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                        <i class="fas fa-lock"></i>
                        </a>
                     </td>
                     @endif
                     @if($chekform->delete_access==1 && $row->userId == Session::get('userId') || Session::get('user_type') == 1)
                     <td> 
                        <button  class="btn btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ base64_encode($row->tge_code) }}"  data-route="{{route('TrimGateEntry.destroy', base64_encode($row->tge_code) )}}" title="Delete">
                            <i class="fas fa-trash"></i> 
                        </button>
                     </td>
                     @else
                     <td>
                        <button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                        <i class="fas fa-lock"></i>
                        </button>
                     </td>
                     @endif
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 
   $(document).on('click','#DeleteRecord',function(e) {
   
      var Route = $(this).attr("data-route");
      var id = $(this).data("id");
      var token = $(this).data("token");
   
     //alert(Route);
    
    //alert(data);
      if (confirm("Are you sure you want to Delete this Record?") == true) {
    $.ajax({
           url: Route,
           type: "DELETE",
            data: {
            "id": id,
            "_method": 'DELETE',
             "_token": token,
             },
           
           success: function(data){
   
              //alert(data);
           location.reload();
   
           }
   });
   }
   
   });
</script> 
@endsection