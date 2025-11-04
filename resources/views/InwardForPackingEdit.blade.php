@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Inward For Packing </h4>
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
            @if(isset($InwardForPackingMasterList))
            <form action="{{ route('InwardForPacking.update',$InwardForPackingMasterList) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ofp_date" class="form-label">Entry Date</label>
                        <input type="date" name="ifp_date" class="form-control" id="ifp_date" value="{{$InwardForPackingMasterList->ifp_date}}"  >
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $InwardForPackingMasterList->c_code }}">
                        <input type="hidden" name="ifp_code" class="form-control" id="ifp_code" value="{{$InwardForPackingMasterList->ifp_code}}" readOnly>
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Outward For Packing</label>
                        <input type="text" name="ofp_code" class="form-control" id="ofp_code" value="{{$InwardForPackingMasterList->ofp_code}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Sales order No</label>
                        <input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="{{$InwardForPackingMasterList->sales_order_no}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-control select2" id="Ac_code"  disabled>
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $InwardForPackingMasterList->Ac_code ? 'selected="selected"' : '' }} 
                           >{{ $row->ac_short_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Main Style Category</label>
                        <select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)"  disabled>
                           <option value="">--Main Style--</option>
                           @foreach($MainStyleList as  $row)
                           {
                           <option value="{{ $row->mainstyle_id }}"
                           {{ $row->mainstyle_id == $InwardForPackingMasterList->mainstyle_id ? 'selected="selected"' : '' }}  
                           >{{ $row->mainstyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Sub Style Category</label>
                        <select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)"  disabled>
                           <option value="">--Sub Style--</option>
                           @foreach($SubStyleList as  $row)
                           {
                           <option value="{{ $row->substyle_id }}"
                           {{ $row->substyle_id == $InwardForPackingMasterList->substyle_id ? 'selected="selected"' : '' }}
                           >{{ $row->substyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fg_id" class="form-label">Style Name</label>
                        <select name="fg_id" class="form-control" id="fg_id"  disabled>
                           <option value="">--Select Style--</option>
                           @foreach($FGList as  $row)
                           {
                           <option value="{{ $row->fg_id }}"
                           {{ $row->fg_id == $InwardForPackingMasterList->fg_id ? 'selected="selected"' : '' }} 
                           >{{ $row->fg_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="{{$InwardForPackingMasterList->style_no}}"  readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{$InwardForPackingMasterList->style_description}}"  readOnly>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">From  Stitching Vendor</label>
                        <select name="vendorId" class="form-control" id="vendorId"  disabled >
                           <option value="">--Select Vendor--</option>
                           @foreach($Ledger as  $rowvendor)
                           {
                           <option value="{{ $rowvendor->ac_code }}"
                           {{  $rowvendor->ac_code == $InwardForPackingMasterList->vendorId ? 'selected="selected"' : '' }}
                           >{{ $rowvendor->ac_short_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>               
                  <div class="col-md-3">
                        <div class="mb-3">
                            <label for="sent_to" class="form-label">Sent To Packing Vendor</label>
                            <select name="sent_to" class="form-control select2" id="sent_to" disabled>
                            <option value="">--Select--</option>
                            @foreach($ToSent as  $sent) 
                                <option value="{{ $sent->loc_id }}"  {{  $sent->loc_id == $InwardForPackingMasterList->sent_to ? 'selected="selected"' : '' }}>{{ $sent->location }}</option> 
                            @endforeach
                            </select>
                        </div>
                  </div> 
               </div>
               <div class="row"  >
                  <div class="  "  >
                     <div class="panel-group" id="accordion"> 
                        <div class="panel panel-default">
                           <div class="panel-heading"> 
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Inward For Packing</a>
                              </h4>
                           </div>
                           <div id="collapse1" class="panel-collapse collapse in" style="width:100%;">
                              <div class="panel-body">
                                 <div class="row">
                                    <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
                                    <div class="table-wrap">
                                       <div class="table-responsive">
                                          <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th>Color</th>
                                                   @foreach ($SizeDetailList as $sz) 
                                                   <th>{{$sz->size_name}}</th>
                                                   @endforeach
                                                   <th>Total Qty</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                @if(count($InwardForPackingDetailList)>0)
                                                @php $no=1;$n=1; @endphp
                                                @foreach($InwardForPackingDetailList as $List) 
                                                <tr>
                                                   <td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;"/></td>
                                                   <td>
                                                      <input type="hidden" name="item_codef[]" value="{{$List->item_code}}" id="item_codef"  />
                                                      <select name="color_id[]"   id="color_id" style="width:300px; height:30px;" disabled>
                                                         <option value="">--Color  List--</option>
                                                         @foreach($ColorList as  $row)
                                                         {
                                                         <option value="{{ $row->color_id }}"
                                                         {{ $row->color_id == $List->color_id ? 'selected="selected"' : '' }} 
                                                         >{{ $row->color_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   @php 
                                                        $n=1;   
                                                        $SizeQtyList=explode(',', $List->size_qty_array);
                                                        $finishingBillingData = DB::SELECT("SELECT sum(packing_qty) as packing_qty FROM finishing_billing_details WHERE perticular_ids=8 AND sales_order_no='".$InwardForPackingMasterList->sales_order_no."'");
                                                        
                                                        $packing_qty = isset($finishingBillingData[0]->packing_qty) ? $finishingBillingData[0]->packing_qty : 0;
                                                        
                                                        
                                                   @endphp
                                                   @foreach($SizeQtyList  as $szQty)
                                                   <td ><input style="width:80px; float:left;"  name="s@php echo $n; @endphp[]"  max="{{$szQty}}"  class="size_id" type="number" id="s@php echo $n; @endphp" value="{{$szQty}}" required />  </td>
                                                   @php $n=$n+1;  @endphp
                                                   @endforeach
                                                   <td>
                                                      <input type="hidden" name="overall_size_qty"  value="{{$List->size_qty_total}}" class="overall_size_qty" style="width:80px; float:left;"  />
                                                      <input type="number" step="any" name="size_qty_total[]" class="size_qty_total"  value="{{$List->size_qty_total}}" min="{{min($packing_qty, $List->size_qty_total)}}" max="{{$List->size_qty_total}}" id="size_qty_total" style="width:80px; height:30px; float:left;" readonly />
                                                      <input type="hidden" name="size_qty_array[]"  value="{{$List->size_qty_array}}" id="size_qty_array" style="width:80px; float:left;"  />
                                                      <input type="hidden" name="size_array[]"  value="{{$List->size_array}}" id="size_array" style="width:80px;  float:left;"  />
                                                   </td>
                                                </tr>
                                                @php $no=$no+1;  @endphp
                                                @endforeach
                                                @endif
                                             </tbody>
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
               </br>
               </br>
               <!-- end row -->
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Total Qty</label>
                        <input type="text" name="total_qty" class="form-control" id="total_qty" value="{{$InwardForPackingMasterList->total_qty}}" readOnly>
                     </div>
                  </div>
                  <div class="col-sm-8">
                     <label for="formrow-inputState" class="form-label">Narration</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="{{$InwardForPackingMasterList->narration}}" />
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
                     <a href="{{ Route('InwardForPacking.index') }}" class="btn btn-warning w-md">Cancel</a>
                  </div>
               </div>
         </div>
         </form>
         @endif
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

   $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
        mycalc();
    });
    
    function CheckMaxsize(row) 
    {
        
        // $("#footable_1").find('tr').each(function() 
        // {
        //     var color_id1 = parseInt($(row).parent().parent('tr').find('td select[name="color_id[]"]').val());
        //     var color_id2 = parseInt($(this).find('td select[name="color_ids[]"]').val());
        //     console.log("==>"+color_id2+"==>"+color_id1);
        //     if (!isNaN(color_id1) && !isNaN(color_id2)) 
        //     {
        //         if (color_id1 < color_id2) 
        //         { 
        //             alert("Stitiching Value reached the max limit");
        //             return false;
        //         }
        //     }
        // });
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

$(document).on("change", 'input[class^="size_id"]', function (event) 
{
    var no=1;
    var max = $(this).closest("tr").find('input[name="size_qty_total[]"]').attr('max'); 
    var sz_qty = $(this).val(); 
    
    if(parseFloat(sz_qty) <= parseFloat(max))
    {
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
         
            
        var total_qty = $(this).parent().parent('tr').find('td input[name="size_qty_total[]"]').val(); 
    
        if(parseFloat(total_qty) <= parseFloat(max))
        {
            
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
        
        }
        else
        {
            
            alert("Quantity should be less than "+max);
            $(this).val(0);
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
        }
        
        var total_qty = $(this).parent().parent('tr').find('td input[name="size_qty_total[]"]').val();
        var overall_size_qty = $(this).parent().parent('tr').find('td .overall_size_qty').val();
        
        if(parseFloat(total_qty) <= parseFloat(overall_size_qty))
        {
            
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
        }
        else
        {
            
            alert("Quantity should be less than "+overall_size_qty);
            $(this).val(0);
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
        }
        
      
        mycalc();
    }
    else
    { 
        alert("Quantity should be less than "+max);
        $(this).val(0);
        
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
    } 
      
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
 
    function EnableFields()
    {
        $("select").prop('disabled', false);
    }



 
 $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
    
    mycalc();

});

  
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