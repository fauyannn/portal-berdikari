<?php

namespace App\Http\Controllers;


use App\Models\CmsUser;
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

    public function index($company = null)
    {
        $user = getUser();
        if($user->id_cms_privileges > 2) {
            $company = getUser()->company;
        } else if ($company === null) {
            return redirect('/admin/stocklist/select_company');
        }
        $url = '/api/resource/Item?fields=["name", "item_name", "stock_uom"]';
        $guzzle = new Client(['headers' => ['Authorization' => 'token ' . $this->_token]]);
        $response = $guzzle->request('GET', $this->_host . $url);
        $items = json_decode($response->getBody()->getContents());


        $raws = Stock::where('supplier', $company)
            ->where('type', 1)->get();
        $wips = Stock::where('supplier', $company)
            ->where('type', 2)->get();

        $others = Stock::where('supplier', $company)
            ->where('type', 3)->get();

        return view('stock.stock_list', [
            'items' => $items->data,
            'load_js' => [
                asset('/vendor/crudbooster/assets/select2/dist/js/select2.full.min.js'),
                asset("js/jsqr.js"),
                asset("js/qr_reader.js"),
                asset('/js/stocklist.js')
            ],
            'load_css' => [asset('vendor/crudbooster/assets/select2/dist/css/select2.min.css')],
            'raws' => $raws,
            'wips' => $wips,
            'others' => $others,
            'company' => $company
        ]);
    }


    public function submit(Request $request)
    {
        $company = null;
        if(getUser()->id_cms_privileges > 2) {
            $company = getUser()->company;
        } else {
            $company = $request->input('company');
        }
        $this->saveStocks($request->input('raw'), 1, $company);
        $this->saveStocks($request->input('wip'), 2, $company);
        $this->saveStocks($request->input('other'), 3, $company);

        if(getUser()->id_cms_privileges > 2) {
            return redirect('admin/stocklist');
        }
        return redirect('admin/stocklist/company/'.$company);

    }

    private function saveStocks($stocks, $type, $company)
    {
        DB::transaction(function () use ($stocks, $type, $company) {
            foreach ($stocks as $stock) {
                $rowStock = null;
                //load existing stock
                if ($stock['id']) {
                    $rowStock = Stock::where('id', $stock['id'])->first();
                }
                //delete stock without item selected
                if (!$stock['code']) {
                    if ($rowStock) {
                        $rowStock->delete();
                    }
                    continue;
                }
                //New Stock
                if (!$rowStock) {
                    $rowStock = new Stock;
                }
                $rowStock->type = $type;
                $rowStock->supplier = $company;
                $rowStock->item_code = $stock['code'];
                $rowStock->item_name = $stock['name'];
                $rowStock->qty = $stock['qty'];
                $rowStock->uom = (string)$stock['uom'];
                $rowStock->not_good_qty = $stock['qty_ng'];
                $rowStock->not_good_uom = (string)$stock['uom_ng'];
                $rowStock->save();
            }
        });
    }

    public function processQr(Request $request)
    {
        $url = '/api/resource/Stock Entry/' . $request->input('name');
        $guzzle = new Client(['headers' => ['Authorization' => 'token ' . $this->_token]]);
        $response = $guzzle->request('GET', $this->_host . $url);
        $stockEntry = json_decode($response->getBody()->getContents());
        $items = $stockEntry->data->items;
        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                $rowStock = Stock::where('item_code', $item->item_code)->where('type', 1)->first();
                if (!$rowStock) {
                    $rowStock = new Stock;
                    $rowStock->item_code = $item->item_code;
                    $rowStock->item_name = $item->item_name;
                    $rowStock->uom = $item->stock_uom;
                    $rowStock->type = 1;
                }
                $rowStock->qty += $item->transfer_qty;
                $rowStock->save();
            }
        });

        return 'success';

    }

    public function selectCompany()
    {
        $companies = CmsUser::select('company')->groupBy('company')->get();
        return view('stock.select_company', [
            'companies' => $companies
        ]);
    }

}
