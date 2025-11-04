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



<h4 class="text-4"></h4>
<div class="">
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

<div id="printInvoice">
    <div class="row" style="border: #000000 solid 1px;">
    <div  class="col-md-4">
        
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery No:  </b> <span style="display: inline-block;text-align: right;"> {{ $CutPanelIssueMaster[0]->cpi_code }} </span></br>     
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $CutPanelIssueMaster[0]->cpi_date }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $CutPanelIssueMaster[0]->sales_order_no }} </span></br>
        <b style="display: inline-block;text-align: left;" class="mt-1">Work Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ $CutPanelIssueMaster[0]->vw_code }} </span></br>  
</div> <div  class="col-md-3" >
   
        </div>
    <div  class="col-md-5">         
    <b style="display: inline-block;text-align: left;" class="mt-1">Vendor: </b>  <span style="display: inline-block;text-align: right;">{{  $CutPanelIssueMaster[0]->Ac_name }} </span></br>
        <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: right;">{{  $CutPanelIssueMaster[0]->address }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">GST No:</b>  <span style="display: inline-block;text-align: right;">{{  $CutPanelIssueMaster[0]->gst_no }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">PAN No:</b>  <span style="display: inline-block;text-align: right;">{{  $CutPanelIssueMaster[0]->pan_no }}</span></br>
    </div>
   
    </div>

</div>

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Cut Panel Issue  Details:</h4>
<div class="">
  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
              <thead>
              <tr>
              <th>SrNo</th> 
              <th>Garment Color</th> 
                 @foreach ($SizeDetailList as $sz) 
                   
                      <th>{{$sz->size_name}}</th>
                       
                   @endforeach
                   
                  <th>Total Qty</th>
                  
                  </tr>
              </thead>
              <tbody> 
        @php   $no=1; $totalAmt=0; $totalQty=0;@endphp
          @foreach ($CutPanelIssueList as $row) 
        
          <tr> 
       
          <td>{{$no}}</td>  
          <td>{{$row->color_name}}</td> 

          @if(isset($row->s1))  <td>{{$row->s1}}</td> @endif
          @if(isset($row->s2)) <td>{{$row->s2}}</td>@endif
          @if(isset($row->s3)) <td>{{$row->s3}}</td>@endif
          @if(isset($row->s4)) <td>{{$row->s4}}</td>@endif
          @if(isset($row->s5)) <td>{{$row->s5}}</td>@endif
          @if(isset($row->s6)) <td>{{$row->s6}}</td>@endif
          @if(isset($row->s7)) <td>{{$row->s7}}</td>@endif
          @if(isset($row->s8)) <td>{{$row->s8}}</td>@endif
          @if(isset($row->s9)) <td>{{$row->s9}}</td>@endif
          @if(isset($row->s10)) <td>{{$row->s10}}</td>@endif
          @if(isset($row->s11)) <td>{{$row->s11}}</td>@endif
          @if(isset($row->s12)) <td>{{$row->s12}}</td>@endif
          @if(isset($row->s13)) <td>{{$row->s13}}</td>@endif
          @if(isset($row->s14)) <td>{{$row->s14}}</td>@endif
          @if(isset($row->s15)) <td>{{$row->s15}}</td>@endif
          @if(isset($row->s16)) <td>{{$row->s16}}</td>@endif
          @if(isset($row->s17)) <td>{{$row->s17}}</td>@endif
          @if(isset($row->s18)) <td>{{$row->s18}}</td>@endif
          @if(isset($row->s19)) <td>{{$row->s19}}</td>@endif
         @if(isset($row->s20))  <td>{{$row->s20}}</td> @endif
          <td>{{$row->size_qty_total}}</td> 
       
          </tr>

          @php $no=$no+1; 
          
          if($CutPanelIssueMaster[0]->is_opening==1){ $totalAmt = $totalAmt + $row->size_qty_total * $CutPanelIssueMaster[0]->rate; }
          else { $totalAmt = $totalAmt + $row->size_qty_total * $CutPanelIssueMaster[0]->vendor_rate; } 
          
          $totalQty = $totalQty + $row->size_qty_total;
          
          
          
          @endphp
       @endforeach
            </tbody>
            
            <tfoott>
                <tr>
                    <td colspan="{{count($SizeDetailList) +1}}"></td>
                    <td><b>Total</b></td> <td><b>{{$totalQty}}</b></td>
                    
                    
                </tr>
                
            </tfoott>
            
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
 

<p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
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