<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ABGF - Sistema de Garantias Públicas MPME)</title>
    <link rel="shortcut icon" href="{{ asset('imagens/Favicon.ico') }}" type="image/x-icon" />

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('inclusoes/js/jQuery.js?v=1.11.2') }}"></script>
    <script type="text/javascript" src="{{ asset('inclusoes/js/jQuery.FancyBox.js?v=2.1.5') }}"></script>
    <script type="text/javascript" src="{{ asset('inclusoes/js/jQuery.MaskedInput.js?v=1.3.1') }}"></script>
    <script type="text/javascript" src="{{ asset('inclusoes/js/jQuery.MaskBrPhone.js?v=1.1') }}"></script>
    <script type="text/javascript" src="{{ asset('inclusoes/js/jQuery.autoNumeric.js?v=2.0') }}"></script>
    <script type="text/javascript" src="{{ asset('inclusoes/js/jQuery.Funcoes.js') }}"></script>
    <script type="text/javascript" src="{{ asset('inclusoes/js/jQuery.CrossBrowser.IE7.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dist/sweetalert.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js') }}"> </script>

    {{--<script type="text/javascript" src="http://updateyourbrowser.net/asn.js')"> </script>--}}

    {{--<script type="text/javascript" src="http://updateyourbrowser.net/asn.js"> </script>--}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!-- Fecha Scripts -->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <link href="{{ asset('inclusoes/css/Principal.css') }}" rel="stylesheet" type="text/css" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>


    <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/plugins/select2/select2.css') }}"/>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME STYLES -->
    <link href="{{ asset('assets/global/css/components.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/global/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/admin/layout/css/layout.css') }}" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="{{ asset('assets/admin/layout/css/themes/default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/admin/layout/css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('dist/sweetalert.css') }}" rel="stylesheet" type="text/css"/>




    <!-- Fecha Styles -->

</head>
<body>
<div class="container">
    <div class="row" style="margin-top:20px;">
        <div class="text-left" style="float:left;margin-left: 20px;">
            <a href="http://www.abgf.gov.br" target="_blank"> <img  width="140" src="{{ asset('imagens/logoabgf.png') }}" alt="SGP"  /></a>
        </div>
        <div class="text-right" style="float:right;margin-right: 20px;">
            <img src="{{ asset('imagens/IndexSGPLogo.gif') }}" alt="SGP" />
        </div>
    </div>

            @yield('content')

      
    </div>



<footer class="footer">
    <div class="text-center" style="margin-top:5px">
        &copy;  Agência Brasileira Gestora de Fundos Garantidores e Garantias S.A. - <?php echo date('Y');?>. Versão do sistema: 2.0
    </div>
</footer>
</div>


<!-- Scripts Rodape -->



<!--[if lt IE 9]>
<script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>

<script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script>

<![endif]-->

<script src="{{ asset('assets/global/plugins/jquery-1.11.0.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/global/plugins/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>


<script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/global/plugins/select2/select2.min.js') }}"></script>

<script src="{{ asset('assets/global/scripts/metronic.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/layout/scripts/layout.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/layout/scripts/quick-sidebar.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/layout/scripts/demo.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/pages/scripts/form-wizard.js') }}"></script>
<script src="{{ asset('src/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('js/jquery.cpfcnpj.min.js') }}"></script>
<script src="{{ asset('src/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('js/funcoes.cadastro.js?v=5') }}"></script>
<script src="{{ asset('js/funcoes.login.js') }}"></script>

<link href="{{ asset('css/sol.css') }}" rel="stylesheet" type="text/css"/>

<script src="{{ asset('js/sol.js') }}"></script>




<link href="{{ asset('css/datepicker.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('css/icons-sprite.css') }}" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js') }}"> </script>
</body>
</html>