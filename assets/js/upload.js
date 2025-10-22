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

document.addEventListener("DOMContentLoaded", () => {
  const uploadBtn = document.getElementById("uploadBtn");
  const form = document.querySelector("form");
  const progressContainer = document.getElementById("uploadProgress");
  const progressBar = document.getElementById("progressBar");
  const uploadStatus = document.getElementById("uploadStatus");

  uploadBtn.addEventListener("click", function (e) {
    e.preventDefault();

    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();

    xhr.open("POST", "../api/api_upload.php", true);

    // Progress handler
    xhr.upload.addEventListener("progress", (e) => {
      if (e.lengthComputable) {
        const percent = Math.round((e.loaded / e.total) * 100);
        progressContainer.classList.remove("d-none");
        progressBar.style.width = percent + "%";
        progressBar.textContent = percent + "%";

        if (percent === 100) {
          uploadStatus.textContent = "Processing...";
        }
      }
    });

    // On success
    xhr.onload = () => {
      if (xhr.status === 200) {
        uploadStatus.textContent = "Upload complete!";
        progressBar.classList.remove("progress-bar-animated");
        progressBar.classList.add("bg-success");

        // Optional redirect
        setTimeout(() => {
          window.location.href = "../dashboard.php?status=success";
        }, 1500);
      } else {
        uploadStatus.textContent = "Upload failed. Please try again.";
        progressBar.classList.add("bg-danger");
      }
    };

    // On error
    xhr.onerror = () => {
      uploadStatus.textContent = "Upload error occurred.";
      progressBar.classList.add("bg-danger");
    };

    xhr.send(formData);
  });
});
