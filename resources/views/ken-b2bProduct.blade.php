<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ken-b2bProduct</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style> 
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            overflow-y: auto; 
        }

        .container {
            display: flex;
            flex-direction: row;
            padding: 20px;
            width: 100%;
            height: 95vh; /* Full screen height */
            background: #F5F5FA; 
        }

        @keyframes backgroundAnimation {
            0% { background-position: 0 0; }
            100% { background-position: 100% 100%; }
        }

        .category-section {
            width: 15%;
            background: rgba(255, 255, 255, 0.8); /* Transparent background */
            padding: 20px;
            margin-right: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .category-section h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .category-section ul {
            list-style: none;
            padding: 0;
        }

        .category-section li {
            font-size: 12px;
            margin: 10px 0;
            cursor: pointer;
            text-align: center;
            padding: 15px;
            border-radius: 5px;
            background: #f8f9fa;
            transition: background 0.3s ease;
        }

        .category-section li:hover {
            background: #ff00d87a;
            color: white;
        }

        .products-section {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            height: 90vh;
            overflow-y: scroll;
            overflow-x: hidden; 
            /*scrollbar-width: none; */
            /*-ms-overflow-style: none;  */
        }
        
        .product {
            flex: 1 1 calc(33.33% - 20px); /* 3 products per row, considering the gap */
            box-sizing: border-box;
            min-width: 200px; /* Adjust based on design preference */
        }

        
        .products-section::-webkit-scrollbar {
            /*display: none; */
        }

        .carousel {
            position: relative;
            /*width: 100%;*/
            overflow: hidden;
            background: rgba(255, 255, 255, 0.5); /* Transparent background */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .carousel-inner {
            display: flex;
            flex-direction: column;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            display: none;
        }

        .carousel-row.active {
            display: flex;
        }

        .carousel-buttons {
            text-align: center;
            margin-top: 10px;
        }

        .carousel-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 0 10px;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .carousel-buttons button:hover {
            background-color: #0056b3;
        }

        .product-card {
            /*width: 100%;*/
            padding: 20px;
            background: rgba(255, 255, 255, 0.5); /* Transparent background */
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            /*transition: transform 0.3s ease, box-shadow 0.3s ease;*/
        }

        .product-card:hover {
            /*transform: scale(1.05); */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
        }

        .product-card img {
            width: 100%;
            height: 200px;
            /*object-fit: cover;*/
            border-radius: 5px;
        }

        .product-card h3 {
            font-size: 18px;
            margin-top: -9px;
        }

        .product-card p {
            font-size: 10px;
            color: #6c757d;
        }

        .logo-img {
            width: 100px;
            height: auto;
            animation: bounceLogo 2s infinite alternate;
        }

        @keyframes bounceLogo {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-10px);
            }
        }

        .back-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #ff7f00; /* Attractive orange color */
            color: white;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #e07b00; /* Darker orange on hover */
            transform: translateY(-3px);
        }

        .back-btn:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(255, 127, 0, 0.7); /* Glow effect on focus */
        }
        .upload-container {
          display: flex;
          align-items: center;
          justify-content: center;
          flex-direction: column;
          background-color: #f9f9f9; /* Light background for contrast */
          border: 1px solid #ddd; /* Subtle border for definition */
          border-radius: 10px;
          padding: 20px;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Soft shadow */
          max-width: 400px;
          margin: auto;
        }
        
        .upload-form {
          width: 100%;
        }
        
        .file-browser {
          position: relative;
          margin-bottom: 20px;
        }
        
        .upload-input {
          width: 100%;
          /*padding: 10px;*/
          font-size: 8px;
          border: 1px solid #ccc;
          border-radius: 5px;
          transition: all 0.3s ease;
        }
        
        .upload-input:hover,
        .upload-input:focus {
          border-color: #007bff;
          box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        
        .upload-button {
          width: 100%;
          padding: 10px 15px;
          font-size: 10px;
          font-weight: bold;
          background-color: #28a745;
          color: #fff;
          border: none;
          border-radius: 5px;
          transition: background-color 0.3s ease, transform 0.3s ease;
        }
        
        .upload-button:hover {
          background-color: #218838;
          transform: scale(1.02);
          cursor: pointer;
        }
        
        .upload-button:active {
          transform: scale(0.98);
        }
        
        @media (max-width: 576px) {
          .upload-container {
            padding: 15px;
          }
        
          .upload-input {
            font-size: 12px;
          }
        
          .upload-button {
            font-size: 14px;
          }
        }
        
        .hide
        {
            display:none;
        }
        
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /* Modal content */
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            max-width: 90%;  /* Ensures the modal content fits well in smaller screens */
            max-height: 90%; /* Limits the height to 90% of the screen */
            overflow-y: auto; /* Adds scrolling if content overflows vertically */
            box-sizing: border-box;
        }
        
        /* Image inside modal */
        #modalImage {
            width: 100%; /* Makes the image take up full width of the modal */
            max-height: 400px; /* Limits image height */
            object-fit: contain; /* Ensures the image maintains its aspect ratio */
            margin-bottom: 20px;
        }
        
        /* Close button */
        /*.close {*/
        /*    color: #aaa;*/
        /*    font-size: 28px;*/
        /*    font-weight: bold;*/
        /*    position: absolute;*/
        /*    top: 10px;*/
        /*    right: 20px;*/
        /*}*/
        
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
        /* Responsiveness for small screens */
        @media (max-width: 768px) {
            .modal-content {
                padding: 10px;
                max-width: 95%;
                max-height: 85%;
            }
        
            .close {
                font-size: 24px;
                top: 5px;
                right: 10px;
            }
        }
        /* Initially hide the sub-categories */
        .sub-category {
            width : 25vw;
            display: none;
            position: absolute; /* Position it in front of the main category */
            left: 100%; /* Position it to the right of the parent category */
            top: 0; /* Align it to the top of the category */
            padding-left: 20px;
            /*background-color: #007bff; */
            color: white; /* White text color for better contrast */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Optional: adds a shadow for depth */
            z-index: 10; /* Ensures it's in front of other elements */
            transition: opacity 0.3s ease;
            border-radius: 8px;
            color:blue;
        }
        
        /* Make category item a relative container to position sub-category */
        .category-item {
            position: relative;
            cursor: pointer;
        }
        
        /* Animation when showing the sub-category */
        .sub-category.show {
            display: block;
            opacity: 1;
        }
        
        /* Style for checkboxes */
        .sub-category li {
            margin: 10px 0;
            display: flex;
            align-items: center;
            animation: slideIn 0.5s ease-out;
        }
        
        /* Checkboxes styling */
        .sub-checkbox {
            margin-right: 10px;
            transition: transform 0.3s ease;
        }
        
        /* Hover effect on checkboxes */
        .sub-checkbox:hover {
            transform: scale(1.2);
        }
        
        /* Sub-category hover effect */
        .category-item:hover .sub-category {
            display: block;
        }
        
        /* Slide-in animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .tag {
          display: inline-block;
          background-color: gray;
          color: white;
          padding: 5px 10px;
          margin: 5px;
          border-radius: 20px;
          font-size: 8px;
        }
        
        .tag .remove-tag {
          margin-left: 10px;
          cursor: pointer;
          font-weight: bold;
        }

        .selected_product
        {
            width: 60px;
            height: 30px;
        }
        
        .ba-we-love-subscribers {
        	width: 50vw;
        	height: 50px;
        	background-color: #fff;
        	border-radius: 15px;
        	box-shadow: 0px 12px 45px rgba(0, 0, 0, .15);
        	font-family: 'Roboto', sans-serif;
        	text-align: center;
        	margin: 0 0 10px 0;
        	overflow: hidden;
        	opacity: 0;
        }
        .ba-we-love-subscribers.open {
        	height: 10%;
        	opacity: 1;
        }
        .ba-we-love-subscribers.popup-ani {
        	-webkit-transition: all .8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        	transition: all .8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .ba-we-love-subscribers h1 {
        	font-size: 20px;
        	color: #757575;
        	padding: 25px 0;
        	margin: 0;
            font-weight:400;
            font-family: 'Roboto', sans-serif;
        
        }
        .ba-we-love-subscribers .love {
        	width: 20px;
        	height: 20px;
        	background-position: 35px 84px;
        	display: inline-block;
        	margin: 0 6px;
        	background-size: 62px;
        }
        .ba-we-love-subscribers .ba-logo {
        	width: 65px;
        	height: 25px;
        	background-position: 0px;
        	margin: 0 auto;
        	opacity: .5;
        	cursor: pointer;
        }
        .ba-we-love-subscribers .ba-logo:hover {
        	opacity: 1;
        }
        .logo-ani {
        	transition: 0.5s linear;
        	-webkit-transition: 0.5s linear;
        }
        .ba-we-love-subscribers input {
        	font-size: 20px;
        	padding: 12px 15px;
        	/*border-radius: 15px;*/
        	border: 0;
        	outline: none;
        	/*margin: 8px 0;*/
        	width: 90%;
        	box-sizing: border-box;
        	line-height: normal;
        	/*Bootstrap Overide*/
        	font-family: sans-serif;
        	/*Bootstrap Overide*/
        }
        .ba-we-love-subscribers form {
        	padding: 5px 30px 0;
        	margin-bottom: 15px;
        }
        .ba-we-love-subscribers input[name="email"],.ba-we-love-subscribers input[type="text"],.ba-we-love-subscribers input[type="number"] {
        	background-color: #eee;
        	border-radius: 10px;
        }
        .ba-we-love-subscribers input[name="submit"] {
        	background-color: #00aeef;
        	cursor: pointer;
        	color: #fff;
        }
        .ba-we-love-subscribers input[name="submit"]:hover {
        	background-color: #26baf1;
        }
        .ba-we-love-subscribers .img {
        	background-image: url("https://4.bp.blogspot.com/-1J75Et4_5vc/WAYhWRVuMiI/AAAAAAAAArE/gwa-mdtq0NIqOrlVvpLAqdPTV4VAahMsQCPcB/s1600/barrel-we-love-subscribers-img.png");
        }
        .ba-we-love-subscribers-fab {
        	width: 65px;
        	height: 65px;
        	background-color: #00aeef;
        	border-radius: 30px;
        	float: right;
        	box-shadow: 0px 12px 45px rgba(0, 0, 0, .3);
        	z-index: 5;
        	position: relative;
        }
        .ba-we-love-subscribers-fab .img-fab {
        	height: 30px;
        	width: 30px;
        	margin: 15px auto;
        	background-image: url("https://4.bp.blogspot.com/-1J75Et4_5vc/WAYhWRVuMiI/AAAAAAAAArE/gwa-mdtq0NIqOrlVvpLAqdPTV4VAahMsQCPcB/s1600/barrel-we-love-subscribers-img.png");
        	background-position: -1px -53px;
        }
        .ba-we-love-subscribers-fab .wrap {
        	transform: rotate(0deg);
        	-webkit-transition: all .15s cubic-bezier(0.15, 0.87, 0.45, 1.23);
        	transition: all .15s cubic-bezier(0.15, 0.87, 0.45, 1.23);
        }
        .ba-we-love-subscribers-fab .ani {
        	transform: rotate(45deg);
        	-webkit-transition: all .15s cubic-bezier(0.15, 0.87, 0.45, 1.23);
        	transition: all .15s cubic-bezier(0.15, 0.87, 0.45, 1.23);
        }
        .ba-we-love-subscribers-fab .close {
        	background-position: -2px 1px;
        	transform: rotate(-45deg);
        	float: none;
        	/*Bootstrap Overide*/
        	opacity: 1;
        	/*Bootstrap Overide*/
        }
        .ba-we-love-subscribers-wrap {
        	position: fixed;
        	right: 25px;
        	bottom: 50px;
        	z-index: 1000;
        }
        .ba-settings {
        	position: absolute;
        	top: -25px;
        	right: 0px;
        	padding: 10px 20px;
        	background-color: #555;
        	border-radius: 5px;
        	color: #fff;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
            /*display: inline-flex;*/
            text-align: justify;
            width: 47vw;
        }
        
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
            transition: transform 0.3s ease, color 0.3s ease;
            width: 30vw;
        }
        
       
        textarea {
            width: 83%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            outline: none;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }
        
        input:focus,
        textarea:focus {
            border: 2px solid #4c57d6;
            box-shadow: 0 0 5px rgba(76, 87, 214, 0.5);
        }
        
        input::placeholder,
        textarea::placeholder {
           font-family: sans-serif;
           font-size:20px;
            /*color: #aaa;*/
        }
        
        textarea {
            resize: none;
        }
        
        button.submit-btn {
            background: #4c57d6;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background 0.3s ease, transform 0.2s ease;
            width: 100%;
        }
        
        button.submit-btn:hover {
            background: #6a8dfc;
            transform: scale(1.05);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Labels Animation on Focus */
        input:focus + label,
        textarea:focus + label {
            color: #6a8dfc;
            transform: translateY(-5px);
        }
        
        .product-card {
            display: flex; 
            border: 1px solid #ddd;
            padding: 15px;
            max-width: 500px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            gap: 20px; /* Space between image and details */
        }
        
        .product-image {
            flex: 1;
            max-width: 50%;
        }
        
        .product-image img {
            width: 100%;
            border-radius: 8px;
        }
        
        .product-details {
            flex: 1;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            text-align: justify;
            max-width:50%;
        }
        
        .product-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        
        .product-specs,
        .product-material,
        .product-price {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }
        
        .product-price span {
            font-size: 16px;
            font-weight: bold;
            color: #e63946;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            font-size: 10px;
            font-weight: bold;
            cursor: pointer;
            margin-left: -2px;
        }
        
        .checkbox-label input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .product_count {
            position: absolute;
            top: -8px; /* Slightly above the .img-fab */
            right: 45px; /* Adjust positioning */
            background: red;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 63%;
            min-width: 7px;
            text-align: center;
            z-index: 10;
        }
        
        .product_count1 {
            position: absolute;
            top: 100px; 
            font-size: 10px;
            font-weight: bold;
            padding: 4px 8px;
            min-width: 7px;
            text-align: center;
            z-index: 10;
        }
        /*.wrap {*/
        /*    position: relative;*/
        /*    display: inline-block;*/
        /*}*/

        .error { color: red; font-size: 14px; }
        
        .sub-category
        {
            background: #fff;
        }
        
        .hide
        {
            display: none;    
        }
        .gallery {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }
        
        .zoomable {
            width: 200px;
            height: 200px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .zoomable:hover {
            transform: scale(1.1);
        }
        
        /* Fullscreen overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .overlay.active {
            display: flex;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        /* Zoomed image container */
        .zoom-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .overlay img {
            max-width: 60%;
            max-height: 60%;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }
        
        /* Close button on image */
        .close-btn {
            position: absolute;
            top: -30px;
            right: 110px;
            font-size: 25px;
            /*color: white;*/
            cursor: pointer;
            color: red;
            padding: 5px 10px;
            border-radius: 50%;
            transition: 0.3s;
        }
         
        /* Fade-in effect */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
          /* Large screens (desktops, above 1024px) */
        @media screen and (min-width: 1025px) {
           /*.category-section{ */
           /*     width: 25%;*/
           /* } */
        }
        /* Small screens (phones, up to 600px) */
        @media screen and (max-width: 600px) {
            
            .category-section{ 
                width: 25%;
            }
            
            .product-card
            {
                display: block;
            }
            
            .product-details ,.product-image
            {
                flex: 1;
                max-width: 100%;
            }
            
            .ba-we-love-subscribers
            {
                width: 100vw;
            }
            
            .ba-we-love-subscribers input,textarea 
            {
                width: 80vw;
            }
            
            #submitButton
            {
                width: 30vw!important;
            }
            
            .close-btn 
            {
                right: 50px;
            }
                         
        }
        
        /* Medium screens (tablets, 601px to 1024px) */
        @media screen and (min-width: 601px) and (max-width: 1024px) {
           
            /*.intro-section { */
            /*    max-width: 30%; */
            /*}*/
                
            /*.intro-section h1 {*/
            /*    font-size: 2.5em; */
            /*}*/
                
            /*.enter_btn_mg*/
            /*{*/
            /*    width: 60%!important;*/
            /*}*/
        }
        
    </style>
</head>
<body>
    <div class="container">
        <!-- Left-side category section -->
        <div class="category-section">
            <div class="col-12" style="display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('assets/images/ken_svg.svg') }}" class="logo-img" alt="KEN Logo">
            </div>
            <h4>FILTER BY</h4>
            <div class="product_count1">0</div>
            <div id="selectedTagsContainer" style="margin: 20px 0;"></div>
            <div class="col-12 mt-5" style="display: flex; align-items: center; justify-content: center;">
                <a href="/ken-b2b" class="btn btn-warning back-btn">Back</a> 
            </div><br/>
            <ul id="cat_list">
                <li class="category-item">End Use
                    <ul class="sub-category">
                        @foreach($categoryList1 as $row)
                        @php
                            $endUseData = DB::select("SELECT count(*) as total_count FROM exhibition_product WHERE end_use = ?", [$row->filter_name]);
                            $enduse_count = isset($endUseData[0]->total_count) ? $endUseData[0]->total_count : 0;
                        @endphp
                        <li onclick="toggleCheckbox(this); LoadProduct();updateSelectedTags();"><input type="checkbox" class="sub-checkbox end_use" end_use="{{$row->filter_name}}" value="{{$row->filter_name}}" onclick="handleCheckboxClick(event, this);" >{{$row->filter_name}} ({{$enduse_count}})<span class="text_span"></span></li> 
                        @endforeach
                    </ul>
                </li>
                <li class="category-item">Weave
                    <ul class="sub-category">
                        @foreach($categoryList2 as $row)
                        @php
                            $weaveData = DB::select("SELECT count(*) as total_count FROM exhibition_product WHERE weave = ?", [$row->filter_name]);
                            $weave_count = isset($weaveData[0]->total_count) ? $weaveData[0]->total_count : 0;
                        @endphp
                        <li onclick="toggleCheckbox(this); LoadProduct();updateSelectedTags();"><input type="checkbox" class="sub-checkbox weave" weave="{{$row->filter_name}}" value="{{$row->filter_name}}" onclick="handleCheckboxClick(event, this);" >{{$row->filter_name}} ({{$weave_count}})<span class="text_span"></span></li> 
                        @endforeach
                    </ul>
                </li>
                <li class="category-item">
                    Content
                    <ul class="sub-category">
                        @foreach($categoryList3 as $row3)
                        @php
                            $contentData = DB::select("SELECT count(*) as total_count FROM exhibition_product WHERE content = ?", [$row3->filter_name]);
                            $content_count = isset($contentData[0]->total_count) ? $contentData[0]->total_count : 0;
                        @endphp
                        <li onclick="toggleCheckbox(this); LoadProduct();updateSelectedTags();"><input type="checkbox" class="sub-checkbox content" content="{{$row3->filter_name}}" value="{{$row3->filter_name}}" onclick="handleCheckboxClick(event, this);" >{{$row3->filter_name}} ({{$content_count}}) <span class="text_span"></span></li> 
                        @endforeach
                    </ul>
                </li> 
                <li class="category-item">
                    GSM Range
                    <ul class="sub-category">
                        @foreach($categoryList4 as $row)
                        @php
                            $gsm_rangeData = DB::select("SELECT count(*) as total_count FROM exhibition_product WHERE gsm_range = ?", [$row->filter_name]); 
                            $gsm_range_count = isset($gsm_rangeData[0]->total_count) ? $gsm_rangeData[0]->total_count : 0;
                        @endphp
                        <li onclick="toggleCheckbox(this); LoadProduct();updateSelectedTags();"><input type="checkbox" class="sub-checkbox gsm" gsm="{{$row->filter_name}}" value="{{$row->filter_name}}" onclick="handleCheckboxClick(event, this);" ><span="text_span">{{$row->filter_name}} ({{$gsm_range_count}}) <span class="text_span"></span></li> 
                        @endforeach
                    </ul>
                </li>
                @if($type == 1)
                <li class="category-item">
                    Width Range
                    <ul class="sub-category">
                        @foreach($categoryList5 as $row)
                        @php
                            $width_rangeData = DB::select("SELECT count(*) as total_count FROM exhibition_product WHERE width_range = ?", [$row->filter_name]);
                            $width_range_count = isset($width_rangeData[0]->total_count) ? $width_rangeData[0]->total_count : 0;
                        @endphp
                        <li onclick="toggleCheckbox(this); LoadProduct();updateSelectedTags();"><input type="checkbox" class="sub-checkbox width" width="{{$row->filter_name}}" value="{{$row->filter_name}}" onclick="handleCheckboxClick(event, this);" >{{$row->filter_name}} ({{$width_range_count}})  <span class="text_span"></span></li> 
                        @endforeach
                    </ul>
                </li> 
                <li>
                    Price Range<hr/>
                    <div>
                        <input type="range" id="priceRange" min="0" max="500" step="5" value="500" style="flex-grow: 1;width: -webkit-fill-available;"><hr/>
                        <input type="number" id="minPrice" min="0" max="500" step="5" value="0">
                        <input type="number" id="maxPrice" min="0" max="500" step="5" value="500">
                    </div><br/>
                    <span id="priceValue">₹500</span>
               </li>
               @endif
            </ul>
            <div class="col-12 mt-5" style="display: flex; align-items: center; justify-content: center;">
                <a href="javascript:void(0);" class="btn btn-warning back-btn" onclick="DownloadExcel();">Download Excel</a> 
            </div>
        </div> 
        <div class="ba-we-love-subscribers-wrap">
            <div class="ba-we-love-subscribers popup-ani">
                <header>
                    <h1 style="text-decoration: underline;"><b>SEND INQUIRY</b></h1>
                </header>
                <form id="emailForm" method="post" enctype="multipart/form-data">
                    @csrf 
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input id="first_name" name="first_name" type="text" placeholder="Enter your first name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" name="last_name" type="text" placeholder="Enter your last name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" placeholder="Enter your email" oninput="validateEmail();" required>
                        <span class="error"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_no">Contact No.</label>
                        <input id="contact_no" name="contact_no" type="number" step="any" pattern="[0-9]{10}" placeholder="Enter your contact number" required>
                    </div>
                    
                    <div class="form-group" style="display: flex;">
                        <i class="fa fa-paperclip ml-2" aria-hidden="true"></i> <label for="attachment" style="width: 8vw;margin-left: 12px;"> Excel Attachment &nbsp; &nbsp; &nbsp;</label>
                        <input id="attachment" name="attachment" type="file" accept=".pdf,.doc,.docx,.jpg,.png" class="hide">
                        <img src="{{ URL::asset('assets/images/excellogo.png')}}" alt="Product" height="25" id="excellogo" class="hide">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" placeholder="Type your message here" rows="4" required></textarea>
                    </div>
                    <div class="form-group" style="justify-content: center;">
                        <input class="logo-ani" name="submit" type="submit" value="Send" id="submitButton" style="width: 10vw;border-radius:10px;" onclick="validateEmail();"> 
                    </div>
                </form>
            </div>
           <div class="ba-we-love-subscribers-fab"  onclick="emailProduct();">
                <div class="wrap">
                    <div class="img-fab img"></div>
                    <div class="product_count">0</div>
                </div>
            </div>
        </div>
        <!-- Right-side product section -->
        <div class="products-section" id="product_section"></div> 
        <!-- Modal -->
        <!--<div id="productModal" class="modal">-->
        <!--    <div class="modal-content">-->
        <!--        <span class="close">&times;</span>-->
        <!--        <img id="modalImage" src="" alt="Product Image" style="max-width: 100%; height: auto; margin-bottom: 20px;">-->
        <!--    </div>-->
        <!--</div>-->
    </div>
    <div class="overlay">
        <div class="zoom-container">
            <span class="close-btn">&times;</span>  <!-- Close Button on Image -->
            <img id="zoomedImage">
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<input type="hidden" id="type_id" value="{{$type}}">
<script>

    function zoomImage(row)
    { 
        var imgSrc = $(row).attr("src");
        $("#zoomedImage").attr("src", imgSrc);
        $(".overlay").addClass("active");
        $("#zoomedImage").addClass("zoomed"); // Add zoom effect
    }
    $(document).ready(function() 
    {
        $(".overlay, .close-btn").click(function() 
        {
                $(".overlay").removeClass("active");
        });
    });
    
    $(document).keydown(function(e) 
    {
        if (e.key === "Escape" || e.keyCode === 27) 
        {
            $(".overlay").removeClass("active");
        	$('.ba-we-love-subscribers-fab .wrap').toggleClass("ani");
        	$('.ba-we-love-subscribers').toggleClass("open");
        	$('.img-fab.img').toggleClass("close");
        } 
        
    });

    function checkedProduct(row)
    {
        if ($(row).is(':checked')) 
        {
            $(row).val(1);
        } 
        else 
        {
            $(row).val(0);
        }
        
        updateProductCount();
    }
    
    $(".ba-we-love-subscribers-fab").click(function() {
    	$('.ba-we-love-subscribers-fab .wrap').toggleClass("ani");
    	$('.ba-we-love-subscribers').toggleClass("open");
    	$('.img-fab.img').toggleClass("close");
    });

    function sentEmail()
    {
       $(".custom-model-main").addClass('model-open');
       emailProduct1();
    }

    function FormAdd()
    {
          var from = $("#from").val(); // Get the value from the #from input 
          // Get the current value of #send_to, append the new value, and set it back
          var sendToCurrentValue = 'bhikajikamble143@gmail.com'; 
          $("#send_to").val(sendToCurrentValue + (sendToCurrentValue ? "," : "") + from);
    }
    
    function updateProductCount() 
    { 
        var count = $(".selected_product:checked").length;  
        $(".product_count").text(count);
    }
    
     // Update count on page load
     updateProductCount();
      
    function validateEmail()
    {
        var email = $("#email").val();
        var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // General email format regex
        var allowedTLDs = ["com", "net", "org", "in", "co"];  // Allowed TLDs
        var errorMessage = $(".error");

        // Check if the email matches the general format
        if (regex.test(email) == false) {
            errorMessage.text("Invalid email format.");
            return false;
        }

        // Split the email into local part and domain part
        var domain = email.split('@')[1];

        // Extract the TLD (everything after the last dot)
        var tld = domain.split('.').pop().toLowerCase();

        // Check if the TLD is in the allowed list
        if (!allowedTLDs.includes(tld)) {
            errorMessage.text("Email must end with .com, .net, .org, .in, or .co.");
            return false;
        }

        // Clear the error message if the email is valid
        errorMessage.text("");
        return true;
    } 

 
    $(document).ready(function() 
    { 
          $('#emailForm').on('submit', function(event) 
          {  
              event.preventDefault();
              var submitButton = $('#submitButton'); // Adjust selector as per your button's ID
              $('#submitButton').attr('readonly', true).val('Please wait...');  
              $('#submitButton').attr('type', 'button');  
              // Create FormData object
              var formData = new FormData($(this)[0]);
              
              // Append file to FormData object
              var fileInput = $('#attachment')[0].files[0]; 
              formData.append('file', fileInput);
       
              $.ajax({
                  url: '/SendEmailToClientExhibition',
                  type: 'POST', 
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(data) {
                      $(".custom-model-main").removeClass('model-open');
                      swal(
                          'Success',
                          'Emails have been successfully sent...!',
                          'success'
                      );
                     $('.img-fab.img').trigger("click");
                     $('#submitButton').attr('type', 'submit');  
                     $("#first_name").val("");
                     $("#last_name").val("");
                     $("#email").val("");
                     $("#contact_no").val("");
                     $("#message").text("");
                  },
                  error: function(error) {
                      $(".custom-model-main").removeClass('model-open');
                      console.error('Error:', error);
                  }
              }); 
          });
          
    });
          
     function emailProduct()
     {
        var exportArr = [];
        $(".product-card").each(function () 
        {
            var checked = $(this).find('.selected_product').val();
            
            if (parseInt(checked) === 1) 
            {
                exportArr.push({
                    "Sort No.": $(this).attr('data-sort_no'),
                    "Quality": $(this).attr('data-quality'),
                    "Content": $(this).attr('data-content'),
                    "Width": $(this).attr('data-width'),
                    "Weave": $(this).attr('data-weave'),
                    "GSM": $(this).attr('data-gsm'),
                    "Quantity In Mtrs": $(this).attr('data-quantity'),
                    "End Use": $(this).attr('data-end_use'),
                    "Image": $(this).attr('data-image')
                });
            }
        });
    
        if (exportArr.length === 0) {
            alert("No products selected!");
            return;
        }
    
        try {
            // Convert the data into an Excel sheet
            var ws = XLSX.utils.json_to_sheet(exportArr);
    
            // Create a workbook
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Products");
    
            // Generate the workbook as a Blob
            var workbookBlob = XLSX.write(wb, { bookType: "xlsx", type: "array" });
    
            // Convert Blob to File and attach it to a file input
            var excelFile = new File([workbookBlob], "Selected_Products.xlsx", { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            $("#excellogo").removeClass("hide");
            // Find the file input element and set the file
            var fileInput = document.getElementById('attachment');  
            var dataTransfer = new DataTransfer();  
            dataTransfer.items.add(excelFile);
            fileInput.files = dataTransfer.files;
    
            // alert("Excel file created and attached!");
        } catch (error) {
            console.error("Error exporting products:", error);
        }
    }
    
    function DownloadExcel() 
    {
        console.log("hii");
        var exportArr = [];
        $(".product-card").each(function () 
        {
            exportArr.push({
                "Sort No.": $(this).attr('data-sort_no'),
                "Quality": $(this).attr('data-quality'),
                "Content": $(this).attr('data-content'),
                "Width": $(this).attr('data-width'),
                "Weave": $(this).attr('data-weave'),
                "GSM": $(this).attr('data-gsm'),
                "Quantity In Mtrs": $(this).attr('data-quantity'),
                "End Use": $(this).attr('data-end_use'),
                "Image": $(this).attr('data-image')
            });
        });
        
        // Convert JSON to worksheet
        var ws = XLSX.utils.json_to_sheet(exportArr);
    
        // Create a new workbook and append the worksheet
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Products");
    
        // Write workbook as an array (Uint8Array)
        var workbookBlob = XLSX.write(wb, { bookType: "xlsx", type: "array" });
    
        // Create a Blob from the workbook
        var blob = new Blob([workbookBlob], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        });
    
        // Create a temporary anchor element for download
        var url = URL.createObjectURL(blob);
        var a = document.createElement("a");
        a.href = url;
        a.download = "Selected_Products.xlsx";
        document.body.appendChild(a);
        a.click();
    
        // Clean up
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    
    function SetCount()
    {
        // let availableWeaves = new Set();
        // let availableGSM = new Set();
        // let availableContent = new Set();
        // let availableWidth = new Set();
    
        // $(".product-card").not('.hide').each(function () {
        //     let WeaveArr = $(this).attr('weave_ids')?.split(',') || [];
        //     WeaveArr.forEach(weave => availableWeaves.add(weave));
            
        //     let GSMArr = $(this).attr('gsm_ids')?.split(',') || [];
        //     GSMArr.forEach(gsm => availableGSM.add(gsm));
            
        //     let ContentArr = $(this).attr('content_ids')?.split(',') || [];
        //     ContentArr.forEach(content => availableContent.add(content));
            
        //     let WidthArr = $(this).attr('width_ids')?.split(',') || [];
        //     WidthArr.forEach(width => availableWidth.add(width));
        // });
    
        // function updateCheckboxes(selector, availableSet, disabledClass) {
        //     $(selector).each(function () {
        //         let itemId = $(this).attr(selector.replace('.', ''));
        //         if (!availableSet.has(itemId)) {
        //             $(this).prop('checked', false).prop('disabled', true);
        //             $(this).closest('div').addClass(disabledClass);
        //         } else {
        //             $(this).prop('disabled', false);
        //             $(this).closest('div').removeClass(disabledClass);
        //         }
        //     });
    
        //     $(document).off("click", `.${disabledClass}`).on("click", `.${disabledClass}`, function (e) {
        //         e.preventDefault();
        //     });
        // }
    
        // updateCheckboxes(".weave", availableWeaves, "disabled-weave");
        // updateCheckboxes(".gsm", availableGSM, "disabled-gsm");
        // updateCheckboxes(".content", availableContent, "disabled-content");
        // updateCheckboxes(".width", availableWidth, "disabled-width");
    }

    function toggleCheckbox(liElement) {
        let checkbox = liElement.querySelector("input[type='checkbox']");
        checkbox.checked = !checkbox.checked; // Toggle the checkbox state
        
        SetCount();
    }
    
    function handleCheckboxClick(event, checkbox) {
        event.stopPropagation(); // Prevent `li` from being clicked when clicking checkbox
        LoadProduct();updateSelectedTags();
    }

   

     function getSelectedValues(className) 
     {
        var selectedValues = [];
        $("." + className + ":checked").each(function() {
            var value = $(this).attr(className);
            if (!selectedValues.includes(value)) {
                selectedValues.push(value);
            }
        });
        return selectedValues.join(',');
    }
    
    function LoadProduct()
    { 
        // Get selected values for each filter
        var end_use_filter = getSelectedValues('end_use');
        var weave_filter = getSelectedValues('weave');
        var content_filter = getSelectedValues('content');
        var gsm_filter = getSelectedValues('gsm');
        var width_filter = getSelectedValues('width');
        var type = $("#type_id").val();
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('LoadExhibitionProducts') }}",
          data:{'type':type, 'end_use_filter':end_use_filter, 'weave_filter':weave_filter,'content_filter':content_filter,'gsm_filter':gsm_filter,'width_filter':width_filter},
          success: function(data)
          { 
             $("#product_section").html(data.html); 
             var count1 = $(".selected_product").length;  
             $(".product_count1").text("Total Products ("+count1+")");
          },
          complete: function(data)
          {
            // SetCount();
          }
        });
    }
    
    function updateSelectedTags()
    {
        const $selectedTagsContainer = $('#selectedTagsContainer'); // Get the container
        $selectedTagsContainer.empty(); // Clear current tags
    
        // Get all checked checkboxes
        $('.end_use:checked').each(function () {
            
            const $checkbox = $(this);
            const tagValue = $checkbox.val();
    
            // Create a tag div
            const $tag = $('<div></div>').addClass('tag').text(tagValue);
            // Create a remove button for the tag
            const $removeButton = $('<span></span>')
                .addClass('remove-tag')
                .text('×')
                .on('click', function () {
                    $checkbox.prop('checked', false); // Uncheck the checkbox
                    updateSelectedTags(); // Refresh the tags
                });
    
            // Append the remove button to the tag
            $tag.append($removeButton);
    
            // Append the tag to the container
            $selectedTagsContainer.append($tag);
        });
        $('.weave:checked').each(function () {
            
            const $checkbox = $(this);
            const tagValue = $checkbox.val();
    
            // Create a tag div
            const $tag = $('<div></div>').addClass('tag').text(tagValue);
            // Create a remove button for the tag
            const $removeButton = $('<span></span>')
                .addClass('remove-tag')
                .text('×')
                .on('click', function () {
                    $checkbox.prop('checked', false); // Uncheck the checkbox
                    updateSelectedTags(); // Refresh the tags
                });
    
            // Append the remove button to the tag
            $tag.append($removeButton);
    
            // Append the tag to the container
            $selectedTagsContainer.append($tag);
        });
        
        $('.content:checked').each(function () {
            
            const $checkbox = $(this);
            const tagValue = $checkbox.val();
    
            // Create a tag div
            const $tag = $('<div></div>').addClass('tag').text(tagValue);
            // Create a remove button for the tag
            const $removeButton = $('<span></span>')
                .addClass('remove-tag')
                .text('×')
                .on('click', function () {
                    $checkbox.prop('checked', false); // Uncheck the checkbox
                    updateSelectedTags(); // Refresh the tags
                });
    
            // Append the remove button to the tag
            $tag.append($removeButton);
    
            // Append the tag to the container
            $selectedTagsContainer.append($tag);
        });
        $('.gsm:checked').each(function () {
            
            const $checkbox = $(this);
            const tagValue = $checkbox.val();
    
            // Create a tag div
            const $tag = $('<div></div>').addClass('tag').text(tagValue);
            // Create a remove button for the tag
            const $removeButton = $('<span></span>')
                .addClass('remove-tag')
                .text('×')
                .on('click', function () {
                    $checkbox.prop('checked', false); // Uncheck the checkbox
                    updateSelectedTags(); // Refresh the tags
                });
    
            // Append the remove button to the tag
            $tag.append($removeButton);
    
            // Append the tag to the container
            $selectedTagsContainer.append($tag);
        });
        $('.width:checked').each(function () {
            
            const $checkbox = $(this);
            const tagValue = $checkbox.val();
    
            // Create a tag div
            const $tag = $('<div></div>').addClass('tag').text(tagValue);
            // Create a remove button for the tag
            const $removeButton = $('<span></span>')
                .addClass('remove-tag')
                .text('×')
                .on('click', function () {
                    $checkbox.prop('checked', false); // Uncheck the checkbox
                    updateSelectedTags(); // Refresh the tags
                });
    
            // Append the remove button to the tag
            $tag.append($removeButton);
    
            // Append the tag to the container
            $selectedTagsContainer.append($tag);
        });
        LoadProduct();
    }
    

    $(document).ready(function() 
    {
          // Update the slider and price value when minPrice or maxPrice is manually updated
        $('#minPrice, #maxPrice').on('input', function() {
            var minPrice = parseFloat($('#minPrice').val());
            var maxPrice = parseFloat($('#maxPrice').val());
        
            // Ensure minPrice is not greater than maxPrice
            if (minPrice > maxPrice) {
                maxPrice = minPrice;
                $('#maxPrice').val(maxPrice);
            }
        
            // Update the range attributes and slider value
            $('#priceRange').attr('min', minPrice).attr('max', maxPrice);
            $('#priceRange').val(maxPrice);
            $('#priceValue').text('₹' + maxPrice);
        
            
            // Filter product cards based on price range
            $(".product-card").each(function() {
                var rate = parseFloat($(this).atrr('rate')); // Get the rate from the product data attribute
                console.log(rate);
                // Show the product if the rate is between minPrice and maxPrice
                if (rate >= minPrice && rate <= maxPrice) {
                   // SetCount();
                      $(this).removeClass("hide");
                } else {
                    //SetCount(); 
                    $(this).addClass("hide");
                }
            });
        });
        
        // Update the priceValue and maxPrice when the slider is moved
        $('#priceRange').on('input', function() {
            var sliderValue = parseFloat($(this).val());
            $('#priceValue').text('₹' + sliderValue);
            $('#maxPrice').val(sliderValue);
        
            var minPrice = parseFloat($('#minPrice').val());
            var maxPrice = parseFloat($('#maxPrice').val());
        
            // Filter product cards based on price range
            $(".product-card").each(function() {
                var rate = parseFloat($(this).data('rate')); // Get the rate from the product data attribute
        
                // Show the product if the rate is between minPrice and maxPrice
                if (rate >= minPrice && rate <= maxPrice) {
                    $(this).removeClass("hide");
                    //SetCount();
                } else {
                    $(this).addClass("hide");
                    //SetCount();
                }
            });  
            
            var count1 = $(".product-card").not(".hide").length;  
            $(".product_count1").text("Total Products ("+count1+")");
        });

        
        LoadProduct();
         $('#productModal').fadeOut();
        // When a product card is clicked
        $('.product-card').click(function() 
        {
            var sortNo = $(this).data('sort_no');
            var quality = $(this).data('quality');
            var content = $(this).data('content');
            var width = $(this).data('width');
            var weave = $(this).data('weave');
            var gsm = $(this).data('gsm');
            var rate = $(this).data('rate');
            var quantity = $(this).data('quantity');
            var end_use = $(this).data('end_use');
            var image = $(this).data('image'); // Get the product image URL
    
            // Set the modal content
            $('#modalSortNo').text("Product Sort No: " + sortNo);
            $('#modalQuality').text("Quality: " + quality);
            $('#modalContent').text("Content: " + content);
            $('#width').text("Width: " + width);
            $('#weave').text("Weave: " + weave);
            $('#gsm').text("GSM: " + gsm);
            $('#rate').text("Rate: " + rate);
            $('#quantity').text("Quantity: " + quantity);
            $('#end_use').text("End Use: " + end_use);
            $('#modalImage').attr('src', image); // Set the image source 
            // Show the modal
            $('#productModal').fadeIn(); // Ensure modal is shown only when a card is clicked
        });
    
        // Close the modal when the close button is clicked
        $('.close').click(function() {
            $('#productModal').fadeOut(); // Hide the modal on close
        });
    
        // Close the modal when clicking outside the modal content
        $(window).click(function(event) {
            if ($(event.target).is('#productModal')) {
                $('#productModal').fadeOut(); // Hide the modal if clicking outside
            }
        }); 
              
        // Toggle the visibility of sub-categories on click
        $('.category-item').click(function (event) {
            event.stopPropagation(); // Prevent event bubbling to the document
    
            var subCategory = $(this).find('.sub-category');
    
            // Slide toggle the sub-category with fade effect
            subCategory.stop(true, true).fadeToggle(300).toggleClass('show');
    
            // Close other open sub-categories
            $(this).siblings().find('.sub-category').fadeOut(300).removeClass('show');
        });
    
        // Prevent clicks inside the sub-category from closing it
        $('.sub-category').click(function (event) {
            event.stopPropagation(); // Stop click propagation
        });
    
        // Optionally, allow clicking outside to close all subcategories
        $(document).click(function () {
            $('.sub-category').fadeOut(300).removeClass('show');
        }); 
     

    });


    document.querySelectorAll(".carousel").forEach((carousel, index) => {
        const rows = carousel.querySelectorAll(".carousel-row");
        let currentSlide = 2;

        const nextSlide = () => {
            rows[currentSlide].classList.remove("active");
            currentSlide = (currentSlide + 1) % rows.length;
            rows[currentSlide].classList.add("active");
        };

        const prevSlide = () => {
            rows[currentSlide].classList.remove("active");
            currentSlide = (currentSlide - 1 + rows.length) % rows.length;
            rows[currentSlide].classList.add("active");
        };

        carousel.insertAdjacentHTML(
            "beforeend",
            `<div class="carousel-buttons hide">
                <button onclick="prevSlide()">Previous</button>
                <button onclick="nextSlide()">Next</button>
            </div>`
        );
    });
</script>
</html>
