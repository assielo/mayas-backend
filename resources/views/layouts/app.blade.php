<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHEPITIMAYAS SECURITE — Supervision & Incidents</title>
    
    <!-- Alpine.js pour la réactivité (menu mobile, transitions) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            400: '#fbbf24',
                            500: '#f59e0b', // Jaune/Doré inspiré du logo
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex flex-col selection:bg-brand-500 selection:text-slate-950" x-data="{ mobileMenuOpen: false }">

    <!-- En-tête Navigation -->
    <header class="bg-slate-900/90 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                <!-- Logo & Titre -->
                <div class="flex items-center space-x-3.5">
                    <div class="relative group cursor-pointer">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-brand-500 to-amber-300 rounded-xl blur opacity-30 group-hover:opacity-75 transition duration-300"></div>
                        <!-- Placez votre fichier image logo sous public/images/logo-mayas.png -->
                        <img src="{{ asset('images/logo-mayas.png') }}" alt="MAYAS Sécurité" class="relative w-12 h-12 object-contain rounded-lg p-0.5 bg-slate-950 border border-slate-800">
                    </div>
                    <div>
                        <h1 class="font-extrabold text-lg sm:text-xl tracking-tight text-white flex items-center gap-2">
                            CHEPITIMAYAS <span class="text-brand-400 font-semibold text-xs px-2 py-0.5 rounded bg-brand-500/10 border border-brand-500/20">SÉCURITÉ</span>
                        </h1>
                        <p class="text-xs text-slate-400 hidden sm:block">Contrôle d'Accès, Supervision & Incidents</p>
                    </div>
                </div>

                <!-- Navigation Desktop -->
                <nav class="hidden lg:flex items-center gap-1.5 bg-slate-950/60 p-1.5 rounded-2xl border border-slate-800/80">
                    <a href="{{ route('dashboard') }}" 
                       class="{{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-slate-950 font-bold shadow-lg shadow-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium' }} px-4 py-2 rounded-xl text-xs transition-all duration-200 flex items-center">
                        <i class="fa-solid fa-chart-line mr-2"></i> Supervision
                    </a>

                    <a href="{{ route('vehicles.index') }}" 
                       class="{{ request()->routeIs('vehicles.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-slate-950 font-bold shadow-lg shadow-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium' }} px-4 py-2 rounded-xl text-xs transition-all duration-200 flex items-center">
                        <i class="fa-solid fa-car-rear mr-2 opacity-80"></i> Véhicules & Parking
                    </a>

                    <a href="{{ route('incidents.index') }}" 
                       class="{{ request()->routeIs('incidents.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-slate-950 font-bold shadow-lg shadow-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium' }} px-4 py-2 rounded-xl text-xs transition-all duration-200 flex items-center">
                        <i class="fa-solid fa-triangle-exclamation mr-2 opacity-80"></i> Incidents
                    </a>

                    <a href="{{ route('mdm.index') }}" 
                       class="{{ request()->routeIs('mdm.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-slate-950 font-bold shadow-lg shadow-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium' }} px-4 py-2 rounded-xl text-xs transition-all duration-200 flex items-center">
                        <i class="fa-solid fa-mobile-screen-button mr-2 opacity-80"></i> Flotte MDM
                    </a>

                    <a href="{{ route('reports.index') }}" 
                       class="{{ request()->routeIs('reports.*') ? 'bg-gradient-to-r from-brand-500 to-amber-600 text-slate-950 font-bold shadow-lg shadow-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium' }} px-4 py-2 rounded-xl text-xs transition-all duration-200 flex items-center">
                        <i class="fa-solid fa-file-contract mr-2 opacity-80"></i> Audits & Exports
                    </a>
                </nav>

                <!-- Actions Droite -->
                <div class="hidden sm:flex items-center space-x-3">
                    <div class="flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <span class="relative flex h-2 w-2 mr-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        En Ligne
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-slate-800 hover:bg-rose-500/10 hover:text-rose-400 hover:border-rose-500/30 text-slate-300 border border-slate-700/80 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 flex items-center group">
                            <i class="fa-solid fa-power-off mr-2 text-slate-400 group-hover:text-rose-400 transition-colors"></i> Déconnexion
                        </button>
                    </form>
                </div>

                <!-- Bouton Menu Mobile -->
                <div class="flex lg:hidden items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-slate-400 hover:text-white focus:outline-none p-2 rounded-lg bg-slate-800/50">
                        <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-lg' : 'fa-bars text-lg'"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Menu Mobile Nav -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden bg-slate-900 border-b border-slate-800 px-4 pt-2 pb-4 space-y-2">
            
            <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 rounded-xl text-xs font-medium text-white hover:bg-slate-800"><i class="fa-solid fa-chart-line mr-2 text-brand-400"></i> Supervision</a>
            <a href="{{ route('vehicles.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-800"><i class="fa-solid fa-car-rear mr-2 text-amber-400"></i> Véhicules & Parking</a>
            <a href="{{ route('incidents.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-800"><i class="fa-solid fa-triangle-exclamation mr-2 text-rose-400"></i> Incidents</a>
            <a href="{{ route('mdm.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-800"><i class="fa-solid fa-mobile-screen-button mr-2 text-emerald-400"></i> Flotte MDM</a>
            <a href="{{ route('reports.index') }}" class="block px-3 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-slate-800"><i class="fa-solid fa-file-contract mr-2 text-blue-400"></i> Audits & Exports</a>
            
            <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-400">
                    <span class="w-1.5 h-1.5 mr-2 bg-emerald-400 rounded-full"></span> Connecté
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-rose-400 hover:underline flex items-center font-medium">
                        <i class="fa-solid fa-power-off mr-1.5"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Zone de Contenu -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>