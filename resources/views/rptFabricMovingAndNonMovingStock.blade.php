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
          table{
          display: table;
          width:100%;
          border-collapse:collapse;
          }
          tr {
          display: table-row;
          padding: 2px;
          }
          tr p {
          margin: 0px !important; 
          }
          td,th {
          display: table-cell;
          padding: 8px;
          width: 300px;
          border: #000000 solid 1px;
          font-size:14px !important;
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
          
          .invoice-container{
                  border: none;
          }
          
          .back
          {
            background: black;
          }
           table {
            border-spacing: 0px;
            table-layout: fixed;
            margin-left: auto;
            margin-right: auto;
        }
 
        td {
            word-wrap: break-word;
        }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container" id="mainDiv">
         <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
         <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
                 <div class="row mt-3">
                    <div class="col-md-12">
                    <center>
                      <h4 class="mb-0">RUNNING ORDER MOVING FABRIC Report</h4>
                    </center>
                      <div id="loader" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
                      <table class="table" style="height:10vh;" id="moving">
                         <thead>
                              <tr style="background-color:#eee;">
                                   <th nowrap>BUYER</th>
                                   <th nowrap>Opening/Against PO</th>
                                   <th class="text-center" nowrap>PO NO.</th>
                                   @foreach($movingItemsDescription as $items)
                                     <th class="text-center" nowrap>{{$items->quality_name}}</th>
                                   @endforeach
                              </tr>
                         </thead>
                         <tbody id="movingBody">
                                
                         </tbody>
                      </table>
                  </div>
                  
                   <div class="col-md-12">
                    <center>
                      <h4 class="mb-0">NON-MOVING FABRIC Report</h4>
                    </center>
                     <div id="loader1" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
                      <table class="table" style="height:10vh;" id="nonMoving" >
                         <thead>
                              <tr style="background-color:#eee;">
                                   <th nowrap>BUYER</th>
                                   <th nowrap>Opening/Against PO</th>
                                   <th class="text-center" nowrap>PO NO.</th>
                                   @foreach($nonMovingItemsDescription as $nonItems)
                                   @php
                                     $NonFabricInwardDetails =DB::select("select quality_master.quality_name 
                                         from inward_details
                                         left join purchase_order ON purchase_order.pur_code = inward_details.po_code
                                         left join inward_master on inward_master.in_code=inward_details.in_code
                                         left join item_master on item_master.item_code=inward_details.item_code 
                                         left join quality_master on quality_master.quality_code=item_master.quality_code 
                                         where purchase_order.po_status = 2 AND inward_details.po_code = '".$nonItems->po_code."' 
                                         AND item_master.quality_code=".$nonItems->quality_code);
                                   @endphp
                                     <th class="text-center" nowrap>{{$NonFabricInwardDetails[0]->quality_name}}</th>
                                   @endforeach
                              </tr>
                         </thead>
                         <tbody id="nonMovingBody">
                         </tbody>
                      </table>
                  </div>
                   
                   <div class="col-md-12">
                    <center>
                      <h4 class="mb-0">Opening Stock Fabric Report</h4>
                    </center>
                     <div id="loader2" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
                      <table class="table" style="height:10vh;" id="openingStock" >
                         <thead>
                              <tr style="background-color:#eee;">
                                   <th class="text-center" nowrap>BUYER</th>
                                   <th class="text-center" nowrap>Opening PO</th>
                                   <th class="text-center" nowrap>PO NO.</th>
                                   @php
                                     $openingDetails1 =DB::select("SELECT DISTINCT quality_master.quality_name  FROM inward_details 
                                        INNER JOIN item_master ON item_master.item_code =  inward_details.item_code
                                        INNER JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                                        WHERE is_opening = 1 GROUP BY inward_details.item_code ");
                                   
                                   @endphp
                                   @foreach($openingDetails1 as $quality)
                                        <th class="text-center">{{$quality->quality_name}}</th>
                                   @endforeach
                              </tr>
                         </thead>
                         <tbody id="openingStockBody">
                         </tbody>
                      </table>
                  </div>
                </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Fabric Moving And Non Moving Stock Report</p>
      <!--<p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>-->
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

   
   
//   $('#invoice').click(function(){
//       Popup($('.invoice')[0].outerHTML);
//       function Popup(data) 
//       {
//           window.print();
//           return true;
//       }
//       });

      function GetMovingReportData()
      {
            $.ajax({
                dataType: "json",
                url: "{{ route('GetMovingReportData') }}",
                beforeSend: function() {
                 $('#loader').show();
                 $('#moving').hide();
                 $('#mainDiv').addClass('back');
                 $('#nonMoving').hide();
                },
                complete: function(){
                 $('#loader').hide();
                 $('#moving').show();
                 $('#mainDiv').removeClass('back');
                 $('#nonMoving').show();
                },
                success: function(data)
                {
                    $('#movingBody').html(data.html);
                  
                    var result = [];
                    $('#moving tr').each(function()
                    {
                       $('td', this).each(function(index, val){
                          if(!result[index]) result[index] = 0;
                          result[index] += parseFloat($(val).text());
                       });
                    });
                     result.shift();
                     result.shift();
                     result.shift();
                    $('#moving').append('<tr><td colspan="3" class="text-right"><strong>Total : </strong></td></tr>');
                    $(result).each(function()
                    {
                       $('#moving tr').last().append('<td class="text-center"><strong>'+this.toFixed(2)+'</strong></td>')
                    });
                }
            });
     }
     
     function GetNonMovingReportData()
     {
            $.ajax({
                dataType: "json",
                url: "{{ route('GetNonMovingReportData') }}",
                beforeSend: function() {
                 $('#loader1').show();
                 $('#nonMoving').hide();
                },
                complete: function(){
                 $('#loader1').hide();
                 $('#nonMoving').show();
                },
                success: function(data)
                {
                    $('#nonMovingBody').html(data.html);
                    
                    var result = [];
                    $('#nonMoving tr').each(function()
                    {
                       $('td', this).each(function(index, val){
                          if(!result[index]) result[index] = 0;
                          result[index] += parseFloat($(val).text());
                       });
                    });
                    result.shift();
                    result.shift();
                    result.shift();
                    $('#nonMoving').append('<tr><td colspan="3" class="text-right"><strong>Total : </strong></td></tr>');
                    $(result).each(function()
                    {
                       $('#nonMoving tr').last().append('<td class="text-center"><strong>'+this.toFixed(2)+'</strong></td>')
                    });
                }
            });
     }
     
     function GetOpeningReportData()
     {
            $.ajax({
                dataType: "json",
                url: "{{ route('GetOpeningReportData') }}",
                beforeSend: function() {
                 $('#loader2').show();
                 $('#openingStock').hide();
                },
                complete: function(){
                 $('#loader2').hide();
                 $('#openingStock').show();
                },
                success: function(data)
                {
                    $('#openingStockBody').html(data.html);
                    
                    var result = [];
                    $('#openingStock tr').each(function()
                    {
                      $('td', this).each(function(index, val){
                          if(!result[index]) result[index] = 0;
                          result[index] += parseFloat($(val).text());
                      });
                    });
                    result.shift();
                    result.shift();
                    result.shift();
                    $('#openingStock').append('<tr><td colspan="3" class="text-right"><strong>Total : </strong></td></tr>');
                    $(result).each(function()
                    {
                      $('#openingStock tr').last().append('<td class="text-center"><strong>'+this.toFixed(2)+'</strong></td>')
                    });
                }
            });
     }
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Fabric Moving And Non Moving Stock Report.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
      $(document).ready(function(){
           GetMovingReportData();
            var result = [];
            $('#moving tr').each(function(){
               $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text().replace(/,/g , ''));
               });
            });
            result.shift();
            result.shift();
            result.shift();
            $('#moving').append('<tr><td colspan="3" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
                var y=Math.round(this);
                y=y.toString();
                var lastThree1 = y.substring(y.length-3);
                var otherNumbers1 = y.substring(0,y.length-3);
                if(otherNumbers1 != '')
                    lastThree1 = ',' + lastThree1;
                var res1 = otherNumbers1.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree1;
               $('#moving tr').last().append('<td class="text-center"><strong>'+res1+'</strong></td>')
            });
      });
      
       $(document).ready(function(){
           GetNonMovingReportData();
           GetOpeningReportData();
            var result = [];
            $('#nonMoving tr').each(function(){
               $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text().replace(/,/g , ''));
               });
            });
            result.shift();
            result.shift();
            result.shift();
            $('#nonMoving').append('<tr><td colspan="3" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
                var y=Math.round(this);
                y=y.toString();
                var lastThree1 = y.substring(y.length-3);
                var otherNumbers1 = y.substring(0,y.length-3);
                if(otherNumbers1 != '')
                    lastThree1 = ',' + lastThree1;
                var res1 = otherNumbers1.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree1;
               $('#nonMoving tr').last().append('<td class="text-center"><strong>'+res1+'</strong></td>')
            });
      });
   </script>
</html>