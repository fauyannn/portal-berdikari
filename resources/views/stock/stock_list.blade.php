@extends('crudbooster::admin_template')
@section('content')


    <div class="modal fade" id="qrScanModal" tabindex="-1" role="dialog" aria-labelledby="qrScanCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrScanTitle">Scan QR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <video id="video" hidden></video>
                            <canvas id="canvas" class="col-md-12"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panelRoot panel panel-default">
        <div class="panel-heading">
            <strong><i class="fa fa-star"></i>Raw List</strong>
            <button type="button" style="margin-left: 20px" id="scanQr" class="btn-info btn-sm btn"><i class="fa fa-camera" style="width: 20px"></i>Scan QR</button>
            <input type="hidden" id="company" name="company" value="{{$company}}">
        </div>

        <div class="panel-body" style="padding:20px 0px 0px 0px">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped col-md-12">
                        <thead>
                        <tr>
                            <th width="40%">Item</th>
                            <th width="20%">Stock</th>
                            <th width="20%">UOM</th>
                            <th width="20%">Select</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($raws  as $item)
                            <tr>
                                <td>
                                    {{$item->item_name}}
                                </td>
                                <td>
                                    {{$item->total_amount}}
                                </td>
                                <td>
                                    {{$item->uom}}
                                </td>
                                <td>
                                    <a href="/admin/stocklist/detail/{{$item->id}}">
                                        <button class="btn btn-info">Detail</button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="/admin/stocklist/process">
                    <button class="btn btn-info">Process Raw</button>
                </a>
            </div>
        </div>
    </div>
    <div class="panelRoot panel panel-default">
        <div class="panel-heading">
            <strong><i class="fa fa-star"></i>FG List</strong>
        </div>

        <div class="panel-body" style="padding:20px 0px 0px 0px">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped col-md-12">
                        <thead>
                        <tr>
                            <th width="40%">Item</th>
                            <th width="20%">Stock</th>
                            <th width="20%">UOM</th>
                            <th width="20%">Select</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fgs  as $item)
                            <tr>
                                <td>
                                    {{$item->item_name}}
                                </td>
                                <td>
                                    {{$item->total_amount}}
                                </td>
                                <td>
                                    {{$item->uom}}
                                </td>
                                <td>
                                    <a href="/admin/stocklist/detail/{{$item->id}}">
                                        <button class="btn btn-info">Detail</button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="panelRoot panel panel-default">
        <div class="panel-heading">
            <strong><i class="fa fa-star"></i>NG List</strong>
        </div>

        <div class="panel-body" style="padding:20px 0px 0px 0px">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped col-md-12">
                        <thead>
                        <tr>
                            <th width="40%">Item</th>
                            <th width="20%">Stock</th>
                            <th width="20%">UOM</th>
                            <th width="20%">Select</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ngs  as $item)
                            <tr>
                                <td>
                                    {{$item->item_name}}
                                </td>
                                <td>
                                    {{$item->total_amount}}
                                </td>
                                <td>
                                    {{$item->uom}}
                                </td>
                                <td>
                                    <a href="/admin/stocklist/detail/{{$item->id}}">
                                        <button class="btn btn-info">Detail</button>
                                    </a>
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