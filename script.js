function scrollTeam(direction) {
      const carousel = document.getElementById('teamCarousel');
      // Dynamically calculate scroll amount based on card width + gap
      const cardWidth = carousel.querySelector('.team-card').offsetWidth;
      const gap = 32; // 2rem gap
      const scrollAmount = cardWidth + gap;
      
      carousel.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
      });
    }

    // --- Scroll Animations (Intersection Observer) ---
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

    // --- 2. Carousel Controls ---
    const carousel = document.getElementById('servicesCarousel');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    function scrollCarousel(direction) {
      // Calculate width of one card + gap to snap perfectly
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

    // --- 3. Creative Parallax Floating Background Elements ---
    const shapesContainer = document.getElementById('shapesContainer');
    const shapes = [];

    // Create random minimalist circles
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
      
      // Store speed in data attribute for parallax calc
      shape.dataset.speed = speed;
      
      shapesContainer.appendChild(shape);
      shapes.push(shape);
    }

    // Parallax logic for shapes based on scroll
    window.addEventListener('scroll', () => {
      const scrollY = window.scrollY;
      shapes.forEach(shape => {
        const speed = parseFloat(shape.dataset.speed);
        // Move element upward at different speeds relative to scroll
        const yPos = -(scrollY * speed);
        shape.style.transform = `translateY(${yPos}px)`;
      });
    });
    function scrollTeam(direction) {
      const carousel = document.getElementById('teamCarousel');
      // Dynamically calculate scroll amount based on card width + gap
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

  // 4) Scroll reveal for key sections/cards
  const revealTargets = document.querySelectorAll(
    '.spot-card, .spot-section, .cta-section, .updates-section'
  );
  if (revealTargets.length) {
    revealTargets.forEach(el => el.classList.add('scroll-reveal'));
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealTargets.forEach(el => observer.observe(el));
  }

  // 4b) Parallax background for designated sections
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

  // 3b) Home coverflow carousel (click to advance)
  const coverflow = document.querySelector('.carousel-container');
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
      advance();
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

document.addEventListener('DOMContentLoaded', () => {
  // 1. Grab the modal and its internal elements
  const modal = document.getElementById("spotModal");
  const closeBtn = document.querySelector(".close-modal");
  const modalImg = document.getElementById("modal-img");
  const modalTitle = document.getElementById("modal-title");
  const modalDesc = document.getElementById("modal-desc");

  // 2. Grab all carousel cards
  const carouselItems = document.querySelectorAll(".carousel-item");

  // 3. Listen for clicks on every card
  carouselItems.forEach(item => {
    item.addEventListener("click", function() {
      // Get the data attributes from the specific card you clicked
      const title = this.getAttribute("data-title");
      const desc = this.getAttribute("data-desc");
      const imgsrc = this.getAttribute("data-img");

      // Inject that text/image into the hidden modal
      modalTitle.textContent = title;
      modalDesc.textContent = desc;
      modalImg.src = imgsrc;

      // Reveal the modal
      modal.classList.add("show");
    });
  });

  // 4. Close the modal when clicking the 'X'
  closeBtn.addEventListener("click", () => {
    modal.classList.remove("show");
  });

  // 5. Close the modal when clicking outside the white box (on the dark background)
  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.classList.remove("show");
    }
  });

  
});