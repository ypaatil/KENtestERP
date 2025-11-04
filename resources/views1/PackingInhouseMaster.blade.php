@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Packing Inhouse</h4>
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
 
<form action="{{route('PackingInhouse.store')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="vpo_date" class="form-label">Entry Date</label>
        <input type="date" name="pki_date" class="form-control" id="pki_date" value="{{date('Y-m-d')}}" required  >
        @foreach($counter_number as  $row)
    <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
@endforeach
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
    </div>
</div>

 

<div class="col-md-2">
    <div class="mb-3">
        <label for="vpo_code" class="form-label">Process Order</label>
        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
       <select name="vpo_code" class="form-control select2" id="vpo_code"    onChange="getFinishingInhouseDetails(this.value);">
<option value="">--Process Order No--</option>
@foreach($VendorPurchaseOrderList as  $row)
{
    <option value="{{ $row->vpo_code }}">{{ $row->vpo_code }} ({{ $row->sales_order_no }})</option>
}
@endforeach
</select>
    </div>
</div>


<!-- <div class="col-md-2">-->
<!--<div class="mb-3">-->
<!--<label for="formrow-email-input" class="form-label">Sales order No</label>-->
<!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required readOnly>-->
<!--</div>-->
<!--</div>-->


<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Sales Order no</label>
       
       <select name="sales_order_no" class="form-control select2" id="sales_order_no" required  onChange="getSalesOrderTableOpening(this.value);">
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
<label for="formrow-email-input" class="form-label">Vendor Rate</label>
<input type="text" name="vendor_rate" class="form-control" id="vendor_rate" value="0" required readOnly>
</div>
</div>
 
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Buyer/Party</label>
<select name="Ac_code" class="form-control  " id="Ac_code" required   >
<option value="">--Select Buyer--</option>
@foreach($BuyerList as  $row)
{
    <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
   
  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" required>
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
    <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
}
@endforeach
</select>
</div>
</div>
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style Category</label>
<select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" required> 
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}"
   >{{ $row->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>    
     
    
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Style Name</label>
<select name="fg_id" class="form-control" id="fg_id" required>
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="" required readOnly>
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="" required readOnly>

</div>
</div>


<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-control" id="vendorId" required  >
<option value="">--Select Vendor--</option>
@foreach($Ledger as  $rowvendor)
{
    <option value="{{ $rowvendor->ac_code }}">{{ $rowvendor->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
  
  <div class="col-md-2">
  <div class="form-check form-check-primary mb-5">
    <input class="form-check-input" type="checkbox" id="is_opening" name="is_opening"  >
    <label class="form-check-label" for="is_opening">
    Opening Stock
</label>
</div>
</div> 

    <div class="col-md-3">
    <label for="location_id" class="form-label">Location/Warehouse</label>
    <select name="location_id" class="form-control select2  " id="location_id" required>
       <option value="">--Location--</option>
       @foreach($LocationList as  $row)
       {
       <option value="{{ $row->loc_id }}">{{ $row->location }}</option>
       }
       @endforeach
    </select>
 </div>
  
</div> 
  
   <div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Packing Entry</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in" style="width:100%;">
        <div class="panel-body">
            
       <div class="row">
   
        <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
        <div class="table-wrap">
        <div class="table-responsive">
        <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
         
        </table>
        </div>
        </div>
        </div>
        
        </div>
      
      
      </div>
    </div>

    
       
    </div>
  </div> 
</div>
</div>
 
 
 
 

 
 </br>
 
 </br>

 
<!-- end row -->
<div class="row">
     
     
<div class="col-md-2">
<div class="mb-3">
<label for="total_qty" class="form-label">Total Qty</label>
<input type="text" name="total_qty" class="form-control" id="total_qty" value="" required readOnly>
</div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="rate" class="form-label">Rate</label>
<input type="text" name="rate" class="form-control" id="rate" value="0" required onchange="mycalc();">
</div>
</div>

 
<div class="col-md-2">
<div class="mb-3">
<label for="vendor_amount" class="form-label">Total Amount</label>
<input type="text" name="vendor_amount" class="form-control" id="vendor_amount" value="" required readOnly>

</div>
</div>
     
<div class="col-sm-6">
<label for="formrow-inputState" class="form-label">Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="" />
</div>
</div>
  
</div>

<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" onclick="EnableFields();">Submit</button>
<a href="{{ Route('PackingInhouse.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<!-- end row -->
<script>




function getSalesOrderTableOpening(sales_order_no)
{

      $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('SalesOrderDetails') }}",
            data:{'sales_order_no':sales_order_no},
            success: function(data){
            
            $("#season_id").val(data[0]['season_id']);
            $("#Ac_code").val(data[0]['Ac_code']);
            $("#currency_id").val(data[0]['currency_id']);
            
            
            $("#mainstyle_id").val(data[0]['mainstyle_id']);
            $("#substyle_id").val(data[0]['substyle_id']);
            
            $("#style_no").val(data[0]['style_no']);
            $("#fg_id").val(data[0]['fg_id']);
            
            $("#style_description").val(data[0]['style_description']);
            $("#order_rate").val(data[0]['order_rate']);
            var prod_val=parseFloat(data[0]['production_value']);
            //alert(prod_val);
                 $("#vendorRate").attr({
                       "max" : prod_val,        
                       "min" : 0           
                    });
             
             
         
             document.getElementById('Ac_code').disabled=true;
         
             document.getElementById('mainstyle_id').disabled=true;
             document.getElementById('substyle_id').disabled=true;
             document.getElementById('fg_id').disabled=true;
         
            
        }
        });
        
         $("#footable_2").html('');
        
        
        $.ajax({
        dataType: "json",
        url: "{{ route('Op_GetOrderQty') }}",
        data:{'tr_code':sales_order_no},
        success: function(data){
        $("#footable_2").html(data.html);
        }
        });

     setTimeout(function(){
            mycalc(); 
        }, 2000);
}




// $(document).on("change", 'input[class^="size_id"]', function (event) 
// {
    
//     var value = $(this).val();
    
//              var maxLength = parseInt($(this).attr('max'));
//              var minLength = parseInt($(this).attr('min')); 
//     if(value>maxLength){alert('Value can not be greater than '+maxLength);}
//     if ((value !== '') && (value.indexOf('.') === -1)) {
         
         
         
//         $(this).val(Math.max(Math.min(value, maxLength), minLength));
//     }
    
   
// });




// $(document).on("change", 'input[class^="size_id"]', function (event) 
// {
//   var no=1;
//     var sales_order_no = $('#sales_order_no').val();
//     var sizes= $(this).closest("tr").find('input[name="size_array[]"]').val();
//     var size_array = sizes.split(',');
   
//       var values = [];
//       $("#footable_2 tr td  input[class='size_id']").each(function() {
//       values.push($(this).val());
//       if(values.length==size_array.length)
//       {
          
//         $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
//         // alert(values);
//             var sum = values.reduce(function( a,  b){
//                 return parseInt(a) + parseInt(b);
//             }, 0);
//         $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
         
//             values = [];
//       }
       
//     });
    
//             mycalc();
//   });

 
$(document).on("change", 'input[class^="size_id"]', function (event) 
{
    var no=1;
    var main = $(this);
    var sales_order_no = $('#sales_order_no').val();
    var sizes= $(this).closest("tr").find('input[name="size_array[]"]').val();
    var size_array = sizes.split(',');
      var values = [];
      $("#footable_2 tr td  input[class='size_id']").each(function() {
      values.push($(this).val());
      if(values.length==size_array.length)
      {
          
        $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
            var sum = values.reduce(function( a,  b){
                return parseInt(a) + parseInt(b);
            }, 0);
        $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
         
            qtyCheck($(this).closest("tr"),main);
            values = [];
      }
       
    });
  
    mycalc();
});

function qtyCheck(row,main)
{
    var totalQty = $(row).find("td input[name='overall_size_qty']").val();
    var size_qty_total = $(row).find("td input[name='size_qty_total[]']").val();
    var size_row =  $(row).find("td input[class='size_id']");
    console.log(totalQty);
    if(parseFloat(size_qty_total) > parseFloat(totalQty) &&  parseFloat(totalQty)>=0  )
    {
        alert("Total quantity can not be greater than "+totalQty);
       
        $(main).val(0);
        var values = [];
        var sizes= $(row).find('input[name="size_array[]"]').val();
        var size_array = sizes.split(',');
        $(size_row).each(function() 
        {
          values.push($(this).val());
          if(values.length==size_array.length)
          {
                $(row).find('input[name="size_qty_array[]"]').val(values);
                var sum = values.reduce(function( a,  b){
                    return parseInt(a) + parseInt(b);
                }, 0);
                $(row).find('input[name="size_qty_total[]"]').val(sum);
                values = [];
          }
        });
            
    }
}
  
  
function getFinishingInhouseDetails(vpo_code)
{

      $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('FinishingInhouseDetails') }}",
            data:{'vpo_code':vpo_code},
            success: function(data){
            
           
            $("#Ac_code").val(data[0]['Ac_code']);
            $("#vendorId").val(data[0]['vendorId']);
            $("#sales_order_no").val(data[0]['sales_order_no']);
            $("#mainstyle_id").val(data[0]['mainstyle_id']);
            $("#substyle_id").val(data[0]['substyle_id']);
            $("#style_no").val(data[0]['style_no']);
            $("#fg_id").val(data[0]['fg_id']);
            $("#style_description").val(data[0]['style_description']);
            $("#vendor_rate").val(data[0]['order_rate']);
             document.getElementById('Ac_code').disabled=true;
             document.getElementById('mainstyle_id').disabled=true;
             document.getElementById('substyle_id').disabled=true;
             document.getElementById('fg_id').disabled=true;
             document.getElementById('vendorId').disabled=true;
       
        }
        });

        $.ajax({
        dataType: "json",
        url: "{{ route('FNSI_GetOrderQty') }}",
        data:{'vpo_code':vpo_code},
        success: function(data){
        $("#footable_2").html(data.html);
        }
        });
 
}

