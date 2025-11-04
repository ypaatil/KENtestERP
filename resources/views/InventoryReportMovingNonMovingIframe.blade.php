@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<style>
    .hide{
        display:none;
    }
    #tablefoot th
    {
       border: 1px solid;
    }
    .text-right
    {
        text-align: right;  
    }
    
    #progressBar 
    {
        width: 100%;
        background-color: #f3f3f3;
        border: 1px solid #ccc;
        height: 30px;
        border-radius: 5px;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
    }
    #progress 
    {
        width: 0;
        height: 100%;
        background-color: #4caf50;
        text-align: center;
        line-height: 30px;
        color: white;
        border-radius: 5px;
        transition: width 0.4s ease;
    }
    #checkmark 
    {
        display: none;
        width: 50px;
        height: 50px;
        margin: 20px auto;
    }
    .checkmark_circle 
    {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4caf50;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .checkmark_check 
    {
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4caf50;
        fill: none;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
    }
    @keyframes stroke 
    {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @import 'https://fonts.googleapis.com/css?family=Open+Sans:600,700';
    
    * {font-family: 'Open Sans', sans-serif;}
    
    .rwd-table {
      margin: auto;
      min-width: 300px;
      max-width: 100%;
      border-collapse: collapse;
    }
    
    .rwd-table tr:first-child {
      border-top: none;
      background: #ff000017;
      color: black;
    }
    
    .rwd-table tr {
      border-top: 1px solid #ddd;
      border-bottom: 1px solid #ddd;
      background-color: #f5f9fc;
    }
    
    .rwd-table tr:nth-child(odd):not(:first-child) {
      background-color: #ebf3f9;
    }
    
    .rwd-table th {
      display: none;
    }
    
    .rwd-table td {
      display: block;
    }
    
    .rwd-table td:first-child {
      margin-top: .5em;
    }
    
    .rwd-table td:last-child {
      margin-bottom: .5em;
    }
    
    .rwd-table td:before {
      content: attr(data-th) ": ";
      font-weight: bold;
      width: 120px;
      display: inline-block;
      color: #000;
    }
    
    .rwd-table th,
    .rwd-table td {
      /*text-align: left;*/
    }
    
    .rwd-table {
      color: #333;
      border-radius: .4em;
      overflow: hidden;
    }
    
    .rwd-table tr {
      border-color: #bfbfbf;
    }
    
    .rwd-table th,
    .rwd-table td {
      padding: .5em 1em;
    }
    @media screen and (max-width: 601px) {
      .rwd-table tr:nth-child(2) {
        border-top: none;
      }
    }
    @media screen and (min-width: 600px) {
      .rwd-table tr:hover:not(:first-child) {
        background-color: #d8e7f3;
      }
      .rwd-table td:before {
        display: none;
      }
      .rwd-table th,
      .rwd-table td {
        display: table-cell;
        padding: .25em .5em;
      }
      .rwd-table th:first-child,
      .rwd-table td:first-child {
        padding-left: 0;
      }
      .rwd-table th:last-child,
      .rwd-table td:last-child {
        padding-right: 0;
      }
      .rwd-table th,
      .rwd-table td {
        padding: 1em !important;
      }
    }
    
    
    /* THE END OF THE IMPORTANT STUFF */
    
    /* Basic Styling */
    body {
    /*background: #4B79A1;*/
    /*background: -webkit-linear-gradient(to left, #4B79A1 , #283E51);*/
    /*background: linear-gradient(to left, #4B79A1 , #283E51);        */
    }
    h1 {
      text-align: center;
      font-size: 2.4em;
      color: #f2f2f2;
    }
    .container {
      display: block;
      text-align: center;
    }
    h3 {
      display: inline-block;
      position: relative;
      text-align: center;
      font-size: 1.5em;
      color: #cecece;
    }
    h3:before {
      content: "\25C0";
      position: absolute;
      left: -50px;
      -webkit-animation: leftRight 2s linear infinite;
      animation: leftRight 2s linear infinite;
    }
    h3:after {
      content: "\25b6";
      position: absolute;
      right: -50px;
      -webkit-animation: leftRight 2s linear infinite reverse;
      animation: leftRight 2s linear infinite reverse;
    }
    @-webkit-keyframes leftRight {
      0%    { -webkit-transform: translateX(0)}
      25%   { -webkit-transform: translateX(-10px)}
      75%   { -webkit-transform: translateX(10px)}
      100%  { -webkit-transform: translateX(0)}
    }
    @keyframes leftRight {
      0%    { transform: translateX(0)}
      25%   { transform: translateX(-10px)}
      75%   { transform: translateX(10px)}
      100%  { transform: translateX(0)}
    }
    
    /*
        Don't look at this last part. It's unnecessary. I was just playing with pixel gradients... Don't judge.
    */
    /*
    @media screen and (max-width: 601px) {
      .rwd-table tr {
        background-image: -webkit-linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
        background-image: -moz-linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
        background-image: -o-linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
        background-image: -ms-linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
        background-image: linear-gradient(left, #428bca 137px, #f5f9fc 1px, #f5f9fc 100%);
      }
      .rwd-table tr:nth-child(odd) {
        background-image: -webkit-linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
        background-image: -moz-linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
        background-image: -o-linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
        background-image: -ms-linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
        background-image: linear-gradient(left, #428bca 137px, #ebf3f9 1px, #ebf3f9 100%);
      }
    }*/

</style> 
<div class="row">
   <div class="col-12">
      <div class="card"> 
         <div class="card-body">
            <div class="col-md-12 text-center mb-5"> 
                <div id="progressBar">
                    <div id="progress">0%</div>
                </div> 
                <div id="checkmark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark_circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark_check" fill="none" d="M14 27l10 10L38 17"/>
                    </svg>
                </div>
            </div>
            
               <div class="table-responsive invoice" id="tbleData">
               <div class="col-12 text-center m-4"> 
                     <h4 class="mb-sm-0 font-size-18"><b>Inventory Moving/Non-Moving Report</b></h4>  
               </div> 
                <table id="tbl" class="rwd-table">
                  <thead class="tablehead"> 
                  <tr style="text-align:center; white-space:nowrap">
        			    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col first-col"></th>
        			    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col second-col"></th>
        			    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col third-col">Perticulars</th>  
        			     @php
        			        $colorCtr = 0;
        			        
                            foreach($period as $key1=>$dates)
                            {  
                              $yrdata= strtotime($dates."-01");
                              $monthName = date('F', $yrdata);  
                              $arr = explode("-", $dates);
                              $year = $arr[0];  
                          
                        @endphp
        			    <th colspan="2" style="background:{{$colorArr[0]}};border-top: 3px solid black;border-right: 1px solid gray;" class="text-center">{{$monthName}}-{{$year}}</th>
        			    @php  
                           }   
                        @endphp
                    </tr>
                    <tr style="text-align:center; white-space:nowrap"> 
        			    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col first-col"></th>
        			    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col second-col"></th>
        			    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col third-col"></th>
        			    @php
        			      $colorCtr1 = 0;
                            foreach($period as $key=>$dates)
                            {  
                        @endphp
        			    <th style="background:{{$colorArr[0]}};border-bottom: 3px solid black;border-right: 1px solid #8080803d;" class="sticky_row">Qty.</th> 
        			    <th style="background:{{$colorArr[0]}};border-bottom: 3px solid black;border-right: 1px solid gray;" class="sticky_row">Value</th>
        			    @php 
        			      $colorCtr1++;
                           }   
                        @endphp
                    </tr>
                    </thead>  
                    <tbody id="tablebody"></tbody> 
                    <tfoot id="tablefoot" style="background: #ff000017;color: black;font-size: larger;"></tfoot> 
                </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="https://code.jquery.com/jquery-1.12.3.js"></script> 

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.3.0/js/dataTables.scroller.min.js"></script>
<script src="https://cdn.datatables.net/keytable/2.11.0/js/dataTables.keyTable.min.js"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script>

    LoadFabricInventoryMovingNonMovingReport();
    LoadTrimsInventoryMovingNonMovingReport();
    LoadFGInventoryMovingNonMovingReport();
    var totalRequests = 4;  
    var completedRequests = 0;
    
    function html_table_to_excel(type)
    {
        var data = document.getElementById('tbleData');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'INVENTORY MOVING/NON-MOVING REPORT.' + type);
    }

    const export_button = document.getElementById('export_button');

    export_button.addEventListener('click', () =>  {
        html_table_to_excel('xlsx');
    });
       
   
    function updateProgressBar() 
    {
            var percentage = Math.round((completedRequests / totalRequests) * 100);
            $('#progress').css('width', percentage + '%').text(percentage + '% ...Loading');
 
            if (percentage === 100) {
                $('#progressBar').fadeOut('slow', function() {
                    $('#checkmark').fadeIn('slow');
                });
            }
    }
    function LoadFabricInventoryMovingNonMovingReport()
    {
        var fin_year_id = 5;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadFabricInventoryMovingNonMovingReport') }}",
            beforeSend: function() 
            {
                //$("#sync").attr('disabled','disabled');
            },
            complete: function(data)
            {
                // $("#sync").removeAttr('disabled');
                // setTimeout(function() 
                // { 
                //     $(".alert-success").addClass('hide'); 
                    
                // }, 2500);
            },
            success: function(data)
            {
                $("#tablebody").append(data.html);
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            },
            error: function (error) 
            {
            }
        });
    }
    
    function LoadTrimsInventoryMovingNonMovingReport()
    {
        var fin_year_id = 5;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadTrimsInventoryMovingNonMovingReport') }}",
            beforeSend: function() 
            {
                //$("#sync").attr('disabled','disabled');
            },
            complete: function(data)
            {
                // $("#sync").removeAttr('disabled');
                // setTimeout(function() 
                // { 
                //     $(".alert-success").addClass('hide'); 
                    
                // }, 2500);
            },
            success: function(data)
            {
                $("#tablebody").append(data.html);
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            },
            error: function (error) 
            {
            }
        });
    }
    
    function LoadFGInventoryMovingNonMovingReport()
    {
        var fin_year_id = 5;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadFGInventoryMovingNonMovingReport') }}",
            beforeSend: function() 
            {
                //$("#sync").attr('disabled','disabled');
            },
            complete: function(data)
            {
                // $("#sync").removeAttr('disabled');
                // setTimeout(function() 
                // { 
                //     $(".alert-success").addClass('hide'); 
                    
                // }, 2500);
            },
            success: function(data)
            {
                $("#tablebody").append(data.html);
                LoadWIPInventoryMovingNonMoving();
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            },
            error: function (error) 
            {
            }
        });
    }
    
         function LoadWIPInventoryMovingNonMoving() {
            var fin_year_id = 5;
            $.ajax({
                dataType: "json",
                data: { 'fin_year_id': fin_year_id },
                url: "{{ route('LoadWIPInventoryMovingNonMoving') }}",
                beforeSend: function() {
                    //$("#sync").attr('disabled','disabled');
                },
                complete: function(data) {
                    // $("#sync").removeAttr('disabled');
                    // setTimeout(function() 
                    // { 
                    //     $(".alert-success").addClass('hide'); 
                        
                    // }, 2500);
                },
                success: function(data) {
                    // Assuming data.html contains the HTML for table rows
                    // Append the HTML to the table body
                    $("#tbl tbody").append(data.html);
                    
        
                    // Call any additional functions needed after appending data
                    calculateGrandTotal();
                    completedRequests++;
                    updateProgressBar();
                },
                error: function(error) {
                    console.error('Error loading data:', error);
                }
            });
        }

    $(document).ready(function() 
    {
       setTimeout(function() 
       {
            try {
                $('#tbl').DataTable({
                    dom: 'Bfrtip', // B for Buttons
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            } catch (error) {
                // Handle or ignore the error as needed
                console.error("An error occurred while initializing the DataTable:", error);
            }
        }, 60000);
    });

    
   function calculateGrandTotal() 
   {
        var trims = $(".trims_total_value");
        var fabric = $(".fabric_total_value");
        var FG = $(".FG_total_value");
        var WIP = $(".WIP_total_value");
       
        var GrandTotalHtml = '<tr><th></th><th></th><th nowrap>Grand Total</th>';
        for(var i=0; i<=11;i++)
        { 
            var total = 0;
            var trim = 0;
            var fab = 0;
            var fg = 0;
            var wip = 0;
            
            if(trims.length > 0)
            {
                trim = parseFloat($(trims[i]).text().replace(/,/g, ''));
            }
            
            if(fabric.length > 0)
            {
                fab = parseFloat($(fabric[i]).text().replace(/,/g, ''));
            }
            
            if(FG.length > 0)
            {
                fg = parseFloat($(FG[i]).text().replace(/,/g, ''));
            }
            
            if(WIP.length > i)
            {
                wip = parseFloat($(WIP[i]).text().replace(/,/g, ''));
            }
            
            var total = trim + fab + fg +wip;
            
            GrandTotalHtml += '<th></th><th class="grand_total text-right">' + formatCurrency(total.toFixed(0)) + '</th>';
        }
        GrandTotalHtml += '</tr>';
        var NonMovingTotalHtml = '<tr><th></th><th></th><th nowrap>Total Non moving stock</th>';
        
        var Trims_non = $(".Trims_non_moving_value");
        var Fabric_non = $(".Fabric_non_moving_value");
        var FG_non = $(".FG_non_moving_value");
        
        for(var i=0; i<=11;i++)
        { 
            var total_non = 0;
            var trim_non = 0;
            var fab_non = 0;
            var fg_non = 0; 
            
            if(Trims_non.length > 0)
            {
                trim_non = parseFloat($(Trims_non[i]).text().replace(/,/g, ''));
            }
            
            if(Fabric_non.length > 0)
            {
                fab_non = parseFloat($(Fabric_non[i]).text().replace(/,/g, ''));
            }
            
            if(FG_non.length > 0)
            {
                fg_non = parseFloat($(FG_non[i]).text().replace(/,/g, ''));
            } 
            
            var total_non = trim_non + fab_non + fg_non;
            
            NonMovingTotalHtml += '<th></th><th class="total_non text-right">' + formatCurrency(total_non.toFixed(0)) + '</th>';
        }
        NonMovingTotalHtml += '</tr>';
        
        var PerNonMovingTotalHtml = '<tr><th></th><th></th><th nowrap>% of non moving Inventory in Total Inventory</th>';
        var grand_total = $(".grand_total"); 
        var total_non = $(".total_non");
        var WIP_total_value = $(".WIP_total_value");
        console.log(grand_total);
        for(var i=0; i<=11;i++)
        { 
            var per_total_non = 0;
            var grand = 0;
            var tot_non = 0; 
            var WIP_tot_value = 0;
            
            if(grand_total.length > 0 && total_non.length > 0)
            {
                grand = parseFloat($(grand_total[i]).text().replace(/,/g, ''));
                tot_non = parseFloat($(total_non[i]).text().replace(/,/g, ''));
                WIP_tot_value = parseFloat($(WIP_total_value[i]).text().replace(/,/g, ''));
                var grand1 = parseFloat(grand) + parseFloat(WIP_tot_value); 
                
                if(grand1 > 0 && tot_non > 0)
                {  
                    per_total_non = (tot_non/grand1) * 100;
                }
            } 
             
            PerNonMovingTotalHtml += '<th></th><th class="text-right">' + formatCurrency(per_total_non.toFixed(2)) + '</th>';
        }
        PerNonMovingTotalHtml += '</tr>';
        
        var HoldingCostPAHtml = '<tr><th></th><th></th><th nowrap>Holding cost P.A.</th>'; 
        
        for(var i=0; i<=11;i++)
        {  
            HoldingCostPAHtml += '<th></th><th class="text-right">15.00%</th>';
        }
        HoldingCostPAHtml += '</tr>';
        
        var HoldingCostTotalHtml = '<tr><th></th><th></th><th>Holding Cost </th>';
        
        for(var i=0; i<=11;i++)
        { 
            var holding_cost = 0;
            var tot_non1 = 0; 
            
            if(total_non.length > 0)
            {
                tot_non1 = parseFloat($(total_non[i]).text().replace(/,/g, ''));
                holding_cost = tot_non1*0.15/365*31;
            } 
             
            HoldingCostTotalHtml += '<th></th><th class="text-right">' + formatCurrency(holding_cost.toFixed(0)) + '</th>';
        } 
        HoldingCostTotalHtml += '</tr>';
        $("#tablefoot").html(GrandTotalHtml+NonMovingTotalHtml+PerNonMovingTotalHtml+HoldingCostPAHtml+HoldingCostTotalHtml);
    } 

    function formatCurrency(amount) 
    {
        var strAmount = amount.toString();
        var parts = strAmount.split(".");
        var wholePart = parts[0];
        var decimalPart = parts.length > 1 ? "." + parts[1] : "";
        
        // Split the whole part into an array of digits
        var wholePartArray = wholePart.split('');
        var formattedWholePart = '';
        
        // Start from the end of the whole part
        var counter = 0;
        for (var i = wholePartArray.length - 1; i >= 0; i--) {
            formattedWholePart = wholePartArray[i] + formattedWholePart;
            counter++;
            if (counter === 3 && i > 0) {
                formattedWholePart = ',' + formattedWholePart;
                counter = 0;
            } else if (counter === 2 && i > 0 && wholePartArray.length - i > 3) {
                formattedWholePart = ',' + formattedWholePart;
                counter = 0;
            }
        }
        
        return formattedWholePart + decimalPart;
    }

</script> 