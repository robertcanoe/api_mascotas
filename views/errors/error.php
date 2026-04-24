<section class="error-view">
    <h1><?= e($title ?? 'Error') ?></h1>
    <p><?= e($message ?? 'Ha ocurrido un error inesperado.') ?></p>
    <a class="cta-primary" href="<?= e(base_url('/')) ?>">Volver al inicio</a>
</section>
