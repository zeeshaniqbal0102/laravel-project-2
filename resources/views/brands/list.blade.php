@extends('layouts.app')
@section('stylesheets')
<link href="{{ url("public/assets/plugins/datatables/datatables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ url("public/assets/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ url("public/assets/plugins/select2/css/select2.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ url("public/assets/plugins/select2/css/select2-bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
@endsection
@section('contents')
<div class="breadcrumbs">
      <h1>Representative Dashboard</h1>
  <ol class="breadcrumb">
    <li><a href="#">Hotel Brand</a></li>
  </ol>
</div>
<div class="page-content-container">
  @include("includes/alerts")
  <div class="page-content-row">
    <div class="page-content-col">
      <div class="row">
        <div class="col-md-12">
        {!! Form::open([ 'url' => 'brands', 'id' => 'frm_list' ]) !!}
          <input type="hidden" name="_method" value="DELETE">
          <div class="portlet box green">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-table"></i>Hotel Brands </div>
              <div class="actions pull-right">
            <a class="btn btn-info" href="{{ url("brands/add") }}"> <i class="fa fa-plus"></i> Add New </a>
            <button type="button" class="btn btn-danger" onClick="doDelete()"> <i class="fa fa-trash"></i> Delete </button>    
            </div>
            </div>
            <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                <thead>
                  <tr role="row" class="heading">
                    <th width="2%"> <div class="md-checkbox has-success">
                        <input type="checkbox" id="checkAll" class="md-check group-checkable check_all">
                        <label for="checkAll"> <span class="inc"></span> <span class="check"></span> <span class="box"></span> </label>
                      </div>
                    </th>
                    <th>ID</th>
                    <th>Hotel Chain</th>
                    <th>Hotel Brand</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th>Modified</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table> 
            </div>
          </div>
        {!! FORM::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section("js_plugins")
<script src="{{ url("public/assets/plugins/datatables/datatables.min.js") }}" type="text/javascript"></script>
<script src="{{ url("public/assets/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js") }}" type="text/javascript"></script>
@endsection
@section('javascripts')
<script language="javascript">
	Globals["urlStatusUpdate"] = "/brands/update-status";
	Globals["urlList"] = "/brands";
	Globals["sortingDisabled"] = [0, 7];
    Globals["defaultSort"] = [1, "asc"];
    Globals["dtDom"] = "lBfrtip";
    Globals["className"] = "btn btn-info btn-sm";
    Globals["dtButtons"] = [
        {
            extend: 'excel',
            text: '<i class="fa fa-file-excel-o"></i> Excel',
            className: Globals["className"]
        },
        {
            extend: 'pdf',
            text: '<i class="fa fa-file-pdf-o"></i> PDF',
            className: Globals["className"]
        },
        {
            extend: 'csv',
            text: '<i class="fa fa-file-text-o"></i> CSV',
            className: Globals["className"]
        },
        {
            extend: 'copy',
            text: '<i class="fa fa-copy"></i> Copy',
            className: Globals["className"]
        },
        {
            extend: 'print',
            text: '<i class="fa fa-print"></i> Print',
            className: Globals["className"]
        }
    ];
</script>
<script src="{{ url("resources/assets/scripts/datatable_instance.js") }}" type="text/javascript"></script>
@endsection