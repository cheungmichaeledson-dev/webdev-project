async function includeHTML(selector, url) {
  const el = document.querySelector(selector);
  if (!el) return;
  const resp = await fetch(url);
  if (!resp.ok) throw new Error(`Failed to load ${url}`);
  const html = await resp.text();
  el.innerHTML = html;
}

async function initUserMenu() {
  try {
    const resp = await fetch('get_session.php');
    const session = await resp.json();

    const menuContainer = document.getElementById('user-menu');
    if (!menuContainer) return;

    if (session.loggedIn && session.username) {
      menuContainer.innerHTML = `
        <button class="nav-username-btn" id="nav-username-btn" aria-haspopup="true" aria-expanded="false">
          ${escapeHtml(session.username)} &#x25BE;
        </button>
        <div class="nav-dropdown" id="nav-dropdown" role="menu" aria-hidden="true">
          <button class="nav-dropdown-item logout-item" id="nav-logout-btn" role="menuitem">
            Logout
          </button>
        </div>
      `;

      const btn = document.getElementById('nav-username-btn');
      const dropdown = document.getElementById('nav-dropdown');

      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = dropdown.classList.toggle('open');
        btn.setAttribute('aria-expanded', isOpen);
        dropdown.setAttribute('aria-hidden', !isOpen);
      });

      // Defer so this doesn't catch the same click that opened the dropdown
      setTimeout(() => {
        document.addEventListener('click', (e) => {
          if (!menuContainer.contains(e.target)) {
            dropdown.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
            dropdown.setAttribute('aria-hidden', 'true');
          }
        });
      }, 0);

      document.getElementById('nav-logout-btn').addEventListener('click', () => {
        dropdown.classList.remove('open');
        showLogoutModal();
      });

    } else {
      menuContainer.innerHTML = `<a href="login.php" id="nav-login-link">Login</a>`;
    }
  } catch (err) {
    console.warn('Session check failed:', err);
  }
}

function showLogoutModal() {
  let modal = document.getElementById('logout-modal');
  if (modal) {
    modal.classList.add('open');
    return;
  }

  modal = document.createElement('div');
  modal.id = 'logout-modal';
  modal.className = 'logout-modal open';
  modal.setAttribute('role', 'dialog');
  modal.setAttribute('aria-modal', 'true');
  modal.setAttribute('aria-labelledby', 'logout-modal-title');
  modal.innerHTML = `
    <div class="logout-modal-backdrop"></div>
    <div class="logout-modal-box">
      <h2 id="logout-modal-title" class="logout-modal-title">Log out?</h2>
      <p class="logout-modal-msg">Are you sure you want to log out of your account?</p>
      <div class="logout-modal-actions">
        <button class="logout-modal-cancel" id="logout-cancel-btn">Cancel</button>
        <a href="logout.php" class="logout-modal-confirm">Yes, Log Out</a>
      </div>
    </div>
  `;
  document.body.appendChild(modal);

  document.getElementById('logout-cancel-btn').addEventListener('click', () => {
    modal.classList.remove('open');
  });

  modal.querySelector('.logout-modal-backdrop').addEventListener('click', () => {
    modal.classList.remove('open');
  });
}

function escapeHtml(str) {
  return str.replace(/[&<>"']/g, c =>
    ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])
  );
}

document.addEventListener('DOMContentLoaded', () => {
  includeHTML('#header-placeholder', 'header.html').then(() => {
    initUserMenu();
  });
  includeHTML('#sidebar-placeholder', 'sidebar.html');
});