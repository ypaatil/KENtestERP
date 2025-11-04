@extends('layouts.master') 

@section('content')
 

 
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">

<h4 class="card-title mb-4">Sales Order</h4>

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
 
<form action="{{route('BuyerPurchaseOrder.store')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">
  
<div class="col-md-2">
    <div class="mb-3">
        <label for="po_code" class="form-label">Buyer PO Code</label>
        <input type="text" name="po_code" class="form-control" id="po_code" value="" required>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="tr_date" class="form-label">Entry Date</label>
        <input type="date" name="tr_date" class="form-control" id="tr_date" value="{{date('Y-m-d')}}" required readOnly>
@foreach($counter_number as  $row)
     <!--<input type="hidden" name="tr_code" class="form-control" id="tr_code" value="{{ 'KDPL'.'-'.$row->tr_no }}">-->
    <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
@endforeach
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
@php
 session()->put('BuyerPurchase','1');
@endphp    
    </div>
</div>  
  
<div class="col-md-2">
<div class="mb-3">
<label for="og_id" class="form-label">Order Group</label>
<select name="og_id" class="form-select" id="og_id" required>
<option value="">--Order Group--</option>
@foreach($OrderGroupList as  $row)
{
    <option value="{{ $row->og_id }}">{{ $row->order_group_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="Ac_code" class="form-label">Buyer/Party</label>
<select name="Ac_code" class="form-select select2" id="Ac_code" required onchange="getSeasonList(this.value); getBrandList(this.value);">
<option value="">--Select Buyer--</option>
@foreach($Ledger as  $row)
{
    <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
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
@foreach($BrandList as  $row)
{
    <option value="{{ $row->brand_id }}">{{ $row->brand_name }}</option>
}
@endforeach
</select>
</div>
</div> 
  
 <div class="col-md-2">
<div class="mb-3">
<label for="season_id" class="form-label">Season</label>
<select name="season_id" class="form-select" id="season_id" required>
<option value="">--Season--</option>
@foreach($SeasonList as  $row)
{
    <option value="{{ $row->season_id }}">{{ $row->season_name }}</option>
}
@endforeach
</select>
</div>
</div> 
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="order_received_date" class="form-label">Received Date</label>
        <input type="date" name="order_received_date" class="form-control" id="order_received_date" value="{{date('Y-m-d')}}" required>
    </div>
</div> 

<!--<div class="col-md-2">-->
<!--    <div class="mb-3">-->
<!--        <label for="buyer_delivery_date" class="form-label">Delivery Date</label>-->
<!--        <input type="date" name="buyer_delivery_date" class="form-control" id="buyer_delivery_date" value="{{date('Y-m-d')}}" required>-->
<!--    </div>-->
<!--</div> -->
 
 
  <div class="col-md-2">
<div class="mb-3">
<label for="currency_id" class="form-label">Order currency</label>
<select name="currency_id" class="form-select" id="currency_id" required>
<option value="">--Currency--</option>
@foreach($CurrencyList as  $row)
{
    <option value="{{ $row->cur_id }}">{{ $row->currency_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
<div class="col-md-2">
    <div class="mb-3">
        <label for="order_rate" class="form-label">Order Rate</label>
        <input type="number" step="0.01" name="order_rate" class="form-control" id="order_rate" value="" required>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="order_value" class="form-label">Order Value</label>
        <input type="text" name="order_value" class="form-control" id="order_value" value="" required readOnly>
    </div>
</div>
  
 <div class="col-md-2">
    <div class="mb-3">
        <label for="total_qty" class="form-label">Total Qty</label>
        <input type="number" step="0.01"  name="total_qty" class="form-control" id="total_qty" value="0" readOnly required>
    </div>
</div> 
  
  
</div>
<div class="row">
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-select" id="mainstyle_id"  onchange="getSubStyle(this.value)" required>
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
<select name="substyle_id" class="form-select" id="substyle_id" onchange="getStyle(this.value)" required> 
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}">{{ $row->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>    
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Style Name</label>
<select name="fg_id" class="form-select" id="fg_id" required>
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}">{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

<div class="col-md-2">
<div class="mb-3">
<label for="style_no" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="" required>

</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value=""  >

</div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="style_pic_path" class="form-label">Style Image</label>
<input type="file" name="style_pic_path" class="form-control" id="style_pic_path" value="" >

</div>
</div>




<div class="col-md-2">
<div class="mb-3">
<label for="ptm_id" class="form-label">Payment Terms</label>
<select name="ptm_id" class="form-select" id="ptm_id" required>
<option value="">--Payment Terms--</option>
@foreach($PaymentTermsList as  $row)
{
    <option value="{{ $row->ptm_id }}">{{ $row->ptm_name }}</option>
}
@endforeach
</select>
</div>
</div>



<div class="col-md-2">
<div class="mb-3">
<label for="dterm_id" class="form-label">Delivery Terms</label>
<select name="dterm_id" class="form-select" id="dterm_id" required>
<option value="">--Delivery Terms--</option>
@foreach($DeliveryTermsList as  $row)
{
    <option value="{{ $row->dterm_id }}">{{ $row->delivery_term_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="ship_id" class="form-label">Shipment Mode</label>
<select name="ship_id" class="form-select" id="ship_id" required>
<option value="">--Shipment--</option>
@foreach($ShipmentList as  $row)
{
    <option value="{{ $row->ship_id }}">{{ $row->ship_mode_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="country_id" class="form-label">Country</label>
<select name="country_id" class="form-select" id="country_id" required>
<option value="">--Country--</option>
@foreach($CountryList as  $row)
{
    <option value="{{ $row->c_id }}">{{ $row->c_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="warehouse_id" class="form-label">Destination</label>
<select name="warehouse_id" class="form-select" id="warehouse_id" required>
<option value="">--Destination--</option>
@foreach($WarehouseList as  $row)
{
    <option value="{{ $row->warehouse_id }}">{{ $row->warehouse_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="order_received_date" class="form-label">Shipment Date</label>
        <input type="date" name="shipment_date" class="form-control" id="shipment_date" value="{{date('Y-m-d')}}" required>
    </div>
</div> 

<div class="col-md-2">
    <div class="mb-3">
        <label for="plan_cut_date" class="form-label">Plan Cut Date</label>
        <input type="date" name="plan_cut_date" class="form-control" id="plan_cut_date" value="{{date('Y-m-d')}}" required>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="inspection_date" class="form-label">Inspection Date</label>
        <input type="date" name="inspection_date" class="form-control" id="inspection_date" value="{{date('Y-m-d')}}" required>
    </div>
</div> 

<div class="col-md-2">
    <div class="mb-3">
        <label for="ex_factory_date" class="form-label">Ex Factory Date</label>
        <input type="date" name="ex_factory_date" class="form-control" id="ex_factory_date" value="{{date('Y-m-d')}}" required>
    </div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="doc_path1" class="form-label">PO Document</label>
<input type="file" name="doc_path1" class="form-control" id="doc_path1" value="">

</div>
</div>

 <div class="col-md-2">
<div class="mb-3">
<label for="sz_code" class="form-label">Select Size Group</label>
<select name="sz_code" class="form-select select2"   id="sz_code" required onChange="GetSizeDetailList(this.value);">
<option value="">--Size--</option>
@foreach($SizeList as  $row)
{
    <option value="{{ $row->sz_code }}">{{ $row->sz_name }}</option>
}
@endforeach
</select>
</div>
</div>  
 
</div>

<div class="row">
     
<input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<div class="table-wrap" id="divSelect">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
<thead>
<tr>
<th>SrNo</th>
            <th>Item</th>
            <th>Color</th>
            <th>Size</th>
            <th>Qty</th>
            <th>  <i class="fas fa-trash"></i> </th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="text" name="id[]" value="1" id="id0" style="width:50px;"/></td>
<td> <select name="item_code[]" class="select2-select"  id="item_code0" style="width:250px;" required>
<option value="">--Item--</option>
@foreach($ItemList as  $row)
{
    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
}
@endforeach
</select></td>
<td> <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px;" required>
<option value="">--Color--</option>
@foreach($ColorList as  $row)
{
    <option value="{{ $row->color_id }}">{{ $row->color_name }}</option>
}
@endforeach
</select></td>

<td> <select name="sz_code[]" class="select2-select" id="sz_code0" style="width:150px;" required>
<option value="">--Size--</option>
@foreach($SizeList as  $row)
{
    <option value="{{ $row->sz_code }}">{{ $row->sz_name }}</option>
}
@endforeach
</select></td>

<td><input type="number" name="qty[]"   id="qty0" class="QTY"  value="0" style="width:80px;" required/> 
  <input name="unit_id[]"  id="unit_id0" value="3"  style="width:100px;"  type="hidden" required>
 </td>

<td><input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" class="Abutton btn btn-warning pull-left">
<input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>

 </tbody>
 
</table>
</div>
</div>


</div>

<div class="row">
   
<div class="col-md-2">
    <div class="mb-3">
        <label for="shipped_qty" class="form-label">Shipped Qty</label>
        <input type="number" name="shipped_qty" class="form-control" id="shipped_qty" value="0" required onkeyup="calculate();">
    </div>
</div>


<div class="col-md-2">
    <div class="mb-3">
        <label for="balance_qty" class="form-label">Balance Qty</label>
        <input type="number" name="balance_qty" class="form-control" id="balance_qty" value="0" required readOnly>
    </div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="job_status_id" class="form-label">PO Status</label>
<select name="job_status_id" class="form-select" id="job_status_id" required>
<option value="">--PO Status--</option>
@foreach($JobStatusList as  $row)
{
    <option value="{{ $row->job_status_id }}">{{ $row->job_status_name }}</option>
}
@endforeach
</select>
</div>
</div>


 
<div class="col-sm-6">
<label for="formrow-inputState" class="form-label">Order Remark / Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="" />
</div>
</div>
 
</div>

<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" id="Submit" disabled>Submit</button>
<a href="{{ Route('BuyerPurchaseOrder.index') }}" class="btn btn-warning w-md">Cancel</a>
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
//  $(document).on('change','input[name^="item_code[]"]', function(event) {  
    
//     CalculateRowGST($(this).closest("tr"));

// });


//     function CalculateRowGST(row)
// 	{ 
//         var tax_type_id=document.getElementById('tax_type_id');
//         var item_code=+row.find('input[name^="item_code[]"]').val();
//         $.ajax({
//         type: "GET",
//         url: "{{ route('TaxList') }}",
//         data:'item_code='+item_code,
//         success: function(data){

//             row.find('input[name^="cgst_per[]"]').val(data['cgst_per']);
//             row.find('input[name^="sgst_per[]"]').val(data['sgst_per']);

//             row.find('input[name^="igst_per[]"]').val(data['igst_per']);

//         }
//         });

//     }



function calculate()
 {
    
     var shipped_qty=$('#shipped_qty').val();
     var order_qty=$('#total_qty').val();
     var balance_qty=order_qty-shipped_qty;
     $('#balance_qty').val(balance_qty);
     
     
 }

$(document).on("keyup", 'input[name^="size_id[]"]', function (event) 
{
     var sizes= $(this).closest("tr").find('input[name="size_array[]"]').val();
     var size_array = sizes.split(',');
      var values = [];
      $("#footable_3 tr td  input[name='size_id[]']").each(function() {
      values.push($(this).val());
      if(values.length==size_array.length)
      {
          
        $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
        
        var sum = values.reduce(function( a,  b){
                return parseInt(a) + parseInt(b);
            }, 0);
        $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
        
            values = [];
      }
    });
    
    
         mycalc();
  calculate();
     
   });
    
  
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


 function getSeasonList(val) 
{	//alert(val);

   $.ajax({
    type: "GET",
    url: "{{ route('SeasonList') }}",
    data:{'Ac_code':val, },
    success: function(data){
    $("#season_id").html(data.html);
    }
    });
}  

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

function GetSizeDetailList(str)
{
    $.ajax({
        dataType: "json",
    url: "{{ route('SizeDetailList') }}",
    data:{'sz_code':str},
    success: function(data){
    $("#footable_3").html(data.html);
    }
    });
}



function getAddress(site_code)
{ 

    var Ac_code=document.getElementById('Ac_code').value;
$.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('GetAddress') }}",
            //data:'table_id='+table_id,
            data:{Ac_code:Ac_code,site_code:site_code},
            success: function(response){
                
            console.log(response);     
            
            $("#DeliveryAddress").val(response[0]['consignee_address']);
           
        }
        });
    }


 $('#footable_3').on('change', '.item', function() 
 {
 
    var tax_type_id=document.getElementById('tax_type_id').value;
    var item_code = $(this).val();
    var row = $(this).closest('tr'); // get the row
    
    $.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('TaxList') }}",
        data:'item_code='+item_code,
        success: function(data){
            if(tax_type_id==1)
            {
                        row.find('input[name^="cgst_per[]"]').val(data[0]['cgst_per']);
                        row.find('input[name^="sgst_per[]"]').val(data[0]['sgst_per']);
                        row.find('input[name^="igst_per[]"]').val();
            }
            else
            {
                row.find('input[name^="igst_per[]"]').val(data[0]['igst_per']);
                row.find('input[name^="cgst_per[]"]').val(0);
                row.find('input[name^="sgst_per[]"]').val(0);
            }
      
        }
        });

});




$(document).on('click', '.Abutton', function () {
    var $tr = $(this).closest('tr');
    var $lastTr = $tr.closest('table').find('tr:last');

    $lastTr.find('.select2-select').select2('destroy');

    var $clone = $lastTr.clone();

    $clone.find('td').each(function() {
        var el = $(this).find(':first-child');
        var id = el.attr('id') || null;
        if (id) {
            var i = id.substr(id.length - 1);
            var prefix = id.substr(0, (id.length - 1));
            el.attr('id', prefix + (+i + 1));
        }
    });
    $tr.closest('tbody').append($clone);
        $lastTr.find('.select2-select').select2();
    $clone.find('.select2-select').select2();
       
    document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

recalcIdcone();
mycalc();
    
});
 
 


//  function addrow()
// {
//     var row = $("#footable_3 tr:last");

//     row.find(".color").each(function(index)
//     {
//         $(this).select2('destroy');
//     }); 
    
//     row.find(".Item").each(function(index)
//     {
//         $(this).select2('destroy');
//     }); 

//     row.find(".unit").each(function(index)
//     {
//         $(this).select2('destroy');
//     }); 


//  row.find(".size").each(function(index)
//     {
//         $(this).select2('destroy');
//     }); 

//   var newrow = row.clone();   
    
//  $('#footable_3 tbody tr:last').find('select[name^="unit_id[]"]').each(function() {
//           $('select.select2').select2();
//     });
//     // $('#footable_3 tbody tr:last').find('select[name^="item_code[]"]').each(function() {
//     //     newrow.find('select[name^="item_code[]"]').val(this.value);
//     // });

//  $('#footable_3 tbody tr:last').find('select[name^="unit_id[]"]').each(function() {
//         newrow.find('select[name^="unit_id[]"]').val(this.value);
//     });
    
   
    
//     $("#footable_3").append(newrow);
    
     
//      $('.select2').select2();
    
    
     
//       document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
//      recalcIdcone();
    
//     mycalc();
//     calculate();
      
 
 
      
// }
 





$(document).on("click", 'input[name^="Abutton[]"]', function (event) {
    
        insertcone($(this).closest("tr"));
        
    });
  
var indexcone = 2;
function insertcone(Abutton){
    var rowsx=$(Abutton).closest("tr");

var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;
cell1.appendChild(t1);
 

var cell5 = row.insertCell(1);
var t5=document.createElement("select");
var x = $("#color_id"),
y = x.clone();
y.attr("id","color_id");
y.attr("name","color_id[]");
var color=+rowsx.find('select[name^="color_id[]"]').val();
y.val(color);
y.attr("selected","selected"); 
y.width(100);
y.appendTo(cell5);
  

var cell4 = row.insertCell(2);
var t4=document.createElement("select");
var x = $("#sz_code"),
y = x.clone();
y.attr("id","sz_code");
y.attr("name","sz_code[]");
y.width(100);
y.appendTo(cell4);

var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required="true";
t5.id = "qty"+indexcone;
t5.name="qty[]";
t5.className="QTY";
t5.onkeyup=mycalc();
t5.value="0";
t5.setAttribute("onkeyup", "mycalc();");
cell5.appendChild(t5);

var cell6 = row.insertCell(4);
var t5=document.createElement("select");
var x = $("#unit_id"),
y = x.clone();
y.attr("id","unit_id");
y.attr("name","unit_id[]");
var unit=+rowsx.find('select[name^="unit_id[]"]').val();
y.val(unit);
y.attr("selected","selected");
y.width(100);
y.appendTo(cell6);
 
 
var cell8=row.insertCell(5);
var btnAdd = document.createElement("input");
btnAdd.id = "Abutton";
btnAdd.name = "Abutton[]";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
// btnAdd.setAttribute("onclick", "insertcone(); ");
cell8.appendChild(btnAdd);
 
 
 
var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell8.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

indexcone++;
recalcIdcone();
 mycalc();
}

  
$("table.footable_3").on("keyup", 'input[name^="qty[]"],input[name^="base_rate[]"],input[name^="cgst_per[]"],input[name^="cgst_amt[]"],input[name^="sgst_per[]"],input[name^="sgst_amt[]"],input[name^="igst_per[]"],input[name^="igst_amt[]"],input[name^="amount[]"],input[name^="total_amount[]"],input[name^="gst_amt[]"],input[name^="total_qty[]"],input[name^="GrossAmount[]"],input[name^="GstAmount[]"],input[name^="NetAmount[]"]', function (event) {
       // CalculateRow($(this).closest("tr"));
        mycalc();
    });
	
		
		
		
	function CalculateRow(row)
	{ 
		var qty=+row.find('input[name^="qty[]"]').val();
        var total_qty=+row.find('input[name^="total_qty[]"]').val();
		var base_rate=+row.find('input[name^="base_rate[]"]').val();
		var amount=parseFloat(qty * base_rate).toFixed(2);
        var total_amount=+row.find('input[name^="total_amount[]"]').val();
		var cgst_per=+row.find('input[name^="cgst_per[]"]').val();
		var cgst_amt=+row.find('input[name^="cgst_amt[]"]').val();
        var igst_per=+row.find('input[name^="igst_per[]"]').val();
		var igst_amt=+row.find('input[name^="igst_amt[]"]').val();
        var sgst_per=+row.find('input[name^="sgst_per[]"]').val();
		var sgst_amt=+row.find('input[name^="sgst_amt[]"]').val();
		var GrossAmount= +row.find('input[name^="GrossAmount[]"]').val();
		var GstAmount= +row.find('input[name^="GstAmount[]"]').val();
		var NetAmount=+row.find('input[name^="NetAmount[]"]').val();
		  
		 
		 if(qty>0)
		 {
			 
             row.find('input[name^="amount[]"]').val(amount);
			 
			 if(igst_per!=0)
			 {
                igst_amt=parseFloat(amount*(igst_per/100)).toFixed(2);
				  row.find('input[name^="igst_amt[]"]').val(parseFloat(igst_amt));
				  total_amount=parseFloat(amount)+parseFloat(igst_amt);
				  row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));
                  row.find('input[name^="gst_amt[]"]').val(parseFloat(igst_amt));
                  row.find('input[name^="cgst_per[]"]').val(0);
                    row.find('input[name^="cgst_amt[]"]').val(0);
                    row.find('input[name^="sgst_per[]"]').val(0);
                    row.find('input[name^="sgst_amt[]"]').val(0);

			 }
			 else
			 {
                row.find('input[name^="igst_per[]"]').val(0);
                row.find('input[name^="igst_amt[]"]').val(0);
                cgst_amt=parseFloat(amount*(cgst_per/100)).toFixed(2);
				  row.find('input[name^="cgst_amt[]"]').val(parseFloat(cgst_amt));
				  
				  sgst_amt=parseFloat(amount*(sgst_per/100)).toFixed(2);
				  row.find('input[name^="sgst_amt[]"]').val(parseFloat(sgst_amt));
				 				  
				  total_amount=parseFloat(amount)+parseFloat(cgst_amt)+parseFloat(sgst_amt);
				  row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));
				  row.find('input[name^="gst_amt[]"]').val(parseFloat(cgst_amt)+parseFloat(sgst_amt));
				 
			 }
			 
		}
			 
			 	  mycalc();
}















function mycalc()
{   
sum1 = 0.0;
var amounts = document.getElementsByClassName('QTY');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_qty").value = sum1.toFixed(2);

var order_rate=$("#order_rate").val();
var order_value=order_rate * sum1.toFixed(2);
$("#order_value").val(order_value.toFixed(2));


if(order_value<=0)
{
    document.getElementById("Submit").disabled=true;
    
}
else
{
    document.getElementById("Submit").disabled=false;
}
}



function deleteRowcone(btn) {
    // alert();
if(document.getElementById('cntrr').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;

recalcIdcone();

if($("#cntrr").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}



function recalcIdcone(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}
  $('.select2').select2();

</script>

<!-- end row -->
@endsection