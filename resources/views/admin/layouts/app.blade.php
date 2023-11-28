<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME')}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('logo.png') }}">
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div id="app"></div>
</body>

</html>
