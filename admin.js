/* ── Helpers ── */
const $ = id => document.getElementById(id);
const esc = s => String(s ?? '').replace(/[&<>"']/g, c =>
  ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

function stars(n) {
  return '★'.repeat(Math.max(0, Math.min(5, n))) + '☆'.repeat(5 - Math.max(0, Math.min(5, n)));
}

function toast(msg, type = 'success') {
  const el = $('admin-toast');
  el.textContent = msg;
  el.className = `admin-toast toast-${type} show`;
  setTimeout(() => el.classList.remove('show'), 3000);
}

async function api(url, method = 'GET', body = null) {
  const opts = { method, headers: { 'Content-Type': 'application/json' } };
  if (body) opts.body = JSON.stringify(body);
  const r = await fetch(url, opts);
  return r.json();
}

function openModal(id) {
  $(id).classList.add('open');
  $(id).setAttribute('aria-hidden', 'false');
}

function closeModal(id) {
  $(id).classList.remove('open');
  $(id).setAttribute('aria-hidden', 'true');
}

/* ── Confirm dialog ── */
function confirm(desc, onOk) {
  $('confirm-modal-desc').textContent = desc;
  openModal('confirm-modal');
  const ok = $('confirm-ok');
  const cancel = $('confirm-cancel');
  const cleanup = () => { ok.onclick = null; cancel.onclick = null; closeModal('confirm-modal'); };
  ok.onclick = () => { cleanup(); onOk(); };
  cancel.onclick = cleanup;
  document.querySelector('#confirm-modal .admin-modal-backdrop').onclick = cleanup;
}

/* ── Tab switching ── */
const titles = { dashboard: 'Dashboard', reviews: 'Reviews', spots: 'Spots', users: 'Users', community: 'Community', visits: 'Visits' };

document.querySelectorAll('.admin-nav-item').forEach(btn => {
  btn.addEventListener('click', () => {
    const tab = btn.dataset.tab;
    document.querySelectorAll('.admin-nav-item').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    $(`tab-${tab}`).classList.add('active');
    $('admin-page-title').textContent = titles[tab];
    loaders[tab]?.();
  });
});

/* ═══════════════════════════════
   DASHBOARD
═══════════════════════════════ */
async function loadDashboard() {
  const data = await api('admin/stats.php');
  $('stat-users').textContent    = data.users;
  $('stat-spots').textContent    = data.spots;
  $('stat-reviews').textContent  = data.reviews;
  $('stat-featured').textContent = data.featured;

  const wrap = $('dashboard-recent-reviews');
  if (!data.recent.length) { wrap.innerHTML = '<p class="empty-text">No reviews yet.</p>'; return; }

  wrap.innerHTML = `
    <table class="admin-table">
      <thead><tr>
        <th>User</th><th>Spot</th><th>Rating</th><th>Comment</th><th>Date</th>
      </tr></thead>
      <tbody>
        ${data.recent.map(r => `
          <tr>
            <td>${esc(r.username)}</td>
            <td>${esc(r.spot_name)}</td>
            <td><span class="stars">${stars(r.rating)}</span></td>
            <td class="td-truncate">${esc(r.comment)}</td>
            <td class="td-muted">${new Date(r.created_at).toLocaleDateString()}</td>
          </tr>`).join('')}
      </tbody>
    </table>`;
}

/* ═══════════════════════════════
   REVIEWS
═══════════════════════════════ */
let allReviews = [];

async function loadReviews() {
  allReviews = await api('admin/reviews.php');
  populateSpotFilter();
  renderReviews();
}

function populateSpotFilter() {
  const sel = $('reviews-spot-filter');
  const spots = [...new Set(allReviews.map(r => r.spot_name))].sort();
  sel.innerHTML = '<option value="">All Spots</option>' +
    spots.map(s => `<option value="${esc(s)}">${esc(s)}</option>`).join('');
}

function renderReviews() {
  const search = $('reviews-search').value.toLowerCase();
  const spot   = $('reviews-spot-filter').value;
  const wrap   = $('reviews-table-wrap');

  const filtered = allReviews.filter(r =>
    (!spot || r.spot_name === spot) &&
    (!search || r.username.toLowerCase().includes(search) || r.comment.toLowerCase().includes(search))
  );

  if (!filtered.length) { wrap.innerHTML = '<p class="empty-text">No reviews found.</p>'; return; }

  wrap.innerHTML = `
    <table class="admin-table">
      <thead><tr>
        <th>User</th><th>Spot</th><th>Rating</th><th>Comment</th><th>Admin Reply</th><th>Date</th><th>Actions</th>
      </tr></thead>
      <tbody>
        ${filtered.map(r => `
          <tr data-id="${r.id}">
            <td>${esc(r.username)}</td>
            <td>${esc(r.spot_name)}</td>
            <td><span class="stars">${stars(r.rating)}</span></td>
            <td class="td-truncate">${esc(r.comment)}</td>
            <td class="td-truncate td-muted">${esc(r.admin_reply) || '<em>—</em>'}</td>
            <td class="td-muted">${new Date(r.created_at).toLocaleDateString()}</td>
            <td>
              <div class="td-actions">
                <button class="btn-action btn-gold" onclick="openReply(${r.id}, ${JSON.stringify(esc(r.admin_reply || ''))})">Reply</button>
                <button class="btn-action btn-danger" onclick="deleteReview(${r.id})">Delete</button>
              </div>
            </td>
          </tr>`).join('')}
      </tbody>
    </table>`;
}

$('reviews-search').addEventListener('input', renderReviews);
$('reviews-spot-filter').addEventListener('change', renderReviews);

function deleteReview(id) {
  confirm('Delete this review? This cannot be undone.', async () => {
    const r = await api('admin/reviews.php', 'DELETE', { id });
    if (r.success) { toast('Review deleted.'); allReviews = allReviews.filter(x => x.id !== id); renderReviews(); }
    else toast(r.error || 'Error deleting review.', 'error');
  });
}

function openReply(id, existing) {
  $('reply-review-id').value = id;
  $('reply-text').value = existing || '';
  openModal('reply-modal');
}

$('reply-form').addEventListener('submit', async e => {
  e.preventDefault();
  const id    = parseInt($('reply-review-id').value);
  const reply = $('reply-text').value.trim();
  const r = await api('admin/reviews.php', 'POST', { id, reply });
  if (r.success) {
    toast('Reply saved.');
    const rev = allReviews.find(x => x.id === id);
    if (rev) rev.admin_reply = reply;
    closeModal('reply-modal');
    renderReviews();
  } else toast(r.error || 'Error saving reply.', 'error');
});

$('reply-modal-cancel').onclick = () => closeModal('reply-modal');
document.querySelector('#reply-modal .admin-modal-backdrop').onclick = () => closeModal('reply-modal');

/* ═══════════════════════════════
   SPOTS
═══════════════════════════════ */
let allSpots = [];

async function loadSpots() {
  allSpots = await api('admin/spots.php');
  renderSpots();
}

function renderSpots() {
  const wrap = $('spots-table-wrap');
  if (!allSpots.length) { wrap.innerHTML = '<p class="empty-text" style="padding:24px">No spots found.</p>'; return; }

  wrap.innerHTML = `<div class="spots-card-grid">${allSpots.map(s => {
    const img = s.image
      ? `<img src="${esc(s.image)}" alt="${esc(s.name)}" class="spot-card-img" onerror="this.src='images/default.png'">`
      : `<div class="spot-card-img spot-card-no-img"><span>No image</span></div>`;
    return `
    <div class="spot-card" data-id="${s.id}">
      <div class="spot-card-thumb">
        ${img}
        ${s.featured ? '<span class="spot-card-badge">⭐ Featured</span>' : ''}
      </div>
      <div class="spot-card-body">
        <div class="spot-card-name">${esc(s.name)}</div>
        <div class="spot-card-meta">
          ${s.category ? `<span class="spot-card-cat">${esc(s.category)}</span>` : ''}
          ${s.address  ? `<span class="spot-card-addr">${esc(s.address)}</span>` : ''}
        </div>
        ${s.description ? `<p class="spot-card-desc">${esc(s.description)}</p>` : ''}
      </div>
      <div class="spot-card-actions">
        <button class="btn-action btn-gold" onclick="editSpot(${s.id})">Edit</button>
        <button class="btn-action btn-danger" onclick="deleteSpot(${s.id})">Delete</button>
      </div>
    </div>`;
  }).join('')}</div>`;
}

/* ── Image upload helpers ── */
let _pendingImageFile = null;
let _removeImage = false;

function resetImageUI() {
  _pendingImageFile = null;
  _removeImage = false;
  $('spot-image-file').value = '';
  $('img-preview').src = '';
  $('img-preview').classList.add('hidden');
  $('img-placeholder').style.display = '';
  $('img-actions').style.display = 'none';
  $('img-current-path').textContent = '';
}

function setImagePreview(src, label) {
  $('img-preview').src = src;
  $('img-preview').classList.remove('hidden');
  $('img-placeholder').style.display = 'none';
  $('img-actions').style.display = 'flex';
  $('img-current-path').textContent = label || '';
}

$('img-upload-zone').addEventListener('click', e => {
  if (e.target.id === 'img-change-btn' || e.target.id === 'img-remove-btn') return;
  $('spot-image-file').click();
});

$('img-upload-zone').addEventListener('dragover', e => {
  e.preventDefault();
  $('img-upload-zone').classList.add('drag-over');
});

$('img-upload-zone').addEventListener('dragleave', () => {
  $('img-upload-zone').classList.remove('drag-over');
});

$('img-upload-zone').addEventListener('drop', e => {
  e.preventDefault();
  $('img-upload-zone').classList.remove('drag-over');
  const file = e.dataTransfer.files[0];
  if (file) handleImageFile(file);
});

$('spot-image-file').addEventListener('change', e => {
  const file = e.target.files[0];
  if (file) handleImageFile(file);
});

function handleImageFile(file) {
  const allowed = ['image/jpeg','image/png','image/webp','image/gif'];
  if (!allowed.includes(file.type)) { toast('Use JPG, PNG, WEBP, or GIF.', 'error'); return; }
  if (file.size > 5 * 1024 * 1024) { toast('Image must be under 5 MB.', 'error'); return; }
  _pendingImageFile = file;
  _removeImage = false;
  const reader = new FileReader();
  reader.onload = ev => setImagePreview(ev.target.result, file.name);
  reader.readAsDataURL(file);
}

$('img-change-btn').onclick = () => $('spot-image-file').click();

$('img-remove-btn').onclick = () => {
  _pendingImageFile = null;
  _removeImage = true;
  resetImageUI();
  $('img-current-path').textContent = 'Image will be removed on save.';
  $('img-actions').style.display = 'flex';
};

/* ── Open modal ── */
$('add-spot-btn').onclick = () => {
  $('spot-modal-title').textContent = 'Add Spot';
  $('spot-id').value = '';
  $('spot-name').value = '';
  $('spot-category').value = '';
  $('spot-address').value = '';
  $('spot-description').value = '';
  $('spot-history').value = '';
  $('spot-featured').checked = false;
  resetImageUI();
  openModal('spot-modal');
};

function editSpot(id) {
  const s = allSpots.find(x => x.id === id);
  if (!s) return;
  $('spot-modal-title').textContent = 'Edit Spot';
  $('spot-id').value          = s.id;
  $('spot-name').value        = s.name;
  $('spot-category').value    = s.category ?? '';
  $('spot-address').value     = s.address ?? '';
  $('spot-description').value = s.description ?? '';
  $('spot-history').value     = s.history ?? '';
  $('spot-featured').checked  = !!s.featured;
  resetImageUI();
  if (s.image) {
    setImagePreview(s.image, s.image);
  }
  openModal('spot-modal');
}

/* ── Submit (multipart for image upload) ── */
$('spot-form').addEventListener('submit', async e => {
  e.preventDefault();
  const btn = $('spot-save-btn');
  btn.disabled = true;
  btn.textContent = 'Saving…';

  try {
    const fd = new FormData();
    fd.append('id',          $('spot-id').value || '0');
    fd.append('name',        $('spot-name').value.trim());
    fd.append('category',    $('spot-category').value.trim());
    fd.append('address',     $('spot-address').value.trim());
    fd.append('description', $('spot-description').value.trim());
    fd.append('history',     $('spot-history').value.trim());
    fd.append('featured',    $('spot-featured').checked ? '1' : '0');
    if (_pendingImageFile) fd.append('image', _pendingImageFile);
    if (_removeImage)      fd.append('remove_image', '1');

    const resp = await fetch('admin/spots.php', { method: 'POST', body: fd });
    const r    = await resp.json();

    if (r.success) {
      toast($('spot-id').value ? 'Spot updated.' : 'Spot added.');
      closeModal('spot-modal');
      loadSpots();
    } else {
      toast(r.error || 'Error saving spot.', 'error');
    }
  } finally {
    btn.disabled = false;
    btn.textContent = 'Save';
  }
});

$('spot-modal-cancel').onclick = () => closeModal('spot-modal');
document.querySelector('#spot-modal .admin-modal-backdrop').onclick = () => closeModal('spot-modal');

function deleteSpot(id) {
  const s = allSpots.find(x => x.id === id);
  confirm(`Delete "${s?.name}"? All reviews for this spot will also be deleted.`, async () => {
    const r = await api('admin/spots.php', 'DELETE', { id });
    if (r.success) { toast('Spot deleted.'); allSpots = allSpots.filter(x => x.id !== id); renderSpots(); }
    else toast(r.error || 'Error deleting spot.', 'error');
  });
}

/* ═══════════════════════════════
   USERS
═══════════════════════════════ */
let allUsers = [];

async function loadUsers() {
  allUsers = await api('admin/users.php');
  renderUsers();
}

function renderUsers() {
  const search = $('users-search').value.toLowerCase();
  const role   = $('users-role-filter').value;
  const wrap   = $('users-table-wrap');

  const filtered = allUsers.filter(u =>
    (!role || u.role === role) &&
    (!search || u.username.toLowerCase().includes(search) || u.email.toLowerCase().includes(search))
  );

  if (!filtered.length) { wrap.innerHTML = '<p class="empty-text">No users found.</p>'; return; }

  wrap.innerHTML = `
    <table class="admin-table">
      <thead><tr>
        <th>Username</th><th>Email</th><th>Role</th><th>Reviews</th><th>Joined</th><th>Actions</th>
      </tr></thead>
      <tbody>
        ${filtered.map(u => `
          <tr data-id="${u.id}">
            <td><strong>${esc(u.username)}</strong></td>
            <td class="td-muted">${esc(u.email)}</td>
            <td><span class="badge badge-${u.role}">${u.role}</span></td>
            <td class="td-muted">${u.review_count}</td>
            <td class="td-muted">${new Date(u.created_at).toLocaleDateString()}</td>
            <td>
              <div class="td-actions">
                ${u.role === 'user'
                  ? `<button class="btn-action btn-gold" onclick="changeRole(${u.id},'promote')">Promote</button>`
                  : `<button class="btn-action" onclick="changeRole(${u.id},'demote')">Demote</button>`}
                <button class="btn-action btn-danger" onclick="deleteUser(${u.id}, '${esc(u.username)}')">Delete</button>
              </div>
            </td>
          </tr>`).join('')}
      </tbody>
    </table>`;
}

$('users-search').addEventListener('input', renderUsers);
$('users-role-filter').addEventListener('change', renderUsers);

async function changeRole(id, action) {
  const r = await api('admin/users.php', 'POST', { id, action });
  if (r.success) {
    toast(`User ${action === 'promote' ? 'promoted to admin' : 'demoted to user'}.`);
    const u = allUsers.find(x => x.id === id);
    if (u) u.role = r.role;
    renderUsers();
  } else toast(r.error || 'Error.', 'error');
}

function deleteUser(id, username) {
  confirm(`Delete user "${username}"? Their reviews will also be deleted.`, async () => {
    const r = await api('admin/users.php', 'DELETE', { id });
    if (r.success) { toast('User deleted.'); allUsers = allUsers.filter(x => x.id !== id); renderUsers(); }
    else toast(r.error || 'Error deleting user.', 'error');
  });
}

/* ═══════════════════════════════
   COMMUNITY (reuses reviews data)
═══════════════════════════════ */
async function loadCommunity() {
  const reviews = await api('admin/reviews.php');
  const wrap = $('community-table-wrap');

  if (!reviews.length) { wrap.innerHTML = '<p class="empty-text">No community reviews yet.</p>'; return; }

  wrap.innerHTML = `
    <table class="admin-table">
      <thead><tr>
        <th>User</th><th>Spot</th><th>Rating</th><th>Comment</th><th>Date</th><th>Actions</th>
      </tr></thead>
      <tbody>
        ${reviews.map(r => `
          <tr>
            <td>${esc(r.username)}</td>
            <td>${esc(r.spot_name)}</td>
            <td><span class="stars">${stars(r.rating)}</span></td>
            <td class="td-truncate">${esc(r.comment)}</td>
            <td class="td-muted">${new Date(r.created_at).toLocaleDateString()}</td>
            <td>
              <button class="btn-action btn-danger" onclick="deleteCommunityReview(${r.id}, this)">Delete</button>
            </td>
          </tr>`).join('')}
      </tbody>
    </table>`;
}

async function deleteCommunityReview(id, btn) {
  confirm('Delete this review?', async () => {
    const r = await api('admin/reviews.php', 'DELETE', { id });
    if (r.success) { toast('Review deleted.'); btn.closest('tr').remove(); }
    else toast(r.error || 'Error.', 'error');
  });
}

/* ═══════════════════════════════
   Init
═══════════════════════════════ */
const loaders = {
  dashboard: loadDashboard,
  reviews:   loadReviews,
  spots:     loadSpots,
  users:     loadUsers,
  community: loadCommunity,
  visits:    loadVisits,
};

loadDashboard();

/* ═══════════════════════════════
   VISITS
═══════════════════════════════ */
async function loadVisits() {
  const data = await api('admin/visits.php');

  $('stat-total-visits').textContent = Number(data.total).toLocaleString();

  // Per page table
  const pageWrap = $('visits-by-page');
  if (!data.perPage.length) {
    pageWrap.innerHTML = '<p class="empty-text">No visits recorded yet.</p>';
  } else {
    const max = Math.max(...data.perPage.map(r => r.visits));
    pageWrap.innerHTML = `
      <table class="admin-table">
        <thead><tr><th>Page</th><th>Visits</th><th style="width:40%">Bar</th></tr></thead>
        <tbody>
          ${data.perPage.map(r => `
            <tr>
              <td>${esc(r.page)}</td>
              <td>${Number(r.visits).toLocaleString()}</td>
              <td>
                <div class="visit-bar-wrap">
                  <div class="visit-bar" style="width:${Math.round((r.visits/max)*100)}%"></div>
                </div>
              </td>
            </tr>`).join('')}
        </tbody>
      </table>`;
  }

  // Per day table
  const dayWrap = $('visits-by-day');
  if (!data.perDay.length) {
    dayWrap.innerHTML = '<p class="empty-text">No visits in the last 14 days.</p>';
  } else {
    const maxDay = Math.max(...data.perDay.map(r => r.visits));
    dayWrap.innerHTML = `
      <table class="admin-table">
        <thead><tr><th>Date</th><th>Visits</th><th style="width:40%">Bar</th></tr></thead>
        <tbody>
          ${data.perDay.map(r => `
            <tr>
              <td>${r.day}</td>
              <td>${Number(r.visits).toLocaleString()}</td>
              <td>
                <div class="visit-bar-wrap">
                  <div class="visit-bar" style="width:${Math.round((r.visits/maxDay)*100)}%"></div>
                </div>
              </td>
            </tr>`).join('')}
        </tbody>
      </table>`;
  }

  // Hand data directly to chart renderer (defined in admin.php inline script)
  if (typeof window.renderVisitsCharts === 'function') {
    window.renderVisitsCharts(data.perDay || [], data.perPage || []);
  }
}