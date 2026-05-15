<?php require 'includes/db.php'; require 'tracker.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IntraSpots | Community</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/header.css">
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
      width: 100%;
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
      width: 100%;
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
      background: linear-gradient(135deg, #2C1810 0%, #0E0C0A 50%, #1A1209 100%);
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

    /* ── STATS BAR (FIXED WIDTH) ── */
    .stats-bar {
      background: var(--dark-2);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 1.5rem 2rem;
      display: flex;
      gap: 3rem;
      align-items: center;
      width: 100%; /* Changed from 100vw to fix page stretching */
      box-sizing: border-box;
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

    /* ── MAIN LAYOUT (FIXED GRID) ── */
    .community-main {
      max-width: 1400px;
      margin: 0 auto;
      padding: 4rem 2rem;
      display: grid;
      /* minmax(0, 1fr) is critical to force the carousel to scroll instead of pushing the sidebar */
      grid-template-columns: minmax(0, 1fr) 320px; 
      gap: 2.5rem;
      width: 100%;
      box-sizing: border-box;
    }

    .left-col {
      min-width: 0; /* Prevents column blowout */
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

    /* ── SPOT CAROUSEL (CLEAN SCROLLING) ── */
    .spot-carousel {
      position: relative;
      margin-bottom: 4rem;
      width: 100%;
    }

    .carousel-track {
      display: flex;
      gap: 1.5rem;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
      padding-bottom: 1.5rem;
      width: 100%;
    }

    .carousel-track::-webkit-scrollbar { display: none; }

    .spot-card {
      flex: 0 0 280px; /* Consistent card width */
      scroll-snap-align: start;
      background: var(--dark-2);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      transition: transform 0.3s ease, border-color 0.3s ease;
      cursor: pointer;
      max-width: 85vw; 
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

    .comment-count {
      font-size: 12px;
      color: var(--cream-muted);
      display: flex;
      align-items: center;
      gap: 4px;
    }

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
      width: 100%;
    }

    .video-wrapper {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      border: 1px solid var(--border);
      background: var(--dark-3);
      aspect-ratio: 16/9;
      width: 100%;
    }

    .video-wrapper video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
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
      transition: border-color 0.2s, opacity 0.3s ease;
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

    .form-group { margin-bottom: 1rem; }

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

    /* ── SIDEBAR ── */
    .sidebar { width: 320px; }

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
    .lb-name { font-size: 13px; font-weight: 500; color: var(--cream); }
    .lb-category { font-size: 11px; color: var(--cream-muted); }
    .lb-votes { font-size: 12px; color: var(--gold); font-weight: 500; }

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

<section class="community-hero">
  <div class="hero-fallback"></div>
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

<?php
  $totalSpots = $pdo->query("SELECT COUNT(*) FROM spots")->fetchColumn();
  $totalReviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
  $totalVotes = $pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();
  $totalVisits = $pdo->query("SELECT COUNT(*) FROM page_visits")->fetchColumn();
?>
<div class="stats-bar">
  <div class="stat-item">
    <span class="stat-num" id="stat-spots"><?= $totalSpots ?></span>
    <span class="stat-label">Spots</span>
  </div>
  <div class="stat-divider"></div>
  <div class="stat-item">
    <span class="stat-num" id="stat-reviews"><?= $totalReviews ?></span>
    <span class="stat-label">Reviews</span>
  </div>
  <div class="stat-divider"></div>
  <div class="stat-item">
    <span class="stat-num" id="stat-votes"><?= $totalVotes ?></span>
    <span class="stat-label">Upvotes</span>
  </div>
  <div class="stat-divider"></div>
  <div class="stat-item">
    <span class="stat-num" id="stat-users"><?= number_format($totalVisits) ?></span>
    <span class="stat-label">Explorers</span>
  </div>
</div>

<div class="community-main">
  <div class="left-col">

    <div class="spot-carousel">
      <div class="section-header">
        <h2 class="section-title">Explore <span>Spots</span></h2>
        <a href="sidebar.html" class="section-link">View All</a>
      </div>

      <?php
        $spots = $pdo->query("SELECT s.*, COUNT(DISTINCT v.id) as vote_count, COUNT(DISTINCT r.id) as review_count
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
                  <button type="button" class="vote-btn upvote" onclick="event.stopPropagation(); handleVote(<?= $spot['id'] ?>, 'up', this)">
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

    <div class="video-section">
      <div class="section-header">
        <h2 class="section-title">Discover <span>Intramuros</span></h2>
      </div>
      <div class="video-wrapper">
        <video controls poster="images/intramuros-hero.jpg">
          <source src="images/intramuros-video.mp4" type="video/mp4">
        </video>
      </div>
    </div>

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

      <div class="comment-form">
        <h3>Leave a Review</h3>
        <form id="reviewForm">
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
      </div>

      <div id="reviewsFeed">
        </div>
    </div>

  </div>

  <aside class="sidebar">

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

    <div class="sidebar-card">
      <h3>Recent Activity</h3>
      </div>

  </aside>
</div>

<div class="toast" id="toast"></div>

<script>
  function scrollCarousel(dir) {
    const track = document.getElementById('spotCarousel');
    track.scrollBy({ left: dir * 300, behavior: 'smooth' });
  }

  function selectSpot(id, name) {
    const sel = document.getElementById('spotSelect');
    if (sel) {
      sel.value = id;
      showToast('Selected: ' + name);
    }
  }

  let currentRating = 0;
  function setRating(val) {
    currentRating = val;
    document.getElementById('ratingValue').value = val;
    const stars = document.querySelectorAll('#ratingInput span');
    stars.forEach((s, i) => s.classList.toggle('active', i < val));
  }

  function handleVote(spotId, type, btn) {
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
        showToast(data.message || 'Error processing vote');
      }
    })
    .catch(err => {
      console.error("Vote Error:", err);
      showToast('Network error.');
    });
  }

  document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault(); 
    const formData = new FormData(this); 

    fetch('submit_review.php', {
      method: 'POST',
      body: formData
    })
    .then(async res => {
      const text = await res.text();
      try {
        return JSON.parse(text);
      } catch(err) {
        console.error("Raw Server Response:", text);
        throw new Error("Server did not send valid JSON.");
      }
    })
    .then(data => {
      if (data.success) {
        showToast(data.message);
        this.reset();
        setRating(0); 
        fetchLiveFeed(); // Refresh feed immediately
      } else {
        showToast(data.message);
      }
    })
    .catch(err => {
      console.error("Submit Error:", err);
      showToast('Error connecting to server.');
    });
  });

  function deleteReview(reviewId, btnElement) {
    if(!confirm('Are you sure you want to delete this review?')) return;

    fetch('delete_review.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ review_id: reviewId })
    })
    .then(async res => {
      const text = await res.text();
      try {
        return JSON.parse(text);
      } catch(err) {
        throw new Error("Invalid server response.");
      }
    })
    .then(data => {
      if(data.success) {
        showToast(data.message);
        const postElement = btnElement.closest('.feed-post');
        postElement.style.opacity = "0";
        setTimeout(() => postElement.remove(), 300);
      } else {
        showToast(data.message);
      }
    })
    .catch(err => {
      console.error("Delete Error:", err);
      showToast('Error connecting to server.');
    });
  }

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

  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2500);
  }

  function renderFeed(reviews) {
    const feedContainer = document.getElementById('reviewsFeed');
    if (reviews.length === 0) {
      feedContainer.innerHTML = `<div class="feed-post" style="text-align:center; color: var(--cream-muted); padding: 3rem;">No reviews yet</div>`;
      return;
    }

    let html = '';
    reviews.forEach(review => {
      const dateObj = new Date(review.created_at);
      const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      const avatarChars = review.username.substring(0, 2).toUpperCase();
      let starsHtml = '';
      for(let i = 1; i <= 5; i++) {
        starsHtml += `<span class="star ${i <= review.rating ? '' : 'empty'}">★</span>`;
      }

      const deleteBtn = (review.user_id == 1) 
        ? `<button type="button" class="vote-btn" style="color: #E05555; border-color: #E05555;" onclick="deleteReview(${review.id}, this)">Delete</button>` 
        : '';

      html += `
        <div class="feed-post" data-rating="${review.rating}">
          <div class="post-header">
            <div class="avatar">${avatarChars}</div>
            <div class="post-meta">
              <p class="post-author">${review.username}</p>
              <p class="post-spot">@ ${review.spot_name}</p>
            </div>
            <div>
              <div class="post-rating">${starsHtml}</div>
              <p class="post-time">${formattedDate}</p>
            </div>
          </div>
          <p class="post-comment">${review.comment}</p>
          <div class="post-actions">
            <button type="button" class="vote-btn upvote" onclick="handleVote(${review.spot_id}, 'up', this)">
              <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"></polyline></svg>
              Helpful
            </button>
            ${deleteBtn}
          </div>
        </div>
      `;
    });
    feedContainer.innerHTML = html;
  }

  function fetchLiveFeed() {
    fetch('api/fetch_reviews.php')
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          renderFeed(data.reviews);
          if(data.stats) {
            document.getElementById('stat-spots').innerText = data.stats.spots;
            document.getElementById('stat-reviews').innerText = data.stats.reviews;
            document.getElementById('stat-votes').innerText = data.stats.votes;
            document.getElementById('stat-users').innerText = data.stats.users;
          }
        }
      })
      .catch(err => console.error("Sync error:", err));
  }

  setInterval(fetchLiveFeed, 3000);
  fetchLiveFeed(); // Initial load
</script>
 <script src="barScript.js"></script>
</body>
</html>