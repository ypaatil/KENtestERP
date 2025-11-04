@extends('layouts.master') 
@section('content')
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .required_label
    {
        color:red;
        font-weight:600;
        font-size:14px;
    }
 
    /* Style for the modal background */
    .modal {
      display: none; /* Hidden by default */
      position: fixed;
      z-index: 1;
      left: 160px;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5); /* Black with opacity */
    }

    /* Style for the modal content */
    .modal-content {
      background-color: white;
      margin: 5% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 1000px;
      border-radius: 10px;
      text-align: center;
    }

    /* Style for the close button */
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
    }

    /* Button style */
    .open-modal {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    .open-modal:hover {
      background-color: #45a049;
    }
    
    .text-right
    {
        text-align:right;
    }
    
    .groupbtn
    {
        display: flex;height: fit-content;
        text-align: justify;margin-top: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px auto;
      font-family: Arial, sans-serif;
    }
    th, td { 
      text-align: left;
      padding: 8px;
    }
    th {
      background-color: #f4f4f4;
      font-weight: bold;
    }
    tr:nth-child(even) {
      /*background-color: #f9f9f9;*/
    }
    /*tr:hover {*/
    /*  background-color: #f1f1f1;*/
    /*}*/
  </style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Opportunity - OP{{ $OpportunityMaster->opportunity_id}}</h4>
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
              <form action="{{ route('OpportunityMasterUpdate') }}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf
               <input type="hidden" name="opportunity_id" class="form-control" id="opportunity_id" value="{{ $OpportunityMaster->opportunity_id}}">
                <div class="row"> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="opportunity_date" class="form-label">Date<span class="required_label">*</span></label>
                        <input type="date" name="opportunity_date" class="form-control" id="opportunity_date" value="{{ $OpportunityMaster->opportunity_date}}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="opportunity_name" class="form-label">Opportunity Name<span class="required_label">*</span></label>
                        <input type="text" name="opportunity_name" class="form-control" id="opportunity_name" value="{{ $OpportunityMaster->opportunity_name}}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
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
                  <div class="col-md-2">
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
                   <div class="col-sm-4 mt-2">
                      <label for="formrow-inputState" class="form-label"></label>
                      <div class="form-group">
                         <button class="btn btn-primary w-md" onclick="EnableFeilds();">Update</button>
                         <a href="{{ Route('Opportunity.index') }}" class="btn btn-warning w-md">Cancel</a> 
                      </div>
                   </div>
               </div>
               </form>
               <div class="col-md-2">
                 <div class="mt-4"> 
                       <button class="open-modal" onclick="EditData(0,1);">Add New Record</button>
                   </div>
               </div>
               <div class="row"> 
                   <div class="table-responsive">
                      <table id="footable_2" class="table  table-bordered m-b-0  footable_2">
                         <thead> 
                            <tr>
                               <th>Sr No</th>
                               <th>Opportunity Detail Id</th>
                               <th>Style Category</th>
                               <th>Style Name</th> 
                               <th>FOB (INR)</th>  
                               <th>SAM</th>  
                               <th>Qty. in PCS</th>  
                               <th>Value. in INR</th>  
                               <th>Edit</th>
                               <th>Delete</th>
                            </tr>
                         </thead>
                         <tbody> 
                            @php
                                $sr_no = 1;
                            @endphp
                            @foreach($OpportunityDetails as $rows)
                            <tr>
                               <td>{{$sr_no++}}</td>
                               <td>OP{{$rows->opportunity_id}}/{{$rows->opportunity_detail_id}}</td>
                               <td> {{$rows->mainstyle_name}}</td>
                               <td>{{$rows->style_name}}</td> 
                               <td class="text-right">{{number_format($rows->fob_rate_inr, 2, '.', ',')}}</td> 
                               <td class="text-right">{{number_format($rows->sam, 2, '.', ',')}}</td> 
                               <td class="text-right">{{number_format($rows->quantity, 0, '.', ',')}}</td> 
                               <td class="text-right">{{ money_format("%!.0n",($rows->total_amount_inr))}}</td> 
                               <td>
                                  <a href="javascript:void(0);" style="width:40px;" id="Abutton0"   onclick="EditData({{$rows->opportunity_detail_id, $rows->opportunity_id}});" class="btn btn-warning pull-left"><i class="fas fa-pencil-alt"></i></a>  
                               </td> 
                               <td><input type="button" class="btn btn-danger pull-left" onclick="DeleteRow(this, {{$rows->opportunity_detail_id}}, {{$rows->opportunity_id}});" value="X"  style="margin-left:10px;"></td>
                            </tr>
                            @endforeach
                         </tbody>
                      </table>
                   </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <!--<button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFeilds();">Update</button>-->
                  </div>
               </div>
         </div>
            @endif
      </div>
      <!-- end card body -->
   </div>
   <!-- end card -->
</div>
<!-- end col --> 
<!-- end col -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="text-right"> 
            <span class="close">&times;</span>
        </div>
        <div class="OppFrm"></div>
    </div>
</div>
</div>
<!-- end row --> 
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
     
    $(document).ready(function () 
    {
        // Show the modal (you might already have a function for this)
       // $('#myModal').fadeIn();
    
        // Close the modal when clicking the close button
        $('.close').on('click', function () {
            $('#myModal').fadeOut();
        });
    
        // Prevent closing the modal on clicks inside the modal content
        $('.modal-content').on('click', function (e) {
            e.stopPropagation();
        });
    
        // Optionally, clicking outside the modal-content does nothing
        $('#myModal').on('click', function (e) {
            e.preventDefault(); // Ensures no unwanted behavior
        });
    });

    
    function EditData(opportunity_detail_id, opportunity_id)
    {
        var opportunity_id = $("#opportunity_id").val();
        $(".OppFrm").html(''); 
        $.ajax({
           url: "{{route('OpportunityEdit')}}",
           type: "GET",
           data: { "opportunity_detail_id": opportunity_detail_id, "opportunity_id": opportunity_id },
           success: function(data)
           { 
                $(".OppFrm").html(data.html); 
                $('#myModal').fadeIn();
           }
        });
    }
   
    function CalculateFOB(row)
    {
        var fob = $('td input[name="fob_rate"]').val();
        var exchange = $('td input[name="exchange_rate"]').val();
        var fob_rate_inr = parseFloat(fob) * parseFloat(exchange);
        $('td input[name="fob_rate_inr"]').val(fob_rate_inr ? fob_rate_inr : 0);
        CalculateAmount(row);
    }
    
    function CalculateCMOHP(row)
    {
        console.log("hii");
        var CM = $('td input[name="CM"]').val() ? $('td input[name="CM"]').val() : 0;
        var OH = $('td input[name="OH"]').val() ? $('td input[name="OH"]').val() : 0; 
        var P = $('td input[name="P"]').val() ? $('td input[name="P"]').val() : 0; 
        var sam = $('td input[name="sam"]').val() ? $('td input[name="sam"]').val() : 0;
        var quantity = $('td input[name="quantity"]').val() ? $('td input[name="quantity"]').val() : 0;
        
        var CMOHP_value = parseFloat((parseFloat(CM)+parseFloat(OH)+parseFloat(P))*quantity);
        var CMOHP_min = parseFloat((parseFloat(CM)+parseFloat(OH)+parseFloat(P))/sam);
        
        $('td input[name="CMOHP_value"]').val(CMOHP_value);
        $('td input[name="CMOHP_min"]').val(parseFloat(CMOHP_min).toFixed(2));
        CalculateAmount(row);
        
    }
    
    function CalculateAmount(row)
    {
        var quantity = $('td input[name="quantity"]').val() ? $('td input[name="quantity"]').val() : 0;
        var fob_rate_inr =  $('td input[name="fob_rate_inr"]').val() ? $('td input[name="fob_rate_inr"]').val() : 0;
        
        var total_amount = parseFloat(quantity * fob_rate_inr);
        $('td input[name="total_amount_inr"]').val(total_amount);
    }
    
    function CalculateMinutes(row)
    {
        var quantity = $('td input[name="quantity"]').val() ? $('td input[name="quantity"]').val() : 0;
        var sam =  $('td input[name="sam"]').val()? $('td input[name="sam"]').val() : 0;
        
        var total_min = parseFloat(quantity * sam);
        $('td input[name="total_minute"]').val(total_min);
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
        $(newRow).find('input[name="sr_no"]').val(''); // Adjust if necessary
        $(newRow).find('input, select').not('input[name="sr_no"]').val('');
    
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
    
    var isDropdownOpen = false;
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
   
     
    function DeleteRow(row, opportunity_detail_id, opportunity_id) 
    {
        swal({
            title: "Confirm Delete?",
            text: "Are you sure you want to permanently delete this opportunity details?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                // If user confirms, proceed with deletion
                $.ajax({
                    type: "GET",
                    url: "{{ route('DeleteOpportunityDetail') }}",
                    data: { "opportunity_detail_id": opportunity_detail_id, "opportunity_id": opportunity_id },
                    success: function(data) {
                        // Remove the row from the table
                        var row1 = row.parentNode.parentNode;
                        row1.parentNode.removeChild(row1);
    
                        // Show success message
                        swal("Deleted!", "The opportunity detail has been deleted.", "success");
                    },
                    error: function() {
                        // Handle errors gracefully
                        swal("Error!", "There was an issue deleting the record. Please try again later.", "error");
                    }
                });
            } else {
                // If user cancels the deletion
                swal("Cancelled", "The opportunity detail is safe!", "info");
            }
        });
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