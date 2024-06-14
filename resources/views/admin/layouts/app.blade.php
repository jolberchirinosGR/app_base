<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME')}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link id="favicon" rel="icon" type="image/png" sizes="16x16" href="{{ asset('logow.webp') }}">
</head>

<body id="app">
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
    // Función para actualizar el favicon basado en el tema del navegador
    function updateFavicon() {
        const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const favicon = document.getElementById('favicon');
        if (isDarkMode) {
            favicon.href = '{{ asset('logow.webp') }}';
        } else {
            favicon.href = '{{ asset('logob.webp') }}';
        }
    }

    // Detectar cambios en el esquema de color preferido
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateFavicon);

    // Llamar a la función al cargar la página
    updateFavicon();
</script>

</html>
