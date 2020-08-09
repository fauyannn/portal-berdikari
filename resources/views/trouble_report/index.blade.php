@extends('crudbooster::admin_template')
@section('content')

        <div class="panelRoot panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-star"></i>Trouble Report List</strong>
            </div>
            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped col-md-12">
                            <thead>
                            <tr>
                                <th>Trouble Report Id</th>
                                <th>Select</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>
                                        {{$report->name}}
                                    </td>
                                    <td>
                                        <a href="/admin/trouble_report/{{str_replace("/", ".", $report->name)}}"><button type="button" class="add_new btn btn-info btn-sm">Select</button></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

@endsection