<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KEN GLOBAL DESIGNS PRIVATE LIMITED</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"  >
    <link rel="stylesheet" href="style.css">
</head>
<style>
   
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;400&display=swap');



table, th, td {
    border:1px solid black;
    border-collapse: collapse;
    font-family: 'Poppins', sans-serif;
  }
 .t_data{
    height: 30px;
}
.t_head {
    font-size: 15px;
    font-weight: 600;
    background-color: #eee;
    text-align: center;
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    padding: 5px 20px;
}
.ken_img{
    width: 85%;
}
.ken_data{
   
    font-size: 24px;
    text-align: center;
}
.date{
    font-family: 'Poppins', sans-serif; 
  
}
.get_pass{
    font-family: 'Poppins', sans-serif; 
    text-align: center;
    /*margin: 38px;*/
}
p.get_pass {
    padding: 30px 0px;
}
section.ken_table {
    overflow-x: auto;
}
@media screen and (min-width:320px) and (max-width:768px){
    .ken_img {
        width: 70%;
    }
    p.date {
        text-align: center;
    }
    p.get_pass {
        padding: 5px 0px;
    }
}
</style>
<body>
    <div class="row"> 
        <div class="col-md-3 mt-3">
          <div class="btn-group d-print-none"> <a  href="javascript:void(0);" id="print" class="btn btn-info"> Print</a> </div>
          <button type="button" id="export_button" class="btn btn-warning">Export</button> 
        </div>
    </div>
<div id="invoice">
     <section class="get_pass1" style="margin: 38px;">
        <div class="container1">
        <div class="row">
            <div class="col-md-3">
                <img src="http://kenerp.com/logo/ken.jpeg" alt="" class="ken_img">
            </div>
            <div class="col-md-7">
              <h2 class="ken_data">KEN GLOBAL DESIGNS PRIVATE LIMITED</h2>
              <p class="get_pass"><b>Get Pass-3</b><br/><br/><b> From Date:</b>{{$fromDate}} - <b>To Date :</b>{{$toDate}}</p> 
            </div>
            <div class="col-md-2">
                <p class="date"> Date:{{date("d-m-Y")}}</p>
            </div>
       </div>
</div>
     </section>
<section class="ken_table"  style="margin: 38px;">
      <div class="card"> 
            <div class="col-md-12">
                <table action="" class="get_pass2">
                    <thead>
                        <tr>
                              <th class="t_head" nowrap>Sr.NO.</th>
                              <th class="t_head" nowrap>DC No.</th>
                               <th class="t_head" nowrap>DC Date </th>
                               <th class="t_head" nowrap>Return Type </th>
                               <th class="t_head" nowrap>Received Date </th>
                               <th class="t_head" nowrap>Return DC NO</th>
                               <th class="t_head" nowrap>Buyer/Vendor</th>
                               <th class="t_head" nowrap>Material Type</th>
                               <th class="t_head" nowrap>Sent Through</th>
                               <th class="t_head" nowrap>To Loaction</th>
                               <th class="t_head" nowrap>Department</th>
                               <th class="t_head" nowrap>Item Description</th>
                               <th class="t_head" nowrap>Issued Quantity</th>
                               <th class="t_head" nowrap>Returned Quantity</th>
                               <th class="t_head" nowrap>Balance To Return Quantity</th>
                               <th class="t_head" nowrap>UOM</th>
                               <th class="t_head" nowrap>Base Rate </th>
                               <th class="t_head" nowrap>Balance To Return Amount</th>
                               <th class="t_head" nowrap>GST%</th>
                               <th class="t_head" nowrap>Balance To GST Amount</th>
                               <th class="t_head" nowrap>Balance To Return Total Amount</th> 
                               <th class="t_head" nowrap>User</th>
                               <th class="t_head" nowrap>Remark</th>
                        </tr>
                    </thead>
                    <tbody> 
                           @php
                                $srno =1;
                           @endphp
                           @foreach($DeliveryChallanMasterData as $row)
                           
                           @php
                                    if($row->reciever_type == 1)
                                    {
                                        $dcType = 'Delivered';
                                    }
                                    else if($row->reciever_type == 2)
                                    {
                                        $dcType = 'Return';
                                    }
                                    else
                                    {
                                        $dcType = '';
                                    }
                                    if($row->amount > 0 && $row->quantity > 0)
                                    {
                                      $bal_to_return_amt = ($row->amount/$row->quantity) * $row->return_quantity;
                                    }
                                    else
                                    {
                                      $bal_to_return_amt = 0;
                                    }
                                  
                                    if($row->gst_amt > 0 && $row->quantity > 0)
                                    {
                                        $bal_to_gst_amt = ($row->gst_amt/$row->quantity) * $row->return_quantity;
                                    }
                                    else
                                    {
                                       $bal_to_gst_amt = 0;
                                    }
                                  
                                    if($row->gst_amt > 0 && $row->quantity > 0)
                                    {
                                        $bal_to_return_total_amt = ($row->total_amount/$row->quantity) * $row->return_quantity; 
                                    }
                                    else
                                    {
                                        $bal_to_return_total_amt = 0;
                                    }
                                   
                           @endphp
                            </tr> 
                               <td class="t_data text-center">{{$srno++}}</td>
                               <td class="t_data text-center">{{$row->issue_no}}</td>
                               <td class="t_data text-center">{{$row->issue_date}}</td>
                               <td class="t_data text-center">{{$dcType}}</td>
                               <td class="t_data text-center">{{$row->return_date}}</td>
                               <td class="t_data text-center">{{$row->return_issue_no}}</td>
                               <td class="t_data text-center">{{$row->ac_name}}</td>
                               <td class="t_data text-center">{{$row->material_type_name}}</td>
                               <td class="t_data text-center">{{$row->sent_through}}</td>
                               <td class="t_data text-center">{{$row->to_location}}</td>
                               <td class="t_data text-center">{{$row->dept_name}}</td>
                               <td class="t_data text-center">{{$row->item_description}}</td>
                               <td class="t_data text-center">{{$row->quantity}}</td>
                               <td class="t_data text-center">{{$row->return_quantity}}</td>
                               <td class="t_data text-center">{{$row->quantity - $row->return_quantity}}</td>
                               <td class="t_data text-center">{{$row->unit_name}}</td>
                               <td class="t_data text-center">{{$row->rate}}</td>
                               <td class="t_data text-center">{{$bal_to_return_amt}}</td>
                               <td class="t_data text-center">{{$row->gst_per}}</td>
                               <td class="t_data text-center">{{$bal_to_gst_amt}}</td>
                               <td class="t_data text-center">{{$bal_to_return_total_amt}}</td>
                               <td class="t_data">{{$row->username}}</td>
                               <td class="t_data">{{$row->remark}}</td>
                             </tr>
                             @endforeach
                      </tbody>
                </table>
            </div> 
    </div>
</section>
    </div>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>
      function html_table_to_excel(type)
       {
          var data = document.getElementById('invoice');
      
          var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
      
          XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
      
          XLSX.writeFile(file, 'Get Pass-3.' + type);
       }
      
       const export_button = document.getElementById('export_button');
      
       export_button.addEventListener('click', () =>  {
          html_table_to_excel('xlsx');
       });
       
       
       
       
        $('#print').click(function(){
                  Popup($('#invoice')[0].outerHTML);
                  function Popup(data) 
                  {
                      window.print();
                      return true;
                  }
        });
      		
      		
   </script>
</body>
</html>