const uid = () => 'c-' + Math.random().toString(36).slice(2, 9);
const getNowISO = () => new Date().toISOString();

function loadCollections() {
  try {
    return JSON.parse(localStorage.getItem('minhas_colecoes_v1') || '[]');
  } catch (e) {
    return [];
  }
}

function saveCollections(list) {
  localStorage.setItem('minhas_colecoes_v1', JSON.stringify(list));
}

let collections = loadCollections();

const collectionsGrid = document.getElementById('collectionsGrid');
const emptyState = document.getElementById('emptyState');
const statCollections = document.getElementById('statCollections');
const statItems = document.getElementById('statItems');
const statUpdated = document.getElementById('statUpdated');
const searchInput = document.getElementById('searchInput');
const sortSelect = document.getElementById('sortSelect');

function updateStats() {
  statCollections.textContent = collections.length;
  const totalItems = collections.reduce((s, c) => s + (c.items?.length || 0), 0);
  statItems.textContent = totalItems;
  const latest = collections.reduce((a, c) => (!a || c.updatedAt > a ? c.updatedAt : a), null);
  statUpdated.textContent = latest ? new Date(latest).toLocaleString() : '—';
}

function escapeHtml(s) {
  if (!s) return '';
  return s.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;');
}

function renderCollections() {
  collectionsGrid.innerHTML = '';
  const q = (searchInput.value || '').toLowerCase().trim();
  const sortBy = sortSelect.value;

  let arr = [...collections];
  if (sortBy === 'name') arr.sort((a, b) => a.name.localeCompare(b.name));
  else if (sortBy === 'items_desc') arr.sort((a, b) => (b.items?.length || 0) - (a.items?.length || 0));
  else arr.sort((a, b) => (b.updatedAt || '').localeCompare(a.updatedAt || ''));

  if (q) arr = arr.filter(c => c.name.toLowerCase().includes(q) || (c.description || '').toLowerCase().includes(q));

  emptyState.classList.toggle('d-none', arr.length !== 0);

  arr.forEach(col => {
    const colEl = document.createElement('div');
    colEl.className = 'col-12 col-sm-6 col-lg-4';
    colEl.innerHTML = `
      <div class="card collection-card position-relative">
        <div class="card-body">
          <div class="d-flex align-items-start">
            <div class="me-3">
              <div style="width:56px;height:56px;border-radius:10px;background:${col.color || '#6f42c1'};
                          display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;">
                ${(col.name || '').split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()}
              </div>
            </div>
            <div class="flex-grow-1">
              <h6 class="card-title mb-1">${escapeHtml(col.name)}</h6>
              <div class="text-muted small mb-2">${escapeHtml(col.description || '')}</div>
              <div class="small text-muted">Itens: <span class="fw-semibold">${col.items?.length || 0}</span></div>
            </div>
            <div class="ms-2 text-end">
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                  <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item view-items" href="#" data-id="${col.id}"><i class="bi bi-eye me-2"></i> Ver itens</a></li>
                  <li><a class="dropdown-item edit-collection" href="#" data-id="${col.id}"><i class="bi bi-pencil me-2"></i> Editar</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item text-danger delete-collection" href="#" data-id="${col.id}"><i class="bi bi-trash me-2"></i> Eliminar</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer small text-muted d-flex justify-content-between">
          <div>Atualizado: ${col.updatedAt ? new Date(col.updatedAt).toLocaleString() : '—'}</div>
          <div>${col.items?.length || 0} items</div>
        </div>
      </div>
    `;
    collectionsGrid.appendChild(colEl);
  });

  document.querySelectorAll('.view-items').forEach(btn => btn.addEventListener('click', e => {
    e.preventDefault();
    openItemsModal(btn.dataset.id);
  }));

  document.querySelectorAll('.edit-collection').forEach(btn => btn.addEventListener('click', e => {
    e.preventDefault();
    openEditModal(btn.dataset.id);
  }));

  document.querySelectorAll('.delete-collection').forEach(btn => btn.addEventListener('click', e => {
    e.preventDefault();
    if (confirm('Eliminar esta coleção?')) {
      collections = collections.filter(c => c.id !== btn.dataset.id);
      saveCollections(collections);
      renderCollections();
    }
  }));

  updateStats();
}

