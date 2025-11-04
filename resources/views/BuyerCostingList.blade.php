@extends('layouts.master') 
@section('content')  
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
    .btn-icon {
        display: flex;
        align-items: center;
    }
    .btn-icon i {
        margin-right: 5px;
    }
    
    @media (max-width: 600px) {
        .breadcumbCls {
            display: none;
        }
        
        .navbar-header {
            background: #703eb385;
        }
        .titleCls { 
            text-align: center;
        }
        
        #vertical-menu-btn {
            display: none;
        }
    }
    
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 18px;
        text-align: left;
    }

    th, td {
        padding: 12px;
        border: 1px solid #ddd;
    }

    /*th {*/
    /*    background-color: #f8f9fa;*/
    /*    color: #333;*/
    /*}*/

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tbody tr:hover {
        background-color: #f1c40f38;
        color: black;
        font-weight:900;
    }

    caption {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .table>thead {
        vertical-align: middle;
        background-color: #024A8E!important;
        color: #fff;
    }
    
</style> 
 
<div class="page-title-box d-sm-flex align-items-center justify-content-between titleCls">
    <div class="col-8">
        <h4 class="mb-sm-0 font-size-18">Buyer Costing</h4>
    </div>
</div>   

<div class="row m-2">
    <div class="row"> 
        <div class="col-4">
            <a href="{{ Route('BuyerCosting.create') }}">
                <button type="button" class="btn btn-primary w-100">
                    <span class="d-md-none"><i class="fas fa-plus"></i></span>
                    <span class="d-none d-md-inline">Add New Record</span>
                </button>
            </a>
        </div>
    </div>
</div> 

@if(session()->has('message'))
<div class="col-md-12">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('error'))
<div class="col-md-12">
   <div class="alert alert-danger">
      {{ session()->get('error') }}
   </div>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body table-responsive">
                <table data-order='[[ 0, "desc" ]]' data-page-length='100' id="sales_costing_table" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr class="text-center">
                            <th>Sr No</th>
                            <th>Costing No</th>
                            <th>Date</th>
                            <th>Buyer Name</th>
                            <th>Brand</th>
                            <th>Style</th>
                            <th>Market</th> 
                            <th>FOB</th>
                            <th>Exchange Rate</th>
                            <th>SAM</th>
                            <th>CMOHP<br/>(₹/Min)</th>
                            <th>Qty.<br/> in PCS</th>
                            <th>Value.<br/> in INR</th>
                            <th>CMOHP/ <br/>FOB (%)</th>
                            <th>Created By</th>
                            <th>Revise</th> 
                            <th>Copy</th> 
                            <th>Edit</th> 
                            <th>View</th>
                            @if(Session::get('user_type') == 1)
                            <th>Delete</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $srno = 1;
                        @endphp
                        @foreach($BuyerCostingData as $row)
                        @php
                            $cmohp = $row->production_value + $row->other_value + $row->profit_value;
                          
                            if($cmohp > 0 && $row->sam > 0)
                            {
                                $cmohp_per =   $cmohp/$row->sam;
                            }
                            else
                            {
                                $cmohp_per = 0;
                            }
                            
                            if($cmohp > 0 && $row->fob_rate > 0)
                            {
                                $cmohp1 =  ($cmohp / $row->fob_rate) * 100;
                            }
                            else
                            {
                                $cmohp1 = 0;
                            }
                             
                            $costing_no = $row->revised_id ? $row->revised_id : $row->sr_no;
                        @endphp
                        <tr>
                            <td>{{$srno++}}</td>
                            <td>{{$costing_no}}</td>
                            <td nowrap>{{date("d-m-Y", strtotime($row->entry_date))}}</td>
                            <td>{{$row->buyer_name}}</td>
                            <td>{{$row->brand_name}}</td>
                            <td nowrap>{{$row->style_name}}</td>
                            @if($row->order_group_name == 'Domestic')
                            <td class="text-center">D</td>
                            @elseif($row->order_group_name == 'Export')
                            <td class="text-center">E</td> 
                            @else
                            <td class="text-center">-</td>
                            @endif
                            <td class="text-right" nowrap>{{preg_replace('/[a-zA-Z0-9]/', '', $row->currency_name)}} {{sprintf("%.2f", $row->inr_rate)}}</td>
                            <td class="text-right">{{sprintf("%.2f", $row->exchange_rate)}}</td>
                            <td class="text-right">{{sprintf("%.2f", $row->sam)}}</td>
                            <td class="text-right">{{sprintf("%.2f", $cmohp_per)}}</td> 
                            <td class="text-right">{{money_format('%!.0n', $row->total_qty)}}</td>
                            <td class="text-right">{{money_format('%!.0n', $row->total_value)}}</td>
                            <td class="text-right">{{sprintf("%.2f", $cmohp1)}}</td>
                            <td nowrap>{{$row->username}}</td> 
                            <td class="text-center">
                                @if($row->isDisabled == 0 && $row->username == Session::get('username'))
                                    <a class="btn btn-icon btn-sm" href="ReviseBuyerCostingEdit?srno={{$row->sr_no}}" style="font-size: 20px;font-weight: 800;">
                                        <i class='fas fa-redo-alt'></i>
                                    </a>
                                @else
                                    <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                                        <i class="fas fa-lock"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                <a class="btn btn-icon btn-sm" href="RepeatBuyerCostingEdit?srno={{$row->sr_no}}" style="font-size: 20px;font-weight: 800;">+</a>
                            </td>
                            <td class="text-center">
                                @if($row->isDisabled == 0 && $row->username == Session::get('username'))
                                    <a class="btn btn-icon btn-sm" href="{{route('BuyerCosting.edit', $row->sr_no)}}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @else
                                    <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                                        <i class="fas fa-lock"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                <a class="btn btn-outline-secondary btn-sm print" target="_blank" href="BuyerCostingPrint/{{$row->sr_no}}" title="print">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                            @if(Session::get('user_type') == 1)
                            <td>
                               <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->sr_no }}"  data-route="{{route('BuyerCosting.destroy', $row->sr_no )}}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>           
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#sales_costing_table').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
        });

        table.buttons().container().appendTo('#sales_costing_table_wrapper .col-md-6:eq(0)');

        $(document).on('click','.DeleteRecord',function(e) {
            var Route = $(this).attr("data-route");
            var id = $(this).data("id");
            var token = $(this).data("token");

            if (confirm("Are you sure you want to Delete this Record?")) {
                $.ajax({
                    url: Route,
                    type: "DELETE",
                    data: {
                        "id": id,
                        "_method": 'DELETE',
                        "_token": token,
                    },
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    });
    $(document).on('click','#DeleteRecord',function(e) {
        
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
                     
                     success: function(data){
            
                        //alert(data);
                     location.reload();
            
                     }
            });
        }
 });
</script>  
@endsection
