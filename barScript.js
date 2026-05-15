
    async function includeHTML(selector, url) {
      const resp = await fetch(url);
      if (!resp.ok) throw new Error(`Failed to load ${url}`);
      const html = await resp.text();
      document.querySelector(selector).innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', () => {
      includeHTML('#header-placeholder', 'header.html');
      includeHTML('#sidebar-placeholder', 'sidebar.html');
    });

