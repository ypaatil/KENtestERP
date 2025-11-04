@extends('layouts.master') 
@section('content')   
<style>
    th,td{
        text-align:center;
    }
</style>
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Stock Assocition</h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Stock Assocition</li>
            </ol>
        </div>
    </div>
</div>
</div>       
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                   <div class="row justify-content-center">
                      <div class="col-md-2">
                         <div class="mb-3">
                            <label for="bom_code" class="form-label">BOM Code</label>
                            <select name="bom_code" class="form-control select2" id="bom_code" >
                               <option value="All">--All--</option>
                               @foreach($BomData as $bom)
                                 <option value="{{$bom->bom_code}}">{{$bom->bom_code }}({{$bom->sales_order_no }})</option>
                               @endforeach
                            </select>
                         </div>
                      </div>
                      <div class="col-md-2">
                         <div class="mb-3">
                            <label for="cat_id" class="form-label">Category</label>
                            <select name="cat_id" class="form-control select2" id="cat_id" onchange="GetClassifictionData()" >
                               <option value="All">--All--</option>
                               @foreach($Categorylist as $categ)
                                 <option value="{{$categ->cat_id}}">{{$categ->cat_name }}</option>
                               @endforeach
                            </select>
                         </div>
                      </div>
                      <div class="col-md-2">
                         <div class="mb-3">
                            <label for="line_id" class="form-label">Classification</label>
                            <select name="class_id" class="form-control select2" id="class_id" required onchange="GetItemData()" >
                                  <option value="All">--All--</option>
                              
                            </select>
                         </div>
                      </div>
                      <div class="col-md-2">
                         <div class="mb-3">
                            <label for="item_code" class="form-label">Item</label>
                            <select name="item_code" class="form-control select2" id="item_code" required>
                                 <option value="All">--All--</option>
                              
                            </select>
                         </div>
                      </div>
                      <div class="col-sm-2">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="button" onclick="loadData();" class="btn btn-primary w-md">Search</button>
                          </div>
                      </div>
                   </div>
             </div>
            <table id="tbl" class="table table-responsive w-100 ">
                  <thead>
                    <tr style="text-align:center;">
                          <th> SrNo</th>
                          <th> PO Number</th>
                          <th> Item Code</th>
                          <th> Item Name</th>
                          <th> Description</th>
                          <th> Avaliable Stock</th>
                          <th> Action</th>
                     </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{route('StockAssociationForFabric.store')}}" method="POST">
        @csrf
        <div class="modal-content" style="width: 990px;">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Stock Association</h4>
            <button type="button" class="btn btn-danger close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered dt-responsive nowrap w-100 ">
              <thead>
                <tr style="text-align:center;">
                  <th>BOM</th>
                  <th>Sales Order No</th>
                  <th>Item Code</th>
                  <th>Item Name</th>
                  <th>Allocated Stock</th>
                  <th>Associated Stock</th>
                  <th>Issue Stock</th>
                  <th>Avaliable Stock</th>
                  <th>Issue Qty</th>
                </tr>
                </thead>
                <tbody id="popup_data"></tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://unpkg.com/popper.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
<script type="text/javascript">

  function allocatedStock(row)
  {
       var po_code = $(row).attr('po_code');
       var item_code = $(row).attr('item_code');
       var tr_code = $(row).attr('tr_code');
       var tr_date = $(row).attr('tr_date');
       var bom_code = $(row).attr('bom_code');
       
        $.ajax({
            dataType: "json",
            url: "{{ route('GetAllocatedFabricStockData') }}",
            data:{'po_code':po_code,'item_code':item_code,'bom_code':bom_code,'tr_code':tr_code,'tr_date':tr_date},
            success: function(data){
               console.log(data);
            $('#popup_data').html(data.html);
           }
        });
  }
  $(function () {

  	 $('#tbl').DataTable().clear().destroy();
       var no=0;
       var table = $('#tbl').DataTable({
    
        ajax: {
           url:"{{ route('StockAssociationForFabric.index') }}",
     	   data:  {'bom_code': ""},
     	   dataSrc: 'data'
        },
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
         columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          {data: 'po_code', name: 'po_code'},
          {data: 'item_code', name: 'item_master.item_code'},
          {data: 'item_name', name: 'item_master.item_name'},
          {data: 'item_description', name: 'item_master.item_description'},
          {data: 'avaliable_Stock', name: 'avaliable_Stock'},
          {data: 'action', name: 'action'},
         ]
    });
    
  });

    function loadData()
    {
       var bom_code = $("#bom_code").val();
       var class_id = $("#class_id").val();
       var item_code = $("#item_code").val();

       var no=0;
       var item_code = $("#item_code").val();
       
       $('#tbl').DataTable().clear().destroy();
       var table = $('#tbl').DataTable({
    
        ajax: {
           url:"{{ route('StockAssociationForFabric.index') }}",
           data:  {'bom_code':bom_code,'class_id':class_id,'item_code':item_code},
     	   dataSrc: 'data'
        },
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
         columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          {data: 'po_code', name: 'po_code'},
          {data: 'item_code', name: 'item_master.item_code'},
          {data: 'item_name', name: 'item_master.item_name'},
          {data: 'item_description', name: 'item_master.item_description'},
          {data: 'avaliable_Stock', name: 'avaliable_Stock'},
          {data: 'action', name: 'action'},
         ]
        });
    }
    // GetClassifictionData();
    // GetItemData();
    
    function GetClassifictionData()
    {
         var cat_id =  $('#cat_id').val();
         var class_id =  $('#class_id').val();
         
            $.ajax({
                dataType: "json",
                url: "{{ route('GetClassifictionData') }}",
                data:{'cat_id':cat_id},
                success: function(data){
                $('#class_id').html(data.html);
               }
            });
    }
   
    function GetItemData()
    {
        var cat_id =  $('#cat_id').val();
        var class_id =  $('#class_id').val();
        var bom_code =  $('#bom_code').val();
        var item_code =  $('#item_code').val();
        
        $.ajax({
            dataType: "json",
            url: "{{ route('GetItemFabricDataFromDetail') }}",
            data:{'cat_id':cat_id,'class_id':class_id,'bom_code':bom_code,'item_code':item_code},
            success: function(data)
            {
                 $("#item_code").html(data.html);
            }
        });
    }
    
</script> 
@endsection