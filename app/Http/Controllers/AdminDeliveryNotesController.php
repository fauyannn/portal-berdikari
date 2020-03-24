<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminDeliveryNotesController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "delivery_notes";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Supplier","name"=>"supplier"];
			$this->col[] = ["label"=>"Delivery Date","name"=>"delivery_date"];
			// $this->col[] = ["label"=>"Supplier Delivery Note","name"=>"supplier_delivery_note"];
			$this->col[] = ["label"=>"Created at","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			
			$this->form[] = ['label'=>'Delivery Date','name'=>'delivery_date','type'=>'text','readonly'=>true];
			$this->form[] = ['label'=>'Supplier','name'=>'supplier','readonly'=>true,'type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-9'];
			// $this->form[] = ['label'=>'Supplier','name'=>'supplier','type'=>'hidden'];
			// $this->form[] = ['label'=>'Supplier','name'=>'supplier','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'Purchase Order','name'=>'purchase_order','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-9','dataenum'=>''];
			// $this->form[] = ['label'=>'Select items','name'=>'item_po','type'=>'hidden','width'=>'col-sm-9'];
			// $this->form[] = ['label'=>'Supplier Delivery Note','name'=>'supplier_delivery_note','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-9'];
			
			$columns[] 		= ['label'=>'Purchase Order','name'=>'purchase_order','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'Item Code','name'=>'item_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'Item Name','name'=>'item_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'QTY','name'=>'qty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'UOM','name'=>'uom','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'Rate','name'=>'rate','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'Amount','name'=>'amount','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			
			
			$this->form[] = ['label'=>'Items','columns'=>$columns,'name'=>'detail','type'=>'child','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10','table'=>'delivery_note_items','foreign_key'=>'delivery_note_id'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Purchase Order','name'=>'purchase_order','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Supplier','name'=>'supplier','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Supplier Delivery Note','name'=>'supplier_delivery_note','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Items','type'=>'child','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10','table'=>'delivery_note_items','foreign_key'=>'delivery_note_id'];
			# OLD END FORM

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
			// pr($_GET);
			// $this->script_js = '$("table#table-detail tr:first td:eq(1)").text("'.$_GET['idx'].'");';
			if($_GET['supplier']){
				$this->script_js .= '$("input[name=\"supplier\"]").val("'.$_GET['supplier'].'");';
			}
			if($_GET['delivery_date']){
				$this->script_js .= '$("input[name=\"delivery_date\"]").val("'.$_GET['delivery_date'].'");';
			}		


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
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
	        $this->load_js[] = asset("js/delivery-note.js");
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
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
			// pr($_POST,1);
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
			\DB::table('delivery_note_items')
			->where('delivery_note_id',null)
			->update(['delivery_note_id'=>$id]);
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


		public function getSupplier($id){
			$order_by 		= 'name desc'; //default
			$filters		= ["name"=>["like","%".@$_GET['q']."%"]];

			$filters = json_encode($filters);
			// pr($order_by);
			$doctype 		= 'Supplier';
			$start 			= $_GET['start']?:0;
			$page_length 	= $_GET['limit']?:10;
			$fields 		= "name, supplier_name";
			
			
			$_url 	= '/api/method/counting_machine.counting_machine.doctype.counting_machine.counting_machine.get_all_data';
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


			$response = $data->message->data;
			if($response){
				foreach($response as $key => $val){
					$response[$key]->id = $val->name;
					$response[$key]->text = $val->supplier_name;
				}
			}
			if(request()->ajax()){
				$response =  response()->json($response);
			}

			return $response;			
		}

		function getDeliverynotedetail($id){			
			$doctype 		= 'Delivery Note';
			$_url 	= '/api/method/counting_machine.counting_machine.doctype.counting_machine.counting_machine.get_data_detail';
			$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => $this->_token]]);
			$res 	= $client->request('GET', $this->_host.$_url, [
				'query' => [
					'doctype' => $doctype,
					'id' => $_GET['idx']
					]
			]);
			$data = json_decode($res->getBody()->getContents());
			$response = @$data->message;			
			if(request()->ajax()){
				$response =  response()->json($response);
			}
			// $response =  response()->json($response);
			return $response;	
		}

		public function getPurchaseorder($id){
			$doctype 		= 'Delivery Schedule';
			$start 			= 0;
			$page_length 	= 500;
			$order_by       = 'purchase_order desc';
			$fields 		= "purchase_order,item_code, item_name, qty,stock_uom,last_purchase_rate";
			
			$param 			= explode('__',$id);
			$supplier		= @$param[0];
			$delivery_date	= @$param[1];

			$filters['supplier'] = ['=',$supplier];
			$filters['delivery_date'] = ['=',$delivery_date];
			$filters = json_encode($filters);

			$_url 	= '/api/method/counting_machine.counting_machine.doctype.counting_machine.counting_machine.get_all_data';
			$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => $this->_token]]);
			$res 	= $client->request('GET', $this->_host.$_url, [
				'query' => [
					'doctype' => $doctype,
					'start' => $start,
					'page_length' => $page_length,
					'fields' => $fields,
					'order_by' => $order_by,
					'filters' => $filters,
					// 'group_by' => 'purchase_order'
					]
			]);
			$data = json_decode($res->getBody()->getContents());
			// $datas = [];
			// if(@count($data->message->data)){
			// 	foreach($data->message->data as $k => $val){
			// 		$datas[$val->item_code] = $val;
			// 	}
			// }
			// pr($datas);


			// if($_GET['item'] == 1){
			// 	$item_codes = [];
			// 	$_datas = $data->message->data;
			// 	if($_datas){
			// 		foreach($_datas as $k => $val){
			// 			$item_codes[] = $val->item_code;
			// 		}
			// 	}
			// 	$items = $this->getItems($item_codes);
			// 	// pr($items);
			// 	if(@count($items->message->data)){
			// 		foreach($items->message->data as $k => $val){
			// 			$datas[$val->item_code]->uom = $val->stock_uom;
			// 			$datas[$val->item_code]->rate = $val->last_purchase_rate;
			// 			$datas[$val->item_code]->amount = $val->last_purchase_rate * $datas[$val->item_code]->qty;
			// 		}
			// 	}
			// }

			// pr($datas);/.
			$response = @$data->message;			
			if(request()->ajax()){
				$response =  response()->json($response);
			}
			return $response;
		}


		public function getItems($item_codes){
			$doctype 		= 'Item';
			$start 			= 0;
			$page_length 	= 1000;
			$order_by       = 'item_code desc';
			$fields 		= "item_code,stock_uom,last_purchase_rate";
			
			$param 			= explode('__',$id);
			$supplier		= @$param[0];
			$delivery_date	= @$param[1];

			$filters['item_code'] = ['IN',implode(',',$item_codes)];
			$filters = json_encode($filters);
			

			$_url 	= '/api/method/counting_machine.counting_machine.doctype.counting_machine.counting_machine.get_all_data';
			$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => $this->_token]]);
			$res 	= $client->request('GET', $this->_host.$_url, [
				'query' => [
					'doctype' => $doctype,
					'start' => $start,
					'page_length' => $page_length,
					'fields' => $fields,
					'order_by' => $order_by,
					'filters' => $filters,
					// 'group_by' => 'purchase_order'
					]
			]);
			$data = json_decode($res->getBody()->getContents());
			return $data;
		}
	    //By the way, you can still create your own method in here... :) 


	}