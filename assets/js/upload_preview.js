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

    // Append dropped files to existing files
    const dt = new DataTransfer();
    Array.from(fileInput.files).forEach(f => dt.items.add(f)); // existing
    Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f)); // new
    fileInput.files = dt.files;

    displayFiles();
  });

  fileInput.addEventListener('change', displayFiles);

  // =============================
  // Display selected files
  // =============================
  function displayFiles() {
    fileList.innerHTML = '';
    Array.from(fileInput.files).forEach((file, index) => {
      const ext = file.name.split('.').pop().toLowerCase();
      const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
      let error = '';

      if (!allowedExtensions.includes(ext)) {
        error = 'Invalid file type';
      } else if (file.size > maxFileSize) {
        error = 'File too large (max 50MB)';
      }

      const fileItem = document.createElement('div');
      fileItem.className = 'border rounded-3 p-2 mb-2 bg-white shadow-sm';
      fileItem.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <span><i class="fas fa-file me-2 text-secondary"></i>${file.name}</span>
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
    });

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

    // Disable upload button if no files
    uploadBtn.disabled = fileInput.files.length === 0;
    uploadBtn.classList.toggle('disabled', uploadBtn.disabled);

    updateAsterisks();
  }

  // =============================
  // Update asterisks
  // =============================
  function updateAsterisks() {
    if (fileAsterisk) fileAsterisk.classList.toggle('d-none', fileInput.files.length > 0);
    if (categoryAsterisk) categoryAsterisk.classList.toggle('d-none', !!categorySelect.value);
  }

  categorySelect.addEventListener('change', updateAsterisks);

  // =============================
  // Submit spinner
  // =============================
  const form = uploadBtn.closest('form');
  if (form) {
    form.addEventListener('submit', e => {
      if (uploadBtn.disabled) {
        e.preventDefault(); // Prevent submit if no files
        return;
      }
      uploadBtn.disabled = true;
      uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
    });
  }

  updateAsterisks();
});
