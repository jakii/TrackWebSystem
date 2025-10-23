document.addEventListener('DOMContentLoaded', () => {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('documents');
    const fileList = document.getElementById('file-list');
    const uploadBtn = document.querySelector('button[name="upload"]');
    const categorySelect = document.getElementById('category_id');

    if (!dropZone || !fileInput || !fileList || !uploadBtn || !categorySelect) return;

    const allowedExtensions = [
        'pdf','doc','docx','xls','xlsx','ppt','pptx','txt',
        'jpg','jpeg','png','zip','rar',
        'mp4'
    ];
    const maxFileSize = 50 * 1024 * 1024; // 50MB

    // Required field indicators
    const fileAsterisk = document.querySelector('label[for="documents"] span.text-danger');
    const categoryAsterisk = document.querySelector('label[for="category_id"] span.text-danger');

    // --- Drop zone events ---
    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('bg-primary-subtle');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('bg-primary-subtle');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('bg-primary-subtle');
        const droppedFiles = e.dataTransfer.files;
        if (droppedFiles.length > 0) {
            fileInput.files = droppedFiles;
            displayFiles();
        }
    });

    // --- File input change ---
    fileInput.addEventListener('change', displayFiles);

    // --- Category change ---
    categorySelect.addEventListener('change', () => {
        if (categorySelect.value) {
            categoryAsterisk?.classList.add('d-none');
        } else {
            categoryAsterisk?.classList.remove('d-none');
        }
    });

    // --- Display selected files with validation ---
function displayFiles() {
    fileList.innerHTML = '';
    let hasError = false;

    const allowedExtensions = [
        'pdf','doc','docx','xls','xlsx','ppt','pptx','txt',
        'jpg','jpeg','png','zip','rar','mp4'
    ];

    const allowedMIMEs = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'image/jpeg',
        'image/png',
        'application/zip',
        'application/x-rar-compressed',
        'video/mp4'
    ];

    const maxFileSize = 50 * 1024 * 1024; // 50MB

    Array.from(fileInput.files).forEach(file => {
        const fileName = file.name;
        const ext = fileName.split('.').pop().toLowerCase();
        const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        let error = '';

        if (!allowedExtensions.includes(ext) || !allowedMIMEs.includes(file.type)) {
            error = 'Invalid MIME type or file extension';
            hasError = true;
        } else if (file.size > maxFileSize) {
            error = 'File too large (max 50MB)';
            hasError = true;
        }

        const fileItem = document.createElement('div');
        fileItem.className = 'border rounded-3 p-2 mb-2 bg-white shadow-sm';

        fileItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file me-2 text-secondary"></i>${fileName}</span>
                <small class="text-muted">${fileSize}</small>
            </div>
            ${error ? `<div class="text-danger small mt-1">${error}</div>` : ''}
        `;

        fileList.appendChild(fileItem);
    });

    uploadBtn.disabled = hasError || fileInput.files.length === 0;
    uploadBtn.classList.toggle('disabled', uploadBtn.disabled);

    if (fileInput.files.length > 0) {
        fileAsterisk?.classList.add('d-none');
    } else {
        fileAsterisk?.classList.remove('d-none');
    }
}

});
