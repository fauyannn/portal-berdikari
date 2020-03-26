<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	// use Guzzle;

	class AdminPurchaseOrderController extends \crocodicstudio\crudbooster\controllers\CBController {

		private $_datas = [];
		private $_host;
		private $_token;

		function __construct()
		{
			$env = env_api();
			$this->_host = $env['host'];
			$this->_token = $env['token'];
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "purchase_orders";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Purchase Order Number","name"=>"purchase_order_number"];
			$this->col[] = ["label"=>"Purchase Order Date","name"=>"purchase_order_date"];
			$this->col[] = ["label"=>"Total Amount","name"=>"total_amount"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Purchase Order Number','name'=>'purchase_order_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Purchase Order Number','name'=>'purchase_order_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			# OLD END FORM

			// pr($this,1);

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
			$this->script_js = null;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
			$this->pre_index_html = $this->generate_listdata();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
			$this->load_js[] = asset("js/mypo.js");
			$this->load_js[] = asset("js/myglobal.js");
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
			//Your code here			
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
			//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

	    //By the way, you can still create your own method in here... :) 


		public function getQuery($id){
			// $_GET			= $_GET;
			$this->table = "purchase_orders";
			// pr($_GET);
			
			$order_by 		= 'name desc'; //default
			$filters		= [];
			$arrfield = [
				$this->table.'.purchase_order_number'=>'name',
				$this->table.'.purchase_order_date' => 'transaction_date',
				$this->table.'.total_amount' => 'grand_total'
				];
				
			if($_GET['filter_column']){
				foreach($_GET['filter_column'] as $key => $val){
					if(@$val['sorting']){
						$order_by = @$arrfield[$key].' '.$val['sorting'];
					}
					if(@$val['value']){
						if($val['type']=='like'){
							$filters[@$arrfield[$key]] = [$val['type'],'%'.$val['value'].'%'];
						} else {
							$filters[@$arrfield[$key]] = [$val['type'],$val['value']];
						}
					}
				}
			}
			if(!CRUDBooster::isSuperadmin()){
				$user = getUser();
				$supplier = $user->company;
				$filters['supplier'] = ['=',$supplier];
			}		
			$filters = json_encode($filters);
			// pr($filters);
			$doctype 		= 'Purchase Order';
			$start 			= $_GET['start']?:0;
			$page_length 	= $_GET['limit']?:20;
			$fields 		= "name, transaction_date, grand_total";
			
			
			$_url 	= '/api/method/counting_machine.counting_machine.doctype.counting_machine.counting_machine.get_all_data'.$params;
			$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => $this->_token]]);
			$res 	= $client->request('GET', $this->_host.$_url, [
				'query' => [
					'doctype' => $doctype,
					'start' => $start,
					'page_length' => $page_length,
					'fields' => $fields,
					'order_by' => $order_by,
					'filters' => $filters
					]
			]);
			$data = json_decode($res->getBody()->getContents());
			$data->message->modul_url = CRUDBooster::mainpath('');
			$data->message->total_rows = $page_length;
			$data->message->get_start = $_GET['start'];

			 
			// $url_full = str_replace('?','&',url()->full());
			

			$response = $data;
			if(request()->ajax()){
				$response =  response()->json($data);
			}
			else {
				$url_full = url()->full(); 
				$url_full = str_replace('?','&',$url_full);
				$url_full = str_replace('/purchase_orders','/purchase_orders/query/1?e='.md5(0),$url_full);
				$data->message->url_full = $url_full;
				// pr($_GET);
				// pr($data);				
			}

			return $response;			
		}

		private function generate_listdata(){
			// pr($_GET);
			$data = $this->getQuery($_GET);

			$datas = $data->message->data;
			// pr($data->message);			

			$total_rows = 'Total rows : '.$data->message->total_rows.' of '.$data->message->total_data;
			
			$datalist = "<table id='temp' style='display:none;'>";
			if($datas){	
				foreach($datas as $key => $val){
					$id = $val->name;
					$return_url = '?return_url=http%3A%2F%2F127.0.0.1%3A8000%2Fadmin%2Fpurchase_order';
					$url = CRUDBooster::mainpath('show/'.$id);
										
					$datalist .= "<tr>
							<td>".$val->name."</td>
							<td>".$val->transaction_date."</td>
							<td class='pull-right'>Rp ".formatMoney($val->grand_total)."</td>
							<td><div class='button_action pull-right'><a class='btn btn-xs btn-primary btn-detail' title='Detail Data' href='".$url."'><i class='fa fa-eye'></i></a></div></td>
						</tr>";
				}	
			}
			$datalist .= "</table>";
			if($data->message->total_rows < $data->message->total_data){
				$url_full = $data->message->url_full;
				$datalist .= '<div id="loadmore" style="display:none;">
						<div style="text-align:center;">
							<a id="url-loadmore" class="btn btn-xs btn-primary" href="javascript:void(0)" data-href="'.$url_full.'" data-limit="'.$data->message->total_rows.'" data-totaldata="'.$data->message->total_data.'">load more</a>
						</div>
						</div>';

				// $dataArray = json_encode($data->message->data);
				// $datalist .= $dataArray->render();
			}
			
			$datalist .= '<div id="total_rows" style="display:none;">'.$total_rows.'</div>';
			return $datalist;
		}

		public function getShow($id){
			$items = [];
			$_url = '/api/resource/Purchase Order/'.$id;
			$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => $this->_token]]);
			$res = $client->request('GET', $this->_host.$_url);
			$data = json_decode($res->getBody()->getContents());
			$data = $data->data;
			if(request()->ajax()){
				return response()->json($data->items);
			}
			return view('purchase_order_detail',compact('data'));
		}

	}