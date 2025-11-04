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
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
      <button class="button_niks btn  btn-info btn-rounded print" id="doPrint">Print</button>
      <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">Planned Vs Actual-PPC Report</h4>
               </center>
               <!-- Item Details -->
               <h4 class="text-4"></h4>
               <div class=""></div>
               <!-- Passenger Details -->
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr class="text-center">
                            <td colspan="{{count($stitchingInhouseSizeDetails1)+13}}" style="background: #ebca19;">{{$LineData->line_name}}</td>
                        </tr>
                        <tr style="background-color:#eee;">
                           <th nowrap>Sr.No.</th>
                           <th nowrap>Issued Date</th>
                           <th nowrap>Buyer Brand</th>
                           <th nowrap>Main Style Category</th>
                           <th nowrap>KDPL</th>
                           <th nowrap>Work Order No.</th>
                           <th nowrap>Garment Color</th>
                           <th nowrap>Work Order Qty.</th>
                           <th nowrap>Size </th>
                           <th nowrap>Issued Qty.</th>
                           @foreach($stitchingInhouseSizeDetails1 as $row1)
                             <th nowrap class="text-center">{{$row1->sti_date}}</th>
                           @endforeach
                           <th nowrap class="text-center">Rejections + Stains</th>
                           <th nowrap class="text-center">Total</th>
                           <th nowrap class="text-center">Balance</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                            $nos = 1;
                            $totalWorkQty = 0;
                            $totalProductionQty = 0;
                            
                            function sum_same($array) 
                            {  
                                $keyArray = [];
                            
                                foreach ($array as $key=> $val) 
                                {
                                    $size_name = $array[$key]->size_name;
                                    if(isset($keyArray->size_name)) 
                                    {
                                        $keyArray->size_name += $entry->size_qty;
                                    } 
                                    else 
                                    {
                                        $keyArray->size_name = $entry->size_qty;
                                    }
                                }
                            
                                // Convert the keyArray to the old format.
                                $resultArray = [];
                                foreach ($keyArray as $key => $value) {
                                    $resultArray[] = ["size_name" => $key, "QTY" => $value];
                                }
                            
                                return $resultArray;
                            }
                        @endphp
                        @foreach($CutPanelIssueDetails as $row)
                        @php
                            
                            $WorkOrder=DB::select("SELECT * FROM cut_panel_issue_size_detail2 WHERE cpi_date = '".$row->cpi_date."' 
                                        AND vw_code = '".$row->vw_code."' AND vendorId = '".$vendorId."' AND line_id = '".$line_id."' 
                                        AND size_id =".$row->size_id);
                         
                            $sizeName = $row->size_name."-".$WorkOrder[0]->size_qty; 
                            
                            $stiching =DB::select("SELECT sti_date FROM stitching_inhouse_size_detail2 
                                           WHERE vw_code = '".$row->vw_code."' AND sales_order_no = '".$row->sales_order_no."' 
                                           AND color_id = '".$row->color_id."' GROUP By sti_date");
                                           
                            $TotalQty = DB::select("SELECT ifnull(sum(size_qty),0) as totalSizeQty  FROM stitching_inhouse_size_detail2
                                           WHERE vw_code = '".$row->vw_code."' AND sales_order_no = '".$row->sales_order_no."' 
                                           AND color_id = '".$row->color_id."'");           
                            //DB::enableQueryLog();           
                            $stiching1 = DB::select("SELECT stitching_inhouse_size_detail2.size_id,size_name,size_qty FROM stitching_inhouse_size_detail2 
                                           INNER JOIN size_detail ON size_detail.size_id = stitching_inhouse_size_detail2.size_id
                                           WHERE vw_code = '".$row->vw_code."' AND sales_order_no = '".$row->sales_order_no."' 
                                           AND color_id = '".$row->color_id."'");
                                           
                            //dd(DB::getQueryLog());    
                            
                            $RejectionsDetails = DB::select("SELECT ifnull(sum(size_qty),0) as reject_qty  FROM `qcstitching_inhouse_size_reject_detail2` 
                                            WHERE `qcsti_date` = '".$row->cpi_date."' AND `vw_code` = '".$row->vw_code."' 
                                            AND `sales_order_no` = '".$row->sales_order_no."' AND `color_id` = '".$row->color_id."'");
                            
                            $vendorWorkOrderData = DB::select("SELECT * FROM `vendor_work_order_detail` WHERE `vw_code` = '".$row->vw_code."' 
                                                    AND `color_id` = '".$row->color_id."'");
                            
                            unset($sizeNames);
                            $sizeNames = array();
                            $size1 = array();
                            $size2 = array();
                            foreach($stiching1 as $sizes)
                            {  
                                if($sizes->size_qty > 0)
                                {
                                     $size1[] = $sizes->size_name;
                                }
                                
                            }
                            
                            foreach($stiching1 as $sizes)
                            {  
                                if($sizes->size_qty > 0)
                                {
                                
                                     $size2[] = $sizes->size_qty;
                                }
                                
                            }

                            foreach($stiching1 as $sizes)
                            {  
                                if($sizes->size_qty > 0)
                                {
                                    $stiching2 = DB::select("SELECT sum(size_qty) as size_qty FROM stitching_inhouse_size_detail2 
                                           INNER JOIN size_detail ON size_detail.size_id = stitching_inhouse_size_detail2.size_id
                                           WHERE vw_code = '".$row->vw_code."' AND sales_order_no = '".$row->sales_order_no."' 
                                           AND color_id = '".$row->color_id."' AND stitching_inhouse_size_detail2.size_id=".$sizes->size_id);
                                           
                                    $sizeNames[] = $sizes->size_name."-".$stiching2[0]->size_qty;
                                }
                            }
                           
                                                 
                        $List = implode(', ', array_unique($sizeNames));
                        @endphp
                        <tr>
                           <td nowrap>{{$nos++}}</td>
                           <td nowrap>{{$row->cpi_date}}</td>
                           <td nowrap>{{$row->brand_name}}</td>
                           <td nowrap>{{$row->mainstyle_name}}</td>
                           <td nowrap>{{$row->sales_order_no}}</td>
                           <td nowrap>{{$row->vw_code}}</td>
                           <td nowrap>{{$row->color_name}}</td>
                           <td nowrap class="text-right">{{round(isset($vendorWorkOrderData[0]->size_qty_total) ? $vendorWorkOrderData[0]->size_qty_total : 0)}}</td>
                           <td nowrap>{{$List}}</td>
                           <td nowrap  class="text-right">{{number_format(isset($TotalQty[0]->totalSizeQty) ? $TotalQty[0]->totalSizeQty : 0,2)}} </td>
                           @php
                          
                               foreach($stitchingInhouseSizeDetails1 as $row2)
                               {
                                   $stiching1 =DB::select("SELECT ifnull(sum(size_qty),0) as size_qty FROM stitching_inhouse_size_detail2 
                                           WHERE vw_code = '".$row->vw_code."' AND sales_order_no = '".$row->sales_order_no."' 
                                           AND color_id = '".$row->color_id."' AND sti_date='".$row2->sti_date."' GROUP By sti_date");
                                           
                                 $size_qty = 0;
                                
                                 $size_qty = isset($stiching1[0]->size_qty) ? $stiching1[0]->size_qty : 0;
                                 $totalProductionQty = $totalProductionQty + $size_qty;
                           @endphp
                              <td  class="text-right">{{number_format($size_qty,2)}}</td>
                           @php
                               } 
                               $t1 = str_replace(',', '', $TotalQty[0]->totalSizeQty);
                               $t2 = str_replace(',', '', $totalProductionQty+$RejectionsDetails[0]->reject_qty);
                               
                               $balanceQty = $t1-$t2;
                           @endphp
                           <td nowrap class="text-right">{{number_format($RejectionsDetails[0]->reject_qty,2)}}</td>
                           <td nowrap class="text-right">{{number_format($totalProductionQty+$RejectionsDetails[0]->reject_qty,2)}}</td>
                           <td nowrap class="text-right">{{intval(preg_replace('/[^\d.]/', '', $balanceQty))}}</td>
                        </tr>
                        @php        
                               $totalProductionQty = 0;
                        @endphp
                        @endforeach
                        <tr id="totalColumns">
                            <td colspan="7" class="text-right"><strong>Total : </strong></td>
                        </tr>
                        <tr colspan="2"  class"text-right">
                           <td class="text-right" nowrap colspan="10" class"text-right"><b>Planned:</b></td>
                            @php
                               if(count($stiching) < count($stitchingInhouseSizeDetails1))
                               {
                                    for($i=0; $i< count($stitchingInhouseSizeDetails1)+3; $i++)
                                    {
                            @endphp
                                     <td nowrap id="td{{$i}}" ><input type="number" step="any" class="form-control" value="0" style="width: 100px;"  onchange="setTextToTd({{$i}},this.value);" /></td>
                            @php        
                                    }
                               }
                           @endphp
                        </tr>
                        <tr>
                           <td colspan="10" nowrap class="text-right"><b>Deviation:</b></td>
                            @php
                               if(count($stiching) < count($stitchingInhouseSizeDetails1))
                               {
                                    for($j=0; $j< count($stitchingInhouseSizeDetails1)+3; $j++)
                                    {
                            @endphp
                                     <td nowrap id="DevTd{{$j}}" class="text-right">0</td>
                            @php        
                                    }
                               }
                           @endphp
                        </tr>
                     </tbody>
                  </table>
                  </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Planned Vs Actual-PPC Report</p>
      <p class="text-center d-print-none"><a href="{{Route('OpenOrderPPC.index')}}">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
   function setTextToTd(index,ele)
   {
        $("#td"+index).text(ele).css("text-align","right");
        var updated = parseInt(index) + 3;
        var total = $("#total"+updated).text().replace(/,/g , '');
        console.log(total);
        $("#DevTd"+index).text(parseInt(total) - parseInt(ele));
        $("#td"+index).attr("onclick","setInputBox("+index+",this)");
   }
   
   function setInputBox(index,obj)
   {
       $(obj).removeAttr("onclick");
       $(obj).wrapInner('<input type="number" step="any" class="form-control" value="0" style="width: 100px;"  onchange="setTextToTd('+index+',this.value);" />');
   }
   $('#printInvoice').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
      
     document.getElementById("doPrint").addEventListener("click", function() {
     var printContents = document.getElementById('invoice').innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
     });
     
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Planned Vs Actual-PPC Report.' + type);
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
                  if ($(val).text().indexOf(',') > -1)
                  {
                       result[index] += parseFloat($(val).text().replace(/,/g , ''));
                  }
                  else
                  { 
                      result[index] += parseFloat($(val).text() ? $(val).text() : 0);
                  }
              });
            });
             result.shift();
             result.shift();
             result.shift();
             result.shift();
             result.shift();
             result.shift();
             result.shift();
            var p = 0;
            $(result).each(function(){
                var x=this;
                x=x.toString();
                var afterPoint = '';
                if(x.indexOf('.') > 0)
                  afterPoint = x.substring(x.indexOf('.'),x.length);
                x = Math.floor(x);
                x=x.toString();
                var lastThree = x.substring(x.length-3);
                var otherNumbers = x.substring(0,x.length-3);
                if(otherNumbers != '')
                    lastThree = ',' + lastThree;
                var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
              if(res == 'NaN')
              {
                  res = "-";
              }
              else
              {
                  res1 = res.split('.');
                  if(res1.length > 1)
                  {
                     res2 = "."+res1[1].substr(0, 2);
                  }
                  else
                  {
                      res2  = "";
                  }
                  res = res1[0]+""+res2;
              }
              $('#totalColumns').append('<td class="text-right" id="total'+p+'"><strong>'+res+'</strong></td>');
              p++;
            });
      });
   </script>
</html>