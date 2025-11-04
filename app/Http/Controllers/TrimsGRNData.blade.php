@extends('layouts.master') 
@section('content')   

                        
                        <!-- end page title -->
                        @if(session()->has('message'))
                        <div class="col-md-3">
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        </div>
                        @endif

                        @if(session()->has('messagedelete'))
                        <div class="col-md-3">
                            <div class="alert alert-danger">
                                {{ session()->get('messagedelete') }}
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
                                                
                                                <th>Supplier Name</th>
                                                <th>PO No</th>
                                                 <th>Sales Order No</th>
                                                  <th>GRN No.</th>
                                                    <th>GRN Date.</th>
                                                    <th>Invoice No.</th>
                                                    <th>Invoice Date.</th>
                                                    <th>Item Code</th>
                                                     <th>Item Name</th>
                                                      <th>GRN Qty   </th>
                                                      <th>Width</th>
                                                      <th>Quality Name</th>
                                                       <th>Color</th>
                                                       <th>Item Description</th>
                                                  
                                             </tr>
                                            </thead>
        
                                            <tbody>

                                           @foreach($TrimsInwardDetails as $row)   
                                              
                                            <tr>
                                           
                                              <td>{{$row->ac_name}}</td> 
                                                <td> {{ $row->po_code  }} </td>
                                                 <td>   @if($row->is_opening!=1) $row->sales_order_no @else Opening Stock @endif  </td>
                                                <td> {{ $row->trimCode  }} </td>
                                                <td> {{ $row->trimDate  }} </td>
                                                <td> {{ $row->invoice_no  }} </td>
                                                <td> {{ $row->invoice_date  }} </td>
                                                <td> {{ $row->item_code  }} </td>
                                                <td> {{ $row->item_name  }} </td>
                                                <td> {{ $row->meter  }} </td>
                                                <td> {{ $row->dimension  }} </td>
                                                <td> {{ $row->quality_name  }} </td>     
                                                <td> {{ $row->color_name  }} </td>
                                                <td> {{ $row->item_description  }} </td> 
                                                    
                                            </tr>
                                             
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        
                                                       <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
  
                        
                        @endsection