@extends('crudbooster::admin_template')
@section('content')
    <script>
        var items = {!! collect($items)->toJson() !!};
        var count = {{count($stocks)}};
    </script>
<div class="panel panel-default">
    <table hidden>
        <tbody id="formRow">
        <tr>
            <td class="item_code">
                <select class="selectItem" name="data[__PLACEHOLDER__][code]">
                    <option></option>
                    @foreach($items as $item)
                        <option value="{{$item->name}}" @if ($item->name == $stock->item_code)selected @endif>{{$item->name}}</option>
                    @endforeach
                </select>
                <input class="hiddenName" type="hidden" name="data[__PLACEHOLDER__][name]" value="{{$stock->item_name}}" />
            </td>
            <td class="itemName">
                {{$stock->item_name}}
            </td>
            <td>
                <input name="data[__PLACEHOLDER__][qty]" type="text" value="{{$stock->qty}}" size="12">
            </td>
            <td>
                <input name="data[__PLACEHOLDER__][qty_wip]" type="text" value="{{$stock->wip_qty}}" size="12">
            </td>
            <td>
                <input name="data[__PLACEHOLDER__][qty_finished]" type="text" value="{{$stock->finish_good_qty}}" size="12">
            </td>
            <td>
                <input name="data[__PLACEHOLDER__][qty_ng]" type="text" value="{{$stock->not_good_qty}}" size="12">
            </td>
            <td class="itemUom">
                <input name="data[__PLACEHOLDER__][uom]" type="text" value="{{$stock->uom}}" size="12">
            </td>
        </tr>
        </tbody>
    </table>
    <div class="panel-heading">
        <strong><i class="fa fa-star"></i>Stock</strong>
    </div>
    <form method="post" action="/admin/stocklist/submit">
        {{ csrf_field() }}
        <div class="panel-body" style="padding:20px 0px 0px 0px">
            <div class="box-body" id="parent-form-area">
                <div class="table-responsive">
                    <table id="table-detail" class="table table-striped col-md-12">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Qty</th>
                                <th>Qty WIP</th>
                                <th>Qty Finished</th>
                                <th>Qty NG</th>
                                <th>UOM</th>
                            </tr>
                        </thead>
                        <tbody id="stockListTableBody" >
                            @foreach($stocks as $key => $stock)
                            <tr>
                                <td class="item_code">
                                    <input type="hidden" name="data[{{$key}}][id]" value="{{$stock->id}}" />
                                    <select class="selectItem" name="data[{{$key}}][code]">
                                        <option></option>
                                        @foreach($items as $item)
                                            <option value="{{$item->name}}" @if ($item->name == $stock->item_code)selected @endif>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <input class="hiddenName" type="hidden" name="data[{{$key}}][name]" value="{{$stock->item_name}}" />
                                </td>
                                <td class="itemName">
                                    {{$stock->item_name}}
                                </td>
                                <td>
                                    <input name="data[{{$key}}][qty]" type="text" value="{{$stock->qty}}" size="12">
                                </td>
                                <td>
                                    <input name="data[{{$key}}][qty_wip]" type="text" value="{{$stock->wip_qty}}" size="12">
                                </td>
                                <td>
                                    <input name="data[{{$key}}][qty_finished]" type="text" value="{{$stock->finish_good_qty}}" size="12">
                                </td>
                                <td>
                                    <input name="data[{{$key}}][qty_ng]" type="text" value="{{$stock->not_good_qty}}" size="12">
                                </td>
                                <td class="itemUom">
                                    <input name="data[{{$key}}][uom]" type="text" value="{{$stock->uom}}" size="12">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" id="add_new" class="btn btn-info btn-sm">Add New</button>
                <button id="save" class="btn btn-default btn-sm">Save</button>
            </div>
        </div>
    </form>
</div>

@endsection