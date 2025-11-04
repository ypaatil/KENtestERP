<!DOCTYPE html>
<html lang="en">
   <head>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
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
       <div class="col-md-12" style="margin: 10px;">
      <div class="row">
      <!-- Header -->
      <div class="invoice" style="border: 4px solid #aba3a3;margin: 5px;">
      <!-- Main Content -->
      <main>
         <!-- Item Details -->
        <div class="col-md-12" style="background: blanchedalmond;">
            <div class="row">
                <div class="col-md-4">
                   <p style="margin: 11px 15px 2px 0px;"><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230" style="border-radius: 13px;"> </p>
                </div>
                <div class="col-md-6" style="margin-top: 61px;">
                   <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                </div>
                <div class="col-md-2">    
                </div>
            </div>
        </div>
         <hr>
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
            
            .table thead th, td {
                vertical-align: middle!important;
            }

            th {
                /*writing-mode: vertical-rl;*/
                /* text-orientation: sideways; */
                /*transform: scale(-1);*/
                font-size: 13px;
                margin: 0px;
                background: #58347ec2;
                color: #fff;
            }
            
            .vl{
                writing-mode: horizontal-tb!important;
                text-orientation: sideways!important;
                transform: scale(1)!important;
                font-size: 13px!important;
                margin: 0px!important;
                background: #58347ec2;
                color: #fff;
            }
            .lv{
                writing-mode: vertical-rl;
                /* text-orientation: sideways; */
                transform: scale(-1);
                font-size: 13px;
                margin: 0px;
                text-align: center;
                background: #58347ec2;
                color: #fff;
            }
         </style>
         <!-- Passenger Details -->
         <div class="col-md-12" style="margin: 10px;background: #2e2c2512;">
              <table class="table table-bordered text-1 table-sm" style="height:10vh; white-space: nowrap;">
                    <thead>
                        <tr>
                            <th></th>
                            @foreach($PPCList as $vendor1)
                                <th class="text-center" colspan="2">{{ $vendor1->Ac_name }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="text-center">Date</th>
                            @foreach($PPCList as $vendor2)
                                <th colspan="2" class="text-center">{{ $vendor2->line_name }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th class="text-center"></th>
                            @foreach($PPCList as $vendor3)
                                <th class="text-center">Color</th>
                                <th class="text-center">Qty</th>
                            @endforeach
                        </tr>
                    </thead>
                    @php
                        // Fetch PPC Data
                        $ppcData = DB::SELECT("
                            SELECT p1.*, color_master.color_name, brand_master.description 
                            FROM ppc_master p1 
                            INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = p1.sales_order_no 
                            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id 
                            LEFT JOIN color_master ON color_master.color_id = p1.color_id 
                            WHERE p1.vendorId = ".$searchVendorId."
                            ORDER BY p1.vendorId, p1.line_id, p1.start_date
                        ");
                    
                        // Fetch Holiday Data in One Query
                        $holidayData = DB::SELECT("SELECT unit_id, holiday_date FROM ppc_holiday WHERE unit_id = ".$searchVendorId);
                        $holidayDates = [];
                        foreach ($holidayData as $holiday) {
                            $holidayDates[$holiday->holiday_date] = true;
                        }
                    
                        // Prepare Data
                        $dataArray = [];
                        foreach ($ppcData as $ppc) {
                            $vendorId = $ppc->vendorId;
                            $lineId = $ppc->line_id;
                            $startDate = strtotime($ppc->start_date);
                            $endDate = strtotime($ppc->end_date);
                            $days = ($endDate - $startDate) / 86400 + 2; // Convert seconds to days
                            $targetAmt = $ppc->target;
                            $pCapacity = $ppc->production_capacity;
                    
                            $fullCapacityDays = ($pCapacity > 0) ? floor($targetAmt / $pCapacity) : 0;
                            $remaining = ($pCapacity > 0) ? $targetAmt % $pCapacity : $targetAmt;
                            $actualProductionDays = 0;
                            $holidayCount = 0;
                    
                            for ($i = 0; $i < $days; $i++) {
                                $formattedDate = date('Y-m-d', strtotime("+$i days", $startDate));
                    
                                // Check if it's a holiday
                                if (isset($holidayDates[$formattedDate])) {
                                    $dataArray[$formattedDate][$vendorId][$lineId] = [
                                        'date' => $formattedDate,
                                        'color' => '',
                                        'target' => '',
                                        'isHoliday' => true
                                    ];
                                    $holidayCount++;
                                } else {
                                    if (!isset($dataArray[$formattedDate][$vendorId][$lineId])) {
                                        $dailyTarget = ($actualProductionDays < $fullCapacityDays) ? $pCapacity : (($actualProductionDays == $fullCapacityDays) ? $remaining : 0);
                                        if ($dailyTarget > 0) {
                                            $dataArray[$formattedDate][$vendorId][$lineId] = [
                                                'date' => $formattedDate,
                                                'color' => $ppc->description . "-" . $ppc->color_name,
                                                'target' => $dailyTarget,
                                                'isHoliday' => false
                                            ];
                                            $actualProductionDays++;
                                        }
                                    }
                                }
                            }
                        }
                    @endphp
                    <tbody>
                    @foreach ($dataArray as $date => $vendorData)
                        <tr>
                            <td class="text-center">{{ date('d-M-Y', strtotime($date)) }}</td>
                            @foreach ($PPCList as $vendor)
                                @php
                                    $vendorId = $vendor->vendorId;
                                    $lineId = $vendor->line_id;
                                    $data = $vendorData[$vendorId][$lineId] ?? null;
                                @endphp
                    
                                @if ($data && $data['isHoliday'])
                                    <td class="text-center" colspan="2" style="background-color: #FF99CC; font-weight: 700;">Holiday</td>
                                @elseif ($data)
                                    <td class="text-center">{{ $data['color'] }}</td>
                                    <td class="text-center" style="background-color: #f9f90dc7; font-weight: 700;">
                                        {{ $data['target'] }}
                                    </td>
                                @else
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
              <footer>
                 <div class="btn-group btn-group-sm d-print-none"><a href="javascript:window.print()" class="btn btn-info border text-white shadow-none">Print</a></div>
              </footer>
           </div>
      </div>
      </div>
      </div>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script>
    //   $(document).ready(function(){
    //         var result = [];
    //         $('table tr').each(function(){
    //           $('td', this).each(function(index, val){
    //               if(!result[index]) result[index] = 0;
    //               result[index] += parseFloat($(val).text());
    //           });
    //         });
    //         result.shift();
    //         result.shift();
    //         result.shift();
    //         $('table').append('<tr><td colspan="3" class="text-right"><strong>Total : </strong></td></tr>');
    //         $(result).each(function(){
    //           $('table tr').last().append('<td class="text-center"><strong>'+this+'</strong></td>')
    //         });
    //   });
   </script>
</html>