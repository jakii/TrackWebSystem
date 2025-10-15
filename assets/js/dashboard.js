document.addEventListener("DOMContentLoaded", function() {
  // ====== Skeleton Loader ======
  const skeleton = document.getElementById('loadingSkeleton');
  const content = document.getElementById('dashboardContent');
  
  setTimeout(() => {
    skeleton?.classList.add('d-none');
    content?.classList.remove('d-none');
  }, 500);

  // ====== Show More / Less ======
  const showMoreBtn = document.getElementById("showMoreBtn");
  if (showMoreBtn) {
    showMoreBtn.addEventListener("click", function() {
      const hidden = document.querySelectorAll(".doc-row.d-none");
      const allRows = document.querySelectorAll(".doc-row");
      const expanded = hidden.length === 0;

      if (!expanded) {
        hidden.forEach(r => r.classList.remove("d-none"));
        showMoreBtn.innerHTML = '<i class="fas fa-chevron-up me-2"></i>Show Less';
      } else {
        allRows.forEach((r, i) => { if (i >= 5) r.classList.add("d-none"); });
        showMoreBtn.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Show More';
      }
    });
  }
});