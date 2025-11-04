@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .text-right
    {
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Cutting Detail Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Cutting Entry</a></li>
               <li class="breadcrumb-item active">Cutting Detail Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/show_cutting_detail" method="POST" enctype="multipart/form-data">
                                @csrf 
                    <div class="row"> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="fromDate" class="form-label">From date</label>
                                <input type="date" name="fromDate" class="form-control" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}" required> 
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="toDate" class="form-label">To Date</label>
                                <input type="date" name="toDate" class="form-control" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}" required>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="sales_order_no" class="form-label">Order No.</label>
                                <select class="form-control select2" id="sales_order_no" name="sales_order_no">
                                    <option value="">--Select--</option>
                                     @foreach($salesOrderList as $sales) 
                                       <option value="{{$sales->tr_code}}" {{ $sales->tr_code == $sales_order_no ? 'selected="selected"' : '' }} >{{$sales->tr_code}}</option>      
                                     @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="employeeCode" class="form-label">Buyer Brand</label>
                                <select class="form-control select2" id="brand_id" name="brand_id">
                                    <option value="">--Select--</option>
                                     @foreach($BrandList as $rowBrand) 
                                       <option value="{{$rowBrand->brand_id }}">{{ $rowBrand->brand_name }} </option>
                                     @endforeach
                                </select>
                            </div>
                        </div> 
                        
                               <div class="col-md-3">
                            <div class="mb-3">
                                <label for="employeeCode" class="form-label">Style</label>
                                <select class="form-control select2" id="mainstyle_id" name="mainstyle_id">
                                    <option value="">--Select--</option>
                                     @foreach($styleList as $rowStyle) 
                                       <option value="{{$rowStyle->mainstyle_id }}">{{ $rowStyle->mainstyle_name }} </option>
                                     @endforeach
                                </select>
                            </div>
                        </div> 
                        
                        <div class="col-md-2 mt-2">
                            <label for="formrow-inputState" class="form-label"></label>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-md">Search</button>
                              
                            </div>
                        </div>
                    </div> 
                </form> 
            </div>
        </div>
    </div>
</div>

<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
@endsection