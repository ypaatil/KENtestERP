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
<form action="{{Route('rptCuttingOCR1')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">
<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Sales Order no</label>
       <select name="sales_order_no" class="form-control select2" id="sales_order_no" required  onchange="GetCuttingPoList(this.value);" >
        <option value="">--Sales Order No--</option>
        @foreach($SalesOrderList as  $row)
        {
            <option value="{{ $row->sales_order_no }}">{{ $row->sales_order_no }}</option>
        }
        @endforeach
        </select>
    </div>
</div>


<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Cutting PO No.</label>
        <select name="vpo_code" class="form-control select2" id="vpo_code"   >
            <option value="">-- All --</option>
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
     
     function GetCuttingPoList(sales_order_no)
     {
         $.ajax({
        dataType: "json",
        url: "{{ route('CuttingPOList') }}",
        data:{'sales_order_no':sales_order_no},
        success: function(data){
        $("#vpo_code").html(data.html);
        }
        });
         
     }
     
     
 </script>
 
 
 
 
 
 
 
<!-- end row -->
@endsection