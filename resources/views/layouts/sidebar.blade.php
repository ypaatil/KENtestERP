<link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<style>
    .hide {
        display: none!important;
    }

    .overlay {
        position: fixed; /* Use fixed positioning to ensure it covers the viewport */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
        z-index: 10; /* Ensure it is above the page content */
        pointer-events: none; /* Allow clicks to pass through the overlay */
    }

    .vertical-menu, .vertical-menu1 {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .vertical-menu li, .vertical-menu1 li {
        display: inline-block; /* Align items horizontally for larger screens */
        background-color: #272828; /* Background for inactive items */
        color: #fff; /* Text color for inactive items */
        padding: 10px 20px; /* Adjusted padding */
        font-size: 14px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        margin-right: 15px; /* Space between list items */
        list-style: none;
    }

    .vertical-menu li:hover, .vertical-menu1 li:hover {
        background-color: #3D3D3D;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .vertical-menu li.active, .vertical-menu1 li.active {
        background-color: rgba(61, 61, 61, 0.9); /* Background with opacity for active state */
        color: #fff;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border-left: 4px solid #fff; /* Border color matching text */
    }

    .vertical-menu li.active:hover, .vertical-menu1 li.active:hover {
        background-color: rgba(61, 61, 61, 0.9); /* Keep the background color consistent on hover */
    }

    .vertical-menu li a span, .vertical-menu1 li a span {
        font-size: 14px; /* Adjust the size as needed */
        font-weight: bold; /* Optional: Make the text bold */
    }

    .vertical-menu1 {
        left: 230px;
        width: 20%;
        background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent background */
        padding-top: 5%; /* Ensure top padding to align items properly */
        position: fixed; /* Use fixed positioning for the sidebar */
        z-index: 10;
        overflow-y: auto; /* Enable vertical scrolling */
        max-height: 100vh; /* Adjust as needed to fit your layout */
    }
    .vertical-menu1 a
    {
        color:#fff;
    }
    
    .no-scroll {
        overflow: hidden;
    }

  
    /* For screens smaller than 1200px */
    @media (max-width: 1200px) {
        .vertical-menu1 {
            margin-left: 15%;
            overflow-y: auto; /* Enable vertical scrolling */
            max-height: 55%; /* Adjust as needed to fit your layout */
        }
    }
    
    /* For screens smaller than 992px */
    @media (max-width: 992px) {
        .vertical-menu1 {
            margin-left: 10%;
            overflow-y: auto; /* Enable vertical scrolling */
            max-height: 55%; /* Adjust as needed to fit your layout */
        }
    }
    
    /* For screens smaller than 768px */
    @media (max-width: 768px) {
        .vertical-menu1 {
            margin-left: 5%;
            overflow-y: auto; /* Enable vertical scrolling */
            max-height: 55%; /* Adjust as needed to fit your layout */
        }
    }
    
    /* For screens smaller than 576px */
    @media (max-width: 576px) {
        .vertical-menu1 {
            margin-left: 0%;
            width: 100%; /* Full width on very small screens */
            overflow-y: auto; /* Enable vertical scrolling */
            max-height: 55%; /* Adjust as needed to fit your layout */
        }
    }
</style>

   <div class="" id="menu_overly"></div>
<div class="vertical-menu">
     <div data-simplebar class="h-100">
    <div id="sidebar-menu">
    <!-- Left Menu Start -->
{{ getSideBar1(); }}
    </div>
    </div>
    </div>
    
    <div class="vertical-menu1 hide">
     <div data-simplebar class="h-100">
    <div id="sidebar-menu1">
    <!-- Left Menu Start -->
{{ getSideBar(); }}
    </div>
    </div>
    </div>
    <script>


    function GetSubSideMenu(menu_id) 
    {
        $('.vertical-menu1').removeClass("hide");
        $('.suhead').addClass('hide');
        $('.head' + menu_id).removeClass("hide");
    
        // Show the overlay and prevent scrolling
        $('#menu_overly').addClass('overlay');
        $('body').addClass('no-scroll');
    
        // Check if the parent 'li' element has the 'mm-active' class
        if ($('.head' + menu_id).parent('li').hasClass('mm-active')) {
            HideOverlay();
        }
    }
    
    // Function to hide the overlay and allow scrolling
    function HideOverlay() {
        $('#menu_overly').removeClass('overlay');
        $('body').removeClass('no-scroll');
        $('.vertical-menu1').addClass("hide");
    }
    
  


    </script>