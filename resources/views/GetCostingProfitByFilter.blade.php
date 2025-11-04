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
<form action="/costingProfitSheet3" method="GET" enctype="multipart/form-data">
@csrf 
<div class="row">
    
    
    
    <div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Year</label>
<select name="fin_year_id" class="form-select select2" id="fin_year_id"   required>
<option value="">--Year--</option>
@foreach($FinYearList as  $row)
{
<option value="{{ $row->fdate }}">{{ $row->fin_year_name }}</option>

}
@endforeach
</select>
</div>
</div>
    
    
<div class="col-md-4">
    <div class="mb-3">
        <label for="po_date" class="form-label">Buyer Name</label>
         
       <select name="Ac_code" class="form-control select2" id="Ac_code"  onchange="getBrandList(this.value);" >
<option value="">--Buyer--</option>
@foreach($BuyerList as  $row)
{
    <option value="{{ $row->Ac_code }}">{{ $row->Ac_name }}</option>
}
@endforeach
</select>
    </div>
</div>

 
  
 <div class="col-md-2">
<div class="mb-3">
<label for="brand_id" class="form-label">Buyer Brand</label>
<select name="brand_id" class="form-select" id="brand_id" required>
<option value="">--Brands--</option>

</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-select select2" id="mainstyle_id"   required>
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
<option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>

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
     
     
     
      function getBrandList(val) 
{	//alert(val);

   $.ajax({
    type: "GET",
    url: "{{ route('BrandList') }}",
    data:{'Ac_code':val, },
    success: function(data){
    $("#brand_id").html(data.html);
    }
    });
} 
     
 </script>
 
 
 
 
 
 
 
<!-- end row -->
@endsection