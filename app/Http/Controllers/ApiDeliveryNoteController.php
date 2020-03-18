<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiDeliveryNoteController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "delivery_notes";        
				$this->permalink   = "delivery_note";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
				// return $postdata;
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
				// return $query->join('delivery_note_items','delivery_note_id','=','delivery_notes.id');
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
				//This method will be execute after run the main process
				$id = $result['id'];

				$items = \DB::table('delivery_note_items')
				->select('item_code','item_name','qty','uom','rate','amount')
				->where('delivery_note_id',$id)
				->get();
				return $result['items'] = $items;

		    }

		}