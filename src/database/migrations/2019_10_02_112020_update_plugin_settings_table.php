<?php

use AlexStack\LaravelCms\Models\LaravelCmsSetting;
use Illuminate\Database\Migrations\Migration;

class UpdatePluginSettingsTable extends Migration
{
    private $config;
    private $table_name;

    public function __construct()
    {
        $this->config     = include base_path('config/laravel-cms.php');
        $this->table_name = $this->config['table_name']['settings'];
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        $setting_data = [
            'category'        => 'plugin',
            'param_name'      => 'page-tab-external-image-link',
            'input_attribute' => '{"rows":15,"required":"required"}',
            'enabled'         => 0,
            'sort_value'      => 55,
            'abstract'        => 'Save external images to your server and add <span style="color: rgb(99, 74, 165);">rel=nofollow</span> to the external links. <a href="https://www.laravelcms.tech" target="_blank"><i class="fas fa-link mr-1"></i>Tutorial</a>',
            'param_value'     => '{
"plugin_name" : "External Images & Links",
"blade_file" : "remote-image",
"tab_name" : "",
"php_class"  : "Amila\\LaravelCMS\\ExternalImageLink\\ExternalImageLinkController",
"version": "0.1.1",
"remote_image_to_local" : {
  	"enable": true,
    "exclude":[".laravelcms.tech","localhost/",".test", ".local"],
    "local_image_size": "original",
  	"replace_fields": ["main_content","sub_content","extra_content_1","extra_content_2","extra_content_3"]
},
"nofollow_external_links" : {
  	"enable": true,
    "exclude":["localhost/",".test", ".laravelcms.tech"],
    "rel_text": "nofollow noopener external noindex",
     "target" : "_blank",
    "replace_fields": ["main_content","sub_content","extra_content_1","extra_content_2","extra_content_3"]
    }
}',
        ];
        LaravelCmsSetting::UpdateOrCreate(
            ['category'=>'plugin', 'param_name' => 'page-tab-external-image-link'],
            $setting_data
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        LaravelCmsSetting::where('param_name', 'page-tab-external-image-link')->where('category', 'plugin')->delete();
    }
}
