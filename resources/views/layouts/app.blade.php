<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="Tropi-Techno Sarl - Entreprise de biotechnologies agricoles au Togo, spécialisée dans les semences bio-locales et l'agriculture biologique">
    <title>@yield('title', 'Tropi-Techno Sarl - Agriculture Biologique au Togo')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    @include('partials.navbar')
    <main>
        @yield('content')
         <!-- WhatsApp Button---->
    @include('partials.whatsapp-multi')      <!-- Version simple -->
    {{-- @include('partials.whatsapp-button') --}}    <!-- Version multi-contacts -->
    {{-- @include('partials.whatsapp-custom') --}}   <!-- Version message dynamique -->
    {{-- @include('partials.whatsapp-form') --}}     <!-- Version formulaire -->
    </main>
    @include('partials.footer')
    
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>