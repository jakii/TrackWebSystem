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

  const maxFileSize = 50 * 1024 * 1024;

  // Drop Zone
  dropZone.addEventListener('click', () => fileInput.click());
  dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('bg-primary-subtle');
  });
  dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('bg-primary-subtle');
  });
  dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('bg-primary-subtle');
    fileInput.files = e.dataTransfer.files;
    displayFiles();
  });

  fileInput.addEventListener('change', displayFiles);

  // Hide/show asterisk for category
  categorySelect.addEventListener('change', () => {
    if (categorySelect.value) {
      categorySelect.classList.remove('is-invalid');
      if (categoryAsterisk) categoryAsterisk.classList.add('d-none');
    } else {
      if (categoryAsterisk) categoryAsterisk.classList.remove('d-none');
    }
  });

  // Initialize both asterisks on page load
  updateAsterisks();

function displayFiles() {
  fileList.innerHTML = '';
  let hasError = false;
  const dt = new DataTransfer();

  Array.from(fileInput.files).forEach((file, index) => {
    const fileName = file.name;
    const ext = fileName.split('.').pop().toLowerCase();
    const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    let error = '';

    if (!allowedExtensions.includes(ext) ||
        (!allowedMIMEs.includes(file.type) && file.type !== '')) {
      error = 'Invalid file type';
      hasError = true;
    } else if (file.size > maxFileSize) {
      error = 'File too large (max 50MB)';
      hasError = true;
    }

    // create file item
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

    // add valid files to DataTransfer
    if (!error) dt.items.add(file);
  });

  // attach remove event handlers
  fileList.querySelectorAll('.remove-file').forEach(btn => {
    btn.addEventListener('click', e => {
      const removeIndex = parseInt(e.currentTarget.dataset.index, 10);
      const newFiles = Array.from(fileInput.files).filter((_, i) => i !== removeIndex);

      const newDt = new DataTransfer();
      newFiles.forEach(f => newDt.items.add(f));

      fileInput.files = newDt.files;
      displayFiles(); // refresh the list
    });
  });

  uploadBtn.disabled = hasError || fileInput.files.length === 0;
  uploadBtn.classList.toggle('disabled', uploadBtn.disabled);

  updateAsterisks();
}

  function updateAsterisks() {
    if (fileAsterisk) {
      if (fileInput.files && fileInput.files.length > 0) {
        fileAsterisk.classList.add('d-none');
      } else {
        fileAsterisk.classList.remove('d-none');
      }
    }

    if (categoryAsterisk) {
      if (categorySelect.value) {
        categoryAsterisk.classList.add('d-none');
      } else {
        categoryAsterisk.classList.remove('d-none');
      }
    }
  }
});
