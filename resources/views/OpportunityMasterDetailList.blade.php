@extends('layouts.master') 
@section('content')
<style>
    .required_label
    {
        color:red;
        font-weight:600;
        font-size:14px;
    }
</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Opportunity Edit</h4>
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
            @if(isset($OpportunityMaster))
            <form action="{{ route('Opportunity.update',$OpportunityMaster) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <input type="hidden" name="opportunity_id" class="form-control" id="opportunity_id" value="{{ $OpportunityMaster->opportunity_id}}">
                <div class="row"> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="opportunity_date" class="form-label">Date<span class="required_label">*</span></label>
                        <input type="date" name="opportunity_date" class="form-control" id="opportunity_date" value="{{ $OpportunityMaster->opportunity_date}}" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer Name  <span class="required_label">*</span></label>
                        <select name="Ac_code" class="form-select" id="Ac_code" required>
                           <option value="">--Select--</option>
                           @foreach($BuyerList as  $row) 
                                <option value="{{ $row->crm_id }}"  {{ $row->crm_id == $OpportunityMaster->Ac_code ? 'selected="selected"' : '' }}>{{ $row->buyer_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Buyer Brand  <span class="required_label">*</span></label>
                        <select name="brand_id" class="form-select" id="brand_id" required>
                           <option value="">--Select--</option>
                           @foreach($BuyerList as  $row) 
                                <option value="{{ $row->crm_id }}"  {{ $row->crm_id == $OpportunityMaster->brand_id ? 'selected="selected"' : '' }}>{{ $row->buyer_brand }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
               </div>
               <div class="row"> 
                   <div class="table-responsive">
                      <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                         <thead> 
                            <tr>
                               <th>Sr No</th>
                               <th>Style Category</th>
                               <th>Style Name</th>
                               <th>Style Descriptions</th> 
                               <th>Product Image</th> 
                               <th>Product Link if Any</th> 
                               <th>Gender</th> 
                               <th>Fabric Details</th> 
                               <th>Size Range</th> 
                               <th>SAM</th> 
                               <th>Quantity</th> 
                               <th>Currency</th> 
                               <th>FOB</th> 
                               <th>Exchange Rate</th> 
                               <th>FOB Rate (INR)</th> 
                               <th>CM</th> 
                               <th>OH</th> 
                               <th>P</th> 
                               <th>CMOHP Value</th> 
                               <th>CMOHP/Min</th> 
                               <th>Total Amount (INR)</th> 
                               <th>Total Minute</th> 
                               <th>Stage</th> 
                               <th>Remark</th>
                               <th>Add/Remove</th>
                            </tr>
                         </thead>
                         <tbody> 
                            @php
                                $sr_no = 1;
                            @endphp
                            @foreach($OpportunityDetails as $rows)
                            <tr>
                               <td><input type="text" name="sr_no[]" value="{{$sr_no++}}" class="form-control" id="id0" style="width:50px;" readonly/></td>
                               <td> 
                                    <select name="main_style_id[]" class="form-select" id="main_style_id" style="width:250px;" >
                                       <option value="">--Select--</option>
                                       @foreach($MainStyleList as  $row) 
                                            <option value="{{ $row->mainstyle_id }}"  {{ $row->mainstyle_id == $rows->main_style_id ? 'selected="selected"' : '' }}>{{ $row->mainstyle_name }}</option> 
                                       @endforeach
                                    </select>
                               </td>
                               <td><input type="text" name="style_name[]" class="style_name form-control" value="{{$rows->style_name}}" id="style_name" style="width:250px;" /></td>
                               <td><input type="text" name="style_description[]" class="style_description form-control" value="{{$rows->style_description}}" id="style_description" style="width:300px;" /></td> 
                               <td><input type="file" name="product_image[]" class="product_image form-control" id="product_image" style="width:250px;" /></td>
                               <td><input type="text" name="product_url[]" class="product_url form-control" value="{{$rows->product_url}}" id="product_url" style="width:300px;" /></td> 
                               <td>
                                   <select name="gender_id[]" class="form-select" id="gender_id" style="width:250px;" >
                                       <option value="">--Select--</option>
                                       @foreach($GenderList as  $row) 
                                            <option value="{{ $row->gender_id }}"  {{ $row->gender_id == $rows->gender_id ? 'selected="selected"' : '' }}>{{ $row->gender_name }}</option> 
                                       @endforeach
                                    </select>
                                </td> 
                               <td><input type="text" name="fabric_details[]" class="fabric_details form-control" value="{{$rows->fabric_details}}" id="fabric_details" style="width:300px;" /></td> 
                               <td><input type="text" name="size_range[]" class="size_range form-control" value="{{$rows->size_range}}" id="size_range" style="width:300px;" /></td> 
                               <td><input type="number" step="any" name="sam[]" class="sam form-control" value="{{$rows->sam}}" onchange="CalculateCMOHP(this);CalculateMinutes(this);"  id="sam" style="width:300px;" /></td> 
                               <td><input type="number" step="any" name="quantity[]" class="quantity form-control" value="{{$rows->quantity}}" id="quantity" onchange="CalculateCMOHP(this); CalculateAmount(this);CalculateMinutes(this);"  style="width:300px;" /></td> 
                               <td>
                                   <select name="cur_id[]" class="form-select" id="cur_id" style="width:250px;" >
                                       <option value="">--Select--</option>
                                       @foreach($CurrencyList as  $row) 
                                            <option value="{{ $row->cur_id }}" {{ $row->cur_id == $rows->cur_id ? 'selected="selected"' : '' }}>{{ $row->currency_name }}</option> 
                                       @endforeach
                                    </select>
                                </td> 
                               <td><input type="number" step="any" name="fob_rate[]" class="fob_rate form-control" value="{{$rows->fob_rate}}" id="fob_rate" onchange="CalculateFOB(this);" style="width:300px;" /></td> 
                               <td><input type="number" step="any" name="exchange_rate[]" class="exchange_rate form-control" value="{{$rows->exchange_rate}}" id="exchange_rate" onchange="CalculateFOB(this);"  style="width:300px;" /></td>  
                               <td><input type="number" step="any" name="fob_rate_inr[]" class="fob_rate_inr form-control" value="{{$rows->fob_rate_inr}}" id="fob_rate_inr" onchange="CalculateAmount(this);" style="width:300px;" /></td> 
                               <td><input type="number" step="any" name="CM[]" class="CM form-control" value="{{$rows->CM}}" id="CM" onchange="CalculateCMOHP(this);" style="width:300px;" /></td>  
                               <td><input type="number" step="any" name="OH[]" class="OH form-control" value="{{$rows->OH}}" id="OH" onchange="CalculateCMOHP(this);"  style="width:300px;" /></td> 
                               <td><input type="number" step="any" name="P[]" class="P form-control" value="{{$rows->P}}" id="P" onchange="CalculateCMOHP(this);"  style="width:300px;" /></td> 
                               <td><input type="number" step="any" name="CMOHP_value[]" class="CMOHP_value form-control" value="{{$rows->CMOHP_value}}" id="CMOHP_value" style="width:300px;" readonly /></td> 
                               <td><input type="number" step="any" name="CMOHP_min[]" class="CMOHP_min form-control" value="{{$rows->CMOHP_min}}" id="CMOHP_min" style="width:300px;"  readonly/></td> 
                               <td><input type="number" step="any" name="total_amount_inr[]" class="total_amount_inr form-control" value="{{$rows->total_amount_inr}}" id="total_amount_inr" style="width:300px;" readonly /></td> 
                               <td><input type="number" step="any" name="total_minutes[]" class="total_minutes form-control" value="{{$rows->total_minutes}}" id="total_minutes" style="width:300px;" readonly/></td> 
                               <td>
                                   <select name="opportunity_stage_id[]" class="form-select" id="opportunity_stage_id" style="width:250px;" >
                                       <option value="">--Select--</option>
                                       @foreach($StatgeList as  $row) 
                                            <option value="{{ $row->opportunity_stage_id }}" {{ $row->opportunity_stage_id == $rows->opportunity_stage_id ? 'selected="selected"' : '' }}>{{ $row->opportunity_stage_name }}</option> 
                                       @endforeach
                                    </select>
                                </td> 
                               <td><input type="text" name="remark[]" class="remark form-control" value="{{$rows->remark}}" id="remark" style="width:300px;" /></td>  
                               <td>
                                  <a href="javascript:void(0);" style="width:40px;" id="Abutton0"   onclick="AddNew(this);" class="Abutton btn btn-warning pull-left">+</a> 
                                  <a href="javascript:void(0);" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" style="margin-left:5px;" >X</a> 
                               </td>
                            </tr>
                            @endforeach
                         </tbody>
                      </table>
                   </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFeilds();">Update</button>
                     <a href="{{ Route('Opportunity.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script>
 
 
    function CalculateFOB(row)
    {
        var fob = $(row).closest('tr').find('td input[name="fob_rate[]"]').val();
        var exchange = $(row).closest('tr').find('td input[name="exchange_rate[]"]').val();
        var fob_rate_inr = parseFloat(fob) * parseFloat(exchange);
        $(row).parent().parent('tr').find('td input[name="fob_rate_inr[]"]').val(fob_rate_inr ? fob_rate_inr : 0);
        CalculateAmount(row);
        
    }
    
    function CalculateCMOHP(row)
    {
        var CM = $(row).closest('tr').find('td input[name="CM[]"]').val() ? $(row).closest('tr').find('td input[name="CM[]"]').val() : 0;
        var OH = $(row).closest('tr').find('td input[name="OH[]"]').val() ? $(row).closest('tr').find('td input[name="OH[]"]').val() : 0; 
        var P = $(row).closest('tr').find('td input[name="P[]"]').val() ? $(row).closest('tr').find('td input[name="P[]"]').val() : 0; 
        var sam = $(row).closest('tr').find('td input[name="sam[]"]').val() ? $(row).closest('tr').find('td input[name="sam[]"]').val() : 0;
        var quantity = $(row).closest('tr').find('td input[name="quantity[]"]').val() ? $(row).closest('tr').find('td input[name="quantity[]"]').val() : 0;
        
        var CMOHP_value = parseFloat((parseFloat(CM)+parseFloat(OH)+parseFloat(P))*quantity);
        var CMOHP_min = parseFloat((parseFloat(CM)+parseFloat(OH)+parseFloat(P))/sam);
        
        $(row).closest('tr').find('td input[name="CMOHP_value[]"]').val(CMOHP_value);
        $(row).closest('tr').find('td input[name="CMOHP_min[]"]').val(parseFloat(CMOHP_min).toFixed(2));
        CalculateAmount(row);
        
    }
    
    function CalculateAmount(row)
    {
        var quantity = $(row).closest('tr').find('td input[name="quantity[]"]').val() ? $(row).closest('tr').find('td input[name="quantity[]"]').val() : 0;
        var fob_rate_inr =  $(row).parent().parent('tr').find('td input[name="fob_rate_inr[]"]').val() ? $(row).closest('tr').find('td input[name="fob_rate_inr[]"]').val() : 0;
        
        var total_amount = parseFloat(quantity * fob_rate_inr);
        $(row).closest('tr').find('td input[name="total_amount_inr[]"]').val(total_amount);
    }
    
    function CalculateMinutes(row)
    {
        var quantity = $(row).closest('tr').find('td input[name="quantity[]"]').val() ? $(row).closest('tr').find('td input[name="quantity[]"]').val() : 0;
        var sam =  $(row).parent().parent('tr').find('td input[name="sam[]"]').val()? $(row).closest('tr').find('td input[name="sam[]"]').val() : 0;
        
        var total_min = parseFloat(quantity * sam);
        $(row).closest('tr').find('td input[name="total_minutes[]"]').val(total_min);
    }
    
    function recalcIdcone()
    {
       $.each($("#footable_2 tr"),function (i,el)
       {
         $(this).find("td:first input").val(i); 
       });
    }
   
    function AddNew() 
    { 
        $('select').select2('destroy');
        var newRow = $('#footable_2 tbody tr:last').clone();
                    
        var isDropdownOpen = false;
    
        // Reset input fields in the cloned row (except sr_no[])
        $(newRow).find('input[name="sr_no[]"]').val(''); // Adjust if necessary
        $(newRow).find('input, select').not('input[name="sr_no[]"]').val('');
    
        $('#footable_2 tbody').append(newRow);
        
        // Re-initialize select2 for all selects in the table
        $('#footable_2 tbody select').select2();
    
        // Recalculate any ID or index if required
        recalcIdcone();
    }
    
    // Event delegation for dynamically added selects
    $('#footable_2').on('mouseenter', 'select', function() {
        if (!$(this).data('select2')) {
            $(this).select2();
        }
    });
    
    $('#footable_2').on('mouseleave', 'select', function() {
        var $this = $(this); 
        setTimeout(function() { 
            if (!isDropdownOpen && !$this.is(':hover')) {
                $this.select2('destroy');
            }
        }, 5000); 
    });
    
    $('#footable_2').on('select2:open', 'select', function() {
        isDropdownOpen = true;
    });
    
    $('#footable_2').on('select2:close', 'select', function() {
        isDropdownOpen = false;
    });
    
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
        
        var isDropdownOpen = false;
        
        $('select').on('mouseenter', function() {
            // Initialize select2 if it's not already initialized
            if (!$(this).data('select2')) {
                $(this).select2();
            }
        });
        
        $('select').on('mouseleave', function() {
            var $this = $(this);
            // Delay destroying select2 until the dropdown is closed
            setTimeout(function() {
                // Only destroy select2 if dropdown is not open and mouse has fully left
                if (!isDropdownOpen && !$this.is(':hover')) {
                    $this.select2('destroy');
                }
            }, 5000); // Delay to allow mouseleave event to finish
        });
        
        // Detect when the dropdown is opened
        $('select').on('select2:open', function() {
            isDropdownOpen = true;
        });
        
        // Detect when the dropdown is closed
        $('select').on('select2:close', function() {
            isDropdownOpen = false;
        });
    });
     
   
   function EnableFields()
   {
      $("select").removeAttr('disabled');
      $("input").removeAttr('disabled');
   }
   
 
   function deleteRow(btn) 
   {  
       var rowCount = $('#footable_2 tbody tr').length;
       if(rowCount > 1) 
       {
           var row = btn.parentNode.parentNode;
           row.parentNode.removeChild(row); 
           recalcIdcone();
       }
   }
   
   function getState(val) 
   {	//alert(val);
       $.ajax({
       type: "GET",
       url: "{{ route('StateList') }}",
       data:'country_id='+val,
       success: function(data){
       $("#state_id").html(data.html);
       }
       });
   } 
 
</script>
@endsection