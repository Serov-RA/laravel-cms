<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>
    <link href="/css/admin/dataTables.bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="/css/admin/bootstrap3.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/css/admin/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/css/admin//nprogress.css" rel="stylesheet">
    <!-- JQuery UI -->
    <link href="/css/admin/jquery-ui.min.css" rel="stylesheet">

    @stack('css')

    <!-- Custom Theme Style -->
    <link href="/css/admin/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="/" class="site_title"><i class="fa fa-cogs"></i> <span> {{ $app['config']->get('app.name') }}</span></a>
                </div>

                <div class="clearfix"></div>

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>{{ __('Menu') }}</h3>
                        <ul class="nav side-menu">
                            <li>
                                <a href="#">
                                    <i class="fa fa-globe"></i>
                                    <span>{{ __('Site') }}</span>
                                    <span class="fa fa-chevron-down"></span>
                                </a>
                                <ul class="nav child_menu">
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'site', 'model' => 'page'], false) }}">
                                            <i class="fa fa-files-o"></i><span>{{ __('Pages') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'site', 'model' => 'template'], false) }}">
                                            <i class="fa fa-file-o"></i><span>{{ __('Templates') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'site', 'model' => 'incut'], false) }}">
                                            <i class="fa fa-clipboard"></i><span>{{ __('Incuts') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'site', 'model' => 'css'], false) }}">
                                            <i class="fa fa-css3"></i><span>{{ __('CSS') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'site', 'model' => 'js'], false) }}">
                                            <i class="fa fa-file-code-o"></i><span>{{ __('JS') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'site', 'model' => 'option'], false) }}">
                                            <i class="fa fa-cogs"></i><span>{{ __('Options') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{-- <li>
                                <a href="#">
                                    <i class="fa fa-envelope"></i><span>{{ __('Mail') }}</span><span class="fa fa-chevron-down"></span>
                                </a>
                                <ul class="nav child_menu">
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'mail', 'model' => 'mailmessage'], false) }}">
                                            <i class="fa fa-send"></i><span>{{ __('Mail messages') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'mail', 'model' => 'mailtemplate'], false) }}">
                                            <i class="fa fa-file-o"></i><span>{{ __('Mail templates') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'mail', 'model' => 'mailservice'], false) }}">
                                            <i class="fa fa-cogs"></i><span>{{ __('Mail services') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                            <li>
                                <a href="#">
                                    <i class="fa fa-unlock-alt"></i><span>{{ __('Access') }}</span><span class="fa fa-chevron-down"></span>
                                </a>
                                <ul class="nav child_menu">
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'access', 'model' => 'user'], false) }}">
                                            <i class="fa fa-user"></i><span>{{ __('Users') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin', ['section' => 'access', 'model' => 'role'], false) }}">
                                            <i class="fa fa-users"></i><span>{{ __('Roles') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li></ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <nav class="nav navbar-nav">
                    <ul class=" navbar-right">
                        <li class="nav-item dropdown open" style="padding-left: 15px;">
                            <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                               {{ \Illuminate\Support\Facades\Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin', [
                                    'section' => 'access',
                                    'model' => 'users',
                                    'action' => 'edit',
                                    'id' => \Illuminate\Support\Facades\Auth::id()], false) }}">
                                    <i class="fa fa-user pull-right"></i> {{ __('Profile') }}
                                </a>
                                <form method="post" action="{{ route('logout', [], false) }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa fa-sign-out pull-right"></i> {{ __('Sign out') }}
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>{{ $title }}</h3>
                    </div>
                </div>

                <div class="clearfix"></div>

                @if (\Illuminate\Support\Facades\Session::has('flash-message'))
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="alert
                                   alert-{{ \Illuminate\Support\Facades\Session::get('flash-message')['status'] }}
                                   alert-dismissible"
                                   role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <strong>
                            {{ \Illuminate\Support\Facades\Session::get('flash-message')['message'] }}
                            </strong>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="/js/admin/jquery.min.js"></script>
<!-- jQuery UI -->
<script src="/js/admin/jquery-ui.min.js"></script>
<!-- Bootstrap -->
<script src="/js/admin/bootstrap3.bundle.min.js"></script>
<!-- FastClick -->
<script src="/js/admin/fastclick.js"></script>
<!-- NProgress -->
<script src="/js/admin/nprogress.js"></script>
<!-- Custom Theme Scripts -->
<script src="/js/admin/custom.min.js"></script>

@stack('js')

<script src="/js/admin/admin.js"></script>

@if (isset($section, $model))
<script type="text/javascript">
    var indexUrl = "{{ route('admin', ['section' => $section, 'model' => $model], false) }}"
    @if (url()->current() !== route('admin', ['section' => $section, 'model' => $model]))
        Admin.selectMenu(indexUrl);
    @endif
</script>
@endif


</body>
</html>
