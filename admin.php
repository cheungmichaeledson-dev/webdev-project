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
      <div class="stat-grid" id="visits-stat-grid">
        <div class="stat-card">
          <div class="stat-value" id="stat-total-visits">—</div>
          <div class="stat-label">Total Visits</div>
        </div>
      </div>

      <div class="admin-section">
        <h2 class="admin-section-title">Visits by Page</h2>
        <div id="visits-by-page" class="admin-table-wrap">
          <p class="loading-text">Loading...</p>
        </div>
      </div>

      <div class="admin-section">
        <h2 class="admin-section-title">Visits per Day <span class="admin-section-sub">(last 14 days)</span></h2>
        <div id="visits-by-day" class="admin-table-wrap">
          <p class="loading-text">Loading...</p>
        </div>
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
</body>
</html>
