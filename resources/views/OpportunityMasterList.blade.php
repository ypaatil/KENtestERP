@extends('layouts.master') 
@section('content')   
<style>
    /* Style for the modal background */
    .modal {
      display: none; /* Hidden by default */
      position: fixed;
      z-index: 3;
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
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 700px;
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
    .text-left
    {
        text-align:left;
    }
  </style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Opportunity List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Opportunity List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-md-6">
       <button class="open-modal" onclick="EditData(0);">Add New Record</button>
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr No</th>
                     <th>Opportunity Date</th>
                     <th>Opportunity Id</th>
                     <th>Opportunity Name</th>
                     <th>Buyer Name</th>
                     <th>Buyer Brand</th>  
                     <th>User</th>  
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1; 
                  @endphp
                  @foreach($OpportunityData as $row)    
                  <tr>
                     <td>{{ $srno++ }}</td>
                     <td>{{ date("d-m-Y", strtotime($row->opportunity_date)) }}</td>
                     <td>OP{{ $row->opportunity_id }}</td>
                     <td>{{ $row->opportunity_name }}</td>
                     <td>{{ $row->buyer_name }}</td>
                     <td>{{ $row->buyer_brand }}</td>   
                     <td>{{ $row->username }}</td>   
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('Opportunity.edit', $row->opportunity_id)}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a>
                     </td> 
                     <td>
                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->opportunity_id }}"  data-route="{{route('Opportunity.destroy', $row->opportunity_id )}}" title="Delete">
                        <i class="fas fa-trash"></i>
                        </button> 
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="text-right">
            <span class="close">&times;</span>
        </div>
        <div class="OppFrm"></div>
    </div>
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 


 
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

    
   function EditData(opportunity_id)
   {
        $(".OppFrm").html(''); 
        $.ajax({
           url: "{{route('OpportunityCreate')}}",
           type: "GET",
           success: function(data)
           { 
                $(".OppFrm").html(data.html); 
                $('#myModal').fadeIn();
           }
        });
   }
   
   $(document).on('click','#DeleteRecord',function(e) 
   {
       var Route = $(this).attr("data-route");
       var id = $(this).data("id");
       var token = $(this).data("token");
        
       if (confirm("Are you sure you want to Delete this Record?") == true) 
       {
            $.ajax({
               url: Route,
               type: "DELETE",
                data: {
                "id": id,
                "_method": 'DELETE',
                 "_token": token,
                 },
               
               success: function(data)
               {
                    location.reload();
               }
           });
        }
   
   });
    
    
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
    
        $(newRow).find('input[name="sr_no[]"]').val('');
        $(newRow).find('input, select').not('input[name="sr_no[]"]').val('');
    
        $('#footable_2 tbody').append(newRow);
         
        $('#footable_2 tbody select').select2();
     
        recalcIdcone();
    
        $('#footable_2').on('mouseenter', 'select', function() 
        {
            if (!$(this).data('select2')) {
                $(this).select2();
            }
        });
        
        $('#footable_2').on('mouseleave', 'select', function() 
        {
            var $this = $(this); 
            setTimeout(function() { 
                if (!isDropdownOpen && !$this.is(':hover')) 
                {
                    $this.select2('destroy');
                }
            }, 5000); 
        });
        
        $('#footable_2').on('select2:open', 'select', function() 
        {
            isDropdownOpen = true;
        });
        
        $('#footable_2').on('select2:close', 'select', function() 
        {
            isDropdownOpen = false;
        });
    }
    
    $(document).ready(function() 
    { 
        $('#frmData').submit(function() 
        {
            $('#Submit').prop('disabled', true);
        }); 
                
        var isDropdownOpen = false;
        
        $('select').on('mouseenter', function() 
        {
            if (!$(this).data('select2')) 
            {
                $(this).select2();
            }
        });
        
        $('select').on('mouseleave', function() 
        {
            var $this = $(this); 
            setTimeout(function() 
            { 
                if (!isDropdownOpen && !$this.is(':hover')) 
                {
                    $this.select2('destroy');
                }
            }, 5000); 
        });
         
        $('select').on('select2:open', function() 
        {
            isDropdownOpen = true;
        });
         
        $('select').on('select2:close', function() 
        {
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
   
</script>  
@endsection