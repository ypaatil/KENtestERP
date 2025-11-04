@extends('layouts.master') 
@section('content')
<style>
    .hide{
        display:none;
    }
    
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Spares - Material Inward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Spares - Material Inward</li>
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
            <form action="{{route('MaterialInward.update',$materialInward)}}" method="POST" id="frmData" enctype="multipart/form-data">
               <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'MaterialInward' ?>" /> 
               @method('put')
               @csrf    
               <h4 class="card-title mb-4">Spares - Material Inward: {{ $materialInward->materiralInwardCode }}</h4>
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
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="materiralInwardDate" class="form-label">GRN Date</label>
                        <input type="date" name="materiralInwardDate" class="form-control" id="materiralInwardDate" value="{{ $materialInward->materiralInwardDate  }}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                        <input type="hidden" name="materiralInwardCode" class="form-control" id="materiralInwardCode" value="{{ $materialInward->materiralInwardCode }}" readonly="readonly">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="invoice_no" class="form-label">Invoice No</label>
                        <input type="text" name="invoice_no" id="invoice_no" class="form-control" id="invoice_no" value="{{ $materialInward->invoice_no }}"  >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="invoice_date" class="form-label">Invoice Date</label>
                        <input type="date" name="invoice_date" id="invoice_date" class="form-control" id="invoice_date" value="{{ $materialInward->invoice_date }}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="po_code" class="form-label">PO NO</label>   
                        <select name="po_code" class="form-select select2" id="po_code" onchange="getPODetails();FetchPoData();" disabled >
                           <option value="">PO code</option>
                           @foreach($POList as  $rowpol)
                           <option value="{{ $rowpol->pur_code  }}"
                           {{ $rowpol->pur_code == $materialInward->po_code ? 'selected="selected"' : '' }}
                           >{{ $rowpol->pur_code }}</option>
                           @endforeach
                        </select>
                        <input type="hidden" name="po_codenew" id="po_codenew" class="form-control"  value="{{ request()->po_code;  }}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="po_type_id" class="form-label">PO Type</label>
                        <select name="po_type_id" class="form-select" id="po_type_id" onchange="getPartyDetails();" style="width:200px;" disabled>
                           <option value="">PO Type</option>
                           @foreach($POTypeList as  $rowpo)
                           <option value="{{ $rowpo->po_type_id  }}"
                           {{ $rowpo->po_type_id == $materialInward->po_type_id ? 'selected="selected"' : '' }}
                           >{{ $rowpo->po_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Supplier</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" onchange="getPartyDetails();" disabled>
                           <option value="">--- Select Party Name ---</option>
                           @foreach($ledgerlist as  $rowledger)
                           <option value="{{ $rowledger->ac_code  }}"
                           {{ $rowledger->ac_code == $materialInward->Ac_code ? 'selected="selected"' : '' }}
                           >{{ $rowledger->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-check form-check-primary mb-5">
                        <input class="form-check-input" type="checkbox" id="is_opening" disabled name="is_opening"  @php if($materialInward->is_opening==1){echo 'checked'; } @endphp>
                        <label class="form-check-label" for="is_opening">
                        Opening Stock
                        </label>
                     </div>
                  </div>
                  
                  
                   <div class="col-md-3">
                        <label for="location_id" class="form-label">Location/Warehouse</label>
                        <select name="location_id" class="form-select select2  " id="location_id" disabled>
                           <option value="">--Select Buyer--</option>
                           @foreach($LocationList as  $row)
                           {
                           <option value="{{ $row->loc_id }}"
                           {{ $row->loc_id == $materialInward->location_id ? 'selected="selected"' : '' }}    
                           >{{ $row->location }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
               </div> 
               <div class="table-wrap">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>SrNo</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Classification</th>
                              <th>UOM</th>
                              <th>Quantity</th>
                              <th>Rate</th>
                              <th>Amount</th>
                              <th>Remove</th>
                           </tr>
                        </thead>
                        <tbody id="bomdis" >
                           @php    
                           if($detailpurchase =="") 
                           {   
                           @endphp
                           <tr>
                              <td><input type="text" name="id" value="1" id="id"  style="width:50px;" readonly /></td>
                              <td>
                                 <select name="item_codes[]" class="item" id="item_code" class="" style="width:200px;height:30px;" onchange="CheckDuplicate(this);" disabled>
                                      
                                    <option value="">--- Select Item ---</option>
                                    @foreach($itemlist as  $rowitem)
                                    {
                                    <option value="{{ $rowitem->item_code  }}">{{ $rowitem->item_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select name="unit_id[]" class="unit_ids" id="unit_ids"    style="width:150px;height:30px;">
                                    <option value="">--- Select Unit ---</option>
                                    @foreach($unitlist as  $rowunit)
                                    {
                                    <option value="{{ $rowunit->unit_id  }}" >{{ $rowunit->unit_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td><input type="number" step="any"  name="item_qtys[]" class="QTY"  value="0" id="item_qty" style="width:80px;height:30px;" required/>
                                 <input type="hidden"   name="hsn_codes[]"   value="0" id="hsn_codes" style="width:80px;height:30px;"  required/>
                              </td>
                              <td><input type="number" step="any"  name="item_rates[]"    value="0" id="item_rates" style="width:80px;height:30px;" disabled/></td>
                              <td><input type="number" step="any" readOnly name="amounts[]" class="AMT"  value="0" id="amounts" style="width:80px;height:30px;" disabled/></td>
                              <td>
                                  <button type="button" onclick="deleteRow();" class="btn btn-danger pull-left" >X</button> 
                              </td>
                           </tr>
                           @php 
                               } 
                           @endphp    
                        </tbody>
                     </table>
                  </div>
               </div>
               <input type="hidden"   name="cnt" id="cnt" value="{{ count($detailpurchase) }}">  
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="totalqty" class="form-label">Total Quantity</label>
                        <input type="text" name="totalqty" class="form-control" id="totalqty" value="{{ $materialInward->totalqty }}" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_amount" class="form-label">Total Amount</label>
                        <input type="text" name="total_amount" class="form-control" id="total_amount" value="{{ $materialInward->total_amount }}" readonly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="remark" class="form-label">Remark</label>
                        <input type="text" name="remark" class="form-control" id="remark" value="{{ $materialInward->remark }}" >
                     </div>
                  </div>
               </div>
               <div class="table-responsive">
                <table id="footable_3" class="table table-bordered table-striped m-b-0 footable_3">
                    <thead>
                        <tr>
                            <th>Attachment Name</th>
                            <th>Attachment</th>
                            <th>View Attachment</th>
                            <th>Add/Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($MaterialInwardAttachmentList) > 0)
                        @foreach($MaterialInwardAttachmentList as $index => $row)
                        <tr>
                           <td> 
                               <input type="text" name="attachment_name[]" class="form-control" value="{{ $row->attachment_name }}" id="attachment_name_{{ $index }}" style="width:300px;"/>
                           </td>
                            <td> 
                                <input type="file" name="upload_attachment[]" id="upload_attachment_{{ $index }}" style="width:200px;"/>
                            </td>
                            <td>
                                @if(isset($row->upload_attachment))
                                    <a href="{{ asset('public/uploads/MaterialInward/'. $row->upload_attachment) }}" target="_blank">View Attachment</a>
                                @endif 
                            </td>
                            <td>
                                <input type="button" style="width:40px;" id="Abutton{{ $index }}" name="button[]" value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left">
                                <input type="button" id="Bbutton{{ $index }}" class="btn btn-danger pull-left" onclick="deleteRow1(this);" value="X" style="margin-left:5px;">
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                           <td> 
                               <input type="text" name="attachment_name[]" class="form-control" value="" id="attachment_name_0" style="width:300px;"/>
                           </td>
                            <td> 
                                <input type="file" name="upload_attachment[]" id="upload_attachment_0" style="width:200px;"/>
                            </td>
                            <td>
                               -
                            </td>
                            <td>
                                <input type="button" style="width:40px;" id="Abutton0" name="button[]" value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left">
                                <input type="button" id="Bbutton0" class="btn btn-danger pull-left tbl3" onclick="deleteRow1(this);" value="X" style="margin-left:5px;">
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
               </div>
               </br>  
               <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit">Update</button>
               <a href="{{ Route('MaterialInward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row -->

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>
<script>


    function deleteRow1(btn) 
    { 
        var row = btn.parentNode.parentNode; 
        var link = $(btn).closest('tr').find('a').attr('href'); 
    
        if (typeof link !== 'undefined' && link) { 
            var filename = link.substring(link.lastIndexOf('/') + 1); 
            var materiralInwardCode = $("#materiralInwardCode").val(); 
                
            $.ajax({
                type: "GET",
                dataType: "json", 
                url: "{{ route('DeleteMaterialInwardAttachment') }}",
                data:{'upload_attachment':filename,'materiralInwardCode' : materiralInwardCode}, 
                success: function(data) { 
                    row.parentNode.removeChild(row); 
                },
                error: function(xhr, status, error) {
                    console.error('Error occurred while deleting:', error);
                }
            });

        } 
        else 
        { 
            var table = row.parentNode;
            if (table.rows.length > 1) 
            {
                row.parentNode.removeChild(row); 
            }
        } 
    }
    
    function addNewRow(row)
    {
        var table = $(row).closest('table');
        var lastRow = table.find('tr:last').clone();  
        
        lastRow.find('input[type="text"]').val('');  
        lastRow.find('input[type="file"]').val(''); 
    
        table.append(lastRow); 
    }
    
    function CheckDuplicate(row) 
    {
      const currentDropdown = row // Reference to the current dropdown
      const currentValue = $(row).val(); // Value of the current dropdown

      // Check if the current value exists in other dropdowns
      let isDuplicate = false;
      $('select[name="item_codes[]"]').not(currentDropdown).each(function () {
        if ($(this).val() === currentValue) {
          isDuplicate = true;
          return false; // Exit loop early if duplicate is found
        }
      });

      if (isDuplicate) {
        // Show alert and reset current dropdown
        alert('This item code is already selected. Please choose a different one.');
        $(row).val('');
      }
    }
    
    $('#is_opening').change(function () 
    {
        if ($(this).is(':checked')) 
        {
            $('select[name="item_codes[]"]').attr('disabled', false);
        } 
        else 
        {
            $('select[name="item_codes[]"]').attr('disabled', true);
        }
    });
    
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
     
    function checkNumber(row) 
    { 
        var max = parseFloat($(row).attr('max'));  // Set the maximum value here
        var stock = parseFloat($(row).attr('stock'));  // Set the maximum value here
        var inputNumber =  parseFloat($(row).val());
        console.log(max);
        if (inputNumber > max || inputNumber >= stock) {
            alert(`The number exceeds the maximum value of ${max}`);
            $(row).val(max);
        } 
    }
    

    $( document ).ready(function() 
    {     
           GetBomdisData();   
    });
    
    function GetBomdisData()
    {
        var materiralInwardCode = $("#materiralInwardCode").val();
        var po_code = $("#po_code").val();
        
        $.ajax({
              type: "GET",
              dataType:"json",
              async: true,
              url: "{{ route('GetMateriralInwardCodeWiseData') }}",
              data:{'materiralInwardCode':materiralInwardCode,'po_code' : po_code},
              success: function(data)
              {
                  $("#bomdis").html(data.html); 
                //  $(".btn-danger").not("button[name='allocate[]']").prop("disabled", true);

              }
        }); 
        // setTimeout(function() {
        //      $(".tbl3").prop("disabled", false);
        // }, 1000);
    }
    
    function SetQtyToBtn(obj)
    { 
        var qty = $(obj).val();
        $(obj).parent().parent('tr').find('td button[name="allocate[]"]').attr('qty', qty);
    }
    
    
   function GetUnit(row)
   { 
       var tax_type_ids=1;
       var item_code = $(row).val();
       var row = $(row).closest('tr');
       
       $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GSTPER') }}",
           data:{item_code:item_code,tax_type_id:tax_type_ids},
           success: function(data)
           { 
                $(row).find('input[name^="hsn_code[]"]').val(data.data[0].unit_id);
                $(row).find('select[name^="unit_ids[]"]').val(data.data[0].unit_id).change();
            }
        });
   }
   
   
   
   
   
   function selselect()
   {
      setTimeout(
   function() 
   {
   
   $("#footable_2 tr td  select[name='item_codes[]']").each(function() {
   
     $(this).closest("tr").find('select[name="item_codes[]"]').select2();
     $(this).closest("tr").find('select[name="rack_id[]"]').select2();
   
     });
   }, 1000);
   }
   
   
   function deleteRow(btn) {
   if(document.getElementById('cnt').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cnt').value = document.getElementById('cnt').value-1;
   recalcId();
   mycalc();
   if($("#cnt").val()<=0)
   {       
   document.getElementById('Submit').disabled=true;
   }
   
   
   
   }
   }
   
   function recalcId(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
    function getPODetails()
   {
      
       var po_code=$("#po_code").val();
       
       $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('getTrimsPODetails') }}",
               data:{'po_code':po_code},
               success: function(data){
                   
                //   $("#po_type_id").val(data[0]['po_type_id']);
                   $("#Ac_code").val(data[0]['Ac_code']);
                    
                      
                    
                   var qhtml=data[0]["ac_name"];
                   var q=data[0]['Ac_code'];
                   $('#Ac_code').val(data[0]["Ac_code"]);
                  // $('#select2-chosen-1').html(qhtml);
                   
                   
                   
                  
           }
           });
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
   document.getElementById("totalqty").value = sum1.toFixed(2);
   
    
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('AMT');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_amount").value = sum1.toFixed(2);
   
   }
   
   
   
   
   function frieght_payable()
   {
       var Net_amount=document.getElementById('Net_amount').value;
       var freight_amt=$('#freight_amt').val();
   
   var payable_amount=(parseFloat(Net_amount) + (parseFloat(freight_amt)));
   $('#payable_amt').val(parseFloat(payable_amount).toFixed(0));
   
   }
   
   
   function disc_calculatess()
   {
   
   var item_qty=document.getElementById('item_qty').value;
   var item_rate=document.getElementById('item_rate').value;
   var disc_per=document.getElementById('disc_per').value;
   var amount= item_qty*item_rate
   
   var disc_amount= parseFloat(parseFloat(amount) * parseFloat(disc_per/100));
   $('#disc_amount').val(disc_amount.toFixed(2));
   
   var amount= parseFloat(parseFloat(amount) - parseFloat(disc_amount)).toFixed(2);
   $('#amount').val(amount);
   calculateGstsss();
   
   }
   
   
   
   
   function calculateGstsss()
   {
   var amount=document.getElementById('amount').value;
   var pur_cgst=document.getElementById('pur_cgst').value;
   var pur_sgst=document.getElementById('pur_sgst').value;
   var pur_igst=document.getElementById('pur_igst').value;
   
   var tax_type_id1=document.getElementById('tax_type_id').value;
   if(tax_type_id1==2)
   {
   var iamt=  parseFloat(( amount*(pur_igst/100))).toFixed(2);
   $('#iamt').val(iamt);
   
   $('#total_amount').val(parseFloat(amount) + parseFloat(iamt));
   
   }
   else{
   var camt=  parseFloat(( amount*(pur_cgst/100))).toFixed(2);
   $('#camt').val(camt);
   var samt= parseFloat(( amount*(pur_sgst/100))).toFixed(2);
   $('#samt').val(samt);
   
   $('#total_amount').val(parseFloat(amount) + parseFloat(camt) + parseFloat(samt));
   
   }
   }
   
   
   function divideBy(str) 
   { 
   // item_code = document.getElementById("item_code").value;  
   
   // calculate_gst(item_code);
   
   }
   
   var tax_type_id =1;
   function calculate_gst(item_code)
   {
   
   
   tax_type_ids =document.getElementById("tax_type_id").value
   
   $.ajax(
   {
   type:"GET",
   dataType:'json',
   url: "{{ route('GSTPER') }}",
   data:{item_code:item_code,tax_type_id:tax_type_ids},
   success:function(response)
   {
   
     console.log(response);  
   
      if(tax_type_id==1)
               {
   
     $("#pur_cgst").val(response[0].cgst_per); 
     $("#pur_sgst").val(response[0].sgst_per); 
     $("#pur_igst").val(response[0].igst_per);
   } else{
   
   $("#pur_igst").val(response[0].igst_per);
   
   }
   
   }
   
   });
   }
   
   
    
   
   
   function firmchange(firm_id){
   
   
   var type=document.getElementById('type').value;
   
   //alert(firm_id);
   
   $.ajax({
   type:"GET",
   url:'getdata.php',
   dataType:"json",
   data:{firm_id:firm_id,type:type, fn:"Firm_change"},
   success:function(response){
   console.log(response);  
   
   $("#pur_code").val(response["code"]+'-'+response["tr_no"]);
   $("#c_code").val(response["c_code"]);
   
   }
   });
   }
   
   
   
   function getPartyDetails()
   {
       
   }
   
   
   
       $(document).on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"]', function (event) {
           CalculateRow($(this).closest("tr"));
          });
       function CalculateRow(row)
       {
           var item_qtys=+row.find('input[name^="item_qtys[]"]').val();
           var item_rates=+row.find('input[name^="item_rates[]"]').val();
           var amount=(parseFloat(item_qtys)*parseFloat(item_rates)).toFixed(2);
           row.find('input[name^="amounts[]"]').val(amount);
           mycalc();
       }
   
   function getBomDetail(type){
       
       
       var  bom_codes = $("#bom_code option:selected").map(function() {
         return this.value;
       }).get().join(",");
       
       
      // alert(bom_codes);
   // var bom_code=document.getElementById("bom_code").value;
   
   var tax_type_id=document.getElementById("tax_type_id").value;
   
   
   $.ajax({
   type:"GET",
   url:"{{ route('getBoMDetail') }}",
   //dataType:"json",
   data:{type:type,bom_code:bom_codes,tax_type_id:tax_type_id},
   success:function(response){
   console.log(response);  
       $("#bomdis").append(response.html);
    mycalc();
   }
   });
   }
   
   $(document).ready(function(){
   
   FetchPoData();
   
   }); 
   
   function FetchPoData()
   {
       var po_code=document.getElementById('po_code').value;  
       if(po_code !="" && po_code!=0)
       {
           $("#Ac_code").select2("destroy");     
           gettable(po_code);
           getPODetails();
   
       }
   }
   
   setInterval(function() {mycalc()}, 1000);
   
   //  setInterval(fun, 3000);  
   
   function gettable(po_code){
   
      
   var po_codes=btoa(po_code);
   $.ajax({
   type:"GET",
   url:"{{ route('getPoForTrims') }}",
   //dataType:"json",
   data:{po_code:po_codes},
   success:function(response){
   console.log(response);  
       $("#trimInward").html(response.html);
   
   }
   });
   
   }
    
   function EnableFields()
   {
        $("input").prop('disabled', false);
        $("select").prop('disabled', false);
        $("#is_opening").props('disabled', false);
   }
   
   function getDetails(po_code){
   
   $.ajax({
   type:"GET",
   url:"{{ route('getPoMasterDetailTrims') }}",
   //dataType:"json",
   data:{po_code:po_code},
   success:function(response){
   console.log(response);
   
   $("#Ac_code").val(response[0].Ac_code);
   $("#tax_type_id").val(response[0].tax_type_id);
   $("#supplierRef").val(response[0].supplierRef);
   $("#pur_date").val(response[0].pur_date);
   $("#po_type_id").val(response[0].po_type_id);
   $("#bomtype").val(response[0].bomtype);
   $("#bom_code").val(response[0].bom_code);
   $("#in_narration").val(response[0].narration);
   $("#po_code").val(response[0].pur_code);
   
   
   }
   });
   } 
   
   
   
   function openmodal(po_code,item_code)
 {
     
     getFabInDetails(po_code,item_code);
        $('#modalFormSize').modal('show');
 }
 
  function closemodal()
 {
       $('#modalFormSize').modal('hide');
    //    $('#product-options').modal('hide');
 }
 
 
 
 function getFabInDetails(po_code,item_code)
{
     
    $.ajax({
    type: "GET",
    url: "{{ route('GetComparePOInwardList') }}",
    data: { sr_no: po_code, item_code: item_code },
    success: function(data){
    $("#MaterialInwardData").html(data.html);
    }
    });
}
   
   
   function deleteRow(btn) 
   { 
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row); 
       recalcId();
       mycalc(); 
   }   
   
</script>
@endsection