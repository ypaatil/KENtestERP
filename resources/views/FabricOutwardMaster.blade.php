@extends('layouts.master') 
@section('content')
<style>
    .form-popup-bg {
      position:absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      flex-direction: column;
      align-content: center;
      justify-content: center;
    }
    .form-popup-bg {
      position: fixed;
      left: 0;
      top: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(94, 110, 141, 0.9);
      opacity: 0;
      visibility: hidden;
      -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
      -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
      transition: opacity 0.3s 0s, visibility 0s 0.3s;
      overflow-y: auto;
      z-index: 10000;
    }
    .form-popup-bg.is-visible {
      opacity: 1;
      visibility: visible;
      -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
      -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
      transition: opacity 0.3s 0s, visibility 0s 0s;
    }
    .form-container {
        background-color: #011b3285;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 825px;
        margin-left: auto;
        margin-right: auto;
        position:relative;
        padding: 40px;
        color: #fff;
        height: fit-content;
        max-height: -webkit-fill-available;
    }
    .close-button {
      background:none;
      color: #fff;
      width: 40px;
      height: 40px;
      position: absolute;
      top: 0;
      right: 0;
      border: solid 1px #fff;
    }
    
    .form-popup-bg:before{
      content:'';
      background-color: #fff;
      opacity: .25;
      position:absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Outward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Outward</li>
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
            <h4 class="card-title mb-4">Fabric Outward</h4>
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
            <form action="{{route('FabricOutward.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fout_date" class="form-label">Issue Date</label>
                        <input type="date" name="fout_date" class="form-control" id="fout_date" value="{{date('Y-m-d')}}" required>
                        @foreach($counter_number as  $row)
                        <input type="hidden" name="fout_code" class="form-control" id="fout_code" value="{{ 'FOUT'.'-'.$row->tr_no }}">
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                        @endforeach
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Outward For</label>
                        <select name="out_type_id" class="form-control select2" id="out_type_id" required onchange="GetSINCodeList();" >
                           <option value="">--Type--</option>
                           @foreach($OutTypeList as  $rowot)
                           {
                           <option value="{{ $rowot->out_type_id }}">{{ $rowot->out_type_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Vendor Name</label>
                        <select name="vendorId" class="form-control select2" id="vendorId" required  onchange="getVendorPO(this.value);">
                           <option value="">--Select Vendor--</option>
                           @foreach($Ledger as  $rowvendor)
                           {
                           <option value="{{ $rowvendor->ac_code }}">{{ $rowvendor->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2" id="vpo_code1">
                     <div class="mb-3">
                        <label for="vpo_code" class="form-label">Cutting PO No</label>
                        <select name="vpo_code" class="form-select select2" id="vpo_code" onChange="getBalanceCutingdata(this.value);GetStockAssociationData(); EnableTrackCode(this);"  >
                           <option value="">--PO No--</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 hide" id="sample_indent_code1">
                     <div class="mb-3">
                        <label for="sample_indent_code" class="form-label">SIN No.</label>
                        <select name="sample_indent_code" class="form-select select2" id="sample_indent_code" onchange="GetSampleIndentMasterCustomerData(); EnableTrackCode(this);">
                           <option value="">-- Select --</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Main Style Category</label>
                        <select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value);">
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
                        <select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)">
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
                        <select name="fg_id" class="form-control" id="fg_id">
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
                        <input type="text" name="style_no" class="form-control" id="style_no" value="">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="track_code" class="form-label">Scan Roll No. Barcode</label>   
                        <input type="text" name="track_code" class="form-control" id="track_code" value="" onfocusout="getFabricDetails(this.value);"  disabled>
                     </div>
                  </div>
                  <div id="Error" class="alert alert-success"  ></div>
               </div>
               <div class="row">
                 <h4><b>Sample Indent</b></h4>
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_1" class="table table-bordered table-striped m-b-0  footable_1">
                           <thead>
                              <tr>
                                 <th>Sr No</th> 
                                 <th>Item Code</th>
                                 <th>Item Name</th>
                                 <th>Associated Stock</th>
                                 <th>Order Qty</th>
                                 <th>Actual Stock</th> 
                              </tr>
                           </thead>
                           <tbody id="SampleData"></tbody> 
                        </table>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <input type="number" value="0" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>TrackCode</th>
                                 <th>Supplier Roll No</th>
                                 <th>Item Name</th>
                                 <th>Color</th>
                                 <th>Quality</th>
                                 <th>Part</th>
                                 <th>Shade</th>
                                 <th>Width</th>
                                 <th>Meter</th>
                                 <th>Remove</th>
                              </tr>
                           </thead>
                           <tbody id="FabricData">
                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>Sr No</th>
                                 <th>TrackCode</th>
                                 <th>Supplier Roll No</th>
                                 <th>Item Name</th>
                                 <th>Color</th>
                                 <th>Quality</th>
                                 <th>Part</th>
                                 <th>Shade</th>
                                 <th>Width</th>
                                 <th>Meter</th>
                                 <th>Remove</th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_meter" class="form-label">Total Meter</label>
                        <input type="number" step="any"  name="total_meter" class="form-control" id="total_meter" value="0" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Total Taga</label>
                        <input type="number" name="total_taga_qty" class="form-control" id="total_taga_qty" value="0" readonly>
                     </div>
                  </div>
                  <div class="col-sm-8">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Narration</label>
                        <input type="text" name="in_narration" class="form-control" id="in_narration" value="" />
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <label for="formrow-inputState" class="form-label"></label>
                     <div class="form-group">
                        <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
                        <a href="{{ Route('FabricOutward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<div class="form-popup-bg">
  <div class="form-container">
    <button id="btnCloseForm" class="close-button">X</button>
    <h1 style="color: #db8e02;">Stock Deatils</h1>
    <div class="col-md-12">
         <table id="stockPopupTable" class="table  table-bordered table-striped m-b-0 footable_2 stripe row-border order-column" cellspacing="0" width="100%" style="color: antiquewhite!important;">
            <thead>
               <tr>
                  <th nowrap style="color: antiquewhite">Supplier Name</th>
                  <th nowrap style="color: antiquewhite">PO No</th>
                  <th nowrap style="color: antiquewhite">Stock Qty.</th>
               </tr>
            </thead>
            <tbody id="stockPopupBody">
               <tr>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
               </tr>
            </tbody>
            <tfoot id="stockPopupFoot">
            </tfoot>
         </table>
      </div>
  </div>
</div>  
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>

    function EnableTrackCode(row)
    {
        var id = $(row).val(); 
        
        if(id != '')
        {
            $("#track_code").removeAttr("disabled");
        }
        else
        {
            $("#track_code").attr("disabled");
        }

    }
        
    function closeForm() 
    {
        $('.form-popup-bg').removeClass('is-visible');
    }
    
    function stockPopup(row,item_code)
    {
        var po_code = $(row).parent().parent('tr').find('td select[name="po_code[]"]').val();  
        var vendorId = $("#vendorId").val();
        $.ajax(
        {
           type:"GET",
           dataType:'json',
           url: "{{ route('GetStockDetailPopupForFabric') }}",
           data:{'item_code':item_code, 'po_code':po_code,'vendorId':vendorId},
           success:function(response)
           {
                $("#stockPopupBody").html(response.html);
                $("#stockPopupFoot").html(response.html1);
                $('.form-popup-bg').addClass('is-visible');
           }
        });
   
    }
    
    $(document).ready(function($) 
    {
        $('.form-popup-bg').on('click', function(event) 
        {
            if ($(event.target).is('.form-popup-bg') || $(event.target).is('#btnCloseForm')) 
            {
              event.preventDefault();
              $(this).removeClass('is-visible');
            }
        });
    });


   function GetSampleIndentMasterCustomerData()
   {
       var sample_indent_code = $("#sample_indent_code").val();
       $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetSINCodeForFabricOutwardList') }}",
          data:{'sample_indent_code':sample_indent_code},
          success: function(data)
          {
              $("#SampleData").html(data.html); 
              
              $("#mainstyle_id").val(data.mainstyle_id);
              $("#substyle_id").val(data.substyle_id);
              $("#style_description").val(data.style_description);
              
              $("#mainstyle_id").attr('disabled', true);
              $("#substyle_id").attr('disabled', true);
              $("#style_description").attr('disabled', true);
          }
      });
   }
   
   
   function GetStockAssociationData()
   {
       var vpo_code = $("#vpo_code").val();
       $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetStockAssociationData') }}",
          data:{'vpo_code':vpo_code},
          success: function(data)
          {
              $("#SampleData").html(data.html); 
          }
      });
   }
   
   function GetSINCodeList()
   {
      $("#track_code").attr("disabled", true);
      var out_type_id = $("#out_type_id").val();
      if(out_type_id == 7)
      {
          $("#vpo_code1").addClass("hide");
          $("#sample_indent_code1").removeClass("hide");
          $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('GetSINCodeForTrimOutwardList') }}",
              success: function(data)
              {
                  $("#sample_indent_code").html(data.html); 
                  $("#vendorId").val(177).change();
                  $("#vendorId").attr('disabled', true);
              }
          });
      }
      else
      {
          
          $("#vpo_code1").removeClass("hide");
          $("#sample_indent_code1").addClass("hide");
      }
   }
   
   
    $(document).ready(function() {
          $('#frmData').submit(function() {
              $('#Submit').prop('disabled', true);
          }); 
    });
      
    $(document).on("change", 'input[name^="meters[]"]', function(event) {
        var $this = $(this); // Cache the current input element
        var value = parseInt($this.val());
        var row = $this.closest('tr');
        var row2 = $this.closest('td').find('span').html();
        var item_code = row.find("td select[name='item_code[]']").val();
        var track_code = row.find("td input[name='track_codes[]']").val();
        var maxLength = parseInt($this.attr('max'));
        var maxval = parseInt($this.attr('maxval'));
    
        // Check if the value exceeds the max limit
        if (value > maxLength) {
            alert('Value cannot be greater than ' + maxLength);
            $this.val(row2);
        }
        var total = 0 ;
        $("#footable_2 > tbody > tr").each(function()
        {
            var item_code1 = $(this).find('td select[name="item_code[]"]').val();
            if(item_code == item_code1)
            {
                total += parseFloat($(this).find('td input[name="meters[]"]').val()) || 0;
            }
        });
        
        
        $("#footable_2 > tbody > tr").each(function()
        {
            var item_code1 = $(this).find('td select[name="item_code[]"]').val();
            var meters1 = $(this).find('td input[name="meters[]"]').val();
            var max1 = $(this).find('td input[name="meters[]"]').attr('max');
            if(item_code == item_code1)
            {
                //$(this).find('td input[name="meters[]"]').attr("max", Math.abs(parseInt(maxval - total) + parseInt(meters1)));
               // $(this).find('td input[name="meters[]"]').attr("max", maxval);
                // var max2 = Math.abs(parseInt(maxval - total) + parseInt(meters1));
                // if(max2 > maxval)
                // {
                //     alert('Value cannot be greater than ' + maxval);
                //     $(this).find('td input[name="meters[]"]').val(maxval);
                // }
                // $(this).find('td input[name="meters[]"]').attr("max", max2);
            }
        });
       
    });

   $(document).on("keyup", 'input[name^="meters[]"]', function(event) {
        var $this = $(this); // Cache the current input element
        var value = parseInt($this.val());
        var row = $this.closest('tr');
        var row2 = $this.closest('td').find('span').html();
        var item_code = row.find("td select[name='item_code[]']").val();
        var track_code = row.find("td input[name='track_codes[]']").val();
        var maxLength = parseInt($this.attr('max'));
        var maxval = parseInt($this.attr('maxval'));
    
        // Check if the value exceeds the max limit
        if (value > maxLength) {
            alert('Value cannot be greater than ' + maxLength);
            $this.val(row2);
        }
        var total = 0 ;
        $("#footable_2 > tbody > tr").each(function()
        {
            var item_code1 = $(this).find('td select[name="item_code[]"]').val();
            if(item_code == item_code1)
            {
                total += parseFloat($(this).find('td input[name="meters[]"]').val()) || 0;
            }
        });
        
        
        $("#footable_2 > tbody > tr").each(function()
        {
            var item_code1 = $(this).find('td select[name="item_code[]"]').val();
            var meters1 = $(this).find('td input[name="meters[]"]').val();
            var max1 = $(this).find('td input[name="meters[]"]').attr('max');
            if(item_code == item_code1)
            {
                // $(this).find('td input[name="meters[]"]').attr("max", Math.abs(parseInt(maxval - total) + parseInt(meters1)));
                  // $(this).find('td input[name="meters[]"]').attr("max", maxval);
            }
        });
       
    });
   
   function getBalanceCutingdata()
   {
        var vpo_code=$('#vpo_code').val();
        $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('VendorPurchaseOrderDetails') }}",
              data:{'vpo_code':vpo_code},
              success: function(data)
              {
                  $("#vendorId").val(data[0]['vendorId']);
                  $("#mainstyle_id").val(data[0]['mainstyle_id']);
                  $("#substyle_id").val(data[0]['substyle_id']);
                  $("#style_no").val(data[0]['style_no']);
                  $("#fg_id").val(data[0]['fg_id']);
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
   
   
   window.onload = function()
   { 
        $("#Error").hide();
   };
   
   function EnableFields()
   {
       document.getElementById('mainstyle_id').disabled=false;
       document.getElementById('substyle_id').disabled=false;
       document.getElementById('fg_id').disabled=false;
       document.getElementById('style_description').disabled=false;
       document.getElementById('style_no').disabled=false;
       document.getElementById('vendorId').disabled=false;
       $("select").prop('disabled', false);
   }
   
   function getVendorPO(vendorId)
   {
       var process_id=1;
       var out_type_id = $("#out_type_id").val();
       
       $.ajax({
            dataType: "json",
              url: "{{ route('getVendorPO') }}",
              data:{'vendorId':vendorId,'process_id':process_id},
              success: function(data)
              {
                 $("#vpo_code").html(data.html);
              }
        });
        
        if(out_type_id == 2 || out_type_id == 3 || out_type_id == 6 || out_type_id == 5)
        {
            $("#track_code").removeAttr("disabled");
        }
        else
        {
            $("#track_code").attr("disabled");
        }
   }
   
   
   function getFabricDetails(track_code)
   {
      var vpo_code = $("#vpo_code").val();
      var out_type_id = $("#out_type_id").val();
      if(track_code!='')
      {
          var next=1;
          $("#footable_2 tbody tr").each(function() {
              var thisRow = $(this);
              var match = thisRow.find('input[name^="track_codes[]"]').val();
              if(match.toUpperCase() == track_code.toUpperCase()){next=2;}
          });

          if(next!=2)
          {
              $.ajax({
                  type: "GET",
                  url: "{{ route('FabricRecord') }}",
                  data:{'track_code':track_code,'vpo_code':vpo_code,'out_type_id':out_type_id},
                  success: function(response)
                  {
                      if(out_type_id == 1)
                      {
                          if(response.total_count == 0)
                          {
                              $("#Error").addClass("alert alert-danger").removeClass('alert-success').attr('style','display:block');
                              $('#Error').html('Track code roll item code not available in Cutting PO.');
                              setTimeout(hideDiv, 5000);
                          }
                          else if(response.html != 'zero')
                          {
                                var new_item_code = $(response.html).find('td select[name="item_code[]"]').val();
                                var item_found = false;
                                
                                $('#SampleData tr').each(function() 
                                {
                                    var exist_item_code = $(this).find('td:nth-child(2)').html();
                                    if (exist_item_code === new_item_code) {
                                        item_found = true;
                                        return false; // Exit the loop as we found a match
                                    }
                                }); 
                                
                                if(!item_found) 
                                {
                                    alert("Item code not found!");
                                }
                                else
                                {
                                    $("#FabricData").append(response.html);    
                                    mycalc();
                                    recalcIdcone();
                                }
                               
                               $("#Error").show();
                               $("#Error").addClass("alert alert-success");
                               $('#Error').html('Success');
                               setTimeout(hideDiv, 5000);
                               document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
                          }
                          else
                          {     $("#Error").show();
                                $("#Error").addClass("alert alert-danger");
                                $('#Error').html('This roll Already Used with total meter or QC Not Done!');
                                setTimeout(hideDiv, 5000);
                          }
                      }
                      else
                      {
                            var new_item_code = $(response.html).find('td select[name="item_code[]"]').val();
                            var item_found = false;
                            
                            $('#SampleData tr').each(function() 
                            {
                                var exist_item_code = $(this).find('td:nth-child(2)').html();
                                if (exist_item_code === new_item_code) {
                                    item_found = true;
                                    return false; // Exit the loop as we found a match
                                }
                            }); 
                            
                            if(out_type_id == 2 || out_type_id == 5 || out_type_id == 6)
                            {
                                $("#FabricData").append(response.html);
                                mycalc();
                                recalcIdcone();
                            }
                            else
                            {
                                if(!item_found) 
                                {
                                    alert("Item code not found!");
                                }
                                else
                                {
                                    $("#FabricData").append(response.html);
                                    mycalc();
                                    recalcIdcone();
                                }
                            }
                            
                           $("#Error").show();
                           $("#Error").addClass("alert alert-success");
                           $('#Error').html('Success');
                           setTimeout(hideDiv, 5000);
                           document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
                      }
                          
                    
                    $("#footable_2 > tbody > tr").each(function() {
                        var item_code = $(this).find("td select[name='item_code[]']").val();
                        var row = $(this).find("td input[name='meters[]']");
                        
                        $("#footable_1 > tbody > tr").each(function() 
                        {
                           if($(this).find('td:nth-child(2)').html() === item_code)
                           {
                               var assoc_qty = $(this).find('td:nth-child(4)').html();
                               var order_qty = $(this).find('td:nth-child(5)').html();
                               
                               $(row).attr("max",  Math.min(assoc_qty, order_qty)); 
                               $(row).attr("maxval",  Math.min(assoc_qty, order_qty)); 
                           }
                        });  
                    });
                  }
               }); 
              $('#track_code').val('');
              $('#track_code').focus();
                  
          }
          else
          {
              alert("This Roll Already in the list..!");
              next=1;
              $('#track_code').val('');
              $('#track_code').focus();
          }
        mycalc();
      }
      
      var total_taga = $("#footable_2 > tbody > tr").length; 
      $("#total_taga_qty").val(total_taga);
   }
   
   function hideDiv()
   {
      $("#Error").hide();
   }
   
   
   
   $(document).on('keyup','input[name^="meter[]"]', function(event) 
   {   
        mycalc();
   });
   
   var indexcone = 2;
   function insertcone(){
   
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
   var x = $("#color_id"),
   y = x.clone();
   y.attr("id","color_id");
   y.attr("name","color_id[]");
   y.width(100);
   y.appendTo(cell5);
   
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#part_id"),
   y = x.clone();
   y.attr("id","part_id");
   y.attr("name","part_id[]");
   y.width(100);
   y.appendTo(cell3);
   
   var cell3 = row.insertCell(3);
   var t3=document.createElement("select");
   var x = $("#quality_code"),
   y = x.clone();
   y.attr("id","quality_code");
   y.attr("name","quality_code[]");
   y.width(100);
   y.appendTo(cell3);
     
   var cell6 = row.insertCell(4);
   var t6=document.createElement("input");
   t6.style="display: table-cell; width:80px;";
   t6.type="text";
   t6.required="true";
   t6.id = "width"+indexcone;
   t6.name="width[]";
   t6.onkeyup=mycalc();
   t6.value="0";
   cell6.appendChild(t6);
   
   var t7=document.createElement("input");
   t7.style="display: table-cell; width:80px;";
   t7.type="hidden";
   t7.className="TAGAQTY";
   t7.required="true";
   t7.id = "taga_qty"+indexcone;
   t7.name="taga_qty[]";
   t7.onkeyup=mycalc();
   t7.value="1";
   cell6.appendChild(t7);
   
   var cell7 = row.insertCell(5);
   var t8=document.createElement("input");
   t8.style="display: table-cell; width:80px;";
   t8.type="text";
   t8.className="METER";
   t8.id = "meter"+indexcone;
   t8.name="meter[]";
   t8.onkeyup=mycalc();
   cell7.appendChild(t8);
   
   var cell7 = row.insertCell(6);
   var t7=document.createElement("input");
   t7.style="display: table-cell; width:80px;";
   t7.type="text";
   t7.id = "track_code"+indexcone;
   t7.name="track_code[]";
   cell7.appendChild(t7);
   
   var cell8=row.insertCell(7);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone()");
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
   document.getElementById("total_meter").value = sum1.toFixed(2);
   
   
   }
   
   function deleteRowcone(btn) {
   if(document.getElementById('cntrr').value > 0){
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
   
</script>
<!-- end row -->
@endsection