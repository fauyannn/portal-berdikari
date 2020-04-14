<?php

function pr($data, $exit=0){
    echo "<pre>";
        print_r($data);
    echo "</pre>"; 
    if($exit){
        exit;
    }
}

function formatMoney($data, $point = 0) {
    return number_format($data,$point,',','.');
}

function env_api(){
    $env = env('API_ERP', 'dev1');
    // pr($env);
    $data['local']['host'] = 'http://localhost:8080';
    $data['local']['token'] = 'token 335dfbc88231b23:af0f28f0fc2b9d5';

    $data['dev1']['host'] = 'http://dev1.hasgroup.id';
    $data['dev1']['token'] = 'token 3a425aa71a6f09e:3fa7e7ee7f8593f';

    $data['berdikari']['host'] = 'http://berdikari.indonesiaornamenteknologi.co.id';
    $data['berdikari']['token'] = 'token 3a425aa71a6f09e:e613955e1a62c06';

    return $data[$env];
}

function getUser($id=false){
    $user_id = ($id)? $id : CRUDBooster::myId();
    $user = DB::table('cms_users')->find($user_id);
    return $user;
}

function getItemPO(array $_filters){
    // pr($_filters);
    $doctype            = 'Purchase Order Item';
    $start 			    = 0;
    $page_length 	    = 999;
    $order_by           = 'name desc';
    $fields 		    = "parent,item_code,stock_uom,qty,rate,amount";

    $filters['parent']      = ['IN',implode(',',$_filters['po'])];
    $filters['item_code']   = ['IN',implode(',',$_filters['item_code'])];
    $filters                = json_encode($filters);

    $token = 'token ' . env('ERP_TOKEN');
    $host = env('ERP_URL');
    $_url 	= '/api/method/counting_machine.counting_machine.doctype.counting_machine.counting_machine.get_all_data';
    $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => $token]]);
    $res 	= $client->request('GET', $host.$_url, [
        'query' => [
            'doctype' => $doctype,
            'start' => $start,
            'page_length' => $page_length,
            'fields' => $fields,
            'order_by' => $order_by,
            'filters' => $filters,
            'group_by'=>''
            ]
    ]);			

    $data = json_decode($res->getBody()->getContents());
    $datas = $data->message->data;
    // pr($datas,1);
    $data_items = [];
    if(count($datas)){
        foreach($datas as $k => $val){
            $data_items[$val->parent][$val->item_code] = $val;
        }
    }
    return $data_items;
}