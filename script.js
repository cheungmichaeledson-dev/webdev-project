

(function () {
  'use strict';

  
  function initScrollReveal() {
    const els = document.querySelectorAll('.reveal');
    if (!els.length) return;

    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('in_view');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    els.forEach(function (el) { observer.observe(el); });
  }

  
  function initModal() {
    const modal      = document.getElementById('spotModal');
    const modalImg   = document.getElementById('modal-img');
    const modalTitle = document.getElementById('modal-title');
    const modalDesc  = document.getElementById('modal-desc');
    const closeBtn   = document.querySelector('.close_modal');

    if (!modal) return;

    document.querySelectorAll('.carousel_item').forEach(function (card) {
      card.addEventListener('click', function () {
        modalImg.src       = card.dataset.img   || '';
        modalImg.alt       = card.dataset.title || '';
        modalTitle.textContent = card.dataset.title || '';
        modalDesc.textContent  = card.dataset.desc  || '';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
      });
    });

    closeBtn && closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', function (e) {
      if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeModal();
    });

    function closeModal() {
      modal.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  
  function initHorizontalScroll() {
    const strip   = document.querySelector('.horizontal_strip');
    const pinWrap = document.querySelector('.horizontal_wrap');
    if (!strip || !pinWrap) return;

    if (window.matchMedia('(max-width: 768px)').matches) {
      pinWrap.style.transform = '';
      return;
    }

    requestAnimationFrame(function () {
      let currentScroll = 0;
      let interval = null;
      let maxScroll = 0;

      function updateMaxScroll() {
        maxScroll = Math.max(0, pinWrap.scrollWidth - strip.offsetWidth);
        if (currentScroll > maxScroll) currentScroll = maxScroll;
        pinWrap.style.transform = 'translateX(' + (-currentScroll) + 'px)';
      }

      function scrollBy(direction) {
        const speed = 12;
        if (direction === 'right' && currentScroll < maxScroll) {
          currentScroll = Math.min(currentScroll + speed, maxScroll);
        } else if (direction === 'left' && currentScroll > 0) {
          currentScroll = Math.max(currentScroll - speed, 0);
        }
        pinWrap.style.transform = 'translateX(' + (-currentScroll) + 'px)';
      }

      function makeZone(side) {
        const zone = document.createElement('div');
        zone.className = 'hover_zone ' + side + '_zone';
        zone.addEventListener('mouseenter', function () {
          interval = setInterval(function () { scrollBy(side); }, 16);
        });
        zone.addEventListener('mouseleave', function () {
          clearInterval(interval);
          interval = null;
        });
        return zone;
      }

      strip.style.position = 'relative';
      strip.appendChild(makeZone('left'));
      strip.appendChild(makeZone('right'));

      updateMaxScroll();

      const images = pinWrap.querySelectorAll('img');
      images.forEach(function (img) {
        if (img.complete) {
          updateMaxScroll();
        } else {
          img.addEventListener('load', updateMaxScroll, { once: true });
        }
      });

      window.addEventListener('resize', updateMaxScroll);
    });
  }

  
  function init() {
    initScrollReveal();
    initModal();
    initHorizontalScroll();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();

function scrollTeam(direction) {
      const carousel = document.getElementById('teamCarousel');

      const cardWidth = carousel.querySelector('.team-card').offsetWidth;
      const gap = 32; // 2rem gap
      const scrollAmount = cardWidth + gap;
      
      carousel.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
      };

      const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target); // Only animate once
          }
        });
      }, observerOptions);

      const fadeElements = document.querySelectorAll('.fade-in');
      fadeElements.forEach(el => observer.observe(el));
    });


function revealOnScroll() {
      const reveals = document.querySelectorAll('.reveal');
      const windowHeight = window.innerHeight;
      const elementVisible = 100;

      reveals.forEach((reveal) => {
        const elementTop = reveal.getBoundingClientRect().top;
        if (elementTop < windowHeight - elementVisible) {
          reveal.classList.add('active');
        }
      });
    }
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll(); // Trigger once on load

    const carousel = document.getElementById('servicesCarousel');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    function scrollCarousel(direction) {

      const cardWidth = carousel.querySelector('.service-item').offsetWidth;
      const gap = 32; // 2rem gap from CSS
      const scrollAmount = cardWidth + gap;

      carousel.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
      });
    }

    nextBtn.addEventListener('click', () => scrollCarousel(1));
    prevBtn.addEventListener('click', () => scrollCarousel(-1));

    const shapesContainer = document.getElementById('shapesContainer');
    const shapes = [];

    for(let i = 0; i < 8; i++) {
      const shape = document.createElement('div');
      shape.classList.add('shape');
      
      const size = Math.random() * 150 + 50; // 50px to 200px
      const posX = Math.random() * 100; // 0 to 100vw
      const posY = Math.random() * 100; // 0 to 100% of container
      const speed = Math.random() * 0.4 + 0.1; // Parallax speed multiplier
      
      shape.style.width = `${size}px`;
      shape.style.height = `${size}px`;
      shape.style.left = `${posX}%`;
      shape.style.top = `${posY}%`;

      shape.dataset.speed = speed;
      
      shapesContainer.appendChild(shape);
      shapes.push(shape);
    }

    window.addEventListener('scroll', () => {
      const scrollY = window.scrollY;
      shapes.forEach(shape => {
        const speed = parseFloat(shape.dataset.speed);

        const yPos = -(scrollY * speed);
        shape.style.transform = `translateY(${yPos}px)`;
      });
    });
    function scrollTeam(direction) {
      const carousel = document.getElementById('teamCarousel');

      const cardWidth = carousel.querySelector('.team-card').offsetWidth;
      const gap = 32; // 2rem gap
      const scrollAmount = cardWidth + gap;
      
      carousel.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
      };

      const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target); // Only animate once
          }
        });
      }, observerOptions);

      const fadeElements = document.querySelectorAll('.fade-in');
      fadeElements.forEach(el => observer.observe(el));
    });

