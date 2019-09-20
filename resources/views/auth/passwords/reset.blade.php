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
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>SGP</b>MPME</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Recupere sua senha (* min: 6 max 10)</p>

    <form action="{{ route('resetar.token') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="token" value="{{ $request->token }}">
      <div class="form-group has-feedback">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <input type="text" class="form-control" placeholder="E-mail" name="email" value="{{ $email ?? '' or old('email') }}" autofocus>
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

       <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}"> 
        <div class="form-group has-feedback">
           <input id="password-confirm" type="password" placeholder="Repita a senha"  class="form-control" minlength="6" maxlength="10" name="password_confirmation" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
        @endif

      </div>
      <div class="row">
        <div class="col-xs-8">
         
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Recuperar</button>
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

    <a  href="/login" class="text-center">Voltar</a>

  </div>
  <!-- /.login-box-body -->
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
  });
</script>
@include('flash-message')
</body>
</html>
