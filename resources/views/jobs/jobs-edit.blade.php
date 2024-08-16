@extends('layouts.app')

@section('stylesheets')
   <style> .portlet.box.green {
           border: 1px solid #5cd1db;
       }</style>

<link href="{{url("public/assets/layout/css/custom.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{url("public/assets/layout/css/style.css")}}" rel="stylesheet" type="text/css"/>

<link href="{{ url("public/assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/css/components.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/plugins/plugins.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/css/search.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/plugins/clockface/css/clockface.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/plugins/datatables/datatables.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/plugins/layout5/css/style.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/plugins/select2/css/select2.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/plugins/select2/css/select2-bootstrap.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="{{ url("public/assets/css/layoutdemo.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />
<link href="{{ url("public/assets/css/darkblue.min.css") }}" id="style_components" rel="stylesheet" type="text/css" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.1.1/animate.min.css" rel="stylesheet"
        type="text/css"/>





@endsection

@section('contents')
    <div class="container-fluid">
    <div class="page-content" style="padding: 30px;">
      <div class="breadcrumbs">
        <h1>LW Client Dashboard</h1>
      </div>
      <div class="page-content-container">
        @include("includes/alerts")
        <div class="page-content-row">
          <div class="page-content-col">
            <div class="portlet box green">
              <div class="portlet-title">
                <div class="caption">
                  <i class="icon-pin "></i>
                  <span class="caption-subject bold uppercase">New Job Form</span>
                </div>
              </div>
              <div class="portlet-body form">
                {!! Form::open([ 'url' => 'jobs-edit/'.$jobs_record->job_id, 'id' => 'frmAddNew','class'=>'form-horizontal form-bordered']) !!}
                <div class="form-body">
                  <div class="row">
                    <div class="col-lg-12">

                      <div class="portlet light bordered">
                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="chain_id">Select Client/Company <span style="color: red">*</span> </label>

                                <select name="company_id" class="form-control " id="companies_list">
                                  <option value=""> ----- SELECT COMPANY ----- </option>
                                  @foreach($companies as $comp)
                                    <option value="{{$comp->company_id}}"
                                      data-aminities = "@foreach($comp->company_amenity as $com_amenity){{ $com_amenity->amen_id }},@endforeach"
                                      {{ ($comp->company_id == $jobs_record->company_id?'selected':'') }}
                                      >{{$comp->company_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-lg-12">

                      <div class="portlet light bordered">
                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="chain_id">Reservation Title <span style="color: red">*</span> </label>
                                {{ Form::text('title', 
                                (old("title")?old("title"):$jobs_record->title)

                                , ['class' => 'form-control','id'=>'title', 'maxlength' => '100','placeholder'=>'Enter Reservation Title']) }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="portlet light bordered">
                        <div class="portlet-title">
                          <div class="caption font-green-sharp">
                            <i class="icon-info font-green-sharp"></i>
                            <span class="caption-subject bold">Job Address:</span>
                          </div>
                        </div>
                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group">
                                <label for="address">Street Address <span style="color: red">*</span> </label>
                                {{ Form::text('address', (old("address")?old("address"):$jobs_record->address), ['class' => 'form-control','id'=>'address', 'maxlength' => '200','placeholder'=>'Enter Street Address']) }}
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <div class="form-group">
                                <label for="city">City <span style="color: red">*</span> </label>
                                {{ Form::text('city', (old("city")?old("city"):$jobs_record->city), ['class' => 'form-control','id'=>'city', 'maxlength' => '50','placeholder'=>'Enter City']) }}
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <div class="form-group">
                                <label for="state_id">State <span style="color: red">*</span> </label>
                                {!! Form::select('state_id', $States, (old("state_id")?old("state_id"):$jobs_record->state_id), ['class' => 'form-control select2', 'id' => 'state_id']) !!}
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <div class="form-group">
                                <label for="zip">Zip <span style="color: red">*</span></label>
                                {{ Form::text('zip', (old("zip")?old("zip"):$jobs_record->zip), ['class' => 'form-control','id'=>'zip', 'maxlength' => '10','placeholder'=>'Enter Zip']) }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="portlet light bordered">
                        <div class="portlet-title">
                          <div class="caption font-green-sharp">
                            <i class="icon-info font-green-sharp"></i>
                            <span class="caption-subject bold">Number Of Rooms:</span>
                          </div>
                        </div>
                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="double">Doubles (Select number of double rooms required)</label>
                                

                                <select class="form-control" id="double" name="double">
                                  @for($i=0; $i<=20; $i++)
                                    <option value="{{$i}}" {{ (old("double")?old("double"):$jobs_record->double_rooms)  ==$i?'selected':'' }}>{{$i}}</option>
                                  @endfor

                                </select>



                                (Repeat Information) <input style="margin-top: 22px;width: 30px;height: 16px;cursor: pointer;" id="dbl_check" type="checkbox">
                              </div>
                              <div id="dbl">
                                  

                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="single">Single (Select number of single rooms required)</label>

                                <select class="form-control" id="single" name="single">
                                  @for($i=0; $i<=20; $i++)
                                    <option value="{{$i}}" {{ (old("single")?old("single"):$jobs_record->single_rooms)==$i?'selected':'' }}>{{$i}}</option>
                                  @endfor

                                </select>


                                (Repeat Information) <input style="margin-top: 22px;width: 30px;height: 16px;cursor: pointer;" id="sngl_check" type="checkbox">
                              </div>
                              <div id="sngl"></div>
                            </div>
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label for="others">Others </label>
                                {{ Form::text('others', (old("others")?old("others"):$jobs_record->others), ['class' => 'form-control','id'=>'others', 'maxlength' => '100','placeholder'=>'e.g Suite,Conjoining rooms etc']) }}
                              </div>
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-12" id="oth">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label style='padding-top: 0px;'><b>Information</b></label>
                                          <div class="input-group">
                                            <input class="form-control form-control-inline date-picker" size="16" type="text" value="{{(old("others_check_in")?old("others_check_in"):$jobs_record->others_check_in)}}" name="others_check_in" placeholder="Check In" >
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input class="form-control form-control-inline date-picker" size="16" type="text" value="{{(old("others_check_out")?old("others_check_out"):$jobs_record->others_check_out)}}" name="others_check_out" placeholder="Check Out">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label>Notes</label>
                                          {{ Form::textarea('others_notes', (old("others_notes")?old("others_notes"):$jobs_record->others_notes), ['class' => 'form-control','id'=>'others_notes', 'rows' => '5']) }}
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="portlet light bordered">
                        <div class="portlet-title">
                          <div class="caption font-green-sharp">
                            <i class="icon-info font-green-sharp"></i>
                            <span class="caption-subject bold">Preferences</span>
                          </div>
                        </div>
                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="others">Preferences </label>
                               
                                  <select name="amen_id[]" id="amen_id" class="form-control select2" multiple>
                                    @foreach($Amenities as $key => $value)
                                      <option value="{{$key}}" 
                                        @foreach($amenities as $ameni)
                                            {{ $key == $ameni['amen_id']?'selected':'' }}
                                        @endforeach
                                      >{{$value}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            </div>
                            <?php /* {{in_array($key,$CompAmenities)?'selected':''}} */?>
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="others">Additional Amenities </label>
                                {{ Form::textarea('amenities_notes', (old("amenities_notes")?old("amenities_notes"):$jobs_record->amenities_notes), ['class' => 'form-control ','id'=>'amenities_notes', 'rows' => '3']) }}
                              </div>
                            </div>

                          </div>
                        </div>

                      </div>

                    </div>


                    <div class="col-lg-6">


                      <div class="portlet light bordered">
                        <div class="portlet-title">
                          <div class="caption font-green-sharp">
                            <i class="icon-info font-green-sharp"></i>
                            <span class="caption-subject bold">Urgency</span>
                          </div>
                        </div>


                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group form-md-radios">
                                <div class="md-radio-inline">
                                  <div class="md-radio">
                                    <input 
                                        type="radio" 
                                        name="confirmation" 
                                        value="1" 
                                        id="statusActive"
                                        class="md-radiobtn"{!! ((int)old("Status", 1) == 1 ? ' checked="checked"' : '') !!}
                                        {{ ($jobs_record->confirmation == 1? ' checked="checked"' : '') }}
                                    />
                                    <label for="statusActive"> <span></span> <span class="check"></span>
                                      <span class="box"></span> Need confirmation by the end of today
                                    </label>
                                  </div>
                                  <br>
                                  <br>
                                  <div class="md-radio">
                                    <input type="radio" name="confirmation" value="0"
                                           id="statusDeactive"
                                           class="md-radiobtn"{!! ((int)old("Status", 1) == 0 ? ' checked="checked"' : '') !!} 
                                           {{ ($jobs_record->confirmation == 0? ' checked="checked"' : '') }}
                                           />
                                    <label for="statusDeactive"> <span></span> <span
                                              class="check"></span> <span class="box"></span> Future
                                      Date </label>
                                  </div>
                                  <br>
                                  <br>
                                  <div id="drop">
                                    <label for="confirmation_date">Choose a Calendar Date</label>
                                    {{ Form::text('confirmation_date', 
                                    (old("confirmation_date")?old("confirmation_date"):date_format(date_create($jobs_record->confirmation_date),'Y-m-d')), ['class' => 'form-control form-control-inline date-picker','id'=>'confirmation_date','placeholder'=>'Select Date']) }}
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>
                        </div>

                      </div>


                    </div>
                  </div>


                  <div class="row">

                    <div class="col-lg-6">

                      <div class="portlet light bordered">
                        <div class="portlet-title">
                          <div class="caption font-green-sharp">
                            <i class="icon-info font-green-sharp"></i>
                            <span class="caption-subject bold">Accounting Info:</span>
                          </div>
                        </div>


                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="billing_id">Payment Type <span style="color: red">*</span> </label>
                                {!! Form::select('billing_id', $Pay, (old("billing_id")?old("billing_id"):$jobs_record->billing_id), ['class' => 'form-control  select2', 'id' => 'billing_id']) !!}
                              </div>
                            </div>


                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="project_type">Project Type </label>
                                {{ Form::text('project_type', (old("project_type")?old("project_type"):$jobs_record->project_type), ['class' => 'form-control','id'=>'project_type', 'maxlength' => '100','placeholder'=>'Enter Project Type']) }}
                              </div>
                            </div>
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="project_no">Project Name </label>
                                {{ Form::text('project_no', (old("project_no")?old("project_no"):$jobs_record->project_no), ['class' => 'form-control','id'=>'project_no', 'maxlength' => '100','placeholder'=>'Enter Project Name']) }}
                              </div>
                            </div>


                          </div>
                        </div>

                      </div>

                    </div>


                    <div class="col-lg-6">


                      <div class="portlet light bordered">
                        <div class="portlet-title">
                          <div class="caption font-green-sharp">
                            <i class="icon-info font-green-sharp"></i>
                            <span class="caption-subject bold">Requestor Info:</span>
                          </div>
                        </div>


                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="req_name">Name </label>
                                {{ Form::text('req_name',
                                            (old("req_name")?old("req_name"):$jobs_record->req_name), 

                                            ['class' => 'form-control','id'=>'req_name', 'maxlength' => '50']) }}
                              </div>
                            </div>
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="req_email">Email </label>
                                {{ Form::text('req_email', 

                                (old("req_email")?old("req_email"):$jobs_record->req_email)
                                , ['class' => 'form-control','id'=>'req_email', 'maxlength' => '150']) }}
                              </div>
                            </div>
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="req_contact">Phone </label>
                                {{ Form::text('req_contact',
                                (old("req_contact")?old("req_contact"):$jobs_record->req_contact), ['class' => 'form-control','id'=>'req_contact', 'maxlength' => '30']) }}
                              </div>
                            </div>

                          </div>
                        </div>

                      </div>


                    </div>
                  </div>


                  <div class="row">

                    <div class="col-lg-6">

                      <div class="portlet light bordered">
                        <div class="portlet-title">
                          <div class="caption font-green-sharp">
                            <i class="icon-info font-green-sharp"></i>
                            <span class="caption-subject bold">Send Invoice To</span>
                          </div>
                        </div>


                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="billing_id">Sender Name </label>
                                {{ Form::text('invoice_name',
                                (old("invoice_name")?old("invoice_name"):$jobs_record->invoice_name), ['class' => 'form-control','id'=>'invoice_name', 'maxlength' => '50']) }}
                              </div>
                            </div>
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="project_type">Sender Email </label>
                                {{ Form::text('invoice_email', (old("invoice_email")?old("invoice_email"):$jobs_record->invoice_email), ['class' => 'form-control','id'=>'invoice_email', 'maxlength' => '150']) }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="portlet light bordered">
                        <div class="portlet-title">
                          <div class="caption font-green-sharp">
                            <i class="icon-info font-green-sharp"></i>
                            <span class="caption-subject bold">Additional Notes for Rep</span>
                          </div>
                        </div>
                        <div class="portlet-body pbmb10">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                {{ Form::textarea('notes', (old("notes")?old("notes"):$jobs_record->notes), ['class' => 'form-control','id'=>'notes', 'rows' => '5']) }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  {{Form::close()}}
                </div>
                <div class="form-group">
                  <div>
                    <button type="button" id="submit_form"
                                    class="btn btn-success pull-right"
                                    style="    margin-top: -55px; margin-right: 15px;"
                                    >Update Job
                            </button>

                  </div>
                </div>
              </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  
@endsection
@section('to_top')
    <a href="#index" class="go2top">
        <i class="icon-arrow-up"></i>
    </a>
    @endsection
<!--[if lt IE 9]>
    <script src="../assets/global/plugins/respond.min.js"></script>
    <script src="../assets/global/plugins/excanvas.min.js"></script>
    <script src="../assets/global/plugins/ie8.fix.min.js"></script>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->

    <?php 
        $oldCheckInArraydbl=[];
        $oldCheckOutArraydbl=[];
        $oldGuest1Arraydbl=[];
        $oldGuest2Arraydbl=[];

        $oldCheckInArraysing=[];
        $oldCheckOutArraysing=[];
        $oldGuest1Arraysing=[];

        $i = 1;
        $j = 1;
        foreach ($rooms as $room){
            if($room['room_type'] == 2){
                $oldCheckInArraydbl[]=$room['check_in'];
                $oldCheckOutArraydbl[]=$room['check_out'];
                $oldGuest1Arraydbl[]=$room['guest_1'];
                $oldGuest2Arraydbl[]=$room['guest_2'];
                $i++;
            }else{ 
                $oldCheckInArraysing[]=$room['check_in'];
                $oldCheckOutArraysing[]=$room['check_out'];
                $oldGuest1Arraysing[]=$room['guest_1'];
                $j++;
            }
        }
    ?>
@php
  
  if(!is_null(old('check_in_dbl')) && !is_null(old('check_out_dbl')) && !is_null(old('guest_1_dbl')) && !is_null(old('guest_2_dbl')) ){
      $oldCheckInArraydbl=old('check_in_dbl');
      $oldCheckOutArraydbl=old('check_out_dbl');
      $oldGuest1Arraydbl=old('guest_1_dbl');
      $oldGuest2Arraydbl=old('guest_2_dbl');
  }

  if(!is_null(old('check_in_sngl')) && !is_null(old('check_out_sngl')) && !is_null(old('guest_1_sngl')) ){
      $oldCheckInArraysing=old('check_in_sngl');
      $oldCheckOutArraysing=old('check_out_sngl');
      $oldGuest1Arraysing=old('guest_1_sngl');

  }


@endphp
<script>
    const oldCheckInArraydbl = @json($oldCheckInArraydbl);
    const oldCheckOutArraydbl = @json($oldCheckOutArraydbl);
    const oldGuest1Arraydbl = @json($oldGuest1Arraydbl);
    const oldGuest2Arraydbl = @json($oldGuest2Arraydbl);
    
    const oldCheckInArraysing = @json($oldCheckInArraysing);
    const oldCheckOutArraysing = @json($oldCheckOutArraysing);
    const oldGuest1Arraysing = @json($oldGuest1Arraysing);

    // console.log('oldCheckInArraydbl', oldCheckInArraydbl);
    // console.log('oldCheckOutArraydbl', oldCheckOutArraydbl);
    // console.log('oldGuest1Arraydbl', oldGuest1Arraydbl);
    // console.log('oldGuest2Arraydbl', oldGuest2Arraydbl);

    // console.log('oldCheckInArraysing', oldCheckInArraysing);
    // console.log('oldCheckOutArraysing', oldCheckOutArraysing);
    // console.log('oldGuest1Arraysing', oldGuest1Arraysing);
</script>


@section('js_plugins')


  

    <script src="{{ url("public/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js") }}" type="text/javascript"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
    
    <script src="{{ url("public/assets/js/moment.min.js") }}" type="text/javascript"></script>

    <script src="{{ url("public/assets/plugins/select2/js/select2.full.min.js") }}" type="text/javascript"></script>
    <script src="{{ url("public/assets/js/quick-sidebar.min.js") }}" type="text/javascript"></script>

    <script src="{{ url("public/assets/js/quick-nav.min.js") }}" type="text/javascript"></script>

    <script src="{{ url("public/assets/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js") }}" type="text/javascript"></script>


    

    <script src="{{ url("public/assets/js/datatables.fnReloadAjax.js") }}" type="text/javascript"></script>

    <script src="{{ url("resources/assets/datatable_instance.js") }}" type="text/javascript"></script>


    <script src="{{ url("resources/assets/request.js") }}" type="text/javascript"></script>
@endsection

@section('javascripts')
    
  <script type="text/javascript">
    $('#companies_list').on('change',function(){
      var selected_company_aminites = $(this).find(':selected').data('aminities').replace(/,+$/,'');
      var selected_company_aminites = selected_company_aminites.split(",");
      console.log(selected_company_aminites);
      $("#amen_id > option").each(function() {
        $(this).prop('selected', false).trigger('change');
          if(jQuery.inArray(this.value, selected_company_aminites) !== -1){
            $(this).prop('selected', true).trigger('change');
          }


          //

      });

    });
  </script>
@endsection


