<!DOCTYPE html>
<html lang="en">
   <head>
       @php setlocale(LC_MONETARY, 'en_IN');  @endphp
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
          width: 410px;
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
          background-image: url('http://ken.korbofx.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
          }
          
          .invoice-container{
                  border: none;
          }
          
          .table thead th {
                vertical-align: middle;
          }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
         <a  href="javascript:void(0)" id="printPage" class="button_niks btn  btn-info btn-rounded "> Print</a>
         <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">Get Daily Unit-1 WIP Report</h4>
               </center>
                 <div class="row mt-3">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; ">
                         <thead>
                            <tr>
                                 <th></th>
                                 <th nowrap class="text-center"> Unit - 1 </th>
                                 <th nowrap class="text-center"> Unit - 1 </th>
                                 <th colspan="8"></th>
                                 <th nowrap class="text-center"> Unit 1 + Unit 2 </th>
                                 <th nowrap class="text-center"> Unit 1 + Unit 2 </th>
                                 <th nowrap class="text-center"> Unit 1 + Unit 2 </th>
                                 <th nowrap class="text-center"> Unit 1 + Unit 2 </th>
                            </tr>  
                            <tr style="background-color:#eee;">
                                   <th nowrap rowspan="3"  class="text-center">DATE</th>
                                   <th nowrap rowspan="3"  class="text-center">Cutting</th>
                                   <th class="text-center" nowrap rowspan="3">Cumulative Cutting</th> 
                                   <th class="text-center" nowrap colspan="8">SEWING</th>
                                   <th class="text-center" nowrap  rowspan="3">Total Sewing</th>
                                   <th class="text-center" nowrap  rowspan="3">Cumulative Sewing</th>
                                   <th class="text-center" nowrap  rowspan="3">Finishing/Packing</th>
                                   <th class="text-center" nowrap  rowspan="3">Cumulative Finishing</th>
                            </tr>
                            <tr>
                                  <th class="text-center" nowrap colspan="{{count($lineUnit1Data)}}">UNIT - 1</th>
                                  <th class="text-center" nowrap colspan="{{count($lineUnit2Data)}}">UNIT - 2</th> 
                            </tr> 
                            <tr>
                                @foreach($lineUnit1Data as $lines1)
                                     <th nowrap class="text-center">{{$lines1->line_name}}</th>
                                @endforeach 
                                @foreach($lineUnit2Data as $lines2)
                                     <th nowrap class="text-center">{{$lines2->line_name}}</th>
                                @endforeach 
                            </tr>
                         </thead>
                         <tbody>   
                            @php
                                $TotalSewingQty = 0;
                                $temp = 0;
                                $temp1 = 0;
                                $temp2 = 0;
                            @endphp
                            @foreach($dateArr as $row)
                            @php
                               $cuttingData = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from cut_panel_grn_size_detail2 where cpg_date ='".$row."' AND vendorId=56");
                               $temp += isset($cuttingData[0]->size_qty) ? $cuttingData[0]->size_qty : 0;
                            @endphp
                            <tr>
                               <td nowrap>{{date('d-m-Y', strtotime($row))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",(isset($cuttingData[0]->size_qty) ? $cuttingData[0]->size_qty : 0))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",($temp))}}</td>
                               @foreach($lineUnit1Data as $line1)
                               @php
                                   $stitchingData1 = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  
                                                    from stitching_inhouse_size_detail2 where sti_date ='".$row."' 
                                                    AND vendorId=56 AND line_id='".$line1->line_id."'");
                               @endphp
                               <td nowrap class="text-right">{{money_format("%!.0n",(isset($stitchingData1[0]->size_qty) ? $stitchingData1[0]->size_qty : 0))}}</td>
                               @php
                                    $TotalSewingQty = $TotalSewingQty + $stitchingData1[0]->size_qty;
                               @endphp
                               @endforeach  
                               @foreach($lineUnit2Data as $line2)
                               @php
                                   $stitchingData2 = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  
                                                    from stitching_inhouse_size_detail2 where sti_date ='".$row."' 
                                                    AND vendorId=115 AND line_id='".$line2->line_id."'");
                               @endphp
                               <td nowrap class="text-right">{{money_format("%!.0n",(isset($stitchingData2[0]->size_qty) ? $stitchingData2[0]->size_qty : 0))}}</td>
                                @php
                                    $TotalSewingQty = $TotalSewingQty + $stitchingData2[0]->size_qty;
                                @endphp
                               @endforeach 
                               
                               @php
                                 $temp1 += isset($TotalSewingQty) ? $TotalSewingQty : 0;
                                 
                                  $packingData2 = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  
                                                    from packing_inhouse_size_detail2 where pki_date ='".$row."' 
                                                    AND vendorId IN(56,115)");
                                                    
                                 $temp2 += isset($packingData2[0]->size_qty) ? $packingData2[0]->size_qty : 0;
                               @endphp
                               <td class="text-right" nowrap>{{money_format("%!.0n",($TotalSewingQty))}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",($temp1))}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",(isset($packingData2[0]->size_qty) ? $packingData2[0]->size_qty : 0))}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",($temp2))}}</td>
                             </tr>
                             @php
                                    $TotalSewingQty = 0;
                             @endphp
                             @endforeach
                              @php
                                    $totalcols  = count($lineUnit2Data) + count($lineUnit1Data);
                             @endphp
                         </tbody>
                      </table>
                  </div>
                </div>
               </div>
            </main>
         </div>
      </div>
      <input type="hidden" id="Dynamic_Row" value="{{$totalcols}}">
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Daily Unit-1 WIP Report</p>
      <!--<p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>-->
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

   
   
  $('#printPage').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');
        var date_format =  $(data).find('table tbody tr td:nth-child(1):not(:last)')
        date_format.each(function(i){
            var d = $(this).html();
            var dateAr = d.split('-');
            var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0].slice(-2); 
            var newTbl = $(data).find('table tbody tr td:nth-child(1)')[i];
            $(newTbl).html(newDate);
        });
        
        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Daily Unit-1 WIP Report.' + type);
        location.reload();
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
     $(document).ready(function(){
            var result = [];
            $('table tr').each(function(){
              $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text().replace(/,/g , ''));
              });
            });
            result.shift();
            $('table').append('<tr><td class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
                var y=Math.round(this);
                y=y.toString();
                var lastThree1 = y.substring(y.length-3);
                var otherNumbers1 = y.substring(0,y.length-3);
                if(otherNumbers1 != '')
                    lastThree1 = ',' + lastThree1;
                var res1 = otherNumbers1.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree1;
              $('table tr').last().append('<td class="text-right"><strong>'+res1+'</strong></td>');
              $('table tr').last().find('td:eq(2)').html('-');
              var Dynamic_Row = $('#Dynamic_Row').val();
              var CS = parseInt(Dynamic_Row) + parseInt(4);
              var CF = parseInt(Dynamic_Row) + parseInt(6);
              $('table tr').last().find('td:eq('+CS+')').html('-');
              $('table tr').last().find('td:eq('+CF+')').html('-');
            });
      });
      
   </script>
</html>