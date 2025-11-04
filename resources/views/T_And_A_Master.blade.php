@extends('layouts.master') 

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title mb-4">T and A Master</h4>

                @if ($errors->any())

                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <form action="{{route('T_And_A_Master.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
                    @csrf 
                    <div class="row">
                          
                        <div class="col-md-3">
                            <div class="mb-4">
                                <label for="formrow-inputState" class="form-label">Sales Order No.</label>
                                <select name="tr_code" class="form-select select2" id="tr_code" onchange="getDetails(this.value);">
                                    <option value="">--Select Sales Order No--</option>
                                    @foreach($SalesOrderList as  $row)
                                    {
                                        <option value="{{ $row->tr_code }}">{{ $row->tr_code }}</option>
                                    }
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="dterm_id" class="form-label">Delivery Terms</label>
                                <select name="dterm_id" class="form-select" id="dterm_id" required>
                                    <option value="">--Delivery Terms--</option>
                                        @foreach($DeliveryTermsList as  $row)
                                        {
                                            <option value="{{ $row->dterm_id }}">{{ $row->delivery_term_name }}</option>
                                        }
                                    @endforeach
                                </select>
                            </div>
                        </div>
                  
                        <div class="col-md-3">
                            <div class="mb-4">
                                <label for="formrow-inputState" class="form-label">Buyer Name</label>
                                <select name="Ac_code" class="form-select" id="Ac_code">
                                    <option value="">--Select Buyer--</option>
                                    @foreach($ledgerlist as  $row)
                                    {
                                        <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
                                    }
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-4">
                                <label for="order_received_date" class="form-label">Order Recieved Date</label>
                                <input type="date" name="order_received_date" class="form-control" id="order_received_date" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="mb-2">
                                <label for="formrow-inputState" class="form-label">Main Style</label>
                                <select name="mainstyle_id" class="form-select" id="mainstyle_id">
                                    <option value="">--Select Main Style--</option>
                                    @foreach($MainStyleList as  $row)
                                    {
                                        <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                                    }
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-2">
                                <label for="formrow-inputState" class="form-label">Sub Style</label>
                                <select name="substyle_id" class="form-select" id="substyle_id">
                                    <option value="">--Select Sub Style--</option>
                                    @foreach($SubStyleList as  $row)
                                    {
                                        <option value="{{ $row->substyle_id }}">{{ $row->substyle_name }}</option>
                                    }
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-2">
                                <label for="formrow-inputState" class="form-label">Style</label>
                                <select name="fg_id" class="form-select" id="fg_id">
                                    <option value="">--Select Style--</option>
                                    @foreach($FGList as  $row)
                                    {
                                        <option value="{{ $row->fg_id }}">{{ $row->fg_name }}</option>
                                    }
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-2">
                                <label for="formrow-inputState" class="form-label">Style No</label>
                                <select name="style_no" class="form-select" id="style_no">
                                    <option value="">--Select Style No--</option>
                                    @foreach($StyleList as  $row)
                                    {
                                        <option value="{{ $row->style_no }}">{{ $row->style_no }}</option>
                                    }
                                    @endforeach
                                </select>
                            </div>
                        </div>                        
                        <div class="col-md-2">
                            <div class="mb-2">
                                <label for="formrow-email-input" class="form-label">Style Description</label>

                                <input type="text" name="style_description" class="form-control" id="style_description" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-2">
                                <label for="formrow-email-input" class="form-label">Shipment Date</label>
                                <input type="date" name="shipment_date" class="form-control" id="shipment_date">
                                <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                                    <thead>
                                        <tr>
                                            <th>SrNo</th>
                                            <th>Activity Name</th>
                                        
                                            <th>Targate Date</th>
                                            <th>Actual Date</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody id="PlanData">
                                        <tr>
                                            <td><input type="text" name="id[]" value="1" id="id" style="width:50px;"/></td>
                                            <td> <select name="act_ids[]" class="item" id="act_id" style="width:200px;  height:30px;" required>
                                                <option value="">--Activity Name--</option>
                                                @foreach($ActList as  $row)
                                                {
                                                    <option value="{{ $row->act_id }}">{{ $row->act_name }}</option>
                                                }
                                                @endforeach
                                            </select></td>

                                            <td><input type="text" name="days[]" id="days" style="width:80px;"/></td>
                                            <td><input type="date" name="target_date[]" id="target_date" style="width:110px;" value="{{date('Y-m-d')}}" /></td>
                                            <td><input type="date" name="actual_date[]" id="actual_date" style="width:110px;" value="{{date('Y-m-d')}}" /></td>
                                            <!--<td>-->
                                            <!--    <input type="button" onclick="insertcone(); " class="btn btn-warning pull-left" value="+">-->
                                            <!--    <input type="button" class="btn btn-danger pull-right" onclick="deleteRowcone(this);" value="X" >-->
                                            <!--</td>-->
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                    <div class="col-sm-6">
                        <label for="formrow-inputState" class="form-label"></label>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
                            <a href="{{ Route('T_And_A_Master.index') }}" class="btn btn-warning w-md">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- end card body -->
    </div>
    <!-- end card -->
