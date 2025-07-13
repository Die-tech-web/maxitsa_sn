<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAXITSA - Service Commercial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #AC5810;
            border-radius: 3px;
        }
    </style>
</head>
<body class="flex h-screen bg-gray-100 font-sans overflow-hidden">
    
    <aside class="w-48 bg-[#AC5810] text-white flex flex-col h-full">
        <div class="flex items-center justify-center gap-2 py-4 bg-[#AC5810] flex-shrink-0">
            <span class="text-xl">👤</span>
            <span class="text-sm font-semibold">Agent001</span>
            <span class="text-xs">▼</span>
        </div>

        <nav class="flex flex-col gap-4 p-3 flex-1 justify-center">
            <button class="w-full flex items-center justify-start gap-3 bg-[#473523] px-3 py-2 rounded text-white text-base font-bold shadow border-l-2 border-white">
                <i class="fa-solid fa-search text-xl"></i>
                RECHERCHE
            </button>
            <button class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded text-base font-semibold shadow border-l-2" style="border-left-color: #473523">
                <i class="fa-solid fa-users text-xl"></i>
                Clients
            </button>
            <button class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded text-base font-semibold shadow border-l-2" style="border-left-color: #473523">
                <i class="fa-solid fa-chart-line text-xl"></i>
                Rapports
            </button>
            <button class="w-full flex items-center justify-start gap-3 bg-white text-black px-3 py-2 rounded text-base font-semibold shadow border-l-2" style="border-left-color: #473523">
                <i class="fa-solid fa-cog text-xl"></i>
                Paramètres
            </button>
        </nav>

        <div class="p-3 flex-shrink-0">
            <button class="w-full bg-white text-black px-3 py-2 rounded text-sm flex items-center gap-2">
                <i class="fa-solid fa-right-from-bracket"></i>
                Déconnexion
            </button>
        </div>
    </aside>

    <main class="flex-1 bg-white flex flex-col h-full">
        <header class="flex justify-between items-center p-4 bg-white border-b flex-shrink-0">
            <div>
                <h1 class="text-2xl font-bold">MAXITSA</h1>
                <p class="text-gray-600 text-sm">Service Commercial - Recherche de Comptes</p>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span>🔄</span>
                <span>Agent Commercial</span>
                <span class="text-lg">👤</span>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto scrollbar-thin">
            <div class="p-4">
                
                <div class="bg-white rounded-lg shadow-md border-2 border-[#AC5810] mb-6 max-w-xl ml-auto">
                    <div class="bg-[#473523] text-white text-center py-2 rounded-t-lg">
                        <span class="text-base font-semibold">🔍 Rechercher un Compte Client</span>
                    </div>
                    <div class="p-3">
                        <div class="flex gap-2 items-end">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Numéro de téléphone</label>
                                <input type="tel" id="phoneSearch" placeholder="Ex: 776667777" 
                                       class="w-full px-2 py-1.5 border-2 border-gray-300 rounded-md focus:border-[#AC5810] focus:outline-none text-sm">
                            </div>
                            <button onclick="searchAccount()" class="bg-[#AC5810] text-white px-3 py-1.5 rounded-md hover:bg-[#8B4710] transition-colors font-medium text-sm">
                                <i class="fa-solid fa-search mr-1"></i>Rechercher
                            </button>
                        </div>
                    </div>
                </div>

                <div id="accountInfo" class="bg-white rounded-lg shadow-md border-2 border-blue-400 mb-6 hidden">
                    <div class="bg-[#473523] text-white text-center py-3 rounded-t-lg">
                        <span class="text-lg font-semibold">📋 Informations du Compte</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-gray-700 mb-2">Client</h3>
                                <p class="text-lg font-bold" id="clientName">Nom du Client</p>
                                <p class="text-sm text-gray-600" id="clientPhone">776667777</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-gray-700 mb-2">Solde Actuel</h3>
                                <p class="text-2xl font-bold text-green-600" id="accountBalance">0 FCFA</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-gray-700 mb-2">Statut</h3>
                                <span class="inline-block bg-green-500 text-white px-3 py-1 rounded-full text-sm">Actif</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="transactionHistory" class="bg-white rounded-lg shadow-md border-2 border-blue-400 hidden">
                    <div class="bg-[#473523] text-white py-3 rounded-t-lg">
                        <div class="flex justify-between items-center px-6">
                            <span class="text-lg font-semibold">📊 Historique des Transactions</span>
                            <div class="flex gap-2">
                                <select id="typeFilter" class="px-3 py-1 rounded text-black text-sm">
                                    <option value="">Tous les types</option>
                                    <option value="depot">Dépôt</option>
                                    <option value="retrait">Retrait</option>
                                    <option value="paiement">Paiement</option>
                                </select>
                                <input type="date" id="dateFilter" class="px-3 py-1 rounded text-black text-sm">
                                <button onclick="filterTransactions()" class="bg-[#AC5810] text-white px-3 py-1 rounded text-sm hover:bg-[#8B4710]">
                                    <i class="fa-solid fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-3">🕐 10 Dernières Transactions</h3>
                            <div id="recentTransactions" class="space-y-3">
                            </div>
                        </div>

                        <div class="text-center py-4 border-t border-gray-200">
                            <button onclick="showAllTransactions()" class="bg-[#473523] text-white px-6 py-3 rounded-lg hover:bg-[#362A1C] transition-colors font-semibold">
                                <i class="fa-solid fa-list mr-2"></i>Voir toutes les transactions
                            </button>
                        </div>
                    </div>
                </div>

                <div id="allTransactions" class="bg-white rounded-lg shadow-md border-2 border-green-400 mt-6 hidden">
                    <div class="bg-[#473523] text-white py-3 rounded-t-lg">
                        <div class="flex justify-between items-center px-6">
                            <span class="text-lg font-semibold">📋 Toutes les Transactions</span>
                            <button onclick="hideAllTransactions()" class="text-white hover:text-gray-300">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="mb-4 flex gap-4 items-center">
                            <input type="text" id="searchTransaction" placeholder="Rechercher une transaction..." 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:border-[#AC5810] focus:outline-none">
                            <select id="allTypeFilter" class="px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Tous les types</option>
                                <option value="depot">Dépôt</option>
                                <option value="retrait">Retrait</option>
                                <option value="paiement">Paiement</option>
                            </select>
                            <input type="date" id="allDateFilter" class="px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div id="allTransactionsList" class="space-y-3 max-h-96 overflow-y-auto scrollbar-thin">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>


    <script>
        const sampleTransactions = [
            { id: 1, type: 'retrait', amount: 100000, date: '2025-01-15', time: '14:20', location: 'Dakar', description: 'Retrait' },
            { id: 2, type: 'paiement', amount: -45000, date: '2025-01-15', time: '13:15', location: 'Dakar', description: 'Paiement Facture Senelec' },
            { id: 3, type: 'depot', amount: 750000, date: '2025-01-15', time: '10:30', location: 'Dakar', description: 'Dépôt en espèces' },
            { id: 4, type: 'paiement', amount: -70000, date: '2025-01-14', time: '16:45', location: 'Dakar', description: 'Paiement Facture Sonatel' },
            { id: 5, type: 'depot', amount: 500000, date: '2025-01-14', time: '11:20', location: 'Dakar', description: 'Dépôt en espèces' },
            { id: 6, type: 'retrait', amount: 200000, date: '2025-01-13', time: '15:10', location: 'Dakar', description: 'Retrait' },
            { id: 7, type: 'paiement', amount: -25000, date: '2025-01-13', time: '12:30', location: 'Dakar', description: 'Paiement Orange Money' },
            { id: 8, type: 'depot', amount: 300000, date: '2025-01-12', time: '09:15', location: 'Dakar', description: 'Dépôt en espèces' },
            { id: 9, type: 'paiement', amount: -80000, date: '2025-01-12', time: '14:25', location: 'Dakar', description: 'Paiement Facture Senelec' },
            { id: 10, type: 'retrait', amount: 150000, date: '2025-01-11', time: '16:00', location: 'Dakar', description: 'Retrait' }
        ];

        function searchAccount() {
            const phone = document.getElementById('phoneSearch').value;
            if (!phone) {
                alert('Veuillez entrer un numéro de téléphone');
                return;
            }

            
            setTimeout(() => {
                document.getElementById('accountInfo').classList.remove('hidden');
                document.getElementById('transactionHistory').classList.remove('hidden');
                
                document.getElementById('clientName').textContent = 'Amadou Diallo';
                document.getElementById('clientPhone').textContent = phone;
                document.getElementById('accountBalance').textContent = '1,250,000 FCFA';
                
                loadRecentTransactions();
                
                document.getElementById('accountInfo').scrollIntoView({ behavior: 'smooth' });
            }, 500);
        }

        function loadRecentTransactions() {
            const container = document.getElementById('recentTransactions');
            container.innerHTML = '';
            
            sampleTransactions.slice(0, 10).forEach(transaction => {
                const transactionElement = createTransactionElement(transaction);
                container.appendChild(transactionElement);
            });
        }

        function createTransactionElement(transaction) {
            const div = document.createElement('div');
            div.className = 'flex justify-between items-center py-3 border-b border-gray-200';
            
            const typeColors = {
                'depot': { text: 'text-blue-600', bg: 'bg-blue-500', sign: '+' },
                'retrait': { text: 'text-orange-600', bg: 'bg-orange-500', sign: '' },
                'paiement': { text: 'text-red-500', bg: 'bg-pink-500', sign: '' }
            };
            
            const color = typeColors[transaction.type];
            const amount = transaction.amount > 0 ? `${color.sign}${transaction.amount.toLocaleString()}` : transaction.amount.toLocaleString();
            
            div.innerHTML = `
                <div>
                    <p class="font-medium text-sm">${transaction.description}</p>
                    <p class="text-xs text-gray-500">${transaction.date} à ${transaction.time}</p>
                    <p class="text-xs text-gray-500">à ${transaction.location}</p>
                </div>
                <div class="text-right">
                    <p class="${color.text} font-bold text-sm">${amount}CFA</p>
                    <span class="text-xs ${color.bg} text-white px-2 py-1 rounded capitalize">${transaction.type}</span>
                </div>
            `;
            
            return div;
        }

        function showAllTransactions() {
            document.getElementById('allTransactions').classList.remove('hidden');
            loadAllTransactions();
            document.getElementById('allTransactions').scrollIntoView({ behavior: 'smooth' });
        }

        function hideAllTransactions() {
            document.getElementById('allTransactions').classList.add('hidden');
        }

        function loadAllTransactions() {
            const container = document.getElementById('allTransactionsList');
            container.innerHTML = '';
            
            sampleTransactions.forEach(transaction => {
                const transactionElement = createTransactionElement(transaction);
                container.appendChild(transactionElement);
            });
        }

        function filterTransactions() {
            const type = document.getElementById('typeFilter').value;
            const date = document.getElementById('dateFilter').value;
            
            let filtered = sampleTransactions;
            
            if (type) {
                filtered = filtered.filter(t => t.type === type);
            }
            
            if (date) {
                filtered = filtered.filter(t => t.date === date);
            }
            
            const container = document.getElementById('recentTransactions');
            container.innerHTML = '';
            
            filtered.slice(0, 10).forEach(transaction => {
                const transactionElement = createTransactionElement(transaction);
                container.appendChild(transactionElement);
            });
        }

        // Add event listeners for real-time filtering
        document.getElementById('allTypeFilter').addEventListener('change', function() {
            filterAllTransactions();
        });

        document.getElementById('allDateFilter').addEventListener('change', function() {
            filterAllTransactions();
        });

        document.getElementById('searchTransaction').addEventListener('input', function() {
            filterAllTransactions();
        });

        function filterAllTransactions() {
            const type = document.getElementById('allTypeFilter').value;
            const date = document.getElementById('allDateFilter').value;
            const search = document.getElementById('searchTransaction').value.toLowerCase();
            
            let filtered = sampleTransactions;
            
            if (type) {
                filtered = filtered.filter(t => t.type === type);
            }
            
            if (date) {
                filtered = filtered.filter(t => t.date === date);
            }
            
            if (search) {
                filtered = filtered.filter(t => 
                    t.description.toLowerCase().includes(search) || 
                    t.location.toLowerCase().includes(search)
                );
            }
            
            const container = document.getElementById('allTransactionsList');
            container.innerHTML = '';
            
            filtered.forEach(transaction => {
                const transactionElement = createTransactionElement(transaction);
                container.appendChild(transactionElement);
            });
        }

        document.getElementById('phoneSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchAccount();
            }
        });
    </script>
</body>
</html>