@extends('layouts.master') 
@section('content')
@php setlocale(LC_MONETARY, 'en_IN'); @endphp

<!-- ====== CSS Libraries ====== -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> <!-- Font Awesome for icons -->
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: "Poppins", sans-serif;
        background-color: #f4f6f9;
    }

    /* ===== LEFT SIDEBAR ===== */
    .left-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 200px; /* adjust sidebar width */
        height: 100%;
        background-color: #2f3542;
        color: #fff;
        padding: 20px;
        box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        z-index: 20; /* higher than tab wrapper */
    }

    /* ===== DASHBOARD CONTAINER ===== */
    .dashboard-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
        /*margin-left: 200px;  */
    }

    /* ===== TAB NAVIGATION BAR ===== */
    .tab-sidebar-wrapper {
        position: relative;
        z-index: 5;
    }

    .tab-sidebar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        background: #f1f2f6;
        border-bottom: 3px solid #000;
        padding: 15px 10px;
        overflow-x: auto;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-left: 0; /* tabs already shifted by container */
    }

    .tab-sidebar::-webkit-scrollbar {
        height: 6px;
    }
    .tab-sidebar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    /* ===== TAB BUTTON ===== */
    .tab-button {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border: 1px solid #ddd;
        border-bottom: none;
        color: #333;
        padding: 10px 20px;
        margin: 0 6px;
        border-radius: 12px 12px 0 0;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.05);
    }

    .tab-button i, 
    .tab-button img {
        margin-right: 8px;
        font-size: 15px;
    }

    .tab-button:hover {
        background: #f9f9f9;
    }

    /* ===== ACTIVE TAB ===== */
    .tab-button.active {
        background: #000;
        color: #fff;
        border-color: #000;
        z-index: 2;
        box-shadow: 0 -2px 8px rgba(0,0,0,0.25);
    }

    .tab-button.active i {
        color: #fff;
    }

    /* ===== CONTENT AREA ===== */
    .tab-content-area {
        flex: 1;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.6s ease-out;
        margin-left: 0; /* adjusted by container */
    }

    .tab-content {
        display: none;
        width: 100%;
        height: 100%;
    }
    .tab-content.active {
        display: block;
    }

    .iframe-container {
        width: 100%;
        height: 100%;
    }
    .iframe-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* ===== ANIMATION ===== */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== TAB SCROLL BUTTONS ===== */
    .scroll-btn {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 50px; /* slightly wider for better visibility */
        background: linear-gradient(
            to right, 
            rgba(66, 96, 217, 0.9), 
            rgba(66, 96, 217, 0.6), 
            rgba(66, 96, 217, 0.9)
        );
        border: none;
        outline: none;
        cursor: pointer;
        font-size: 24px;
        color: #fff; /* white text for contrast */
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px; /* rounded edges */
        box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* subtle shadow */
        transition: all 0.3s ease;
    }
    
    .scroll-btn:hover {
        background: rgba(66, 96, 217, 1); /* solid on hover */
        transform: scale(1.1); /* slight zoom effect */
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }
    
    .scroll-btn.right {
        right: 0;
    }
    .scroll-btn:disabled {
        opacity: 0.3;
        cursor: default;
    }
    
   
    
</style>

<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png"> 
@if(isset($chekform) && $chekform->write_access == 1)
<div class="dashboard-container">

    <!-- TAB NAVIGATION -->
    <div class="tab-sidebar-wrapper">
        <button class="scroll-btn left" id="scrollLeft">&#8249;</button>
        <div class="tab-sidebar" id="tabScroll">
            <div class="tab-button active" data-tab="tab1">
                <i class="fa-solid fa-chart-line"></i> MTD Booking Status
            </div>
            <div class="tab-button" data-tab="tab2">
                <i class="fa-solid fa-coins"></i> Sales (FOB + JOB Work)
            </div>
            <div class="tab-button" data-tab="tab3">
                <i class="fa-solid fa-industry"></i> WIP - Vendor With Buyer Brand
            </div>
            <div class="tab-button" data-tab="tab4">
                <i class="fa-solid fa-people-arrows"></i> WIP - Buyer Brand With Vendor
            </div>
        </div>

        <button class="scroll-btn right" id="scrollRight">&#8250;</button>
    </div>


    <!-- TAB CONTENT -->
    <div class="tab-content-area">
        <div id="tab1" class="tab-content active">
            <div class="iframe-container">
                    <iframe id="powerBiIframe" src="https://app.powerbi.com/view?r=eyJrIjoiOGYzYzhlODctMmFmOS00MTk3LTg2YWQtYTczZmY5NDBmZGNmIiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>  
            </div>
        </div>
        <div id="tab2" class="tab-content">
            <div class="iframe-container">
                 <iframe id="powerBiIframe" src="https://app.powerbi.com/view?r=eyJrIjoiMTRhYTJmNTYtOWM5ZS00Y2RkLTkzMDAtN2VlOWY2ZGFiYzM4IiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div> 
        <div id="tab3" class="tab-content">
            <div class="iframe-container">
                 <iframe src="https://app.powerbi.com/view?r=eyJrIjoiZDVlZjAwNjAtYzkyYy00N2IwLTkwMzUtMGU4NWE0MjU3ZjQ0IiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div>
        <div id="tab4" class="tab-content">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiYTg0ZWM1MzMtNzQ1Yy00YjA5LTkxZmYtYWU5YmZhOTIxODY3IiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script> 

/* ===== FIX TAB SCROLL USING jQUERY ===== */
    $(document).ready(function() {
        var $tabScroll = $('#tabScroll');
        var $scrollLeftBtn = $('#scrollLeft');
        var $scrollRightBtn = $('#scrollRight');
    
        function updateScrollButtons() {
            var scrollLeft = $tabScroll.scrollLeft();
            var maxScroll = $tabScroll[0].scrollWidth - $tabScroll.outerWidth();
            $scrollLeftBtn.prop('disabled', scrollLeft <= 0);
            $scrollRightBtn.prop('disabled', scrollLeft >= maxScroll - 1);
        }
    
        // Smooth scroll with animation
        $scrollLeftBtn.on('click', function() {
            $tabScroll.animate({ scrollLeft: $tabScroll.scrollLeft() - 150 }, 300, updateScrollButtons);
        });
    
        $scrollRightBtn.on('click', function() {
            $tabScroll.animate({ scrollLeft: $tabScroll.scrollLeft() + 150 }, 300, updateScrollButtons);
        });
    
        $tabScroll.on('scroll', updateScrollButtons);
        $(window).on('resize', updateScrollButtons);
    
        // Initialize once on load
        updateScrollButtons(); 
        
        /* 🔹 TAB SWITCHING */
        $('.tab-button').click(function() {
            var tab_id = $(this).data('tab');
            $('.tab-button').removeClass('active');
            $(this).addClass('active');
            $('.tab-content').removeClass('active');
            $('#' + tab_id).addClass('active');
        });
    
        /* 🔹 MOBILE REDIRECT */
        if (window.innerWidth <= 600) {
            window.location.href = "https://kenerp.com/BuyerCosting";
        }
         
    });
  
   
</script>
@else
<div class="row center-image">
  <img src="{{ URL::asset('logo/ken.jpeg')}}" alt="Ken Global Designs Pvt. Ltd." style="width: 1056px; height: 520px;">
</div>
@endif
@endsection
