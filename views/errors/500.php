<?php $title = 'Error 500'; ?>
<section class="error-view">
    <h1>500</h1>
    <p><?= e($message ?? 'Error interno del servidor.') ?></p>
    <a class="cta-primary" href="<?= e(base_url('/')) ?>">Volver</a>
</section>