function EnableFields()
{
            $("select").prop('disabled', false);
             
}



 
 $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
    
    mycalc();

});

 
 
//  $('#footable_2').on('change', '.item', function() 
//  {
//   var item_code = $(this).val();

//   var row = $(this).closest('tr'); // get the row
//     $.ajax({
//         type: "GET",
//         dataType:"json",
//         url: "{{ route('ItemDetails') }}",
//       data:{item_code:item_code},
//         success: function(data){

//              console.log(data); 
            
//                 row.find('select[name^="quality_code[]"]').val(data[0]['quality_code']);
//                 +row.find('input[name^="unit_id[]"]').attr('value', data[0]['unit_id']); 
//                 +row.find('input[name^="count_construction[]"]').attr('value', data[0]['item_description']);
             
//          }
//         });

// });


// $('table.footable_2').on('keyup', 'input[name^="s1[]"],input[name^="s2[]"],input[name^="s3[]"],input[name^="s4[]"],input[name^="s5[]"],input[name^="s6[]"],input[name^="s7[]"],input[name^="s8[]"],input[name^="s9[]"],input[name^="s10[]"],input[name^="s11[]"],input[name^="s12[]"],input[name^="s13[]"],input[name^="s14[]"],input[name^="s15[]"],input[name^="s16[]"],input[name^="s17[]"],input[name^="s18[]"],input[name^="s19[]"],input[name^="s20[]"]', function()
// {
//   // alert();
// CalculateQtyRowProxx($(this).closest("tr"));

