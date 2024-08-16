@extends('layouts.app')
@section('stylesheets')
<link href="{{ url("public/assets/plugins/select2/css/select2.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ url("public/assets/plugins/select2/css/select2-bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
@endsection
@section('contents')
<div class="breadcrumbs">
      <h1>Representative Dashboard</h1>
  <ol class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li>Users</li>
  </ol>
</div>
<div class="page-content-container">
  @include("includes/alerts")
  <div class="page-content-row">
    <div class="page-content-col">
      <div class="row">
        <div class="col-md-12">
        {!! Form::open([ 'url' => 'my-profile', 'id' => 'frmAddNew', 'files' => true ]) !!}
          <div class="portlet box green">
            <div class="portlet-title">
              <div class="caption"> <i class="fa fa-table"></i>Users - Edit </div>
              <div class="actions pull-right">
                <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> Save </button>
              </div>
            </div>
            <div class="portlet-body">
              <div class="row">
                <div class="col-md-8">
                  <h3>Personal  Info</h3>
                  <hr>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="FirstName">First Name <span>*</span></label>
                        {{ Form::text('FirstName', old("FirstName", unescape($Record->FirstName)), ['class' => 'form-control','id'=>'FirstName', 'maxlength' => '30']) }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="LastName">Last Name</label>
                        {{ Form::text('LastName', old("LastName", unescape($Record->LastName)), ['class' => 'form-control','id'=>'LastName', 'maxlength' => '30']) }}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="EmailAddress">Email Address <span>*</span></label>
                        {{ Form::text('EmailAddress', old("EmailAddress", unescape($Record->EmailAddress)), ['class' => 'form-control','id'=>'EmailAddress', 'maxlength' => '255']) }}
                      </div>
                    </div>
                  </div>
                  <h3>Contact Info</h3>
                  <hr>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="HomeAddress">Home Address</label>
                          {{ Form::textarea('HomeAddress', old("HomeAddress", unescape($Record->HomeAddress)), ['class' => 'form-control','id'=>'HomeAddress', 'maxlength' => '255', 'rows' => '3']) }}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="form-group">
                          <label for="MobileNumber">Contact Number</label>
                        {{ Form::text('ContactNumber', old("ContactNumber", unescape($Record->ContactNumber)), ['class' => 'form-control','id'=>'HomeAddress', 'maxlength' => '20', 'rows' => '3']) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Profile Picture</label>
                    <input type="file" name="flProfilePicture" id="flProfilePicture" accept="jpg,jpeg,gif,png" max-size="2" class="form-control custom-file-input">
                    <div class="input-group">
                      <input type="text" class="form-control file-name-flProfilePicture" value="{{ old('ProfilePicture', unescape($Record->ProfilePicture)) }}" readonly>
                      <input type="hidden" name="ProfilePicture" id="pathflProfilePicture" value="{{ old('ProfilePicture', unescape($Record->ProfilePicture)) }}">
                      <span class="input-group-btn">
                      <button class="btn blue btn-file" type="button" file-source="flProfilePicture">Browse...</button>
                      </span> </div>
                    <div class="file-msg file-msg-flProfilePicture"></div>
                    <div class="file-preview-flProfilePicture"> @if(is_file(public_path(unescape($Record->ProfilePicture))))
                      {!! '<img src="'.url("public/".unescape($Record->ProfilePicture)).'" class="img-responsive" />' !!}
                      @endif </div>
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