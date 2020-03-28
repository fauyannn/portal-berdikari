<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiCreateInvoiceController extends \crocodicstudio\crudbooster\controllers\ApiController {

			private $_items;

		    function __construct() {    
				$this->table       = "purchase_invoices";        
				$this->permalink   = "create_invoice";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				$this->_items = $postdata['items'];
				unset($postdata['items']);
				$postdata['status'] = 'draft';
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
				$id = $result['id'];
				$items = json_decode($this->_items);
				if($items){
					foreach($items as $k => $val){
						$val->id_purchase_invoice = $id;
						// pr($val,1);
						DB::table('purchase_invoice_items')->insert((array) $val);
					}
				}
				// pr($result,1);
		        //This method will be execute after run the main process

		    }

		}