// });
// function CalculateQtyRowProxx(row)
// {   
// if(row.find('input[name^="s1[]"]').val()){ var s1=row.find('input[name^="s1[]"]').val();}else{var s1=0;}
// if(row.find('input[name^="s2[]"]').val()){ var s2=row.find('input[name^="s2[]"]').val();}else{var s2=0;}
// if(row.find('input[name^="s3[]"]').val()){ var s3=row.find('input[name^="s3[]"]').val();}else{var s3=0;}
//  if(row.find('input[name^="s4[]"]').val()){var s4=row.find('input[name^="s4[]"]').val();}else{var s4=0;}
//  if(row.find('input[name^="s5[]"]').val()){var s5=row.find('input[name^="s5[]"]').val();}else{var s5=0;}
//  if(row.find('input[name^="s6[]"]').val()){var s6=row.find('input[name^="s6[]"]').val();}else{var s6=0;}
//  if(row.find('input[name^="s7[]"]').val()){var s7=row.find('input[name^="s7[]"]').val();}else{var s7=0;}
//  if(row.find('input[name^="s8[]"]').val()){var s8=row.find('input[name^="s8[]"]').val();}else{var s8=0;}
//  if(row.find('input[name^="s9[]"]').val()){var s9=row.find('input[name^="s9[]"]').val();}else{var s9=0;}
//  if(row.find('input[name^="s10[]"]').val()){var s10=row.find('input[name^="s10[]"]').val();}else{var s10=0;}
//  if(row.find('input[name^="s11[]"]').val()){var s11=row.find('input[name^="s11[]"]').val();}else{var s11=0;}
//  if(row.find('input[name^="s12[]"]').val()){var s12=row.find('input[name^="s12[]"]').val();}else{var s12=0;}
//  if(row.find('input[name^="s13[]"]').val()){var s13=row.find('input[name^="s13[]"]').val();}else{var s13=0;}
//  if(row.find('input[name^="s14[]"]').val()){var s14=row.find('input[name^="s14[]"]').val();}else{var s14=0;}
//  if(row.find('input[name^="s15[]"]').val()){var s15=row.find('input[name^="s15[]"]').val();}else{var s15=0;}
//  if(row.find('input[name^="s16[]"]').val()){var s16=row.find('input[name^="s16[]"]').val();}else{var s16=0;}
//  if(row.find('input[name^="s17[]"]').val()){var s17=row.find('input[name^="s17[]"]').val();}else{var s17=0;}
//  if(row.find('input[name^="s18[]"]').val()){var s18=row.find('input[name^="s18[]"]').val();}else{var s18=0;}
//  if(row.find('input[name^="s19[]"]').val()){var s19=row.find('input[name^="s19[]"]').val();}else{var s19=0;}
//  if(row.find('input[name^="s20[]"]').val()){var s20=row.find('input[name^="s20[]"]').val();}else{var s20=0;}
//  var total=parseInt(s1)+parseInt(s2)+parseInt(s3)+parseInt(s4)+parseInt(s5)+parseInt(s6)+parseInt(s7)+parseInt(s8)+parseInt(s9)+parseInt(s10)+parseInt(s11)+parseInt(s12)+parseInt(s13)+parseInt(s14)+parseInt(s15)+parseInt(s16)+parseInt(s17)+parseInt(s18)+parseInt(s19)+parseInt(s20);
//  row.find('input[name^="size_qty_total[]"]').val(total);
 