document.addEventListener('DOMContentLoaded', () => {
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const paras = document.querySelectorAll('.content p');
  if (paras.length) {
    setTimeout(() => {
      paras.forEach((p, i) => setTimeout(() => p.classList.add('visible'), i * 200));
    }, 1000);
  }

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

  const revealTargets = document.querySelectorAll(
    '.spot-card, .spot-section, .cta-section, .updates-section'
  );
  if (revealTargets.length) {
    revealTargets.forEach(el => el.classList.add('scroll-reveal'));
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in_view');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealTargets.forEach(el => observer.observe(el));
  }

  const parallaxEls = document.querySelectorAll('[data-parallax]');
  if (parallaxEls.length && !prefersReducedMotion) {
    const onScroll = () => {
      const scrollY = window.scrollY;
      parallaxEls.forEach(el => {
        const speed = parseFloat(el.dataset.parallaxSpeed || '0.25');
        const offset = Math.round(scrollY * speed);
        el.style.backgroundPosition = `center calc(50% + ${offset}px)`;
      });
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  const carousel = document.querySelector('.team-carousel');
  const btnNext  = document.getElementById('teamNext');
  const btnPrev  = document.getElementById('teamPrev');
  if (carousel && btnNext && btnPrev) {
    const gap = parseInt(getComputedStyle(carousel).gap) || 0;
    const cardWidth = carousel.querySelector('.team-card').offsetWidth + gap;
    btnNext.addEventListener('click', () => carousel.scrollBy({ left: cardWidth, behavior: 'smooth' }));
    btnPrev.addEventListener('click', () => carousel.scrollBy({ left: -cardWidth, behavior: 'smooth' }));

    let autoRotate = setInterval(() => carousel.scrollBy({ left: cardWidth, behavior: 'smooth' }), 4000);
    carousel.addEventListener('mouseenter', () => clearInterval(autoRotate));
    carousel.addEventListener('mouseleave', () => {
      autoRotate = setInterval(() => carousel.scrollBy({ left: cardWidth, behavior: 'smooth' }), 4000);
    });
  }

  const coverflow = document.querySelector('.carousel_container');
  if (coverflow) {
    const radios = Array.from(coverflow.querySelectorAll('input[name="position"]'));
    const advance = () => {
      if (!radios.length) return;
      const currentIndex = radios.findIndex(radio => radio.checked);
      const nextIndex = currentIndex >= 0 ? (currentIndex + 1) % radios.length : 0;
      radios[nextIndex].checked = true;
    };

    coverflow.addEventListener('click', event => {
      if (event.target instanceof HTMLInputElement) return;
      if (event.target.closest('.carousel_item')) return;
      advance();
    });
  }

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

      const sum = data.reduce((s, r) => s + parseInt(r.rating||0,10), 0);
      const avg = (sum/data.length).toFixed(1);
      if (avgEl) avgEl.innerHTML = `<strong>${avg}</strong> out of 5 ` +
        'â˜…'.repeat(Math.round(avg)) + 'â˜†'.repeat(5-Math.round(avg));

      data.forEach(r => {
        const card = document.createElement('div');
        card.className = 'review';
        card.innerHTML = `
          <p><strong>${escapeHTML(r.name)}</strong> ${
            'â˜…'.repeat(+r.rating) + 'â˜†'.repeat(5-(+r.rating))
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

  document.querySelectorAll('.topbar nav a').forEach(a => {
    if (a.href === window.location.href) a.classList.add('active');
  });
});

document.addEventListener('DOMContentLoaded', () => {

  const modal = document.getElementById("spotModal");
  const closeBtn = document.querySelector(".close_modal");
  const modalImg = document.getElementById("modal-img");
  const modalTitle = document.getElementById("modal-title");
  const modalDesc = document.getElementById("modal-desc");

  const carouselItems = document.querySelectorAll(".carousel_item");

  carouselItems.forEach(item => {
    item.addEventListener("click", function() {

      const title = this.getAttribute("data-title");
      const desc = this.getAttribute("data-desc");
      const imgsrc = this.getAttribute("data-img");

      modalTitle.textContent = title;
      modalDesc.textContent = desc;
      modalImg.src = imgsrc;

      modal.classList.add("show");
    });
  });

  closeBtn.addEventListener("click", () => {
    modal.classList.remove("show");
  });

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.classList.remove("show");
    }
  });

  
});
