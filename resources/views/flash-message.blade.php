@if ($message = Session::get('success'))
    <script type="text/javascript">

        $(document).ready(function(){
            swal("Sucesso!",'{{ $message }}',"success");
        });

    </script>
@endif


@if ($message = Session::get('error'))
    <script type="text/javascript">

        $(document).ready(function(){
            swal("Erro!",'{{ $message }}',"error");
        });

    </script>
@endif


@if ($message = Session::get('warning'))
    <script type="text/javascript">

        $(document).ready(function(){
            swal("Ops!",'{{ $message }}',"warning");
        });

    </script>
@endif


@if ($message = Session::get('info'))
    <script type="text/javascript">

        $(document).ready(function(){
            swal("Info!",'{{ $message }}',"info");
        });

    </script>
@endif


@if ($errors->any())
    <script type="text/javascript">

        $(document).ready(function(){
            swal("Erro!",'<?php
            foreach ($errors->all() as $error):
                echo $error . "<br><br>";
            endforeach;
             ?>',"error");
        });

    </script>
@endif
