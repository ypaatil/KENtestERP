<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quality Control Table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <style>
      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f2f7ff;
        margin: 20px;
        position: relative;
        max-width: 100%;
        overflow-x: hidden;
      }
    
      table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        top: 5;
      } 
    
      th, td {
        padding: 12px;
        font-size: 14px;
        word-wrap: break-word;
      }
    
      td {
        padding: 24px;
      }
      
      th {
        background-color: #014a8e !important;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid #fff;
      }
    
      td {
        text-align: center;
        color: #333;
      }
    
      tr:nth-child(even) {
        background-color: #fff;
      }
    
      tr:hover {
        background-color: #fff;
      }
    
      button {
      width: 150px;
      height: 100px;
      font-size: 40px;
      font-weight: 700;
      cursor: pointer;
      border: none;
      border-radius: 5px;
      box-shadow: 0 3px 5px #777777;
      transition: transform 200ms;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    
      button:focus {
        outline: none;
      }
    
      .pass {
        background-image: linear-gradient(to top, #0ba360 0%, #3cba92 100%) !important;
        color: #fff;
      }
    
      .reject {
        background-image: radial-gradient(circle 248px at center, #fe5d70 0%, #fe5d70 47%, #ff454b 100%) !important;
        color: #fff;
      }
    
      .alter {
        background-image: linear-gradient(to top, #ffa42e 0%, #ff9000 100%) !important;
        color: #fff;
      }
    
      .pass:hover,
      .reject:hover,
      .alter:hover {
        background: #1bb7a9;
        box-shadow: 0 0px 0px #777777, inset 0 0 10px #777777;
      }
    
      .pass:active,
      .reject:active,
      .alter:active {
        transform: scale(0.98);
      }
    
      #toast-container {
        position: fixed;  
        top: 70px;
        right: 20px;
        z-index: 9999;
      }
    
      .toast {
        background: #28a745;
        color: white;
        padding: 10px 20px;
        margin-top: 10px;
        border-radius: 5px;
        font-size: 14px;
        animation: slidein 0.5s, fadeout 0.5s 2.5s forwards;
      }
    
      @keyframes slidein {
        from {
          opacity: 0;
          transform: translateX(100%);
        }
    
        to {
          opacity: 1;
          transform: translateX(0);
        }
      }
    
      @keyframes fadeout {
        to {
          opacity: 0;
          transform: translateX(100%);
        }
      }
    
      #no-internet {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #f8f9fa;
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
      }
    
      .no-internet-content img {
        width: 120px;
        margin-bottom: 20px;
      }
    
      .no-internet-content h1 {
        font-size: 24px;
        color: #333;
        margin-bottom: 10px;
      }
    
      .no-internet-content p {
        font-size: 16px;
        color: #666;
      }
    
      /* Media Query for Tablets */
      @media screen and (max-width: 1024px) {
        body {
          margin: 10px;
        }
    
        table, th, td {
          font-size: 13px;
        }
    
        button {
          font-size: 16px;
          padding: 12px;
          max-width: 100%;
        }
    
        .toast {
          font-size: 13px;
        }
    
        caption {
          font-size: 20px;
        }
      }
      
      .radio-group {
          display: flex;
          gap: 15px;
          flex-wrap: wrap;
          margin: 20px 0;
        }
        
        .radio-option {
          position: relative;
        }
        
        .radio-option input[type="radio"] {
          display: none;
        }
        
        .custom-radio {
          display: inline-block;
          padding: 12px 25px;
          border-radius: 30px;
          font-size: 16px;
          font-weight: 600;
          color: white;
          cursor: pointer;
          transition: 0.3s;
          user-select: none;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .radio {
          background: #fff;
          color:#000;
        }
         
        
        /* Selected state */
        .radio-option input[type="radio"]:checked + .custom-radio {
          outline: 3px solid #ffffff;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
          transform: scale(1.05);
          background-image: linear-gradient(-20deg, #2b5876 0%, #4e4376 100%) !important;
          color:#fff;
        }

        .reply-forward-buttons {
          display: flex;
          gap: 15px;
          justify-content: center;
          position: absolute;
          top: 0;
          right: 0;
        }
        
        .reply-forward-buttons1 {
          display: flex;
          gap: 15px;
          justify-content: center;
          position: absolute;
          top: 6%;
          right: 8%;
        }
        
        .action-button {
          display: flex;
          align-items: center;
          gap: 8px;
          padding: 12px 24px;
          font-size: 18px;
          font-weight: 600;
          border: none;
          border-radius: 30px;
          cursor: pointer;
          background: #f0f0f0;
          color: #333;
          transition: 0.3s ease;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Specific button styles */
        .reply {
          background: linear-gradient(135deg, #74ebd5, #acb6e5);
          color: #fff;
        }
        
        .forward {
          background: linear-gradient(135deg, #ff9a9e, #fad0c4);
          color: #fff;
        }
        
        /* Hover animation */
        .action-button:hover {
          transform: translateY(-3px) scale(1.05);
          box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        
        .action-button1 {
          display: flex;
          align-items: center;
          gap: 8px;
          padding: 12px 24px;
          font-size: 18px;
          font-weight: 600;
          border: none;
          border-radius: 30px;
          cursor: pointer;
          background: #f0f0f0;
          color: #333;
          transition: 0.3s ease;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Specific button styles */
        .reply1 {
          background: linear-gradient(135deg, #74ebd5, #acb6e5);
          color: #fff;
        }
        
        .forward1 {
          background: linear-gradient(135deg, #ff9a9e, #fad0c4);
          color: #fff;
        }
        
        /* Hover animation */
        .action-button1:hover {
          transform: translateY(-3px) scale(1.05);
          box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        
        .popup-overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0,0,0,0.5);
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .popup-content {
            background: #fff;
            width: 95vw;
            height: 95vh;
            display: flex;
            border-radius: 8px;
            overflow: hidden;
        }

        
        .popup-left, .popup-right {
          padding: 20px;
          box-sizing: border-box;
          height: 100%;
          overflow-y: auto;
        }
        
        .popup-left {
          width: 20%;
          border-right: 1px solid #ccc;
          background: #f2f2f2;
        }
        
        .popup-right {
          width: 80%;
          background: #fff;
        }
        
        .popup-left h3, .popup-right h3 {
          margin-top: 0;
          margin-bottom: 15px;
          text-align: center;
        }
        
        .operation-list {
          list-style: none;
          padding: 0;
          margin: 0;
        }
        
        .operation-item {
          padding: 12px 10px;
          background: #fff;
          color:#000; 
          margin-bottom: 10px;
          text-align: center;
          border-radius: 5px;
          cursor: pointer;
          font-weight: bold;
          transition: background 0.3s;
          border: 1px solid black;
        }
        
        .operation-item:hover {
          background: #0056b3;
          color: #fff;
        }
        
        .issues-container {
          display: flex;
          flex-wrap: wrap;
          gap: 15px;
        }
        
        .issues {
          width: 100%;
          flex-wrap: wrap;
          gap: 10px;   
          display: contents;
        }
        
        .issues button {
          /*flex: 0 0 150px;*/
          padding: 10px;
          background-image: #fff;
          color: #000; 
          border: none;
          border-radius: 6px;
          cursor: pointer;
          transition: background 0.3s;
        }
        
        .issues button:active {
          background-image: linear-gradient(to top, #ffa42e 0%, #ff9000 100%) !important;
          color: #fff; 
        }
        .issues button:hover {
          background-image: linear-gradient(to top, #ffa42e 0%, #ff9000 100%) !important;
          color: #fff; 
        }
        
        .issues-container button {
          width: 160px;
          height: 120px;
          font-size: 14px;
          font-weight: 700;
          cursor: pointer;
          border: none;
          border-radius: 5px;
          box-shadow: 0 3px 5px #777777;
          transition: transform 200ms;
          display: inline-flex;
          justify-content: center;
          align-items: center;
          text-align: center;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
        }
        
        .operation-item.active {
          background: #0056b3;  /* Change to the active color you want */
          color:#fff;
        }
 
        .radio-group {
          display: flex;         /* Make all labels in a single row */
          flex-wrap: nowrap;     /* Don't allow wrapping */
          overflow-x: auto;       /* Enable horizontal scroll */
          -webkit-overflow-scrolling: touch; /* Smooth scrolling on mobile */
          margin-left: 10%;
          margin-right: 20%;
        }
        
        .radio-option {
          flex: 0 0 auto;        /* Don't shrink */
          margin-right: 30px;    /* Space between radio options */
          white-space: nowrap;   /* Prevent label text from breaking */
        } 
        /* Styling for the search input box */
        #searchInput {
          padding: 10px 15px;
          border-radius: 25px;
          border: 1px solid #ddd;
          font-size: 14px;
          outline: none;
          transition: border-color 0.3s ease, box-shadow 0.3s ease;
          background-color: #f9f9f9;
          margin-bottom: 10px;
          width: 5%;
          position: absolute;
          height: 5%;
        }
        
        #searchInput:focus {
          border-color: #007bff;
          box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }
        
        /* Optional: Add a placeholder style */
        #searchInput::placeholder {
          color: #999;
          font-style: italic;
        }
         #tbles {
            width: 100%;
            table-layout: auto;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            font-size: 2vw; /* Adjust the font size based on the viewport width */
        }
        
        @media (max-width: 1024px) {
            #tbles {
                font-size: 3vw; /* Larger font size on tablets */
            }
        }
        
        @media (max-width: 768px) {
            #tbles {
                font-size: 4vw; /* Adjust font size on smaller devices */
            }
        }
        
        @media (max-width: 480px) {
            #tbles {
                font-size: 5vw; /* Increase font size for very small screens */
            }
        }
        
        .counter-wrapper {
          display: flex;
          flex-direction: column;
          align-items: center;
          width: 100px; /* Adjust as needed */
          /*border: 1px solid #ccc;*/
          padding: 35px;
          border-radius: 8px;
          text-align: center;  
          padding-bottom: 0px;
        }

        .counter-wrapper span {
          /*position: absolute;*/
          padding-top: 1%;
          font-size: 24px;
          font-weight: bold;
        }
        .counter-wrapper button {
          padding: 5px 10px;
          font-size: 20px;
          cursor: pointer;
        }

        .popup-close-btn {
            position: absolute;
            top: 15px;
            right: 60px;
            background: transparent;
            border: none;
            font-size: 47px;
            cursor: pointer;
            color: #333;
            font-weight: 900;
        }
        
       .defect-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            height: 70px; /* Adjust height as needed */
            padding: 5px;
            text-align: center;
        }
        
        .defect-button span {
            font-size: 16px;
            font-weight: bold;
        }
        
        .defect-button label {
            font-size: 14px;
            margin-top: auto;
        }

        .hide
        {
            display:none;
        }
        
        .counter-wrapper button.active 
        {
          background-image: linear-gradient(to top, #ffa42e 0%, #ff9000 100%) !important;
          color: #fff; 
        }
        
         /* Unique modal background */
        .custom-line-modal {
          display: flex;
          justify-content: center;
          align-items: center;
          position: fixed;
          z-index: 9999;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.6);
          backdrop-filter: blur(5px);
        }
    
        /* Unique modal content */
        .custom-line-modal-content {
          background-color: #fff;
          padding: 30px;
          border-radius: 20px;
          text-align: center;
          box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
          animation: fadeIn 0.4s ease;
        }
    
        @keyframes fadeIn {
          from { opacity: 0; transform: scale(0.8); }
          to { opacity: 1; transform: scale(1); }
        }
    
        /* Animated line buttons */
        .custom-line-btn {
          display: inline-block;
          margin: 15px;
          padding: 15px 30px;
          font-size: 18px;
          font-weight: bold;
          border: none;
          border-radius: 50px;
          color: white;
          cursor: pointer;
          transition: transform 0.2s, box-shadow 0.2s;
          background-image: linear-gradient(45deg, #FF416C, #FF4B2B);
          box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
    
        .custom-line-btn:hover {
          transform: scale(1.1);
          box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
    
        .custom-hidden {
          display: none !important;
        }
        
        .live-dot {
            width: 40px;
            height: 40px;
            background-color: red;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.7);
            animation: pulse 1.2s infinite ease-in-out;
            position: absolute;
            margin-top: 8px;
        }
    
        @keyframes pulse {
          0%, 100% {
            transform: scale(1);
            opacity: 1;
          }
          50% {
            transform: scale(1.4);
            opacity: 0.7;
          }
        }
    </style>
</head>
<body>
    <div id="no-internet">
      <div class="no-internet-content">
        <img src="https://cdn-icons-png.flaticon.com/512/3405/3405244.png" alt="No Internet" />
        <h1>No Internet Connection</h1>
        <p>Please check your network settings.</p>
      </div>
    </div>
    <input type="text" id="searchInput" placeholder="Search Work Order...">
    <div class="radio-group">
      @foreach($workOrderData as $vw)
        <label class="radio-option">
          <input type="radio" name="status" value="pass" />
          <span class="custom-radio radio" onclick="GetWOrkOrderTable('{{$vw->vw_code}}');">{{$vw->vw_code}}</span>
        </label> 
      @endforeach
    </div>
    <div class="reply-forward-buttons">
      <span class="action-button reply" onclick="reveFor(this,2);">
        &#x21A9; Reverse
      </span>
      <span class="action-button forward" onclick="reveFor(this,1);" style="background: rgb(0, 123, 255);">
        Forward &#x21AA;
      </span>
      <input type="hidden" id="is_action" value="1">
    </div>
    <input type="hidden" id="line_no" value="" name="line_no">
    <div id="tbles">
     
  </div>
    <div class="popup-overlay">
      <div class="popup-content">
        <div class="reply-forward-buttons1">
          <span class="action-button1 reply1" onclick="reveFor1(this,2);">
            &#x21A9; Reverse
          </span>
          <span class="action-button1 forward1" onclick="reveFor1(this,1);" style="background: rgb(0, 123, 255);">
            Forward &#x21AA;
          </span>
          <input type="hidden" id="is_action1" value="1">
        </div>
        <label class="popup-close-btn close-btn">X</label>
        <div class="popup-left">
          <h3>Operations</h3>
          <ul class="operation-list">
            @foreach($StitichingOperationData as $row)
            <li class="operation-item" operationId="{{$row->dhu_sdt_Id}}" onclick="ReadEachDefectData(this);">{{$row->dhu_sdt_marathi_Name ?? $row->dhu_sdt_Name}}</li>
            @endforeach 
          </ul>
        </div>
        <div class="popup-right">
          <h3>Defects</h3>
          <div class="issues-container hide">
            <div  class="issues">
                @foreach($StitichingDefectData as $row)
                <div class="counter-wrapper"> 
                    <button onclick="QtyCalculate1(this);" style="display: table-row-group;"> <span class="each_defect defect_{{$row->dhu_so_Id}}" defectId="{{$row->dhu_so_Id}}">0</span> <br/><label>{{$row->dhu_so_marathi_Name ?? $row->dhu_so_Name}} </label></button>
                </div>
                <input type="hidden" class="input_each_defect" name="each_defect_{{$row->dhu_so_Id}}" value="0">
                @endforeach  
                <div class="counter-wrapper">
                    <span id="total_defect_qty">0</span><label><b>Total</b></label>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
  
    <!-- Custom Line Selector Modal -->
    <div class="custom-line-modal custom-hidden" id="lineSelectorModal">
      <div class="custom-line-modal-content">
        <h2>Please Select Your Line</h2>
        <button class="custom-line-btn" data-line="Line-1">Line-1</button>
        <button class="custom-line-btn" data-line="Line-2">Line-2</button>
        <button class="custom-line-btn" data-line="Line-3">Line-3</button>
        <button class="custom-line-btn" data-line="Line-4">Line-4</button>
        <!--<button class="custom-line-btn" data-line="Line-5">Line-5</button>-->
      </div>
    </div>
    
  <input type="hidden" id="maxId" value="{{$maxId}}">
  <!-- Toast container -->
  <div id="toast-container"></div>
  <!-- jQuery CDN -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script> 
  
    $(document).ready(function () {
        // Show the modal on qualityControl page load
        $('#lineSelectorModal').removeClass('custom-hidden');
    
        // On button click
        $('.custom-line-btn').on('click', function () {
          const line = $(this).data('line');
          alert('Please confirm your selected ' + line + '\nकृपया आपण निवडलेली ' + line + ' निश्चित करा');
          $("#line_no").val(line);
          $('#lineSelectorModal').addClass('custom-hidden');
        });
    });
  
    function calculateTotal(row) 
    {
        let tbody = $(row).closest('tbody');
    
        let total_pass = 0;
        let total_reject = 0;
        let total_alter = 0;
    
        // Sum all pass_qty inside tbody
        tbody.find(".pass_qty").each(function() {
            total_pass += parseInt($(this).val()) || 0;
        });
    
        // Sum all reject_qty inside tbody
        tbody.find(".reject_qty").each(function() {
            total_reject += parseInt($(this).val()) || 0;
        });
    
        // Sum all alter_qty inside tbody
        tbody.find(".alter_qty").each(function() {
            total_alter += parseInt($(this).val()) || 0;
        });
    
        // Update the totals in tbody (you can adjust the selectors if needed)
        tbody.find(".total_pass").html(total_pass);
        tbody.find(".total_reject").html(total_reject);
        tbody.find(".total_alter").html(total_alter); 
        
        let total_pass_reject = parseInt(total_pass) + parseInt(total_reject);
        // Get initial total_line_bal
        let total_line_bal = parseInt(tbody.find('.initial_line_bal').val()) || 0; // Assuming you store original value somewhere
        let line_balance = total_line_bal - total_pass_reject;
    
        // Update the line balance
        tbody.find('.total_line_bal').html(line_balance);
        
        var vw_code = $(row).attr('vw_code');
        var sales_order_no = $(row).attr('sales_order_no');
        var cutting_qty = $(row).attr('cutting_qty');
        var sz_code = $(row).attr('sz_code');
        var status_id = $(row).attr('status_id'); 
        var color_id = $(row).attr('color_id'); 
        var size_qty = $(row).parent().find('input').val(); 
        var line_no = $("#line_no").val(); 
        if(status_id == 1)
        {
            total_qty = total_pass;
        }
        else if(status_id == 2)
        {
            total_qty = total_reject;
        }
        else if(status_id == 3)
        {
            //total_qty = total_alter;
        }
        else
        {
           // total_qty = 0;
        }
        
        $.ajax({
            dataType: "json",
            url: "{{ route('StoreQualityControlData') }}",
            data:{'vw_code':vw_code,'sales_order_no':sales_order_no,'cutting_qty':cutting_qty,'size_id':sz_code,'status_id':status_id,'size_qty':size_qty,'total_qty':total_qty,'color_id':color_id, 'line_bal':line_balance,'line_no':line_no},
            success: function(data)
            {
               console.log("Data Stored");
            }
        });
    }
    
    function StorePopupData(row)
    {
        var vw_code = $(row).attr('vw_code');
        var maxId = $("#maxId").val();
        var sales_order_no = $(row).attr('sales_order_no');
        var QualityControlId = $(row).attr('QualityControlId'); 
        var color_id = $(row).attr('color_id'); 
        var sz_code = $(row).attr('sz_code'); 
        var operationId = $(row).attr('operationId'); 
        var defectId = $(row).attr('defectId'); 
        var line_no = $("#line_no").val(); 
        var size_qty = $(row).html();  
        if(QualityControlId == '')
        {
            QualityControlId = maxId;
        }
        $.ajax({
            dataType: "json",
            url: "{{ route('StoreAlterQualityControlData') }}",
            data:{'vw_code':vw_code,'sales_order_no':sales_order_no,'QualityControlId':QualityControlId,'size_id':sz_code,'size_qty':size_qty,'color_id':color_id,'operationId':operationId,'defectId':defectId, 'line_no': line_no},
            success: function(data)
            {
               console.log("Data Stored");
            }
        });
    }
    
    function ReadEachDefectData(row)
    {
        $(".issues-container").removeClass('hide');
        var vw_code = $(row).attr('vw_code');
        var sales_order_no = $(row).attr('sales_order_no');
        var QualityControlId = $(row).attr('QualityControlId'); 
        var color_id = $(row).attr('color_id'); 
        var sz_code = $(row).attr('sz_code'); 
        var operationId = $(row).attr('operationId');  
        var size_qty = $(row).html();  
        $(".each_defect").attr("operationId", operationId);
        $(".counter-wrapper span").html(0);
        
        $.ajax({
            dataType: "json",
            url: "{{ route('ReadEachDefectData') }}",
            data:{'vw_code':vw_code,'sales_order_no':sales_order_no,'QualityControlId':QualityControlId,'size_id':sz_code,'color_id':color_id,'operationId':operationId},
            success: function(data)
            {
                var res = data.EachdefectData;
                var total_qty = data.total_qty;
                            
                // Loop through each defect item
                res.forEach(function(item) {
                    // Get the defectId
                    var defectClass = 'defect_' + item.defectId;
            
                    // Set the size_qty to the corresponding class element(s)
                    $('.' + defectClass).html(item.size_qty); // If it's an <input>
                    // OR use `.text()` if it's a <div>, <span>, etc.
                    // $('.' + defectClass).text(item.size_qty);
                });
                
                $("#total_defect_qty").html(total_qty);
            }
        });
    }
    
    function GetWOrkOrderTable(vw_code)
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('GetQualityControlVWTable') }}",
            data:{'vw_code':vw_code},
            success: function(data)
            {
                $("#tbles").html(data.html);
                $(".operation-list").html(data.html1);
                $(".line_nos").html($("#line_no").val());
            }
        });

    }
  
    function reveFor(row, ele) 
    {
        $("#is_action").val(ele);
        // First, remove background from all action-buttons
        $(".action-button").css("background", "");
    
        // Then, set background only for clicked one
        $(row).css("background", '#007bff');
    }
    
    
    function reveFor1(row, ele) 
    {
        $("#is_action1").val(ele);
        // First, remove background from all action-buttons
        $(".action-button1").css("background", "");
    
        // Then, set background only for clicked one
        $(row).css("background", '#007bff');
    }
    
    $(function()
    {
         $('.popup-overlay').fadeOut();
    });
     
     function showToast(message) 
     {
        const $toast = $('<div class="toast"></div>').text(message);
        $('#toast-container').append($toast);
        setTimeout(() => $toast.fadeOut(400, () => $toast.remove()), 3000);
     }

    
     function QtyCalculate(row)
     {
          let count = 0; // use 'let' instead of 'const'
          var is_action = $("#is_action").val();
          
          if (parseInt(is_action) == 2) { 
            count = Math.max($(row).html() - 1, 0);
            console.log("count: " + count);
          } else {
            count = parseInt($(row).html()) + 1;
          }
        
          $(row).html(count);
          $(row).next('input').val(count);
        
          if ($(row).hasClass('pass')) {
            showToast('✔️ Pass added');
          } else if ($(row).hasClass('reject')) {
            showToast('❌ Reject added');
          } else if ($(row).hasClass('alter')) {
            showToast('🔧 Alter added');
          }
     }
     
    
    function QtyCalculate1(row) 
    {
        let is_action1 = parseInt($("#is_action1").val());
        $(this).addClass('active'); 
        let $wrapper = $(row).closest('.counter-wrapper');
        let $span = $wrapper.find('.each_defect');
        let $input = $wrapper.find('.input_each_defect');
    
        if ($span.length === 0) {
            console.error("❌ .each_defect not found");
            return;
        }
    
        let currentCount = parseInt($span.text()) || 0;
        let newCount = (is_action1 === 2) ? Math.max(currentCount - 1, 0) : currentCount + 1;
    
        // ✅ Update text node only
        $span.contents().filter(function() {
            return this.nodeType === 3;
        }).first().replaceWith(newCount);
    
        $input.val(newCount);
        var total = 0; // reset total before counting again
        $('.each_defect').each(function()
        {
            total += parseInt($(this).html()) || 0; // safely parse numbers
        });
    
        $("#total_defect_qty").html(total);
    
        if ($(row).hasClass('pass')) {
            showToast('✔️ Pass added');
        } else if ($(row).hasClass('reject')) {
            showToast('❌ Reject added');
        } else if ($(row).hasClass('alter')) {
            showToast('🔧 Alter added');
        }
        StorePopupData($span);
    }

     
    $(document).on('click', '.operation-item', function(){ 
        $('.operation-item').removeClass('active'); 
        $(this).addClass('active'); 
        var operationId = $(this).attr("operationId");
        $(".counter-wrapper").find("span").attr("operationId", operationId); 
    });

  
    $(document).on('click', '.counter-wrapper button', function(){ 
        $('.counter-wrapper button').removeClass('active'); 
        $(this).addClass('active');  
    });

   

    $('.close-btn, .popup-overlay').click(function(e) 
    {
          if (e.target !== this) return;
          $('.popup-overlay').fadeOut();
          
          $(".issues-container").addClass('hide');
          var QualityControlId = $(".popup-overlay").attr('QualityControlId');
          var sales_order_no = $(".popup-overlay").attr('sales_order_no');
          var vw_code = $(".popup-overlay").attr('vw_code');
          var sz_code = $(".popup-overlay").attr('sz_code');
          var color_id = $(".popup-overlay").attr('color_id');  
          var row = $(".popup-overlay").data('row'); 
          console.log(row);
          $.ajax({
            dataType: "json",
            url: "{{ route('ReadFinalAlterData') }}",
            data:{'vw_code':vw_code,'sales_order_no':sales_order_no,'QualityControlId':QualityControlId,'size_id':sz_code,'color_id':color_id},
            success: function(data)
            {
                $(row).html(data.total_qty);
                $(row).parent().find('input').val(data.total_qty);
            }
          });
          GetWOrkOrderTable(vw_code);    
    });
    
    function OpenPopup(row) 
    {
       $('.popup-overlay').fadeIn();
       $(".counter-wrapper").find('span').html(0);
        var QualityControlId = $(row).attr("QualityControlId");
        var sales_order_no = $(row).attr("sales_order_no");
        var vw_code = $(row).attr("vw_code");
        var sz_code = $(row).attr("sz_code");
        var color_id = $(row).attr("color_id");
     
        var maxId = $("#maxId").val();
        if(QualityControlId == '')
        {
            QualityControlId = maxId;
        }
        
        // Set these as attributes to the span element inside .counter-wrapper
        $(".counter-wrapper").find('span')
            .attr('QualityControlId', QualityControlId)
            .attr('sales_order_no', sales_order_no)
            .attr('vw_code', vw_code)
            .attr('sz_code', sz_code)
            .attr('color_id', color_id);
            
        $(".operation-item").attr('QualityControlId', QualityControlId)
            .attr('sales_order_no', sales_order_no)
            .attr('vw_code', vw_code)
            .attr('sz_code', sz_code)
            .attr('color_id', color_id);
            
        $(".popup-overlay").attr('QualityControlId', QualityControlId)
            .attr('sales_order_no', sales_order_no)
            .attr('vw_code', vw_code)
            .attr('sz_code', sz_code) 
            .data('row', row) 
            .attr('color_id', color_id); 
    }

    $(document).ready(function () 
    {
        // $('.counter-wrapper button').on('click', function() 
        // {
        //     var $span = $(this).siblings('span'); // get the span next to button
        //     var count = parseInt($span.text());   // get current count
        //     $span.text(count + 1);                // increment and set new count
        
        //     var total = 0; // reset total before counting again
        //     $('.each_defect').each(function()
        //     {
        //         total += parseInt($(this).html()) || 0; // safely parse numbers
        //     });
        
        //     $("#total_defect_qty").html(total);
        
        //     StorePopupData($span);
        // });

        
        $('#searchInput').on('input', function() 
        {
            var searchText = $(this).val().toLowerCase();
        
            // Iterate through each radio option
            $('.radio-option').each(function() {
              var optionText = $(this).find('.custom-radio').text().toLowerCase();
        
              // If there's no search input, reset to normal order
              if (searchText === '') {
                $(this).css('order', '0');  // Default order
              } 
              else if (optionText.indexOf(searchText) !== -1) { 
                // Matching search, bring to front
                $(this).css('order', '-1'); 
              } 
              else {
                // Non-matching search, push to back
                $(this).css('order', '1');
              }
            });
        });
        
        
      function checkConnection() {
        if (navigator.onLine) {
          $('#no-internet').fadeOut();
        } else {
          goFullScreen();
          $('#no-internet').fadeIn();
        }
      }
   $(document).one('click', function () {
        goFullScreen();
    });
    function goFullScreen() {
        const elem = document.documentElement; // Whole page
        if (elem.requestFullscreen) {
          elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) { // Firefox
          elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) { // Chrome, Safari, Opera
          elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { // IE/Edge
          elem.msRequestFullscreen();
        }
      }

      // Check initially
      checkConnection();
    
      // Listen for internet connection changes
      window.addEventListener('online', checkConnection);
      window.addEventListener('offline', checkConnection);
      
      
      $('span.reply, span.forward').on('click', function () {
          
          
        const isForward = $(this).hasClass('forward');
    
        // Find the buttons in the NEXT row
        const $targetButtons = $(this).closest('tr').next('tr').find('button.pass, button.reject, button.alter');
    
        $targetButtons.each(function () {
            
          let count = parseInt($(this).text(), 10) || 0;
          var is_action =  $("#is_action").val();
          if(parseInt(is_action) == 2)
          { 
             count = count - 1; 
             console.log("count"+count);
          }
          else
          {
             count += 1;
          }
          $(this).text(count);
          $(this).next('input').val(count); 
        });
    
        if (isForward) {
          showToast('➡️ Forward: Count increased');
        } else {
          showToast('⬅️ Reverse: Count decreased');
        }
      });

      
      $('span.reply1, span.forward1').on('click', function () {
          
          
        const isForward1 = $(this).hasClass('forward1');
    
        // Find the buttons in the NEXT row
        const $targetButtons1 = $(this).parent('div').find('span.each_defect');
    
        $targetButtons1.each(function () {
            
          let count1 = parseInt($(this).text(), 10) || 0;
          var is_action1 =  $("#is_action1").val();
          if(parseInt(is_action1) == 2)
          { 
             count1 = count1 - 1; 
             console.log("count1"+count1);
          }
          else
          {
             count1 += 1;
          }
          $(this).text(count1);
          $(this).next('input').val(count1); 
        });
    
        if (isForward1) {
          showToast('➡️ Forward: Count increased');
        } else {
          showToast('⬅️ Reverse: Count decreased');
        }
      });

      $('button').on('dblclick', function (e) {
          e.preventDefault();
      });
      
    });
  </script>
</body>
</html>
