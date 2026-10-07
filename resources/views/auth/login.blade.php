<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHEPITIMAYAS SÉCURITÉ - Connexion</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-amber-500 via-yellow-500 to-amber-600 min-h-screen flex items-center justify-center p-4">

    <!-- Carte de connexion -->
    <div class="w-full max-w-md bg-zinc-950/95 backdrop-blur-md rounded-3xl shadow-2xl border border-yellow-500/40 p-8 space-y-6">
        
        <!-- Logo Vectoriel Fait Sur-Mesure (Bouclier + Scorpion + Textes) -->
        <div class="text-center space-y-2">
            <div class="inline-block p-1 bg-zinc-900 rounded-2xl border border-yellow-500/30 shadow-lg">
                <svg class="w-32 h-32 mx-auto" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Contour du Bouclier -->
                    <path d="M100 10 C140 10, 185 20, 185 40 C185 110, 135 175, 100 190 C65 175, 15 110, 15 40 C15 20, 60 10, 100 10 Z" fill="#FACC15" stroke="#EAB308" stroke-width="4"/>
                    <path d="M100 16 C136 16, 177 25, 177 43 C177 106, 131 166, 100 180 C69 166, 23 106, 23 43 C23 25, 64 16, 100 16 Z" fill="#FACC15" stroke="#18181B" stroke-width="2"/>
                    
                    <!-- Texte MAYAS -->
                    <text x="100" y="52" text-anchor="middle" font-family="Arial, sans-serif" font-weight="900" font-size="22" fill="#000000">MAYAS</text>
                    
                    <!-- Couronne de Lauriers -->
                    <path d="M50 115 C45 95, 60 75, 75 70 M150 115 C155 95, 140 75, 125 70" stroke="#000000" stroke-width="3" stroke-linecap="round" fill="none"/>
                    
                    <!-- Silhouette du Scorpion -->
                    <g fill="#000000">
                        <!-- Queue & Dards -->
                        <path d="M100 80 C110 75, 120 80, 122 90 C124 100, 115 105, 110 100 C108 98, 115 92, 110 88 C105 84, 98 90, 95 95" stroke="#000000" stroke-width="3" fill="none"/>
                        <!-- Corps -->
                        <ellipse cx="92" cy="102" rx="10" ry="6" transform="rotate(-15 92 102)"/>
                        <ellipse cx="82" cy="106" rx="7" ry="5" transform="rotate(-15 82 106)"/>
                        <!-- Pinces -->
                        <path d="M98 98 C105 92, 112 92, 115 96 C110 98, 105 100, 98 98 Z"/>
                        <path d="M95 105 C102 102, 110 104, 112 108 C108 108, 102 108, 95 105 Z"/>
                    </g>
                    
                    <!-- Texte SÉCURITÉ -->
                    <path id="textPath" d="M 40 145 Q 100 175 160 145" fill="none"/>
                    <text font-family="Arial, sans-serif" font-weight="900" font-size="18" fill="#000000">
                        <textPath href="#textPath" startOffset="50%" text-anchor="middle">SÉCURITÉ</textPath>
                    </text>
                </svg>
            </div>
            <img src="{{ asset('images/logo-mayas.png') }}" alt="MAYAS Sécurité" class="relative w-12 h-12 object-contain rounded-lg p-0.5 bg-slate-950 border border-slate-800">
            <h1 class="text-2xl font-black text-yellow-400 tracking-wider uppercase mt-2">CHEPITIMAYAS SÉCURITÉ</h1>
            <p class="text-xs text-zinc-400 font-medium">Portail d'Authentification Sécurisé</p>
        </div>

        <!-- Formulaire de connexion -->
        <form method="POST" action="/login" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-yellow-400/90 uppercase tracking-wider mb-1.5">Adresse Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-yellow-500">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-3 bg-zinc-900/80 border border-zinc-700 rounded-xl text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all text-sm"
                        placeholder="admin@test.com">
                </div>
                @error('email')
                    <p class="text-xs text-red-400 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-xs font-semibold text-yellow-400/90 uppercase tracking-wider mb-1.5">Mot de passe</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-yellow-500">
                        <i class="fas fa-key"></i>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="w-full pl-10 pr-4 py-3 bg-zinc-900/80 border border-zinc-700 rounded-xl text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all text-sm"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-xs text-red-400 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Se souvenir de moi -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-zinc-400 hover:text-yellow-400 transition-colors">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-zinc-900 border-zinc-700 text-yellow-500 focus:ring-yellow-500 focus:ring-offset-zinc-900">
                    <span>Se souvenir de moi</span>
                </label>
            </div>

            <!-- Bouton de connexion -->
            <button type="submit" 
                class="w-full py-3.5 px-4 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 active:scale-[0.98] text-zinc-950 font-bold rounded-xl shadow-lg shadow-yellow-500/20 transition-all duration-200 flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                <span>Se connecter</span>
                <i class="fas fa-lock text-xs"></i>
            </button>
        </form>

        <!-- Pied de page -->
        <div class="pt-4 border-t border-zinc-800 text-center">
            <p class="text-[11px] text-zinc-500">© 2026 CHEPITIMAYAS SÉCURITÉ. Tous droits réservés.</p>
        </div>

    </div>

</body>
</html>