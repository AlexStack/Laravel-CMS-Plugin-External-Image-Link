<?php

namespace Amila\LaravelCms\Plugins\ExternalImageLink\Controllers;

use AlexStack\LaravelCms\Helpers\LaravelCmsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExternalImageLinkController extends Controller
{
    private $user = null;
    private $helper;

    public function __construct()
    {
        $this->helper = new LaravelCmsHelper();
    }

    public function checkUser()
    {
        // return true;
        if (! $this->user) {
            $this->user = $this->helper->hasPermission();
        }
    }

    // public function create()
    // {
    // }

    public function edit($id, $page)
    {
        // uncomment exit() line to make sure your plugin method invoked
        // please check the php_class value if not invoked

        //exit('Looks good, the plugin\'s edit() method invoked. id=' . $id . ' <hr> FILE=' . __FILE__ . ' <hr> PAGE TITLE=' . $page->title);

        return $id;
    }

    public function store($form_data, $page, $plugin_settings)
    {
        return $this->update($form_data, $page, $plugin_settings);
    }

    public function update($form_data, $page, $plugin_settings)
    {
        $nofollow_settings = $plugin_settings['nofollow_external_links'] ?? [];
        if (isset($nofollow_settings['replace_fields']) && $nofollow_settings['enable']) {
            foreach ($nofollow_settings['replace_fields'] as $field) {
                if (isset($page->$field) && false !== strpos($page->$field, '<a ')) {
                    $page->$field = $this->addNoFollow($page->$field, $nofollow_settings);
                }
            }
        }

        $image_settings = $plugin_settings['remote_image_to_local'] ?? [];
        if (isset($image_settings['replace_fields']) && $image_settings['enable']) {
            foreach ($image_settings['replace_fields'] as $field) {
                if (isset($page->$field) && false !== strpos($page->$field, '<img ')) {
                    $page->$field = $this->replaceRemoteImages($page->$field, $image_settings);
                }
            }
        }

        $page->save();

        //$this->helper->debug($form_data);
    }

    // public function destroy(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Other methods.
     */
    public function addNoFollow($content, $nofollow_settings)
    {
        // $img_array = array();
        // preg_match_all("/(<a[^>]+href=[\"|'| ]{0,}https?:\/\/.*)>/isU", $content, $img_array);
        // $this->helper->debug($img_array);

        return preg_replace_callback(
            "/(<a[^>]+href=[\"|'| ]{0,}https?:\/\/.*)>/isU",
            function ($mach) use ($nofollow_settings) {
                $exclude_ary = [$_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME']];
                if (isset($nofollow_settings['exclude'])) {
                    $exclude_ary = array_merge($nofollow_settings['exclude'], $exclude_ary);
                }

                $ignore_link = false;
                foreach ($exclude_ary as $exclude_str) {
                    if (stripos($mach[0], $exclude_str)) {
                        $ignore_link = true;
                        break;
                    }
                }

                if ($ignore_link) {
                    return $mach[1].' >';
                }

                if (isset($nofollow_settings['target']) && false === stripos($mach[1], 'target=')) {
                    $mach[1] .= ' target="'.$nofollow_settings['target'].'"';
                }

                if (stripos($mach[0], ' rel=')) {
                    return $mach[1].' >';
                }

                return $mach[1].' rel="'.($nofollow_settings['rel_text'] ?? 'nofollow').'">';
            },
            $content
        );
    }

    public function replaceRemoteImages($content, $image_settings)
    {
        // $this->helper->debug($content);

        $img_array = [];

        preg_match_all("/(src|SRC)=[\"|'| ]{0,}(http(s?):\/\/(.*).(gif|jpg|jpeg|bmp|png|svg|webp))/isU", $content, $img_array);
        // all unique images
        $img_array = array_unique($img_array[2]);

        foreach ($img_array as $img) {
            $exclude_ary      = [$_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME']];
            $local_image_size = $image_settings['local_image_size'] ?? 'original';
            if (isset($image_settings['exclude'])) {
                $exclude_ary = array_merge($image_settings['exclude'], $exclude_ary);
            }
            // $this->helper->debug($exclude_ary);

            $ignore_image = false;
            foreach ($exclude_ary as $exclude_str) {
                if (stripos($img, $exclude_str)) {
                    $ignore_image = true;
                    break;
                }
            }
            if ($ignore_image) {
                continue;
            }

            $stream_context = null;
            if (isset($image_settings['stream_options']) && is_array($image_settings['stream_options'])) {
                foreach ($image_settings['stream_options'] as $k=>$v) {
                    if (stripos($img, $k) && isset($v['http'])) {
                        $stream_context = stream_context_create($v);
                        break;
                    }
                }
            }

            $new_file = $this->helper->uploadFile($img, $stream_context);
            if ($new_file) {
                $remote_img_ary[] = $img;
                $local_img_ary[]  = $this->helper->imageUrl($new_file, $local_image_size, $local_image_size);
            }
        }

        if (isset($remote_img_ary)) {
            $content = str_replace($remote_img_ary, $local_img_ary, $content);
            //$this->helper->debug([$img_array, $remote_img_ary, $local_img_ary]);
        }

        return $content;
    }
}
