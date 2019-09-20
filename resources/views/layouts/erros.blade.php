<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>SCE MPME</title>
        
		<link href='https://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Karla:400,400italic,700italic,700' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300italic,300,400italic,700,700italic' rel='stylesheet' type='text/css'>
        
        <link href="{{asset('css/bootstrap.min.css?v=3.3.5')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('css/font-awesome.min.css?v=4.3.0')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('css/jasny-bootstrap.min.css?v=3.1.3')}}" rel="stylesheet" type="text/css">

        <link href="{{asset('css/style-inicio.css')}}?v=<?=time();?>" rel="stylesheet" type="text/css">
        <link href="{{asset('css/style-elementos.css')}}?v=<?=time();?>" rel="stylesheet" type="text/css">        
        
        @yield('scripts')
	</head>
    <body>
		<div class="container">
        	@yield('content')
        </div>
    </body>
</html>