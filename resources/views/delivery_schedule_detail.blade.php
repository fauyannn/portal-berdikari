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
                            <td>Type</td>
                            <td>{{ $data->type }}</td>
                        </tr>
                        <tr>
                            <td>Number</td>
                            <td>{{ ($data->sales_order ?:$data->purchase_order) }}</td>
                        </tr>
                        <tr>
                            <td>Customer</td>
                            <td>{{ $data->customer }}</td>
                        </tr>
                        <tr>
                            <td>Customer's Purchase Order</td>
                            <td>{{ $data->po_no }}</td>
                        </tr>
                        <tr>
                            <td>Item Code</td>
                            <td>{{ $data->item_code }}</td>
                        </tr>
                        <tr>
                            <td>Item Name</td>
                            <td>{{ $data->name }}</td>
                        </tr>
                        <tr>
                            <td>Quantity</td>
                            <td>{{ formatMoney($data->qty) }}</td>
                        </tr>
                        <tr>
                            <td>Delivery Date</td>
                            <td>{{ $data->delivery_date }}</td>
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