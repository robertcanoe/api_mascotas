<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#4CAF50">
    <title><?= e($title ?? 'Protectora de Mascotas') ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= e(base_url('/icons/favicon.svg')) ?>">
    <link rel="alternate icon" href="<?= e(base_url('/icons/favicon.svg')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="manifest" href="<?= e(base_url('/manifest.json')) ?>">
    <link rel="stylesheet" href="<?= e(base_url('/assets/css/app.css')) ?>">
</head>
<body>
    <div class="page-bg" aria-hidden="true"></div>
    <?php include VIEWS_PATH . '/includes/nav.php'; ?>

    <main class="page-main">
        <?= $content ?? '' ?>
    </main>

    <?php include VIEWS_PATH . '/includes/footer.php'; ?>

    <script>
        window.APP_CONFIG = {
            basePath: <?= json_encode(BASE_PATH, JSON_UNESCAPED_UNICODE) ?>,
            apiOrigin: ""
        };
    </script>
    <script src="<?= e(base_url('/assets/js/app.js')) ?>" defer></script>
</body>
</html>
