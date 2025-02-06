<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('titre', "Gestion de compte")</title>
    
        @include('admin.css')
        @yield('css')

</head>
<body>

    @include('admin.header') <!-- Inclure le header -->
    @include('admin.sidebar') <!-- Inclure la sidebar -->
    
   
                @yield('contenu') <!-- Contenu principal -->
           

    <!-- js -->
    @include('admin.js')
    @yield('js')

</body>
</html>
