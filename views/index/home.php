<?php $initialMascotas = is_array($mascotas ?? null) ? $mascotas : []; ?>

<section class="hero">
    <p class="eyebrow">Adopcion responsable</p>
    <h1>Encuentra a tu nueva compania</h1>
    <p class="hero-copy">
        Cada adopcion transforma dos vidas. Explora las mascotas disponibles y envia tu solicitud en minutos.
    </p>
    <a href="#mascotas-section" class="cta-primary">Ver mascotas</a>
</section>

<section id="mascotas-section" class="content-block">
    <div class="block-head">
        <h2>Mascotas disponibles</h2>
        <label class="search-wrap" for="searchInput">
            <span>Buscar</span>
            <input id="searchInput" type="search" placeholder="Nombre, especie o edad" autocomplete="off">
        </label>
    </div>

    <p id="statusMessage" class="status-message" role="status" aria-live="polite"></p>

    <div id="mascotasGrid" class="mascotas-grid" data-empty-message="No hay mascotas que coincidan con el filtro.">
        <?php foreach ($initialMascotas as $mascota): ?>
            <?php include VIEWS_PATH . '/mascotas/partials/card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<section id="adoptar-section" class="content-block adoption-panel hidden" aria-hidden="true">
    <div class="adoption-head">
        <h2>Solicitud de adopcion</h2>
        <button id="closePanelButton" type="button" class="ghost-btn">Cerrar</button>
    </div>

    <p id="selectedMascotaLabel" class="selected-label">Selecciona una mascota para iniciar.</p>

    <form id="adopcionForm" novalidate>
        <input type="hidden" name="mascota_id" id="mascotaIdField">

        <label for="solicitanteField">Nombre del solicitante</label>
        <input id="solicitanteField" name="solicitante" type="text" maxlength="120" required>

        <label for="emailField">Correo electronico</label>
        <input id="emailField" name="email" type="email" maxlength="150" required>

        <label for="mensajeField">Mensaje (opcional)</label>
        <textarea id="mensajeField" name="mensaje" rows="4" maxlength="500"></textarea>

        <button id="submitAdopcionButton" type="submit" class="cta-primary">Enviar solicitud</button>
    </form>

    <p id="adopcionFeedback" class="status-message" role="status" aria-live="polite"></p>
</section>

<section id="faq-section" class="content-block faq-block">
    <h2>FAQ rapida</h2>
    <ul>
        <li>Las solicitudes se revisan en menos de 48 horas.</li>
        <li>Se requiere una entrevista breve antes de la entrega.</li>
        <li>El equipo puede solicitar informacion adicional para validar el entorno.</li>
    </ul>
</section>
