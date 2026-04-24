<article class="mascota-card" data-id="<?= e((string) ($mascota['id'] ?? '')) ?>">
    <img
        src="<?= e((string) ($mascota['foto_url'] ?? '')) ?>"
        alt="Foto de <?= e((string) ($mascota['nombre'] ?? 'Mascota')) ?>"
        loading="lazy"
    >

    <div class="card-body">
        <h3><?= e((string) ($mascota['nombre'] ?? 'Sin nombre')) ?></h3>
        <p class="meta">
            <?= e((string) ($mascota['especie'] ?? 'N/A')) ?> · <?= e((string) ($mascota['edad'] ?? '0')) ?> anios
        </p>
        <p class="desc"><?= e((string) ($mascota['descripcion'] ?? '')) ?></p>
        <button type="button" class="adopt-btn" data-adopt-id="<?= e((string) ($mascota['id'] ?? '')) ?>" data-adopt-nombre="<?= e((string) ($mascota['nombre'] ?? '')) ?>">
            Adoptar
        </button>
    </div>
</article>
