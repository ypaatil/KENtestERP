@extends('layouts.master') 
@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Sale Report</h4>
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
<form action="/SaleFilterReport" method="GET" enctype="multipart/form-data">
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
    
    
    
    
<div class="col-md-4">
    <div class="mb-3">
        <label for="po_date" class="form-label">Buyer Name</label>
         
       <select name="Ac_code" class="form-control select2" id="Ac_code"    >
<option value="">--Buyer--</option>
@foreach($LedgerList as  $row)
{
    <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
}
@endforeach
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
     
    //  function GetSalesOrderList(Ac_code)
    //  {
    //     $.ajax({
            
    //         dataType: "json",
    //         url: "{{ route('GetSalesOrderList') }}",
    //         data:{'Ac_code':Ac_code},
    //         success: function(data){
    //         $("#sales_order_no").html(data.html);
    //     }
    //     });
         
    //  }
     
     
 </script>
 
 
 
 
 
 
 
<!-- end row -->
@endsection