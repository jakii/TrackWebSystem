document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.getElementById('sidebar');
  const topbar = document.getElementById('topbar');
  const main = document.getElementById('mainContent');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const collapseToggleBtn = document.getElementById('collapseToggleBtn');
  const collapseIcon = collapseToggleBtn?.querySelector('i');
  const logoText = document.querySelector('.logo-text');

  // Initialize layout
  function initializeLayout() {
    const savedState = localStorage.getItem('sidebar-collapsed');
    const isCollapsed = savedState === 'true';
    
    if (isCollapsed) {
      sidebar.classList.add('sidebar-collapsed');
      applyCollapsedLayout(true);
    } else {
      applyCollapsedLayout(false);
    }
  }

  // --- Mobile Sidebar Toggle ---
  sidebarToggle?.addEventListener('click', (e) => {
    e.stopPropagation();
    sidebar.classList.toggle('show');
  });

  // Hide sidebar when clicking outside (for small screens)
  document.addEventListener('click', e => {
    if (window.innerWidth < 992 && sidebar.classList.contains('show') && 
        !sidebar.contains(e.target) && !sidebarToggle?.contains(e.target)) {
      sidebar.classList.remove('show');
    }
  });

  // --- Collapse Sidebar Toggle ---
  collapseToggleBtn?.addEventListener('click', function (e) {
    e.stopPropagation();
    const collapsed = sidebar.classList.toggle('sidebar-collapsed');
    applyCollapsedLayout(collapsed);
    localStorage.setItem('sidebar-collapsed', collapsed);
  });

  // --- Responsive behavior ---
  function handleResponsiveToggles() {
    if (window.innerWidth < 992) {
      // Mobile behavior
      sidebarToggle?.classList.remove('d-none');
      collapseToggleBtn?.classList.add('d-none');
      // Reset to expanded state on mobile for better UX
      sidebar.classList.remove('sidebar-collapsed');
      applyCollapsedLayout(false);
    } else {
      // Desktop behavior
      sidebarToggle?.classList.add('d-none');
      collapseToggleBtn?.classList.remove('d-none');
      // Restore saved state
      initializeLayout();
    }
  }

  // Initial setup
  handleResponsiveToggles();
  initializeLayout();

  window.addEventListener('resize', () => {
    handleResponsiveToggles();
    if (window.innerWidth >= 992) {
      sidebar.classList.remove('show');
    }
  });

  // --- Clean URL (remove ?success or ?error params) ---
  if (window.location.search.includes('success') || window.location.search.includes('error')) {
    const url = new URL(window.location);
    url.search = '';
    window.history.replaceState({}, document.title, url);
  }

  // === Function: Apply Collapsed Layout ===
  function applyCollapsedLayout(isCollapsed) {
    if (window.innerWidth < 992) {
      // Mobile layout - fixed positioning
      if (isCollapsed) {
        sidebar.style.width = '80px';
        main.style.marginLeft = '0';
        main.style.width = '100%';
        topbar.style.left = '0';
      } else {
        sidebar.style.width = '220px';
        main.style.marginLeft = '0';
        main.style.width = '100%';
        topbar.style.left = '0';
      }
    } else {
      // Desktop layout
      if (isCollapsed) {
        sidebar.style.width = '80px';
        main.style.marginLeft = '90px';
        main.style.width = 'calc(100% - 100px)';
        topbar.style.left = '90px';
        if (collapseIcon) {
          collapseIcon.classList.replace('fa-chevron-left', 'fa-chevron-right');
        }
        collapseToggleBtn?.setAttribute('title', 'Expand Sidebar');
        // Hide logo text when collapsed
        if (logoText) {
          logoText.style.display = 'none';
        }
      } else {
        sidebar.style.width = '250px';
        main.style.marginLeft = '260px';
        main.style.width = 'calc(100% - 270px)';
        topbar.style.left = '260px';
        if (collapseIcon) {
          collapseIcon.classList.replace('fa-chevron-right', 'fa-chevron-left');
        }
        collapseToggleBtn?.setAttribute('title', 'Collapse Sidebar');
        // Show logo text when expanded
        if (logoText) {
          logoText.style.display = 'block';
        }
      }
    }
  }

  // --- Fix lingering modal backdrop (Bootstrap cleanup) ---
  document.addEventListener('hidden.bs.modal', function () {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
  });

  // Prevent sidebar links from closing mobile sidebar immediately
  sidebar.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
      if (window.innerWidth < 992) {
        // Small delay to allow navigation before closing
        setTimeout(() => {
          sidebar.classList.remove('show');
        }, 300);
      }
    });
  });
});