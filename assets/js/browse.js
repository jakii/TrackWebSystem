// ================== VIEW TOGGLE & SEARCH ==================
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("toggleViewBtn");
    const listView = document.getElementById("listView");
    const gridView = document.getElementById("gridView");

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

    document.querySelectorAll("[data-folder-link]").forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const href = this.getAttribute("href");
            const separator = href.includes("?") ? "&" : "?";
            window.location.href = href + separator + "view=" + currentView;
        });
    });

    const searchInput = document.getElementById("folderSearch");
    const listRows = document.querySelectorAll("#listView tbody tr");
    const gridCards = document.querySelectorAll("#gridView .card");

    if (searchInput) {
        searchInput.addEventListener("input", function () {
            const query = this.value.toLowerCase().trim();

            listRows.forEach(row => {
                const nameCell = row.querySelector("td:first-child");
                const text = nameCell ? nameCell.textContent.toLowerCase() : "";
                row.style.display = text.includes(query) ? "" : "none";
            });

            gridCards.forEach(card => {
                const name = card.querySelector("h6") ? card.querySelector("h6").textContent.toLowerCase() : "";
                card.parentElement.style.display = name.includes(query) ? "" : "none";
            });
        });
    }
});
function initializeMoveDocument() {
    console.log('Move document functionality initialized');
}

document.getElementById('folderSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    
    const listRows = document.querySelectorAll('#listView tbody tr');
    listRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
    
    const gridItems = document.querySelectorAll('#gridView .col-md-3, #gridView .col-sm-6');
    gridItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

document.addEventListener('DOMContentLoaded', function() {
    initializeMoveDocument();
    
    document.getElementById('toggleViewBtn')?.addEventListener('click', function() {
        const listView = document.getElementById('listView');
        const gridView = document.getElementById('gridView');
        const icon = this.querySelector('i');
        const text = this.querySelector('span') || this.lastChild;
        
        if (listView.classList.contains('d-none')) {
            listView.classList.remove('d-none');
            gridView.classList.add('d-none');
            icon.className = 'fas fa-th-large me-1';
            text.textContent = 'Grid View';
            
            const url = new URL(window.location);
            url.searchParams.set('view', 'list');
            window.history.replaceState({}, '', url);
        } else {
            listView.classList.add('d-none');
            gridView.classList.remove('d-none');
            icon.className = 'fas fa-list me-1';
            text.textContent = 'List View';
            
            const url = new URL(window.location);
            url.searchParams.set('view', 'grid');
            window.history.replaceState({}, '', url);
        }
    });
});