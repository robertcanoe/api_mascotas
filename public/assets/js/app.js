(function () {
    const state = {
        mascotas: [],
        filtered: [],
        selectedMascota: null,
    };

    const elements = {
        grid: document.getElementById('mascotasGrid'),
        searchInput: document.getElementById('searchInput'),
        statusMessage: document.getElementById('statusMessage'),
        panel: document.getElementById('adoptar-section'),
        closePanelButton: document.getElementById('closePanelButton'),
        form: document.getElementById('adopcionForm'),
        mascotaIdField: document.getElementById('mascotaIdField'),
        selectedLabel: document.getElementById('selectedMascotaLabel'),
        feedback: document.getElementById('adopcionFeedback'),
    };

    const basePath = (window.APP_CONFIG && window.APP_CONFIG.basePath) || '';
    const apiOrigin = (window.APP_CONFIG && window.APP_CONFIG.apiOrigin) || '';

    function buildApiUrl(path) {
        return `${apiOrigin}${basePath}${path}`;
    }

    function buildLocalPath(path) {
        return `${basePath}${path}`;
    }

    function setStatus(message, type) {
        if (!elements.statusMessage) {
            return;
        }

        elements.statusMessage.textContent = message || '';
        elements.statusMessage.classList.remove('error', 'success');
        if (type) {
            elements.statusMessage.classList.add(type);
        }
    }

    function setFeedback(message, type) {
        if (!elements.feedback) {
            return;
        }

        elements.feedback.textContent = message || '';
        elements.feedback.classList.remove('error', 'success');
        if (type) {
            elements.feedback.classList.add(type);
        }
    }

    function renderMascotas() {
        if (!elements.grid) {
            return;
        }

        elements.grid.innerHTML = '';

        if (state.filtered.length === 0) {
            const empty = document.createElement('p');
            empty.className = 'status-message';
            empty.textContent = elements.grid.dataset.emptyMessage || 'No hay resultados.';
            elements.grid.appendChild(empty);
            return;
        }

        state.filtered.forEach((mascota) => {
            const card = document.createElement('article');
            card.className = 'mascota-card';
            card.innerHTML = `
                <img src="${escapeHTML(mascota.foto_url || '')}" alt="Foto de ${escapeHTML(mascota.nombre || 'Mascota')}" loading="lazy">
                <div class="card-body">
                    <h3>${escapeHTML(mascota.nombre || 'Sin nombre')}</h3>
                    <p class="meta">${escapeHTML(mascota.especie || 'N/A')} · ${escapeHTML(String(mascota.edad || 0))} anios</p>
                    <p class="desc">${escapeHTML(mascota.descripcion || '')}</p>
                    <button type="button" class="adopt-btn" data-adopt-id="${escapeHTML(String(mascota.id || ''))}" data-adopt-nombre="${escapeHTML(mascota.nombre || '')}">Adoptar</button>
                </div>
            `;

            elements.grid.appendChild(card);
        });
    }

    function escapeHTML(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function applyFilter(term) {
        const normalized = (term || '').trim().toLowerCase();
        if (normalized === '') {
            state.filtered = [...state.mascotas];
            return;
        }

        state.filtered = state.mascotas.filter((mascota) => {
            const haystack = `${mascota.nombre || ''} ${mascota.especie || ''} ${mascota.edad || ''}`.toLowerCase();
            return haystack.includes(normalized);
        });
    }

    function openAdoptionPanel(mascotaId, mascotaNombre) {
        state.selectedMascota = { id: Number(mascotaId), nombre: mascotaNombre || '' };

        if (elements.panel) {
            elements.panel.classList.remove('hidden');
            elements.panel.setAttribute('aria-hidden', 'false');
        }

        if (elements.mascotaIdField) {
            elements.mascotaIdField.value = String(mascotaId);
        }

        if (elements.selectedLabel) {
            elements.selectedLabel.textContent = `Solicitud para: ${mascotaNombre}`;
        }

        setFeedback('', '');
        elements.form?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function closeAdoptionPanel() {
        if (elements.panel) {
            elements.panel.classList.add('hidden');
            elements.panel.setAttribute('aria-hidden', 'true');
        }

        if (elements.form) {
            elements.form.reset();
        }

        state.selectedMascota = null;
        setFeedback('', '');
    }

    async function loadMascotas() {
        try {
            setStatus('Cargando mascotas...', '');
            const response = await fetch(buildApiUrl('/mascotas'));
            const payload = await response.json();

            if (!response.ok) {
                throw new Error(payload.message || payload.error || 'No fue posible cargar mascotas.');
            }

            state.mascotas = Array.isArray(payload.data) ? payload.data : [];
            applyFilter(elements.searchInput ? elements.searchInput.value : '');
            renderMascotas();
            setStatus(`Mostrando ${state.filtered.length} mascotas.`, 'success');
        } catch (error) {
            setStatus(error.message || 'Error de red al obtener mascotas.', 'error');
        }
    }

    async function submitAdopcion(event) {
        event.preventDefault();

        if (!elements.form) {
            return;
        }

        if (!state.selectedMascota || !elements.mascotaIdField?.value) {
            setFeedback('Primero selecciona una mascota con el boton Adoptar.', 'error');
            return;
        }

        const formData = new FormData(elements.form);
        const payload = {
            mascota_id: Number(formData.get('mascota_id') || 0),
            solicitante: String(formData.get('solicitante') || '').trim(),
            email: String(formData.get('email') || '').trim(),
            mensaje: String(formData.get('mensaje') || '').trim(),
        };

        try {
            setFeedback('Enviando solicitud...', '');

            const response = await fetch(buildApiUrl('/adopciones'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (!response.ok) {
                const message = result.message || result.error || 'No fue posible enviar la solicitud.';
                throw new Error(message);
            }

            setFeedback(result.message || 'Solicitud enviada correctamente.', 'success');
            elements.form.reset();
            if (elements.mascotaIdField) {
                elements.mascotaIdField.value = String(state.selectedMascota.id);
            }
        } catch (error) {
            setFeedback(error.message || 'Error de red al enviar la solicitud.', 'error');
        }
    }

    function bindEvents() {
        elements.searchInput?.addEventListener('input', (event) => {
            applyFilter(event.target.value || '');
            renderMascotas();
            setStatus(`Mostrando ${state.filtered.length} mascotas.`, '');
        });

        elements.grid?.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            const button = target.closest('[data-adopt-id]');
            if (!(button instanceof HTMLElement)) {
                return;
            }

            const mascotaId = button.dataset.adoptId || '';
            const mascotaNombre = button.dataset.adoptNombre || 'Mascota';
            if (mascotaId === '') {
                return;
            }

            openAdoptionPanel(mascotaId, mascotaNombre);
        });

        elements.form?.addEventListener('submit', submitAdopcion);
        elements.closePanelButton?.addEventListener('click', closeAdoptionPanel);
    }

    async function registerServiceWorker() {
        if (!('serviceWorker' in navigator)) {
            return;
        }

        try {
            const swPath = buildLocalPath('/sw.js');
            const scope = `${basePath || ''}/`;
            await navigator.serviceWorker.register(swPath, { scope });
        } catch (error) {
            console.warn('Service worker registration failed', error);
        }
    }

    bindEvents();
    loadMascotas();
    registerServiceWorker();
})();