// }






$('table.footable_1').on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"]', function()
{
   // alert();
CalculateQtyRowPro($(this).closest("tr"));

});
function CalculateQtyRowPro(row)
{   
 var consumption=+row.find('input[name^="consumption[]"]').val();
 var wastage=+row.find('input[name^="wastage[]"]').val();
 var rate_per_unit=+row.find('input[name^="rate_per_unit[]"]').val();
 var bom_qty=+row.find('input[name^="bom_qty[]"]').val();

//var bom_qty1=(bom_qty + (bom_qty*(wastage/100))).toFixed(4);
 
var total_price=(bom_qty*rate_per_unit).toFixed(2);
//row.find('input[name^="bom_qty[]"]').val(bom_qty1);
row.find('input[name^="total_amount[]"]').val(total_price);
mycalc();
}

 

//  $('#footable_3').on('change', '.item_sewing_trims', function() 
//  {
//   var item_code = $(this).val();
    
//   var row2 = $(this).closest('tr'); // get the row
//     $.ajax({
//         type: "GET",
//         dataType:"json",
//         url: "{{ route('ItemDetails') }}",
//       data:{item_code:item_code},
//         success: function(data2){

//              console.log(data2); 
             
//                 +row2.find('input[name^="unit_ids[]"]').val(data2[0]['unit_id']);
//                  +row2.find('input[name^="descriptions[]"]').val(data2[0]['item_description']);
//               // row2.find('select[name^="descriptions[]"]').attr('value', data[0]['item_description']);
                
