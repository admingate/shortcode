<?php

namespace Admingate\Shortcode\Http\Controllers;

use Admingate\Base\Http\Controllers\BaseController;
use Admingate\Base\Http\Responses\BaseHttpResponse;
use Admingate\Shortcode\Http\Requests\GetShortcodeDataRequest;
use Closure;
use Illuminate\Support\Arr;

class ShortcodeController extends BaseController
{
    public function ajaxGetAdminConfig(?string $key, GetShortcodeDataRequest $request, BaseHttpResponse $response)
    {
        $registered = shortcode()->getAll();

        $data = Arr::get($registered, $key . '.admin_config');

        $attributes = [];
        $content = null;

        if ($code = $request->input('code')) {
            $compiler = shortcode()->getCompiler();
            $attributes = $compiler->getAttributes(html_entity_decode($code));
            $content = $compiler->getContent();
        }

        if ($data instanceof Closure) {
            $data = call_user_func($data, $attributes, $content);
        }

        $data = apply_filters(SHORTCODE_REGISTER_CONTENT_IN_ADMIN, $data, $key, $attributes);

        return $response->setData($data);
    }
}
