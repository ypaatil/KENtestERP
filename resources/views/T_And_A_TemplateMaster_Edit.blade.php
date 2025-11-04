@extends('layouts.master') 

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title mb-4">T and A Template Master</h4>

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

                <form action="{{route('T_And_A_TemplateMaster.update',$T_And_A_TemplateMasterList)}}" method="POST" id="frmData">
                    @csrf 
                    @method('put')
                    <div class="row">
                         
                          <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                          <input type="hidden" name="t_and_a_tid" value="{{ $T_And_A_TemplateMasterList->t_and_a_tid }}" class="form-control" id="formrow-email-input">
                         
                         
                         
						  <div class="col-md-2">
							<div class="mb-3">
								<label for="dterm_id" class="form-label">Delivery Terms</label>
								<select name="dterm_id" class="form-select" id="dterm_id" required>
									<option value="">--Delivery Terms--</option>
									@foreach($DeliveryTermsList as  $row)
									{
										<option value="{{ $row->dterm_id }}" {{ $row->dterm_id== $T_And_A_TemplateMasterList->dterm_id  ? 'selected="selected"' : '' }} >
										{{ $row->delivery_term_name }}
										</option>
									}
									@endforeach
								</select>
							</div>
						</div>
					   </div>
					   
					    <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                    <div class="row">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                                    <thead>
                                        <tr>
                                            <th>SrNo</th>
                                            <th>Activity Name</th>
                                            <th>Days</th>
                                            <th>Dependent on Activity</th>
                                            <th>Add/Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php  if($T_And_A_TemplateDetailfetch->isEmpty()) { @endphp   

                                        <tr>
                                            <td><input type="text" name="id[]" value="1" id="id" style="width:50px;"/></td>
                                            <td> <select name="act_ids[]" class="item" id="act_id" style="width:200px; height:30px;" required>
                                                <option value="">--Activity Name--</option>
                                                @foreach($ActList as  $row)
                                                {
                                                    <option value="{{ $row->act_id }}">{{ $row->act_name }}</option>
                                                }
                                                @endforeach
                                            </select>
                                            </td>

                                            <td><input type="text" name="days[]" id="days" style="width:80px;"/></td>
                                            
                                             <td> 
                                                <select name="dact_ids[]" class="item" id="dact_id" style="width:200px; height:30px;" required>
                                                    <option value="">--Dependent Activity --</option>
                                                    @foreach($ActList as  $row)
                                                    {
                                                        <option value="{{ $row->act_id }}">{{ $row->act_name }}</option>
                                                    }
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="button" onclick="insertcone();" class="btn btn-warning pull-left" value="+"/>
                                                <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X"/>
                                            </td>
                                        </tr>
                                        @php } else { @endphp
                                        @php $no=1; @endphp
                                        @foreach($T_And_A_TemplateDetailfetch as $row)
                                        <tr>
                                            <td>
                                                <input type="text" name="id[]" value="{{ $no }}" id="id" style="width:50px;"/>
                                            </td>
                                            <td> 
                                                <select name="act_ids[]" class="item" id="act_id" style="width:200px; height:30px;" required>
                                                    <option value="">--Activity Name--</option>
                                                    @foreach($ActList as  $rowact)    
                                                        @php
                                                            if($row->act_id== $rowact->act_id)
                                                            {
                                                                $selected1 = "selected";
                                                            }
                                                            else
                                                            {
                                                                $selected1 = "";
                                                            }
                                                        @endphp 
                                                        <option value="{{ $rowact->act_id }}" {{ $selected1 }}>{{ $rowact->act_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <input type="text" name="days[]" id="days" style="width:80px;" value="{{$row->days}}" />
                                            </td>
                                            <td> 
                                                <select name="dact_ids[]" class="item" id="dact_id" style="width:200px; height:30px;" required>
                                                    <option value="">--Dependent Activity Name--</option>
                                                    @foreach($ActList as  $rowact)
                                                    @php
                                                        if($row->dact_id== $rowact->act_id)
                                                        {
                                                            $selected2 = "selected";
                                                        }
                                                        else
                                                        {
                                                            $selected2 = "";
                                                        }
                                                    @endphp 
                                                        <option value="{{ $rowact->act_id }}" {{ $selected2 }}>{{ $rowact->act_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="button" onclick="insertcone(); " class="btn btn-warning pull-left" value="+" />
                                                <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" />
                                            </td>
                                        </tr>
                                        @php $no=$no+1;  @endphp
                                        @endforeach
                                        @php } @endphp
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>SrNo</th>
                                            <th>Activity Name</th>
                                            <th>Days</th>
                                            <th>Add/Remove</th>
                                        </tr>
                                    </tfoot>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                    <div class="col-sm-6">
                        <label for="formrow-inputState" class="form-label"></label>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-md" id="Submit">Submit</button>
                            <a href="{{ Route('T_And_A_TemplateMaster.index') }}" class="btn btn-warning w-md">Cancel</a>
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

        var cell2 = row.insertCell(3);
        var t2=document.createElement("select");
        var x = $("#dact_id"),
        y = x.clone();
        y.attr("id","dact_id");
        y.attr("name","dact_ids[]");
        y.width(200);
        y.height(30);
        y.appendTo(cell2);

        var cell6=row.insertCell(4);

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

            }
        });
    }
</script>

<!-- end row -->
@endsection