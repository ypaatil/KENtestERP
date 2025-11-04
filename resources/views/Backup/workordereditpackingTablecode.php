  @if(count($PackingTrimsList)>0)
                                                @php $no=1; @endphp
                                                @foreach($PackingTrimsList as $List) 
                                                    @php
                                                    $SizeListFromBOM=DB::select("select size_array from bom_packing_trims_details where sales_order_no='".$List->sales_order_no."' and item_code='".$List->item_code."' limit 0,1");
                                                    $size_ids = explode(',', isset($SizeListFromBOM[0]->size_array) ? $SizeListFromBOM[0]->size_array : ""); 
                                                    $SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                                                    $sizes='';
                                                    foreach($SizeDetailList as $sz)
                                                    {
                                                        $sizes=$sizes.$sz->size_name.', ';
                                                    }
                                                    $ColorListpacking = DB::select("select color_id FROM bom_packing_trims_details WHERE item_code =".$List->item_code." AND sales_order_no='".$VendorWorkOrderMasterList->sales_order_no."'");
                                           
                                                    $ColorListpacking1= App\Models\VendorWorkOrderDetailModel::
                                                    join('color_master','vendor_work_order_detail.color_id','=','color_master.color_id') 
                                                    ->where('vendor_work_order_detail.sales_order_no', $VendorWorkOrderMasterList->sales_order_no)
                                                    ->where('vendor_work_order_detail.vw_code', $VendorWorkOrderMasterList->vw_code)
                                                    ->whereIn('vendor_work_order_detail.color_id', array(isset($ColorListpacking[0]->color_id) ? $ColorListpacking[0]->color_id : 0))->where('delflag','=', '0')->distinct('vendor_work_order_detail.color_id')->get('color_name');
                                                   // dd(DB::getQueryLog());
                                                    $colorspk='';
                                                    foreach($ColorListpacking1 as $colorpk)
                                                    {
                                                    $colorspk=$colorspk.$colorpk->color_name.', ';
                                                    }
                                                    @endphp
                                                <tr>
                                                   <td><input type="text" name="idss" value="1" id="{{$no}}" style="width:50px;"/></td>
                                                   <td>
                                                      <select name="item_codess[]" class="item_packing_trims" id="item_codess" style="width:200px; height:30px;" required>
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList3 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}"
                                                         {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }}
                                                         >{{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td>{{rtrim($colorspk, ', ');}} </td>
                                                   <td>{{rtrim($sizes,',');}}</td>
                                                   <td>
                                                      <select name="class_idss[]"   id="class_idss" style="width:200px; height:30px;" required>
                                                         <option value="">--Classification--</option>
                                                         @foreach($ClassList3 as  $row)
                                                         {
                                                         <option value="{{ $row->class_id }}"
                                                         {{ $row->class_id == $List->class_id ? 'selected="selected"' : '' }}
                                                         >{{ $row->class_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td>
                                                      <input type="text"    name="descriptionss[]" value="{{$List->description}}" id="descriptionss" style="width:200px; height:30px;"   />
                                                   </td>
                                                   <td><input type="number" step="0.01"    name="consumptionss[]" value="{{$List->consumption}}" id="consumptionss" style="width:80px; height:30px;" required /></td>
                                                   <td>
                                                      <select name="unit_idss[]" class="select2" id="unit_idss" style="width:100px; height:30px;" required >
                                                         <option value="">--Unit List--</option>
                                                         @foreach($UnitList as  $row)
                                                         {
                                                         <option value="{{ $row->unit_id }}"
                                                         {{ $row->unit_id == $List->unit_id ? 'selected="selected"' : '' }}
                                                         >{{ $row->unit_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td><input type="number" step="0.01" max="5" min="0" class="WASTAGE3"  name="wastagess[]" value="{{$List->wastage}}" id="wastagess" style="width:80px; height:30px;" required /></td>
                                                   <td><input type="text"      name="bom_qtyss[]" value="{{$List->bom_qty}}" id="bom_qtyss" style="width:80px; height:30px;" required readOnly/>
                                                      <input type="hidden"      name="bom_qtyss1[]" value="{{$List->actual_qty}}" id="bom_qtyss1" style="width:80px; height:30px;" required readOnly/>
                                                   </td>
                                                   <input type="hidden"  name="final_consss[]" value="{{$List->final_cons}}" id="final_consss'.$no.'" style="width:80px; height:30px;" required readOnly />
                                                   <input type="hidden"  name="size_qtyss[]" value="{{$List->size_qty}}" id="size_qtyss'.$no.'" style="width:80px; height:30px;" required readOnly />
                                                </tr>
                                                @php 
                                                    $no=$no+1; 
                                                @endphp
                                                @endforeach
                                            @endif