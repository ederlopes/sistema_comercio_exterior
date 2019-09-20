<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ABGF - Sistema de Garantias Públicas MPME)</title>
    <link rel="shortcut icon" href="{{ asset('imagens/Favicon.ico') }}" type="image/x-icon" />

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{asset('inclusoes/js/jQuery.MaskedInput.js?v=1.3.1')}}"></script>
    <script type="text/javascript" src="{{asset('inclusoes/js/jQuery.autoNumeric.js?v=2.0')}}"></script>
    <script type="text/javascript" src="{{asset('inclusoes/js/jQuery.MaskBrPhone.js?v=2.0')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.bootstrap.wizard.min.js?v=1.0')}}"></script>
    <script type="text/javascript" src="{{asset('js/jasny-bootstrap.min.js?v=3.1.3')}}"></script>
    <script type="text/javascript" src="{{asset('dist/sweetalert.min.js')}}"> </script>
    <script type="text/javascript" src="{{asset('js/bootstrap-datepicker.js')}}"> </script>
    <script type="text/javascript" src="{{asset('js/bootstrap-select.js')}}"> </script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{asset('js/funcoes.cadastro.js')}}?v={{time()}}"></script>

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/jasny-bootstrap.min.css?v=3.1.3')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('dist/sweetalert.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/bite-checkbox.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/Cadastro.css')}}?v={{time()}}" rel="stylesheet" type="text/css" />

    <script type="application/javascript">
        var URL_BASE='{{URL::to("/")}}/';
        var URL_ATUAL='{{Request::url()}}/';
    </script>
</head>
<body>
<div id="navegador" align="center" class="alert alert-info  fade in alert-dismissible" style="display: none">
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    <h3><strong>Atenção!</strong> O SCE MPME não é compatível com o navegador utilizado. Para uma melhor navegação, utilize um dos navegadores abaixo:</h3>
    <div class="row" align="center">
        <a href="https://www.google.com/intl/pt-BR_ALL/chrome/" target="_blank"><img src="{{asset('imagens/chrome.png')}}" width="90px" title="Google Chrome"></a>
        <a href="https://www.mozilla.org/pt-BR/firefox/new/" target="_blank"><img src="{{asset('imagens/firefox.png')}}" width="90px" title="Mozila Firefox"></a>
        <a href="https://safari.br.uptodown.com/windows" target="_blank"><img src="{{asset('imagens/safari.png')}}" width="90px" title="Safari"></a>
        <a href="https://www.microsoft.com/pt-br/windows/microsoft-edge" target="_blank"><img src="{{asset('imagens/edge.png')}}" width="90px" title="Microsoft Edge"></a>
    </div>
</div>
    <div class="container">
        <div id="Logo">
            <img src="{{ asset('imagens/sce_logo_100.png') }}" width="100" alt="SCE MPME"/>
        </div>
        @yield('content')
    </div>
    <footer class="footer">
        <div class="text-center">
            &copy;  Agência Brasileira Gestora de Fundos Garantidores e Garantias S.A. - <?php echo date('Y');?>. Módulo ABGF: 2.0
        </div>
    </footer>
    </div>
<script>
    $(function () {
        var isIE = /*@cc_on!@*/false || !!document.documentMode;
        if(isIE == true){
            $("#navegador").show();
            $(".next").addClass('disabled');
        }
    });
</script>
</body>
</html>
