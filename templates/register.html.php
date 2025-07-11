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
  <title>Créer Compte MAXITSA</title>
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
</head>
<body>
  <div class="container">
    <div class="title">
      <h1>CRÉER VOTRE COMPTE MAXITSA</h1>
    </div>
    
    <form class="form-grid" method="POST" action="/register" enctype="multipart/form-data">
      <div class="form-column">
        <div>
          <input 
            type="tel" 
            name="telephone" 
            placeholder="Numéro de téléphone" 
            class="input-field"
            required 
            value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>"
          />
        </div>

        <div>
          <input 
            type="text" 
            name="nom" 
            placeholder="Nom" 
            class="input-field"
            required 
            value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
          />
        </div>

        <div>
          <input 
            type="text" 
            name="prenom" 
            placeholder="Prénom" 
            class="input-field"
            required 
            value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
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

        <div>
          <input 
            type="password" 
            name="confirm_password" 
            placeholder="Confirmer le mot de passe" 
            class="input-field"
            required 
          />
        </div>

        <div>
          <input 
            type="text" 
            name="cni" 
            placeholder="CNI" 
            class="input-field"
            required 
            value="<?= htmlspecialchars($_POST['cni'] ?? '') ?>"
          />
        </div>

        <div>
          <input 
            type="text" 
            name="adresse" 
            placeholder="Adresse" 
            class="input-field"
            required 
            value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>"
          />
        </div>
      </div>

      <div class="form-column">
        <div class="upload-area">
          <p class="upload-text">Upload photo recto</p>
          <button 
            type="button" 
            class="upload-btn"
            onclick="document.getElementById('photo-recto').click()"
          >
            upload
          </button>
          <input type="file" id="photo-recto" name="photo_recto" accept="image/*" class="hidden" required />
        </div>

        <div class="upload-area">
          <p class="upload-text">Upload photo verso</p>
          <button 
            type="button" 
            class="upload-btn"
            onclick="document.getElementById('photo-verso').click()"
          >
            upload
          </button>
          <input type="file" id="photo-verso" name="photo_verso" accept="image/*" class="hidden" required />
        </div>
      </div>

      <div class="submit-area">
        <button type="submit" class="submit-btn">Créer Compte</button>
      </div>
    </form>
  </div>

  <script>
    document.getElementById('photo-recto').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const button = e.target.previousElementSibling;
        button.textContent = file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name;
        button.classList.add('uploaded');
      }
    });

    document.getElementById('photo-verso').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const button = e.target.previousElementSibling;
        button.textContent = file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name;
        button.classList.add('uploaded');
      }
    });
  </script>
</body>
</html>