</div>
<!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });  
$(function(){
   $('.select2').select2(); 
});
function EnableFields()
{  $("select").prop('disabled', false); }

    var indexcone = 1;
    function insertcone(){

        var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
        var row=table.insertRow(table.rows.length);


        var cell1=row.insertCell(0);
        var t1=document.createElement("input");
        t1.style="display: table-cell; width:50px;";
        t1.id = "id"+indexcone;
        t1.name= "id[]";
        t1.value=indexcone;
        cell1.appendChild(t1);


        var cell2 = row.insertCell(1);
        var t2=document.createElement("select");
        var x = $("#act_id"),
        y = x.clone();
        y.attr("id","act_id");
        y.attr("name","act_ids[]");
        y.width(200);
        y.height(30);
        y.appendTo(cell2);


        var cell3 = row.insertCell(2);
        var t3=document.createElement("input");
        t3.style="display: table-cell; width:80px;";
        t3.type="text";
        t3.id = "days"+indexcone;
        t3.name="days[]";
        cell3.appendChild(t3);

        var cell4 = row.insertCell(3);
        var t4=document.createElement("input");
        t4.style="display: table-cell; width:110px;";
        t4.type="date";
        t4.id = "target_date"+indexcone;
        t4.name="target_date[]";
        t4.value="date('Y-m-d')";
        cell4.appendChild(t4);

        var cell5 = row.insertCell(4);
        var t5=document.createElement("input");
        t5.style="display: table-cell; width:110px;";
        t5.type="date";
        t5.id = "actual_date"+indexcone;
        t5.name="actual_date[]";
        t5.value="date('Y-m-d')";
        cell5.appendChild(t5);

        var cell6=row.insertCell(5);

        var btnAdd = document.createElement("INPUT");
        btnAdd.id = "Dbutton";
        btnAdd.type = "button";
        btnAdd.className="btn btn-warning pull-left";
        btnAdd.value = "+";
        btnAdd.setAttribute("onclick", "insertcone()");
        cell6.appendChild(btnAdd)

        var btnRemove = document.createElement("INPUT");
        btnRemove.id = "Dbutton";
        btnRemove.type = "button";
        btnRemove.className="btn btn-danger pull-right";
        btnRemove.value = "X";
        btnRemove.setAttribute("onclick", "deleteRowcone(this)");
        cell6.appendChild(btnRemove);

        var w = $(window);
        var row = $('#footable_3').find('tr').eq(indexcone);

        if (row.length){
            $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
        }

        document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

        indexcone++;
        recalcIdcone();
    }


    function deleteRowcone(btn) {
        if(document.getElementById('cntrr').value > 1){
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);

            document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;

            recalcIdcone();

            if($("#cntrr").val()<=0)
            {		
                document.getElementById('Submit').disabled=true;
            }

        }
    }

    function recalcIdcone(){
        $.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
    }

    function getDetails(tr_code){
        $.ajax({
            type:"GET",
            url:"{{ route('getSalesOrderDetail') }}",
//dataType:"json",
data:{tr_code:tr_code},
success:function(response){
    console.log(response);

    $("#Ac_code").val(response[0].Ac_code);
    $("#order_received_date").val(response[0].order_received_date);
    $("#mainstyle_id").val(response[0].mainstyle_id);
    $("#substyle_id").val(response[0].substyle_id);
    $("#fg_id").val(response[0].fg_id);
    $("#style_no").val(response[0].style_no);
    $("#style_description").val(response[0].style_description);
    $("#shipment_date").val(response[0].shipment_date);
    $("#dterm_id").val(response[0].dterm_id);
     
     $.ajax({
                type: "GET",
                dataType:"json",
                url: "{{ route('GetTNAMasterData') }}",
                data:{'sales_order_no':tr_code,'dterm_id':response[0].dterm_id},
                success: function(data)
                {
                        console.log(data);
                       $("#PlanData").html(data.html);
                }
        });
    
     
    $("input").prop("readonly", true);
    $("select").prop("disabled", true);
    var user_type= @php echo Session::get('user_type');   @endphp 
     $('input[name="actual_date[]"]').prop("readOnly", false);
    if(user_type==1)
    {
         //$("#target_date").prop("readOnly", false);
         $('input[name="target_date[]"]').prop("readOnly", false);
    }
     
}
});

}

    


</script>

<!-- end row -->
@endsection