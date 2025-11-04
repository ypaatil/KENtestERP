@extends('layouts.master') 
@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">OCR Report</h4>
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
<form action="/TrimsGRNReportPrint" method="GET" enctype="multipart/form-data">
@csrf 
<div class="row">
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">From date</label>
<input type="date" name="fdate" class="form-control" id="fdate" value="" required>

</div>
</div>
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">To Date</label>
        <input type="date" name="tdate" class="form-control" id="tdate" value="{{date('Y-m-d')}}" required>
    </div>
</div>
    
    
    
    
<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Supplier Name</label>
        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
       <select name="Ac_code" class="form-control select2" id="Ac_code"    onchange="GetPOList(this.value);" >
<option value="">--Supplier--</option>
@foreach($LedgerList as  $row)
{
    <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
}
@endforeach
</select>
    </div>
</div>


<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">PO NO.</label>
    
       <select name="pur_code" class="form-control select2" id="pur_code"    >
        <option value="">-- PO NO. --</option>
        </select>
    </div>
</div>



 
 

</div>

 

<div class="col-sm-2">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</div>

</div>

</form>
 


</div>
<!-- end card body -->
</div>
<!-- end card -->
</div>
<!-- end col -->


<!-- end col -->
</div>
<!-- end row -->

             
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
 
 
 <script>
     
     function GetPOList(Ac_code)
     {
         $.ajax({
        dataType: "json",
        url: "{{ route('GetPOList') }}",
        data:{'Ac_code':Ac_code},
        success: function(data){
        $("#pur_code").html(data.html);
        }
        });
         
     }
     
     
 </script>
 
 
 
 
 
 
 
<!-- end row -->
@endsection