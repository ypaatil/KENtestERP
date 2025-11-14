@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Summary GRN</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Summary GRN</li>
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
            <h4 class="card-title mb-4">Fabric Summary GRN</h4>
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
            @if(isset($FabricSummaryGRNMasterList)) 
            <form action="{{ route('FabricSummaryGRN.update',$FabricSummaryGRNMasterList->sr_no) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fsg_date" class="form-label">In Date</label>
                        <input type="date" name="fsg_date" class="form-control" id="fsg_date" value="{{ $FabricSummaryGRNMasterList->fsg_date }}" required>
                        <input type="hidden" name="fsg_code" class="form-control" id="fsg_code" value="{{ base64_encode($FabricSummaryGRNMasterList->fsg_code) }}">
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $FabricSummaryGRNMasterList->c_code }}">
                        <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $FabricSummaryGRNMasterList->created_at }}">  
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  
                   @php
                        $stockData = DB::select("select stock_association_for_fabric.*,item_master.item_name from stock_association_for_fabric 
                        INNER JOIN item_master ON item_master.item_code = stock_association_for_fabric.item_code
                        where tr_code ='".$FabricSummaryGRNMasterList->fsg_code."' GROUP BY stock_association_for_fabric.item_code,stock_association_for_fabric.sales_order_no,stock_association_for_fabric.bom_code"); 
                        
                        if(count($stockData) > 0)
                        {
                             $isclick = 1;
                        }
                        else
                        {
                             $isclick = 0;
                        }
                            
                      // $chkRecords = DB::table('fabric_checking_master')->where('po_code', $FabricSummaryGRNMasterList->po_code)->first(); 
                    @endphp
                  <div class="col-md-2">
                    <div class="mb-3">
                    <label for="chk_code" class="form-label">CHK Code</label>   
                    <select name="chk_code" class="form-select select2" id="chk_code" onchange="GetPOCodes();" disabled >
                    <option value="">--Select--</option>
                    @foreach($chkList as  $chks) 
                        <option value="{{ $chks->chk_code  }}"  {{ $chks->chk_code == $FabricSummaryGRNMasterList->chk_code ? 'selected="selected"' : '' }}  >{{ $chks->chk_code }}</option> 
                    @endforeach
                    </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">PO Code</label>   
                        <select name="po_code" class="form-select" id="po_code" onchange="getPODetails();" disabled>
                           <option value="">PO code</option>
                           @foreach($POList as  $rowpol)
                           {
                           <option value="{{ $rowpol->pur_code  }}"
                           {{ $rowpol->pur_code == $FabricSummaryGRNMasterList->po_code ? 'selected="selected"' : '' }} 
                           >{{ $rowpol->pur_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                        <input type="hidden" name="challan_no" id="challan_no" class="form-control" id="formrow-challan_no-input" value="{{$FabricSummaryGRNMasterList->challan_no}}"> 
                        <input type="hidden" name="challan_date" id="challan_date" class="form-control" id="formrow-challan_date-input" value="{{$FabricSummaryGRNMasterList->challan_date}}">  
                        <input type="hidden" name="invoice_no" id="invoice_no" class="form-control" value="{{ $FabricSummaryGRNMasterList->invoice_no }}" id="formrow-invoice_no-input">  
                        <input type="hidden" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="{{ $FabricSummaryGRNMasterList->invoice_date }}"> 
                     
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" id="supplier_id"  disabled>
                           <option value="">--Select Supplier--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $FabricSummaryGRNMasterList->supplier_id ? 'selected="selected"' : '' }}   >  
                           {{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>   
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">PO</label>
                        <select name="po_type_id" class="form-select" id="po_type_id" onchange="getPartyDetails();" disabled>
                           <option value="">Type</option>
                           @foreach($POTypeList as  $rowpo)
                           {
                           <option value="{{ $rowpo->po_type_id  }}"
                           {{ $rowpo->po_type_id == $FabricSummaryGRNMasterList->po_type_id ? 'selected="selected"' : '' }}      
                           >{{ $rowpo->po_type_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <input type="number" value="{{ count($FabricSummaryGRNDetails) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
               <div class="table-wrap">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                        <thead>
                           <tr>
                              <th>SrNo</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Item Description</th>
                              <th>UOM</th>
                              <th>Quantity</th>
                              <th>Add/Remove</th>
                           </tr>
                        </thead>
                        <tbody>
                           @if(count($FabricSummaryGRNDetails)>0)
                           @php 
                                $no=1; 
                                $total_amount = 0;
                           @endphp
                           @foreach($FabricSummaryGRNDetails as $row) 
                           @php
                                $total_amount = $row->total_qty * $row->item_rate;
                           
                           @endphp
                           <tr>
                              <td><input type="text" name="id" value="{{ $no }}" id="id"  style="width:50px;" readonly/></td>
                              <td>
                              
                              <span onclick="openmodal({{ isset($po_sr_no[0]->sr_no) ? $po_sr_no[0]->sr_no : 0 }},{{ $row->item_code }});" style="color:#556ee6; cursor: pointer;"> {{$row->item_code}}</span>
                              
                              </td>
                              <td>
                                 <select name="item_codes[]" class="select2" id="item_codes"  class="select2" style="width:200px;height:30px;" onchange="GetUnit(this);"  disabled>
                                    <option value="">--- Select Item ---</option>
                                    @foreach($itemlist as  $rowitem)
                                    {
                                    <option value="{{ $rowitem->item_code  }}"
                                    {{ $rowitem->item_code == $row->item_code ? 'selected="selected"' : '' }}
                                    >{{ $rowitem->item_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td><input   type="text"  value="{{ $row->item_description}}" style="width:250px;height:30px;"  readonly> </td>
                              <td>
                                 <select name="unit_ids[]"   id="unit_ids"   style="width:150px;height:30px;" disabled>
                                    <option value="">--- Select Unit ---</option>
                                    @foreach($unitlist as  $rowunit)
                                    {
                                    <option value="{{ $rowunit->unit_id  }}"
                                    {{ $rowunit->unit_id == $row->unit_id ? 'selected="selected"' : '' }}
                                    >{{ $rowunit->unit_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td><input type="number" step="any" class="QTY" name="item_qtys[]" value="{{ $row->item_qty }}" style="width:80px;height:30px;"  id="item_qty" readonly>
                                 <input type="hidden" name="hsn_codes[]"   value="{{ $row->hsn_code }}" id="hsn_codes" style="width:80px;height:30px;"  readonly/>
                                 <input type="hidden" name="item_rates[]"    value="{{ $row->item_rate }}" id="item_rates" style="width:80px;height:30px;"  readonly/>
                                 <input type="hidden" name="amounts[]" class="AMT"  value="{{$total_amount}}" id="amounts" style="width:80px;height:30px;"  readonly/>
                              </td>
                              <td>
                                  <button type="button" onclick=" mycalc();" class="Abutton btn btn-warning  btn-sm  pull-left">+</button> 
                                  <input type="button" class="btn btn-danger  btn-sm  pull-left" onclick="deleteRow(this);" value="X" />
                                  <button type="button" name="allocate[]"  onclick="stockAllocate(this);" item_code="{{$row->item_code}}" isClick="{{$isclick}}" is_opening="{{$row->is_opening}}" qty="{{$row->item_qty}}" bom_code="{{$row->bom_code}}" cat_id="{{$row->cat_id}}" class_id="{{$row->class_id}}"  class="btn btn-success pull-left">Allocate</button> 
                              </td>
                           </tr>
                            @endforeach
                           @endif
                        </tbody>
                     </table>
                  </div>
               </div>
                              <br/>
                <div class="table-wrap">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>BOM Code</th>
                              <th>Sales Order No</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Allocated Stock</th>
                           </tr>
                        </thead>
                        <tbody id="stock_allocate">
                            @php
                            
                                if(count($stockData) > 0)
                                {
                            @endphp
                            @foreach($stockData as $row)
                             @if($row->qty > 0)
                                <tr>
                                    <td><input type="text" name="stock_bom_code[]" value="{{$row->bom_code}}" class="form-control" style="width:100px;" readonly=""></td>
                                    <td><input type="text" name="sales_order_no[]" value="{{$row->sales_order_no}}" class="form-control" style="width:100px;" readonly=""></td>
                                    <td><input type="text" name="item_code[]" value="{{$row->item_code}}" class="form-control" style="width:100px;" readonly=""></td>
                                    <td><input type="text" name="item_name[]" value="{{$row->item_name}}" class="form-control" style="width:100px;" readonly=""></td>
                                    <td>
                                        <input type="text" name="allocate_qty[]" value="{{$row->qty}}" class="form-control allocate_qty" style="width:100px;" readonly="">
                                        <input type="hidden" name="cat_id[]" value="{{$row->cat_id}}" class="form-control" style="width:100px;" >
                                        <input type="hidden" name="class_id[]" value="{{$row->class_id}}"  class="form-control" style="width:100px;">
                                    </td>
                                </tr>
                             @endif
                            @endforeach
                            @php
                                }
                            @endphp
                        </tbody>
                     </table>
                  </div>
               </div> 
         <div class="row ml-3">
         <div class="col-md-2">
         <div class="mb-3">
         <label for="total_meter" class="form-label">Total Qty</label>
         <input type="number" readOnly step="0.01"  name="total_qty" class="form-control" id="total_qty" value="{{ $FabricSummaryGRNMasterList->total_qty }}" required>
         </div>
         </div> 
         
         <div class="col-md-2">
         <div class="mb-3">
         <label for="total_allocate_qty" class="form-label">Total Allocated Stock Qty</label>
         <input type="number" readOnly  class="form-control" id="total_allocate_qty" value="" >
         </div>
         </div> 
         
         <input type="hidden" name="transport_id" class="form-control" id="transport_id" value="{{ $FabricSummaryGRNMasterList->transport_id }}">
         <input type="hidden" name="freight_paid" class="form-control" id="freight_paid" value="{{ $FabricSummaryGRNMasterList->freight_paid }}">
 
         </div>
         <div class="col-sm-8 ml-3">
         <div class="mb-3">
         <label for="formrow-inputState" class="form-label">Narration</label>
         <input type="text" name="in_narration" class="form-control" id="in_narration"   value="{{ $FabricSummaryGRNMasterList->in_narration }}" />
         </div>
         </div>
         <div class="col-sm-6">
         <label for="formrow-inputState" class="form-label"></label>
         <div class="form-group">
         <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();"id="Submit">Submit</button>
         <a href="{{ Route('FabricSummaryGRN.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
        calculateAllocatedQty();
    });
    function calculateAllocatedQty()
    {
        var total_allocate_qty = 0;
        $(".allocate_qty").each(function()
        {
            total_allocate_qty += parseFloat($(this).val());
        });
         $("#total_allocate_qty").val(total_allocate_qty);
    }
    //GetPOCodes();
    function GetPOCodes()
    {
        var chk = $("#chk_code").val();
        $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('GetPoCodeFromChk') }}",
              data:{'chk_code':chk},
              success: function(data)
              { 
                  $("#po_code").html(data).change(); 
              }
        });
    }
    
    function getPoForFabricTable(po_code)
    {
        var chk_code = $('#chk_code').val();
        $.ajax({
            type:"GET",
            url:"{{ route('getPoForFabric') }}",
            //dataType:"json",
            data:{po_code:po_code},
            success:function(response)
            {
                console.log(response);  
                $("#fabricInward").html(response.html);
                
                var inputQtyData = $('input[name="item_qtys[]"]');
                var totalQty = 0;
                
                $.each(inputQtyData, function() {
                   totalQty += parseFloat($(this).val());
                });
                $("#total_qty").val(totalQty);
                console.log(inputQtyData);
            
            }
        });
    
    }

    function SetQtyToBtn(obj)
    { 
        var qty = $(obj).val();
        $(obj).parent().parent('tr').find('td button[name="allocate[]"]').attr('qty', qty);
    }
    
    function stockAllocate(obj)
    {
      var row1 = $(obj).attr('item_code');
      var row2 = $(obj).attr('qty');
      var row3 = $(obj).attr('bom_code');
      var row4 = $(obj).attr('cat_id');
      var row5 = $(obj).attr('class_id');
      var isClick = $(obj).attr('isClick');
      var is_opening = $(obj).attr('is_opening'); 
      var po_type_id = $("#po_type_id").val();
      
      if(isClick == 0)
      {
          $.ajax({
                  type: "GET",
                  dataType:"json",
                  url: "{{ route('stockAllocateForFabric') }}",
                  data:{'bom_code':row3,'item_code' : row1, 'item_qty': row2, 'cat_id':row4, 'class_id':row5,'po_type_id':po_type_id,'is_opening':is_opening}, 
                  success: function(data)
                  {
                        $("#stock_allocate").append(data.html);
                        $(obj).attr('isClick', '1');
                        calculateAllocatedQty();
                  }
            });
      }
      else
      {
          alert('Already stock allocated..!');
      }
      
      calculateAllocatedQty();
    }
    
   $(document).on("change", 'input[name^="item_qty[]"]', function (event) 
   {
       @php 
      // $user_type=Session::get('user_type'); if($user_type!=1){ 
           @endphp
       var value = $(this).val();
       
                var maxLength = parseFloat($(this).attr('max'));
                var minLength = parseFloat($(this).attr('min')); 
       if(value>maxLength){alert('Value can not be greater than '+maxLength);}
       if ((value !== '') ) {
           $(this).val(Math.max(Math.min(value, maxLength), minLength));
       }
       
       @php 
       
   //} 
   @endphp
   });
   
    
    
    function getRateFromPO(row)
    {
        var po_code=$('#po_code').val();
         var item_code = $(row).val();
       var row = $(row).closest('tr'); 
             
             
             
            $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('ItemRateFromPO') }}",
               data:{'po_code':po_code,item_code:item_code },
               success: function(data){
                    +row.find('input[name^="item_rates[]"]').val(data[0]['item_rate']);
                     
           }
           });
            
    }
    
    
     function getMinMaxPO(row)
    {
       var po_code=$('#po_code').val();
       var color_id = $(row).val();
       var row = $(row).closest('tr');
       
       var item_code= +row.find('select[name^="item_code[]"]').val();
            $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('ItemMinMaxFromPO') }}",
               data:{'po_code':btoa(po_code),item_code:item_code,color_id:color_id},
               success: function(data){
                   
                   var max= data[0]['item_qty'].toFixed(2);
               row.find('input[name^="item_qty[]"]').attr({"max" :max,"min" : 0});
           }
           });
            
    }
    
     
    function enable(opening)
   {
       
      
       if(opening.checked==true)
       { alert();
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
           
         //  alert(track_code);
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
   
   function EnableFields()
   {
        $("select").prop('disabled', false);
   }
   
    
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
                   $("#supplier_id").val(data[0]['Ac_code']);
                  
           }
           });
           
           
          
    
   }
   
   //  var PBarcode=$("#PBarcode").val();
   // var CBarcode=$("#CBarcode").val();
   
   
   // function UpdateBarcode()
   // {
   //      $("#PBarcode").val(PBarcode);
   //       $("#CBarcode").val(CBarcode);
   // }
   
   // function serBarocode()
   // {
   //             if($("#cp_id").val()==1)
   //             {
                        
   //                     ++PBarcode;
   //                     $("#track_code").val('P'.concat(PBarcode.toString()));
   //                   //alert($("#track_code").val());
   //             }
   //             else if($("#cp_id").val()==2)
   //             {       var CBar='';
   //                     CBar='I' + parseInt(++CBarcode);
   //                      $("#track_code").val(CBar);
   //             }
   // }
   
   
   // $("table.footable_2").on("keyup", 'input[name^="gram_per_meter[]"],input[name^="meter[]"]', function (event) {
   //         CalculateRow($(this).closest("tr"));
           
   //     });
   	 	
   // 	function CalculateRow(row)
   // 	{ 
   // 		var gram_per_meter=+row.find('input[name^="gram_per_meter[]"]').val();
   //         var meter=+row.find('input[name^="meter[]"]').val();
   // 	 	var kg=parseFloat(meter * gram_per_meter).toFixed(2);
   //         row.find('input[name^="kg[]"]').val(kg);
   // 		mycalc();
   // }
   
   
   function getDetails(po_code){
   
   $.ajax({
   type:"GET",
   url:"{{ route('getPoMasterDetail') }}",
   //dataType:"json",
   data:{po_code:po_code},
   success:function(response){
   console.log(response);
   
   $("#supplier_id").val(response[0].Ac_code);
   $("#invoice_no").val(response[0].supplierRef);
   $("#invoice_date").val(response[0].pur_date);
   $("#po_type_id").val(response[0].po_type_id);
   $("#in_narration").val(response[0].narration);
   
    gettable(po_code);
   
   
   document.getElementById('supplier_id').disabled =true;
   document.getElementById('po_type_id').disabled=true;
   
   
    $.ajax({
           dataType: "json",
       url: "{{ route('GetPOItemList') }}",
       data:{'po_code':btoa(po_code)},
       success: function(data){
         $("#item_code").html(data.html);
        
      }
       });
   
   
   
   $.ajax({
           dataType: "json",
       url: "{{ route('GetPOColorList') }}",
       data:{'po_code':btoa(po_code)},
       success: function(data){
         $("#color_id").html(data.html);
        
      }
       });
   
   
   
   
   }
   });
   } 
   
   
   var indexcone = {{ count($FabricSummaryGRNDetails) }};
   //var indexcone = 2;
    
    
   function insertcone(){
   
   $("#item_code").select2("destroy");
   $("#color_id").select2("destroy");
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
     
   
   var cell5 = row.insertCell(1);
   var t5=document.createElement("select");
   var x = $("#item_code"),
   y = x.clone();
   y.attr("id","item_code");
   y.attr("name","item_code[]");
   y.val(''); 
   y.width(200);
   y.height(30);
   y.appendTo(cell5);
    
    
    
    
   var cell7 = row.insertCell(2);
   var t8=document.createElement("input");
   t8.style="display: table-cell; width:80px;height:30px;";
   t8.type="text";
   t8.step="any";
   t8.className="METER";
   t8.id = "item_qty"+indexcone;
   t8.name="item_qty[]";
   t8.min="0";
   t8.max="0";
   t8.setAttribute("onChange", "mycalc();");
   t8.onkeyup=mycalc();
   cell7.appendChild(t8);
   
     
   var cell3 = row.insertCell(3);
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
    
    
   var cell8=row.insertCell(4);
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
      $(this).closest("tr").find('select[name="color_id[]"]').select2();
   
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
    
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('METER');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_qty").value = sum1.toFixed(2);
   
    
    
    
   }
   
   
   
   function deleteRowcone(btn) {
   if(document.getElementById('cntrr').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
   
   recalcIdcone();
   mycalc();
   if($("#cntrr").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
    
   }
   }
   
     $("table.footable_2").on('keyup', 'input[name^="gram_per_meter[]"]', function (event)   
   	{ 
            
       var row=$(this).closest("tr");
       var gram_per_meter=parseFloat(+row.find('input[name^="gram_per_meter"]').val());
       var meter=parseFloat(+row.find('input[name^="meter"]').val());
      var kg=parseFloat(meter*gram_per_meter).toFixed(2);
       row.find('input[name^="kg[]"]').val(kg);
       
       });
   
   function recalcIdcone(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
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
    url: "{{ route('GetCompareFabricPOInwardData') }}",
    data: { sr_no: po_code, item_code: item_code },
    success: function(data){
    $("#InwardData").html(data.html);
    }
    });
}
    
    
    
    
</script>


<div class="modal fade" id="modalFormSize" role="dialog">
<div class="modal-dialog" style="margin: 1.75rem 19rem;">
<div class="modal-content" style="width: 900px;">
<!-- Modal Body -->
<div class="modal-body">
<p class="statusMsg"></p>
 
<div class="seprator-block"></div>
<h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-calendar-note mr-10"></i>Fabric PO Vs GRN</h6>
<hr class="light-grey-hr"/>

<div class="row">


<div id="InwardData"></div>
 
</div>

 


<!-- Modal Footer -->
<div class="modal-footer">
<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closemodal();">Close</button>
 
</div>
</div>
</div>
</div>



<!-- end row -->
@endsection