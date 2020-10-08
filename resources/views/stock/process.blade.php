@extends('crudbooster::admin_template')
@section('content')

        <div class="panelRoot panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-star"></i>Process Raw</strong>
            </div>
            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <div class="box-body">
                    <div class="table-responsive">
                        <form method="post" action="/admin/stocklist/submit_process">
                        <table class="table table-striped col-md-12">
                            <thead>
                                <tr>
                                    <td>Select BOM:</td>
                                    <td>
                                        <select id="selectBom" name="bom">
                                            <option></option>
                                            @foreach($boms as $bom)
                                                <option value="{{$bom->name}}">
                                                    {{$bom->item}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10">&nbsp</td>
                                </tr>
                                <tr>
                                    <td>Item Name</td>
                                    <td>Batch</td>
                                    <td>Batch Remaining Amount</td>
                                    <td>Material Amount</td>
                                    <td>UOM</td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="10">&nbsp</td>
                                </tr>

                                <tr>
                                    <td>FG:</td>
                                    <td><input type="text" name="fg" /></td>
                                </tr>
                                <tr>
                                    <td>NG:</td>
                                    <td><input type="text" name="ng" /></td>
                                </tr>
                            </tfoot>

                        </table>
                        {{ csrf_field() }}
                        <button type="button" id="processButton" class="btn btn-info btn-sm">Process</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection