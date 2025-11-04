@extends('layouts.master') 

@section('content')   

                        
                        <!-- end page title -->
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">BOM Detail</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
                <li class="breadcrumb-item active">BOM Detail</li>
            </ol>
        </div>

    </div>
</div>
</div> 
@php


@endphp

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
         <div class="">
                                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                          <thead>
                                            <tr>
                                                
                                                 <th>SrNo</th>
                                                 <th>Sales Order NO</th>
                                                 <th>BOM Code</th>
                                                 
                                            <th>Item Code</th>
                                            <th>Image</th>
                                            <th>Item Name</th>
                                            <th>Classification</th>
                                            <th>Description</th>
                                             <th >Colors</th>
                                             <th>Sizes</th>
                                            <th>Cons (Mtr/Nos)</th>
                                            <th>UOM</th>
                                            <th>Wastage %</th>
                                            <th>BOM Qty</th>
                                            <th>Remark</th>
                                               
                                             </tr>
                                            </thead>
        
                                            <tbody>

                                                 @php 
 
        
$no=1; 

@endphp


@foreach($FabricList as $rowDetail)  

<tr>
<td>{{ $no }}</td> 
<td>{{ $rowDetail->sales_order_no }}</td> 
<td>{{ $rowDetail->bom_code }}</td> 
<td>{{ $rowDetail->item_code }}</td> 
<td><a href="{{url('images/'.$rowDetail->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$rowDetail->item_image_path)}}"  ></a></td>
<td>{{ $rowDetail->item_name }}</td>
<td>{{ $rowDetail->class_name }}</td>
<td>{{ $rowDetail->description  }}</td>
@php
  
    $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
     'color_master.color_name')->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('item_code','=',$rowDetail->item_code)->where('tr_code','=',$rowDetail->sales_order_no)->DISTINCT()->get();
    
     $data='';
     foreach($ColorList as $row)
     {
       $data=$data.$row->color_name.', ';
     }


@endphp

<td  >{{rtrim($data,',')}}</td>
<td> </td>
<td style="text-align:right">{{ $rowDetail->consumption  }}</td>
<td>{{ $rowDetail->unit_name  }}</td>
<td style="text-align:right">{{ $rowDetail->wastage  }}</td>
<td style="text-align:right"> {{number_format($rowDetail->bom_qty)}}</td>
<td style="text-align:right">{{ $rowDetail->remark  }}</td>
</tr>
@php

$no=$no+1;
@endphp
@endforeach

@php

$no=1; 

@endphp

@if(isset($TrimFabricList))
 
@foreach($TrimFabricList as $rowDetailtrimfabric)  
 
@php 

$color_ids = explode(',', $rowDetailtrimfabric->color_id);  

$size_ids = explode(',', $rowDetailtrimfabric->size_array); 

$ColorList= App\Models\ColorModel::whereIn('color_id', $color_ids)->where('delflag','=', '0')->get('color_name');

$SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
   
@endphp

<tr>
<td>{{ $no }}</td>  
<td>{{ $rowDetailtrimfabric->sales_order_no }}</td> 
<td>{{ $rowDetailtrimfabric->bom_code }}</td> 
<td>{{ $rowDetailtrimfabric->item_code }}</td> 
<td><a href="{{url('images/'.$rowDetailtrimfabric->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$rowDetailtrimfabric->item_image_path)}}"  ></a></td>
<td>{{ $rowDetailtrimfabric->item_name }}</td>
<td>{{ $rowDetailtrimfabric->class_name }}</td>
<td>{{ $rowDetailtrimfabric->description  }}</td>

<td>
    @php
    $color="";
    foreach($ColorList as $Colors)  
    {
    
     $color= $color.$Colors->color_name.', ';   
    
   }
   @endphp
    {{  rtrim($color,", ")    }}
</td>
<td>
  @php   
    $size=""; 
    foreach($SizeDetailList as $sizes)  
    {
   $size= $size.$sizes->size_name.',';   } 
    @endphp 
    {{ rtrim($size,",") }}
</td>

<td>{{ $rowDetailtrimfabric->consumption  }}</td>
<td>{{ $rowDetailtrimfabric->unit_name  }}</td>
<td style="text-align:right">{{ $rowDetailtrimfabric->wastage  }}</td>
<td style="text-align:right"> {{number_format($rowDetailtrimfabric->bom_qty)}}</td>
<td style="text-align:right">{{ $rowDetailtrimfabric->remark  }}</td>
</tr>
@php

