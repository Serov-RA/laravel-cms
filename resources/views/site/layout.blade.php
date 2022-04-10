<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if ($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}" />
    @endif

    @if ($page->meta_description)
        <meta name="keywords" content="{{ $page->meta_keywords }}" />
    @endif

    <title>{{ $page->getPageTitle() }}</title>

    @foreach ($css as $css_item)
        @if ($css_item->css->type === \App\Models\Css::TYPE_INTERNAL)
            <style>
                {!! $css_item->css->content !!}
            </style>
        @else
            <link href="{{ $css_item->css->path }}" rel="stylesheet">
        @endif
    @endforeach

    @foreach ($js[\App\Models\Js::POS_HEAD] as $js_item)
        @if ($js_item->js->type === \App\Models\Js::TYPE_INTERNAL)
            <script type="text/javascript">
                {!! $js_item->js->content !!}
            </script>
        @else
            <script type="text/javascript" src="{{ $js_item->js->path }}"></script>
        @endif
    @endforeach
</head>
<body>

    @foreach ($js[\App\Models\Js::POS_BEGIN] as $js_item)
        @if ($js_item->js->type === \App\Models\Js::TYPE_INTERNAL)
            <script type="text/javascript">
                {!! $js_item->js->content !!}
            </script>
        @else
            <script type="text/javascript" src="{{ $js_item->js->path }}"></script>
        @endif
    @endforeach

    {!! $page->getPageContent() !!}

    @foreach ($js[\App\Models\Js::POS_END] as $js_item)
        @if ($js_item->js->type === \App\Models\Js::TYPE_INTERNAL)
            <script type="text/javascript">
                {!! $js_item->js->content !!}
            </script>
        @else
            <script type="text/javascript" src="{{ $js_item->js->path }}"></script>
        @endif
    @endforeach

</body>
</html>
