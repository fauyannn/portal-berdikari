<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class AdminTroubleReportController extends Controller
{

    private $doctype = "Trouble Report";

    public function __construct()
    {
        $this->_token = env('ERP_TOKEN');
        $this->_host = env('ERP_URL');

    }

    public function index(Request $request) {
        $filters = '[["Trouble Report","supplier","=","'.getUser()->company.'"]]';

        $url = '/api/resource/Trouble Report';



        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'token ' .$this->_token]]);
        $response = $client->request('GET', $this->_host.$url, [
            'query' => [
                'filters' => $filters
            ]
        ]);
        $reports = json_decode($response->getBody()->getContents());
        return view('trouble_report.index', [
            'reports' => $reports->data
        ]);
    }

    public function detail($name, Request $request) {
        $params = $request->all();
        if($params) {
            $params['docname'] = $name;
            $url = '/api/method/trouble_report.api.post_doc';
            $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'token ' .$this->_token]]);
            $response = $client->request('POST', $this->_host.$url, [
                'form_params' => $params
            ]);

            return back();
        }

        $url = '/api/method/trouble_report.api.get_doc';

        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'token ' .$this->_token]]);
        $response = $client->request('GET', $this->_host.$url, [
            'query' => [
                'replaced_name' => $name
            ]
        ]);
        $data = json_decode($response->getBody()->getContents());
        return view('trouble_report.detail', [
            'doc' => $data->message
        ]);
    }
}