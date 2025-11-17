document.addEventListener('DOMContentLoaded', () => {
  const dropZone = document.getElementById('drop-zone');
  const fileInput = document.getElementById('documents');
  const fileList = document.getElementById('file-list');
  const uploadBtn = document.querySelector('button[name="upload"]');
  const categorySelect = document.getElementById('category_id');
  const fileAsterisk = document.getElementById('file-asterisk');
  const categoryAsterisk = document.getElementById('category-asterisk');

  if (!dropZone || !fileInput || !fileList || !uploadBtn || !categorySelect) return;

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
    'image/jpg',
    'image/png',
    'application/zip',
    'application/x-zip-compressed',
    'application/x-rar-compressed',
    'video/mp4',
    'application/octet-stream'
  ];

  const maxFileSize = 50 * 1024 * 1024; // 50MB

  // =============================
  // Drop Zone behavior
  // =============================
  dropZone.addEventListener('click', () => fileInput.click());
  dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('bg-primary-subtle');
  });
  dropZone.addEventListener('dragleave', () => dropZone.classList.remove('bg-primary-subtle'));
  dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('bg-primary-subtle');

    const dt = new DataTransfer();
    Array.from(e.dataTransfer.files).forEach(file => dt.items.add(file));
    fileInput.files = dt.files;

    displayFiles();
  });

  fileInput.addEventListener('change', displayFiles);

  // =============================
  // Display selected files
  // =============================
  function displayFiles() {
    fileList.innerHTML = '';
    let hasError = false;
    const dt = new DataTransfer();

    Array.from(fileInput.files).forEach((file, index) => {
      const fileName = file.name;
      const ext = fileName.split('.').pop().toLowerCase();
      const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
      let error = '';

      if (!allowedExtensions.includes(ext) || (!allowedMIMEs.includes(file.type) && file.type !== '')) {
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
          <div class="d-flex align-items-center">
            <small class="text-muted me-2">${fileSize}</small>
            <button type="button" class="btn btn-sm btn-outline-danger remove-file" data-index="${index}">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        ${error ? `<div class="text-danger small mt-1">${error}</div>` : ''}
      `;
      fileList.appendChild(fileItem);

      // Only add valid files to DataTransfer (so they will be submitted)
      if (!error) dt.items.add(file);
    });

    // Update file input to only include valid files
    fileInput.files = dt.files;

    // Handle file removal
    fileList.querySelectorAll('.remove-file').forEach(btn => {
      btn.addEventListener('click', e => {
        const removeIndex = parseInt(e.currentTarget.dataset.index, 10);
        const newFiles = Array.from(fileInput.files).filter((_, i) => i !== removeIndex);
        const newDt = new DataTransfer();
        newFiles.forEach(f => newDt.items.add(f));
        fileInput.files = newDt.files;
        displayFiles();
      });
    });

    uploadBtn.disabled = fileInput.files.length === 0; // Only disable if no valid files
    uploadBtn.classList.toggle('disabled', uploadBtn.disabled);

    updateAsterisks();
  }

  // =============================
  // Category & file asterisks
  // =============================
  function updateAsterisks() {
    if (fileAsterisk) fileAsterisk.classList.toggle('d-none', fileInput.files.length > 0);
    if (categoryAsterisk) categoryAsterisk.classList.toggle('d-none', !!categorySelect.value);
  }

  categorySelect.addEventListener('change', updateAsterisks);

  // =============================
  // Submit Spinner
  // =============================
  const form = uploadBtn.closest('form');
  if (form) {
    form.addEventListener('submit', e => {
      if (uploadBtn.disabled) {
        e.preventDefault(); // Prevent submit if no valid files
        return;
      }
      uploadBtn.disabled = true;
      uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
    });
  }

  updateAsterisks();
});
