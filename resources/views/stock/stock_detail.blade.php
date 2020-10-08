@extends('crudbooster::admin_template')
@section('content')

        <div class="panelRoot panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-star"></i>Stock Detail</strong>
            </div>

            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped col-md-12">
                            <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Batch No</th>
                                <th>Amount</th>
                                <th>UOM</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($details  as $detail)
                                <tr>
                                    <td>
                                        {{$detail->item_code}}
                                    </td>
                                    <td>
                                        {{$detail->item_name}}
                                    </td>
                                    <td>
                                        {{$detail->batch_no}}
                                    </td>
                                    <td>
                                        {{$detail->amount}}
                                    </td>
                                    <td>
                                        {{$detail->uom}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-info" onclick="window.history.back();">Back</button>
                </div>
            </div>
        </div>


@endsection