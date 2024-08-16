@extends('layouts.app')
@section('stylesheets')
<link href="{{ url("public/assets/plugins/select2/css/select2.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ url("public/assets/plugins/select2/css/select2-bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
@endsection
@section('contents')
<div class="breadcrumbs">
      <h1>Representative Dashboard</h1>
  <ol class="breadcrumb">
    <li><a href="#">Chains</a></li>
  </ol>
</div>
<div class="page-content-container">
  @include("includes/alerts")
  <div class="page-content-row">
    <div class="page-content-col">
      <div class="row">
        <div class="col-md-12">
        {!! Form::open([ 'url' => 'chains/edit/'.$Record->chain_id, 'id' => 'frmAddNew', 'files' => true ]) !!}
          <div class="portlet box green">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-table"></i>Chain - Edit </div>
              <div class="actions pull-right">
                <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> Save </button>
                <button type="button" class="btn btn-warning" onClick="location.href='{{ url('chains') }}'"> <i class="fa fa-times"></i> Cancel </button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="row">
                <div class="col-md-8">
                  <h3>Chain  Info</h3>
                  <hr>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="name">Chain Name <span>*</span></label>
                        {{ Form::text('name', old("name", unescape($Record->name)), ['class' => 'form-control','id'=>'name', 'maxlength' => '100']) }}
                      </div>
                    </div>
                  </div>
                 
                
                </div>
                <div class="col-md-4">
                     <h3>Settings</h3>
                  <hr>
                  <div class="form-group form-md-radios">
                    <label>Status</label>
                    <div class="md-radio-inline">
                      <div class="md-radio">
                        <input type="radio" name="status" value="1" id="statusActive" class="md-radiobtn"{!! ((int)old("status", $Record->
                        status) == 1 ? ' checked="checked"' : '') !!} />
                        <label for="statusActive"> <span></span> <span class="check"></span> <span class="box"></span> Active </label>
                      </div>
                      <div class="md-radio">
                        <input type="radio" name="status" value="0" id="statusDeactive" class="md-radiobtn"{!! ((int)old("status", $Record->
                        status) == 0 ? ' checked="checked"' : '') !!} />
                        <label for="statusDeactive"> <span></span> <span class="check"></span> <span class="box"></span> Deactive </label>
                      </div>
                    </div>
                  </div>
                 
                </div>
              </div>
            </div>
          </div>
        {!! FORM::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js_plugins')
<script src="{{ url("public/assets/plugins/select2/js/select2.full.min.js") }}" type="text/javascript"></script>
@endsection
@section('javascripts')
<script language="javascript">
	Globals["csrf"] = "{{ csrf_token() }}";
	Globals["url"] = "{{ url("/") }}";
	Globals["assetsURL"] = "{{ url("resources/assets") }}";
	Globals["mode"] = "add";
</script>
<script src="{{ url("resources/assets/scripts/users.js") }}" type="text/javascript"></script>
@endsection