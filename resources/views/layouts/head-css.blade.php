@yield('css')

<!-- Bootstrap Css -->
<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
  
<style>
   .panel-heading.active .panel-title a:after {
      transform: rotate(180deg);
    }
    .panel-heading {
        background: #f5f5f5 !important;
        padding: 10px 15px !important;
        border: 1px solid #ddd !important;
    }
    
    .panel-title a {
        display: block !important;
        color: #333 !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }
    
    .panel-title a.collapsed {
        color: #333 !important;
    }
</style>