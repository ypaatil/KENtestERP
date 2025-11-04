@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Finishing Rate Master Edit</h4>
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
            @if(isset($FinishingRateMaster))
            <form action="{{ route('FinishingRate.update',$FinishingRateMaster) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
                  <input type="hidden" name="finishing_rate_code" class="form-control" id="finishing_rate_code" value="{{ $FinishingRateMaster->finishing_rate_code}}">
                  <div class="row"> 
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-select" id="Ac_code" disabled  >
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row) 
                                <option value="{{ $row->ac_code }}" {{ $row->ac_code == $FinishingRateMaster->Ac_code ? 'selected="selected"' : '' }} >{{ $row->ac_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Brand</label>
                        <select name="brand_id" class="form-select" id="brand_id" disabled>
                           <option value="">--Brand--</option>
                           @foreach($BrandList as  $row)
                              <option value="{{ $row->brand_id }}" {{ $row->brand_id == $FinishingRateMaster->brand_id ? 'selected="selected"' : '' }} >{{ $row->brand_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="substyle_id" class="form-label">Style Name</label>
                        <select name="substyle_id" class="form-select select2" id="substyle_id" disabled>
                           <option value="">--Select Style--</option>
                           @foreach($SubStyleList as  $row)
                              <option value="{{ $row->substyle_id }}"  {{ $row->substyle_id == $FinishingRateMaster->substyle_id ? 'selected="selected"' : '' }}  >{{ $row->substyle_name }}</option>
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
                              @foreach($FinishingRateDetailList as $row)
                              <tr>
                                 <td><input type="date" name="finishing_rate_date[]"  class="form-control finishing-rate-date" value="{{$row->finishing_rate_date}}" id="id" style="width:150px;" required /></td>
                                 <td><input type="number" step="any" name="finishing_rate[]"  class="form-control decimal-input"  value="{{$row->finishing_rate}}" style="width:150px;" required  /></td>
                                 <td><input type="number" step="any" name="packing_rate[]"  class="form-control decimal-input"  value="{{$row->packing_rate}}" style="width:150px;" required  /></td>
                                 <td><input type="number" step="any" name="kaj_button_rate[]"  class="form-control decimal-input"  value="{{$row->kaj_button_rate}}" style="width:150px;" required  /></td>
                                 <td><button type="button" onclick="AddNew();" class="btn btn-warning pull-left">+</button>
                                 </td>
                              </tr>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFeilds();">Update</button>
                     <a href="{{ Route('FinishingRate.index') }}" class="btn btn-warning w-md">Cancel</a>
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
    
   function getSalesOrderDetails(sales_order_no)
   {
         $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('SalesOrderDetails') }}",
               data:{'sales_order_no':sales_order_no},
               success: function(data)
               {
                   
                   $("#brand_id").val(data[0]['brand_id']);
                   $("#Ac_code").val(data[0]['Ac_code']); 
                   $("#mainstyle_id").val(data[0]['mainstyle_id']); 
                   $("#fg_id").val(data[0]['fg_id']); 
                   
                   $('#brand_id').attr('disabled', true);
                   $('#Ac_code').attr('disabled', true);
                   $('#mainstyle_id').attr('disabled', true);
                   $('#fg_id').attr('disabled', true);
               }
           });
     
   }
   
   
   function EnableFeilds()
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
@endsection