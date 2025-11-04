
@extends('layouts.master') 

@section('content')   

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                            <li class="breadcrumb-item active">Purchase Order List</li>
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






  <div class="row">
    <div class="col-md-3">
    <div class="card mini-stats-wid" style="background-color:#152d9f;" >
    <div class="card-body">
    <div class="d-flex">
    <div class="flex-grow-1">
    <p class="  fw-medium" style="color:#fff;">No. of PO</p>
    <h4 class="mb-0" style="color:#fff;">@foreach($PODetails as $row) {{$row->noOfPO}} @endforeach</h4>
    </div>

    <div class="flex-shrink-0 align-self-center">
    <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
    <span class="avatar-title" style="background-color:#152d9f;">
    <i class="bx bx-copy-alt font-size-24"></i>
    </span>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <div class="col-md-3">
    <div class="card mini-stats-wid" style="background-color:#556ee6;">
    <div class="card-body">
    <div class="d-flex">
    <div class="flex-grow-1">
    <p class="  fw-medium" style="color:#fff;" >PO Total(Lakh)</p>
    <h4 class="mb-0" style="color:#fff;" >@foreach($PODetails as $row)    {{number_format((double)($row->poTotal/100000), 2, '.', '')}} @endforeach</h4>
    </div>

    <div class="flex-shrink-0 align-self-center ">
    <div class="avatar-sm rounded-circle bg-primary  ">
    <span class="avatar-title  " style="background-color:#556ee6;" >
   
     <i class="bx bx-purchase-tag-alt font-size-24"></i>
    </span>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <div class="col-md-3">
    <div class="card mini-stats-wid" style="background-color:#f79733;">
    <div class="card-body">
    <div class="d-flex">
    <div class="flex-grow-1">
    <p class="  fw-medium" style="color:#fff;">PO Received Total(Lakh)</p>
    <h4 class="mb-0" style="color:#fff;">@foreach($PODetails as $row)   {{number_format((double)($row->receivedTotal/100000), 2, '.', '')}} @endforeach</h4>
    </div>

    <div class="flex-shrink-0 align-self-center">
    <div class="avatar-sm rounded-circle bg-primary  " >
    <span class="avatar-title  " style="background-color:#f79733;">
    <i class="bx bx-archive-in font-size-24"></i>
    </span>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    
    
      <div class="col-md-3">
    <div class="card mini-stats-wid" style="background-color:#008116;">
    <div class="card-body">
    <div class="d-flex">
    <div class="flex-grow-1">
    <p class="  fw-medium" style="color:#fff;">GRN Total(Lakh)</p>
    <h4 class="mb-0" style="color:#fff;">{{number_format((double)($AmountGrn/100000), 2, '.', '')}}  </h4>
    </div>

    <div class="flex-shrink-0 align-self-center">
    <div class="avatar-sm rounded-circle bg-primary  " >
    <span class="avatar-title  " style="background-color:#008116;">
    <i class="bx bx-archive-in font-size-24"></i>
    </span>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
     
    </div>
 
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                            <tr>
                               <th  >PO No</th> 
                                <th  >PO Date</th>     
                                <th  >Fabric Code</th>
                                <th  >Fabric Image</th>
                                <th  >Fabric Quality</th> 
                                <th  >Color</th>
                                 <th  >Width</th> 
                                <th   >PO Qty (Lakh)</th>
                                <th  >Received Meter (Lakh)</th>
                                <th  >To Be Received Meter (Lakh)</th>
                                <th  >Passed Meter (Lakh)</th>
                                
                                <th  >Rejected Meter (Lakh)</th>
                                <th  >Issue Meter (Lakh)</th>
                                <th  >Stock Meter (Lakh)</th>
                                <th  >PO Status</th>
                                <th  >(30 > Days) (Lakh)</th>
                                <th  >(60 > Days) (Lakh)</th>
                                <th  >(90 > Days) (Lakh)</th>
                              
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($InwardFabric as $row)    
                            <tr>
                                <td style="text-align:center; white-space:nowrap"><a href="http://kenerp.com/PurchaseOrder/{{ $row->sr_no }}/edit" >{{ $row->pur_code }} </a></td>
                                <td style="text-align:center; white-space:nowrap">{{ $row->pur_date }}</td>
                                <td style="text-align:center; white-space:nowrap">{{ $row->item_code }}-{{ $row->item_name }}</td>
                                 <td style="text-align:center; white-space:nowrap"><img src="./images/{{ $row->item_image_path }}" width="50" height="50" ></td>
                                <td style="text-align:center; white-space:nowrap">{{ $row->item_description }}</td>
                                <td style="text-align:center; white-space:nowrap">{{ $row->color_name }}</td>
                                <td style="text-align:center; white-space:nowrap">{{ $row->dimension }}</td>
                                <td style="text-align:center; white-space:nowrap"><a href="http://kenerp.com/PurchaseOrder/{{ $row->sr_no }}/edit" >{{ number_format($row->item_qty) }}</a></td>
                                <td style="text-align:center; white-space:nowrap"> <span onclick="openmodal({{ $row->sr_no }},{{ $row->item_code }});" style="color:#556ee6; cursor: pointer;">{{ number_format($row->received_meter) }}</span></td>
                                
                                
                                 <td style="text-align:right;">{{   number_format((double)(($row->item_qty-$row->received_meter)/100000), 2, '.', '') }}</td>
                                 
                                  <td style="text-align:right;">{{  number_format((double)($row->passed_meter/100000), 2, '.', '') }}</td>
                                
                                <td style="text-align:right;">{{   number_format((double)($row->rejected_meter/100000), 2, '.', '')}}</td>
                                <td style="text-align:right;">{{   number_format((double)($row->issue_meter/100000), 2, '.', '')}}</td>
                                
                                <td style="text-align:right;">{{   number_format((double)(($row->received_meter - $row->rejected_meter - $row->issue_meter)/100000), 2, '.', '')}}</td>
                                
                                <td style="text-align:center; white-space:nowrap">{{ $row->job_status_name }}</td>
                                <td style="text-align:right;">{{   number_format((double)($row->t30_days_meter/100000), 2, '.', '')}}</td>
                                <td style="text-align:right;">{{   number_format((double)($row->t60_days_meter/100000), 2, '.', '')}}</td>
                                <td style="text-align:right;">{{    number_format((double)($row->t90_days_meter/100000), 2, '.', '')}}</td>
                               
                               </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->


    <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 

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
 
 
 function openmodal(po_code,item_code)
 {
     
     getFabInDetails(po_code,item_code);
        $('#modalFormSize').modal('show');
 }
 
  function closemodal()
 {
       $('#modalFormSize').modal('hide');
    //    $('#product-options').modal('hide');
 }
 
 
 
 function getFabInDetails(po_code,item_code)
{
     
    $.ajax({
    type: "GET",
    url: "{{ route('InwardData') }}",
    data: { sr_no: po_code, item_code: item_code },
    success: function(data){
    $("#InwardData").html(data.html);
    }
    });
}
 
</script>




<div class="modal fade" id="modalFormSize" role="dialog">
<div class="modal-dialog" style="margin: 1.75rem 19rem;">
<div class="modal-content" style="width: 900px;">
<!-- Modal Body -->
<div class="modal-body">
<p class="statusMsg"></p>
 
<div class="seprator-block"></div>
<h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-calendar-note mr-10"></i>GRN (Fabric Inward)</h6>
<hr class="light-grey-hr"/>

<div class="row">


<div id="InwardData"></div>
 
</div>

 


<!-- Modal Footer -->
<div class="modal-footer">
<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closemodal();">Close</button>
 
</div>
</div>
</div>
</div>



        @endsection