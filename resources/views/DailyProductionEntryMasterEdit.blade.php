@extends('layouts.master') 
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<style>
select {
  /* Remove default arrow in Firefox */
  -moz-appearance: none;
  /* Remove default arrow in other browsers */
  appearance: none;
  /* Add some padding to make room for potential custom styling */
  padding-right: 20px;
  /* Optional: Add a background to hide any remaining artifacts */
  background: transparent;
}

/* Optional: Style the dropdown container to hide potential artifacts */
select::-ms-expand {
  display: none;
}
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">DAILY PRODUCTION ENTRY</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">DAILY PRODUCTION ENTRY</li>
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
         <h4 class="card-title mb-4">DAILY PRODUCTION ENTRY</h4>
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
         @if(isset($DailyProductionEntryList))
          <form action="{{ route('DailyProductionEntry.update',$DailyProductionEntryList) }}" method="POST" enctype="multipart/form-data" id="frmProduction">  
            <input type="hidden" name="dailyProductionEntryId" class="form-control" id="dailyProductionEntryId" value="{{ $DailyProductionEntryList->dailyProductionEntryId}}"> 
            <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
           @method('put')
           @csrf   
            <div class="row">
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-dailyProductionEntryDate" class="form-label">Date</label>
                      <input type="date" name="dailyProductionEntryDate" class="form-control" id="formrow-dailyProductionEntryDate-input" value="{{$DailyProductionEntryList->dailyProductionEntryDate}}" required>  
                  </div>
               </div>   
               <div class="col-md-5">
                  <div class="mb-3">
                     <label for="formrow-employeeCode" class="form-label">Employee</label>
                     <input type="hidden" name="employeeName" class="form-control" id="employeeName" value="{{$DailyProductionEntryList->employeeName}}">  
                     <select name="employeeCode" class="form-select select2" id="employeeCode" required>
                         @php  $chunkSize = 200; @endphp
                        <option value="">--Select--</option>
                         @foreach (array_chunk($employeeMap, $chunkSize) as $chunk) 
                         @foreach ($chunk as $row) 
                        <option value="{{ $row['employeeCode'] }}" {{ $row['employeeCode']  == $DailyProductionEntryList->employeeCode ? 'selected="selected"' : '' }} >({{$row['employeeCode'] }}) {{  $row['employeeName']  }} - ({{ $row['sub_company_name']  }})</option>
                        @endforeach
                        @endforeach
                     </select>
                  </div>
               </div> 
               <div class="col-md-12 table-responsive">
                  <table id="opertionTbl" class="table table-bordered nowrap w-100">
                   <thead>
                       <tr>    
                           <th nowrap>Sr No.</th>
                           <th nowrap>Remove</th>
                           <th nowrap>Add</th>
                           <th nowrap>Sales Order No</th>
                           <th nowrap>Garment Color</th>  
                           <th nowrap>Operartion Name</th>
                           <th nowrap>Bundle No</th>
                           <th nowrap>Stiching Qty</th>
                           <th nowrap>Lot No</th>
                           <th nowrap>Slip No.</th>
                           <th nowrap>Size</th>
                           <th nowrap>Line No</th>
                           <th nowrap>Bundle Track Code</th>
                       </tr>
                   </thead>
                   <tbody>
                       @php
                            $srno = 1;
                       @endphp
                       @foreach($DailyProductionEntryDetailList as $details)
                       @php
                                   
                                 
                                            $balanceCutData = DB::SELECT("SELECT ((SELECT ifnull((cut_panel_issue_qty),0) FROM cutting_entry_details 
                                            INNER JOIN cutting_entry_master ON cutting_entry_master.cuttingEntryId = cutting_entry_details.cuttingEntryId 
                                            WHERE bundleNo='".$details->bundleNo."' AND cutting_entry_master.sales_order_no='".$details->sales_order_no."' AND  cutting_entry_master.vendorId='".Session::get('vendorId')."'   GROUP BY bundleNo) 
                                            - (SELECT ifnull(SUM(stiching_qty),0) FROM daily_production_entry_details 
                                             INNER JOIN daily_production_entry ON daily_production_entry.dailyProductionEntryId = daily_production_entry_details.dailyProductionEntryId
                                             WHERE daily_production_entry_details.bundleNo='".$details->bundleNo."'   AND 
                                             daily_production_entry_details.sales_order_no='".$details->sales_order_no."' AND  daily_production_entry.vendorId='".Session::get('vendorId')."' )) as balanceQty,
                                             (SELECT ifnull((cut_panel_issue_qty),0) FROM cutting_entry_details 
                                            INNER JOIN cutting_entry_master ON cutting_entry_master.cuttingEntryId = cutting_entry_details.cuttingEntryId 
                                            WHERE bundleNo='".$details->bundleNo."' AND cutting_entry_master.sales_order_no='".$details->sales_order_no."' AND cutting_entry_details.color_id='".$details->color_id."'  GROUP BY bundleNo) as CuttingQty
                                             ");                    
                                             
                                             
                  

                            $balanceQty = isset($balanceCutData[0]->balanceQty) ? $balanceCutData[0]->balanceQty : 0;
                            $CuttingQty = isset($balanceCutData[0]->CuttingQty) ? $balanceCutData[0]->CuttingQty : 0;
                 
                             $OperationNameList = DB::table('ob_details')->select('ob_details.operation_id','ob_details.operation_name')
                            ->join('assigned_to_orders', 'assigned_to_orders.mainstyle_id_operation', '=', 'ob_details.mainstyle_id') 
                            ->where('assigned_to_orders.sales_order_no','=', $details->sales_order_no)
                            ->where('ob_details.operation_id','=', $details->operationNameId)   
                            ->get();         
                                
                                
                            
                       @endphp
                       <tr>
                            <td>  {{-- onclick="AddNewRow(this);" --}}
                                 <input type="text" name="srno" class="form-control"  value="{{$srno++}}" style="width:60px;">  
                            </td>
                            <td >
                                <a href="javascript:void(0);" class="btn btn-danger" onclick="removeRow(this);" style="" > X </a>
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-warning" >+</a>
                            </td>
                            <td> 
                                  <select name="sales_order_no[]" class="form-select select2" id="sales_order_no" disabled style="width:150px;"  onchange="operation_List(this);"  >
                                    <option value="">--KDPL--</option>
                                    @foreach($SalesOrderMap[$details->sales_order_no] as  $row)
                                    <option value="{{ $row['sales_order_no'] }}" {{ $row['sales_order_no'] == $details->sales_order_no ? 'selected="selected"' : '' }} >{{ $row['sales_order_no'] }}</option>
                                    @endforeach
                                  </select>
                            </td>
                                <td>   
                                 <select name="color_id[]" class="form-select" disabled style="width:230px;">
                                   <option value="">--Select--</option>  
                                   
                                   @if(isset($colorMap[$details->color_id]))
                                    @foreach($colorMap[$details->color_id] as $colors) 
                                     <option value="{{ $colors['color_id'] }}" {{ $colors['color_id'] == $details->color_id ? 'selected="selected"' : '' }}  >{{ $colors['color_name'] }}</option>  
                                    @endforeach
                                    @endif
                                    
                                    
                                 </select> 
                            </td>   
                            
                            <td> 
                                 <select name="operationNameId[]" class="form-select select2" disabled  style="width:300px;" onclick="checkSalesOrder(this);get_rates(this);">
                                    <option value="">--Select--</option>  
                                    @foreach($OperationNameList as $operations)
                                     <option value="{{$operations->operation_id}}" {{ $operations->operation_id == $details->operationNameId ? 'selected="selected"' : '' }}  >{{$operations->operation_name }} {{ ($operations->operation_id) }}</option>  
                                    @endforeach
                                 </select>
                            </td> 
                            <td>
                                 <input type="number" step="any"  name="bundleNo[]" disabled class="form-control"  value="{{ $details->bundleNo }}" onchange="GetCuttingEntryData(this);checkDuplicates(this);" style="width: 120px;">   
                            </td>
                            <td> 
                           
                                 <input type="number" step="any" name="stiching_qty[]" class="form-control qty" min="1" value="{{ $details->stiching_qty }}" onkeyup="calculateAmt(this);"  style="width:100px;" >  
                               <input type="hidden" step="any" name="cut_panel_issue_qty[]" class="form-control" value="{{$balanceQty }}" max="{{ $details->stiching_qty }}"   style="width:100px;" readonly>  
                           
                            </td>
                    
                            <td>  
                                <input type="text" name="lotNo[]" class="form-control" disabled value="{{ $details->lotNo }}" style="width:80px;" readonly>  
                            </td>
                            <td>
                                 <input type="text" name="slipNo[]" class="form-control" disabled value="{{ $details->slipNo }}" style="width:80px;" readonly>  
                            </td>
                            <td>   
                                  <input type="text" name="size_name[]" class="form-control" value="{{$details->size_name}}"  tabindex="2" style="width:100px;" readonly>  
                                  <input type="hidden" name="size_id[]" class="form-control" value="{{$details->size_id}}" style="width:100px;" readonly>
                            </td>
                            <td>
                                  <select name="line_no[]" class="form-select" style="width:100px;" required> 
                                    @foreach($lineList as  $row)
                                    <option value="{{ $row->line_id }}" {{ $row->line_id == $details->line_no ? 'selected="selected"' : '' }}  >{{ $row->line_name }}</option>
                                    @endforeach
                                 </select>
                                <input  type="hidden" step="any"  name="rate[]" class="form-control RATE" value="{{ $details->rate }}"  style="width:80px;" readonly>    
                                 <input  type="hidden" step="any"  name="amount[]" class="form-control amount" value="{{ $details->amount }}"  style="width:80px;" readonly >  
                            </td>
                            <td>
                                 <input type="text" name="bundle_track_code[]" class="form-control" value="{{ $details->bundle_track_code }}" style="width:100px;"  disabled>  
                            </td>
                       </tr>
                       @endforeach
                   </tbody>
               </table>
            </div>
            </div>
            <div class="row">
               <div class="col-md-3 mt-3" >
                  <div class="mb-3">
                     <label for="total_qty" class="form-label">Total Qty.</label>
                     <input  type="number" step="any"  id="total_qty" class="form-control" value="0" readonly > 
                  </div>
               </div>
               <div class="col-md-3 mt-3" >
                  <div class="mb-3">
                     <label for="total_amount" class="form-label">Total Amount</label>
                     <input  type="number" step="any"  id="total_amount" class="form-control" value="0"  readonly > 
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="mb-3">
                     <label for="formrow-email-input" class="form-label">&nbsp;</label>
                     <button type="submit" class="btn btn-primary w-md" id="submitBtn" onclick="removeDisabled();">Submit</button>
                     <a href="{{ Route('DailyProductionEntry.index') }}"  class="btn btn-warning w-md">Cancel</a>
                  </div>
               </div>
         </div>
         </form>
         @endif
         <!-- end card body -->
      </div>
      <!-- end card -->
   </div>
   <!-- end col -->
   <!-- end col -->
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<!-- end row -->
<script>
    $(document).ready(function() {
        $('#frmProduction').submit(function() {
            $('#submitBtn').prop('disabled', true);
        });
        calculateTotal();
    });
    
    function calculateTotal()
    {
        var total_qty = 0;
        var total_amount = 0;
        $('.qty').each(function()
        {
            total_qty += parseFloat($(this).val()) ? parseFloat($(this).val()) : 0;
        });
        
        $('.amount').each(function(){
            total_amount += parseFloat($(this).val()) ? parseFloat($(this).val()) : 0; 
        });
        
        $("#total_qty").val(total_qty);
        $("#total_amount").val(total_amount);
        
    }
    
    $(function(){
      
      $('.select2').select2();
    });
    function checkSalesOrder(row)
    {
        var sales_order_no = $(row).parent().parent('tr').find('td select[name="sales_order_no[]"]').val();
        if(sales_order_no == "")
        {
            alert('Please Select Sales Order No.');
            $(row).val("").change();
        }
        
        $(row).parent().parent('tr').find('td input').not(':first').val('');
    }
    function operationWiseProductionList(row)
    {
        var sales_order_no = $(row).val();
        var employeeCode = $("#employeeCode").val();
        $.ajax({
          type: "GET",
          dataType:"json",
         // url: "{{ route('operationWiseProductionList') }}",
          data:{'sales_order_no':sales_order_no,'employeeCode':employeeCode}, 
          success: function(data)
          {  
              $(row).parent().parent('tr').find('select[name="operationNameId[]"]').html(data.html); 
          }
        });
    }
    
    
    function checkDuplicates(row)
    {
       var elemArr = [];
        
        // Iterate over each row except the current one to build the array of existing combinations
        $('tr').not($(row).closest('tr')).each(function() {
            var bundleNo = $(this).find('input[name="bundleNo[]"]').val();
            var salesOrderNo = $(this).find('select[name="sales_order_no[]"]').val();
            var operationNameId = $(this).find('select[name="operationNameId[]"]').val();
        
            if (bundleNo && salesOrderNo && operationNameId) {
                elemArr.push(bundleNo + '-' + salesOrderNo + '-' + operationNameId);
            }
        });
        
        // Get the current values of the row
        var currentBundleNo = $(row).val();
        var currentSalesOrderNo = $(row).closest('tr').find('select[name="sales_order_no[]"]').val();
         var currentoperationNameId = $(row).closest('tr').find('select[name="operationNameId[]"]').val(); 
        

        if ($.inArray(currentBundleNo + '-' + currentSalesOrderNo + '-' + currentoperationNameId, elemArr) !== -1) {
            alert("Already Exists...!");
            setTimeout(function() {
                $(row).parent().parent('tr').find('input').not('.RATE').val("");
               // $(row).parent().parent('tr').find('select[name="line_no[]"]').val(1);
                recalcIdcone();
            }, 500);
            //$(row).parent().parent('tr').find('select[name="line_no[]"]').val(1);
        }
          
        //$(row).parent().parent('tr').find('select[name="line_no[]"]').val(1); 
        
    }
    
