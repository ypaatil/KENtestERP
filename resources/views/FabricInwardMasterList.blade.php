@extends('layouts.master') 
@section('content')   
<style>
   .text-right
   {
      text-align:right;
   }
   
   .navbar-brand-box
   {
        width: 266px !important;
   }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Inward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Inward</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)   
<div class="row">
   <div class="col-md-4">
      <a href="{{ Route('FabricInward.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
   <div class="col-lg-4 text-center">
      <h4><b>Note : </b> Showing last 3 month records. If you want to all click on <a href="FabricInwardShowAll">Show All Data</a> button</h4>
   </div>
   <div class="col-lg-4 text-right">
      <a href="FabricInwardShowAll"><button type="button" class="btn btn-warning w-md float-right">Show All Data</button></a> &nbsp; &nbsp; &nbsp;
      <a href="FabricInward"><button type="button" class="btn btn-danger w-md float-right">Back</button></a> &nbsp; &nbsp; &nbsp;
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
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr No</th>
                     <th>Sr GRN No</th>
                     <th>GRN No</th>
                     <th>GRN Date</th>
                     <th>CPO Code</th>
                     <th>Supplier Name</th>
                     <th>Bill To</th>
                     <th>PO No</th>
                     <th>Buyer Name</th>
                     <th>Total meter</th>
                     <th>Total Amount</th>
                     <th>Total Taga</th>
                     <th>Supplier Invoice No</th>
                     <th>Print</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                    @foreach($FabricInwardList as $row)
                        <tr>
                           <td>@php $number = intval(substr($row->in_code,12,20)); echo $number; @endphp</td>
                           <td>{{ $row->sr_no }}</td>
                           <td>{{ $row->in_code }}</td>
                           <td>{{ $row->in_date }}</td>
                           <td>{{ $row->vpo_code }}</td>
                           <td>{{ $row->ac_short_name }}</td>
                           <td>{{ $row->trade_name }}@if($row->site_code!='')({{$row->site_code}})@endif</td>
                           <td>{{ $row->po_code }}</td>
                           <td>{{ $row->buyer }}</td>
                           <td>{{ $row->total_meter }}</td>
                           <td>{{ $row->total_amount }}</td>
                           <td>{{ $row->total_taga_qty }}</td>
                           <td>{{ $row->invoice_no }}</td>
                           <td>
                              <a class="btn btn-outline-secondary btn-sm print" target="_blank" 
                                 href="FabricGRNPrintNew/{{ base64_encode($row->in_code) }}" title="print">
                                 <i class="fas fa-print"></i>
                              </a>
                           </td>
                           @if($chekform->edit_access==1)
                              <td>
                                 @if($row->in_code!='GRN/21-22/FP1099')
                                 <a class="btn btn-outline-secondary btn-sm edit" 
                                    href="{{route('FabricInward.edit', $row->sr_no)}}" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                 </a>
                                 @endif
                              </td>
                           @else
                              <td><a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit"><i class="fas fa-lock"></i></a></td>
                           @endif
                        
                           @if($chekform->delete_access==1 && $row->total_count == 0)
                              <td>
                                 @if($row->in_code!='GRN/21-22/FP1099')
                                 <button class="btn btn-sm delete" id="DeleteRecord"
                                         data-token="{{ csrf_token() }}"
                                         data-id="{{ base64_encode($row->in_code) }}"
                                         data-route="{{route('FabricInward.destroy', base64_encode($row->in_code))}}"
                                         title="Delete">
                                    <i class="fas fa-trash"></i>
                                 </button>
                                 @endif
                              </td>
                           @else
                              <td>
                                 <button class="btn btn-outline-secondary btn-sm delete" title="Delete">
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