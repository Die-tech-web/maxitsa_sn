<?php
  $transactions = $transactions ?? []; // s'assure que $transactions est un tableau
  function getTransactionColor($type) {
      return match(strtolower($type)) {
          'depot'     => 'text-blue-600',
          'paiement'  => 'text-red-500',
          'retrait'   => 'text-orange-600',
          default     => 'text-black'
      };
  }

  function getTransactionBg($type) {
      return match(strtolower($type)) {
          'depot'     => 'bg-blue-500',
          'paiement'  => 'bg-pink-500',
          'retrait'   => 'bg-[#a65413]',
          default     => 'bg-gray-400'
      };
  }
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MAXITSA - Accueil Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
  </head>
  <body class="flex h-screen bg-gray-100 font-sans overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-48 bg-[#AC5810] text-white flex flex-col h-full">
      <div class="flex items-center justify-center gap-2 py-4 bg-[#AC5810] flex-shrink-0">
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
      <div class="p-3 flex-shrink-0">
        <button
          class="w-full bg-white text-black px-3 py-2 rounded text-sm flex items-center gap-2"
        >
          <i class="fa-solid fa-right-from-bracket"></i>
          Deconnexion
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-white flex flex-col h-full">
      <!-- Header fixe -->
      <header class="flex justify-between items-center p-4 bg-white border-b flex-shrink-0">
        <div>
          <h1 class="text-2xl font-bold">MAXITSA</h1>
          <p class="text-gray-600 text-sm">Systèmes de transfert et de paiements</p>
        </div>
        <div class="flex items-center gap-2 text-sm">
          <span>🔄</span>
          <span>Nom Client</span>
          <span class="text-lg">👤</span>
        </div>
      </header>

      <!-- Zone de contenu scrollable -->
      <div class="flex-1 overflow-y-auto">
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

          <!-- Transaction Panel -->
          <div>
                <div class="flex justify-center my-4">
                  <button id="toggle-transactions" class="bg-[#473523] text-white text-sm font-semibold px-4 py-2 rounded shadow">
                    Voir les 10 dernières transactions
                  </button>
                </div>

           <div id="transaction-list" class="p-4 hidden">
  <?php if (!empty($transactions)): ?>
    <?php foreach ($transactions as $transaction): ?>
      <div class="flex justify-between items-center py-2 border-b border-gray-200">
        <div>
          <p class="font-medium text-sm"><?= htmlspecialchars($transaction['description']) ?></p>
          <p class="text-xs text-gray-500"><?= $transaction['date'] ?> à <?= $transaction['time'] ?></p>
          <p class="text-xs text-gray-500">à <?= htmlspecialchars($transaction['location'] ?? 'N/A') ?></p>
        </div>
        <div class="text-right">
          <p class="<?= getTransactionColor($transaction['type']) ?> font-bold text-sm">
            <?= ($transaction['amount'] >= 0 ? '+' : '') . number_format($transaction['amount'], 0, ',', ' ') ?> CFA
          </p>
          <span class="text-xs <?= getTransactionBg($transaction['type']) ?> text-white px-2 py-1 rounded capitalize">
            <?= ucfirst($transaction['type']) ?>
          </span>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-center text-sm text-gray-500 mt-4">Aucune transaction récente trouvée.</p>
  <?php endif; ?>
</div>


            <!-- Footer Button -->
            <div class="text-center py-3 border-t border-gray-200">
              <a href="/transactions" class="bg-[#473523] text-white px-4 py-2 rounded text-sm flex items-center gap-2 mx-auto">
                Voir toutes les transactions
                <span>▼</span>
              </a>
            </div>
          </div>

        </div>
      </div>
    </main>
  <script>
  const btn = document.getElementById('toggle-transactions');
  const list = document.getElementById('transaction-list');

  btn.addEventListener('click', () => {
    list.classList.toggle('hidden');
    btn.textContent = list.classList.contains('hidden')
      ? 'Voir les 10 dernières transactions'
      : 'Masquer les transactions';
  });
</script>

  </body>
</html>
