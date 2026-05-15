<?php require 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IntraSpots | Community</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/base.css">
  <style>
    :root {
      --gold: #C9A84C;
      --gold-light: #E8C97A;
      --dark: #0E0C0A;
      --dark-2: #1A1714;
      --dark-3: #252018;
      --cream: #F5F0E8;
      --cream-muted: #B8B0A0;
      --accent: #8B4513;
      --accent-light: #D4651A;
      --border: rgba(201,168,76,0.2);
      --font-display: 'Playfair Display', serif;
      --font-body: 'DM Sans', sans-serif;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background: var(--dark);
      color: var(--cream);
      font-family: var(--font-body);
      font-weight: 300;
      overflow-x: hidden;
    }

    /* ── HERO BANNER ── */
    .community-hero {
      position: relative;
      height: 70vh;
      min-height: 500px;
      display: flex;
      align-items: flex-end;
      padding: 4rem;
      overflow: hidden;
    }

    .community-hero video {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.45;
    }

    .hero-fallback {
      position: absolute;
      inset: 0;
      background: 
        linear-gradient(135deg, #2C1810 0%, #0E0C0A 50%, #1A1209 100%);
    }

    .hero-grain {
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
      pointer-events: none;
      z-index: 2;
    }

    .hero-gradient {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, var(--dark) 0%, transparent 60%);
      z-index: 3;
    }

    .hero-content {
      position: relative;
      z-index: 4;
      max-width: 700px;
    }

    .hero-eyebrow {
      font-size: 11px;
      letter-spacing: 4px;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .hero-eyebrow::before {
      content: '';
      width: 40px;
      height: 1px;
      background: var(--gold);
    }

    .hero-title {
      font-family: var(--font-display);
      font-size: clamp(2.5rem, 6vw, 5rem);
      font-weight: 900;
      line-height: 1.05;
      color: var(--cream);
      margin-bottom: 1.25rem;
    }

    .hero-title span {
      color: var(--gold);
      font-style: italic;
    }

    .hero-subtitle {
      font-size: 1rem;
      color: var(--cream-muted);
      line-height: 1.7;
      max-width: 480px;
    }

    /* ── STATS BAR ── */
    .stats-bar {
      background: var(--dark-2);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 1.5rem 4rem;
      display: flex;
      gap: 3rem;
      align-items: center;
    }

    .stat-item {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .stat-num {
      font-family: var(--font-display);
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--gold);
    }

    .stat-label {
      font-size: 11px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--cream-muted);
    }

    .stat-divider {
      width: 1px;
      height: 40px;
      background: var(--border);
    }

    /* ── MAIN LAYOUT ── */
    .community-main {
      max-width: 1400px;
      margin: 0 auto;
      padding: 2rem 1.5rem;
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 3rem;
    }

    /* ── SECTION HEADERS ── */
    .section-header {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      margin-bottom: 2rem;
    }

    .section-title {
      font-family: var(--font-display);
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--cream);
    }

    .section-title span {
      color: var(--gold);
      font-style: italic;
    }

    .section-link {
      font-size: 12px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--gold);
      text-decoration: none;
      border-bottom: 1px solid var(--border);
      padding-bottom: 2px;
      transition: border-color 0.2s;
    }

    .section-link:hover { border-color: var(--gold); }

    /* ── SPOT CAROUSEL ── */
    .spot-carousel {
      position: relative;
      margin-bottom: 4rem;
    }

    .carousel-track {
      display: flex;
      gap: 1.5rem;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
      padding: 0 1 rem;
      padding-bottom: 1rem;
    }

    .carousel-track::-webkit-scrollbar { display: none; }

    .spot-card {
      flex: 0 0 220px;
      scroll-snap-align: start;
      background: var(--dark-2);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      transition: transform 0.3s ease, border-color 0.3s ease;
      cursor: pointer;
    }

    .spot-card:hover {
      transform: translateY(-6px);
      border-color: var(--gold);
    }

    .spot-card-img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block;
    }

    .spot-card-img-placeholder {
      width: 100%;
      height: 180px;
      background: linear-gradient(135deg, var(--dark-3), var(--accent));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: var(--gold);
    }

    .spot-card-body {
      padding: 1.25rem;
    }

    .spot-card-category {
      font-size: 10px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 0.5rem;
    }

    .spot-card-name {
      font-family: var(--font-display);
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--cream);
      margin-bottom: 0.5rem;
    }

    .spot-card-desc {
      font-size: 13px;
      color: var(--cream-muted);
      line-height: 1.6;
      margin-bottom: 1rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .spot-card-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .vote-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      background: transparent;
      border: 1px solid var(--border);
      border-radius: 50px;
      padding: 6px 14px;
      color: var(--cream-muted);
      font-family: var(--font-body);
      font-size: 13px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .vote-btn:hover, .vote-btn.active {
      background: rgba(201,168,76,0.1);
      border-color: var(--gold);
      color: var(--gold);
    }

    .vote-btn svg {
      width: 14px;
      height: 14px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }

    .vote-btn.downvote:hover, .vote-btn.downvote.active {
      background: rgba(180,60,60,0.1);
      border-color: #E05555;
      color: #E05555;
    }

    .comment-count {
      font-size: 12px;
      color: var(--cream-muted);
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* carousel nav arrows */
    .carousel-nav {
      display: flex;
      gap: 8px;
      margin-top: 1rem;
      justify-content: flex-end;
    }

    .carousel-nav button {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--cream);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
      font-size: 16px;
    }

    .carousel-nav button:hover {
      background: var(--gold);
      border-color: var(--gold);
      color: var(--dark);
    }

    /* ── VIDEO SECTION ── */
    .video-section {
      margin-bottom: 4rem;
    }

    .video-wrapper {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      border: 1px solid var(--border);
      background: var(--dark-3);
      aspect-ratio: 16/9;
    }

    .video-wrapper video,
    .video-wrapper iframe {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .video-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      background: linear-gradient(135deg, var(--dark-3), #1a1209);
      min-height: 300px;
    }

    .video-placeholder .play-btn {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: var(--gold);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform 0.2s, background 0.2s;
    }

    .video-placeholder .play-btn:hover {
      transform: scale(1.1);
      background: var(--gold-light);
    }

    .video-placeholder .play-btn svg {
      width: 28px;
      height: 28px;
      fill: var(--dark);
      margin-left: 4px;
    }

    .video-placeholder p {
      color: var(--cream-muted);
      font-size: 14px;
      letter-spacing: 1px;
    }

    /* ── COMMUNITY FEED ── */
    .feed-filters {
      display: flex;
      gap: 8px;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .filter-pill {
      padding: 6px 16px;
      border-radius: 50px;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--cream-muted);
      font-family: var(--font-body);
      font-size: 12px;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .filter-pill.active, .filter-pill:hover {
      background: var(--gold);
      border-color: var(--gold);
      color: var(--dark);
    }

    .feed-post {
      background: var(--dark-2);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem;
      margin-bottom: 1.25rem;
      transition: border-color 0.2s;
    }

    .feed-post:hover { border-color: rgba(201,168,76,0.4); }

    .post-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 1rem;
    }

    .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent), var(--gold));
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 14px;
      color: var(--cream);
      flex-shrink: 0;
    }

    .post-meta { flex: 1; }

    .post-author {
      font-size: 14px;
      font-weight: 500;
      color: var(--cream);
    }

    .post-spot {
      font-size: 12px;
      color: var(--gold);
    }

    .post-time {
      font-size: 11px;
      color: var(--cream-muted);
    }

    .post-rating {
      display: flex;
      gap: 2px;
    }

    .star {
      color: var(--gold);
      font-size: 13px;
    }

    .star.empty { color: var(--dark-3); filter: brightness(3); }

    .post-comment {
      font-size: 14px;
      color: var(--cream-muted);
      line-height: 1.7;
      margin-bottom: 1rem;
      font-style: italic;
    }

    .post-comment::before {
      content: '"';
      font-family: var(--font-display);
      font-size: 2rem;
      color: var(--gold);
      opacity: 0.4;
      line-height: 0;
      vertical-align: -0.5rem;
      margin-right: 4px;
    }

    .post-actions {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    /* ── COMMENT FORM ── */
    .comment-form {
      background: var(--dark-2);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem;
      margin-bottom: 1.25rem;
    }

    .comment-form h3 {
      font-family: var(--font-display);
      font-size: 1.1rem;
      color: var(--cream);
      margin-bottom: 1rem;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      font-size: 11px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--cream-muted);
      margin-bottom: 6px;
    }

    .form-group select,
    .form-group textarea,
    .form-group input {
      width: 100%;
      background: var(--dark-3);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 10px 14px;
      color: var(--cream);
      font-family: var(--font-body);
      font-size: 14px;
      outline: none;
      transition: border-color 0.2s;
    }

    .form-group select:focus,
    .form-group textarea:focus,
    .form-group input:focus {
      border-color: var(--gold);
    }

    .form-group select option { background: var(--dark-2); }

    .form-group textarea {
      resize: vertical;
      min-height: 100px;
      line-height: 1.6;
    }

    .rating-input {
      display: flex;
      gap: 4px;
      font-size: 1.5rem;
      cursor: pointer;
    }

    .rating-input span {
      color: var(--dark-3);
      filter: brightness(4);
      transition: color 0.1s;
    }

    .rating-input span.active,
    .rating-input span:hover {
      color: var(--gold);
    }

    .btn-submit {
      width: 100%;
      padding: 12px;
      background: var(--gold);
      border: none;
      border-radius: 8px;
      color: var(--dark);
      font-family: var(--font-body);
      font-size: 14px;
      font-weight: 500;
      letter-spacing: 1px;
      cursor: pointer;
      transition: background 0.2s, transform 0.1s;
    }

    .btn-submit:hover { background: var(--gold-light); }
    .btn-submit:active { transform: scale(0.98); }

    .login-notice {
      text-align: center;
      padding: 1.5rem;
      background: var(--dark-3);
      border-radius: 8px;
      border: 1px dashed var(--border);
      font-size: 13px;
      color: var(--cream-muted);
      margin-top: 0.5rem;
    }

    .login-notice a {
      color: var(--gold);
      text-decoration: none;
    }

    /* ── SIDEBAR ── */
    .sidebar { position: relative; }

    .sidebar-card {
      background: var(--dark-2);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .sidebar-card h3 {
      font-family: var(--font-display);
      font-size: 1rem;
      color: var(--cream);
      margin-bottom: 1.25rem;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid var(--border);
    }

    .leaderboard-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid rgba(201,168,76,0.08);
    }

    .leaderboard-item:last-child { border-bottom: none; }

    .rank {
      font-family: var(--font-display);
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--gold);
      width: 24px;
      flex-shrink: 0;
    }

    .rank.top { color: var(--gold-light); }

    .lb-info { flex: 1; }

    .lb-name {
      font-size: 13px;
      font-weight: 500;
      color: var(--cream);
    }

    .lb-category {
      font-size: 11px;
      color: var(--cream-muted);
    }

    .lb-votes {
      font-size: 12px;
      color: var(--gold);
      font-weight: 500;
    }

    .tag-cloud {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .tag {
      padding: 5px 12px;
      border-radius: 50px;
      border: 1px solid var(--border);
      font-size: 12px;
      color: var(--cream-muted);
      cursor: pointer;
      transition: all 0.2s;
    }

    .tag:hover {
      border-color: var(--gold);
      color: var(--gold);
    }

    /* ── TOAST ── */
    .toast {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      background: var(--dark-2);
      border: 1px solid var(--gold);
      border-radius: 12px;
      padding: 1rem 1.5rem;
      color: var(--cream);
      font-size: 14px;
      z-index: 9999;
      transform: translateY(100px);
      opacity: 0;
      transition: all 0.3s ease;
    }

    .toast.show {
      transform: translateY(0);
      opacity: 1;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
      .community-main { grid-template-columns: 1fr; }
      .sidebar { display: none; }
      .stats-bar { padding: 1.5rem 2rem; gap: 1.5rem; flex-wrap: wrap; }
    }

    @media (max-width: 640px) {
      .community-hero { padding: 2rem; }
      .hero-title { font-size: 2.5rem; }
    }
  </style>
</head>
<body>

<div id="header-placeholder"></div>

<!-- ── HERO WITH VIDEO ── -->
<section class="community-hero">
  <div class="hero-fallback"></div>

  <!-- Replace src with your actual video file -->
  <video autoplay muted loop playsinline poster="images/intramuros-hero.jpg">
    <source src="images/intramuros-video.mp4" type="video/mp4">
  </video>

  <div class="hero-grain"></div>
  <div class="hero-gradient"></div>

  <div class="hero-content">
    <p class="hero-eyebrow">IntraSpots Community</p>
    <h1 class="hero-title">Share Your<br><span>Intramuros</span><br>Story</h1>
    <p class="hero-subtitle">Join fellow explorers — vote for your favorite spots, leave reviews, and discover hidden gems through the eyes of the community.</p>
  </div>
</section>

<!-- ── STATS BAR ── -->
<?php
  $totalSpots = $pdo->query("SELECT COUNT(*) FROM spots")->fetchColumn();
  $totalReviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
  $totalVotes = $pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();
  $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>
<div class="stats-bar">
  <div class="stat-item">
    <span class="stat-num"><?= $totalSpots ?></span>
    <span class="stat-label">Spots</span>
  </div>
  <div class="stat-divider"></div>
  <div class="stat-item">
    <span class="stat-num"><?= $totalReviews ?></span>
    <span class="stat-label">Reviews</span>
  </div>
  <div class="stat-divider"></div>
  <div class="stat-item">
    <span class="stat-num"><?= $totalVotes ?></span>
    <span class="stat-label">Upvotes</span>
  </div>
  <div class="stat-divider"></div>
  <div class="stat-item">
    <span class="stat-num"><?= $totalUsers ?></span>
    <span class="stat-label">Explorers</span>
  </div>
</div>

<!-- ── MAIN CONTENT ── -->
<div class="community-main">
  <div class="left-col">

    <!-- SPOT CAROUSEL -->
    <div class="spot-carousel">
      <div class="section-header">
        <h2 class="section-title">Explore <span>Spots</span></h2>
        <a href="sidebar.html" class="section-link">View All</a>
      </div>

      <?php
        $spots = $pdo->query("SELECT s.*, COUNT(v.id) as vote_count, COUNT(r.id) as review_count
          FROM spots s
          LEFT JOIN votes v ON s.id = v.spot_id
          LEFT JOIN reviews r ON s.id = r.spot_id
          GROUP BY s.id
          ORDER BY vote_count DESC")->fetchAll();
      ?>

      <div class="carousel-track" id="spotCarousel">
        <?php foreach($spots as $spot): ?>
          <div class="spot-card" onclick="selectSpot(<?= $spot['id'] ?>, '<?= htmlspecialchars(addslashes($spot['name'])) ?>')">
            <?php if($spot['image']): ?>
              <img class="spot-card-img" src="<?= htmlspecialchars($spot['image']) ?>" alt="<?= htmlspecialchars($spot['name']) ?>" onerror="this.parentElement.innerHTML='<div class=\'spot-card-img-placeholder\'>🏛️</div>'">
            <?php else: ?>
              <div class="spot-card-img-placeholder">🏛️</div>
            <?php endif; ?>
            <div class="spot-card-body">
              <p class="spot-card-category"><?= htmlspecialchars($spot['category']) ?></p>
              <h3 class="spot-card-name"><?= htmlspecialchars($spot['name']) ?></h3>
              <p class="spot-card-desc"><?= htmlspecialchars($spot['description']) ?></p>
              <div class="spot-card-footer">
                <div style="display:flex;gap:6px;">
                  <button class="vote-btn upvote" onclick="event.stopPropagation(); handleVote(<?= $spot['id'] ?>, 'up', this)">
                    <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"></polyline></svg>
                    <span><?= $spot['vote_count'] ?></span>
                  </button>
                </div>
                <span class="comment-count">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                  <?= $spot['review_count'] ?>
                </span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="carousel-nav">
        <button onclick="scrollCarousel(-1)">&#8592;</button>
        <button onclick="scrollCarousel(1)">&#8594;</button>
      </div>
    </div>

    <!-- VIDEO SECTION -->
    <div class="video-section">
      <div class="section-header">
        <h2 class="section-title">Discover <span>Intramuros</span></h2>
      </div>
      <div class="video-wrapper">
        <!-- Replace with your actual video -->
        <video controls poster="images/intramuros-hero.jpg">
          <source src="images/intramuros-video.mp4" type="video/mp4">
          <!-- Fallback if no video file -->
          <div class="video-placeholder">
            <div class="play-btn">
              <svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            </div>
            <p>Intramuros — A Walk Through History</p>
          </div>
        </video>
      </div>
    </div>

    <!-- COMMUNITY FEED -->
    <div class="feed-section">
      <div class="section-header">
        <h2 class="section-title">Community <span>Reviews</span></h2>
      </div>

      <div class="feed-filters">
        <button class="filter-pill active" onclick="filterFeed('all', this)">All</button>
        <button class="filter-pill" onclick="filterFeed('5', this)">★ 5 Stars</button>
        <button class="filter-pill" onclick="filterFeed('4', this)">★ 4 Stars</button>
        <button class="filter-pill" onclick="filterFeed('recent', this)">Most Recent</button>
      </div>

      <!-- COMMENT FORM -->
      <div class="comment-form">
        <h3>Leave a Review</h3>
        <?php if(isset($_SESSION['user_id'])): ?>
          <form method="POST" action="submit_review.php">
            <div class="form-group">
              <label>Select Spot</label>
              <select name="spot_id" id="spotSelect" required>
                <option value="">Choose a spot...</option>
                <?php foreach($spots as $s): ?>
                  <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Rating</label>
              <div class="rating-input" id="ratingInput">
                <span onclick="setRating(1)">★</span>
                <span onclick="setRating(2)">★</span>
                <span onclick="setRating(3)">★</span>
                <span onclick="setRating(4)">★</span>
                <span onclick="setRating(5)">★</span>
              </div>
              <input type="hidden" name="rating" id="ratingValue" value="0">
            </div>
            <div class="form-group">
              <label>Your Review</label>
              <textarea name="comment" placeholder="Share your experience..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">Post Review</button>
          </form>
        <?php else: ?>
          <div class="login-notice">
            <a href="login.php">Log in</a> or <a href="register.php">register</a> to leave a review and vote for your favorite spots.
          </div>
        <?php endif; ?>
      </div>

      <!-- REVIEWS FEED -->
      <div id="reviewsFeed">
        <?php
          $reviews = $pdo->query("
            SELECT r.*, u.username, s.name as spot_name
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            JOIN spots s ON r.spot_id = s.id
            ORDER BY r.created_at DESC
            LIMIT 20
          ")->fetchAll();
        ?>

        <?php if(empty($reviews)): ?>
          <div class="feed-post" style="text-align:center; color: var(--cream-muted); padding: 3rem;">
            <p style="font-family: var(--font-display); font-size:1.5rem; margin-bottom:0.5rem;">No reviews yet</p>
            <p style="font-size:13px;">Be the first to share your Intramuros experience!</p>
          </div>
        <?php else: ?>
          <?php foreach($reviews as $review): ?>
            <div class="feed-post" data-rating="<?= $review['rating'] ?>">
              <div class="post-header">
                <div class="avatar"><?= strtoupper(substr($review['username'], 0, 2)) ?></div>
                <div class="post-meta">
                  <p class="post-author"><?= htmlspecialchars($review['username']) ?></p>
                  <p class="post-spot">@ <?= htmlspecialchars($review['spot_name']) ?></p>
                </div>
                <div>
                  <div class="post-rating">
                    <?php for($i=1; $i<=5; $i++): ?>
                      <span class="star <?= $i <= $review['rating'] ? '' : 'empty' ?>">★</span>
                    <?php endfor; ?>
                  </div>
                  <p class="post-time"><?= date('M d, Y', strtotime($review['created_at'])) ?></p>
                </div>
              </div>
              <p class="post-comment"><?= htmlspecialchars($review['comment']) ?></p>
              <div class="post-actions">
                <button class="vote-btn upvote" onclick="handleVote(<?= $review['spot_id'] ?>, 'up', this)">
                  <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"></polyline></svg>
                  Helpful
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <!-- ── SIDEBAR ── -->
  <aside class="sidebar">

    <!-- LEADERBOARD -->
    <div class="sidebar-card">
      <h3>🏆 Most Upvoted Spots</h3>
      <?php
        $top = $pdo->query("
          SELECT s.name, s.category, COUNT(v.id) as votes
          FROM spots s
          LEFT JOIN votes v ON s.id = v.spot_id
          GROUP BY s.id
          ORDER BY votes DESC
          LIMIT 5
        ")->fetchAll();
      ?>
      <?php foreach($top as $i => $item): ?>
        <div class="leaderboard-item">
          <span class="rank <?= $i === 0 ? 'top' : '' ?>">#<?= $i+1 ?></span>
          <div class="lb-info">
            <p class="lb-name"><?= htmlspecialchars($item['name']) ?></p>
            <p class="lb-category"><?= htmlspecialchars($item['category']) ?></p>
          </div>
          <span class="lb-votes">▲ <?= $item['votes'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- CATEGORIES -->
    <div class="sidebar-card">
      <h3>Browse by Category</h3>
      <div class="tag-cloud">
        <?php
          $cats = $pdo->query("SELECT DISTINCT category FROM spots WHERE category IS NOT NULL")->fetchAll();
          foreach($cats as $cat):
        ?>
          <span class="tag" onclick="filterByCategory('<?= htmlspecialchars($cat['category']) ?>')"><?= htmlspecialchars($cat['category']) ?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- RECENT ACTIVITY -->
    <div class="sidebar-card">
      <h3>Recent Activity</h3>
      <?php
        $recent = $pdo->query("
          SELECT r.comment, r.rating, u.username, s.name as spot_name, r.created_at
          FROM reviews r
          JOIN users u ON r.user_id = u.id
          JOIN spots s ON r.spot_id = s.id
          ORDER BY r.created_at DESC
          LIMIT 4
        ")->fetchAll();
      ?>
      <?php if(empty($recent)): ?>
        <p style="font-size:13px; color:var(--cream-muted);">No activity yet.</p>
      <?php else: ?>
        <?php foreach($recent as $act): ?>
          <div style="padding: 10px 0; border-bottom: 1px solid var(--border);">
            <p style="font-size:12px; color:var(--cream); font-weight:500;"><?= htmlspecialchars($act['username']) ?> reviewed <span style="color:var(--gold)"><?= htmlspecialchars($act['spot_name']) ?></span></p>
            <p style="font-size:11px; color:var(--cream-muted); margin-top:2px;"><?= date('M d', strtotime($act['created_at'])) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </aside>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script src="barScript.js"></script>
<script src="script.js"></script>

<script>
  // Carousel scroll
  function scrollCarousel(dir) {
    const track = document.getElementById('spotCarousel');
    track.scrollBy({ left: dir * 320, behavior: 'smooth' });
  }

  // Select spot for review form
  function selectSpot(id, name) {
    const sel = document.getElementById('spotSelect');
    if (sel) {
      sel.value = id;
      showToast('Selected: ' + name);
    }
  }

  // Star rating
  let currentRating = 0;
  function setRating(val) {
    currentRating = val;
    document.getElementById('ratingValue').value = val;
    const stars = document.querySelectorAll('#ratingInput span');
    stars.forEach((s, i) => s.classList.toggle('active', i < val));
  }

  // Vote handler (wires to your PHP later)
  function handleVote(spotId, type, btn) {
    <?php if(isset($_SESSION['user_id'])): ?>
      fetch('api/vote.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ spot_id: spotId, type: type })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          btn.classList.toggle('active');
          const countEl = btn.querySelector('span');
          if (countEl) countEl.textContent = data.new_count;
          showToast(data.message);
        } else {
          showToast(data.message || 'Already voted!');
        }
      });
    <?php else: ?>
      showToast('Please log in to vote!');
      setTimeout(() => window.location.href = 'login.php', 1500);
    <?php endif; ?>
  }

  // Filter feed
  function filterFeed(val, btn) {
    document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.feed-post').forEach(post => {
      if (val === 'all' || val === 'recent') {
        post.style.display = 'block';
      } else {
        post.style.display = post.dataset.rating === val ? 'block' : 'none';
      }
    });
  }

  // Filter by category
  function filterByCategory(cat) {
    showToast('Filtering by: ' + cat);
  }

  // Toast notification
  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2500);
  }
</script>

</body>
</html>