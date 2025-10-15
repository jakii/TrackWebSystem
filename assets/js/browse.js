// ================== VIEW TOGGLE & SEARCH ==================
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("toggleViewBtn");
    const listView = document.getElementById("listView");
    const gridView = document.getElementById("gridView");

    // ---- Handle View Toggle ----
    let currentView = sessionStorage.getItem("viewMode") || "list";

    function updateView() {
        if (currentView === "grid") {
            listView.classList.add("d-none");
            gridView.classList.remove("d-none");
            toggleBtn.innerHTML = '<i class="fas fa-list me-1"></i> List View';
        } else {
            gridView.classList.add("d-none");
            listView.classList.remove("d-none");
            toggleBtn.innerHTML = '<i class="fas fa-th-large me-1"></i> Grid View';
        }
    }

    updateView();

    toggleBtn.addEventListener("click", function () {
        currentView = currentView === "grid" ? "list" : "grid";
        sessionStorage.setItem("viewMode", currentView);
        updateView();
    });

    // ---- Keep view mode consistent on navigation ----
    document.querySelectorAll("[data-folder-link]").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const href = this.getAttribute("href");
            const separator = href.includes("?") ? "&" : "?";
            window.location.href = href + separator + "view=" + currentView;
        });
    });

    // ---- Search Function ----
    const searchInput = document.getElementById("folderSearch");
    const listRows = document.querySelectorAll("#listView tbody tr");
    const gridCards = document.querySelectorAll("#gridView .card");

    if (searchInput) {
        searchInput.addEventListener("input", function () {
            const query = this.value.toLowerCase().trim();

            // Filter list view
            listRows.forEach(row => {
                const nameCell = row.querySelector("td:first-child");
                const text = nameCell ? nameCell.textContent.toLowerCase() : "";
                row.style.display = text.includes(query) ? "" : "none";
            });

            // Filter grid view
            gridCards.forEach(card => {
                const name = card.querySelector("h6") ? card.querySelector("h6").textContent.toLowerCase() : "";
                card.parentElement.style.display = name.includes(query) ? "" : "none";
            });
        });
    }
});
