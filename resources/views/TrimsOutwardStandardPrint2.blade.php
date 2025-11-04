<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ken Global Designs Pvt. Ltd.</title>
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
     background-image: url('http://kenerp.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
     
}



</style>


 @php
if($TrimsOutwardMaster[0]->trim_type==2)
{
    $SalesOrder=DB::select("select sales_order_no from vendor_purchase_order_master where vpo_code='".$TrimsOutwardMaster[0]->vpo_code."'");
 }
 else
  {
    $SalesOrder=DB::select("select sales_order_no from vendor_work_order_master where vw_code='".$TrimsOutwardMaster[0]->vw_code."'");
    }
 @endphp

<div id="printInvoice">
<div class="row" style="border: #000000 solid 1px;">
    <div  class="col-md-4">
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery No:  </b> <span style="display: inline-block;text-align: right;"> {{ isset($TrimsOutwardMaster[0]->trimOutCode) ? $TrimsOutwardMaster[0]->trimOutCode :"" }} </span></br>     
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Date:  </b> <span style="display: inline-block;text-align: right;"> {{ isset($TrimsOutwardMaster[0]->tout_date) ? $TrimsOutwardMaster[0]->tout_date :"" }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Work Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ isset($TrimsOutwardMaster[0]->vw_code) ? $TrimsOutwardMaster[0]->vw_code :"" }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Process Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ isset($TrimsOutwardMaster[0]->vpo_code) ? $TrimsOutwardMaster[0]->vpo_code :"" }} </span></br>
      </div>
    <div  class="col-md-3" >
    </div>
    <div  class="col-md-5">         
    <b style="display: inline-block;text-align: left;" class="mt-1">Vendor: </b>  <span style="display: inline-block;text-align: right;">{{  isset($TrimsOutwardMaster[0]->Ac_name) ? $TrimsOutwardMaster[0]->Ac_name :"" }} </span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order No:</b>  <span style="display: inline-block;text-align: right;">{{  isset($SalesOrder[0]->sales_order_no) ? $SalesOrder[0]->sales_order_no :"" }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: right;">{{  isset($TrimsOutwardMaster[0]->address) ? $TrimsOutwardMaster[0]->address :"" }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">GST No:</b>  <span style="display: inline-block;text-align: right;">{{  isset($TrimsOutwardMaster[0]->gst_no) ? $TrimsOutwardMaster[0]->gst_no :"" }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">PAN No:</b>  <span style="display: inline-block;text-align: right;">{{  isset($TrimsOutwardMaster[0]->pan_no) ? $TrimsOutwardMaster[0]->pan_no :"" }}</span></br>
    </div>
</div>
</div>

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Trims Details:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
   <thead>
<tr>
<th rowspan="2">SrNo</th>
<th rowspan="2">Classification</th>
<th rowspan="2">Item Code</th>
<th rowspan="2">Item Name</th>
<th rowspan="2">UOM</th> 
<th rowspan="2">Qty</th>
 


</tr>

</thead>
<tbody>
@php 

$trimsOutwardDetailstables = App\Models\TrimsOutwardDetailModel::
select('trimsOutwardDetail.item_rate','item_master.color_name','unit_master.unit_name','classification_master.class_name','item_master.item_name','trimsOutwardDetail.item_code','item_master.item_description','item_master.cgst_per','item_master.sgst_per','item_master.igst_per',
'item_master.hsn_code','item_master.dimension', DB::raw('sum(trimsOutwardDetail.item_qty) as item_qty'))
->join('item_master','item_master.item_code', '=', 'trimsOutwardDetail.item_code')
->join('classification_master','classification_master.class_id', '=', 'item_master.class_id')
->join('unit_master','unit_master.unit_id', '=', 'item_master.unit_id')
->where('trimsOutwardDetail.trimOutCode','=', $TrimsOutwardMaster[0]->trimOutCode)
->groupby('trimsOutwardDetail.item_code')
->get();

$no=1; $amt=0;$tamt=0; @endphp
@foreach($trimsOutwardDetailstables as $rowDetail)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->class_name }} </td>
<td>{{ $rowDetail->item_code }}</td>
<td>{{ $rowDetail->item_name }}</td>
 <td>{{ $rowDetail->unit_name }} </td>
<td>{{ round($rowDetail->item_qty,2) }}  
 </td>
 
  
  
  
</tr>
 
@php $no=$no+1;
 
@endphp
  @endforeach
 <td colspan="6" class="text-center"><b>NOT FOR SALE, FOR JOB WORK ONLY</b></td>
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
 




<div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
</footer>
</div>
</main>
</div>
</div>
 

<p class="text-center d-print-none"><a href="/TrimsOutward">&laquo; Back to List</a></p>
</body>

<script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>

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