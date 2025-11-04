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
         <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
         <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">Cut Panel Issue Vs Production Report</h4>
                  <h5 class="mb-0">From Date : {{$fdate}} - To : {{$tdate}}</h5>
               </center>
                 <div class="row mt-3">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; ">
                         <thead>
                              <tr style="background-color:#eee;">
                                   <th nowrap>Issue Date</th>
                                   <th nowrap>Vendor Name</th>
                                   <th class="text-center" nowrap>Line No.</th>
                                   <th class="text-center" nowrap>Size</th>
                                   <th class="text-center" nowrap>Total Size</th>
                                   <th class="text-center" nowrap>Sewing</th>
                                   <th class="text-center" nowrap>Sewing Vs Balance</th>
                              </tr>
                         </thead>
                         <tbody>
                        @foreach($CutPanelIssueDetails as $row)
                        @php
                            
                            $TotalQty = DB::select("SELECT ifnull(sum(size_qty),0) as totalSizeQty  FROM stitching_inhouse_size_detail2
                                           WHERE vw_code = '".$row->vw_code."' AND sales_order_no = '".$row->sales_order_no."' 
                                           AND color_id = '".$row->color_id."'");           
                            //DB::enableQueryLog();           
                            $stiching1 = DB::select("SELECT stitching_inhouse_size_detail2.size_id,size_name,size_qty FROM stitching_inhouse_size_detail2 
                                           INNER JOIN size_detail ON size_detail.size_id = stitching_inhouse_size_detail2.size_id
                                           WHERE vw_code = '".$row->vw_code."' AND sales_order_no = '".$row->sales_order_no."' 
                                           AND color_id = '".$row->color_id."'");
                                           
                            //dd(DB::getQueryLog());    

                            unset($sizeNames);
                            $sizeNames = array();

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
                       //DB::enableQueryLog();
                       if($vendorId > 0)
                       {
                            $Stitching=DB::select("select stitching_inhouse_master.sti_date, 
                                sum(size_qty_total) as  qty from stitching_inhouse_detail
                                INNER JOIN stitching_inhouse_master on stitching_inhouse_master.sti_code=stitching_inhouse_detail.sti_code
                                inner join line_master on line_master.line_id=stitching_inhouse_master.line_id
                                where stitching_inhouse_master.vendorId='".$row->vendorId."' and  
                                stitching_inhouse_master.sti_date = '".$row->cpi_date."'  AND stitching_inhouse_master.line_id='".$line_id."'
                                group by stitching_inhouse_master.sti_date");
                        }
                        else
                        {
                             $Stitching=DB::select("select stitching_inhouse_master.sti_date, 
                                sum(size_qty_total) as  qty from stitching_inhouse_detail
                                INNER JOIN stitching_inhouse_master on stitching_inhouse_master.sti_code=stitching_inhouse_detail.sti_code
                                inner join line_master on line_master.line_id=stitching_inhouse_master.line_id
                                where stitching_inhouse_master.sti_date = '".$row->cpi_date."'
                                group by stitching_inhouse_master.sti_date");
                        }
                          //dd(DB::getQueryLog());
                        if(count($Stitching) > 0)
                        {
                             $sewingQty = $Stitching[0]->qty;
                        }
                        else
                        {
                             $sewingQty = 0;
                        }
                        if($TotalQty[0]->totalSizeQty > 0)
                        {
                        @endphp
                            <tr>
                               <td nowrap>{{$row->cpi_date}}</td>
                               <td nowrap>{{$row->ac_name}}</td>
                               <td class="text-center" nowrap>{{$row->line_name}}</td>
                               <td nowrap>{{$List}}</td>
                               <td class="text-right" nowrap>{{number_format($TotalQty[0]->totalSizeQty,2)}}</td>
                               <td class="text-right" nowrap>{{number_format($sewingQty,2)}}</td>
                               <td class="text-right" nowrap>{{number_format($TotalQty[0]->totalSizeQty - $sewingQty,2)}}</td>
                            </tr>
                        @php
                            }
                        @endphp
                        @endforeach
                         </tbody>
                      </table>
                  </div>
                </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Cut Panel Issue Vs Production Report</p>
      <!--<p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>-->
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

   $(document).ready(function()
   {
        var result = [];
        $('table tr').each(function(){
           $('td', this).each(function(index, val)
           {
              if(!result[index]) result[index] = 0;
              result[index] += parseFloat($(val).text().replace(/,/g , ''));
           });
        });
        result.shift();
        result.shift();
        result.shift();
        result.shift();
        $('table').append('<tr><td colspan="4" class="text-right"><strong>Total : </strong></td></tr>');
        $(result).each(function(){
             var x=this;
             x=x.toString();
             var lastThree = x.substring(x.length-3);
             var otherNumbers = x.substring(0,x.length-3);
             if(otherNumbers != '')
             
             lastThree = ',' + lastThree;
             var output = lastThree.split('.')[1];
             console.log(output);
             //console.log(lastThree.replace(',', ''));
             if(output > 0)
             {  
                
                 var res = ((otherNumbers+'.'+output)).toFixed(2);
                 res.replace(',', '');
                 $('table tr').last().append('<td class="text-right"><strong>'+res+'%</strong></td>')
             }
             else
             {
                 var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",")+ lastThree;
                 $('table tr').last().append('<td class="text-right"><strong>'+res+'</strong></td>')
             }
             //$('table tr').last().append('<td class="text-right"><strong>'+this.toFixed(2)+'</strong></td>')
        });
    });
   
    $('#invoice').click(function()
    {
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

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Cut Panel Issue Vs Production Report.' + type);
    }

    const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
    });
      
     
   </script>
</html>