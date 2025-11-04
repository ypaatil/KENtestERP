@extends('layouts.master') 
@section('content')   
<style>
   .text-right
   {
   text-align:right;
   }
   .wrap-text {
   word-wrap: break-word;
   max-width: 200px; /* Adjust this to the desired width */
   }
   .hide
   {
       display:none;
   }
   
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Data Tables</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
               <li class="breadcrumb-item active">Sale Transaction List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif
@if(session()->has('delete'))
<div class="alert alert-danger">
   {{ session()->get('delete') }}
</div>
@endif 
<!--<div class="row">-->
<!--  <div class="col-md-3">-->
<!--  <div class="card mini-stats-wid" style="background-color:#152d9f;" >-->
<!--  <div class="card-body">-->
<!--  <div class="d-flex">-->
<!--  <div class="flex-grow-1">-->
<!--  <p class="  fw-medium" style="color:#fff;">No. of Sale</p>-->
<!--  <h4 class="mb-0" style="color:#fff;"> </h4>-->
<!--  </div>-->
<!--  <div class="flex-shrink-0 align-self-center">-->
<!--  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">-->
<!--  <span class="avatar-title" style="background-color:#152d9f;">-->
<!--  <i class="bx bx-copy-alt font-size-24"></i>-->
<!--  </span>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  <div class="col-md-3">-->
<!--  <div class="card mini-stats-wid" style="background-color:#556ee6;">-->
<!--  <div class="card-body">-->
<!--  <div class="d-flex">-->
<!--  <div class="flex-grow-1">-->
<!--  <p class="  fw-medium" style="color:#fff;" >Sale Total (Lacs)</p>-->
<!--  <h4 class="mb-0" style="color:#fff;" ></h4>-->
<!--  </div>-->
<!--  <div class="flex-shrink-0 align-self-center ">-->
<!--  <div class="avatar-sm rounded-circle bg-primary  ">-->
<!--  <span class="avatar-title  " style="background-color:#556ee6;" >-->
<!--   <i class="bx bx-purchase-tag-alt font-size-24"></i>-->
<!--  </span>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  <div class="col-md-3">-->
<!--  <div class="card mini-stats-wid" style="background-color:#f79733;">-->
<!--  <div class="card-body">-->
<!--  <div class="d-flex">-->
<!--  <div class="flex-grow-1">-->
<!--  <p class="  fw-medium" style="color:#fff;">Sale Total Qty(Lacs)</p>-->
<!--  <h4 class="mb-0" style="color:#fff;"> </h4>-->
<!--  </div>-->
<!--  <div class="flex-shrink-0 align-self-center">-->
<!--  <div class="avatar-sm rounded-circle bg-primary  " >-->
<!--  <span class="avatar-title  " style="background-color:#f79733;">-->
<!--  <i class="bx bx-archive-in font-size-24"></i>-->
<!--  </span>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--    <div class="col-md-3">-->
<!--  <div class="card mini-stats-wid" style="background-color:#008116;">-->
<!--  <div class="card-body">-->
<!--  <div class="d-flex">-->
<!--  <div class="flex-grow-1">-->
<!--  <p class="  fw-medium" style="color:#fff;">Sale Pending</p>-->
<!--  <h4 class="mb-0" style="color:#fff;">0</h4>-->
<!--  </div>-->
<!--  <div class="flex-shrink-0 align-self-center">-->
<!--  <div class="avatar-sm rounded-circle bg-primary  " >-->
<!--  <span class="avatar-title  " style="background-color:#008116;">-->
<!--  <i class="bx bx-archive-in font-size-24"></i>-->
<!--  </span>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
<!--  </div>-->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-lg-4">
      <a href="{{ Route('SaleTransaction.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Sale</button></a> &nbsp; &nbsp; &nbsp;
   </div>
   <div class="col-lg-4 text-center">
      <h4><b>Note : </b> Showing last 3 month records. If you want to all click on <a href="saleTransactionShowAll">Show All Data</a> button</h4>
   </div>
   <div class="col-lg-4 text-right">
      <a href="saleTransactionShowAll"><button type="button" class="btn btn-warning w-md float-right">Show All Data</button></a> &nbsp; &nbsp; &nbsp;
      <a href="SaleTransaction"><button type="button" class="btn btn-danger w-md float-right">Back</button></a> &nbsp; &nbsp; &nbsp;
   </div>