$no=$no+1;
@endphp
@endforeach

@endif


@php
$no=1; 

@endphp

@if(isset($SewingTrimsList))
 
@foreach($SewingTrimsList as $rowDetailtrims)  
 
@php 

$color_ids = explode(',', $rowDetailtrims->color_id);  

$size_ids = explode(',', $rowDetailtrims->size_array); 

$ColorList= App\Models\ColorModel::whereIn('color_id', $color_ids)->where('delflag','=', '0')->get('color_name');

$SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
   
@endphp

<tr>
<td>{{ $no }}</td> 
<td>{{ $rowDetailtrims->sales_order_no }}</td> 
<td>{{ $rowDetailtrims->bom_code }}</td>  
<td>{{ $rowDetailtrims->item_code }}</td> 

<td><a href="{{url('images/'.$rowDetailtrims->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$rowDetailtrims->item_image_path)}}"  ></a></td>
<td>{{ $rowDetailtrims->item_name }}</td>
<td>{{ $rowDetailtrims->class_name }}</td>
<td>{{ $rowDetailtrims->description  }}</td>

<td>
    @php
    $color="";
    foreach($ColorList as $Colors)  
    {
    
     $color= $color.$Colors->color_name.', ';   
    
   }
   @endphp
    {{  rtrim($color,", ")    }}
</td>
<td>
  @php   
    $size=""; 
    foreach($SizeDetailList as $sizes)  
    {
   $size= $size.$sizes->size_name.',';   } 
    @endphp 
    {{ rtrim($size,",") }}
</td>

<td>{{ $rowDetailtrims->consumption  }}</td>
<td>{{ $rowDetailtrims->unit_name  }}</td>
<td style="text-align:right">{{ $rowDetailtrims->wastage  }}</td>
<td style="text-align:right"> {{number_format($rowDetailtrims->bom_qty)}}</td>
<td style="text-align:right">{{ $rowDetailtrims->remark  }}</td>
</tr>
@php

$no=$no+1;
@endphp
@endforeach

@endif

@php
$nos=1; 

@endphp

@if(isset($PackingTrimsList))
@foreach($PackingTrimsList as $rowDetailpacking)  

@php 

$colorids = explode(',', $rowDetailpacking->color_id);  

$sizeids = explode(',', $rowDetailpacking->size_array); 


$ColorListpacking= App\Models\ColorModel::whereIn('color_id', $colorids)->where('delflag','=', '0')->get('color_name');

$SizeDetailListpacking = App\Models\SizeDetailModel::whereIn('size_id',$sizeids)->get('size_name');
   
@endphp
 
<tr>
<td>{{ $nos }}</td>   
<td>{{ $rowDetailpacking->sales_order_no }}</td> 
<td>{{ $rowDetailpacking->bom_code }}</td> 
<td>{{ $rowDetailpacking->item_code }}</td>
<td><a href="{{url('images/'.$rowDetailtrims->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$rowDetailtrims->item_image_path)}}"  ></a></td>
<td>{{ $rowDetailpacking->item_name }}</td>
<td>{{ $rowDetailpacking->class_name }}</td>
<td>{{ $rowDetailpacking->description  }}</td>

<td>
   @php
    $colorpack="";
    foreach($ColorListpacking as $Colorspacking)  
    {
    
     $colorpack= $colorpack.$Colorspacking->color_name.', ';   
    
   }
   @endphp
    {{  rtrim($colorpack,", ")    }}
    
    
</td>
<td>
 
   @php   
    $sizepack=""; 
    foreach($SizeDetailListpacking as $sizespacking)  
    {
        $sizepack= $sizepack.$sizespacking->size_name.',';   
    } 
    @endphp 
    
    {{ rtrim($sizepack,",") }}   
    
</td>

<td style="text-align:right">{{ $rowDetailpacking->consumption  }}</td>
<td>{{ $rowDetailpacking->unit_name  }}</td>
<td style="text-align:right">{{ $rowDetailpacking->wastage  }}</td>
<td style="text-align:right"> {{number_format($rowDetailpacking->bom_qty)}}</td>
<td style="text-align:right">{{ $rowDetailpacking->remark  }}</td>
</tr>
@php

$nos=$nos+1;
@endphp
@endforeach
@endif







                                            </tbody>
                                        </table>
                                           </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        
                                                       <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
  
                        
                        @endsection        
                        
                        
                        
                        