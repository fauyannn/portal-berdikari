<?php

namespace App\Http\Controllers;


use App\Models\CmsUser;
use App\Models\Stock\Stock;
use App\Models\Stock\StockDetail;
use App\Models\Stock\StockLedger;
use App\Models\Stock\StockOrigin;
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
        $raws = Stock::select('stock.item_code', 'stock.item_name', DB::raw('SUM(amount) as total_amount'))
            ->where('supplier', $company)
            ->where('stock_detail.type', 'raw' )
            ->join('stock_detail','stock_detail.stock_item_id', '=', 'stock.id')
            ->groupBy('stock.id','stock.item_code', 'stock.item_name')
            ->get();

        $fgs = Stock::select('stock.item_code', 'stock.item_name', DB::raw('SUM(amount) as total_amount'))
            ->where('supplier', $company)
            ->where('stock_detail.type', 'fg' )
            ->join('stock_detail','stock_detail.stock_item_id', '=', 'stock.id')
            ->groupBy('stock.id','stock.item_code', 'stock.item_name')
            ->get();

        $ngs = Stock::select('stock.item_code', 'stock.item_name', DB::raw('SUM(amount) as total_amount'))
            ->where('supplier', $company)
            ->where('stock_detail.type', 'ng' )
            ->join('stock_detail','stock_detail.stock_item_id', '=', 'stock.id')
            ->groupBy('stock.id','stock.item_code', 'stock.item_name')
            ->get();



//        $raws = Stock::where('supplier', $company)
//            ->where('type', 1)->get();
//        $wips = Stock::where('supplier', $company)
//            ->where('type', 2)->get();
//
//        $fgs = Stock::where('supplier', $company)
//            ->where('type', 3)->get();
//
//        $ngs = Stock::where('supplier', $company)
//            ->where('type', 4)->get();

        return view('stock.stock_list', [
            'raws' => $raws,
            'fgs' => $fgs,
            'ngs' => $ngs,
            'load_js' => [
                asset('/vendor/crudbooster/assets/select2/dist/js/select2.full.min.js'),
                asset("js/jsqr.js"),
                asset("js/qr_reader.js"),
                asset('/js/stock/stocklist.js')
            ],
            'load_css' => [asset('vendor/crudbooster/assets/select2/dist/css/select2.min.css')],
            'company' => $company
        ]);
    }


//    public function submit(Request $request)
//    {
//        $company = null;
//        if(getUser()->id_cms_privileges > 2) {
//            $company = getUser()->company;
//        } else {
//            $company = $request->input('company');
//        }
//        $this->saveStocks($request->input('raw'), 1, $company);
//        $this->saveStocks($request->input('wip'), 2, $company);
//        $this->saveStocks($request->input('fg'), 3, $company);
//        $this->saveStocks($request->input('ng'), 4, $company);
//
//        if(getUser()->id_cms_privileges > 2) {
//            return redirect('admin/stocklist');
//        }
//        return redirect('admin/stocklist/company/'.$company);
//
//    }

