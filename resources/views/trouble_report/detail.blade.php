@extends('crudbooster::admin_template')
@section('content')
        <div class="panelRoot panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-star"></i>Trouble Report Detail</strong>
            </div>
            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <form class="form-horizontal" method="post" id="form">
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Create 1
                        </label>

                        <textarea class="col-sm-9" name="why_create_1">{{$doc->why_create_1}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Create 2
                        </label>

                        <textarea class="col-sm-9" name="why_create_2">{{$doc->why_create_2}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Create 3
                        </label>

                        <textarea class="col-sm-9" name="why_create_3">{{$doc->why_create_3}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Create 4
                        </label>

                        <textarea class="col-sm-9" name="why_create_4">{{$doc->why_create_4}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Create 5
                        </label>

                        <textarea class="col-sm-9" name="why_create_5">{{$doc->why_create_5}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Send 1
                        </label>

                        <textarea class="col-sm-9" name="why_send_1">{{$doc->why_send_1}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Send 2
                        </label>

                        <textarea class="col-sm-9" name="why_send_2">{{$doc->why_send_2}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Send 3
                        </label>

                        <textarea class="col-sm-9" name="why_send_3">{{$doc->why_send_3}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Send 4
                        </label>

                        <textarea class="col-sm-9" name="why_send_4">{{$doc->why_send_4}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Why Send 5
                        </label>

                        <textarea class="col-sm-9" name="why_send_5">{{$doc->why_send_5}}</textarea>

                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Temporary Action
                        </label>

                        <textarea class="col-sm-9" name="temporary_action">{{$doc->temporary_action}}</textarea>
                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Corrective Action
                        </label>

                        <textarea class="col-sm-9" name="corrective_action">{{$doc->corrective_action}}</textarea>
                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Preventive Action
                        </label>

                        <textarea class="col-sm-9" name="preventive_action">{{$doc->preventive_action}}</textarea>
                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Created By
                        </label>

                        <input type="text" class="col-sm-9" name="vendor_created_by" value="{{$doc->vendor_created_by}}" />
                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Checked By
                        </label>

                        <input type="text" class="col-sm-9" name="vendor_checked_by" value="{{$doc->vendor_checked_by}}"/>
                    </div>
                    <div class="form-group header-group-0" style="">
                        <label class='control-label col-sm-2'>
                            Approved By
                        </label>

                        <input type="text" class="col-sm-9" name="vendor_approved_by" value="{{$doc->vendor_approved_by}}"/>
                    </div>

                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group col-sm-2 control-label">
                        <input class="btn btn-primary" type="submit" value="Submit" />
                        <input class="btn btn-primary" type="button" value="Back" onclick="window.location.href='/admin/trouble_report'" />
                    </div>
                </form>
            </div>
        </div>

@endsection