//   $(function()
//   { 
//       GetEmployeeList();
       
//   });    
   function removeDisabled()
   { 
       $('input,select').removeAttr('disabled');
   }
 
//   $(function()
//   {
//       $("#opertionTbl > tbody > tr").each(function()
//       {
//           if($(this).find("td input[name='amount[]']").val() == "")
//           {
//               calculateAmt($(this).find("td input[name='stiching_qty[]']"));
//           }
        
//       });
//   }) ;
   
   function calculateAmt(row)
   {
         var stitching_qty = $(row).val();
         var rate = $(row).parent().parent('tr').find('input[name="rate[]"]').val();
         var cut_panel_issue_qty = $(row).parent().parent('tr').find('input[name="cut_panel_issue_qty[]"]').attr('max');
         if(parseInt(stitching_qty) > parseInt(cut_panel_issue_qty))
         {
            alert('Stiching qty can not be greater than '+cut_panel_issue_qty);
            $(row).parent().parent('tr').find('input[name="stiching_qty[]"]').val(0);
            $(row).parent().parent('tr').find('input[name="amount[]"]').val(0);
           calculateTotal();
         }
         else
         {
             var totalAmt = parseFloat(stitching_qty) * parseFloat(rate);
             $(row).parent().parent('tr').find('input[name="amount[]"]').val(totalAmt);
             calculateTotal();
         }
   }
   
   function GetCuttingEntryData(row)
   {
        var sales_order_no = $(row).parent().parent('tr').find('td select[name="sales_order_no[]"]').val();
        var operationNameId = $(row).parent().parent('tr').find('td select[name="operationNameId[]"]').val();
        var employeeCode = $("#employeeCode").val();
        var bundleNo = $(row).val();
        if(sales_order_no != "" && operationNameId != "")
        { 
            $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('GetCuttingEntryData') }}",
              data:{'sales_order_no':sales_order_no,'bundleNo':bundleNo,'operationNameId':operationNameId, 'employeeCode':employeeCode}, 
              success: function(data)
              {  
                  $(row).parent().parent('tr').find('input[name="slipNo[]"]').val(data.slipNo);
                //   $(row).parent().parent('tr').find('select[name="operationNameId[]"]').val(data.operationNameId); 
                  $(row).parent().parent('tr').find('input[name="lotNo[]"]').val(data.lotNo);
                //   $(row).parent().parent('tr').find('select[name="sales_order_no[]"]').val(data.sales_order_no); 
                  $(row).parent().parent('tr').find('input[name="bundleNo[]"]').val(data.bundleNo);
                 // $(row).parent().parent('tr').find('input[name="rate[]"]').val(data.operation_rate);   
                  $(row).parent().parent('tr').find('input[name="bundle_track_code[]"]').val(data.bundle_track_code); 
                  $(row).parent().parent('tr').find('input[name="cut_panel_issue_qty[]"]').val(data.cut_panel_issue_qty);  
                  $(row).parent().parent('tr').find('select[name="color_id[]"]').val(data.color_id);  
                  $(row).parent().parent('tr').find('select[name="size_id[]"]').val(data.size_id);  
                  $(row).parent().parent('tr').find('input[name="stiching_qty[]"]').attr('max',data.cut_panel_issue_qty);   
              }
            });
            
        }
        else
        {
            alert("Please Select Operation Name...!");
            $(row).val("");
        }
        
             calculateTotal();
   }
   
   function GetOperationList(main_style_id)
   {
        var main_style_id = $("#main_style_id").val();
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetDailyProductionOperationList') }}",
          data:{'main_style_id':main_style_id},
          success: function(data)
          { 
               $('select[name="operationNameId[]"]').html(data.html);
          }
        });
   }
   
   function GetBuyerPurchaseData(sales_order_no)
   {
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetBuyerPurchaseData') }}",
          data:{'sales_order_no':sales_order_no},
          success: function(data)
          { 
               $('#main_style_id').val(data.main_style_id).trigger('change'); 
               $('#fg_id').val(data.fg_id);
               $('#fg_name').val(data.fg_name);
               $('#Ac_name').val(data.Ac_name);
               $('#style_no').val(data.style_no);
               $('#sam').val(data.sam);
               $('#brand_name').val(data.brand_name);
               $('select[name="size[]"]').html(data.sizehtml); 
              // GetOperationList(data.main_style_id); 
          }
        });
   }
  
