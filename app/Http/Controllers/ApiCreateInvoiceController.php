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
				unset($postdata['email']);
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
				$this->sendEmail($postdata, $result);

			}
			public function sendEmail($postdata, $result)
			{
				$q = DB::table('cms_users')
				->where('company',$postdata['supplier'])
				->where('id_cms_privileges',3)
				->first(['email']);
				$config['to'] = $q->email;
				$config['subject'] = 'Invoice telah dibuat di portal.';
				$config['data'] = ['name' => 'INDTA PRATAMAJAYA', 'subject'=>$config['subject'],'pesan' => 'Pesan email','datetime'=>date('d M Y H:i:s')];
				$config['template'] = 'view.email.invoice';
				$config['attachments'] = [];
				// pr($config,1);
				try{
					\Mail::send('email.invoice', $config['data'], function ($message) use ($config)
					{
						$message->subject($config['subject']);
						$message->from('donotreply@berdikari.com', 'Portal Berdikari');
						$message->to($config['to']);
					});
					// pr('email send',1);
					// return back()->with('alert-success','Berhasil Kirim Email');
					return response (['status' => true,'success' => 'Berhasil Kirim Email']);
				}
				catch (Exception $e){
					return response (['status' => false,'errors' => $e->getMessage()]);
					// pr($e->getMessage());
				}
			}
	}