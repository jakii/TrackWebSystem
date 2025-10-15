document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.getElementById('sidebar');
  const topbar = document.getElementById('topbar');
  const main = document.getElementById('mainContent');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const collapseToggleBtn = document.getElementById('collapseToggleBtn');
  const collapseIcon = collapseToggleBtn?.querySelector('i');

  // ---  Restore Sidebar State on Load ---
  const savedState = localStorage.getItem('sidebar-collapsed');
  if (savedState === 'true') {
    sidebar.classList.add('sidebar-collapsed');
    applyCollapsedLayout(true);
  }

  // Mobile Sidebar Toggle
  sidebarToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('show');
  });

  // Hide sidebar on outside click (mobile only)
  document.addEventListener('click', e => {
    if (window.innerWidth < 992 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
      sidebar.classList.remove('show');
    }
  });

  // Desktop Collapse Toggle
  collapseToggleBtn?.addEventListener('click', function () {
    const collapsed = sidebar.classList.toggle('sidebar-collapsed');
    applyCollapsedLayout(collapsed);
    localStorage.setItem('sidebar-collapsed', collapsed);
  });

  // Auto-reset sidebar visibility on resize
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) sidebar.classList.remove('show');
  });

  // Clean URL parameters
  if (window.location.search.includes('success') || window.location.search.includes('error')) {
    const url = new URL(window.location);
    url.search = '';
    window.history.replaceState({}, document.title, url);
  }

  // === Function: Apply Layout Changes ===
  function applyCollapsedLayout(isCollapsed) {

    sidebar.style.transition = 'width 0.3s ease';
    main.style.transition = 'margin-left 0.3s ease, width 0.3s ease';
    topbar.style.transition = 'left 0.3s ease';

    if (isCollapsed) {
      main.style.marginLeft = '90px';
      main.style.width = 'calc(100% - 100px)';
      topbar.style.left = '90px';
      collapseIcon?.classList.replace('fa-chevron-left', 'fa-chevron-right');
      collapseToggleBtn?.setAttribute('title', 'Expand Sidebar');
    } else {
      main.style.marginLeft = '250px';
      main.style.width = 'calc(100% - 260px)';
      topbar.style.left = '250px';
      collapseIcon?.classList.replace('fa-chevron-right', 'fa-chevron-left');
      collapseToggleBtn?.setAttribute('title', 'Collapse Sidebar');
    }
  }
});

    document.addEventListener('hidden.bs.modal', function () {
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
      document.body.classList.remove('modal-open');
      document.body.style.overflow = '';
    });