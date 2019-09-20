@php
$dadosNotificacao =  getNotificacaoEmAbertoPorUsuario();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ABGF - Sistema de Garantias Públicas</title>
    <link rel="shortcut icon" href="{{ asset('/imagens/Favicon.ico') }}" type="image/x-icon"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>


    <script src="{{ asset('js/jasny-bootstrap.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>


     <!-- Toast -->
    <link rel="stylesheet" href="{{ asset('/bower_components/toast-master/css/jquery.toast.css') }}" />
    <script src="{{ asset('bower_components/toast-master/js/jquery.toast.js')}}"></script>

    <script type="text/javascript" src="{{ asset('/bower_components/moment/min/moment.min.js') }}"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    {{--Tour--}}

    <link rel="stylesheet" href="{{ asset('/css/bootstrap-tour.css') }}" />
    <script type="text/javascript" src="{{ asset('/js/bootstrap-tour.js') }}" ></script>



    {{--end tour--}}

    <script type="text/javascript" src="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/jquery.maskedinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.mask.js') }}"></script>
    <script type="text/javascript" src="{{ asset('inclusoes/js/jQuery.CrossBrowser.IE7.js?v=1.5') }}"></script>
    <script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/funcoes.geral.js').'?'.time() }}"></script>
    <script type="text/javascript" src="{{ asset('js/abgf/exportador/limite/analise_limite.js').'?'.time() }}"></script>
    <!--Plugin Upload -->
    <script type="text/javascript" src="{{ asset('bower_components/blueimp-file-upload/js/vendor/jquery.ui.widget.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bower_components/blueimp-file-upload/js/jquery.fileupload.js') }}"></script>
    <link href="{{ asset('bower_components/blueimp-file-upload/css/jquery.fileupload.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Fecha Plugin Upload -->

     <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{ asset('plugins/timepicker/bootstrap-timepicker.min.css') }}">



    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">



    <!-- Última versão CSS compilada e minificada -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" href="{{ asset('css/jasny-bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{asset('inclusoes/css/Principal.css')}}?v={{time()}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('inclusoes/css/Dashboard.css')}}?v={{time()}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />


    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
    <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js')}}"></script>

    <!-- boostrap select -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css')}}">

    <!-- Latest compiled and minified JavaScript -->
    <script src="{{ asset('js/bootstrap-select.js')}}"></script>
    <script src="{{ asset('js/asn.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('/css/bite-checkbox.css') }}" />

    @yield('scripts')

    <!-- Page script -->
    <script type="application/javascript">
        var URL_BASE='{{URL::to("/")}}/';
        var URL_ATUAL='{{Request::url()}}/';
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{env('ID_GTAG')}}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '{{env('ID_GTAG')}}');
    </script>

</head>
<body class="skin-blue sidebar-mini">

<div class="wrapper" style="height: auto; min-height: 100%;">
    <header class="main-header">
        <!-- Logo -->

        <a href="{{URL::to('/')}}" class="logo">
            <img src="{{ asset('imagens/sce_logo_100_branca.png') }}" width="100" alt="SCE MPME"/>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">


            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">


                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-danger">@php echo count($dadosNotificacao) @endphp</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Você tem @php echo count($dadosNotificacao) @endphp notificações</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    @forelse($dadosNotificacao as $notificacao)
                                        <li>
                                            <a href="javascript:void(0)" class="marcar_como_lida" data-idmpmenotificacaousuario="{{$notificacao->ID_MPME_NOTIFICACAO_USUARIO}}" data-idmpmetiponotificacao="{{$notificacao->ID_MPME_TIPO_NOTIFICACAO}}" data-idoper="{{$notificacao->ID_OPER}}">
                                                <i class="fa fa-users text-aqua"></i>
                                                <small>
                                                    <b>OPERAÇÃO: {{ formatar_codigo($notificacao->ID_OPER) }}
                                                    @if(isset($notificacao->ID_MPME_PROPOSTA))
                                                       / PROPOSTA: {{ formatar_codigo($notificacao->ID_MPME_PROPOSTA) }} /
                                                    @endif
                                                    </b> {{$notificacao->tipo_notificacao->NO_TIPO_NOTIFICACAO_EXTERNA}}
                                                </small>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <!--<li class="footer"><a href="#">View all</a></li>-->
                        </ul>
                    </li>

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle clearfix" data-toggle="dropdown" aria-expanded="false">
                            <figure>
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </figure>
                            <span class="hidden-xs"> <strong>{{\Illuminate\Support\Facades\Auth::user()->NM_USUARIO}}</strong></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <figure>
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </figure>
                                <p>
                                    Seja bem-vindo(a) <strong>{{\Illuminate\Support\Facades\Auth::user()->NM_USUARIO}}</strong>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="javascript:void(0);" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#aterar-senha">Alterar senha</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{URL::to('/logout')}}" class="btn btn-danger btn-flat">Sair</a>
                                </div>
                            </li>
                        </ul>
                    </li>


                </ul>
            </div>
        </nav>
    </header>


        <div class="container-fluid">
           <!-- <div id="IndexHeader" class="Centro ClearFix">
                <div class="LogoSGP">
                    <img src="{{ asset('imagens/sce_logo_100.png') }}" width="100" alt="SCE MPME"/>
                </div>
            </div>
            <div class="pull-right ClearFix">
                <div style="margin-top: -50px;">
                    Seja bem-vindo(a): <br>
                    {{\Illuminate\Support\Facades\Auth::user()->NM_USUARIO}}
                </div>
            </div>-->
            <div id="IndexSite" class="Centro">
                    @include('flash-message')
                    @yield('content')
            </div>

            <div id="IndexFooter" class="Centro ClearFix">
                <div class="Copy">&copy;  Agência Brasileira Gestora de Fundos Garantidores e Garantias S.A. - 2018. Módulo ABGF: 1.0</div>
            </div>
        </div>

        @include('usuario.alterar-senha')

</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.29.0/dist/sweetalert2.all.min.js" integrity="sha256-FABHlNZdWEEvD1Ge8L18a01NTTLNiZ4uD8hdl5QG5BI=" crossorigin="anonymous"></script>
    <script src="{{ asset('/bower_components/ckeditor/ckeditor.js')}}"></script>

</body>
</html>
