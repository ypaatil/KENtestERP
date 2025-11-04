      
   @extends('layouts.master') 

@section('content')   

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Brand Tables</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                            <li class="breadcrumb-item active">Brand List</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        
                        
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                        <form method="POST" action="{{ route('copy.records') }}">
                                                            @csrf
                                                    
                                                            <div class="mb-3">
                                                                <label for="source_db" class="form-label">From Database</label>
                                                                <input type="text" class="form-control" name="source_db" value="kenerp_KenGlobalERP_2025_2026" required>
                                                            </div>
                                                    
                                                            <!--<div class="mb-3">-->
                                                            <!--    <label for="source_table" class="form-label">Source Table</label>-->
                                                            <!--    <input type="text" class="form-control" name="source_table" required>-->
                                                            <!--</div>-->
                                                    
                                                            <div class="mb-3">
                                                                <label for="target_db" class="form-label">To Database</label>
                                                                <input type="text" class="form-control" name="target_db" value="kenerp_CrossBackupKenGlobalERP2526" required>
                                                            </div>
                                                    
                                                            <!--<div class="mb-3">-->
                                                            <!--    <label for="target_table" class="form-label">Common Table Name</label>-->
                                                            <!--    <input type="text" class="form-control" name="target_table" value="brand_master" required>-->
                                                            <!--</div>-->
                                                            
                                                              <div class="col-md-3">
                                                                 <div class="mb-3">
                                                                    <label for="form_id" class="form-label">Form Name</label>
                                                                    <select name="form_id" class="form-select select2" id="form_id"   required>
                                                                       <option>--Select--</option>
                                                                       @foreach($FormList as  $row) 
                                                                            <option value="{{ $row->form_code }}">{{ $row->form_label }}</option>
                                                                       @endforeach
                                                                    </select>
                                                                 </div>
                                                              </div>
                                                    
                                                            
                                                    
                                                            <div class="mb-3">
                                                                <label for="ids_to_copy" class="form-label">IDs to Copy (comma-separated)</label>
                                                                <textarea class="form-control" name="ids_to_copy" rows="3" placeholder="Leave blank to copy all records"></textarea>
                                                            </div>
                                                    
                                                            <button type="submit" class="btn btn-primary">Copy and Delete</button>
                                                        </form>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
                    
                        @endsection