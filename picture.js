// picture.js
document.addEventListener('DOMContentLoaded', () => {
  // 1) Create the overlay once
  const overlay = document.createElement('div');
  overlay.className = 'lightbox-overlay';
  document.body.appendChild(overlay);

  // 2) Clicking the overlay hides it
  overlay.addEventListener('click', () => {
    overlay.classList.remove('visible');
  });

  // 3) Wire up each thumbnail in your .gallery-section
  document.querySelectorAll('.gallery-section .grid-three img').forEach(img => {
    img.style.cursor = 'zoom-in';         // cue users that it’s clickable
    img.addEventListener('click', () => {
      const clone = img.cloneNode();
      clone.style.cursor = 'zoom-out';    // cue to click out
      overlay.innerHTML = '';             // clear any previous
      overlay.appendChild(clone);
      overlay.classList.add('visible');
    });
  });
});
