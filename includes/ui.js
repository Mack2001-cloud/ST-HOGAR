const setupSidebar = () => {
  const sidebar = document.querySelector('[data-sidebar]');
  const toggle = document.querySelector('[data-sidebar-toggle]');
  const backdrop = document.querySelector('[data-sidebar-close]');

  if (!sidebar || !toggle) return;

  const closeSidebar = () => {
    sidebar.classList.remove('is-open');
    document.body.classList.remove('sidebar-open');
    toggle.setAttribute('aria-expanded', 'false');
  };

  const openSidebar = () => {
    sidebar.classList.add('is-open');
    document.body.classList.add('sidebar-open');
    toggle.setAttribute('aria-expanded', 'true');
  };

  toggle.addEventListener('click', () => {
    if (sidebar.classList.contains('is-open')) {
      closeSidebar();
    } else {
      openSidebar();
    }
  });

  if (backdrop) {
    backdrop.addEventListener('click', closeSidebar);
  }

  sidebar.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 960) {
        closeSidebar();
      }
    });
  });
};

const setupUserMenu = () => {
  const menu = document.querySelector('[data-user-menu]');
  const toggle = document.querySelector('[data-user-toggle]');

  if (!menu || !toggle) return;

  const closeMenu = () => {
    menu.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  };

  toggle.addEventListener('click', (event) => {
    event.stopPropagation();
    const isOpen = menu.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  document.addEventListener('click', (event) => {
    if (!menu.contains(event.target)) {
      closeMenu();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeMenu();
    }
  });
};

document.addEventListener('DOMContentLoaded', () => {
  setupSidebar();
  setupUserMenu();
});
