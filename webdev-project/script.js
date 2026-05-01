// script.js
document.addEventListener('DOMContentLoaded', () => {
  // 1) Paragraph fade-in
  const paras = document.querySelectorAll('.content p');
  if (paras.length) {
    setTimeout(() => {
      paras.forEach((p, i) => setTimeout(() => p.classList.add('visible'), i * 200));
    }, 1000);
  }

  // 2) Slideshow (mySlides + dots)
  const slides = document.querySelectorAll('.mySlides');
  const dots   = document.querySelectorAll('.dot');
  let slideIndex = 1;
  if (slides.length && dots.length) {
    const showSlides = n => {
      if (n > slides.length) slideIndex = 1;
      if (n < 1) slideIndex = slides.length;
      slides.forEach(s => s.style.display = 'none');
      dots.forEach(d => d.classList.remove('active'));
      slides[slideIndex - 1].style.display = 'block';
      dots[slideIndex - 1].classList.add('active');
    };
    window.plusSlides    = n => showSlides(slideIndex += n);
    window.currentSlide  = n => showSlides(slideIndex = n);
    showSlides(slideIndex);
  }

  // 3) Team carousel (horizontal scroll)
  const carousel = document.querySelector('.team-carousel');
  const btnNext  = document.getElementById('teamNext');
  const btnPrev  = document.getElementById('teamPrev');
  if (carousel && btnNext && btnPrev) {
    const gap = parseInt(getComputedStyle(carousel).gap) || 0;
    const cardWidth = carousel.querySelector('.team-card').offsetWidth + gap;
    btnNext.addEventListener('click', () => carousel.scrollBy({ left: cardWidth, behavior: 'smooth' }));
    btnPrev.addEventListener('click', () => carousel.scrollBy({ left: -cardWidth, behavior: 'smooth' }));
    // auto-rotate
    let autoRotate = setInterval(() => carousel.scrollBy({ left: cardWidth, behavior: 'smooth' }), 4000);
    carousel.addEventListener('mouseenter', () => clearInterval(autoRotate));
    carousel.addEventListener('mouseleave', () => {
      autoRotate = setInterval(() => carousel.scrollBy({ left: cardWidth, behavior: 'smooth' }), 4000);
    });
  }



  // 5) Reviews (same code as before, but only if #reviewForm exists)
  const reviewForm = document.getElementById('reviewForm');
  const reviewsContainer = document.getElementById('reviewsList');
  if (reviewForm && reviewsContainer) {
    const REVIEWS_URL = 'https://script.google.com/macros/s/.../exec'; // your URL
    const place = reviewForm.querySelector('input[name="place"]')?.value || '';
    const avgEl = document.getElementById('averageRating');
    const escapeHTML = str => { const p = document.createElement('p'); p.textContent = str; return p.innerHTML; };

    async function loadReviews() {
      const res = await fetch(`${REVIEWS_URL}?place=${encodeURIComponent(place)}`);
      const data = await res.json();
      reviewsContainer.innerHTML = '';
      if (!data.length) {
        if (avgEl) avgEl.textContent = 'No reviews yet.';
        reviewsContainer.textContent = 'Be the first to leave a review!';
        return;
      }
      // average
      const sum = data.reduce((s, r) => s + parseInt(r.rating||0,10), 0);
      const avg = (sum/data.length).toFixed(1);
      if (avgEl) avgEl.innerHTML = `<strong>${avg}</strong> out of 5 ` +
        '★'.repeat(Math.round(avg)) + '☆'.repeat(5-Math.round(avg));

      data.forEach(r => {
        const card = document.createElement('div');
        card.className = 'review';
        card.innerHTML = `
          <p><strong>${escapeHTML(r.name)}</strong> ${
            '★'.repeat(+r.rating) + '☆'.repeat(5-(+r.rating))
          }</p>
          <p>${escapeHTML(r.review)}</p>
        `;
        reviewsContainer.appendChild(card);
      });
    }

    reviewForm.addEventListener('submit', async e => {
      e.preventDefault();
      const fd = new FormData(reviewForm);
      const params = new URLSearchParams(fd);
      const res = await fetch(REVIEWS_URL, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: params.toString()
      });
      const json = await res.json();
      if (json.result==='success') {
        reviewForm.reset();
        loadReviews();
      } else {
        alert('Error saving review: '+(json.message||''));
      }
    });

    loadReviews();
  }



  // 7) Auto-activate nav link
  document.querySelectorAll('.topbar nav a').forEach(a => {
    if (a.href === window.location.href) a.classList.add('active');
  });
});
