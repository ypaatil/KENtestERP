
@extends('layouts.master') 

@section('content')   

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                            <li class="breadcrumb-item active">Purchase Order Approval List</li>
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


@if($chekform->write_access==1)

<div class="row">
<div class="col-md-6">
<a href="{{ Route('PurchaseOrder.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Record</button></a>
</div>
</div>
@endif


  <!--<div class="row">-->
  <!--  <div class="col-md-4">-->
  <!--  <div class="card mini-stats-wid">-->
  <!--  <div class="card-body">-->
  <!--  <div class="d-flex">-->
  <!--  <div class="flex-grow-1">-->
  <!--  <p class="text-muted fw-medium">Pending Lots</p>-->
  <!--  <h4 class="mb-0">3</h4>-->
  <!--  </div>-->

  <!--  <div class="flex-shrink-0 align-self-center">-->
  <!--  <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">-->
  <!--  <span class="avatar-title">-->
  <!--  <i class="bx bx-copy-alt font-size-24"></i>-->
  <!--  </span>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  <div class="col-md-4">-->
  <!--  <div class="card mini-stats-wid">-->
  <!--  <div class="card-body">-->
  <!--  <div class="d-flex">-->
  <!--  <div class="flex-grow-1">-->
  <!--  <p class="text-muted fw-medium">Completed</p>-->
  <!--  <h4 class="mb-0">7</h4>-->
  <!--  </div>-->

  <!--  <div class="flex-shrink-0 align-self-center ">-->
  <!--  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
  <!--  <span class="avatar-title rounded-circle bg-primary">-->
  <!--  <i class="bx bx-archive-in font-size-24"></i>-->
  <!--  </span>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  <div class="col-md-4">-->
  <!--  <div class="card mini-stats-wid">-->
  <!--  <div class="card-body">-->
  <!--  <div class="d-flex">-->
  <!--  <div class="flex-grow-1">-->
  <!--  <p class="text-muted fw-medium">Pending Dispatch</p>-->
  <!--  <h4 class="mb-0">8</h4>-->
  <!--  </div>-->

  <!--  <div class="flex-shrink-0 align-self-center">-->
  <!--  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
  <!--  <span class="avatar-title rounded-circle bg-primary">-->
  <!--  <i class="bx bx-purchase-tag-alt font-size-24"></i>-->
  <!--  </span>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
    
    
  <!--   <div class="col-md-4">-->
  <!--  <div class="card mini-stats-wid">-->
  <!--  <div class="card-body">-->
  <!--  <div class="d-flex">-->
  <!--  <div class="flex-grow-1">-->
  <!--  <p class="text-muted fw-medium">Today's Tasks</p>-->
  <!--  <h4 class="mb-0">5</h4>-->
  <!--  </div>-->

  <!--  <div class="flex-shrink-0 align-self-center">-->
  <!--  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
  <!--  <span class="avatar-title rounded-circle bg-primary">-->
  <!--  <i class="bx bx-purchase-tag-alt font-size-24"></i>-->
  <!--  </span>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
    
    
  <!--   <div class="col-md-4">-->
  <!--  <div class="card mini-stats-wid">-->
  <!--  <div class="card-body">-->
  <!--  <div class="d-flex">-->
  <!--  <div class="flex-grow-1">-->
  <!--  <p class="text-muted fw-medium">Today's Prduction</p>-->
  <!--  <h4 class="mb-0">4200</h4>-->
  <!--  </div>-->

  <!--  <div class="flex-shrink-0 align-self-center">-->
  <!--  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
  <!--  <span class="avatar-title rounded-circle bg-primary">-->
  <!--  <i class="bx bx-purchase-tag-alt font-size-24"></i>-->
  <!--  </span>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
    
  <!--   <div class="col-md-4">-->
  <!--  <div class="card mini-stats-wid">-->
  <!--  <div class="card-body">-->
  <!--  <div class="d-flex">-->
  <!--  <div class="flex-grow-1">-->
  <!--  <p class="text-muted fw-medium">Total Workers</p>-->
  <!--  <h4 class="mb-0">420</h4>-->
  <!--  </div>-->

  <!--  <div class="flex-shrink-0 align-self-center">-->
  <!--  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
  <!--  <span class="avatar-title rounded-circle bg-primary">-->
  <!--  <i class="bx bx-purchase-tag-alt font-size-24"></i>-->
  <!--  </span>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
  <!--  </div>-->
    
  <!--  </div>-->


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <table id="datatable-buttons" class="  table table-bordered dt-responsive nowrap w-100">
                            <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                            <tr>
                                 <th><input type="checkbox" name="chk" id="selectAll"  /></th>
                                <th>PO No</th>
                                <th>PO Date</th>
                                <th>Supplier</th>
                                <th>GST</th>  
                                <th>Gross Amt</th> 
                                <th>GST Amt</th> 
                                <th>Net Amt</th> 
                                 <th>Updated By</th>
                                @foreach($data as $row) 
                                @if($row->approveFlag==1)
                                    <th>Action</th> 
                                @elseif($row->approveFlag==2)
                                 <th>Approval Status</th> 
                                @endif
                                @endforeach
                                
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($data as $row)    
                            <tr>
                          <td>
                               <input type="hidden" name="po_code' . $no . '" value="{{ $row->pur_code }}">
                                <input type="checkbox" name="checkbox" value="{{ $row->pur_code }}"> 
                            </td>
                                <td>{{ $row->pur_code }}</td>
                                <td>{{ $row->pur_date }}</td>
                                <td>{{ $row->ac_name1 }}</td>
                                <td>{{ $row->tax_type_name }}</td>
                                <td>{{ $row->Gross_amount }}</td>
                                <td>{{ $row->Gst_amount }}</td>
                                <td>{{ $row->Net_amount }}</td>
                               <td>{{ $row->username }}</td>   
                                
                                @if($row->approveFlag==1)
                                    <td>
                                        <a class="btn btn-outline-secondary btn-sm edit" href="print/{{ $row->pur_code }}" title="Print">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        
                                        <a class="btn btn-outline-secondary btn-sm edit" href="{{ Route('PurchaseOrder.edit', $row->sr_no) }}" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                         <a class="btn btn-outline-secondary btn-sm edit" href="{{ Route('FabricInward.create',  ['po_code'=>$row->pur_code,'ac_code'=>$row->ac_code]) }}" title="GRN">
                                             <i class="far fa-sticky-note"></i>
                                        </a>
                                        
                                    </td>
                                @elseif($row->approveFlag==2)
                                 <th>Disapproved:{{ $row->reason_disapproval }} </th> 
                                @endif
                                 
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        @endsection
         <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script>
       
         $(document).ready(function () { 
       
 try {
     
     
     $.fn.dataTable.ext.errMode = 'none';
        
    var oTable = $('#datatable-buttons').dataTable({
       // stateSave: true
     });
    var allPages = oTable.fnGetNodes();
 
    $('body').on('click', '#selectAll', function () {
        if ($(this).hasClass('allChecked')) {
            $('input[type="checkbox"]', allPages).prop('checked', false);
            
            assignedTo = $(':checkbox[name=checkbox]:checked',allPages).map(function() {
            return this.value;
            })  .get();
            $('pre.out').text( JSON.stringify(assignedTo ));
            
            
        } else {
            $('input[type="checkbox"]', allPages).prop('checked', true);
            
            assignedTo = $(':checkbox[name=checkbox]:checked',allPages).map(function() {
            return this.value;
            })  .get();
            $('pre.out').text( JSON.stringify(assignedTo ));
   
        }
        $(this).toggleClass('allChecked');
    })
       
           
       }
catch(err){}
});
        
         
        
            var assignedTo= [];
    $(':checkbox[name=checkbox ],checkbox[name=chk]').on('change', function() {
   /// alert("HI");
     assignedTo = $(':checkbox[name=checkbox]:checked').map(function() {
        return this.value;
    })
    .get();

    //Out for DEMO purposes only 
  $('pre.out').text( JSON.stringify( assignedTo ) );
});
        </script>
       
         