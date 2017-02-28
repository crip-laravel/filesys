<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta id="settings"
        data-sizes="{!! str_replace('"', '\'', json_encode(config('cripfilesys.thumbs'))) !!}"
        data-params="{!! str_replace('"', '\'', json_encode($input)) !!}"
        data-public-url="{{ config('cripfilesys.public_url') }}"
        data-base-url="{{ config('cripfilesys.base_url') }}"/>
  <title>CRIP Filesys</title>
  <link href="<{{config('cripfilesys.public_url')}}/css/styles.css" rel="stylesheet">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body>

<div id="app">Loading...</div>

<script src="<?php echo config('cripfilesys.public_url') ?>/js/vendor.bundle.js"></script>
<script src="<?php echo config('cripfilesys.public_url') ?>/js/app.bundle.js"></script>

</body>

</html>