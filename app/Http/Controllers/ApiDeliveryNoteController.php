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

				unset($result['supplier_delivery_note']);
				unset($result['purchase_order']);
				unset($result['id']);
				unset($result['api_response_fields']);
				unset($result['qr_code']);
				$items = \DB::table('delivery_note_items')
				->select('item_code','item_name','qty','uom','batch_no','serial_no')
				->where('delivery_note_id',$id)
				->get();

				// $datas = [];
				if(count($items)){
					foreach($items as $k => $val){
						$items[$k]->batch_no = ($val->batch_no == '-') ? '' : $val->batch_no;
						$items[$k]->serial_no = ($val->serial_no == '-') ? '' : str_replace(",","\r\n",$val->serial_no);
					}
				}
				return $result['items'] = $items;

		    }

		}