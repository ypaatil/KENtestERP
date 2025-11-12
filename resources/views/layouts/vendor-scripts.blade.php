<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/metismenu/metismenu.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/node-waves/waves.min.js')}}"></script>

 <link href="https://cdn.jsdelivr.net/select2/3.5.2/select2.css" rel="stylesheet" />
 <link href="{{ URL::asset('assets/css/filtersearch.css') }}" rel="stylesheet" />
 <script src="https://cdn.jsdelivr.net/select2/3.5.2/select2.min.js"></script>

 <!-- Required datatable js -->
 <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Buttons examples -->
        <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        
        <!-- Responsive examples -->
        <script src="{{ URL::asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <!-- Datatable init js -->
        <script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script>    

   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        <script>
            $('.select2').select2();
            
            
            
            $(document).keypress(function(event) {
    if (event.which == '13') {
        
        event.preventDefault();
    }
});
        </script>
     
<!-- Reports js added 11-11-2025  -->
      <script src="{{ URL::asset('assets/js/dtexport.js') }}"></script>
        
       


<!-- App js -->
<script src="{{ URL::asset('assets/js/app.js') }}"></script>