<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ken Enterprises Pvt. Ltd.</title>
<meta name="author" content="">

<!-- Web Fonts
======================= -->
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>

<!-- Stylesheet
======================= -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/bootstrap.min.css') }}"/>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/all.min.css') }}"/>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/style.css') }}"/>


<style>
    .table-bordered td, .table-bordered th {
    border: 1px solid #0c0c0c;
    body{
    font-family: "Times New Roman", Times, serif;
    }
}

 
</style>

 
</head>
<body>
<!-- Container -->
<div class="container-fluid invoice-container"> 
<!-- Header -->

<div class="invoice">
<!-- Main Content -->
<main>
<!-- Item Details -->
<div class="row">
    
<div class="col-md-4">    
<p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>   

</div>
<div class="col-md-6">    
<h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
<h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
<h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6>
 
</div>
<div class="col-md-2">    

</div>
</div>



<hr>

<div class="">
    <h4 class="text-4"><h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Gate Pass/ Delivery Note</h4></h4>
    
</div>
<style>

  .table{
  display: table;
  width:100%;
  border-collapse:collapse;
}
.tr {
    display: table-row;
    padding: 2px;
}
.tr p {
   margin: 0px !important; 
}
.td {
    display: table-cell;
    padding: 8px;
    width: 410px;
    border: #000000 solid 1px;
}

@page{

  margin: 5px !important;
}

.merged{
    width:25%;
    height:25%;
      padding: 8px;
     display: table-cell;
     background-image: url('http://ken.korbofx.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
     
}



</style>

<div id="printInvoice">
    <div class="row" style="border: #000000 solid 1px;">
    <div  class="col-md-4">
        
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery No:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->fout_code }} </span></br>     
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->fout_date }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ isset($FabricOutwardMaster[0]->sales_order_no) ? $FabricOutwardMaster[0]->sales_order_no : '' }} </span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">Cutting PO No:  </b> <span style="display: inline-block;text-align: right;"> {{ isset($FabricOutwardMaster[0]->vpo_code) ? $FabricOutwardMaster[0]->vpo_code : '' }} </span></br>
       
</div> <div  class="col-md-3" >
     <b style="display: inline-block;text-align: left;" class="mt-1">Main Style Name:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->mainstyle_name }} </span></br>     
      <b style="display: inline-block;text-align: left;" class="mt-1">Sub Style Name:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->substyle_name }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Style No:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->style_no  }} </span></br>
   <b style="display: inline-block;text-align: left;" class="mt-1">Style Name:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->fg_name }} </span></br>
        </div>
    <div  class="col-md-5">         
    <b style="display: inline-block;text-align: left;" class="mt-1">Vendor: </b>  <span style="display: inline-block;text-align: right;">{{  $FabricOutwardMaster[0]->Ac_name }} </span></br>
       
       <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: right;">{{  $FabricOutwardMaster[0]->address }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">GST No:</b>  <span style="display: inline-block;text-align: right;">{{  $FabricOutwardMaster[0]->gst_no }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">PAN No:</b>  <span style="display: inline-block;text-align: right;">{{  $FabricOutwardMaster[0]->pan_no }}</span></br>
    </div>
   
    </div>

</div>

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Roll wise Details:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
   <thead>
<tr>
<th rowspan="2">SrNo</th>

<th rowspan="2">Item Code</th>
<th rowspan="2">Item Name</th>
<th rowspan="2">HSN No.</th>
<th rowspan="2">Color</th>
<th rowspan="2">Part</th>
<th rowspan="2">Quality</th>
<th rowspan="2">Width</th>
<th rowspan="2">Qty</th>
<th rowspan="2">UOM</th>
<th rowspan="2">Shade</th>
<th rowspan="2">Roll No/Track Code</th>
</tr>

</thead>
<tbody>
@php 
  //  DB::enableQueryLog();
      $FabricOutwardDetailstables = App\Models\FabricOutwardDetailModel::select('item_master.color_name','unit_master.unit_name',
      'item_master.item_name','item_master.hsn_code', 
      
        DB::raw('(select width from fabric_checking_details where fabric_checking_details.track_code=fabric_outward_details.track_code) as width'),
      
      
      'fabric_outward_details.item_code','item_master.item_description','inward_details.item_rate',
      'item_master.dimension','part_master.part_name','fabric_outward_details.meter', 'fabric_outward_details.track_code','shade_master.shade_name'  )
      ->leftJoin('item_master','item_master.item_code', '=', 'fabric_outward_details.item_code')
       ->leftJoin('unit_master','unit_master.unit_id', '=', 'item_master.unit_id')
        ->leftJoin('part_master','part_master.part_id', '=', 'fabric_outward_details.part_id')
        ->leftJoin('shade_master','shade_master.shade_id', '=', 'fabric_outward_details.shade_id')
        ->leftJoin('inward_details','inward_details.track_code','=','fabric_outward_details.track_code')
        ->where('fabric_outward_details.fout_code','=', $FabricOutwardMaster[0]->fout_code)
    
    ->get();
  // $query = DB::getQueryLog();
   //       $query = end($query);
   //        dd($query);

$no=1; $amt=0; @endphp
@foreach($FabricOutwardDetailstables as $rowDetail)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->item_code }}</td>
<td> {{ $rowDetail->item_name }}</td>
<td>{{ $rowDetail->hsn_code }}</td>
<td>{{ $rowDetail->color_name }}</td>
<td>{{ $rowDetail->part_name }}</td>
<td>{{ $rowDetail->item_description }}</td>
<td>{{ $rowDetail->width }}</td>
<td>{{ number_format($rowDetail->meter,2) }}</td>
<td>{{$rowDetail->unit_name}}</td>
 <td>{{ $rowDetail->shade_name }}</td>
 
 
   <td>{{ $rowDetail->track_code }}</td>