//   function GetEmployeeList()
//   {
        
//         $.ajax({
//           type: "GET",
//           dataType:"json",
//           url: "{{ route('GetEmpList') }}", 
//           success: function(data)
//           { 
//               $('select[name="employeeCode"]').html(data.html);
//                 $('#employeeCode').val($('#EmpCode').val()).trigger('change');
//           }
//         });
//   }
   function AddNewRow(row)
   { 
        $('.select2').select2("destroy");  
        var tr = $(row).closest('tr');
        var clone = tr.clone();
        console.log(tr.find('select[name="sales_order_no[]"]').val());
        
          clone.find('select[name="sales_order_no[]"]').prop('disabled',false);
        
        tr.find('input[name="bundle_track_code[]"]').attr('readonly',true);
        clone.find('select[name="sales_order_no[]"]').val(tr.find('select[name="sales_order_no[]"]').val());
        clone.find('select[name="operationNameId[]"]').val(tr.find('select[name="operationNameId[]"]').val());
        clone.find('input').not('.RATE').val('');
        //clone.find('input').not(':first').not('select[name="line_no[]"]').val('');
        tr.after(clone);
        clone.find('input[name="bundleNo[]"]').each(function (i) { $(this).attr('tabindex', i + 1); });
        clone.find('input[name="stiching_qty[]"]').each(function (i) { $(this).attr('tabindex', i + 1); });
        $('.select2').select2();
        recalcIdcone();
        calculateTotal();
   } 
   
   function removeRow(row)
   { 
      $(row).parents('tr').remove(); 
      
      calculateTotal();
   }
   
   function recalcIdcone()
   {
       $.each($("#opertionTbl tr"),function (i,el)
       {
             $(this).find("td:first input").val(i);  
       })
   }
   
   
       
    function operation_List(row)
    {
        var sales_order_no = $(row).val();
        var employeeCode = $("#employeeCode").val();
        $.ajax({
          type: "POST",
          dataType:"json",
          url: "{{ route('operation_list') }}",
          data:{'sales_order_no':sales_order_no,'employeeCode':employeeCode,"_token":"{{ csrf_token() }}"}, 
          success: function(data)
          {  
              $(row).parent().parent('tr').find('select[name="operationNameId[]"]').html(data.html); 
          }
        });
    }
   
    function get_rates(row)
    {
        var operationNameId = $(row).val();
        var employeeCode = $("#employeeCode").val();
        
        $.ajax({
          type: "POST",
          dataType:"json",
          url: "{{ route('get_rates') }}",
          data:{'operationNameId':operationNameId,'employeeCode':employeeCode,"_token":"{{ csrf_token() }}"}, 
          success: function(data)
          {  
              $(row).parent().parent('tr').find('input[name="rate[]"]').val(data.rate); 
          }
        });
    }
</script>

@endsection