//          }
//         });

// });
// For Sewing Trims get Consumption Details From Sales Costing Table
$(document).on('change', 'select[name^="item_codes[]"],select[name^="color_ids[][]"],select[name^="size_ids[][]"]', function()
{CalculateQtyRowPros2($(this).closest("tr"));});
function CalculateQtyRowPros2(row)
{   
    var item_code=+row.find('select[name^="item_codes[]"]').val();
    var color_id=row.find('select[name^="color_ids[][]"]').val().join(",");
    var size_id=row.find('select[name^="size_ids[][]"]').val().join(",");
    row.find('input[name^="color_arrays[]"]').val(color_id);
    row.find('input[name^="size_arrays[]"]').val(size_id);
    var sales_order_no=$('#sales_order_no').val();
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('ItemWiseSalesOrderCosting') }}",
            data:{'item_code':item_code,'sales_order_no':sales_order_no,'color_id':color_id,'size_id':size_id},
            success: function(data)
            {
                    console.log(data);
                    row.find('input[name^="descriptions[]"]').val(data[0]['description']);
                    row.find('input[name^="consumptions[]"]').val(data[0]['consumption']);
                    row.find('input[name^="wastages[]"]').val(data[0]['wastage']);
                    row.find('input[name^="rate_per_units[]"]').val(data[0]['rate_per_unit']);
                   
                    row.find('select[name^="class_ids[]"]').val(data[0]['class_id']);
                    row.find('select[name^="unit_ids[]"]').val(data[0]['unit_id']);
                    var bom_qty=data[0]['bom_qty'];
                    
                //    var bom_qty_final= (bom_qty + (bom_qty*(wastage/100))).toFixed(4);
                   var total_amount=(bom_qty*data[0]['rate_per_unit']).toFixed(4)
                    row.find('input[name^="bom_qtys[]"]').val(bom_qty);
                    row.find('input[name^="total_amounts[]"]').val(total_amount);
            }
        });

        mycalc();

}

// For Fabric Trims get Consumption Details From Sales Costing Table
$('table.footable_1').on('change', 'select[name^="item_code[]"]', function()
{CalculateQtyRowPros1($(this).closest("tr"));});
function CalculateQtyRowPros1(row)
{   
    var item_code=+row.find('select[name^="item_code[]"]').val();
//alert(item_code);

    var sales_order_no=$('#sales_order_no').val();
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('FabricWiseSalesOrderCosting') }}",
            data:{'item_code':item_code,sales_order_no:sales_order_no},
            success: function(data)
            {
                    console.log(data);
                    row.find('input[name^="description[]"]').val(data[0]['description']);
                    row.find('input[name^="consumption[]"]').val(data[0]['consumption']);
                    row.find('input[name^="wastage[]"]').val(data[0]['wastage']);
                    row.find('input[name^="rate_per_unit[]"]').val(data[0]['rate_per_unit']);
                    row.find('input[name^="bom_qty[]"]').val(data[0]['bom_qty']);
                    row.find('input[name^="color_id[][]"]').val(data[0]['color_id']);
                    row.find('select[name^="class_id[]"]').val(data[0]['class_id']);
                    row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
                    row.find('input[name^="total_amount[]"]').val(data[0]['bom_qty']*data[0]['rate_per_unit']);
            }
        });

        mycalc();
}

