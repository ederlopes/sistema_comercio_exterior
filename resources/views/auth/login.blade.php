<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SGP MPME | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('LTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('LTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('LTE//bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('LTE/dist/css/AdminLTE.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('LTE/plugins/iCheck/square/blue.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <link rel="stylesheet" href="{{ asset('css/Login.css') }}">

  <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{env('ID_GTAG')}}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '{{env('ID_GTAG')}}');
    </script>

</head>
<body class="hold-transition login-page">

<div id="navegador" align="center" class="alert alert-info fade in alert-dismissible" style="display: none">
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    <h3><strong>Atenção!</strong> O SCE MPME não é compatível com o navegador utilizado. Para uma melhor navegação, utilize um dos navegadores abaixo:</h3>
    <div class="row" align="center">
        <a href="https://www.google.com/intl/pt-BR_ALL/chrome/" target="_blank"><img src="{{asset('imagens/chrome.png')}}" width="90px" title="Google Chrome"></a>
        <a href="https://www.mozilla.org/pt-BR/firefox/new/" target="_blank"><img src="{{asset('imagens/firefox.png')}}" width="90px" title="Mozila Firefox"></a>
        <a href="https://safari.br.uptodown.com/windows" target="_blank"><img src="{{asset('imagens/safari.png')}}" width="90px" title="Safari"></a>
        <a href="https://www.microsoft.com/pt-br/windows/microsoft-edge" target="_blank"><img src="{{asset('imagens/edge.png')}}" width="90px" title="Microsoft Edge"></a>
    </div>
</div>

<div class="login-box">
  <div class="login-logo">
      <div class="LogoABGF">
          <img src="{{ asset('imagens/sce_logo_100.png') }}" width="300" alt="SCE MPME" style="margin-left: -30px;"/>
      </div>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Faça login para iniciar sua sessão</p>

    <form action="{{ route('login') }}" method="post">
    {{ csrf_field() }}
      <div class="form-group has-feedback">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <input type="text" class="form-control" placeholder="Login" name="email" value="{{ old('email') }}" autofocus>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>

           @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
           @endif

         </div>
      </div>

     <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Senha" minlength="6" maxlength="10" name="password" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

         @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
           @endif

      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Mantenha-me conectado
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button id="btnLogin" type="submit" class="btn btn-primary btn-block btn-flat">Logar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!-- div class="social-auth-links text-center">
      <p>- OU -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div -->
    <!-- /.social-auth-links -->

     <a href="{{ route('password.request') }}">
                                    Esqueceu sua senha?
     </a>

  </div>
  <!-- /.login-box-body -->
  <a href="{{route('cadastro')}}" class="btn btn-primary btn-lg btn-block btn-flat" style="margin-top:15px">Cadastre-se</a>
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ asset('LTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('LTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('LTE/plugins/iCheck/icheck.min.js') }} "></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.29.0/dist/sweetalert2.all.min.js" integrity="sha256-FABHlNZdWEEvD1Ge8L18a01NTTLNiZ4uD8hdl5QG5BI=" crossorigin="anonymous"></script>
   
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
    var isIE = /*@cc_on!@*/false || !!document.documentMode;

    if(isIE == true){
          $("#navegador").show();
          $("#btnLogin").prop('disabled', true);
    }
  });
</script>

@include('flash-message')
</body>
</html>
