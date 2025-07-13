<?php if (!empty($_SESSION['message'])): ?>
<div class="alert <?= $_SESSION['type'] ?>">
    <?= $_SESSION['message'] ?>
</div>
<?php unset($_SESSION['message'], $_SESSION['type']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['errors'])): ?>
<ul class="text-danger">
    <?php foreach ($_SESSION['errors'] as $champ => $msg): ?>
        <li><?= htmlspecialchars($msg) ?></li>
    <?php endforeach; ?>
    <?php unset($_SESSION['errors']); ?>
</ul>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion Agent - MAXITSA</title>
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
            padding: 3rem;
            max-width: 28rem;
            width: 100%;
        }

        .title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .title h1 {
            font-size: 1.875rem;
            font-weight: bold;
            color: #C4782A;
            letter-spacing: 0.025em;
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

        .submit-btn {
            background-color: #6B4423;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: 100%;
            margin-top: 1rem;
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

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #6b7280;
        }

        .register-link a {
            color: #C4782A;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">
            <h1>CONNEXION AGENT</h1>
        </div>

        <form method="POST" action="/agent/login">
            <div class="form-column">
                <div>
                    <input
                        type="text"
                        name="telephone"
                        placeholder="Numéro de téléphone"
                        class="input-field"
                        required
                        value="<?= htmlspecialchars($telephone ?? '') ?>"
                    />
                </div>

                <div>
                    <input
                        type="password"
                        name="password"
                        placeholder="Mot de passe"
                        class="input-field"
                        required
                    />
                </div>

                <button type="submit" class="submit-btn">
                    Se Connecter
                </button>
            </div>
        </form>

        <div class="register-link">
            Pas encore inscrit ? <a href="/agent/register">S'inscrire</a>
        </div>
    </div>
</body>
</html>