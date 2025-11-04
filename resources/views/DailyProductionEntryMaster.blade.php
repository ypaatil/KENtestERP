@extends('layouts.master') 
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Daily Production Entry</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Daily Production Entry</li>
            </ol>
         </div>
      </div>
   </div>
</div>

<style>
.modal-xl {
    max-width: 90%;  /* Adjust this percentage as needed */
}    
</style>

<!-- end page title -->
<div class="row">
<div class="col-xl-12">
   <div class="card">
      <div class="card-body">
         <h4 class="card-title mb-4">Daily Production Entry</h4>
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
         <form action="{{route('DailyProductionEntry.store')}}" method="POST" id="frmProduction">
         <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId" >
            @csrf 
            <div class="row">
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-dailyProductionEntryDate" class="form-label">Date</label>
                     <input type="date" name="dailyProductionEntryDate" class="form-control" id="dailyProductionEntryDate" value="{{date('Y-m-d')}}" required>  
                  </div>
               </div> 
               <div class="col-md-5">
                  <div class="mb-3">
                     <label for="formrow-employeeCode" class="form-label">Employee</label>
                     <input type="hidden" name="employeeName" class="form-control" id="employeeName">  
                     <select name="employeeCode" class="form-select select2" id="employeeCode" required>
                        <option value="">--Select--</option>
                        @foreach($employeeList as $row)
                            <option value="{{$row->employeeCode}}">({{$row->employeeCode}}) {{$row->employeeName}} - ({{ $row->sub_company_name }})</option>
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
                           <th nowrap>Balance Qty</th>
                           <th nowrap>Lot No</th>
                           <th nowrap>Slip No.</th>
                           <th nowrap>Size</th>
                           <th nowrap>Line No</th>
                           <th nowrap>Bundle Track Code</th>
                       </tr> 
                   </thead>
                   <tbody> 
                       <tr>
                            <td> 
                                 <input type="text" name="srno[]" class="form-control" id="formrow-srno-input" value="1" style="width:50px;" readonly>  
                            </td>
                            <td >
                                <a href="javascript:void(0);" class="btn btn-danger" onclick="removeRow(this);" style="" > X </a>
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-warning" onclick="AddNewRow(this);"  tabindex="3">+</a>
                            </td>
                            <td> 
                                  <select name="sales_order_no[]" class="form-select select2" id="sales_order_no" style="width:150px;" onchange="operation_List(this);GetBuyerPurchaseData(this,this.value);" >
                                    <option value="">--KDPL--</option>
                                    @foreach($SalesOrderList as  $row)
                                    <option value="{{ $row->sales_order_no }}">{{ $row->sales_order_no }}</option>
                                    @endforeach
                                  </select>
                            </td>
                              <td>   
                                 <select name="color_id[]" class="form-select select2"  style="width:230px;">
                                   <option value="">--Select--</option>  
                                    @foreach($ColorList as $colors)
                                     <option value="{{$colors->color_id}}"  >{{$colors->color_name}}</option>  
                                    @endforeach
                                 </select> 
                            </td>   
                            
                            <td> 
                                 <select name="operationNameId[]" class="form-select select2"  style="width:300px;-webkit-appearance: none;" onclick="checkSalesOrder(this);get_rates(this);get_groups(this); SetStitichingQty(this);" >
                                    <option value="">--Select--</option>   
                                 </select>
                            </td>
                            <td>
                                 <input type="number" step="any"  name="bundleNo[]" class="form-control" style="width:120px;" value="" tabindex="1" onchange="GetCuttingEntryData(this);checkDuplicates(this);checkDynamicDuplicates(this);" required>   
                            </td>
                            <td> 
                                 <input  type="number" step="any"  name="stiching_qty[]" class="form-control qty" min="1" value="" onkeyup="calculateAmt(this);checkMaxValue(this);" tabindex="2" style="width:100px;" >  
                            </td>
                            <td> 
                                 <input  type="number" step="any"  name="cut_panel_issue_qty[]" class="form-control" value=""  style="width:100px;" id="cut_panel_issue_qty" readonly>  
                            </td>
                            <td>  
                                <input type="text" name="lotNo[]" class="form-control" value="" style="width:80px;" readonly>  
                            </td>
                            <td>
                                 <input type="text" name="slipNo[]" class="form-control" value="" style="width:80px;" readonly>  
                            </td>
                            <td>  
                                  <input type="text" name="size_name[]" class="form-control" value=""  tabindex="2" style="width:100px;" readonly>  
                                  <input type="hidden" name="size_id[]" class="form-control" value="" style="width:100px;" readonly> 
                            </td>
                            <td>
                                  <select name="line_no[]" class="form-select" style="width:100px;" required> 
                                    @foreach($lineList as  $row)
                                    <option value="{{ $row->line_id }}">{{ $row->line_name }}</option>
                                    @endforeach
                                 </select>
                                 <input  type="hidden" step="any"  name="rate[]" class="form-control RATE" value=""  style="width:80px;" readonly>  
                                  <input  type="hidden" step="any"  name="amount[]" class="form-control amount" value=""  style="width:80px;" readonly>  
                            </td>
                  
                            <td>
                                 <input type="text" name="bundle_track_code[]" class="form-control" value="" style="width:100px;"  readonly>  
                            </td>
                       </tr>
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
         <!-- end card body -->
      </div>
      <!-- end card -->
   </div>
   <!-- end col -->
   <!-- end col -->
   
   
   
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <!-- Added modal-lg for a larger modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel" style="color:#F00;">Already Exist.!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Information in a Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                              <th scope="col" nowrap>Employee</th>
                            <th scope="col" nowrap>Sales Order No.</th>
                            <th scope="col">Garment Color</th>
                            <th scope="col" nowrap>Operation Name</th>
                            <th scope="col" nowrap>Bundle No.</th>   
                          <th scope="col" nowrap>Size</th>  
                         <th scope="col" nowrap>Qty</th>     
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Data rows will go here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

   
</div>




