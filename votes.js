
const VOTE_ENDPOINT = 'https://script.google.com/macros/s/AKfycbwM_lFotSkp6vtiUzdxeyQp_pFWyxzxXhRY4zMlm4dHbiBurFKoHB2yni8m9XdItfDE/exec';

document.addEventListener('DOMContentLoaded', () => {
  loadVoteTotals();
  restoreVoteState();
  setupVoteButtons();
});

function loadVoteTotals() {
  fetch(VOTE_ENDPOINT)
    .then(res => res.json())
    .then(data => {
      data.forEach(({ itemId, score }) => {
        const card = document.querySelector(`.spot-card[data-id="${itemId}"]`);
        if (card) {
          const countEl = card.querySelector('.vote-count');
          if (countEl) countEl.textContent = score;
        }
      });
    })
    .catch(err => console.error('Failed to load vote totals:', err));
}


function restoreVoteState() {
  document.querySelectorAll('.spot-card').forEach(card => {
    const id     = card.dataset.id;
    const stored = sessionStorage.getItem(`vote-${id}`);

    if (stored === 'upvote' || stored === 'downvote') {
      const btn = card.querySelector(`.${stored}`);
      if (btn) btn.classList.add('active');
    }
  });
}

function setupVoteButtons() {
  document.querySelectorAll('.vote-btn').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      e.stopPropagation();

      const card    = btn.closest('.spot-card');
      const itemId  = card.dataset.id;
      const countEl = card.querySelector('.vote-count');
      const voteType= btn.classList.contains('upvote') ? 'upvote' : 'downvote';
      const delta   = voteType === 'upvote' ?  1 : -1;

      let currentScore = parseInt(countEl.textContent, 10) || 0;
      const previous   = sessionStorage.getItem(`vote-${itemId}`);

      if (previous === voteType) {
        countEl.textContent = currentScore - delta;
        btn.classList.remove('active');
        sessionStorage.removeItem(`vote-${itemId}`);
        sendVote(itemId, -delta);

      } else {

        if (previous === 'upvote' || previous === 'downvote') {
          const prevBtn = card.querySelector(`.${previous}`);
          if (prevBtn) prevBtn.classList.remove('active');
          currentScore -= (previous === 'upvote' ? 1 : -1);
        }

        countEl.textContent = currentScore + delta;
        sessionStorage.setItem(`vote-${itemId}`, voteType);
        btn.classList.add('active');
        sendVote(itemId, delta);
      }
    });
  });
}

function sendVote(itemId, delta) {
  const payload = `itemId=${encodeURIComponent(itemId)}&delta=${delta}`;
  fetch(VOTE_ENDPOINT, {
    method:  'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body:    payload
  }).catch(err => console.error('Vote failed:', err));
}

