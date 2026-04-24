<?php $title = 'Error 404'; ?>
<section class="error-view">
    <h1>404</h1>
    <p><?= e($message ?? 'No encontramos lo que buscas.') ?></p>
    <a class="cta-primary" href="<?= e(base_url('/')) ?>">Ir al inicio</a>
</section>
