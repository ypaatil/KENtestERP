<!DOCTYPE html>
<html>
   <head>
      <title>KEN Garment</title>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <style>
         html, body, div, h1, h2, h3, p, blockquote, ul, ol, li, pre { 
         margin: 0; padding: 0 }
         li { margin-left: 1.5em }
         h1{text-align:center;}
         @page {size: a4;}
         @media screen { body { margin: 0em }}
         @page {
         margin: 0cm;
         }
         body { font: 11pt Georgia, serif }
         q::before { content: "\201C"; }
         q::after { content: "\201D"; }
         q { font-style: italic }
         h1 { font-size: 3em; font-family: "san-serif"; padding: 0 0 0.2em }
         h2, h3 { font-size: 1.1em; margin: 0.8em 0 0.4em; text-align:center;}
         h4{padding: 0;}
         p, li { margin: 0.2em 0 0.4em }
         ul, ol { margin: 0.2em 0 0.4em 1.5em }
         a { text-decoration: none; color: inherit }
         address {   font-style: normal; text-align:center; }
         aside { float: right; width: 10em }
         footer { float: bottom; text-align: center }
         body.usd td.currency:before { content: "USD\A0$" }
         body.eur td.currency:before { content: "EUR\A0\20AC\A0" }
         body.aud td.currency:before { content: "AUD\A0$" }
         body.eur tr.usd, body.eur tr.aud { display: none }
         body.usd tr.eur, body.usd tr.aud { display: none }
         body.aud tr.eur, body.aud tr.usd { display: none }
         h5{ text-align:center; }
         .lines {
         border: 1px solid #000;
         }
         .button_niks
         {
         background-color: #a0c715;
         border: none;
         color: white;
         padding: 15px 32px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 16px;
         margin: 4px 2px;
         cursor: pointer;
         -webkit-transition-duration: 0.4s; /* Safari */
         transition-duration: 0.4s;
         box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
         }
         .ridge {border-style: groove;}
      </style>
      <style>
         table {
         font-family: arial, sans-serif;
         border-collapse: collapse;
         width: 100%;
         }
         td, th {
         border: 1px solid #dddddd;
         text-align: left;
         padding: 2px;
         }
         th {
         border: 1px solid #dddddd;
         text-align: left;
         padding: 2px;
         background-color:##cfcfcf;
         }
         tr:nth-child(even) {
         background-color: #dddddd;
         }
      </style>
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   </head>
   <body class="usd">
      <div class="container-fluid invoice-container">
        <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
      <button type="button" id="export_button" class="btn btn-warning">Export</button>
      </div>
      <div class="invoice" id="invoice">
        <main>
         <address  >
            <h2>Ken Enterprise Pvt. Ltd.</h2>
            <h6>Fabric Inward</h6>
            <h6>From: {{ date('d-m-Y',strtotime($_POST['fdate'])) }}  To: {{ date('d-m-Y',strtotime($_POST['tdate'])) }} </h6>
         </address>
         @if(count($FabricInwardDetail)>0)
         @foreach($FabricInwardDetail as $rowMaster)   
         <hr>
         <table >
            <thead>
               <th>Code</th>
               <th>Date</th>
               <th>Inward Type</th>
               <th>Buyer Name</th>
               <th>PO No</th>
               <th>Supplier Ref No</th>
            </thead>
            <tbody>
               <tr>
                  <td>{{ $rowMaster->in_code }}</td>
                  <td>{{ $rowMaster->in_date }}</td>
                  <td>{{ $rowMaster->cp_name }}</td>
                  <td>{{ $rowMaster->Ac_name }}</td>
                  <td>{{ $rowMaster->po_code }}</td>
                  <td>{{ $rowMaster->supplierRef }}</td>
               </tr>
            </tbody>
         </table>
         <table >
            <thead>
               <tr>
                  <th rowspan="2">SrNo</th>
                  <th rowspan="2">Color</th>
                  <th rowspan="2">Part</th>
                  <th rowspan="2">Quality</th>
                  <th rowspan="2">Width</th>
                  <th rowspan="2">Meter</th>
                  <th rowspan="2">Track Code</th>
               </tr>
            </thead>
            <tbody>
               @php 
               $FabricInwardDetailstables = App\Models\FabricInwardDetailModel::join('item_master','item_master.item_code', '=', 'inward_details.item_code')
               ->join('part_master','part_master.part_id', '=', 'inward_details.part_id')
               ->where('inward_details.in_code','=', $rowMaster->in_code)->get(['inward_details.*', 'item_master.color_name','item_master.item_description','item_master.dimension','part_master.part_name']);
               $no=1; @endphp
               @foreach($FabricInwardDetailstables as $rowDetail)  
               <tr>
                  <td>{{ $no }}</td>
                  <td>{{ $rowDetail->color_name }}</td>
                  <td>{{ $rowDetail->part_name }}</td>
                  <td>{{ $rowDetail->item_description }}</td>
                  <td>{{ $rowDetail->dimension }}</td>
                  <td>{{ $rowDetail->meter }}</td>
                  <td>{{ $rowDetail->track_code }}</td>
               </tr>
               @php $no=$no+1; @endphp
               @endforeach
            <tfoot>
               <tr>
                  <td colspan="5"><b>Total Meter / Total Taga</b></td>
                  <td style="font-weight:bold;">{{ $rowMaster->total_meter }}</td>
                  <td style="font-weight:bold;">{{ $rowMaster->total_taga_qty }}</td>
               </tr>
            </tfoot>
            </tbody>
         </table>
         @endforeach    
         @else
         <center>
            <h4 style="font-weight:bold;">Record Not Found</h4>
         </center>
         @endif
         <p><small>Print Date </small>
         </p>
         <footer><a href="#">	 Developed By Seaquid Technology (I) Pvt. Ltd. </a></footer>
         <main>
      </div>
      <script src="{{ URL::asset('http://ken.korbofx.org/assets/libs/jquery/jquery.min.js')}}"></script>
      <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
      <script>
         function html_table_to_excel(type)
         {
            var data = document.getElementById('invoice');
    
            var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
    
            XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
    
            XLSX.writeFile(file, 'Fabric Inward Report.' + type);
          }
    
          const export_button = document.getElementById('export_button');
        
          export_button.addEventListener('click', () =>  {
                html_table_to_excel('xlsx');
          });
          
          $('#printInvoice').click(function()
          {
              Popup($('.invoice')[0].outerHTML);
              function Popup(data) 
              {
                  window.print();
                  return true;
              }
          });
      
      </script>
   </body>
</html>