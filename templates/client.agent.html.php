<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Maxitsa - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#a54f0d] min-h-screen flex items-center justify-center p-6">
    <!-- Conteneur global avec ombre et hauteur augmentée -->
    <div class="flex flex-col md:flex-row w-[90%] max-w-[1500px] min-h-[80vh] bg-white shadow-2xl rounded-3xl p-10 gap-10">
        
        <!-- Bloc Bienvenue -->
        <div class="flex-1 flex items-center justify-center">
            <div class="bg-gradient-to-br from-gray-50 to-white shadow-md rounded-2xl px-10 py-12 text-center border border-gray-200">
                <h1 class="text-4xl font-extrabold text-black tracking-wide">BIENVENUE</h1>
                <p class="text-lg text-gray-700 mt-3 font-semibold">DANS MAXITSA</p>
                <p class="text-sm text-gray-600 mt-2">Systèmes de transfert et de paiements</p>
            </div>
        </div>

        <!-- Formulaire de sélection -->
        <div class="flex-1 flex items-center justify-center">
            <div class="bg-gray-50 p-10 rounded-2xl shadow-lg w-full max-w-md border border-gray-200">
                
                <!-- Bouton Client - Redirige vers inscription -->
                <a href="/register" class="block w-full border border-black text-black py-3 rounded-lg mb-4 font-semibold hover:bg-gray-100 transition text-center">
                    CLIENT
                </a>
                
                <!-- Bouton Service Commercial - Redirige vers inscription agent -->
                <a href="/agent" class="block w-full border border-black text-black py-3 rounded-lg mb-4 font-semibold hover:bg-gray-100 transition text-center">
                    AGENT
                </a>
                
                <!-- Bouton Se Connecter - Pour les utilisateurs existants -->
                <a href="/login" class="block w-full bg-[#a54f0d] text-white py-3 rounded-lg font-semibold mb-6 hover:brightness-110 transition text-center">
                    Se Connecter
                </a>
                
                <p class="text-sm text-center text-black font-medium">
                    Nouveau client ? 
                    <a href="/register" class="underline font-semibold hover:text-[#a54f0d]">S'inscrire</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
