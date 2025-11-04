@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Cutting Entry Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Cutting Entry Master</li>
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
         <h4 class="card-title mb-4">Cutting Entry Master</h4>
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
         @if(isset($CuttingEntryList))
          <form action="{{ route('CuttingEntry.update',$CuttingEntryList) }}" method="POST" enctype="multipart/form-data">  
            <input type="hidden" name="cuttingEntryId" class="form-control" id="cuttingEntryId" value="{{ $CuttingEntryList->cuttingEntryId}}"> 
            <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
           @method('put')
           @csrf   
            <div class="row">
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-cuttingEntryDate" class="form-label">Date</label>
                      <input type="date" name="cuttingEntryDate" class="form-control" id="formrow-cuttingEntryDate-input" value="{{$CuttingEntryList->cuttingEntryDate}}" required>  
                  </div>
               </div>  
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-sales_order_no" class="form-label">KDPL</label>
                     <select name="sales_order_no" class="form-select select2" id="sales_order_no" onchange="GetBuyerPurchaseData(this.value);" disabled>
                        <option value="">--KDPL--</option>
                        @foreach($SalesOrderList as  $row)
                        <option value="{{ $row->sales_order_no }}"  {{ $row->sales_order_no == $CuttingEntryList->sales_order_no ? 'selected="selected"' : '' }} >{{ $row->sales_order_no }}</option>
                        @endforeach
                     </select>
                  </div>
               </div> 
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-buyer_name" class="form-label">Buyer Name</label>
                      <input type="text" name="Ac_name" class="form-control" id="Ac_name" value="" readonly>  
                  </div>
               </div> 
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-brand" class="form-label">Buyer Brand</label>
                      <input type="text" name="brand_name" class="form-control" id="brand_name" value="" readonly>  
                  </div>
               </div>  
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-main_style_id" class="form-label">Main Style</label>
                     <select name="main_style_id" class="form-select select2" id="main_style_id" disabled>
                        <option value="">--Main Style--</option>
                        @foreach($MainStyleList as  $row)
                        <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>   
               <div class="col-md-3">
                  <div class="mb-3">
                      <label for="formrow-fg_name" class="form-label">Style Name</label>
                      <input type="hidden" name="fg_id" class="form-control" id="fg_id" value=""> 
                      <input type="text" name="fg_name" class="form-control" id="fg_name" value="" readonly> 
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-style_no" class="form-label">Style No.</label>
                      <input type="text" name="style_no" class="form-control" id="style_no" value="" readonly>  
                  </div>
               </div> 
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="formrow-sam" class="form-label">SAM</label>
                      <input type="text" name="sam" class="form-control" id="sam" value="" readonly>  
                  </div>
               </div>   
                <div class="col-md-12 table-responsive">
                  <table id="opertionTbl" class="table table-bordered dt-responsive nowrap w-100">
                   <thead>
                       <tr>
                           <th>Sr No.</th> 
                           <th>Garment Color</th>
                           <th>Lot No</th>
                           <th>Bundle No</th>
                           <th>Bundle Track Code</th>
                           <th>Slip No.</th> 
                           <th>Size</th>
                           <th>Cut Panel Qty</th>
                           <th>Action</th>
                       </tr>
                   </thead>
                   <tbody>
                       @php
                            $srno = 1;
                            
                            $cuttingData = DB::SELECT('select tr_no as count FROM counter_number WHERE code="B" AND type="BundleTrackCode"');
                            $sr_no = isset($cuttingData[0]->count) ? $cuttingData[0]->count : 0;
                       @endphp
                       @foreach($CuttingEntryDetailList as $details)
                       @php
                             //DB::enableQueryLog();

                            $prodData = DB::SELECT("SELECT count(*) as total_count FROM daily_production_entry_details WHERE sales_order_no='".$CuttingEntryList->sales_order_no."' AND bundle_track_code='".$details->bundle_track_code."'");
                                        
                             //dd(DB::getQueryLog());
                            $total_count = isset($prodData[0]->total_count) ? $prodData[0]->total_count : 0;
                            
                            if($total_count > 0)
                            {
                                $btn = 'disabled';
                            }
                            else
                            {
                                $btn ='';
                            }
                       @endphp
                       <tr>
                            <td> 
                                 <input type="hidden" name="tr_no[]" class="form-control"  value="{{$sr_no}}">  
                                 <input type="text" class="form-control"  value="{{$srno++}}" style="width:60px;">  
                            </td> 
                            <td>   
                                 <select name="color_id[]" class="form-select"  style="width:230px;" {{$btn}}>
                                   <option value="">--Select--</option>  
                                    @foreach($ColorList as $colors)
                                     <option value="{{$colors->color_id}}" {{ $colors->color_id == $details->color_id ? 'selected="selected"' : '' }}  >{{$colors->color_name}}</option>  
                                    @endforeach
                                 </select> 
                            </td>
                            <td>  
                                <input type="text" name="lotNo[]" class="form-control" value="{{$details->lotNo}}" style="width:100px;" {{$btn}}>  
                            </td>
                            <td>
                                 <input type="number" step="any"   name="bundleNo[]" class="form-control" value="{{$details->bundleNo}}"  style="width:100px;" onchange="checkDuplicateBundleNo(this);" {{$btn}}>  
                            </td>
                            <td>
                                 <input type="text" name="bundle_track_code[]" class="form-control" value="{{$details->bundle_track_code}}" style="width:100px;" readonly>  
                            </td>
                            <td>
                                 <input type="text" name="slipNo[]" class="form-control" value="{{$details->slipNo}}"  style="width:100px;"  {{$btn}} >  
                            </td> 
                            <td> 
                                  <select name="size[]" class="form-select"  style="width:100px;" {{$btn}} >
                                    <option value="">--Select--</option>  
                                     @foreach($sizeList as $sizes)
                                     <option value="{{$sizes->size_id}}" {{ $sizes->size_id == $details->size ? 'selected="selected"' : '' }}  >{{$sizes->size_name}}</option>  
                                    @endforeach
                                 </select>
                            </td>
                            <td>
                                 <input type="text" name="cut_panel_issue_qty[]" class="form-control" value="{{$details->cut_panel_issue_qty}}"  style="width:100px;" {{$btn}} onchange="calculateCutQty();" >  
                            </td>
                            
                            {{-- onclick="AddNewRow(this);" --}}
                            <td nowrap>
                                <a href="javascript:void(0);" class="btn btn-warning" >+</a>
                                <button type="button" class="btn btn-danger" onclick="removeRow(this);" {{$btn}} > X </button>
                            </td>
                       </tr>
                       @endforeach
                   </tbody>
               </table>
               </div> 
               <div class="col-md-3">
                  <div class="mt-3 mb-3">
                     <label for="total_cut_qty" class="form-label">Total Cut Qty.</label>
                      <input type="text" name="total_cut_qty" class="form-control" id="total_cut_qty" value="{{$CuttingEntryList->total_cut_qty}}" readonly>  
                  </div>
               </div>
            </div>
            <div class="row">
         <div class="col-sm-6">
    <div class="mb-3">
        <label for="jpart_id" class="form-label">For Which Job Parts Do You want Slip Print..?</label>
       <select name="jpart_id[]" class="form-select" id="jpart_id" size="10" required multiple>
        <option value="0">--All Part--</option>
            @foreach($JobPartList as $rowCutting)
                <option value="{{ $rowCutting->jpart_id }}"
                {{ in_array($rowCutting->jpart_id, explode(',', $CuttingEntryList->jpart_id ?? '')) ? 'selected="selected"' : '' }}>
                {{ $rowCutting->jpart_name }} ({{ $rowCutting->jpart_description }})
                </option>
            @endforeach
        </select>
    </div>
