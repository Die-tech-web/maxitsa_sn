<?php
// Inclure le bootstrap
require_once __DIR__ . '/../../bootstrap.php';

// Vérifier que l'utilisateur est connecté (optionnel)
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Définir la page courante pour le sidebar
$current_page = 'home';
$user_phone = $current_user['phone'] ?? '776667777';
$client_name = $current_user['name'] ?? 'Nom Client';
?>


<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MAXITSA - Accueil Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
  </head>
  <body class="flex h-screen bg-gray-100 font-sans">
   
    <aside class="w-48 bg-[#AC5810] text-white flex flex-col">
     
      <div class="flex items-center justify-center gap-2 py-4 bg-[#AC5810]">
        <span class="text-xl">👤</span>
        <span class="text-sm font-semibold">776667777</span>
        <span class="text-xs">▼</span>
      </div>

     
      <nav class="flex flex-col gap-4 p-3 flex-1 justify-center">
        <button
          class="w-full flex items-center justify-start gap-3 bg-[#473523] px-3 py-2 rounded text-white text-base font-bold shadow border-l-2 border-white"
        >
          <i class="fa-solid fa-house text-xl"></i>
          HOME
        </button>
        <button
          class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded text-base font-semibold shadow border-l-2"
          style="border-left-color: #473523"
        >
          <i class="fa-solid fa-user text-xl"></i>
          Mes Comptes
        </button>
        <button
          class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded text-base font-semibold shadow border-l-2"
          style="border-left-color: #473523"
        >
          <i class="fa-solid fa-money-bill-transfer text-xl"></i>
          Paiements
        </button>
        <button
          class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded text-base font-semibold shadow border-l-2"
          style="border-left-color: #473523"
        >
          <i class="fa-solid fa-wallet text-xl"></i>
          Solde
        </button>
      </nav>

     
      <div class="p-3">
        <button
          class="w-full bg-white text-black px-3 py-2 rounded text-sm flex items-center gap-2"
        >
          <i class="fa-solid fa-right-from-bracket"></i>
          Deconnexion
        </button>
      </div>
    </aside>

    <main class="flex-1 bg-white">
      <header class="flex justify-between items-center p-4 bg-white border-b">
        <div>
          <h1 class="text-2xl font-bold">MAXITSA</h1>
          <p class="text-gray-600 text-sm">
            Systèmes de transfert et de paiements
          </p>
        </div>
        <div class="flex items-center gap-2 text-sm">
          <span>🔄</span>
          <span>Nom Client</span>
          <span class="text-lg">👤</span>
        </div>
      </header>

      <!-- Content Area -->
      <div class="p-4">
        <!-- Tabs and Balance -->
        <div class="flex gap-4 mb-4">
          <button
            class="flex-1 py-2 rounded-lg bg-white shadow-md text-lg font-semibold border-2 border-gray-200 border-l-4"
            style="border-left-color: #473523"
          >
            DEPOTS
          </button>
          <button
            class="flex-1 py-2 rounded-lg bg-white shadow-md text-lg font-semibold border-2 border-gray-200 border-l-4"
            style="border-left-color: #473523"
          >
            RETRAIT
          </button>
          <button
            class="flex-1 py-2 rounded-lg bg-white shadow-md text-lg font-semibold border-2 border-gray-200 border-l-4"
            style="border-left-color: #473523"
          >
            PAIEMENTS
          </button>
          <div
            class="flex-1 py-2 rounded-lg bg-white shadow-md text-lg font-semibold border-2 border-gray-200 border-l-4 text-center"
            style="border-left-color: #473523"
          >
            <div class="text-sm text-gray-600">SOLDE:</div>
            <div class="text-lg font-bold text-black">00fcfa</div>
          </div>
        </div>

        <!-- Balance Check Button -->
        <!-- <div class="flex justify-end mb-4">
          <button class="bg-orange-800 text-white px-4 py-2 rounded text-sm">
            Consulter solde
          </button>
        </div> -->

        <!-- Transaction Panel -->
        <div class="bg-white rounded-lg shadow-md border-2 border-blue-400">
          <!-- Header -->
          <div class="bg-[#473523] text-white text-center py-2 rounded-t-lg">
            <span class="text-sm font-semibold"
              >Voir les 10 derniers transactions</span
            >
          </div>

          <!-- Transaction List -->
          <div class="p-4 max-h-96 overflow-y-auto">
            <!-- Transaction Item -->
            <div
              class="flex justify-between items-center py-2 border-b border-gray-200"
            >
              <div>
                <p class="font-medium text-sm">Retrait</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-orange-600 font-bold text-sm">100000CFA</p>
                <span class="text-xs bg-[#a65413] text-white px-2 py-1 rounded"
                  >Retrait</span
                >
              </div>
            </div>

            <div
              class="flex justify-between items-center py-2 border-b border-gray-200"
            >
              <div>
                <p class="font-medium text-sm">Paiement Facture Senelec</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-red-500 font-bold text-sm">-45000CFA</p>
                <span class="text-xs bg-pink-500 text-white px-2 py-1 rounded"
                  >Paiement</span
                >
              </div>
            </div>

            <div
              class="flex justify-between items-center py-2 border-b border-gray-200"
            >
              <div>
                <p class="font-medium text-sm">Depot en espèces</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-blue-600 font-bold text-sm">+750000CFA</p>
                <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded"
                  >Depot</span
                >
              </div>
            </div>

            <div
              class="flex justify-between items-center py-2 border-b border-gray-200"
            >
              <div>
                <p class="font-medium text-sm">Paiement Facture Senelec</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-red-500 font-bold text-sm">-70000CFA</p>
                <span class="text-xs bg-pink-500 text-white px-2 py-1 rounded"
                  >Paiement</span
                >
              </div>
            </div>

            <div
              class="flex justify-between items-center py-2 border-b border-gray-200"
            >
              <div>
                <p class="font-medium text-sm">Depot en espèces</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-blue-600 font-bold text-sm">+750000CFA</p>
                <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded"
                  >Depot</span
                >
              </div>
            </div>

            <div
              class="flex justify-between items-center py-2 border-b border-gray-200"
            >
              <div>
                <p class="font-medium text-sm">Paiement Facture Senelec</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-red-500 font-bold text-sm">-90000CFA</p>
                <span class="text-xs bg-pink-500 text-white px-2 py-1 rounded"
                  >Paiement</span
                >
              </div>
            </div>

            <div
              class="flex justify-between items-center py-2 border-b border-gray-200"
            >
              <div>
                <p class="font-medium text-sm">Retrait</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-orange-600 font-bold text-sm">100000CFA</p>
                <span class="text-xs bg-[#a65413] text-white px-2 py-1 rounded"
                  >Retrait</span
                >
              </div>
            </div>

            <div
              class="flex justify-between items-center py-2 border-b border-gray-200"
            >
              <div>
                <p class="font-medium text-sm">Depot en espèces</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-blue-600 font-bold text-sm">+750000CFA</p>
                <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded"
                  >Depot</span
                >
              </div>
            </div>

            <div
              class="flex justify-between items-center py-2 border-b border-gray-200"
            >
              <div>
                <p class="font-medium text-sm">Paiement Facture Senelec</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-red-500 font-bold text-sm">-45000CFA</p>
                <span class="text-xs bg-pink-500 text-white px-2 py-1 rounded"
                  >Paiement</span
                >
              </div>
            </div>

            <div class="flex justify-between items-center py-2">
              <div>
                <p class="font-medium text-sm">Retrait</p>
                <p class="text-xs text-gray-500">19-05-2025 à 14h20</p>
                <p class="text-xs text-gray-500">à Dakar</p>
              </div>
              <div class="text-right">
                <p class="text-orange-600 font-bold text-sm">200000CFA</p>
                <span class="text-xs bg-[#a65413] text-white px-2 py-1 rounded"
                  >Retrait</span
                >
              </div>
            </div>
          </div>

          <!-- Footer Button -->
          <div class="text-center py-3 border-t border-gray-200">
            <button
              class="bg-[#473523] text-white px-4 py-2 rounded text-sm flex items-center gap-2 mx-auto"
            >
              Voir les toutes les transactions
              <span>▼</span>
            </button>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>
