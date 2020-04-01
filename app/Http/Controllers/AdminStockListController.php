<?php

namespace App\Http\Controllers;


use App\Models\Stock;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStockListController extends Controller
{
    private $_token;
    private $_host;

    public function __construct()
    {
        $this->_token = env('ERP_TOKEN');
        $this->_host = env('ERP_URL');

    }

    public function index()
    {
        $url 	= '/api/resource/Item?fields=["name", "item_name", "stock_uom"]';
        $guzzle = new Client(['headers' => ['Authorization' => 'token '.$this->_token]]);
        $response 	= $guzzle->request('GET', $this->_host.$url);
        $items = json_decode($response->getBody()->getContents());
        $stocks = Stock::get();
        return view('stock_list', [
            'items' => $items->data,
            'load_js' => [asset('/js/stocklist.js')],
            'stocks' => $stocks
        ]);
    }

    public function submit(Request $request)
    {
        $stocks = $request->input('data');

        DB::transaction(function () use ($stocks){
            foreach ($stocks as $stock){
                $rowStock = null;
                //load existing stock
                if($stock['id']){
                    $rowStock = Stock::where('id', $stock['id'])->first();
                }
                //delete stock without item selected
                if(!$stock['code']) {
                    if($rowStock) {
                        $rowStock->delete();
                    }
                    continue;
                }
                //New Stock
                if(!$rowStock){
                    $rowStock = new Stock;
                }
                $rowStock->item_code = $stock['code'];
                $rowStock->item_name = $stock['name'];
                $rowStock->qty = $stock['qty'];
                $rowStock->wip_qty = $stock['qty_wip'];
                $rowStock->finish_good_qty = $stock['qty_finished'];
                $rowStock->not_good_qty = $stock['qty_ng'];
                $rowStock->uom = (string)$stock['uom'];
                $rowStock->save();
            }
        });

        return redirect('admin/stocklist');
    }

}