</div>       
        
               <div class="col-md-6">
                  <div class="mb-3">
                     <label for="formrow-email-input" class="form-label">&nbsp;</label>
                     <button type="submit" class="btn btn-primary w-md"  onclick="removeDisabled();"  >Submit</button>
                     <a href="{{ Route('CuttingEntry.index') }}"  class="btn btn-warning w-md">Cancel</a>
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
<input type="hidden" id="sr_no" value="{{$sr_no}}">
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
   @if(isset($CuttingEntryList))
    GetBuyerPurchaseData($("#sales_order_no").val());
   @endif
   
   $(function()
   {
       calculateCutQty();
   });
   
    function calculateCutQty()
    {
         var total_qty = 0;
         $('input[name="cut_panel_issue_qty[]"]').each(function(){
             total_qty += parseFloat($(this).val());
         });
         
         $("#total_cut_qty").val(total_qty);
    }
    
   $("select").on( "click", function() 
   {
       $(this).select2('destory');
       $(this).select2();
   }); 
   
   function checkDuplicateBundleNo(row)
   {
        var sales_order_no = $("#sales_order_no").val();
        var color_id = $(row).parent().parent('tr').find('td select[name="color_id[]"] option:selected').val();
        var bundleNo = $(row).val();
          
        var bundle_count = 0;
        $('input[name="bundleNo[]"]').each(function()
        {
            
          if($(this).val() == bundleNo)
          {
              bundle_count++;
          }
          if(bundle_count >= 2)
          { 
              alert("Already Exist Bundle No.");
              $(row).val("");
          }
        }); 
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('checkDuplicateBundleNo') }}",
          data:{'sales_order_no':sales_order_no,'color_id':color_id,'bundleNo':bundleNo},
          success: function(data)
          { 
              if(data.total_count > 0)
              {
                    alert("Already Exist Bundle No.");
                    $(row).val("");
              }
          }
        });
   }
   
  function removeDisabled()
   {
       $('select').removeAttr('disabled');
       $('input').removeAttr('disabled');
   }
   