const collectionForm = document.getElementById('collectionForm');
const modalEl = document.getElementById('addCollectionModal');
const bootstrapModal = new bootstrap.Modal(modalEl);

collectionForm.addEventListener('submit', e => {
  e.preventDefault();
  if (!collectionForm.checkValidity()) {
    collectionForm.classList.add('was-validated');
    return;
  }

  const id = document.getElementById('collectionId').value;
  const name = document.getElementById('collectionName').value.trim();
  const desc = document.getElementById('collectionDescription').value.trim();
  const color = document.getElementById('collectionColor').value;

  if (id) {
    const idx = collections.findIndex(c => c.id === id);
    if (idx >= 0) {
      Object.assign(collections[idx], { name, description: desc, color, updatedAt: getNowISO() });
    }
  } else {
    collections.unshift({ id: uid(), name, description: desc, color, items: [], updatedAt: getNowISO() });
  }

  saveCollections(collections);
  collectionForm.reset();
  collectionForm.classList.remove('was-validated');
  bootstrapModal.hide();
  renderCollections();
});

function openEditModal(id) {
  const col = collections.find(c => c.id === id);
  if (!col) return;
  document.getElementById('collectionId').value = col.id;
  document.getElementById('collectionName').value = col.name;
  document.getElementById('collectionDescription').value = col.description || '';
  document.getElementById('collectionColor').value = col.color || '#6f42c1';
  bootstrapModal.show();
}

const viewItemsModalEl = document.getElementById('viewItemsModal');
const viewItemsBs = new bootstrap.Modal(viewItemsModalEl);
let currentViewingId = null;

function openItemsModal(id) {
  currentViewingId = id;
  const c = collections.find(x => x.id === id);
  if (!c) return;
  document.getElementById('itemsModalTitle').textContent = `Itens — ${c.name}`;
  renderItemsList();
  viewItemsBs.show();
}

function renderItemsList() {
  const itemsList = document.getElementById('itemsList');
  itemsList.innerHTML = '';
  const c = collections.find(x => x.id === currentViewingId);
  if (!c) return;
  c.items.forEach((it, idx) => {
    const li = document.createElement('li');
    li.className = 'list-group-item d-flex justify-content-between align-items-center';
    li.innerHTML = `<div>${escapeHtml(it)}</div><button class="btn btn-sm btn-outline-danger remove-item" data-idx="${idx}"><i class="bi bi-trash"></i></button>`;
    itemsList.appendChild(li);
  });
  document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', e => {
      const idx = +btn.dataset.idx;
      const c = collections.find(x => x.id === currentViewingId);
      c.items.splice(idx, 1);
      c.updatedAt = getNowISO();
      saveCollections(collections);
      renderItemsList();
      renderCollections();
    });
  });
}

document.getElementById('addItemBtn').addEventListener('click', () => {
  const val = document.getElementById('itemNameInput').value.trim();
  if (!val) return;
  const c = collections.find(x => x.id === currentViewingId);
  c.items.push(val);
  c.updatedAt = getNowISO();
  saveCollections(collections);
  document.getElementById('itemNameInput').value = '';
  renderItemsList();
  renderCollections();
});

searchInput.addEventListener('input', renderCollections);
sortSelect.addEventListener('change', renderCollections);

document.getElementById('importBtn').addEventListener('click', () => {
  const json = prompt('Cole o JSON de coleções para importar (vai substituir as coleções atuais):');
  if (!json) return;
  try {
    const parsed = JSON.parse(json);
    if (!Array.isArray(parsed)) throw new Error('Formato inválido');
    collections = parsed;
    saveCollections(collections);
    renderCollections();
    alert('Importado com sucesso.');
  } catch (err) {
    alert('Erro ao importar: ' + err.message);
  }
});

if (collections.length === 0) {
  collections = [
    { id: uid(), name: "Figuras de ação", description: "Bonecos e figuras colecionáveis", color: "#f59e0b", items: ["Figura A", "Figura B"], updatedAt: getNowISO() },
    { id: uid(), name: "Livros de fantasia", description: "Edições especiais", color: "#06b6d4", items: ["Livro X", "Livro Y"], updatedAt: getNowISO() }
  ];
  saveCollections(collections);
}

renderCollections();
