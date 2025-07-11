
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <title><?= isset($title) ? htmlspecialchars($title) : 'MaxitSA' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-[#101d0b] min-h-screen text-white">
    <!-- Header -->
    <?php if (isset($showHeader) && $showHeader): ?>
      <?php require_once __DIR__ . '/../partial/header.html.php'; ?>
    <?php endif; ?>

    <!-- Sidebar -->
    <?php if (isset($showSidebar) && $showSidebar): ?>
      <?php require_once __DIR__ . '/../partial/sidebar.html.php'; ?>
    <?php endif; ?>

    <main class="max-w-5xl mx-auto mt-8 p-8">
      <?php
        // Affiche le contenu spécifique à chaque page
        echo $contentForLayout;
      ?>
    </main>
  </body>
</html>