// For Packing Trims get Consumption Details From Sales Costing Table
$(document).on('change', 'select[name^="item_codess[]"],select[name^="color_idss[][]"],select[name^="size_idss[][]"]', function()
{CalculateQtyRowPros3($(this).closest("tr"));});
function CalculateQtyRowPros3(row)
{   
    var item_code=+row.find('select[name^="item_codess[]"]').val();
    var sales_order_no=$('#sales_order_no').val();
    var color_id=row.find('select[name^="color_idss[][]"]').val().join(",");
    var size_id=row.find('select[name^="size_idss[][]"]').val().join(",");
    

    row.find('input[name^="color_arrayss[]"]').val(color_id);
    row.find('input[name^="size_arrayss[]"]').val(size_id);

   $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('PackingWiseSalesOrderCosting') }}",
            data:{'item_code':item_code,sales_order_no:sales_order_no,'color_id':color_id,'size_id':size_id},
            success: function(data)
            {
                    console.log(data);
                    row.find('input[name^="descriptionss[]"]').val(data[0]['description']);
                    row.find('input[name^="consumptionss[]"]').val(data[0]['consumption']);
                    row.find('input[name^="wastagess[]"]').val(data[0]['wastage']);
                    row.find('select[name^="class_idss[]"]').val(data[0]['class_id']);
                    row.find('select[name^="unit_idss[]"]').val(data[0]['unit_id']);
                    row.find('input[name^="rate_per_unitss[]"]').val(data[0]['rate_per_unit']);
                  // alert(data[0]['bom_qty']);
                    var bom_qty=parseFloat(data[0]['bom_qty']);
                    // var wastage=parseFloat(data[0]['wastage']);
                //    var bom_qty_final= (bom_qty + (bom_qty*(wastage/100))).toFixed(4);
                   var rate=data[0]['rate_per_unit'];
                   var total_amount=(bom_qty*rate).toFixed(4);
                    row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
                    row.find('input[name^="total_amountss[]"]').val(total_amount);
            }
        });

        mycalc();

}
// $('#footable_4').on('change', '.item_packing_trims', function() 
//  {
//   var item_code = $(this).val();
  
//   var row1 = $(this).closest('tr'); // get the row
//     $.ajax({
//         type: "GET",
//         dataType:"json",
//         url: "{{ route('ItemDetails') }}",
//       data:{item_code:item_code},
//         success: function(data1){

//              console.log(data1); 
             
//                 +row1.find('input[name^="unit_idss[]"]').val(data1[0]['unit_id']);
//                  +row1.find('input[name^="descriptionss[]"]').val(data1[0]['item_description']);
//               // row1.find('select[name^="descriptionss[]"]').attr('value', data[0]['item_description']);
               
//          }
//         });

// });
 
  
 $('table.footable_4').on("keyup", 'input[name^="consumptionss[]"],input[name^="wastagess[]"],input[name^="rate_per_unitss[]"],input[name^="bom_qtyss[]"]', function()
{
   // alert();
CalculateQtyRowPross($(this).closest("tr"));

});
function CalculateQtyRowPross(row)
{   
 
 var consumption=+row.find('input[name^="consumptionss[]"]').val();
 var wastage=+row.find('input[name^="wastagess[]"]').val();
 var rate_per_unit=+row.find('input[name^="rate_per_unitss[]"]').val();
 var bom_qty=+row.find('input[name^="bom_qtyss[]"]').val();
  
//  row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
 var total_price=(bom_qty*rate_per_unit).toFixed(2);
//  row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
 
 row.find('input[name^="total_amountss[]"]').val(total_price);
 mycalc();
}

 
 
var indexcone = 2;
function insertcone1(){

var table=document.getElementById("footable_1").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;

cell1.appendChild(t1);
  
var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#item_code"),
y = x.clone();
y.attr("id","item_code");
y.attr("name","item_code[]");
y.width(200);
y.appendTo(cell3);
   
var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#class_id"),
y = x.clone();
y.attr("id","class_id");
y.attr("name","class_id[]");
y.width(200);
y.appendTo(cell3);

var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:200px; height:30px";
t5.type="text";
t5.id = "description"+indexcone;
t5.name="description[]";
cell5.appendChild(t5); 
 
var cell5 = row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "consumption"+indexcone;
t5.name="consumption[]";
cell5.appendChild(t5);  

var cell3 = row.insertCell(5);
var t3=document.createElement("select");
var x = $("#unit_id"),
y = x.clone();
y.attr("id","unit_id");
y.attr("name","unit_id[]");
y.width(100);
y.appendTo(cell3);

var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "rate_per_unit"+indexcone;
t5.name="rate_per_unit[]";
cell5.appendChild(t5);

var cell5 = row.insertCell(7);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "wastage"+indexcone;
t5.name="wastage[]";
cell5.appendChild(t5);

var cell5 = row.insertCell(8);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "bom_qty"+indexcone;
t5.name="bom_qty[]";
cell5.appendChild(t5);
 

var cell5 = row.insertCell(9);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.className="FABRIC";
t5.readOnly=true;
t5.id = "total_amount"+indexcone;
t5.name="total_amount[]";
cell5.appendChild(t5); 
 
 
var cell6=row.insertCell(10);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone1()");
cell6.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone1(this)");
cell6.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_1').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr1').value = parseInt(document.getElementById('cntrr1').value)+1;

