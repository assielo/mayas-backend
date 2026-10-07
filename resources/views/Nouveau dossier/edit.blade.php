<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHEPITIMAYAS SÉCURITÉ - Modifier mes accès</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-xl bg-zinc-950 p-6 rounded-2xl border border-yellow-500/30 text-white shadow-xl space-y-4">
        
        <div class="flex items-center justify-between border-b border-zinc-800 pb-3">
            <h2 class="text-xl font-bold text-yellow-400 flex items-center gap-2">
                <i class="fas fa-user-gear"></i> Modifier mes accès
            </h2>
            <a href="{{ route('dashboard') }}" class="text-xs text-zinc-400 hover:text-yellow-400 transition-colors">
                <i class="fas fa-arrow-left"></i> Tableau de bord
            </a>
        </div>

        @if (session('status'))
            <div class="p-3 bg-green-500/20 border border-green-500 text-green-400 rounded-xl text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update-access') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Adresse Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-yellow-400/90 uppercase mb-1">Nouvelle Adresse Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="w-full px-4 py-2.5 bg-zinc-900 border border-zinc-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                @error('email') 
                    <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Mot de passe actuel -->
            <div>
                <label for="current_password" class="block text-xs font-semibold text-yellow-400/90 uppercase mb-1">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password" required
                    class="w-full px-4 py-2.5 bg-zinc-900 border border-zinc-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                @error('current_password') 
                    <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Nouveau mot de passe -->
            <div>
                <label for="password" class="block text-xs font-semibold text-yellow-400/90 uppercase mb-1">Nouveau mot de passe (optionnel)</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2.5 bg-zinc-900 border border-zinc-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm"
                    placeholder="Laissez vide pour ne pas modifier">
                @error('password') 
                    <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-yellow-400/90 uppercase mb-1">Confirmer le nouveau mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full px-4 py-2.5 bg-zinc-900 border border-zinc-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
            </div>

            <button type="submit" 
                class="w-full py-3 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-zinc-950 font-bold rounded-xl transition-all uppercase text-sm tracking-wider mt-2">
                Enregistrer les modifications
            </button>
        </form>
    </div>

</body>
</html>