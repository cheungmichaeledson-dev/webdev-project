<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home.html');
    exit;
}
$username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IntraSpots | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
  <style>
    /* Visits — two-column chart row */
    .visits-charts-row {
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 20px;
      margin-top: 20px;
    }
    .visits-chart-card {
      border-radius: 12px;
      padding: 20px 22px;
    }
    @media (max-width: 900px) {
      .visits-charts-row { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <aside class="admin-sidebar">
    <div class="admin-brand">
      <span class="admin-brand-logo">IS</span>
      <span class="admin-brand-name">IntraSpots</span>
    </div>
    <nav class="admin-nav">
      <button class="admin-nav-item active" data-tab="dashboard">
        <span class="nav-icon">&#9732;</span> Dashboard
      </button>
      <button class="admin-nav-item" data-tab="reviews">
        <span class="nav-icon">&#9998;</span> Reviews
      </button>
      <button class="admin-nav-item" data-tab="spots">
        <span class="nav-icon">&#9873;</span> Spots
      </button>
      <button class="admin-nav-item" data-tab="users">
        <span class="nav-icon">&#9786;</span> Users
      </button>
      <button class="admin-nav-item" data-tab="community">
        <span class="nav-icon">&#9993;</span> Community
      </button>
      <button class="admin-nav-item" data-tab="visits">
        <span class="nav-icon">&#128202;</span> Visits
      </button>
    </nav>
    <div class="admin-sidebar-footer">
      <span class="admin-logged-as">Logged in as <strong><?= $username ?></strong></span>
      <a href="logout.php" class="admin-logout-btn">Logout</a>
    </div>
  </aside>

  <main class="admin-main">

    <header class="admin-topbar">
      <h1 class="admin-page-title" id="admin-page-title">Dashboard</h1>
      <a href="home.html" class="admin-back-link">&#8592; Back to Site</a>
    </header>

    <!-- DASHBOARD TAB -->
    <section class="admin-tab active" id="tab-dashboard">
      <div class="stat-grid">
        <div class="stat-card">
          <div class="stat-value" id="stat-users">—</div>
          <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" id="stat-spots">—</div>
          <div class="stat-label">Total Spots</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" id="stat-reviews">—</div>
          <div class="stat-label">Total Reviews</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" id="stat-featured">—</div>
          <div class="stat-label">Featured Spots</div>
        </div>
      </div>

      <div class="admin-section">
        <h2 class="admin-section-title">Recent Reviews</h2>
        <div id="dashboard-recent-reviews" class="admin-table-wrap">
          <p class="loading-text">Loading...</p>
        </div>
      </div>
    </section>

    <!-- REVIEWS TAB -->
    <section class="admin-tab" id="tab-reviews">
      <div class="admin-section">
        <h2 class="admin-section-title">All Reviews</h2>
        <div class="admin-filters">
          <input type="text" id="reviews-search" class="admin-search" placeholder="Search by user or comment...">
          <select id="reviews-spot-filter" class="admin-select">
            <option value="">All Spots</option>
          </select>
        </div>
        <div id="reviews-table-wrap" class="admin-table-wrap">
          <p class="loading-text">Loading...</p>
        </div>
      </div>
    </section>

    <!-- SPOTS TAB -->
    <section class="admin-tab" id="tab-spots">
      <div class="admin-section">
        <div class="admin-section-header">
          <h2 class="admin-section-title">All Spots</h2>
          <button class="btn-admin-primary" id="add-spot-btn">+ Add Spot</button>
        </div>
        <div id="spots-table-wrap" class="admin-table-wrap">
          <p class="loading-text">Loading...</p>
        </div>
      </div>
    </section>

    <!-- USERS TAB -->
    <section class="admin-tab" id="tab-users">
      <div class="admin-section">
        <h2 class="admin-section-title">All Users</h2>
        <div class="admin-filters">
          <input type="text" id="users-search" class="admin-search" placeholder="Search by username or email...">
          <select id="users-role-filter" class="admin-select">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
        </div>
        <div id="users-table-wrap" class="admin-table-wrap">
          <p class="loading-text">Loading...</p>
        </div>
      </div>
    </section>

    <!-- COMMUNITY TAB -->
    <section class="admin-tab" id="tab-community">
      <div class="admin-section">
        <h2 class="admin-section-title">Community Reviews</h2>
        <p class="admin-section-desc">Reviews submitted through the community page.</p>
        <div id="community-table-wrap" class="admin-table-wrap">
          <p class="loading-text">Loading...</p>
        </div>
      </div>
    </section>

    <!-- VISITS TAB -->
    <section class="admin-tab" id="tab-visits">

      <!-- Stat row -->
      <div class="stat-grid" id="visits-stat-grid">
        <div class="stat-card">
          <div class="stat-value" id="stat-total-visits">—</div>
          <div class="stat-label">Total Visits</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" id="stat-visits-today">—</div>
          <div class="stat-label">Today</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" id="stat-visits-avg">—</div>
          <div class="stat-label">Daily Average</div>
        </div>
        <div class="stat-card">
          <div class="stat-value" id="stat-visits-peak">—</div>
          <div class="stat-label">Peak Day</div>
        </div>
      </div>

      <!-- Charts row -->
      <div class="visits-charts-row">

        <!-- Daily bar chart -->
        <div class="admin-section visits-chart-card" id="visits-daily-card">
          <h2 class="admin-section-title">
            Visits per Day
            <span class="admin-section-sub">(last 14 days)</span>
          </h2>
          <div id="visits-bar-wrap" style="position:relative;width:100%;height:260px;">
            <canvas id="visits-bar-chart"
              role="img"
              aria-label="Bar chart showing daily visits over the last 14 days">
              Loading visits data…
            </canvas>
          </div>
          <!-- Custom legend -->
          <div style="display:flex;gap:16px;margin-top:10px;font-size:12px;color:var(--muted);">
            <span style="display:flex;align-items:center;gap:5px;">
              <span style="width:10px;height:10px;border-radius:2px;background:#6366f1;display:inline-block;"></span>
              Visits
            </span>
            <span style="display:flex;align-items:center;gap:5px;">
              <span style="width:24px;height:2px;background:#a5b4fc;display:inline-block;border-top:2px dashed #a5b4fc;"></span>
              7-day avg
            </span>
          </div>
        </div>

        <!-- Doughnut: visits by page -->
        <div class="admin-section visits-chart-card" id="visits-page-card">
          <h2 class="admin-section-title">Visits by Page</h2>
          <div id="visits-donut-wrap" style="position:relative;width:100%;height:220px;display:flex;align-items:center;justify-content:center;">
            <canvas id="visits-donut-chart"
              role="img"
              aria-label="Doughnut chart showing proportion of visits by page">
              Loading page data…
            </canvas>
          </div>
          <div id="visits-donut-legend" style="margin-top:12px;font-size:12px;display:flex;flex-direction:column;gap:5px;"></div>
        </div>

      </div>

      <!-- Raw tables (hidden behind charts; still populated by admin.js for fallback) -->
      <div class="admin-section" style="display:none;">
        <div id="visits-by-page" class="admin-table-wrap"><p class="loading-text">Loading...</p></div>
      </div>
      <div class="admin-section" style="display:none;">
        <div id="visits-by-day" class="admin-table-wrap"><p class="loading-text">Loading...</p></div>
      </div>

    </section>

  </main>

  <!-- MODAL: Edit/Add Spot -->
  <div class="admin-modal" id="spot-modal" aria-hidden="true">
    <div class="admin-modal-backdrop"></div>
    <div class="admin-modal-box admin-modal-box--wide">
      <h2 class="admin-modal-title" id="spot-modal-title">Edit Spot</h2>
      <form id="spot-form" class="admin-form">
        <input type="hidden" id="spot-id">

        <!-- Two-column layout: left = fields, right = image -->
        <div class="spot-form-grid">
          <div class="spot-form-fields">
            <div class="form-row">
              <label>Name</label>
              <input type="text" id="spot-name" required>
            </div>
            <div class="form-row-duo">
              <div class="form-row">
                <label>Category</label>
                <input type="text" id="spot-category">
              </div>
              <div class="form-row">
                <label>Featured</label>
                <label class="toggle-label">
                  <input type="checkbox" id="spot-featured" class="toggle-input">
                  <span class="toggle-track"><span class="toggle-thumb"></span></span>
                  <span class="toggle-text">Show on homepage</span>
                </label>
              </div>
            </div>
            <div class="form-row">
              <label>Address</label>
              <input type="text" id="spot-address">
            </div>
            <div class="form-row">
              <label>Description</label>
              <textarea id="spot-description" rows="3"></textarea>
            </div>
            <div class="form-row">
              <label>History</label>
              <textarea id="spot-history" rows="3"></textarea>
            </div>
          </div>

          <!-- Image upload panel -->
          <div class="spot-form-image">
            <label class="form-label-sm">Spot Image</label>
            <div class="img-upload-zone" id="img-upload-zone">
              <img id="img-preview" src="" alt="" class="img-preview hidden">
              <div class="img-upload-placeholder" id="img-placeholder">
                <span class="img-upload-icon">🖼️</span>
                <span class="img-upload-hint">Click or drag to upload</span>
                <span class="img-upload-sub">JPG, PNG, WEBP · max 5 MB</span>
              </div>
              <input type="file" id="spot-image-file" accept="image/jpeg,image/png,image/webp,image/gif" class="img-file-input">
            </div>
            <div class="img-actions" id="img-actions" style="display:none">
              <button type="button" class="btn-img-action" id="img-change-btn">Change</button>
              <button type="button" class="btn-img-action btn-img-remove" id="img-remove-btn">Remove</button>
            </div>
            <p class="img-current-path" id="img-current-path"></p>
          </div>
        </div>

        <div class="admin-modal-actions">
          <button type="button" class="btn-admin-cancel" id="spot-modal-cancel">Cancel</button>
          <button type="submit" class="btn-admin-primary" id="spot-save-btn">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL: Confirm Delete -->
  <div class="admin-modal" id="confirm-modal" aria-hidden="true">
    <div class="admin-modal-backdrop"></div>
    <div class="admin-modal-box admin-modal-box--sm">
      <h2 class="admin-modal-title">Are you sure?</h2>
      <p class="admin-modal-desc" id="confirm-modal-desc">This action cannot be undone.</p>
      <div class="admin-modal-actions">
        <button type="button" class="btn-admin-cancel" id="confirm-cancel">Cancel</button>
        <button type="button" class="btn-admin-danger" id="confirm-ok">Delete</button>
      </div>
    </div>
  </div>

  <!-- MODAL: Admin Reply -->
  <div class="admin-modal" id="reply-modal" aria-hidden="true">
    <div class="admin-modal-backdrop"></div>
    <div class="admin-modal-box">
      <h2 class="admin-modal-title">Admin Reply</h2>
      <form id="reply-form" class="admin-form">
        <input type="hidden" id="reply-review-id">
        <div class="form-row">
          <label>Reply</label>
          <textarea id="reply-text" rows="4" placeholder="Write your reply..."></textarea>
        </div>
        <div class="admin-modal-actions">
          <button type="button" class="btn-admin-cancel" id="reply-modal-cancel">Cancel</button>
          <button type="submit" class="btn-admin-primary">Save Reply</button>
        </div>
      </form>
    </div>
  </div>

  <div class="admin-toast" id="admin-toast"></div>

  <script src="admin.js"></script>

  <!-- Chart.js for Visits visualizations -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
  <script>
  (function () {
    const BAR_COLOR    = '#6366f1';
    const BAR_HOVER    = '#4f46e5';
    const AVG_COLOR    = '#a5b4fc';
    const DONUT_COLORS = ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444','#ec4899','#3b82f6'];

    function isDark() {
      return document.documentElement.classList.contains('dark') ||
             window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    function textColor() { return isDark() ? '#c2c0b6' : '#4b4b47'; }
    function gridColor() { return isDark() ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)'; }

    function rollingAvg(arr, w) {
      return arr.map((_, i) => {
        const s = arr.slice(Math.max(0, i - w + 1), i + 1);
        return Math.round(s.reduce((a, b) => a + b, 0) / s.length);
      });
    }

    function updateExtraStats(perDay) {
      if (!perDay.length) return;
      const vals  = perDay.map(r => Number(r.visits));
      const today = vals[vals.length - 1];
      const avg   = Math.round(vals.reduce((a, b) => a + b, 0) / vals.length);
      const peak  = Math.max(...vals);
      const set   = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v.toLocaleString(); };
      set('stat-visits-today', today);
      set('stat-visits-avg',   avg);
      set('stat-visits-peak',  peak);
    }

    var _barChart = null;
    var _donutChart = null;

    function renderBarChart(perDay) {
      const ctx = document.getElementById('visits-bar-chart');
      if (!ctx || !perDay.length) return;
      if (_barChart) { _barChart.destroy(); }

      const rawVals     = perDay.map(r => Number(r.visits));
      const avg         = rollingAvg(rawVals, 7);
      const shortLabels = perDay.map(r => {
        const d = new Date(r.day);
        return isNaN(d) ? r.day : d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
      });
      const tc = textColor(), gc = gridColor();

      _barChart = new Chart(ctx, {
        data: {
          labels: shortLabels,
          datasets: [
            {
              type: 'bar', label: 'Visits', data: rawVals,
              backgroundColor: BAR_COLOR, hoverBackgroundColor: BAR_HOVER,
              borderRadius: 4, borderSkipped: false, order: 2,
            },
            {
              type: 'line', label: '7-day avg', data: avg,
              borderColor: AVG_COLOR, borderWidth: 1.5, borderDash: [4, 4],
              pointRadius: 0, tension: 0.4, fill: false, order: 1,
            }
          ]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: isDark() ? '#2c2c2a' : '#fff',
              titleColor: tc, bodyColor: tc,
              borderColor: isDark() ? 'rgba(255,255,255,0.12)' : 'rgba(0,0,0,0.1)',
              borderWidth: 1, padding: 10,
              callbacks: { label: c => ' ' + c.dataset.label + ': ' + c.parsed.y.toLocaleString() }
            }
          },
          scales: {
            x: {
              ticks: { color: tc, font: { size: 11 }, autoSkip: false, maxRotation: 45 },
              grid: { display: false },
            },
            y: {
              ticks: { color: tc, font: { size: 11 }, callback: v => v.toLocaleString() },
              grid: { color: gc },
              beginAtZero: true,
            }
          }
        }
      });
    }

    function renderDonutChart(perPage) {
      const ctx = document.getElementById('visits-donut-chart');
      if (!ctx || !perPage.length) return;
      if (_donutChart) { _donutChart.destroy(); }

      const labels = perPage.map(r => r.page);
      const values = perPage.map(r => Number(r.visits));
      const total  = values.reduce((a, b) => a + b, 0);
      const tc     = textColor();

      _donutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels,
          datasets: [{
            data: values,
            backgroundColor: DONUT_COLORS.slice(0, values.length),
            hoverOffset: 6, borderWidth: 2,
            borderColor: isDark() ? '#1c1c1a' : '#fff',
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: false, cutout: '62%',
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: isDark() ? '#2c2c2a' : '#fff',
              titleColor: tc, bodyColor: tc,
              borderColor: isDark() ? 'rgba(255,255,255,0.12)' : 'rgba(0,0,0,0.1)',
              borderWidth: 1,
              callbacks: {
                label: c => {
                  const pct = total ? Math.round(c.parsed / total * 100) : 0;
                  return ' ' + c.parsed.toLocaleString() + ' visits (' + pct + '%)';
                }
              }
            }
          }
        }
      });

      const legend = document.getElementById('visits-donut-legend');
      if (legend) {
        legend.innerHTML = labels.map(function(lbl, i) {
          const pct   = total ? Math.round(values[i] / total * 100) : 0;
          const color = DONUT_COLORS[i % DONUT_COLORS.length];
          return '<span style="display:flex;align-items:center;gap:6px;justify-content:space-between;">'
            + '<span style="display:flex;align-items:center;gap:6px;">'
            + '<span style="width:10px;height:10px;border-radius:2px;background:' + color + ';flex-shrink:0;"></span>'
            + '<span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">' + lbl + '</span>'
            + '</span>'
            + '<span style="font-weight:500;margin-left:8px;">' + pct + '%</span>'
            + '</span>';
        }).join('');
      }
    }

    /* Exposed globally so loadVisits() in admin.js can call it directly */
    window.renderVisitsCharts = function(perDay, perPage) {
      renderBarChart(perDay);
      renderDonutChart(perPage);
      updateExtraStats(perDay);
    };
  })();
  </script>
</body>
</html>