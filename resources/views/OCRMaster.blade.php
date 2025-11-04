@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">OCR Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">OCR Master</li>
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
   <h4 class="card-title mb-4">OCR Master</h4>
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
   <form action="{{route('OCR.store')}}" method="POST"  enctype="multipart/form-data"> 
   <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control"  >
   @csrf 
   <div class="row">
      <div class="row">
            <div class="col-md-2">
             <div class="mb-3">
                    <label for="sales_order_no" class="form-label">Sales Order No</label>
                    <select name="sales_order_no" class="form-control select2" id="sales_order_no" >
                       <option value="">--Select--</option>
                       @foreach($SalesOrderList as  $row)
                       <option value="{{ $row->tr_code }}">{{ $row->tr_code }}</option>
                       @endforeach
                    </select>
                </div>
            </div> 
       </div>
       <table class="table" id="ocrmaster">
           <thead>
               <tr>
                   <th nowrap>Sr No.</th>
                   <th class="text-center">Date</th>
                   <th>Transport Qty</th>
                   <th>Transport Image</th>
                   <th>Testing Qty</th> 
                   <th>Testing Image</th> 
                   <th>Action</th> 
               </tr>
           </thead>
           <tbody> 
               <tr>
                    <td>1</td>
                    <td><input type="date" name="ocr_date[]" class="form-control" id="ocr_date" value="{{date('Y-m-d')}}" /></td>
                    <td><input type="text" name="transport_qty[]" class="form-control" id="transport_qty" value="0" onkeyup="calTransQty()" /></td>
                    <td><input type="file" name="transport_image[]" class="form-control" id="transport_image" value="" /></td>
                    <td><input type="text" name="testing_qty[]" class="form-control" id="testing_qty" value="0"  onkeyup="calTransQty()" /></td>
                    <td><input type="file" name="testing_image[]" class="form-control" id="testing_image" value="" /></td>
                    <td nowrap>
                        <input type="button" class="btn btn-warning pull-left" value="+" onclick="addNewRow(this);" >
                        <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" >
                    </td> 
               </tr> 
           </tbody>
       </table> 
   </div>
   <div class="col-md-12"> 
       <div class="row"> 
           <div class="col-md-2">
               <div class="mb-3">
                 <label for="total_transport_qty" class="form-label">Total Transport Qty</label> 
                 <input type="text" class="form-control" id="total_transport_qty" value="0" readonly />
               </div>
           </div>
           <div class="col-md-2">
               <div class="mb-3">
                 <label for="total_testing_qty" class="form-label">Total Testing Qty</label> 
                 <input type="text" class="form-control" id="total_testing_qty" value="0" readonly />
               </div>
           </div>
       </div>
   </div>
   <div class="row"> 
       <div class="col-md-6">
           <div class="mb-3">
                   <label for="formrow-email-input" class="form-label">&nbsp;</label>
                   <button type="submit" class="btn btn-primary w-md">Submit</button>
                    <a href="{{ Route('OCR.index') }}"  class="btn btn-warning w-md">Cancel</a>
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
    
    function calTransQty()
    {
        var total_trans = 0;
        var total_testing = 0;
        $('input[name="transport_qty[]"]').each(function(){
            total_trans += parseFloat($(this).val());
        });
        $("#total_transport_qty").val(total_trans);
        
        $('input[name="testing_qty[]"]').each(function()
        {
            total_testing += parseFloat($(this).val());
        });
        $("#total_testing_qty").val(total_testing);
    }
    
    function addNewRow(row)
    { 
        var tr = $(row).closest('tr');
        var clone = tr.clone();
        console.log(clone); 
        $('tbody').append(clone);
        recalcIdcone();
        calTransQty();
    }
     
   function recalcIdcone()
   {
       $.each($("#ocrmaster tr"),function (i,el)
       {
            $(this).find("td:first").text(i); 
       })
   }
   
   function deleteRowcone(row)
   {
       var tr = $(row).parent().parent('tr').remove(); 
       calTransQty();
   }
   
</script>
<!-- end row -->
<!-- end row -->
<!-- end row -->
@endsection