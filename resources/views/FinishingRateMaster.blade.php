@extends('layouts.master') 
@section('content')
<style>
    .hide{
        display:none;
    }
</style>

<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Finishing Rate Master</h4>
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
            <form action="{{route('FinishingRate.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <div class="row"> 
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" required  onchange="getBrandList();" >
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row) 
                                <option value="{{ $row->ac_code }}">{{ $row->ac_short_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Brand</label>
                        <select name="brand_id" class="form-select select2" id="brand_id" required>
                           <option value="">--Brand--</option> 
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="substyle_id" class="form-label">Style Name</label>
                        <select name="substyle_id" class="form-select select2" id="substyle_id" required>
                           <option value="">--Select Style--</option>
                           @foreach($SubStyleList as  $row)
                              <option value="{{ $row->substyle_id }}">{{ $row->substyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row"> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                           <thead>
                              <tr>
                                 <th>Date</th>
                                 <th>Quality Rate</th>
                                 <th>Packing Rate</th>
                                 <th>Kaj Button Rate</th>
                                 <th>Add/Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td><input type="date" name="finishing_rate_date[]"  class="form-control finishing-rate-date" value="{{date('Y-m-d')}}" id="id" style="width:150px;" required/></td>
                                 <td><input type="number" step="any" name="finishing_rate[]"  class="form-control decimal-input"  value="0" style="width:150px;" required/></td>
                                 <td><input type="number" step="any" name="packing_rate[]"  class="form-control decimal-input"  value="0" style="width:150px;" required/></td>
                                 <td><input type="number" step="any" name="kaj_button_rate[]"  class="form-control decimal-input"  value="0" style="width:150px;" required/></td>
                                 <td><button type="button" onclick="AddNew();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X"  style="margin-left:10px;"></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFields();">Submit</button>
                     <a href="{{ Route('FinishingRate.index') }}" class="btn btn-warning w-md">Cancel</a>
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
 
     $(document).on('input', '.decimal-input', function () {
        let value = $(this).val();
        // Regular expression to match up to 3 decimal places
        if (!/^\d*\.?\d{0,2}$/.test(value)) {
            $(this).val(value.slice(0, -1)); // Remove the last invalid character
        }
     });
 
     $(document).on('change', '.finishing-rate-date', function() {
        let selectedDates = [];
        let isDuplicate = false;
    
        $('.finishing-rate-date').each(function() {
            const dateValue = $(this).val();
            if (selectedDates.includes(dateValue)) {
                isDuplicate = true;
                return false; // Break the loop if duplicate is found
            }
            if (dateValue) {
                selectedDates.push(dateValue);
            }
        });
    
        if (isDuplicate) {
            alert("Duplicate date found! Please select a different date.");
            $(this).val(''); // Clear the duplicated date
        }
    });

    function AddNew()
    {
        var newRow = $('#footable_2 tbody tr:last').clone();
 
        newRow.find('input[name="finishing_rate_date[]"]').val(''); 
 
        $('#footable_2 tbody').append(newRow);
    }
    
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
    
 
   function getBrandList() 
   { 
       var Ac_code = $("#Ac_code").val();  
       $.ajax({
           type: "GET",
           url: "{{ route('BrandList') }}",
           data:{'Ac_code':Ac_code },
           success: function(data)
           {
             $("#brand_id").html(data.html);
           }
       });
   }  
   
   
   function EnableFields()
   {
      $("select").removeAttr('disabled');
      $("input").removeAttr('disabled');
   }
   
   
   
   function deleteRowcone1(btn) 
   { 
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row); 
   }
   
   
    function getSubStyle(val) 
    {	//alert(val);
       $.ajax({
       type: "GET",
       url: "{{ route('SubStyleList') }}",
       data:'mainstyle_id='+val,
       success: function(data){
       $("#substyle_id").html(data.html);
       }
       });
    }   
        
    function getStyle(val) 
    {	//alert(val);
   
      $.ajax({
       type: "GET",
       url: "{{ route('StyleList') }}",
       data:{'substyle_id':val, },
       success: function(data){
       $("#fg_id").html(data.html);
       }
       });
    }  
   
   
</script>
<!-- end row -->
@endsection