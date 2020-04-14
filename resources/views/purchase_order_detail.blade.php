@extends('crudbooster::admin_template')
@section('content')
<style>
    .right{float:right;}
</style>
<p>
    <a title="Return" href="{{ CRUDBooster::mainpath('') }}">
        <i class="fa fa-chevron-circle-left "></i>
        &nbsp; Back To List Data Traking
    </a>
</p>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><i class="fa fa-star"></i> Purchase Order Detail</strong>
    </div>
    <div class="panel-body" style="padding:20px 0px 0px 0px">
        <div class="box-body" id="parent-form-area">
            <div class="table-responsive">
                <table id="table-detail" class="table table-striped">
                    <tbody>
                        <tr>
                            <td>Supplier</td>
                            <td>{{ $data->supplier }}</td>
                        </tr>
                        <tr>
                            <td>Purchase Order Number</td>
                            <td>{{ $data->name }}</td>
                        </tr>
                        <tr>
                            <td>Purchase Order Date</td>
                            <td>{{ $data->transaction_date }}</td>
                        </tr>
                        <tr>
                            <td>Total Amount</td>
                            <td>Rp {{ formatMoney($data->grand_total) }}</td>
                        </tr>
                        <tr class="">
                            <td colspan="2">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <i class="fa fa-bars"></i> Items
                                    </div>
                                    <div class="panel-body">
                                        <table id="table-detail" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Item Code</th>
                                                    <th>Item Name</th>
                                                    <th>Reqd By Date</th>
                                                    <th>Quantity</th>
                                                    <th>UOM</th>
                                                    <th>Rate</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($data->items)
                                                    @foreach($data->items as $key => $val)
                                                        <tr>
                                                            <td class="">
                                                                <span class="td-label">{{ $val->item_code }}</span>
                                                            </td>
                                                            <td class="">
                                                                <span class="td-label">{{ $val->item_name }}</span>
                                                            </td>
                                                            <td class="">
                                                                <span class="td-label">{{ $val->schedule_date }}</span>
                                                            </td>
                                                            <td class="">
                                                                <span class="td-label right">{{ formatMoney($val->qty) }}</span>
                                                            </td>
                                                            <td class="">
                                                                <span class="td-label">{{ $val->uom }}</span>
                                                            </td>
                                                            <td class="">
                                                                <span class="td-label right">Rp {{ formatMoney($val->rate) }}</span>
                                                            </td>
                                                            <td class="">
                                                                <span class="td-label right">Rp {{ formatMoney($val->qty * $val->rate) }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>   
            
            <div class="box-footer" style="background: #F5F5F5">
                <div class="form-group">
                    <label class="control-label col-sm-2"></label>
                    <div class="col-sm-10">
                    </div>
                </div>
            </div><!-- /.box-footer-->
        </div>
    </div>
</div> 

@endsection