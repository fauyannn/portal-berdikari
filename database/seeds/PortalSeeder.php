<?php

use Illuminate\Database\Seeder;

class PortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cek = DB::table('cms_apikey')
        ->where('screetkey','b6a7aaa47e18b4890403794a39cc43f4')
        ->count();
        
        if(!$cek){
            $this->command->info('insert table cms_apikey...');
            DB::insert("insert into `cms_apikey` (`screetkey`, `hit`, `status`, `created_at`) values ('b6a7aaa47e18b4890403794a39cc43f4', '4', 'active', '2020-03-17 05:09:50')");
        }

        $cek2 = DB::table('cms_apicustom')
        ->where('permalink','delivery_note')
        ->count();
        if(!$cek2){
            $this->command->info('insert table cms_apicustom...');
            DB::insert("insert into `cms_apicustom` (`id`, `permalink`, `tabel`, `aksi`, `kolom`, `orderby`, `sub_query_1`, `sql_where`, `nama`, `keterangan`, `parameter`, `created_at`, `updated_at`, `method_type`, `parameters`, `responses`) VALUES (NULL, 'delivery_note', 'delivery_notes', 'detail', NULL, NULL, NULL, NULL, 'Delivery Note', NULL, NULL, NULL, NULL, 'get', 'a:1:{i:0;a:5:{s:4:\"name\";s:14:\"purchase_order\";s:4:\"type\";s:6:\"string\";s:6:\"config\";N;s:8:\"required\";s:1:\"1\";s:4:\"used\";s:1:\"1\";}}', 'a:5:{i:0;a:4:{s:4:\"name\";s:14:\"purchase_order\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:1;a:4:{s:4:\"name\";s:8:\"supplier\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:2;a:4:{s:4:\"name\";s:22:\"supplier_delivery_note\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:3;a:4:{s:4:\"name\";s:7:\"qr_code\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:4;a:4:{s:4:\"name\";s:2:\"id\";s:4:\"type\";s:7:\"integer\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}}')");
        }
        
        
    }
}