</tr>

@php $no=$no+1;
 
@endphp
  @endforeach
 
</tbody>
</tbody>
</table>
 
 
 <table class="table table-bordered text-1 table-sm"  >
     <tbody>
     <tr>
    

 

<td colspan="11">  <b>Total Taga:   {{ $FabricOutwardMaster[0]->total_taga_qty }}   </b></td> 
 
 
<td style="font-weight:bold; text-align:left;">Total  Meter: {{ number_format($FabricOutwardMaster[0]->total_meter,2) }}</td>
 
</tr> 
     </tbody>
 </table>
 
 
 
 <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Summary:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
   <thead>
<tr>
<th rowspan="2">SrNo</th>
<th rowspan="2">Item Code</th>
<th rowspan="2">Item Name</th>
<th rowspan="2">Color</th>
<th rowspan="2">Part</th>
<th rowspan="2">Quality</th>
 
<th rowspan="2">Qty</th>
 
 
</tr>

</thead>
<tbody>
@php 

  $FabricOutwardDetailstables = App\Models\FabricOutwardDetailModel::select('item_master.color_name','item_master.item_name',
  'fabric_outward_details.item_code','item_master.item_description','inward_details.item_rate',
  'item_master.dimension','part_master.part_name',DB::raw('sum(fabric_outward_details.meter) as meter'))
  ->leftJoin('item_master','item_master.item_code', '=', 'fabric_outward_details.item_code')
    ->leftJoin('part_master','part_master.part_id', '=', 'fabric_outward_details.part_id')
    ->leftJoin('inward_details','inward_details.track_code','=','fabric_outward_details.track_code')
    ->where('fabric_outward_details.fout_code','=', $FabricOutwardMaster[0]->fout_code)
    ->groupby('fabric_outward_details.item_code')
    ->get();


$no=1; $amt=0; @endphp
@foreach($FabricOutwardDetailstables as $rowDetail)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->item_code }}</td>
<td>{{ $rowDetail->item_code }} {{ $rowDetail->item_name }}</td>
<td>{{ $rowDetail->color_name }}</td>
<td>{{ $rowDetail->part_name }}</td>
<td>{{ $rowDetail->item_description }}</td>
 
<td>{{ number_format($rowDetail->meter) }}</td>
 
</tr>

@php $no=$no+1;
 
@endphp
  @endforeach
<tfoot>
<tr>
    <td colspan="3"><b>    </b></td>
<td  <b>Total Taga:   </b></td>
<td  ><b> {{ $FabricOutwardMaster[0]->total_taga_qty }}  </b></td>
<td style="font-weight:bold;">Total  Meter</td>
<td style="font-weight:bold;">{{ number_format($FabricOutwardMaster[0]->total_meter) }}</td>
 
</tr>    
    <tr >
        
        <td colspan="8" class="text-center"><b>NOT FOR SALE, FOR JOB WORK ONLY</b></td>
        </tr>
</tfoot>
</tbody>
</tbody>
</table>

   <div class="row">
    <!-- Fare Details -->
<div class="col-md-3">
<h4 class="text-4 mt-2">Prepared By:</h4>

 </div>
<div class="col-md-3">
 <h4 class="text-4 mt-2">Checked By:</h4>
    
    </div>
    <div class="col-md-3">
 <h4 class="text-4 mt-2">Approved By:</h4>
    
    </div>
    <div class="col-md-3">
 <h4 class="text-4 mt-2">Authorized By:</h4>
    
    </div>
    </div><br>
   
<!-- Footer -->
<footer  >
  

<div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none">Print</a> </div>
</footer>
</div>
</main>
</div>
</div>
 

<p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
</body>

<script src="{{ URL::asset('http://ken.korbofx.org/assets/libs/jquery/jquery.min.js')}}"></script>

<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
 
<script>  $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
            function Popup(data) 
            {
                window.print();
                return true;
            }
        });
		
		
		</script>

</html>