indexcone++;
recalcIdcone1();
}

// Start Sewing Trims----------------------------
var indexcone1 = 2;
function insertcone2(){

var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "ids"+indexcone1;
t1.name= "ids[]";
t1.value=indexcone1;

cell1.appendChild(t1);
  
var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#item_codes"),
y = x.clone();
y.attr("id","item_codes");
y.attr("name","item_codes[]");
y.width(200);
y.appendTo(cell3);
  
var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#class_ids"),
y = x.clone();
y.attr("id","class_ids");
y.attr("name","class_ids[]");
y.width(200);
y.appendTo(cell3);


var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:200px; height:30px";
t5.type="text";
t5.id = "descriptions"+indexcone1;
t5.name="descriptions[]";
cell5.appendChild(t5); 



var cell3 = row.insertCell(4);
var t3=document.createElement("select");
var x = $("#color_ids"),
y = x.clone();
y.attr("id","color_ids");
y.attr("name","color_ids[][]");
y.width(200);
y.appendTo(cell3); 


var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "color_arrays"+indexcone2;
t5.name="color_arrays[]";
cell3.appendChild(t5); 

var cell3 = row.insertCell(5);
var t3=document.createElement("select");
var x = $("#size_ids"),
y = x.clone();
y.attr("id","size_ids");
y.attr("name","size_ids[][]");
y.width(200);
y.appendTo(cell3); 

var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "size_arrays"+indexcone2;
t5.name="size_arrays[]";
cell3.appendChild(t5); 

  
var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "consumptions"+indexcone1;
t5.name="consumptions[]";
cell5.appendChild(t5);  
 
var cell3 = row.insertCell(7);
var t3=document.createElement("select");
var x = $("#unit_ids"),
y = x.clone();
y.attr("id","unit_ids");
y.attr("name","unit_ids[]");
y.width(100);
y.appendTo(cell3);

var cell5 = row.insertCell(8);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "rate_per_units"+indexcone1;
t5.name="rate_per_units[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(9);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "wastages"+indexcone1;
t5.name="wastages[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(10);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "bom_qtys"+indexcone1;
t5.name="bom_qtys[]";
cell5.appendChild(t5);
  
var cell5 = row.insertCell(11);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.className="SEWING";
t5.readOnly=true;
t5.id = "total_amounts"+indexcone1;
t5.name="total_amounts[]";
cell5.appendChild(t5); 

 
 
var cell6=row.insertCell(12);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone2()");
cell6.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
cell6.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone1);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr2').value = parseInt(document.getElementById('cntrr2').value)+1;

indexcone1++;
recalcIdcone2();
}


