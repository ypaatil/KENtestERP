@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">KDPL Wise Set Percentage Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">KDPL Wise Set Percentage</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
   <h4 class="card-title mb-4">KDPL Wise Set Percentage</h4>
   @if ($errors->any())
   <div class="col-md-6">
      <div class="alert alert-danger">
         <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
   </div>
   @endif 
   <form action="{{ route('KDPLWiseSetPercentage.create') }}" method="GET">  
      <div class="row">
            <div class="col-md-2">
             <div class="mb-3">
                    <label for="job_status_id" class="form-label">Order Status</label>
                    <select name="job_status_id" class="form-control select2" id="job_status_id" >
                       <option value="">--Select--</option>
                       @foreach($JobStatusList as  $row)
                       <option value="{{ $row->job_status_id }}" {{ $row->job_status_id == $job_status_id ? 'selected="selected"' : '' }} >{{ $row->job_status_name }}</option>
                       @endforeach
                    </select>
                </div>
            </div>
         <div class="col-md-5">
            <div class="mt-4">
               <label for="formrow-email-input" class="form-label">&nbsp;</label>
               <button type="submit" class="btn btn-primary w-md">Submit</button>
            </div>
         </div>
       </div>
   </form> 
   <form action="{{route('KDPLWiseSetPercentage.store')}}" method="POST">
       <input type="hidden" name="job_status_id" value="{{$row->job_status_id}}" >
   @csrf 
   <div class="row">
       <table class="table" id="kdplmaster">
           <thead>
               <tr>
                   <th>Sr No.</th>
                   <th>Sales Order No.</th>
                   <th>Left Over Fabric Value</th>
                   <th>Left Over Trims Value</th>
                   <th>Left Pcs Value</th>
                   <th>Rejection Pcs Value</th> 
               </tr>
           </thead>
           <tbody>
               @php
                    $srno = 1;
               @endphp
               @foreach($SalesOrderList as  $row)
               <tr>
                   <td>{{$srno++}}</td>
                   <td><input type="text" name="sales_order_no[]" class="form-control" id="sales_order_no" value="{{$row->sales_order_no}}" readonly /></td>
                   <td><input type="text" name="leftover_fabric_value[]" class="form-control" id="leftover_fabric_value" value="{{$row->leftover_fabric_value ? $row->leftover_fabric_value : 50}}" /></td>
                   <td><input type="text" name="leftover_trims_value[]" class="form-control" id="leftover_trims_value" value="{{$row->leftover_trims_value ? $row->leftover_trims_value : 50}}" /></td>
                   <td><input type="text" name="left_pcs_value[]" class="form-control" id="left_pcs_value" value="{{$row->left_pcs_value ? $row->left_pcs_value : 70}}" /></td>
                   <td><input type="text" name="rejection_pcs_value[]" class="form-control" id="rejection_pcs_value" value="{{$row->rejection_pcs_value ? $row->rejection_pcs_value : 70}}" /></td> 
               </tr>
               @endforeach
           </tbody>
       </table>
   </div>
   <div class="row"> 
       <div class="col-md-6">
           <div class="mb-3">
                   <label for="formrow-email-input" class="form-label">&nbsp;</label>
                   <button type="submit" class="btn btn-primary w-md">Submit</button>
                    <a href="{{ Route('KDPLWiseSetPercentage.index') }}"  class="btn btn-warning w-md">Cancel</a>
           </div>
       </div>
   </div>
   </form>
   <!-- end card body -->
   </div>
   <!-- end card -->
   </div>
   <!-- end col -->
   <!-- end col -->
</div>
</div>
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    // $("#kdplmaster").on('click', '.aButton', function () 
    // {  
    //     var tr = $(this).parent().parent('tr');
    //     tr.find(".select2").each(function(index)
    //     {
    //         $(this).select2('destroy');
    //     });
    //     var clone = tr.clone();
    //     clone.find(':text').val('');
        
    //     tr.after(clone);
    //     $("select.select2").select2();
    // });

    // $("#kdplmaster").on('click', '.rButton', function () 
    // {  
    //     var tr = $(this).parent().parent('tr').remove(); 
    // });
</script>
<!-- end row -->
<!-- end row -->
<!-- end row -->
@endsection