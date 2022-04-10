<?php

namespace App\Http\Controllers\Site;

use App\Models\Js;
use App\Models\Page;
use App\Models\PageJs;
use App\Models\PageCss;
use App\Models\TemplateJs;
use App\Models\TemplateCss;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    public function __invoke(Request $request): View
    {
        $page = Page::findByAddress($request->path());

        if (!$page) {
            abort(404);
        }

        $js = [
            Js::POS_HEAD => [],
            Js::POS_BEGIN => [],
            Js::POS_END => [],
        ];

        $template_js = TemplateJs::where('template_id', $page->template_id)->orderBy('block_pos')->get();

        foreach ($template_js as $js_item) {
            $js[$js_item->view_pos][] = $js_item;
        }

        $page_js = PageJs::where('page_id', $page->id)->orderBy('block_pos')->get();

        foreach ($page_js as $js_item) {
            $js[$js_item->view_pos][] = $js_item;
        }

        $css = [];

        $template_css = TemplateCss::where('template_id', $page->template_id)->orderBy('block_pos')->get();

        foreach ($template_css as $css_item) {
            $css[] = $css_item;
        }

        $page_css = PageCss::where('page_id', $page->id)->orderBy('block_pos')->get();

        foreach ($page_css as $css_item) {
            $css[] = $css_item;
        }

        return view('site.'.$page->template->template_file, [
            'js' => $js,
            'css' => $css,
            'page' => $page,
        ]);
    }
}