//    private function saveStocks($stocks, $type, $company)
//    {
//        DB::transaction(function () use ($stocks, $type, $company) {
//            foreach ($stocks as $stock) {
//                $rowStock = null;
//                //load existing stock
//                if ($stock['id']) {
//                    $rowStock = Stock::where('id', $stock['id'])->first();
//                }
//                //delete stock without item selected
//                if (!$stock['code']) {
//                    if ($rowStock) {
//                        $rowStock->delete();
//                    }
//                    continue;
//                }
//                //New Stock
//                if (!$rowStock) {
//                    $rowStock = new Stock;
//                }
//                $rowStock->type = $type;
//                $rowStock->supplier = $company;
//                $rowStock->item_code = $stock['code'];
//                $rowStock->item_name = $stock['name'];
//                $rowStock->qty = $stock['qty'];
//                $rowStock->uom = (string)$stock['uom'];
//                $rowStock->not_good_qty = $stock['qty_ng'];
//                $rowStock->not_good_uom = (string)$stock['uom_ng'];
//                $rowStock->save();
//            }
//        });
//    }

    public function processQr(Request $request, $company = null)
    {
        $url = '/api/resource/Stock Entry/' . $request->input('name');
        $guzzle = new Client(['headers' => ['Authorization' => 'token ' . $this->_token]]);
        $response = $guzzle->request('GET', $this->_host . $url);
        $stockEntry = json_decode($response->getBody()->getContents());
        $items = $stockEntry->data->items;
        DB::transaction(function () use ($request, $company, $items) {
            foreach ($items as $item) {
                $stockQuery = Stock::where('item_code', $item->item_code);
                $stockQuery->where('supplier', $company);

                $rowStock = $stockQuery->first();
                if (!$rowStock) {
                    $rowStock = new Stock();
                    $rowStock->supplier = $company;
                    $rowStock->item_code = $item->item_code;
                    $rowStock->item_name = $item->item_name;
                    $rowStock->uom = $item->stock_uom;
                    $rowStock->save();
                }

                $stockDetail = new StockDetail();
                $stockDetail->stock_item_id = $rowStock->id;
                $stockDetail->type = 'raw';
                $stockDetail->original_amount = $item->qty;
                $stockDetail->amount = $item->qty;
                $stockDetail->batch_no = $item->batch_no;
                $stockDetail->erp_stock_entry = $request->input('name');
                $stockDetail->save();

                $stockLedger = new StockLedger();
                $stockLedger->stock_detail_id = $stockDetail->id;
                $stockLedger->amount = $item->qty;
                $stockLedger->save();


            }
        });

        return 'success';

    }

    public function stockDetail($item) {

        $stockQuery = Stock::join('stock_detail','stock_detail.stock_item_id', '=', 'stock.id')
        ->where('stock.id', $item);

        $user = getUser();
        if($user->id_cms_privileges > 2) {
            $stockQuery->where('supplier', $user->company);
        }

        $details = $stockQuery->get();

        return view('stock.stock_detail', [
            'details' => $details
        ]);

    }

    public function selectCompany()
    {
        $companies = CmsUser::select('company')->groupBy('company')->get();
        return view('stock.select_company', [
            'companies' => $companies
        ]);
    }

    public function processRaw()
    {
        $url = '/api/resource/BOM?fields=["name", "item"]';
        $guzzle = new Client(['headers' => ['Authorization' => 'token ' . $this->_token]]);
        $response = $guzzle->request('GET', $this->_host . $url);
        $boms = json_decode($response->getBody()->getContents());


        return view('stock.process', [
            'boms' => $boms->data,
            'load_js' => [
                asset('/js/stock/process.js')
            ],
        ]);
    }

    public function submitProcess(Request $request){
        $fg = (float) $request->input('fg');
        $ng = (float) $request->input('ng');

        $bom = $this->getErpBOM($request->input('bom'));
        $materials = $request->input('material');

        //cek material total fg + ng cukup
        $total = $fg + $ng;
        $stocks = [];
        foreach ($bom->items as $item) {
            $batchId = (int) $materials[$item->item_code];
            $stockQuery = StockDetail::select(['*','stock_detail.id as stock_detail_id'])
                ->join('stock','stock_detail.stock_item_id', '=', 'stock.id');
            $stockQuery->where('supplier', getUser()->company)
                ->where('stock_detail.id', $batchId);
            $stock = $stockQuery->first();
            if($item->qty * $total > $stock->amount || !$stock) {
                return 'invalid';
            }
            $stock->amount = $stock->amount-$item->qty * $total;
            $stocks[] = $stock;
        }


        DB::transaction(function () use ($stocks, $bom, $fg, $ng) {
            $batches = [];
            foreach ($stocks as $stock) {
                $stockDetail = StockDetail::find($stock->stock_detail_id);
                $stockDetail->amount = $stock->amount;
                if($stockDetail->amount <= 0) {
                    $stockDetail->status = 1; //buat nandain kalo habis aja
                }
                $stockDetail->save();
                $batches[] = $stock->batch_no;
            }

            $stockQuery = Stock::where('supplier', getUser()->company)
            ->where('item_code', $bom->item);

            $stock = $stockQuery->first();
            if(!$stock){
                $stock = new Stock();
                $stock->supplier = getUser()->company;
                $stock->item_code = $bom->item;
                $stock->item_name = $bom->item_name;
                $stock->uom = $bom->uom;
                $stock->save();
            }

            if($fg > 0) {
                $stockDetail = new StockDetail();
                $stockDetail->stock_item_id = $stock->id;
                $stockDetail->type = "fg";
                $stockDetail->original_amount = $fg;
                $stockDetail->amount = $fg;
                $stockDetail->batch_no = implode(', ',$batches);
                $stockDetail->status = 1;

                $stockDetail->save();

                foreach ($stocks as $stockRaw) {
                    $origin = new StockOrigin();
                    $origin->goods_detail_id =  $stockDetail->id;
                    $origin->raw_detail_id =  $stockRaw->stock_detail_id;
                    $origin->save();
                }

            }

            if($ng > 0) {
                $stockDetail = new StockDetail();
                $stockDetail->stock_item_id = $stock->id;
                $stockDetail->type = "ng";
                $stockDetail->original_amount = $ng;
                $stockDetail->amount = $ng;
                $stockDetail->batch_no = implode(', ',$batches);
                $stockDetail->status = 1;
                $stockDetail->save();

                foreach ($stocks as $stockRaw) {
                    $origin = new StockOrigin();
                    $origin->goods_detail_id =  $stockDetail->id;
                    $origin->raw_detail_id =  $stockRaw->stock_detail_id;
                    $origin->save();
                }
            }


        });

        return 'success';




    }

    public function bomDetail($name)
    {
        $items = $this->getErpBOM($name)->items;

        foreach($items as &$item) {
            $stockQuery = Stock::select('stock_detail.*')
            ->join('stock_detail','stock_detail.stock_item_id', '=', 'stock.id')
            ->where('item_code', $item->item_name)
            ->where('supplier', getUser()->company)
            ->where('type', 'raw')
            ->where('status', 0);

            $item->batches = $stockQuery->get();
        }


        return response()->json($items);

    }

    private function getErpBOM($name) {
        $url = '/api/resource/BOM/' . $name;
        $guzzle = new Client(['headers' => ['Authorization' => 'token ' . $this->_token]]);
        $response = $guzzle->request('GET', $this->_host . $url);
        $bomJson = json_decode($response->getBody()->getContents());
        return $bomJson->data;
    }

}
