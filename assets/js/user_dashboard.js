document.addEventListener("DOMContentLoaded", function() {
  const btn = document.getElementById("showMoreUserBtn");
  if (!btn) return;
  
  btn.addEventListener("click", function() {
    const rows = document.querySelectorAll(".user-doc-row");
    const expanded = btn.dataset.expanded === "true";

    if (!expanded) {
      rows.forEach(r => r.classList.remove("d-none"));
      btn.innerHTML = '<i class="fas fa-chevron-up me-2"></i>Show Less';
      btn.dataset.expanded = "true";
    } else {
      rows.forEach((r, i) => { if (i >= 5) r.classList.add("d-none"); });
      btn.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Show More';
      btn.dataset.expanded = "false";
    }
  });
});
document.addEventListener("DOMContentLoaded", function() {
  const selectAll = document.getElementById("selectAllUser");
  const checkboxes = document.querySelectorAll(".doc-checkbox-user");
  const bulkBtn = document.getElementById("bulkActionDropdownUser");

  function updateBulkState() {
    const selected = document.querySelectorAll(".doc-checkbox-user:checked").length;
    bulkBtn.disabled = selected === 0;
    selectAll.checked = selected === checkboxes.length && selected > 0;
  }

  selectAll?.addEventListener("change", () => {
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkState();
  });

  checkboxes.forEach(cb => cb.addEventListener("change", updateBulkState));

  // Bulk actions (simulate API call / redirect)
  document.querySelectorAll(".bulk-action-user").forEach(btn => {
    btn.addEventListener("click", () => {
      const action = btn.dataset.action;
      const selected = [...document.querySelectorAll(".doc-checkbox-user:checked")].map(cb => cb.value);
      if (selected.length === 0) return;

      if (action === "delete" && !confirm("Are you sure you want to delete selected documents?")) return;

      console.log("User bulk action:", action, "on IDs:", selected);
      // You can call an API or redirect to a PHP script here:
      // window.location.href = `documents/bulk_action.php?action=${action}&ids=${selected.join(",")}`;
    });
  });

  // Show More / Less toggle
  const btn = document.getElementById("showMoreUserBtn");
  if (btn) {
    btn.addEventListener("click", function() {
      const rows = document.querySelectorAll(".user-doc-row");
      const expanded = btn.dataset.expanded === "true";
      if (!expanded) {
        rows.forEach(r => r.classList.remove("d-none"));
        btn.innerHTML = '<i class="fas fa-chevron-up me-2"></i>Show Less';
        btn.dataset.expanded = "true";
      } else {
        rows.forEach((r, i) => { if (i >= 5) r.classList.add("d-none"); });
        btn.innerHTML = '<i class="fas fa-chevron-down me-2"></i>Show More';
        btn.dataset.expanded = "false";
      }
    });
  }
});