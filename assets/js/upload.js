// /assets/js/upload.js
document.addEventListener("DOMContentLoaded", function () {
    const dropZone = document.getElementById("drop-zone");
    const fileInput = document.getElementById("documents");
    const fileList = document.getElementById("file-list");

    if (!dropZone || !fileInput) return;

    // Click on drop-zone opens file selector
    dropZone.addEventListener("click", () => fileInput.click());

    // Show selected files
    function updateFileList(files) {
        fileList.innerHTML = "";
        Array.from(files).forEach(file => {
            const item = document.createElement("div");
            item.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
            fileList.appendChild(item);
        });
    }

    // Drag over
    dropZone.addEventListener("dragover", e => {
        e.preventDefault();
        dropZone.classList.add("bg-light");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("bg-light");
    });

    // Drop
    dropZone.addEventListener("drop", e => {
        e.preventDefault();
        dropZone.classList.remove("bg-light");
        fileInput.files = e.dataTransfer.files;
        updateFileList(fileInput.files);
    });

    // Manual select
    fileInput.addEventListener("change", () => {
        updateFileList(fileInput.files);
    });
});
