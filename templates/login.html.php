<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Connexion - Maxistsa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-3xl shadow-2xl px-8 py-12 flex flex-col md:flex-row items-center justify-center gap-12 max-w-4xl w-full mx-4 border-2" style="border-color: #AC5810;">
        
        <div class="flex-1 flex flex-col items-center justify-center">
            <div class="relative flex flex-col items-center">
                <div class="bg-gradient-to-br from-blue-200 via-white to-orange-100 w-56 h-56 rounded-full shadow-xl flex items-center justify-center border-2" style="border-color: #AC5810;">
                    <img src="images/uploads/om.png" alt="Orangemon Logo" class="w-20 h-20 object-contain" />
                </div>
                <h1 class="text-3xl font-extrabold tracking-widest mt-6" style="color: #473523;">MAXISTSA</h1>
                <p class="text-lg tracking-wide font-semibold mt-1" style="color: #473523;">Bienvenue sur votre espace client</p>
            </div>
        </div>

        <div class="flex-1 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-2xl px-8 py-8 w-full max-w-md border-2" style="border-color: #AC5810;">
                
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div style="position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 15px; border-radius: 8px; z-index: 1000;">
                        <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($message) && $messageType === 'error'): ?>
                    <div class="alert error mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <?= htmlspecialchars($message) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert error mb-4">
                        <ul class="list-disc list-inside">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form class="flex flex-col gap-6" method="POST" action="/login">
                    <div>
                        <input
                            type="text"
                            name="telephone"
                            value="<?= htmlspecialchars($telephone ?? '') ?>"
                            placeholder="Numéro de téléphone"
                            class="w-full border-2 rounded-xl px-4 py-3 text-base focus:outline-none focus:ring-2 focus:ring-blue-300 transition"
                            style="border-color: #AC5810;"
                        />
                    </div>

                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            placeholder="Mot de passe"
                            id="password"
                            class="w-full border-2 rounded-xl px-4 py-3 text-base focus:outline-none focus:ring-2 focus:ring-blue-300 transition pr-20"
                            style="border-color: #AC5810;"
                        />
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 font-bold text-gray-600 hover:text-blue-600 text-sm"
                        >
                            AFFICHER
                        </button>
                    </div>

                    <button
                        type="submit"
                        class="w-full border-2 rounded-xl py-3 font-semibold text-base hover:bg-gray-50 transition shadow-sm"
                        style="border-color: #AC5810;"
                    >
                        Se Connecter
                    </button>
                </form>

                <div class="text-right mt-4 text-sm font-medium text-gray-600">
                    Vous n'avez pas de compte ?
                    <a href="/register" class="font-bold ml-2 hover:underline" style="color: #473523;">Créer un compte</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById("password");
            pwd.type = pwd.type === "password" ? "text" : "password";
        }

        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f3f4f6;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .container {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        padding: 2rem;
        max-width: 64rem;
        width: 100%;
    }

    .title {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .title h1 {
        font-size: 1.875rem;
        font-weight: bold;
        color: #C4782A;
        letter-spacing: 0.025em;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .form-column {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .input-field {
        width: 100%;
        border: 2px solid #C4782A;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        color: #374151;
        transition: all 0.3s ease;
    }

    .input-field:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(196, 120, 42, 0.5);
    }

    .input-field::placeholder {
        color: #6b7280;
    }

    .upload-area {
        border: 2px solid #C4782A;
        border-radius: 0.75rem;
        padding: 1.5rem;
        text-align: center;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        background-color: #f9fafb;
    }

    .upload-text {
        color: #374151;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .upload-btn {
        background-color: #d1d5db;
        color: #374151;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 0 auto;
    }

    .upload-btn:hover {
        background-color: #9ca3af;
    }

    .upload-btn.uploaded {
        background-color: #dcfce7;
        color: #166534;
    }

    .hidden {
        display: none;
    }

    .submit-btn {
        background-color: #6B4423;
        color: white;
        font-weight: 600;
        padding: 0.75rem 4rem;
        border-radius: 0.75rem;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
        display: block;
        margin-top: 2rem;
    }

    .submit-btn:hover {
        background-color: #5A3A1F;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* Messages d'alerte */
    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        animation: slideIn 0.3s ease-out;
    }

    .alert.success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .alert.error {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    .text-danger {
        color: #dc2626;
        background-color: #fee2e2;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (min-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .title h1 {
            font-size: 2.25rem;
        }

        .submit-area {
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
        }
    }
</style>