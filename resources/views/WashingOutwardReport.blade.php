@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<style>
    .hide{
        display:none;
    }
    
    .text-right{
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Washing Outward Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Washing Outward Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 

<div class="row">
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/WashingOutwardReport" method="GET">
                  <div class="row"> 
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" name="from_date" id="from_date" value="{{$from_date}}">
                         </div>
                      </div>
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" name="to_date" id="to_date" value="{{$to_date}}">
                         </div>
                      </div>
                      <div class="col-md-3"> 
                         <div class="mt-4">
                            <label class="form-label"></label>
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/WashingOutwardReport" class="btn btn-warning">Clear</a>
                         </div>
                      </div>
                  </div>
              </form>
          </div>
       </div>
    </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
                     <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                         <thead> 
                                <tr style="background-color:#eee;"> 
                                   <th>Sr No</th>
                                   <th>Outward Date</th>
                                   <th>Sales Order No</th>
                                   <th>Washing PO No</th>
                                   <th>Outward No</th>
                                   <th>PO Status</th> 
                                   <th>Stitching Vendor Name</th>  
                                   <th>Washing Vendor Name</th> 
                                   <th>Buyer Name</th>
                                   <th>Style Category</th>
                                   <th>Style No</th>
                                   <th>Garment Color</th>
                                   <th>Size</th>
                                   <th>Outward Qty</th>
                                </tr>
                         </thead>
                         <tbody>
                             @php
                                $srno = 1;
                            @endphp
                            @foreach($WashList as $row)
                            @php
                                if($row->endflag == 1)
                                {
                                    $po_status = 'Open';
                                }
                                else if($row->endflag == 2)
                                {
                                    $po_status = 'Close';
                                }
                                else
                                {
                                    $po_status = '';
                                }
                                
                                $washingOutData = explode("-", $row->vpo_code);

                            @endphp
                                <tr>
                                   <td>{{$srno++}}</td>
                                   <td>{{date("d-m-Y",strtotime($row->vpo_date))}}</td>
                                   <td>{{$row->sales_order_no}}</td>
                                   <td>{{$row->vpo_code}}</td>
                                   <td>WOUT-{{$washingOutData[1]}}</td>
                                   <td>{{$po_status}}</td> 
                                   <td>{{$row->stiching_vendorName}}</td> 
                                   <td>{{$row->vendorName}}</td> 
                                   <td>{{$row->Ac_name}}</td> 
                                   <td>{{$row->mainstyle_name}}</td> 
                                   <td>{{$row->style_no}}</td> 
                                   <td>{{$row->color_name}}</td> 
                                   <td>{{$row->size_name}}</td> 
                                   <td class="text-right">{{$row->size_qty}}</td> 
                                </tr> 
                            @endforeach
                         </tbody>
                </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
<script>  

    $(document).ready(function()
    { 
    }); 
</script>
@endsection