// Start Packing Trims----------------------------
var indexcone2 = 2;
function insertcone3(){

var table=document.getElementById("footable_4").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "idss"+indexcone2;
t1.name= "idss[]";
t1.value=indexcone2;

cell1.appendChild(t1);
  
var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#item_codess"),
y = x.clone();
y.attr("id","item_codess");
y.attr("name","item_codess[]");
y.width(200);
y.appendTo(cell3);
  
var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#class_idss"),
y = x.clone();
y.attr("id","class_idss");
y.attr("name","class_idss[]");
y.width(200);
y.appendTo(cell3);


var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:200px; height:30px";
t5.type="text";
t5.id = "descriptionss"+indexcone2;
t5.name="descriptionss[]";
cell5.appendChild(t5); 


var cell3 = row.insertCell(4);
var t3=document.createElement("select");
var x = $("#color_idss"),
y = x.clone();
y.attr("id","color_idss");
y.attr("name","color_idss[][]");
y.width(200);
y.appendTo(cell3);  
 
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "color_arrayss"+indexcone2;
t5.name="color_arrayss[]";
cell3.appendChild(t5); 


var cell3 = row.insertCell(5);
var t3=document.createElement("select");
var x = $("#size_idss"),
y = x.clone();
y.attr("id","size_idss");
y.attr("name","size_idss[][]");
y.width(200);
y.appendTo(cell3); 

var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "size_arrayss"+indexcone2;
t5.name="size_arrayss[]";
cell3.appendChild(t5);
 
  
var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "consumptionss"+indexcone2;
t5.name="consumptionss[]";
cell5.appendChild(t5);  
 
var cell3 = row.insertCell(7);
var t3=document.createElement("select");
var x = $("#unit_idss"),
y = x.clone();
y.attr("id","unit_idss");
y.attr("name","unit_idss[]");
y.width(100);
y.appendTo(cell3);

var cell5 = row.insertCell(8);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "rate_per_unitss"+indexcone2;
t5.name="rate_per_unitss[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(9);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "wastagess"+indexcone2;
t5.name="wastagess[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(10);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "bom_qtyss"+indexcone2;
t5.name="bom_qtyss[]";
cell5.appendChild(t5);
  
var cell5 = row.insertCell(11);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.className="PACKING";
t5.readOnly=true;
t5.id = "total_amountss"+indexcone2;
t5.name="total_amountss[]";
cell5.appendChild(t5); 
 
 
var cell6=row.insertCell(12);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone3()");
cell6.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone3(this)");
cell6.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_4').find('tr').eq(indexcone2);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr3').value = parseInt(document.getElementById('cntrr3').value)+1;

indexcone2++;
recalcIdcone3();
}







function mycalc()
{   

sum1 = 0.0;
var amounts = document.getElementsByClassName('size_qty_total');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_qty").value = sum1;

 
  
 var vendor_rate=$("#vendor_rate").val();
 var rate=$("#rate").val();
 
 
 if(rate==0 || rate=='')
 {
    var vendor_amount=(parseFloat(vendor_rate)*parseInt(sum1)).toFixed(2);
 }
 else
 {
     var vendor_amount=(parseFloat(rate)*parseInt(sum1)).toFixed(2);
 }
 $("#vendor_amount").val(vendor_amount);
 
 
 
 
}


function calculateamount()
{
    
    
var prod_qty=document.getElementById('prod_qty').value;
var rate_per_piece=document.getElementById('rate_per_piece').value;


var total_amount= parseFloat(parseFloat(prod_qty) * parseFloat(rate_per_piece));
$('#total_amount').val(total_amount.toFixed(2));
}






function deleteRowcone1(btn) {
if(document.getElementById('cntrr1').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr1').value = document.getElementById('cntrr1').value-1;

recalcIdcone1();

if($("#cntrr1").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}


function deleteRowcone2(btn) {
if(document.getElementById('cntrr2').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr2').value = document.getElementById('cntrr2').value-1;

recalcIdcone2();

if($("#cntrr2").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}

function deleteRowcone3(btn) {
if(document.getElementById('cntrr3').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr3').value = document.getElementById('cntrr3').value-1;

recalcIdcone3();

if($("#cntrr3").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}


function recalcIdcone1(){
$.each($("#footable_2 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}

function recalcIdcone2(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}

function recalcIdcone3(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}


 function getSubStyle(val) 
{	//alert(val);
    $.ajax({
    type: "GET",
    url: "{{ route('SubStyleList') }}",
    data:'mainstyle_id='+val,
    success: function(data){
    $("#substyle_id").html(data.html);
    }
    });
}   
     
  function getStyle(val) 
{	//alert(val);

   $.ajax({
    type: "GET",
    url: "{{ route('StyleList') }}",
    data:{'substyle_id':val, },
    success: function(data){
    $("#fg_id").html(data.html);
    }
    });
}  


</script>

<!-- end row -->
@endsection