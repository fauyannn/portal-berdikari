@extends('crudbooster::admin_template')
@section('content')
    <script>
      var items = {!! collect($items)->toJson() !!};
      var count = {{count($raws)+count($wips)+count($others)}};
    </script>
    <style>
        .select2-selection {
            height:34px  !important;
        }

    </style>
    <div class="modal fade" id="qrScanModal" tabindex="-1" role="dialog" aria-labelledby="qrScanCenterTitle" aria-hidden="true">
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
                            <video id="video" hidden></video><canvas id="canvas" class="col-md-12"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form method="post" action="/admin/stocklist/submit">
        <div class="panelRoot panel panel-default">
            <table class="placeholder" hidden>
                <tbody class="formRow">
                <tr>
                    <td class="item_code">
                        <select name="raw[__PLACEHOLDER__][code]">
                            <option></option>
                            @foreach($items as $item)
                                <option value="{{$item->name}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        <input class="hiddenName" type="hidden" name="raw[__PLACEHOLDER__][name]"
                               value="{{$stock->item_name}}"/>
                    </td>
                    <td class="itemName">
                        {{$stock->item_name}}
                    </td>
                    <td>
                        <input name="raw[__PLACEHOLDER__][qty]" type="text" value="{{$stock->qty}}" size="12">
                    </td>
                    <td class="itemUom">
                        <input name="raw[__PLACEHOLDER__][uom]" type="text" value="{{$stock->uom}}" size="12">
                    </td>
                </tr>
                </tbody>
            </table>
            {{ csrf_field() }}
            <div class="panel-heading">
                <strong><i class="fa fa-star"></i>Stock Raw</strong>
                <button type="button" style="margin-left: 20px" id="scanQr" class="btn-info btn-sm btn">Scan QR</button>
            </div>
            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped col-md-12">
                            <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Qty</th>
                                <th>UOM</th>
                            </tr>
                            </thead>
                            <tbody class="stockListTableBody">
                            @foreach($raws as $key => $stock)
                                <tr>
                                    <td class="item_code" style="">
                                        <input type="hidden" name="raw[{{$key}}][id]" value="{{$stock->id}}"/>
                                        <select class="selectItem" name="raw[{{$key}}][code]">
                                            <option></option>
                                            @foreach($items as $item)
                                                <option value="{{$item->name}}"
                                                        @if ($item->name == $stock->item_code)selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        <input class="hiddenName" type="hidden" name="raw[{{$key}}][name]"
                                               value="{{$stock->item_name}}"/>
                                    </td>
                                    <td class="itemName">
                                        {{$stock->item_name}}
                                    </td>
                                    <td>
                                        <input name="raw[{{$key}}][qty]" type="text" value="{{$stock->qty}}" size="12">
                                    </td>
                                    <td class="itemUom">
                                        <input name="raw[{{$key}}][uom]" type="text" value="{{$stock->uom}}" size="12">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="add_new btn btn-info btn-sm">Add New</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="panelRoot panel panel-default">
            <table class="placeholder" hidden>
                <tbody class="formRow">
                <tr>
                    <td class="item_code">
                        <select class="" name="wip[__PLACEHOLDER__][code]">
                            <option></option>
                            @foreach($items as $item)
                                <option value="{{$item->name}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        <input class="hiddenName" type="hidden" name="wip[__PLACEHOLDER__][name]"
                               value=""/>
                    </td>
                    <td class="itemName">
                    </td>
                    <td>
                        <input name="wip[__PLACEHOLDER__][qty]" type="text" value="" size="12">
                    </td>
                    <td class="itemUom">
                        <input name="wip[__PLACEHOLDER__][uom]" type="text" value="" size="12">
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="panel-heading">
                <strong><i class="fa fa-star"></i>Stock WIP</strong>
            </div>
            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <div class="box-body" id="parent-form-area">
                    <div class="table-responsive">
                        <table id="table-detail" class="table table-striped col-md-12">
                            <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Qty</th>
                                <th>UOM</th>
                            </tr>
                            </thead>
                            <tbody class="stockListTableBody">
                            @foreach($wips as $key => $stock)
                                <tr>
                                    <td class="item_code">
                                        <input type="hidden" name="wip[{{$key}}][id]" value="{{$stock->id}}"/>
                                        <select class="selectItem" name="wip[{{$key}}][code]">
                                            <option></option>
                                            @foreach($items as $item)
                                                <option value="{{$item->name}}"
                                                        @if ($item->name == $stock->item_code)selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        <input class="hiddenName" type="hidden" name="wip[{{$key}}][name]"
                                               value="{{$stock->item_name}}"/>
                                    </td>
                                    <td class="itemName">
                                        {{$stock->item_name}}
                                    </td>
                                    <td>
                                        <input name="wip[{{$key}}][qty]" type="text" value="{{$stock->qty}}" size="12">
                                    </td>
                                    <td class="itemUom">
                                        <input name="wip[{{$key}}][uom]" type="text" value="{{$stock->uom}}" size="12">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="add_new btn btn-info btn-sm">Add New</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="panelRoot panel panel-default">
            <table class="placeholder" hidden>
                <tbody class="formRow">
                <tr>
                    <td class="item_code">
                        <select class="" name="other[__PLACEHOLDER__][code]">
                            <option></option>
                            @foreach($items as $item)
                                <option value="{{$item->name}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        <input class="hiddenName" type="hidden" name="other[__PLACEHOLDER__][name]"
                               value=""/>
                    </td>
                    <td class="itemName">
                    </td>
                    <td>
                        <input name="other[__PLACEHOLDER__][qty]" type="text" value="" size="12">
                    </td>
                    <td>
                        <input name="other[__PLACEHOLDER__][uom]" type="text" value="" size="12">
                    </td>
                    <td>
                        <input name="other[__PLACEHOLDER__][qty_ng]" type="text" value="" size="12">
                    </td>
                    <td>
                        <input name="other[__PLACEHOLDER__][uom_ng]" type="text" value="" size="12">
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="panel-heading">
                <strong><i class="fa fa-star"></i>Stock other</strong>
            </div>
            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <div class="box-body" id="parent-form-area">
                    <div class="table-responsive">
                        <table id="table-detail" class="table table-striped col-md-12">
                            <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>other Qty</th>
                                <th>UOM</th>
                                <th>NG Qty</th>
                                <th>UOM</th>
                            </tr>
                            </thead>
                            <tbody class="stockListTableBody">
                            @foreach($others as $key => $stock)
                                <tr>
                                    <td class="item_code">
                                        <input type="hidden" name="other[{{$key}}][id]" value="{{$stock->id}}"/>
                                        <select class="selectItem" name="other[{{$key}}][code]">
                                            <option></option>
                                            @foreach($items as $item)
                                                <option value="{{$item->name}}"
                                                        @if ($item->name == $stock->item_code)selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        <input class="hiddenName" type="hidden" name="other[{{$key}}][name]"
                                               value="{{$stock->item_name}}"/>
                                    </td>
                                    <td class="itemName">
                                        {{$stock->item_name}}
                                    </td>
                                    <td>
                                        <input name="other[{{$key}}][qty]" type="text" value="{{$stock->qty}}"
                                               size="12">
                                    </td>
                                    <td>
                                        <input name="other[{{$key}}][uom]" type="text" value="{{$stock->uom}}"
                                               size="12">
                                    </td>
                                    <td>
                                        <input name="other[{{$key}}][qty_ng]" type="text" value="{{$stock->qty}}"
                                               size="12">
                                    </td>
                                    <td>
                                        <input name="other[{{$key}}][uom_ng]" type="text" value="{{$stock->uom}}"
                                               size="12">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="add_new btn btn-info btn-sm">Add New</button>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="company" value="{{$company}}">
        @if(getUser()->id_cms_privileges <= 2)
            <a href="/admin/stocklist/select_company"><button type="button" class="btn bg-teal">Back</button></a>
        @endif
        <button id="save" class="btn bg-green">Save</button>
    </form>

@endsection