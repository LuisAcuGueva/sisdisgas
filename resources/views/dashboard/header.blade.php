<html lang="" class=" ">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="images/favicon.ico">
        <title>SISDISGAS</title>

        <!-- Bootstrap -->
        {!! Html::style('css/bootstrap.min.css') !!}
        <!-- Font Awesome -->
        {!! Html::style('css/font-awesome/css/font-awesome.min.css') !!}
        <!-- Custom Theme Style -->
        {!! Html::style('css/custom.min.css') !!}
        <!-- Estilos Style -->
        {!! Html::style('css/estilos.css') !!}

        {!! Html::style('css/pages.css') !!}

        {!! Html::style('plugins/iCheck/skins/flat/green.css') !!}

        {!! Html::style('plugins/switchery/switchery.min.css') !!}

        {!! Html::style('plugins/timepicker/bootstrap-timepicker.min.css') !!}
        {!! Html::style('plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') !!}
        {!! Html::style('plugins/bootstrap-daterangepicker/daterangepicker.css') !!}

        {{-- typeahead.js-bootstrap: para autocompletar --}}
        {!! HTML::style('plugins/x-editable/dist/inputs-ext/typeaheadjs/lib/typeahead.js-bootstrap.css', array('media' => 'screen')) !!}
    </head>
<body class="nav-sm footer_fixed" style="position: relative;">

@include('dashboard.left_sidebar')