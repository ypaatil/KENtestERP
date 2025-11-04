@extends('layouts.master') 
@section('content')
<style>
   .hide
   {
   display:none!important;
   }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Inward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Inward</li>
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
            <h4 class="card-title mb-4">Fabric Inward</h4>
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
            <form action="{{route('FabricInward.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="in_date" class="form-label">In Date</label>
                        <input type="date" name="in_date" class="form-control" id="in_date" value="{{date('Y-m-d')}}" required>
                        @foreach($counter_number as  $row)
                        <input type="hidden" name="in_code" class="form-control" id="in_code" value="{{ 'GRN/21-22/FP'.''.$row->tr_no }}">
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                        <input type="hidden" name="PBarcode" class="form-control" id="PBarcode" value="{{ $row->PBarcode }}">
                        <input type="hidden" name="CBarcode" class="form-control" id="CBarcode" value="{{ $row->CBarcode }}">
                        @endforeach
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_code" class="form-label">PO Code</label>   
                        <select name="po_code" class="form-select select2" id="po_code" onchange="getDetails(this.value);GetPurchaseBillDetails();"   >
                           <option value="">PO code</option>
                           @foreach($POList as  $rowpol)
                           {
                           <option value="{{ $rowpol->pur_code  }}"
                           {{ $rowpol->pur_code == request()->po_code ? 'selected="selected"' : '' }} 
                           >{{ $rowpol->pur_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-invoice_no-input" class="form-label">Invoice No</label>
                        <input type="text" name="invoice_no" id="invoice_no" class="form-control" id="formrow-invoice_no-input" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-invoice_date-input" class="form-label">Invoice Date</label>
                        <input type="date" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="{{date('Y-m-d')}}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Supplier</label>
                        <select name="Ac_code" class="form-select   " id="Ac_code" required>
                           <option value="">--Select Supplier--</option>
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
                         <label for="bill_to" class="form-label">Bill To</label>
                         <select name="bill_to" class="form-select" id="bill_to" disabled>
                            <option value="">--Select--</option>
                            @foreach($BillToList as  $row) 
                                <option value="{{ $row->sr_no }}">{{ $row->trade_name }}({{$row->site_code}})</option> 
                            @endforeach
                         </select>
                      </div>
                   </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">CP Type</label>
                        <select name="cp_id" class="form-select" id="cp_id" required onchange="serBarocode();"  disabled>
                           <option value="">--Select CP Type--</option>
                           @foreach($CPList as  $rowCP)
                           {
                           <option value="{{ $rowCP->cp_id }}"
                           @php if($rowCP->cp_id ==1){echo 'selected';} @endphp
                           >{{ $rowCP->cp_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">PO Type</label>
                        <select name="po_type_id" class="form-select" id="po_type_id" required >
                           <option value="">Type</option>
                           @foreach($POTypeList as  $rowpo)
                           {
                           <option value="{{ $rowpo->po_type_id  }}">{{ $rowpo->po_type_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-check form-check-primary mb-5">
                        <input class="form-check-input" type="checkbox" id="is_opening" name="is_opening" onclick="enable(this);">
                        <label class="form-check-label" for="is_opening">
                        Opening Stock
                        </label>
                     </div>
                  </div>
                <div class="col-md-3">
                    <label for="fge_code" class="form-label">Fabric Gate Code</label>
                    <select name="fge_code" class="form-select select2" id="fge_code">
                       <option value="">--Select--</option>
                       @foreach($FGECodeList as  $row) 
                            <option value="{{ $row->fge_code }}">{{ $row->fge_code }}</option> 
                       @endforeach
                    </select>
                 </div>
                <div class="col-md-3">
                    <label for="location_id" class="form-label">Location/Warehouse</label>
                    <select name="location_id" class="form-select select2  " id="location_id" required>
                       <option value="">--Location--</option>
                       @foreach($LocationList as  $row) 
                            <option value="{{ $row->loc_id }}">{{ $row->location }}</option> 
                       @endforeach
                    </select>
                 </div>
                  <div class="col-md-2 hide" id="vendorData">
                     <div class="mb-3">
                        <label for="" class="form-label">Vendor Name</label>   
                         <input type="text" name="vendorName" class="form-control" id="vendorName"  value=""  readonly style="width: 250px;"/>
                     </div>
                  </div>
               </div>
               <div class="table-wrap" id="pofetch">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                        <thead>
                           <tr>
                              <th>Roll No</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Part</th>
                              <th>Meter</th>
                              <th>Gram/Meter</th>
                              <th>KG</th>
                              <th>Rate Per Meter</th>
                              <th>Amount</th>
                              <th nowrap>Suplier Roll No.</th>
                              <th>TrackCode</th>
                              <th>Print</th>
                              <th>Add/Remove</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td><input type="text" name="id[]" value="1" id="id" style="width:50px;"/></td>
                              <td><input type="text" name="item_codes[]" value="" id="item_codes" style="width:80px;" readonly/></td>
                              <td>
                                 <select name="item_code[]"   id="item_code" class="select2" style="width:200px;height:30px;" required  onchange="getRateFromPO(this);">
                                    <option value="">--Item--</option>
                                    @foreach($ItemList as  $row)
                                    {
                                    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select name="part_id[]"  id="part_id" class="select2" style="width:200px;height:30px;" required>
                                    <option value="">--Part--</option>
                                    @foreach($PartList as  $row)
                                    {
                                    <option value="{{ $row->part_id }}"
                                    {{ $row->part_id == 1 ? 'selected="selected"' : '' }}  
                                    >{{ $row->part_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="1" id="taga_qty1" style="width:50px;height:30px;"/><input type="number" step="any" class="METER" name="meter[]" onkeyup="mycalc();" value="0" id="meter1" style="width:80px;height:30px;" required/></td>
                              <td><input type="number" step="any"  name="gram_per_meter[]" value="0" id="gram_per_meter" style="width:80px;height:30px;" required/></td>
                              <td><input type="number" step="any" @php $user_type=Session::get('user_type'); if($user_type!=1){ echo 'readOnly'; }@endphp class="KG" name="kg[]" onkeyup="mycalc();" value="0" id="kg" style="width:80px;height:30px;"  required/></td>
                              <td><input type="number" step="any"    name="item_rates[]"   value="0" id="item_rates" style="width:80px;height:30px;" @php $user_type=Session::get('user_type'); if($user_type!=1){ echo 'readOnly'; }@endphp required/>
                              <td><input type="number" step="any" class="AMT" readOnly  name="amounts[]"   value="0" id="amounts" style="width:80px;height:30px;" required/></td>
                              <td><input type="text"  class="suplier_roll_no"  name="suplier_roll_no[]"   value="" id="suplier_roll_no" style="width:100px;height:30px;"/></td>
                              <td><input type="text" name="track_code[]"  id="track_code" style="width:80px;height:30px;"  readOnly/></td>
                              <td><i   style="font-size:25px;" onclick="CalculateRowPrint(this);" name="print"  class="fa fa-print" ></td>
                              <td>
                                 <input type="button"  style="width:40px;" onclick="insertcone(); " name="print" value="+" class="btn btn-warning pull-left AButton"> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" >
                              </td>
                           </tr>
                        </tbody>
                        <tfoot>
                           <tr>
                              <th>Roll No</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Part</th>
                              <th>Meter</th>
                              <th>Gram/Meter</th>
                              <th>KG</th>
                              <th>Rate Per Meter</th>
                              <th>Amount</th>
                              <th>Suplier Roll No.</th>
                              <th>TrackCode</th>
                              <th>Print</th>
                              <th>Add/Remove</th>
                           </tr>
                        </tfoot>
                        <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                     </table>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_meter" class="form-label">Total Meter</label>
                        <input type="number" readOnly step="0.01"  name="total_meter" class="form-control" id="total_meter" value="0">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_kg" class="form-label">Total KG</label>
                        <input type="number" readOnly step="0.01"  name="total_kg" class="form-control" id="total_kg" value="0">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Total Taga</label>
                        <input type="number"  readOnly   name="total_taga_qty" class="form-control" id="total_taga_qty" value="1">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Total Amount</label>
                        <input type="text" name="total_amount" readOnly class="form-control" id="total_amount" required>
                     </div>
                  </div>
                  </br>
                  <div class="col-md-2 mt-4">
                     <div class="mb-3">
                        <div class="form-check form-check-primary mb-5">
                           <input class="form-check-input" type="checkbox" id="isReturnFabricInward" onchange="GetOrderNo(this);" name="isReturnFabricInward">
                           <label class="form-check-label" for="isReturnFabricInward">
                           Is it retun fabric inward ? 
                           </label>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-2 hide" id="workOrder">
                     <div class="mb-3">
                        <label for="" class="form-label">Vendor Process Order No.</label>   
                        <select name="vw_code" class="form-select select2" id="vw_code" onchange="GetVendorName(this.value);" >
                           <option value="">Vendor Process Order No.</option>
                           @foreach($vendorProcessOrderList as  $vendors)
                           {
                           <option value="{{ $vendors->vpo_code  }}"
                           {{ $vendors->vpo_code == request()->vpo_code ? 'selected="selected"' : '' }} 
                           >{{ $vendors->vpo_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Narration</label>
                        <input type="text" name="in_narration" class="form-control" id="in_narration"  value=""  />
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <label for="formrow-inputState" class="form-label"></label>
                     <div class="form-group">
                        <button type="submit" class="btn btn-primary w-md" onclick="UpdateBarcode(); EnableFields();" id="Submit">Submit</button>
                        <a href="{{ Route('FabricInward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
   $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
     
    function GetPurchaseBillDetails()
    {
       var po_code = $("#po_code").val(); 
       
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetPurchaseBillToDetails') }}",
           data:{'po_code':po_code},
           success: function(data)
           { 
               $("#bill_to").html(data.detail); 
           }
        }); 
    } 
    
   function GetVendorName(vpo_code)
   {
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetVendorName') }}",
          data:{'vpo_code':vpo_code},
          success: function(data)
          {
              console.log(data);
               $("#vendorName").val(data.html);
               $('#vendorData').removeClass('hide');
               
          }
        });
   }
   function GetOrderNo(ele)
   {
       if($(ele).is(":checked"))
       {
          $('#workOrder').removeClass('hide');
          $(ele).val(1);
       }
       else
       {
           $('#workOrder').addClass('hide');
           $(ele).val(0);
       }
   }
   
   function enable(opening)
   {  
   @php $user_type=Session::get('user_type'); if($user_type!=1){  @endphp
      if(opening.checked==true)
      {  
        $("#footable_2 tr td  select[name='item_code[]']").each(function() {
            $(this).closest("tr").find('input[name="item_rates[]"]').prop('readOnly', false);
         });
        
      }
      else
      {
          $("#footable_2 tr td  select[name='item_code[]']").each(function() {
            $(this).closest("tr").find('input[name="item_rates[]"]').prop('readOnly', true);
          });
      }
   
   @php } @endphp
   
   }
   
   
   
   function getRateFromPO(row)
   {
        var po_code=$('#po_code').val();
        var item_code = $(row).val();
        
        $(row).parent().parent('tr').find('td input[name="item_codes[]"]').val(item_code);  
        var row = $(row).closest('tr'); 
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('ItemRateFromPO') }}",
          data:{'po_code':po_code,item_code:item_code},
          success: function(data)
          {
               +row.find('input[name^="item_rates[]"]').val(data[0]['item_rate'])
               
          }
        });   
   }
   
   
   
   var PBarcode=$("#PBarcode").val();
   var CBarcode=$("#CBarcode").val();
   
   
   function UpdateBarcode()
   {
        $("#PBarcode").val(PBarcode);
        $("#CBarcode").val(CBarcode);
         
   }
   
   
   function EnableFields()
   {
       $("select").prop('disabled', false);
   }
   
   
   function serBarocode()
   {
              if($("#cp_id").val()==1)
              {
                       
                      ++PBarcode;
                      $("#track_code").val('P'.concat(PBarcode.toString()));
                     //alert($("#track_code").val());
              }
              else if($("#cp_id").val()==2)
              {       var CBar='';
                      CBar='I' + parseInt(++CBarcode);
                      $("#track_code").val(CBar);
              }
   }
   
   
   
   $(document).ready(function()
   {
       
        serBarocode();
   });
   
   
   
   
   function getPODetails()
   {
     
      var po_code=$("#po_code").val();
      
      $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('PODetail') }}",
              data:{'po_code':po_code},
              success: function(data){
                  
                  $("#po_type_id").val(data[0]['po_type_id']);
                  $("#Ac_code").val(data[0]['Ac_code']);
                 
          }
          });
          
          
         
   
   }
   
   $(document).on("keyup", 'input[name^="gram_per_meter[]"],input[name^="meter[]"]', function (event) {
          CalculateRow($(this).closest("tr"));
          
      });
    	
   function CalculateRow(row)
   { 
   	var gram_per_meter=+row.find('input[name^="gram_per_meter[]"]').val();
          var meter=+row.find('input[name^="meter[]"]').val();
    	var kg=parseFloat(parseFloat(meter).toFixed(2) * parseFloat(gram_per_meter).toFixed(2)).toFixed(2);
    	
          row.find('input[name^="kg[]"]').val(kg.toFixed(2));
   	mycalc();
   }
   
   
   $(document).on("click", 'input[name^="print[]"]', function (event) {
      
          CalculateRowPrint($(this).closest("tr"));
          
      });
    	
   function CalculateRowPrint(btn)
   { 
          var row = $(btn).closest("tr");
          var width=+row.find('input[name^="width[]"]').val();
          var meter=+row.find('input[name^="meter[]"]').val();
          var kg=+row.find('input[name^="kg[]"]').val();
          var color_id=+row.find('select[name^="color_id[]"]').val();
          var part_id=+row.find('select[name^="part_id[]"]').val();
          var quality_code=+row.find('select[name^="quality_code[]"]').val();
          var track_code=row.find('input[name^="track_code[]"]').val();
          var style_no=$("#style_no").val();
          var job_code=$("#job_code").val();
          
          //alert(track_code);
          $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('PrintBarcode') }}",
              data:{'width':width,'meter':meter,'color_id':color_id,'quality_code':quality_code,'kg':kg,  'part_id':part_id,'track_code':track_code,'style_no':style_no,'job_code':job_code},
              success: function(data){
                   
               if((data['result'])=='success')
              {
                alert('Print Barcode For Roll: '+track_code);
              }
              else
              {
                  $alert('Data Can Not Be Printed');
              }
              
          }
          });
          
   }
   
   
   
   
   var indexcone = 2;
   
   function insertcone(){
   
   $("#item_code").select2("destroy");
   $("#part_id").select2("destroy");
   var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "id"+indexcone;
   t1.name= "id[]";
   t1.value=indexcone;
   
   cell1.appendChild(t1);
    
   
   var cell1=row.insertCell(1);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:80px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "item_codes"+indexcone;
   t1.name= "item_codes[]";
   
   cell1.appendChild(t1);
    
   var cell5 = row.insertCell(2);
   var t5=document.createElement("select");
   var x = $("#item_code"),
   y = x.clone();
   y.attr("id","item_code");
   y.attr("name","item_code[]");
   y.width(200);
   y.height(30);
   y.appendTo(cell5);
   
   
   
   var cell3 = row.insertCell(3);
   var t3=document.createElement("select");
   var x = $("#part_id"),
   y = x.clone();
   y.attr("id","part_id");
   y.attr("name","part_id[]");
   y.width(200);
   y.height(30);
   y.appendTo(cell3);
   
   
   var t7=document.createElement("input");
   t7.style="display: table-cell; width:80px;height:30px;";
   t7.type="hidden";
   t7.className="TAGAQTY";
   t7.required="true";
   t7.id = "taga_qty"+indexcone;
   t7.name="taga_qty[]";
   t7.onkeyup=mycalc();
   t7.value="1";
   cell3.appendChild(t7);
   
   var cell7 = row.insertCell(4);
   var t8=document.createElement("input");
   t8.style="display: table-cell; width:80px;height:30px;";
   t8.type="text";
   t8.step="any";
   t8.className="METER";
   t8.id = "meter"+indexcone;
   t8.name="meter[]";
   t8.onkeyup=mycalc();
   cell7.appendChild(t8);
   
   var cell7 = row.insertCell(5);
   var t8=document.createElement("input");
   t8.style="display: table-cell; width:80px;height:30px;";
   t8.type="text";
   t8.step="any";
   t8.id = "gram_per_meter"+indexcone;
   t8.name="gram_per_meter[]";
   t8.value=$('#gram_per_meter').val();
   t8.onkeyup=mycalc();
   cell7.appendChild(t8);
   
   var cell7 = row.insertCell(6);
   var t8=document.createElement("input");
   t8.style="display: table-cell; width:80px;height:30px;";
   t8.type="text";
   t8.step="any";
   t8.className="KG";
   t8.id = "kg"+indexcone;
   t8.name="kg[]";
   t8.readOnly=true;
   t8.value=$('#kg').val();
   t8.onkeyup=mycalc();
   cell7.appendChild(t8);
   
   var cell3 = row.insertCell(7);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;height:30px;";
   t3.type="number";
   t3.step="any";
   t3.required="true";
   t3.id = "item_rates"+indexcone;
   t3.name="item_rates[]";
   t3.value="0";
   if($('#is_opening').prop('checked')) 
   {t3.readOnly=false;}else{t3.readOnly=true;}
   cell3.appendChild(t3);
   
   var cell3 = row.insertCell(8);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;height:30px;";
   t3.type="number";
   t3.readOnly="true";
   t3.step="any";
   t3.required="true";
   t3.className="AMT";
   t3.id = "amounts"+indexcone;
   t3.name="amounts[]";
   t3.value="0";
   cell3.appendChild(t3);
   
   var cell4 = row.insertCell(9);
   var t4=document.createElement("input");
   t4.style="display: table-cell; width:80px;height:30px;";
   t4.type="number";
   t4.step="any";
   t4.required="true";
   t4.className="suplier_roll_no";
   t4.id = "suplier_roll_no"+indexcone;
   t4.name="suplier_roll_no[]";
   t4.value="";
   cell4.appendChild(t4);
   
   var cell7 = row.insertCell(10);
   var t7=document.createElement("input");
   t7.style="display: table-cell; width:80px;height:30px;";
   t7.type="text";
   t7.readOnly=true;
   t7.id = "track_code"+indexcone;
   t7.name="track_code[]";
   if($("#cp_id").val()==1)
   {
      ++PBarcode;
     t7.value='P'+PBarcode;
   }
   else
   {
      ++CBarcode;
      t7.value='I'+CBarcode;
   } 
   
   
   cell7.appendChild(t7);
   
   var cell7 = row.insertCell(11);
   cell7.innerHTML='<i class="fa fa-print" name="print" style="font-size:25px;" onclick="CalculateRowPrint(this);"></i>';
   
   
   var cell8=row.insertCell(12);
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.name = "print";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone();CalculateRowPrint(this);");
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
   mycalc();
   recalcIdcone();
   
   selselect();
      
   }
   
   
   
   function selselect()
   {
       setTimeout(
    function() 
    {
   
    $("#footable_2 tr td  select[name='item_code[]']").each(function() {
   
       $(this).closest("tr").find('select[name="item_code[]"]').select2();
     $(this).closest("tr").find('select[name="part_id[]"]').select2();
   
      });
   }, 2000);
   }
   
   
     $(document).on("keyup", 'input[name^="meter[]"],input[name^="item_rates[]"]', function (event) {
          CalculateRow($(this).closest("tr"));
         });
      function CalculateRow(row)
      {
          var item_qtys=+row.find('input[name^="meter[]"]').val();
          var item_rates=+row.find('input[name^="item_rates[]"]').val();
          var amount=(parseFloat(item_qtys)*parseFloat(item_rates)).toFixed();
          row.find('input[name^="amounts[]"]').val(amount);
          mycalc();
      }
   
   
   function mycalc()
   {  
   document.getElementById("total_taga_qty").value =document.getElementById('cntrr').value;
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('METER');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_meter").value = sum1.toFixed(2);
   
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('KG');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_kg").value = sum1.toFixed(2);
   
   
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
   
    $("table.footable_2").on('keyup', 'input[name^="gram_per_meter[]"]', function (event)   
   { 
           
      var row=$(this).closest("tr");
      var gram_per_meter=parseFloat(+row.find('input[name^="gram_per_meter"]').val());
      var meter=parseFloat(+row.find('input[name^="meter"]').val());
     var kg=parseFloat(meter*gram_per_meter).toFixed(2);
      row.find('input[name^="kg[]"]').val(kg);
      
      });
   
   
   function deleteRowcone(btn) {
   if(document.getElementById('cntrr').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
   mycalc();
   recalcIdcone();
   
   if($("#cntrr").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
   
   }
   }
   
   
   
   function recalcIdcone(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   
   
   $(document).ready(function(){
   
   
   var po_code=document.getElementById('po_code').value;  
   
   
   if(po_code !="" && po_code!=0)
   {
   
   getDetails(po_code);
   
   }
   
   }); 
   
   function gettable(po_code){
   
    //alert(pur_code);
   
   $.ajax({
   type:"GET",
   url:"{{ route('getPo') }}",
   //dataType:"json",
   data:{po_code:po_code},
   success:function(response){
   console.log(response);  
      $("#item_code").html(response.html);
   
   }
   });
   }
   
   
   function getDetails(po_code){
   
   $.ajax({
   type:"GET",
   url:"{{ route('getPoMasterDetail') }}",
   //dataType:"json",
   data:{po_code:po_code},
   success:function(response){
   console.log(response);
   
   $("#Ac_code").val(response[0].Ac_code);
   $("#invoice_no").val(response[0].supplierRef);
   $("#invoice_date").val(response[0].pur_date);
   $("#po_type_id").val(response[0].po_type_id);
   $("#in_narration").val(response[0].narration);
   
   gettable(po_code);
   
   
   document.getElementById('Ac_code').disabled =true;
   document.getElementById('po_type_id').disabled=true;
   
   
   }
   });
   } 
</script>
<!-- end row -->
@endsection