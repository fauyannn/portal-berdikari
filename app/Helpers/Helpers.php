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