<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- end row -->
<script>
    $(document).ready(function() {
        $('#frmProduction').submit(function() {
            $('#submitBtn').prop('disabled', true);
        });
        calculateTotal();
    });
    
    function checkMaxValue(input)
    {
        const max = parseFloat(input.max);
        const value = parseFloat(input.value);
    
        if (!isNaN(value) && value > max) {
            alert(`Value should not exceed ${max}`);
            input.value = ''; // or input.value = max; if you want to auto-correct
            input.focus();
        }
    
        calculateAmt(input); // Continue with your original function
    }


    function SetStitichingQty(row)
    {
        var userId = $("#userId").val();
        var sales_order_no = $(row).parent().parent('tr').find('td select[name="sales_order_no[]"]').val();
        var color_id = $(row).parent().parent('tr').find('td select[name="color_id[]"]').val();
        var operationNameId = $(row).parent().parent('tr').find('td select[name="operationNameId[]"]').val(); 
         
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('SetStitichingQtyForDailyProduction') }}",
          data:{'userId':userId,'sales_order_no':sales_order_no,'color_id':color_id,'operationNameId':operationNameId}, 
          success: function(data)
          {  
               if(parseInt(data.group_id) == 8)
               {
                    $(row).parent().parent('tr').find('input[name="stiching_qty[]"]').attr("max", data.max); 
                    alert('Stiching qty can not be greater than '+data.max);
                    $(row).parent().parent('tr').find('input[name="stiching_qty[]"]').val(0);
                    $(row).parent().parent('tr').find('input[name="amount[]"]').val(0);
               }
          }
        });
    }
    
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
          url: "{{ route('operationWiseProductionList') }}",
          data:{'sales_order_no':sales_order_no,'employeeCode':employeeCode}, 
          success: function(data)
          {  
              $(row).parent().parent('tr').find('select[name="operationNameId[]"]').html(data.html); 
          }
        });
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
    
     function get_groups(row)
    {
        var operationNameId = $(row).val();
        var sales_order_no= $(row).closest('tr').find('td select[name="sales_order_no[]"]').val();
        
        $.ajax({
          type: "POST",
          dataType:"json",
          url: "{{ route('get_groups') }}",
          data:{'operationNameId':operationNameId,sales_order_no:sales_order_no,"_token":"{{ csrf_token() }}"}, 
          success: function(data)
          {   
              if(data > 0)
              {
                    $(row).parent().parent('tr').find('input[name="bundleNo[]"]').prop('disabled', true);
              }
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
            var color_id = $(this).find('select[name="color_id[]"]').val(); 
        
            if (bundleNo && salesOrderNo && operationNameId) {
                elemArr.push(bundleNo + '-' + salesOrderNo + '-' + operationNameId + '-' + color_id);
            }
        });
        
        // Get the current values of the row
        var currentBundleNo = $(row).val();
        var currentSalesOrderNo = $(row).closest('tr').find('select[name="sales_order_no[]"]').val();
         var currentoperationNameId = $(row).closest('tr').find('select[name="operationNameId[]"]').val(); 
          var currentcolor_id = $(row).closest('tr').find('select[name="color_id[]"]').val(); 
        

        if ($.inArray(currentBundleNo + '-' + currentSalesOrderNo + '-' + currentoperationNameId + '-' + currentcolor_id, elemArr) !== -1) {
            alert("Already Exists...!");
            setTimeout(function() {
                $(row).parent().parent('tr').find('input').not('.RATE').val("");
             //   $(row).parent().parent('tr').find('select[name="line_no[]"]').val(1);
                recalcIdcone();
            }, 500);
           // $(row).parent().parent('tr').find('select[name="line_no[]"]').val(1);
        }
          
       // $(row).parent().parent('tr').find('select[name="line_no[]"]').val(1); 
        
    }
    
      
    function removeDisabled()
    {  
       $('select').removeAttr('disabled');
    }
   
    function setEmpName()
    {
        $('#employeeName').val( $("#employeeCode").find(":selected").text());
    }
   
   function calculateAmt(row)
   {
         var stitching_qty = $(row).val();
         var rate = $(row).parent().parent('tr').find('input[name="rate[]"]').val();
         var cut_panel_issue_qty = $(row).parent().parent('tr').find('input[name="cut_panel_issue_qty[]"]').val();
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
        var color_id = $(row).parent().parent('tr').find('td select[name="color_id[]"]').val(); 
        
        var employeeCode = $("#employeeCode").val();
        var bundleNo = $(row).val();
        
        
        if(sales_order_no != "" && operationNameId != "")
        { 
            $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('GetCuttingEntryData') }}",
              data:{'sales_order_no':sales_order_no,'bundleNo':bundleNo,'operationNameId':operationNameId,'employeeCode':employeeCode,color_id:color_id}, 
              success: function(data)
              {  
                  $(row).parent().parent('tr').find('input[name="slipNo[]"]').val(data.slipNo);
                  //$(row).parent().parent('tr').find('select[name="operationNameId[]"]').val(data.operationNameId); 
                  $(row).parent().parent('tr').find('input[name="lotNo[]"]').val(data.lotNo);
                 // $(row).parent().parent('tr').find('select[name="sales_order_no[]"]').val(data.sales_order_no); 
                  $(row).parent().parent('tr').find('input[name="bundleNo[]"]').val(data.bundleNo);
                 // $(row).parent().parent('tr').find('input[name="rate[]"]').val(data.operation_rate);   
                  $(row).parent().parent('tr').find('input[name="bundle_track_code[]"]').val(data.bundle_track_code); 
                 // $(row).parent().parent('tr').find('input[name="color_id[]"]').val(data.color_id);  
                  $(row).parent().parent('tr').find('input[name="color_name[]"]').val(data.color_name); 
                  $(row).parent().parent('tr').find('input[name="size_id[]"]').val(data.size_id); 
                  $(row).parent().parent('tr').find('input[name="size_name[]"]').val(data.size_name); 
                  $(row).parent().parent('tr').find('input[name="cut_panel_issue_qty[]"]').val(data.cut_panel_issue_qty);  
                  $(row).parent().parent('tr').find('input[name="stiching_qty[]"]').val(data.cut_panel_issue_qty);  
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
   
   function GetBuyerPurchaseData(row,sales_order_no)
   {
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetBuyerPurchaseData') }}",
          data:{'sales_order_no':sales_order_no},
          success: function(data)
          { 
   
          $(row).closest('tr').find('select[name="color_id[]"]').html(data.colorHtml); 
        
          }
        });
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
   
 
   function AddNewRow(row)
   { 
       
  
       
        $('.select2').select2("destroy");  
        var tr = $(row).closest('tr');
        var clone = tr.clone(); 
        
        tr.find('input[name="bundle_track_code[]"]').attr('readonly',true);
        clone.find('select[name="sales_order_no[]"]').val(tr.find('select[name="sales_order_no[]"]').val());
        clone.find('select[name="operationNameId[]"]').val(tr.find('select[name="operationNameId[]"]').val());
        clone.find('select[name="color_id[]"]').val(tr.find('select[name="color_id[]"]').val());  
        
          clone.find('input[name="rate[]"]').val(tr.find('input[name="rate[]"]').val());
        clone.find('input').not('.RATE').val('');
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
   }
   
   function recalcIdcone()
   {
       $.each($("#opertionTbl tr"),function (i,el)
       {
             $(this).find("td:first input").val(i);  
       })
   }
   
   
       
    function checkDynamicDuplicates(row)
    {
        
        var bundleNo = $(row).closest('tr').find('input[name="bundleNo[]"]').val();
        var sales_order_no = $(row).closest('tr').find('select[name="sales_order_no[]"]').val();
        var operationNameId = $(row).closest('tr').find('select[name="operationNameId[]"]').val();
        var color_id = $(row).closest('tr').find('select[name="color_id[]"]').val(); 
        
        $.ajax({
          type: "POST",
          dataType:"json",
          url: "{{ route('previous_production_exist_record') }}",
          data:{'bundleNo':bundleNo,'sales_order_no':sales_order_no,'operationNameId':operationNameId,'color_id':color_id,"_token":"{{ csrf_token(); }}"},
          success: function(data)
          { 
              
              console.log(data.result.length);
              
              
              if(data.result.length > 0)
              {
                 $('#infoModal').modal('show');
                 
                 
                        $('#tableBody').empty();
                        
                        // Loop through the result array and append rows to the table body
                        data.result.forEach((item, index) => {
                            
                         let formattedDate = moment(item.dailyProductionEntryDate).format('DD/MM/YYYY');
                            
                        let row = `
                        <tr>
                        <th scope="row">${index + 1}</th>
                        <td nowrap>${formattedDate}</td>
                        <td nowrap>${item.fullName}</td>  
                        <td nowrap>${item.sales_order_no}</td>
                        <td>${item.color_name }</td>
                        <td nowrap>${item.operationNameId}</td>
                        <td nowrap>${item.bundleNo}</td>
                        <td nowrap>${item.size_name}</td> 
                      <td nowrap>${item.stiching_qty}</td>    
                        
                        </tr>
                        `;
                        $('#tableBody').append(row); 
                        });
                 
                  
              } else{
                   console.log(0);
                  
              }
              
          }
        });
        
    }
    
          $('.close').click(function() {
           $('#infoModal').modal('hide');
            });
    
</script>

@endsection