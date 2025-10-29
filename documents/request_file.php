<?php
require_once '../includes/header.php';
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAuth();

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Fetch all users except the logged-in one
$stmt = $db->prepare("SELECT id, username, email FROM users WHERE id != ?");
$stmt->execute([$_SESSION['user_id']]);
$users = $stmt->fetchAll();
?>

<div class="container py-5">
  <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 700px;">
    <div class="card-body p-4">
      <h4 class="fw-semibold text-dark mb-4">
        <i class="fas fa-file-import text-primary me-2"></i>Request a File
      </h4>

      <form id="requestFileForm" action="<?= BASE_URL; ?>api/api_request_file.php" method="post">
        <!-- Recipient -->
        <div class="mb-3">
          <label class="form-label fw-semibold" for="recipient_id">
            Recipient (Email or Username)
            <span class="text-danger fw-bold" style="font-size:1.5em;">*</span>
          </label>
          <select name="recipient_id" id="recipient_id" class="form-select" required>
            <option value="">-- Select User --</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= $u['id']; ?>">
                <?= htmlspecialchars($u['username']) . " (" . htmlspecialchars($u['email']) . ")"; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Document Search -->
        <div class="mb-3 position-relative">
          <label class="form-label fw-semibold" for="document_search">
            Document Title
            <span class="text-danger fw-bold" style="font-size:1.5em;">*</span>
          </label>
          <div class="input-group">
            <input type="text" id="document_search" class="form-control" placeholder="Enter document title..." autocomplete="off" required>
            <button type="button" id="search_btn" class="btn btn-outline-primary">
              <i class="fas fa-search"></i> Search
            </button>
          </div>
          <input type="hidden" name="document_id" id="document_id">
          <div id="search_results" class="list-group position-absolute w-100 mt-1 shadow-sm"
            style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></div>
        </div>

        <!-- Purpose -->
        <div class="mb-3">
          <label class="form-label fw-semibold" for="reason">
            Purpose
            <span class="text-danger fw-bold" style="font-size:1.5em;">*</span>
          </label>
          <textarea id="reason" name="reason" class="form-control" rows="3" placeholder="Why do you need this file?" required></textarea>
        </div>

        <!-- Intended Date -->
        <div class="mb-3">
          <label class="form-label fw-semibold" for="intended_date">
            Intended Date of Usage
            <span class="text-danger fw-bold" style="font-size:1.5em;">*</span>
          </label>
          <input type="date" id="intended_date" name="intended_date" class="form-control" required>
        </div>

        <!-- Submit -->
        <div class="text-end">
          <a href="<?= BASE_URL; ?>documents/shared.php" class="btn btn-light me-2">Cancel</a>
          <button type="submit" class="btn btn-primary"
            style="background: linear-gradient(135deg, #004F80, #0073b6); border: none;">
            <i class="fas fa-paper-plane me-2"></i>Send Request
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const searchBtn = document.getElementById('search_btn');
const searchInput = document.getElementById('document_search');
const resultsBox = document.getElementById('search_results');
const hiddenDocId = document.getElementById('document_id');

// Search on button click
searchBtn.addEventListener('click', async () => {
  const query = searchInput.value.trim();
  hiddenDocId.value = '';
  if (!query) {
    showAlert('Please enter a document title to search.', 'warning');
    return;
  }

  try {
    const res = await fetch(`<?= BASE_URL; ?>api/search_documents.php?q=${encodeURIComponent(query)}`);
    const docs = await res.json();
    showSearchResults(docs);
  } catch (err) {
    showAlert('Error searching for documents.', 'danger');
  }
});

function showSearchResults(docs) {
  resultsBox.innerHTML = '';
  if (docs.length === 0) {
    resultsBox.innerHTML = '<div class="list-group-item text-muted">No documents found</div>';
    resultsBox.style.display = 'block';
    return;
  }

  docs.forEach(doc => {
    const item = document.createElement('button');
    item.type = 'button';
    item.className = 'list-group-item list-group-item-action';
    item.textContent = doc.title;
    item.addEventListener('click', () => selectDocument(doc));
    resultsBox.appendChild(item);
  });

  resultsBox.style.display = 'block';
}

function selectDocument(doc) {
  searchInput.value = doc.title;
  hiddenDocId.value = doc.id;
  resultsBox.style.display = 'none';
}

// AJAX form submit
document.getElementById('requestFileForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  if (!hiddenDocId.value) {
    showAlert('Please select a document from the search results.', 'danger');
    return;
  }

  const form = e.target;
  const formData = new FormData(form);
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;

  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
  submitBtn.disabled = true;

  try {
    const res = await fetch(form.action, { method: 'POST', body: formData });
    const data = await res.json();
    showAlert(data.message, data.status === 'success' ? 'success' : 'danger');

    if (data.status === 'success') {
      form.reset();
      hiddenDocId.value = '';
      setTimeout(() => window.location.href = "<?= BASE_URL; ?>documents/shared.php", 1500);
    } else {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    }
  } catch (error) {
    showAlert('An error occurred while sending the request.', 'danger');
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
  }
});

function showAlert(message, type) {
  const alertDiv = document.createElement('div');
  alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
  alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
  alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
  document.body.appendChild(alertDiv);
  setTimeout(() => { if (alertDiv.parentNode) alertDiv.remove(); }, 5000);
}
</script>
