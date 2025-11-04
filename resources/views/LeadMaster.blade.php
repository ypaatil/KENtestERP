@extends('layouts.master') 
@section('content')
<style>
    .hide{
        display:none;
    } 
    
    .required_label
    {
        color:red;
        font-weight:600;
        font-size:14px;
    } 
    .select2-container--default
    {
        display: block !important; /* Make sure it's shown */
    }
</style>   
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Lead Master</h4>
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
            <form action="{{route('Lead.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <div class="row"> 
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="buyer_name" class="form-label">Buyer Name  <span class="required_label">*</span></label>
                        <input type="text" name="buyer_name" class="form-control" id="buyer_name" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="buyer_brand" class="form-label">Buyer Brand  <span class="required_label">*</span></label>
                        <input type="text" name="buyer_brand" class="form-control" id="buyer_brand" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="" class="form-label">Buyer Type  <span class="required_label">*</span></label> 
                        <select name="buyer_type_id" class="form-select" id="buyer_type_id" required>
                           <option value="">--Select--</option>
                           @foreach($BuyerTypeList as  $row) 
                                <option value="{{ $row->buyer_type_id }}">{{ $row->buyer_type_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_group_id" class="form-label">Market  <span class="required_label">*</span></label>
                        <select name="order_group_id" class="form-select" id="order_group_id" required>
                           <option value="">--Select--</option>
                           @foreach($OrderGroupList as  $row) 
                                <option value="{{ $row->og_id }}">{{ $row->order_group_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3"> 
                        <label for="cur_id" class="form-label">Currency  <span class="required_label">*</span></label> 
                        <select name="cur_id" class="form-select" id="cur_id" required>
                           <option value="">--Select--</option>
                           @foreach($CurrencyList as  $row)
                              <option value="{{ $row->cur_id }}">{{ $row->currency_name }}</option>
                           @endforeach
                        </select> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="" class="form-label">Country  <span class="required_label">*</span></label>
                        <select name="country_id" class="form-select" id="country_id" onchange="getState(this.value);" required>
                           <option value="">--Select--</option>
                           @foreach($countryList as  $row)
                              <option value="{{ $row->c_id }}">{{ $row->c_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="state_id" class="form-label">State</label>
                        <select name="state_id" class="form-select select2" id="state_id">
                           <option value="">--Select--</option>
                           @foreach($StateList as  $row)
                              <option value="{{ $row->state_id }}">{{ $row->state_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="city_name" class="form-label">City</label>
                        <input type="text" name="city_name" class="form-control" id="city_name" value="" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="zip_code" class="form-label">Zip/Postal Code</label>
                        <input type="text" name="zip_code" class="form-control" id="zip_code" value="" >
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="street_name" class="form-label">Street</label>
                        <textarea  class="form-control" name="street_name" cols="12"  id="street_name" ></textarea> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="stage_id" class="form-label">Stage  <span class="required_label">*</span></label>
                        <select name="stage_id" class="form-select" id="stage_id" required>
                           <option value="">--Select--</option>
                           @foreach($StatgeList as  $row)
                              <option value="{{ $row->stage_id }}">{{ $row->stage_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="compliant_status_id" class="form-label">Compliant/Non-compliant  <span class="required_label">*</span></label>
                        <select name="compliant_status_id" class="form-select" id="compliant_status_id" required>
                           <option value="">--Select--</option>
                           @foreach($CompliantList as  $row) 
                                <option value="{{ $row->compliant_status_id }}">{{ $row->compliant_status_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="lead_status_id" class="form-label">Lead Status  <span class="required_label">*</span></label> 
                        <select name="lead_status_id" class="form-select" id="lead_status_id" required>
                           <option value="">--Select--</option>
                           @foreach($LeadStatusList as  $row)
                              <option value="{{ $row->lead_status_id }}">{{ $row->lead_status_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ownership_name" class="form-label">Ownership Name</label>
                        <input type="text" name="ownership_name" class="form-control" id="ownership_name" value="" >
                     </div>
                  </div>
               </div>
               <div class="row"> 
                   <div class="table-responsive">
                      <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                         <thead> 
                            <tr>
                               <th>Sr No</th>
                               <th>Contact Name  <span class="required_label">*</span></th>
                               <th>Contact Number</th>
                               <th>Email ID</th> 
                               <th>Add/Remove</th>
                            </tr>
                         </thead>
                         <tbody> 
                            <tr>
                               <td><input type="text" name="sr_no[]" value="1" class="form-control" id="id0" style="width:50px;" readonly/></td>
                               <td><input type="text" name="contactName[]" class="contactName form-control" value="" id="contactName" style="width:300px;" required /></td>
                               <td><input type="text" name="contactNo[]" class="contactNo form-control" value="" id="contactNo" style="width:250px;" /></td>
                               <td><input type="text" name="email[]" class="email form-control" value="" id="email" style="width:300px;" /></td> 
                               <td>
                                  <button style="width:40px;" id="Abutton0"  name="button[]" onclick="AddNew(this);" class="Abutton btn btn-warning pull-left">+</button> 
                                  <button id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRow(this);" style="margin-left:5px;" >X</button> 
                               </td>
                            </tr>
                         </tbody>
                      </table>
                   </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFields();">Submit</button>
                     <a href="{{ Route('Lead.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<!-- end row -->
<script>
 
    function recalcIdcone()
    {
       $.each($("#footable_2 tr"),function (i,el)
       {
         $(this).find("td:first input").val(i); 
       });
    }
    
    function AddNew()
    {
        var newRow = $('#footable_2 tbody tr:last').clone();
        $(newRow).not('input[name="sr_no[]"]').find('input').val(''); 
        $('#footable_2 tbody').append(newRow);
        recalcIdcone();
    }
    
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
<!-- end row -->
@endsection