<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminPurchaseInvoicesController extends \crocodicstudio\crudbooster\controllers\CBController {

		private $_datas = [];
		private $_host;
		private $_token;
		private $my_company;

		function __construct()
		{
			$env = env_api();
			$this->_host = $env['host'];
			$this->_token = $env['token'];
			$this->my_company = env('COMPANY_NAME','BERDIKARI, CV');
		}
	    public function cbInit() {
			$user = getUser();
			$supplier = $user->company;
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
			$this->table = "purchase_invoices";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Supplier","name"=>"supplier"];
			$this->col[] = ["label"=>"Supplier Invoice Number","name"=>"supplier_invoice_number"];
			$this->col[] = ["label"=>"Due Date","name"=>"due_date"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'id','name'=>'id','type'=>'hidden','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'status','name'=>'status','type'=>'hidden','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'Supplier','name'=>'supplier','type'=>'text','validation'=>'required','width'=>'col-sm-9','readonly'=>'1','value'=>$supplier];
			// $this->form[] = ['label'=>'supplier_invoice_number','name'=>'supplier_invoice_number','type'=>'hidden','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'Supplier Invoice Number','name'=>'supplier_invoice_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'Due Date','name'=>'due_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-9'];
			// $this->form[] = ['label'=>'purchase_order_number','name'=>'purchase_order_number','type'=>'hidden','width'=>'col-sm-9'];
			// $this->form[] = ['label'=>'Purchase Order Number','name'=>'purchase_order_number','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'File Invoice','name'=>'file_invoice','type'=>'upload','validation'=>'required|mimes:jpg,jpeg,png,pdf,doc,docx|max:2000','upload_encrypt'=>false,'width'=>'col-sm-9'];
			
			$columns[]		= ['label'=>'PO Number','name'=>'purchase_order_number','type'=>'text','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'Item Code','name'=>'item_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'Item Name','name'=>'item_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'QTY','name'=>'qty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'UOM','name'=>'uom','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'Rate','name'=>'rate','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$columns[] 		= ['label'=>'Amount','name'=>'amount','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			
			$this->form[] = ['label'=>'Items','columns'=>$columns,'name'=>'detail','type'=>'child','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10','table'=>'purchase_invoice_items','foreign_key'=>'id_purchase_invoice'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//
			//$this->form[] = ['label'=>'supplier_invoice_number','name'=>'supplier_invoice_number','type'=>'hidden'];
			//$this->form[] = ['label'=>'Supplier Invoice Number','name'=>'supplier_invoice_number','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'Supplier','name'=>'supplier','type'=>'text','validation'=>'required','width'=>'col-sm-9','readonly'=>true];
			//$this->form[] = ['label'=>'Supplier Date','name'=>'supplier_date','type'=>'text','validation'=>'required|date','width'=>'col-sm-9','readonly'=>true];
			//$this->form[] = ['label'=>'purchase_order_number','name'=>'purchase_order_number','type'=>'hidden'];
			//$this->form[] = ['label'=>'Purchase Order Number','name'=>'purchase_order_number','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-9'];
			//
			//$columns[] 		= ['label'=>'Item Code','name'=>'item_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$columns[] 		= ['label'=>'Item Name','name'=>'item_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$columns[] 		= ['label'=>'QTY','name'=>'qty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$columns[] 		= ['label'=>'UOM','name'=>'uom','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$columns[] 		= ['label'=>'Rate','name'=>'rate','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$columns[] 		= ['label'=>'Amount','name'=>'amount','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//
			//$this->form[] = ['label'=>'Items','columns'=>$columns,'name'=>'detail','type'=>'child','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-9','table'=>'purchase_invoice_items','foreign_key'=>'id_purchase_invoice'];
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
			// $this->table_row_color[] = ['condition'=>"[status] == 'draft'","color"=>"danger"];
			// $this->table_row_color[] = ['condition'=>"[status] == 'submited'","color"=>"success"];
	          

	        
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
			// pr();
			$this->script_js = '';
			$user = getuser();
			$status = 'draft';
			$my_company = $this->my_company;
			// pr($my_company);
			if(CRUDBooster::getCurrentMethod() == 'getEdit' && $user->company != $my_company){
				$this->script_js .= '$(".box-footer div.form-group").find("div").append(\'<input type="submit" name="submit" value="Submit" class="btn btn-primary" />\');';
			}
			if(CRUDBooster::getCurrentMethod() == 'getEdit' && $user->company == $my_company){
				$url = url('admin/purchase_invoices/generateinvoice/');
				$this->script_js .= 'var _id = $("input[name=\"id\"]").val();$(".box-footer div.form-group").find("div").append(\'<input type="button" name="generate_invoice" data-url="'.$url.'/\'+_id+\'" value="Generate Invoice" class="btn btn-primary" style="display:none;" />\');';
			
				$url = url('admin/purchase_invoices/closeinvoice/');
				$this->script_js .= 'var _id2 = $("input[name=\"id\"]").val();$(".box-footer div.form-group").find("div").append(\'<input type="button" name="close_invoice" data-url="'.$url.'/\'+_id2+\'" value="Close Invoice" class="btn btn-warning" style="display:none;" />\');';
			}
			if(CRUDBooster::getCurrentMethod() == 'getEdit'){
				$url = url('admin/purchase_invoices/reopen/');
				$this->script_js .= 'var _id3 = $("input[name=\"id\"]").val();$(".box-footer div.form-group").find("div").append(\'<input type="button" name="reopen" data-url="'.$url.'/\'+_id3+\'" value="Re-Open" class="btn btn-info" style="display:none;" />\');';
			}
			
			// $this->script_js = '$("table#table-detail tr:first td:eq(1)").text("'.$_GET['idx'].'")';


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
			$this->load_js[] = asset("js/myglobal.js");
			$this->load_js[] = asset("js/purchase-invoice.js");
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->load_js[] = asset("js/purchase-invoice-index.js");
			}
	        
	        
	        
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
			// pr($query->toSql(),1);			
			if(CRUDBooster::isSuperAdmin()){
				return true;
			}
			$user = getUser();
			$my_company = $this->my_company;
			if($user->company != $my_company){
				return $query->where('supplier',$user->company);
			} else {
				return $query->where('status','!=','draft');
			}

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
			$postdata['status'] = 'draft';
			unset($postdata['id']);
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        \DB::table('purchase_invoice_items')
			->where('id_purchase_invoice',null)
			->update(['id_purchase_invoice'=>$id]);
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
			// pr($_POST,1);

			$cek = DB::table('purchase_invoices')->where('id',$id)->first(['status']);
			// pr($cek,1);
			if($cek->status != 'draft'){
				return false;
			}

			if($_POST['submit'] == 'Submit'){
				$postdata['status'] = 'submited';
			}
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

		public function getPurchaseinvoice($id){
			$order_by 		= 'name desc'; //default
			$filters		= ["name"=>["like","%".@$_GET['q']."%"]];
			if(!CRUDBooster::isSuperadmin()){
				$user = getUser();
				$supplier = $user->company;
				$filters['supplier'] = ['=',$supplier];
			}
			$filters = json_encode($filters);
			// pr($filters);
			$doctype 		= 'Purchase Invoice';
			$start 			= $_GET['start']?:0;
			$page_length 	= $_GET['limit']?:10;
			$fields 		= "name, posting_date, posting_time,supplier";
			
			
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
					$response[$key]->text = $val->name;
				}
			}
			if(request()->ajax()){
				$response =  response()->json($response);
			}

			return $response;			
		}
		
		public function getPurchaseorder($id){
			$order_by 		= 'name desc'; //default
			$filters		= ["name"=>["like","%".@$_GET['q']."%"]];
			
			if(!CRUDBooster::isSuperadmin()){
				$user = getUser();
				$supplier = $user->company;
				$filters['supplier'] = ['=',$supplier];
			}

			$filters = json_encode($filters);
			// pr($filters);
			$doctype 		= 'Purchase Order';
			$start 			= $_GET['start']?:0;
			$page_length 	= $_GET['limit']?:10;
			$fields 		= "name";
			
			
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
					$response[$key]->text = $val->name;
				}
			}
			if(request()->ajax()){
				$response =  response()->json($response);
			}

			return $response;			
		}

		function getPurchaseinvoicedetail($id){			
			$doctype = 'Purchase Invoice';
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

		function getGenerateinvoice($id){
			$cek = DB::table('purchase_invoices')->where('id',$id)->where('status','submited')->count();
			if(!$cek){
				return redirect()->to(url('admin/purchase_invoices?e=1'));
			}
			$query = DB::table('purchase_invoices')->where('id',$id)->update(['status'=>'open']);
			if($query){
				return redirect()->to(url('admin/purchase_invoices?e=0&m=Generate invoice success!'));
				// return cb()->redirect(action("AdminPurchaseInvoicesController@getIndex"), "Generate invoice success!", "success");
			}
			return redirect()->to(url('admin/purchase_invoices?e=0&m=Generate invoice failed!'));
			// return cb()->redirectBack("Generate invoice failed!", "danger");
			
		}

		function getCloseinvoice($id){
			$cek = DB::table('purchase_invoices')->where('id',$id)->where('status','open')->count();
			if(!$cek){
				return redirect()->to(url('admin/purchase_invoices?e=1'));
			}
			$query = DB::table('purchase_invoices')->where('id',$id)->update(['status'=>'closed']);
			if($query){
				return redirect()->to(url('admin/purchase_invoices?e=0&m=Closing invoice success!'));
				// return cb()->redirect(action("AdminPurchaseInvoicesController@getIndex"), "Generate invoice success!", "success");
			}
			return redirect()->to(url('admin/purchase_invoices?e=0&m=Closing invoice failed!'));
			// return cb()->redirectBack("Generate invoice failed!", "danger");
			
		}

		function getReopen($id){
			$cek = DB::table('purchase_invoices')->where('id',$id)->where('status','closed')->count();
			if(!$cek){
				return redirect()->to(url('admin/purchase_invoices?e=1'));
			}
			$query = DB::table('purchase_invoices')->where('id',$id)->update(['status'=>'open']);
			if($query){
				return redirect()->to(url('admin/purchase_invoices?e=0&m=Re-Open invoice success!'));
			}
			return redirect()->to(url('admin/purchase_invoices?e=0&m=Re-Open invoice failed!'));
			
		}
	    //By the way, you can still create your own method in here... :)


	}