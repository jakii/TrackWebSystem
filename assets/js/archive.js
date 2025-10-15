function archiveDocument(id) {
    if (!confirm('Archive this document?')) return;
    fetch('../api/api_archive_document.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Failed to archive document');
    });
}

function unarchiveDocument(id) {
    if (!confirm('Unarchive this document?')) return;
    fetch('../api/api_unarchive_document.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Failed to unarchive document');
    });
}