</div>
@endif
</br>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table  data-page-length='25' data-ordering="false" id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                  <tr>
                     <th>SrNo</th>
                     <th>Invoice No</th>
                     <th>Invoice Date</th>
                     <th>Buyer</th>
                     <th>Carton Packing Nos</th>
                     <th>Sales Order Nos</th>
                     <th>GST</th>
                     <th>Total Qty</th>
                     <th>Gross Amount</th>
                     <th>Freight Amount</th>
                     <th>GST Amount</th>
                     <th>Net Amount</th>
                     <th>Narration</th>
                     <th>Username</th>
                     <th>Updated At</th>
                     <th class="" nowrap>Preview</th> 
                     @if(Session::get('user_type')==15) 
                     <th class="" nowrap>E-Invoice</th> 
                     <th class="" nowrap>E-Way Bill</th>  
                     @endif 
                     <th>Tally Print</th>
                     <th>DC Print</th>
                     <th>Print</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($SaleTransactionMasterData as $row) 
                  @php
                  $sales_order_nos = '';
                  $detailData = DB::SELECT("SELECT sales_order_no FROM sale_transaction_detail WHERE sale_code='".$row->sale_code."'");
                  foreach($detailData as $details)
                  {
                  $sales_order_nos .= $details->sales_order_no . ",";
                  }
                  @endphp
                  <tr>
                     <td>{{ $row->sr_no }}</td>
                     <td>{{ $row->sale_code }}</td>
                     <td>{{ $row->sale_date }}</td>
                     <td>{{ $row->ac_name1 }}</td>
                     <td>{{ $row->carton_packing_nos }}</td>
                     <td>{{ $sales_order_nos }}</td>
                     <td>{{ $row->tax_type_name }}</td>
                     <td>{{ $row->total_qty }}</td>
                     <td>{{ $row->Gross_amount }}</td>
                     <td>{{ $row->freight_charges }}</td>
                     <td>{{ $row->Gst_amount }}</td>
                     <td>{{ $row->Net_amount }}</td>
                     <td>{{ $row->narration }}</td>
                     <td>{{ $row->username }}</td>
                     <td>{{ date("d-m-Y", strtotime($row->updated_at)) }}</td>
                     <td class="text-center"> 
                         <a class="btn btn-sm" href="EInvoicePreview/{{ $row->sr_no }}" target="_blank" title="E-Invoice"> Preview </a>   
                     </td>
                     @if(Session::get('user_type')==15) 
                     <td class="text-center">
                        @if($row->irn != NULL || $row->eway_bill_no != NULL)
                            <a class="btn btn-sm" @if($row->irn != NULL && $row->eway_bill_no != NULL) href="EInvoice/{{ $row->sr_no }}" target="_blank" @else href="javascript:void(0);" onclick="checkInvoice('{{ $row->irn }}','{{ $row->eway_bill_no }}');" @endif title="E-Invoice"> View </a> 
                            @else 
                            <button class="btn-generate-einvoice" target="_blank" data-id="{{ $row->sale_code }}" data-irn="{{ $row->irn }}" data-eway_bill_no="{{ $row->eway_bill_no }}"> 
                                <img src="{{ URL::asset('images/e-invoice.png')}}" width="40" height="40" />
                            </button> 
                        @endif 
                     </td>
                     <td class="text-center">                        
                        @if($row->eway_bill_no != NULL)
                            <a class="btn btn-sm" href="EInvoice/{{ $row->sr_no }}" target="_blank" title="E-WayBill"> View </a> 
                        @else 
                            <button class="btn-generate-ewaybill" target="_blank" data-id="{{ $row->sale_code }}" data-irn="{{ $row->irn }}" data-eway_bill_no="{{ $row->eway_bill_no }}"> 
                                <img src="{{ URL::asset('images/e-invoice.png')}}" width="40" height="40" />
                            </button> 
                         @endif  
                     </td> 
                     @endif 
                     <td>
                        <form method="POST" action="{{ route('export.tally.xml') }}">
                           @csrf
                           <input type="hidden" name="sr_no" value="{{$row->sr_no}}">
                           <button type="submit" class="btn btn-success mb-3">Tally XML</button>
                        </form>
                     </td>
                     <td>
                        <a class="btn btn-sm edit" href="DCPrintSaleTransaction/{{ $row->sr_no }}" title="Print">
                        <i class="fas fa-print"></i>
                        </a> 
                     </td>
                     <td>
                        <a class="btn btn-sm edit" href="PrintSaleTransaction/{{ $row->sr_no }}" title="Print">
                        <i class="fas fa-print"></i>
                        </a> 
                     </td>
                     <td> 
                        @if($chekform->edit_access==1) 
                        <a class="btn btn-sm edit" href="{{route('SaleTransaction.edit',$row->sr_no )}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a> 
                        @else
                        <a class="btn btn-sm edit" href="javascript:void(0);" title="Edit">
                        <i class="fas fa-lock"></i>
                        </a>
                        @endif
                     </td>
                     <td> 
                        @if($chekform->delete_access==1)                     
                        <button  class="btn btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->sr_no }}"  data-route="{{route('SaleTransaction.destroy',  $row->sr_no)}}" title="Delete">
                        <i class="fas fa-trash"></i>
                        </button> 
                        @else                 
                        <button class="btn btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                        <i class="fas fa-lock"></i>
                        </button> 
                        @endif 
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
<div id="qrcode" class="hide"></div>
<div id="qrcode1" class="hide"></div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script type="text/javascript"> 

    function GenerateProductionToken()
    {
         $.ajax({
            url: 'getEInvoiceTokenForProduction',
            method: 'GET',
            success: function (response) 
            {
                console.log(response); 
            },
         });
    }
    function checkInvoice(irn,eway_bill_no)
    { 
          if(irn == '')
          {
               alert("E-Invoice Not Generated"); 
          }
          
          if(eway_bill_no == '')
          {
               alert("E-Way Bill Not Generated"); 
          }
    }
    
    $(document).on('click', '.btn-generate-einvoice', function () 
    {
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
          });
          
          let sale_code = $(this).data('id'); 
          
          if (!confirm("Are you sure you want to generate the e-Invoice?")) 
          {
            return; // Stop execution if user cancels
          }
        
          $.ajax({
            url: '/generate-einvoice',
            method: 'POST',
            data: { 'sale_code': sale_code },
            beforeSend: function () {
              // Optional: show loading spinner
            },
            success: function (response) 
            {
                alert("E-Invoice generated successfully. IRN: " + response.irn); 
            },
            error: function (res) 
            {
                let message = "";
            
                // If responseJSON is available
                if (res.responseJSON) {
                    // Case 1: Old structure with raw.error.message
                    if (res.responseJSON.raw && res.responseJSON.raw.error) {
                        message = res.responseJSON.raw.error.message;
                    }
                    // Case 2: New structure with details.error.message
                    else if (res.responseJSON.details && res.responseJSON.details.error) {
                        message = res.responseJSON.details.error.message;
                    }
                    // Fallback if `error` is top-level
                    else if (res.responseJSON.error) {
                        message = res.responseJSON.error;
                    }
                }
                // If only text is available
                else if (res.responseText) {
                    try {
                        let json = JSON.parse(res.responseText);
                        if (json.raw?.error?.message) {
                            message = json.raw.error.message;
                        } else if (json.details?.error?.message) {
                            message = json.details.error.message;
                        } else if (json.error) {
                            message = json.error;
                        } else {
                            message = "Unknown error";
                        }
                    } catch (e) {
                        message = "Error parsing response";
                    }
                }
            
                console.log(message);
                alert(message);
            }

          }); 
    });

    $(document).on('click', '.btn-generate-ewaybill', function () 
    {
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
          });
          
          let sale_code = $(this).data('id'); 
    
          if (!confirm("Are you sure you want to generate the e-Way Bill?"))
          {
            return; // Stop execution if user cancels
          }
        
          $.ajax({
            url: '/generate-ewaybill',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST', 
            data: { 'sale_code': sale_code },
            beforeSend: function () 
            {
              // Optional: show loading spinner
            },
            success: function (response) 
            { 
                alert("E-Way Bill generated successfully. E-Way Bill: " + response.ewayBillNo); 
                
            },
            error: function (res) 
            {
                let message = "";
            
                // If responseJSON is available
                if (res.responseJSON && res.responseJSON.raw && res.responseJSON.raw.error) {
                    message = res.responseJSON.raw.error.message;
                } 
                // If only text is available
                else if (res.responseText) {
                    try {
                        let json = JSON.parse(res.responseText);
                        message = json.raw?.error?.message || "Unknown error";
                    } catch (e) {
                        message = "Error parsing response";
                    }
                }
            
                console.log(message); // This will print your message only
                alert(message);       // Show it in alert
            }
          }); 
    });
 

   $(document).on('click','#DeleteRecord',function(e) {
   
      var Route = $(this).attr("data-route");
      var id = $(this).data("id");
      var token = $(this).data("token");
   
     //alert(Route);
    
    //alert(data);
      if (confirm("Are you sure you want to Delete this Record?") == true) {
    $.ajax({
           url: Route,
           type: "DELETE",
            data: {
            "id": id,
            "_method": 'DELETE',
             "_token": token,
             },
           
           success: function(data){
   
              //alert(data);
           location.reload();
   
           }
   });
   }
   
   });
</script>
@endsection