//   $(function()
//   {
      // GetOperationList($("#main_style_id").val());
      // GetPartList($("#fg_id").val()); 
    //   GetBuyerPurchaseData($("#sales_order_no").val());
       
//   });
//   function GetOperationList(main_style_id)
//   {
        
//         $.ajax({
//           type: "GET",
//           dataType:"json",
//           url: "{{ route('GetDailyProductionOperationList') }}",
//           data:{'main_style_id':main_style_id},
//           success: function(data)
//           { 
//               $('select[name="operationNameId[]"]').html(data.html);
//           }
//         });
//   }
   
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
               //$('select[name="color_id[]"]').html(data.colorHtml); 
               //$('select[name="size[]"]').html(data.sizehtml); 
              // GetOperationList(data.main_style_id);
              // GetPartList(data.fg_id);
          }
        });
   }
 
//   function GetPartList(fg_id)
//   { 
//         $.ajax({
//           type: "GET",
//           dataType:"json",
//           url: "{{ route('GetPartList') }}",
//           data:{'fg_id':fg_id},
//           success: function(data)
//           { 
//               $('select[name="cut_part_id[]"]').html(data.html);
//           }
//         });
//   }
 
   
   function AddNewRow(row)
   { 
        var sr_no = parseInt($("#sr_no").val()) + parseInt(1);
        var tr = $(row).closest('tr'); 
        $('.select2').select2("destroy");  
        var clone = tr.clone();
        tr.after(clone);
        recalcIdcone(); 
        clone.find('select[name="color_id[]"]').val(tr.find('select[name="color_id[]"]').val());
        $(clone).find('td input[name="bundle_track_code[]"]').val('B'+sr_no);
        $(clone).find('td input[name="bundleNo[]"]').val('');
     
        //$(clone).find('td select[name="size[]"]').val('');
        $("#sr_no").val(sr_no);
   } 
   
   function removeRow(row)
   { 
      $(row).parents('tr').remove(); 
      var sr_no = parseInt($("#sr_no").val()) - parseInt(1); 
      $("#sr_no").val(sr_no);
      
      calculateCutQty();
   }
   
   function recalcIdcone()
   {
       $.each($("#opertionTbl tr"),function (i,el)
       {
             $(this).find("td:first input").val(i);  
       })
   }
   
   
</script>

@endsection