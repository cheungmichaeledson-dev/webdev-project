// ── Tab switching ──
function switchTab(name) {
  document.querySelectorAll('.auth-tab').forEach(t => {
    t.classList.toggle('active', t.id === 'tab-' + name);
    t.setAttribute('aria-selected', t.id === 'tab-' + name);
  });
  document.querySelectorAll('.auth-panel').forEach(p => {
    p.classList.toggle('active', p.id === 'panel-' + name);
  });
}

// ── Toggle password visibility ──
function togglePw(inputId, btn) {
  const input = document.getElementById(inputId);
  const isHidden = input.type === 'password';
  input.type = isHidden ? 'text' : 'password';
  btn.textContent = isHidden ? '🙈' : '👁';
  btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
}

// ── Keep register tab active after a server-side register error ──
// The PHP template writes the last POST action into a data attribute on <body>
// so this file stays free of inline PHP.
(function () {
  const action = document.body.dataset.lastAction;
  if (action === 'register') switchTab('register');
})();
