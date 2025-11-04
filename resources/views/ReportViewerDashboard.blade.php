@extends('layouts.master') 

@section('content')
<style>
    .rptShadow {box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;}
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Report Dashboard</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                    <li class="breadcrumb-item active">Report Dashboard</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@foreach($ReportMgmtList as $report)
@php 
    $formData = DB::select("select report_viewer.*, form_master.form_label,form_master.form_name from report_viewer
           INNER JOIN form_master ON form_master.form_code = report_viewer.form_code 
           INNER JOIN form_auth ON  form_auth.form_id = form_master.form_code 
           WHERE report_viewer.moduleId =".$report->moduleId." AND form_auth.write_access=1 AND emp_id=".Session::get('userId'));
       
    if(count($formData) > 0)
    {
@endphp
<div class="row">
     <h4 class="card-title mb-4">{{$report->moduleName}}</h4>
    @foreach($formData as $form) 
        <div class="col-xl-3">
                <div class="card rptShadow">
                    <div class="card-body">
                        <a href="{{ url('/'.$form->form_name) }}" target="_blank"> {{$form->form_label}} </a>
                        <p>{{$form->description}}</p>
                    </div>
                </div>
        </div>
    @endforeach
</div>
@php
}
@endphp
@endforeach
@endsection