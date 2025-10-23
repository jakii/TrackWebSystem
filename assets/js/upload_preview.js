document.addEventListener('DOMContentLoaded', () => {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('documents');
    const fileList = document.getElementById('file-list');
    const uploadBtn = document.querySelector('button[name="upload"]');

    if (!dropZone || !fileInput || !fileList || !uploadBtn) return;

    const allowedExtensions = [
        'pdf','doc','docx','xls','xlsx','ppt','pptx','txt',
        'jpg','jpeg','png','gif',
        'mp4','mov','avi','mkv','wmv'
    ];
    const maxFileSize = 50 * 1024 * 1024; // 50MB

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
        fileInput.files = e.dataTransfer.files;
        displayFiles();
    });

    fileInput.addEventListener('change', displayFiles);

    function displayFiles() {
        fileList.innerHTML = '';
        let hasError = false;

        Array.from(fileInput.files).forEach(file => {
            const fileName = file.name;
            const ext = fileName.split('.').pop().toLowerCase();
            const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            let error = '';

            if (!allowedExtensions.includes(ext)) {
                error = 'Invalid file type';
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
    }
});
document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.getElementById('documents');
    const categorySelect = document.getElementById('category_id');

    const fileAsterisk = document.querySelector('label[for="documents"] span.text-danger');
    const categoryAsterisk = document.querySelector('label[for="category_id"] span.text-danger');

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            fileAsterisk?.classList.add('d-none');
        } else {
            fileAsterisk?.classList.remove('d-none');
        }
    });

    categorySelect.addEventListener('change', () => {
        if (categorySelect.value) {
            categoryAsterisk?.classList.add('d-none');
        } else {
            categoryAsterisk?.classList.remove('d-none');
        }
    });
});
