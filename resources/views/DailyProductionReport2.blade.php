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
        
      <b style="display: inline-block;text-align: left;" class="mt-1">Vendor Name:  </b> <span style="display: inline-block;text-align: right;"> {{$LedgerList[0]->ac_name}}  </span></br>     
       </div> <div  class="col-md-3" >
   
        </div>
    <div  class="col-md-5">       
    
     <b style="display: inline-block;text-align: left;" class="mt-1">
       @php 
 if(isset($fdate) && isset($tdate))
 {
 @endphp     
         
          <b> From:</b>    <span style="display: inline-block;text-align: right;">{{ date('d-m-Y',strtotime($fdate)) }}  <b>To:</b> {{ date('d-m-Y',strtotime($tdate)) }}  </span></br>     
   @php } 
$totalQty=0;

@endphp
    
        </div>
   
    </div>

</div>

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Vendor Production Status Report</h4>
<div class="">
  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
              <thead>
              <tr>
             <th>Department</th>
            <th>Line</th>
            <th>Total O/P</th>
            <th>SAM</th>
            <th>Total Operator</th>
            <th>Total available  Min</th>
            <th>Produced Standard Min</th>
            <th>Total Min Worked</th>
            <th>Line Eff %</th>
                  </tr>
              </thead>
              <tbody> 
         <tr><td colspan="3"><b>Production:</b></td></tr>
                     
                       
                     
                      @php
                         
                            $Stitching=DB::select("select stitching_inhouse_master.sti_date, sum(size_qty_total) as  qty, stitching_inhouse_master.sales_order_no,line_master.line_name, total_workers from stitching_inhouse_detail
                            INNER JOIN stitching_inhouse_master on stitching_inhouse_master.sti_code=stitching_inhouse_detail.sti_code
                            inner join line_master on line_master.line_id=stitching_inhouse_master.line_id
                            where stitching_inhouse_master.vendorId='".$vendorId."' and 
                            stitching_inhouse_master.sti_date between '".$fdate."' and '".$tdate."'  
                            group by stitching_inhouse_master.sti_date,stitching_inhouse_master.line_id order by stitching_inhouse_master.sti_date,stitching_inhouse_master.line_id
                            ");
                         
                        
                         
                         
                          @endphp
                                @foreach($Stitching as $line)
                             @php    $SAM=DB::select("select sam from sales_order_costing_master where sales_order_no='".$line->sales_order_no."'"); 
                             $totalWorkers=$line->total_workers;
                             if($totalWorkers==0){$totalWorkers=40;}
                             @endphp
                                
                                <tr> 
                                <td>{{$line->sti_date}}</td>
                                <td>{{$line->line_name}}</td>
                                <td>{{$line->qty}}</td>
                                <td>{{$SAM[0]->sam}}</td>
                                <td>{{$totalWorkers}}</td>
                                <td>480</td>
                                <td>{{$line->qty * $SAM[0]->sam}}</td>
                                <td>{{$totalWorkers * 480}}</td>
                                <td>{{round((($line->qty * $SAM[0]->sam)/($totalWorkers * 480)),2)*100}}</td>
                                
                                @php 
                                $totalQty=$totalQty + $line->qty;
                                @endphp 
                                
                                </tr>
                                @endforeach
                        <tr>
                     <td>-</td>
                          <td><b>Total</b></td>
                         <td><b>{{$totalQty}}</b></td>
                      </tr>
            </tbody>
            
            <tfoot>
                
                
            </tfoot>
            
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
 

<p class="text-center d-print-none"><a href="GetDailyProductionReport">	Back To Filter </a>></p>
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