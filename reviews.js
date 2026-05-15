
document.addEventListener('DOMContentLoaded', () => {
  const reviewForm = document.getElementById('reviewForm');
  const container  = document.getElementById('reviewsList');
  const averageEl  = document.getElementById('averageRating');
  const REVIEWS_URL = 'https://script.google.com/macros/s/AKfycbwZmJ81JcNUgRNvAS0-0U1dcWypSaVN6z4Hwz8typQZq2Tdxymim_bkJFlCeb16894O/exec';

  if (!reviewForm || !container) return;

  const placeInput = reviewForm.querySelector('input[name="place"]');
  const place = placeInput ? placeInput.value : '';

  const escapeHTML = str => {
    const p = document.createElement('p');
    p.textContent = str;
    return p.innerHTML;
  };
  console.log('ðŸ“ Place is:', place);

  async function loadReviews() {
    try {
      const res = await fetch(`${REVIEWS_URL}?place=${encodeURIComponent(place)}`);
      const data = await res.json();
      container.innerHTML = '';
      if (averageEl) averageEl.textContent = '';

      if (!Array.isArray(data) || data.length === 0) {
        if (averageEl) averageEl.textContent = 'No reviews yet.';
        container.textContent = 'Be the first to leave a review!';
        return;
      }

      const sum = data.reduce((total, r) => total + parseInt(r.rating || 0), 0);
      const avg = (sum / data.length).toFixed(1);
      const stars = 'â˜…'.repeat(Math.round(avg)) + 'â˜†'.repeat(5 - Math.round(avg));

      if (averageEl) {
        averageEl.innerHTML = `<strong>${avg}</strong> out of 5 ${stars}`;
      }

      data.forEach((r, i) => {
        const name = r.name || 'Anonymous';
        const rating = parseInt(r.rating, 10) || 0;
        const review = r.review || 'No comment';

        const card = document.createElement('div');
        card.className = 'review';

        const header = document.createElement('p');
        header.innerHTML = `<strong>${escapeHTML(name)}</strong> ${
          'â˜…'.repeat(rating) + 'â˜†'.repeat(5 - rating)
        }`;
        card.appendChild(header);

        const body = document.createElement('p');
        body.textContent = review;
        card.appendChild(body);

        container.appendChild(card);
      });
    } catch (err) {
      console.error('loadReviews() error:', err);
      container.innerHTML = '<p style="color:#f44336">Failed to load reviews.</p>';
    }
  }

  reviewForm.addEventListener('submit', async e => {
    e.preventDefault();

    const formData   = new FormData(reviewForm);
    const urlEncoded = new URLSearchParams();
    formData.forEach((val, key) => urlEncoded.append(key, val));

    const res = await fetch(REVIEWS_URL, {
      method:  'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body:    urlEncoded.toString()
    });

    const json = await res.json();
    if (json.result === 'success') {
      reviewForm.reset();
      loadReviews();
    } else {
      alert('Error saving review: ' + (json.message || 'Unknown error'));
    }
  });

  loadReviews();
});

