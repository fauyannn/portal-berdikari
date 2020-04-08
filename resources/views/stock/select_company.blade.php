@extends('crudbooster::admin_template')
@section('content')

        <div class="panelRoot panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-star"></i>Company List</strong>
            </div>
            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped col-md-12">
                            <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Select</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td>
                                        {{$company->company}}
                                    </td>
                                    <td>
                                        <a href="/admin/stocklist/company/{{$company->company}}"><button type="button" class="add_new btn btn-info btn-sm">Select</button></a>
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