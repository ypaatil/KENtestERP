@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <form action="{{route('FabricCutting.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <span style="float:right;"><button type="submit" class="btn btn-primary w-md" id="Submit" disabled onclick="EnableFields(); mycalc();">Submit</button> <a href="{{ Route('FabricCutting.index') }}" class="btn btn-warning w-md">Cancel</a></span>
               <h4 class="card-title mb-4">Cutting Task: <label class="form-label" id="lbl_lot_no"></label></h4>
               </br>
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
                        <label for="formrow-inputState" class="form-label">Table</label>
                        <input type="hidden" name="cu_date" class="form-control" id="cu_date" value="{{date('Y-m-d')}}">
                        <input type="hidden" name="lot_no" class="form-control" id="lot_no" value="" >
                        @foreach($counter_number as  $row)
                        <input type="hidden" name="cu_code" class="form-control" id="task_id" value="{{ 'CU'.'-'.$row->tr_no }}">
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                        @endforeach
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                        <select name="table_id" class="form-select" id="table_id" required onchange="getTaskData(this.value);" >
                           <option value="">Table</option>
                           @foreach($TableList as  $row)
                           {
                           <option value="{{ $row->table_id }}">{{ $row->table_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Task List</label>
                        <select name="table_task_code" class="form-select select2" id="table_task_code" required onchange="getCheckMasterData(this.value);">
                           <option value="">--Task--</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">VPO Code</label> 
                        <input type="text" name="vpo_code" class="form-control" id="vpo_code" value="" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Sales Order No</label> 
                        <input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Vendor</label>
                        <select name="vendorId" class="form-control" id="vendorId" required  onchange="getVendorPO(this.value);">
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
                        <input type="text" name="style_no" class="form-control" id="style_no" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="table_avg" class="form-label">Table Average</label>
                        <input type="text" name="table_avg" class="form-control" id="table_avg" value="" readonly required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="track_code" class="form-label">Scan Barcode</label>
                        <input type="text" name="track_code" class="form-control" id="track_code" value=""   onfocusout="getCheckingFabricdata(1);" >
                        <input type="hidden" name="item_code" class="form-control" id="item_code" value="0">
                        <input type="hidden" name="width" class="form-control" id="width" value="0" >
                        <input type="hidden" name="meter" class="form-control" id="meter" value="0" >
                        <input type="hidden" name="layers" class="form-control" id="layers" value="0" >
                     </div>
                  </div>
                  <div class="col-md-5">
                     <div class="mb-3">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <tbody>
                                <tr>
                                     <th></th>
                                     <th class="text-center">Date</th>
                                     <th class="text-center">Start Time</th>
                                     <th class="text-center">End Time</th> 
                                </tr>
                                <tr>
                                     <td><b>Layer</b></td>
                                     <td><input type="date" name="layer_date" value="" class="form-control" id="layer_date"/></td> 
                                     <td><input type="time" name="layer_start_time" value="" class="form-control"  id="layer_start_time"/></td> 
                                     <td><input type="time" name="layer_end_time" value="" class="form-control"  id="layer_end_time"/></td> 
                                </tr>
                                <tr>
                                     <td><b>Cutting</b></td>
                                     <td><input type="date" name="cutting_date" value="" class="form-control"  id="cutting_date"/></td> 
                                     <td><input type="time" name="cutting_start_time" value="" class="form-control"  id="layer_start_time"/></td> 
                                     <td><input type="time" name="cutting_end_time" value="" class="form-control"  id="layer_end_time"/></td> 
                                </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div class="row">
                     <input type="number" value="1" name="cntrr2" id="cntrr2" readonly="" hidden="true"  />
                     <label   class="form-label"><b>2. Comsuption/Cut Piece/Damage Meter:</b></label>
                     <div class="table-wrap">
                        <div class="table-responsive">
                           <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                              <thead>
                                 <tr>
                                    <th>SrNo</th>
                                    <th>Track Code</th>
                                    <th>Item</th>
                                    <th>Width</th>
                                    <th>Meter</th>
                                    <th>Shade</th>
                                    <th>Layers</th>
                                    <th>Used Meter</th>
                                    <th>Balance</th>
                                    <th>Cut Piece Meter</th>
                                    <th>Actual Balance</th>
                                    <th>Damage Meter</th>
                                    <th>Short Meter</th>
                                    <th>Extra Meter</th>
                                    <th><i class="fas fa-trash"></i> </th>
                                 </tr>
                              </thead>
                              <tbody id="endData">
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <th>SrNo</th>
                                    <th>Track Code</th>
                                    <th>Item</th>
                                    <th>Width</th>
                                    <th>Meter</th>
                                    <th>Shade</th>
                                    <th>Layers</th>
                                    <th>Used Meter</th>
                                    <th>Balance</th>
                                    <th>Cut Piece Meter</th>
                                    <th>Actual Balance</th>
                                    <th>Damage Meter</th>
                                    <th>Short Meter</th>
                                    <th>Extra Meter</th>
                                    <th><i class="fas fa-trash"></i> </th>
                                 </tr>
                              </tfoot>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                     <label  class="form-label"><b>1. Size/Qty:</b></label>
                     <div class="table-wrap">
                        <div class="table-responsive">
                           <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                              <thead>
                                 <tr>
                                    <th>SrNo</th>
                                    <th>Track Code</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Ratio</th>
                                    <th>Layers</th>
                                    <th>Qty</th>
                                 </tr>
                              </thead>
                              <tbody id="ratioData">
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <th>SrNo</th>
                                    <th>Track Code</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Ratio</th>
                                    <th>Layers</th>
                                    <th>Qty</th>
                                 </tr>
                              </tfoot>
                           </table>
                        </div>
                     </div>
                  </div>
                  <!-- end row -->
                  <div class="row">
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_qty" class="form-label">Total Qty</label>
                           <input type="text" name="total_pieces" class="form-control" id="total_qty" value="" required readOnly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_layers" class="form-label">Total Layers</label>
                           <input type="text" name="total_layers" class="form-control" id="total_layers" value="" required readOnly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_used_meter" class="form-label">Total Used Meter</label>
                           <input type="text" name="total_used_meter" class="form-control" id="total_used_meter" value="" required readOnly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_cutpiece_meter" class="form-label">Total CutPiece</label>
                           <input type="text" name="total_cutpiece_meter" class="form-control" id="total_cutpiece_meter" value="" required readOnly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_actual_balance" class="form-label">Total Actual Balance</label>
                           <input type="text" name="total_actual_balance" class="form-control" id="total_actual_balance" value="" required readOnly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_damage_meter" class="form-label">Total Damage</label>
                           <input type="text" name="total_damage_meter" class="form-control" id="total_damage_meter" value="" required readOnly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_short_meter" class="form-label">Total Short</label>
                           <input type="text" name="total_short_meter" class="form-control" id="total_short_meter" value="" required readOnly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_extra_meter" class="form-label">Total Extra</label>
                           <input type="text" name="total_extra_meter" class="form-control" id="total_extra_meter" value="" required readOnly>
                        </div>
                     </div>
                     <div class="col-sm-8">
                        <label for="formrow-inputState" class="form-label">Narration</label>
                        <div class="mb-3">
                           <input type="text" name="narration" class="form-control" id="narration"  value="" />
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <label for="formrow-inputState" class="form-label"></label>
                     <div class="form-group">
                        <button type="submit" class="btn btn-primary w-md" id="Submit1" disabled onclick="EnableFields(); mycalc();">Submit</button>
                        <a href="{{ Route('FabricCutting.index') }}" class="btn btn-warning w-md">Cancel</a>
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

  function calculateShortMeter(row)
  {
      var meters = $(row).parent().parent('tr').find('input[name="meters[]"]').val();
      var used_meters = $(row).parent().parent('tr').find('input[name="used_meters[]"]').val();
      var actual_balances = $(row).parent().parent('tr').find('input[name="actual_balances[]"]').val();
      
      var short_meter = meters - used_meters - actual_balances;
      
      $(row).parent().parent('tr').find('input[name="short_meters[]"]').val(short_meter.toFixed(2));
  }
  
  $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit1').prop('disabled', true);
        }); 
    });
   $('body').on('keydown', 'input, select, textarea', function(e) {
   var self = $(this)
   , form = self.parents('form:eq(0)')
   , focusable
   , next
   ;
   if (e.keyCode == 13) {
       
     
    var x = document.getElementById("track_code");
    
    
       if(document.getElementById('track_code').value!='' && document.hasFocus() )
       {
           
           getCheckingFabricdata(1);
            
       } else{
       
   focusable = form.find('input,a,select,button,textarea').filter(':visible');
   next = focusable.eq(focusable.index(this)+1);
   if (next.length) {
   next.focus();
   } else {
   form.submit();
   }
   return false;
   }
   }
   });
   //----------Over---------------------
   $("div.table-wrap").on("keyup",'input[name^="meters[]"]', function (event) {
   mycalc();
   });
   
   
   function EnableFields()
   {
                
                document.getElementById('vpo_code').disabled=false;
                document.getElementById('style_description').disabled=false;
                document.getElementById('style_no').disabled=false;
                
                  $("select").prop('disabled', false);
   }
   
   
   
   
    
    function getTaskData(val) 
   { 
       $.ajax({
       type: "GET",
       url: "{{ route('TaskList') }}",
       data:'table_id='+val,
       success: function(data){
       $("#table_task_code").html(data.html);
       }
       });
   }
    
    // Main Table for size wise Qty
    function getDetails(Action12)
   {  
       var table_avg=$("#table_avg").val();
       var job_code=$("#job_code").val();
       var meter=$("#meter").val();
       var table_id=$("#table_id").val();
       var layers=$("#layers").val();
       var track_code=$("#track_code").val();
       var table_task_code=$("#table_task_code").val();
       var vpo_code=$("#vpo_code").val();
       // alert("table_task_code:"+table_task_code);
       var next=0;
      // var length=$("#footable_2 tr").length;
   
       //var table = $("#footable_2 table tbody");
     //  table.find('tbody.ratioData').each(function() {
        $("#footable_2 tbody tr").each(function() {
       var thisRow = $(this);
       var match = thisRow.find('input[name^="track_codes[]"]').val();
       // note the `==` operator &
       if(match == track_code ){next=2;}
       
       });
    
    
     if(next==0){next=1;}else if(next==2 && Action12==2){next=1;}
   
        if(next==2)
        {
   
           alert('Cutting of this Roll Already Done. Check Table Below..!') ;
   
        }
       else if(Action12==1 && next==1)
               {       
                       layers=0;
                       $.ajax({
                       type: "GET",
                       url: "{{ route('RatioList') }}",
                       data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code,'vpo_code':vpo_code},
                       success: function(response)
                       {
                      
                           if(response.isExist > 0)
                           {
                               $("#ratioData").append(response.html);
                               recalcIdcone(); recalcIdcone2();
                           }
                           else
                           {
                               alert('The scanned track code (Barcode No.) is not available on the delivery challan. Please scan the available track code on the delivery challan against the CPO number.');
                           }
                       }
                       });
               }
               else if(Action12==2 && next==1)
               {
                   
                       $.ajax({
                       type: "GET",
                       url: "{{ route('RatioList') }}",
                       data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code,'vpo_code':vpo_code},
                       success: function(response)
                       {
                           if(response.isExist > 0)
                           {
                               $("#ratioData").append(response.html);
                               recalcIdcone(); recalcIdcone2();
                           }
                           else
                           {
                               alert('The scanned track code (Barcode No.) is not available on the delivery challan. Please scan the available track code on the delivery challan against the CPO number.');
                           }
                       }
                       });
               }
   
        
   }
   
   // Main Table for Cut Piece, Damage, Used Meter
   function getEndDataDetails(Action12)
   {  
       var table_avg=$("#table_avg").val();
       var job_code=$("#job_code").val();
        var meter=$("#meter").val();
       // alert(meter);
       var table_id=$("#table_id").val();
        var layers=$("#layers").val();
       var track_code=$("#track_code").val();
       var table_task_code=$("#table_task_code").val();
       var vpo_code = $("#vpo_code").val();
        //alert(meter+",po_code:"+po_code+",table_id:"+table_id);
       var next=0;
      // var length=$("#footable_2 tr").length;
   
       //var table = $("#footable_2 table tbody");
     //  table.find('tbody.ratioData').each(function() {
        $("#footable_3 tbody tr").each(function() {
       var thisRow = $(this);
       var match = thisRow.find('input[name^="track_codess[]"]').val();
       // note the `==` operator &
       if(match == track_code ){next=2;}
       
       });
    
    
     if(next==0){next=1;}else if(next==2 && Action12==2){next=1;}
   
        if(next==2)
        {
   
           alert('Cutting of this Roll Already Done. Check Table Below..!') ;
   
        }
       else if(Action12==1 && next==1)
               {       
                       layers=0;
                       $.ajax({
                       type: "GET",
                       url: "{{ route('EndDataList') }}",
                       data:{job_code:'job_code','meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code,'vpo_code':vpo_code},
                       success: function(response) 
                       { 
                           if(response.isExist > 0)
                           {
                               $("#endData").append(response.html);
                               recalcIdcone(); recalcIdcone2();
                           }
                           else
                           {
                               alert('The scanned track code (Barcode No.) is not available on the delivery challan. Please scan the available track code on the delivery challan against the CPO number.');
                           }
                       }
                       });
               }
               else if(Action12==2 && next==1)
               {
                   
                       $.ajax({
                       type: "GET",
                       url: "{{ route('EndDataList') }}",
                       data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers ,'task_id':table_task_code,'vpo_code':vpo_code},
                       success: function(response)
                       {  
                           if(response.isExist > 0)
                           {
                               $("#endData").append(response.html);
                               recalcIdcone(); recalcIdcone2();
                           }
                           else
                           {
                               alert('The scanned track code (Barcode No.) is not available on the delivery challan. Please scan the available track code on the delivery challan against the CPO number.');
                           }
                       }
                       });
               }
   
        
        $("#track_code").val('');
        
   }
   
       function getCheckMasterData(table_task_code)
       { 
           $('#table_task_code').attr('disabled',true);
           $.ajax({
                   type: "GET",
                   dataType:"json",
                   url: "{{ route('CheckingMasterList') }}",
                   //data:'table_id='+table_id,
                   data:{table_task_code:table_task_code},
                   success: function(data){
                       
                   console.log(data);     
                     $("#table_avg").val(data[0]['table_avg']);
                   $("#lot_no").val(data[0]['lot_no']);
                   document.getElementById('lbl_lot_no').innerHTML=data[0]['lot_no'];
                  $("#vendorId").val(data[0]['vendorId']);
                   $("#season_id").val(data[0]['season_id']);
                   $("#Ac_code").val(data[0]['Ac_code']);
                   $("#mainstyle_id").val(data[0]['mainstyle_id']);
                   $("#substyle_id").val(data[0]['substyle_id']);
                   $("#style_no").val(data[0]['style_no']);
                   $("#fg_id").val(data[0]['fg_id']);
                   $("#vpo_code").val(data[0]['vpo_code']);
                   $("#sales_order_no").val(data[0]['sales_order_no']);
                   $("#style_description").val(data[0]['style_description']);
                    document.getElementById('mainstyle_id').disabled=true;
                    document.getElementById('substyle_id').disabled=true;
                    document.getElementById('fg_id').disabled=true;
                    document.getElementById('style_description').disabled=true;
                    document.getElementById('style_no').disabled=true;
                    document.getElementById('vendorId').disabled=true;
                 
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
   document.getElementById("total_qty").value = sum1.toFixed(2);
   
   if(sum1.toFixed(2)>0)
   {
      document.getElementById("Submit").disabled=false;
      document.getElementById("Submit1").disabled=false;
   }
   else if(sum1.toFixed(2)==0)
   {
       document.getElementById("Submit").disabled=true;
       document.getElementById("Submit1").disabled=true;
   }
   
   
   
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('Layers');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_layers").value = sum1.toFixed(2);
   
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('UMETER');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_used_meter").value = sum1.toFixed(2);
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('cPiece');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_cutpiece_meter").value = sum1.toFixed(2);
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('aBalance');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_actual_balance").value = sum1.toFixed(2);
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('dPiece');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_damage_meter").value = sum1.toFixed(2);
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('SPiece');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_short_meter").value = sum1.toFixed(2);
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('EPiece');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_extra_meter").value = sum1.toFixed(2);
     
   
   }
   
   // 
    
    $("table.footable_3").on("change", 'input[name^="layerss[]"]', function (event) {
           CalculateLayers($(this).closest("tr"));
       });
   	
   
   	function CalculateLayers(row)
   	{ 
           var layerss=+row.find('input[name^="layerss[]"]').val();
           var track_code=row.find('input[name^="track_codess[]"]').val();
           //alert(track_code);
           var meter=+row.find('input[name^="layerss[]"]').val();
           delete_Row2(track_code); deleteEndDataRow2(track_code);getCheckingFabricdata2(2,track_code,meter,layerss);
             recalcIdcone(); recalcIdcone2();
       }
    
     function getDetails2(Action12,track_code,meter,layers)
   {  
       
       // This function is for change layer in consumption table and get data
       
       var table_avg=$("#table_avg").val();
       var job_code=$("#job_code").val(); 
       var vpo_code = $("#vpo_code").val();
       var table_id=$("#table_id").val();
     
       var table_task_code=$("#table_task_code").val();
       // alert("table_task_code:"+table_task_code);
       var next=0;
      // var length=$("#footable_2 tr").length;
   
       //var table = $("#footable_2 table tbody");
     //  table.find('tbody.ratioData').each(function() {
        $("#footable_2 tbody tr").each(function() {
       var thisRow = $(this);
       var match = thisRow.find('input[name^="track_codes[]"]').val();
       // note the `==` operator &
       if(match == track_code ){next=2;}
       
       });
    
    
     if(next==0){next=1;}else if(next==2 && Action12==2){next=1;}
   
        if(next==2)
        {
   
           alert('Cutting of this Roll Already Done. Check Table Below..!') ;
   
        }
       else if(Action12==1 && next==1)
               {       
                       layers=0;
                       $.ajax({
                       type: "GET",
                       url: "{{ route('RatioList') }}",
                       data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code,'vpo_code':vpo_code},
                       success: function(response)
                       {
                           if(response.isExist > 0)
                           {
                               $("#ratioData").append(response.html);
                               recalcIdcone(); recalcIdcone2();
                           }
                           else
                           {
                               alert('The scanned track code (Barcode No.) is not available on the delivery challan. Please scan the available track code on the delivery challan against the CPO number.');
                           }
                      
                       }
                       });
               }
               else if(Action12==2 && next==1)
               {
                   
                       $.ajax({
                       type: "GET",
                       url: "{{ route('RatioList') }}",
                       data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code,'vpo_code':vpo_code},
                       success: function(response)
                       {
                          if(response.isExist > 0)
                           {
                               $("#ratioData").append(response.html);
                               recalcIdcone(); recalcIdcone2();
                           }
                           else
                           {
                               alert('The scanned track code (Barcode No.) is not available on the delivery challan. Please scan the available track code on the delivery challan against the CPO number.');
                           }
                       }
                       });
               }
   
        
   }
   
    
    function getEndDataDetails2(Action12,track_code,meter,layers)
   {  
       var table_avg=$("#table_avg").val();
     //  var po_code=$("#po_code").val();
        
       var table_id=$("#table_id").val();
       var vpo_code = $("#vpo_code").val();
      
        //alert(meter+",po_code:"+po_code+",table_id:"+table_id);
       var next=0;
      // var length=$("#footable_2 tr").length;
   
       //var table = $("#footable_2 table tbody");
     //  table.find('tbody.ratioData').each(function() {
        $("#footable_3 tbody tr").each(function() {
       var thisRow = $(this);
       var match = thisRow.find('input[name^="track_codess[]"]').val();
       // note the `==` operator &
       if(match == track_code ){next=2;}
       
       });
    
    
     if(next==0){next=1;}else if(next==2 && Action12==2){next=1;}
   
        if(next==2)
        {
   
           alert('Cutting of this Roll Already Done. Check Table Below..!') ;
   
        }
       else if(Action12==1 && next==1)
               {       
                       layers=0;
                       $.ajax({
                           type: "GET",
                           url: "{{ route('EndDataList') }}",
                           data:{'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'vpo_code':vpo_code},
                           success: function(response)
                           { 
                          
                               if(response.isExist > 0)
                               {
                                   $("#endData").append(response.html);
                                   recalcIdcone();
                                   recalcIdcone2();
                               }
                               else
                               {
                                   alert('The scanned track code (Barcode No.) is not available on the delivery challan. Please scan the available track code on the delivery challan against the CPO number.');
                               }
                           }
                       });
               }
               else if(Action12==2 && next==1)
               {
                   
                       $.ajax({
                       type: "GET",
                       url: "{{ route('EndDataList') }}",
                       data:{'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'vpo_code':vpo_code},
                       success: function(response){
                           if(response.isExist > 0)
                           {
                               $("#endData").append(response.html);
                               recalcIdcone();
                               recalcIdcone2();
                           }
                           else
                           {
                               alert('The scanned track code (Barcode No.) is not available on the delivery challan. Please scan the available track code on the delivery challan against the CPO number.');
                           }
                       }
                       });
               }
   
        
        $("#track_code").val('');
        
   }
    
    
    function getCheckingFabricdata2(Action12,track_code,meter,layers)
   {
      // This function is for change layer in consumption table and get data
        var table_avg=$("#table_avg").val();
       $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('CheckingFabricList') }}",
               data:{'track_code':track_code,'table_avg':table_avg},
               success: function(data){
                    getDetails2(Action12,track_code,meter,layers);
                    getEndDataDetails2(Action12,track_code,meter,layers);
                    setTimeout(function(){  mycalc();}, 2000);
                   
           }
           });
       }
    
    
    
    
    
     function getCheckingFabricdata(Action12)
   {
       // This function is for Scan Barcodeand get details
      
       var layers=$("#layers").val();
        var track_code=$("#track_code").val();
       var table_avg=$("#table_avg").val();
       $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('CheckingFabricList') }}",
               data:{'track_code':track_code,'table_avg':table_avg},
               success: function(data){
                   console.log(data);
               $("#item_code").val(data[0]['item_code']);
               $("#width").val(data[0]['width']);
               $("#meter").val(data[0]['meter']);
               if(Action12==1)
               {          
                    $("#layers").val(data[0]['Layers']);
               }
                
                    getDetails(Action12);
                    getEndDataDetails(Action12);
                    setTimeout(function(){  mycalc();}, 2000);
                   
           }
           });
       }
    
     function delete_Row2(track_code)  {
      //$("#footable_2 tr.thisRow").each(function() {
           $("#footable_2 tbody tr").each(function() {
           var thisRow = $(this);
           var match = thisRow.find('input[name^="track_codes[]"]').val();
           // note the `==` operator
           if(match == track_code) {
               thisRow.remove(); 
               // OR thisRow.remove();
           }
       });
   mycalc();
   }
    
    function deleteEndDataRow2(track_code)  {
       
       $("#footable_3 tbody tr").each(function() {
           var thisRow = $(this);
           var match = thisRow.find('input[name^="track_codess[]"]').val();
           // note the `==` operator
           if(match == track_code) {
               thisRow.remove(); 
               // OR thisRow.remove();
           }
           
          
           
       });
       
       
       
       mycalc();
       
   
   }
    
    
    function delete_Row(track_code)  {
     
       var track_code=$("#track_code").val();
       //$("#footable_2 tr.thisRow").each(function() {
           $("#footable_2 tbody tr").each(function() {
           var thisRow = $(this);
           var match = thisRow.find('input[name^="track_codes[]"]').val();
           // note the `==` operator
           if(match == track_code) {
               thisRow.remove(); 
               // OR thisRow.remove();
           }
       });
   
   }
    
    function deleteEndDataRow()  {
       
      var track_code=$("#track_code").val();
       //$("#footable_2 tr.thisRow").each(function() {
           $("#footable_3 tbody tr").each(function() {
           var thisRow = $(this);
           var match = thisRow.find('input[name^="track_codess[]"]').val();
           // note the `==` operator
           if(match == track_code) {
               thisRow.remove(); 
               // OR thisRow.remove();
           }
       });
   
   }
    
   $("table.footable_3").on("change", 'input[name^="cpiece_meters[]"],input[name^="dpiece_meters[]"],input[name^="bpiece_meters[]"],input[name^="short_meters[]"],input[name^="extra_meters[]"]', function (event) {
           CalculateRow($(this).closest("tr"));
       });
   	
   
   	function CalculateRow(row)
   	{ 
           var layerss=+row.find('input[name^="layerss[]"]').val();
           var table_avg=$('#table_avg').val();
           var meters=+row.find('input[name^="meters[]"]').val();
           var used_meters=+row.find('input[name^="used_meters[]"]').val();
   		var cpiece_meters=+row.find('input[name^="cpiece_meters[]"]').val();
           var dpiece_meters=+row.find('input[name^="dpiece_meters[]"]').val();
           var short_meters=+row.find('input[name^="short_meters[]"]').val();
           var extra_meters=+row.find('input[name^="extra_meters[]"]').val();
   		var bpiece_meters= meters-used_meters;
   // bal : 28.92  cut:2 
            
               var bm=(bpiece_meters-cpiece_meters-dpiece_meters- parseFloat(short_meters) + parseFloat(extra_meters)).toFixed(2);
               var cm=(bpiece_meters-bm-dpiece_meters- parseFloat(short_meters) + parseFloat(extra_meters)).toFixed(2);
               var dm=(bpiece_meters-bm-cm- parseFloat(short_meters) + parseFloat(extra_meters)).toFixed(2);
               row.find('input[name^="cpiece_meters[]"]').val(parseFloat(cm));
               row.find('input[name^="dpiece_meters[]"]').val(parseFloat(dm));
               if(bm>=0){row.find('input[name^="bpiece_meters[]"]').val(parseFloat(bm));}
               else{ alert('Balance Meter can not less than Zero..!!');}
       }
   
     
   function deleteRowcone(btn) {
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
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
    
    
   function recalcIdcone2(){
   $.each($("#footable_3 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   } 
    
   
</script>
<!-- end row -->
@endsection