    <!doctype html>
    <html lang="en">

    <head>

    <meta charset="utf-8" />
    <title>Stitching Garment Inventory Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Stitching Garment Inventory Application" name="description" />
   
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    @include('layouts.head-css')
    </head>

    <body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
    <!-- ========== Left Sidebar Start ========== -->
  @include('layouts.top-bar')
  @include('layouts.sidebar')
    <!--- Sidemenu -->
    <!-- Sidebar -->
    
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

    <div class="page-content">
    <div class="container-fluid">
    <!-- start page title -->
@yield('content')
    <!-- end row -->
    </div>
    <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
  @include('layouts.footer')
    </div>
    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
     @include('layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
<!-- JAVASCRIPT -->

<!-- JAVASCRIPT -->
@include('layouts.vendor-scripts')


    </body>

    </html>