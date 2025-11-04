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
                                               
                                                 <th>Process Order No</th>
                                                 <th>Work Order No</th>
                                                  <th>Out No.</th>
                                                    <th>Out Date.</th>
                                                    
                                                    <th>Item Code</th>
                                                     <th>Item Name</th>
                                                      <th>Out Qty   </th>
                                                      <th>Width</th>
                                                      <th>Quality Name</th>
                                                       <th>Color</th>
                                                       <th>Item Description</th>
                                               
                                               
                                             </tr>
                                            </thead>
        
                                            <tbody>

                                           @foreach($TrimsOutwardDetails as $row)   
                                              
                                            <tr>
                                           
                                              <td>{{$row->ac_name}}</td> 
                                                <td>{{$row->vpo_code}}</td> 
                                                .<td>{{$row->vw_code}}</td> 
                                                 
                                                <td> {{ $row->trimOutCode  }} </td>
                                                <td> {{ $row->tout_date  }} </td>
                                                
                                                <td> {{ $row->item_code  }} </td>
                                                <td> {{ $row->item_name  }} </td>
                                                <td> {{ $row->item_qty  }} </td>
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