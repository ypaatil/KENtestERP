<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="https://www.datatables.net/rss.xml">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.jqueryui.min.css">
	<style type="text/css" class="init">
    .text-center
	{
	    text-align:center;
	}
	th
	{
        background: #152d9f!important;
        color: #fff!important;
	}
	</style>
</head>
  
  <body class="wide comments example dt-example-jqueryui">
  @php
        $DBoard = DB::select("SELECT db_id, BK_VOL_TD_P, BK_VOL_M_TO_Dt_P, BK_VOL_Yr_TO_Dt_P, 
            BK_VAL_TD_P, BK_VAL_M_TO_Dt_P, BK_VAL_Yr_TO_Dt_P, SAL_VOL_TD_P,
            SAL_VOL_M_TO_Dt_P, SAL_VOL_Yr_TO_Dt_P, SAL_VAL_TD_P,
            SAL_VAL_M_TO_Dt_P, SAL_VAL_Yr_TO_Dt_P, BOK_SAH_TD_P,
            BOK_SAH_M_TO_Dt_P, BOK_SAH_Y_TO_Dt_P, SAL_SAH_TD_P,
            SAL_SAH_M_TO_Dt_P, SAL_SAH_Yr_TO_Dt_P  FROM dashboard_master");
            $TodayList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
            from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and `order_received_date`=CURRENT_DATE()");
            $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
            from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and MONTH(order_received_date)=MONTH(CURRENT_DATE()) and YEAR(order_received_date)=YEAR(CURRENT_DATE())");
            $YearList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value 
            from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and order_received_date between (select fdate from financial_year_master 
            where financial_year_master.fin_year_id=1) and (select tdate from financial_year_master where financial_year_master.fin_year_id=1)");
        //echo $YearList[0]->total_order_qty;
  @endphp

       <table class="table align-middle table-nowrap mb-0">
          <thead class=" " style="background-color:#f79733; color:white; text-align:center;" >
             <tr >
                <th  style="border: black 0.5px solid;" class="align-middle" rowspan="2">Particular</th>
                <th style="border: black 0.5px solid;" class="align-middle" colspan="3" style="text-align:center">Today</th>
                <th style="border: black 0.5px solid;" class="align-middle" colspan="3" style="text-align:center">Month To Date</th>
                <th style="border: black 0.5px solid;" class="align-middle" colspan="3" style="text-align:center">Year To Date</th>
             </tr>
             <tr  >
                <th style="border: black 0.5px solid;"  class="align-middle">Plan</th>
                <th style="border: black 0.5px solid;" class="align-middle">Actual</th>
                <th  style="border: black 0.5px solid;" class="align-middle">Achievement</th>
                <th style="border: black 0.5px solid;" class="align-middle">Plan</th>
                <th style="border: black 0.5px solid;"  class="align-middle">Actual</th>
                <th  style="border: black 0.5px solid;" class="align-middle">Achievement</th>
                <th style="border: black 0.5px solid;" class="align-middle">Plan</th>
                <th style="border: black 0.5px solid;" class="align-middle">Actual</th>
                <th style="border: black 0.5px solid;" class="align-middle">Achievement</th>
             </tr>
          </thead>
          <tbody>
             <tr style="color:black; text-align:right;   border: black 0.5px solid;">
                <td style="border: black 0.5px solid;"><b>Booking Volume (Pcs) In Lakh</b>	</td>
                <td style="border: black 0.5px solid;">{{money_format('%!i',$DBoard[0]->BK_VOL_TD_P)}} </td>
                @if($TodayList[0]->total_order_qty!=0)
                <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{date('Y-m-d')}}&tdate={{date('Y-m-d')}}">{{money_format('%!i',($TodayList[0]->total_order_qty/100000))}}</a></td>
                <td style="border: black 0.5px solid;">{{money_format('%!i',round((($TodayList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_TD_P),2))}} %</td>
                @else
                <td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>
                @endif
                <td style="border: black 0.5px solid;">{{money_format('%!i',$DBoard[0]->BK_VOL_M_TO_Dt_P)}}</td>
                @if($MonthList[0]->total_order_qty!=0)
                <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}">{{money_format('%!i',(round(($MonthList[0]->total_order_qty/100000),2)))}}</a></td>
                <td style="border: black 0.5px solid;">{{money_format('%!i',round((($MonthList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_M_TO_Dt_P),2))}} %</td>
                @else
                <td style="border: black 0.5px solid;"> 0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>
                @endif
                @php 
                $Financial=DB::select("select fdate ,tdate from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
                @endphp
                <td style="border: black 0.5px solid;"> {{money_format('%!i',$DBoard[0]->BK_VOL_Yr_TO_Dt_P)}}  </td>
                @if($YearList[0]->total_order_qty!=0)
                <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{$Financial[0]->fdate}}&tdate={{$Financial[0]->tdate}}">{{money_format('%!i',($YearList[0]->total_order_qty/100000))}}</a></td>
                <td style="border: black 0.5px solid;">  {{money_format('%!i',round((($YearList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_Yr_TO_Dt_P),2))}} % </td>
                @else
                <td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;"> 0.00</td>
                @endif
             </tr>
             <tr style="color:black; text-align:right;   border: black 0.5px solid;">
                <td style="border: black 0.5px solid;"><b>Booking Value in In Lakh</b></td>
                <td style="border: black 0.5px solid;">  {{money_format('%!i',$DBoard[0]->BK_VAL_TD_P)}}  </td>
                @if($TodayList[0]->total_order_qty!=0)
                <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{date('Y-m-d')}}&tdate={{date('Y-m-d')}}">{{money_format('%!i',($TodayList[0]->total_order_value/100000))}} </a></td>
                <td style="border: black 0.5px solid;">{{money_format('%!i',round((($TodayList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_TD_P),2))}} % </td>
                @else
                <td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>
                @endif
                <td style="border: black 0.5px solid;" >{{money_format('%!i',$DBoard[0]->BK_VAL_M_TO_Dt_P)}} </td>
                @if($MonthList[0]->total_order_qty!=0)
                <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}">{{money_format('%!i',($MonthList[0]->total_order_value/100000))}} </a></td>
                <td style="border: black 0.5px solid;">  {{money_format('%!i',round((($MonthList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_M_TO_Dt_P),2))}} %</td>
                @else
                <td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>
                @endif
                <td style="border: black 0.5px solid;"> {{money_format('%!i',$DBoard[0]->BK_VAL_Yr_TO_Dt_P)}}   </td>
                @if($YearList[0]->total_order_qty!=0)
                <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{$Financial[0]->fdate}}&tdate={{$Financial[0]->tdate}}">{{money_format('%!i',($YearList[0]->total_order_value/100000))}} </a></td>
                <td style="border: black 0.5px solid;"> {{money_format('%!i',round((($YearList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_Yr_TO_Dt_P),2))}} %</td>
                @else
                <td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>
                @endif
             </tr>
          </tbody>
       </table>
	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/dataTables.jqueryui.min.js"></script>
	<script type="text/javascript" class="init">
        $(document).ready(function() 
        {
            $('#example').DataTable({
                "bProcessing": true,
                "sAutoWidth": false,
                "bDestroy":true,
                "sPaginationType": "bootstrap", // full_numbers
                "iDisplayStart ": 10,
                "iDisplayLength": 10,
                "bPaginate": false, //hide pagination
                "bFilter": false, //hide Search bar
                "bInfo": false, // hide showing entries
            });
        });
	</script>
</body>
</html>