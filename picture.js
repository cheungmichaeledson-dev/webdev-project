
document.addEventListener('DOMContentLoaded', () => {

  const overlay = document.createElement('div');
  overlay.className = 'lightbox-overlay';
  document.body.appendChild(overlay);

  overlay.addEventListener('click', () => {
    overlay.classList.remove('visible');
  });

  document.querySelectorAll('.gallery-section .grid-three img').forEach(img => {
    img.style.cursor = 'zoom-in';         // cue users that itâ€™s clickable
    img.addEventListener('click', () => {
      const clone = img.cloneNode();
      clone.style.cursor = 'zoom-out';    // cue to click out
      overlay.innerHTML = '';             // clear any previous
      overlay.appendChild(clone);
      overlay.classList.add('visible');
    });
  });
});

