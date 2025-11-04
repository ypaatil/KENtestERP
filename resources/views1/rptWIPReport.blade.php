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
                  <h4 class="mb-0">WIP Report</h4>
                  <h5 class="mb-0">From Date : {{$fdate}} - To : {{$tdate}}</h5>
               </center>
                 <div class="row mt-3">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; ">
                         <thead>
                              <tr style="background-color:#eee;">
                                   <th nowrap>Date</th>
                                   <th nowrap>Vendor Name</th>
                                   <th class="text-center" nowrap>Cutting</th>
                                   <th class="text-center" nowrap>Issue to Production</th>
                                   <th class="text-center" nowrap>Cutting WIP</th>
                              </tr>
                         </thead>
                         <tbody>
                            @foreach($MasterdataList as $row)
                            @php
                            
                             $CutPanelIssueDetails = DB::select("SELECT  ifnull(sum(size_qty),0) as cutting
                                FROM `cut_panel_issue_size_detail2` where size_qty!=0 AND vendorId = '".$row->vendorId."' 
                                AND cpi_date = '".$row->cpg_date."'");
        
                                if($row->size_qty > 0)
                                {
                            @endphp
                            <tr>
                               <td nowrap>{{$row->cpg_date}}</td>
                               <td nowrap>{{$row->ac_name}}</td>
                               <td class="text-center" nowrap>{{$CutPanelIssueDetails[0]->cutting}}</td>
                               <td class="text-center" nowrap>{{$row->size_qty}}</td>
                               <td class="text-center" nowrap>{{$CutPanelIssueDetails[0]->cutting - $row->size_qty}}</td>
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
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated WIP Report</p>
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
        $('table').append('<tr><td colspan="2" class="text-right"><strong>Total : </strong></td></tr>');
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
                 $('table tr').last().append('<td class="text-center"><strong>'+res+'%</strong></td>')
             }
             else
             {
                 var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",")+ lastThree;
                 $('table tr').last().append('<td class="text-center"><strong>'+res+'</strong></td>')
             }
             //$('table tr').last().append('<td class="text-center"><strong>'+this.toFixed(2)+'</strong></td>')
        });
    });
   
   
  $('#invoice').click(function(){
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

        XLSX.writeFile(file, 'WIP Report.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
     
   </script>
</html>