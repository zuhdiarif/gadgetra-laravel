function showToast(message) {
    var toast = document.querySelector('.success-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.className = 'success-toast';
        document.body.appendChild(toast);
    }
    toast.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
    toast.classList.add('show');
    setTimeout(function() { toast.classList.remove('show'); }, 3500);
}

document.addEventListener('DOMContentLoaded', function() {
    var triggerUpload = document.getElementById('triggerUpload');
    var uploadModal = document.getElementById('uploadModal');
    var closeModal = document.getElementById('closeModal');
    var cancelModal = document.getElementById('cancelModal');
    var fileInput = document.getElementById('fileInput');
    var uploadZone = document.getElementById('uploadZone');
    var previewBox = document.getElementById('previewBox');
    var previewImg = document.getElementById('previewImg');
    var previewInfo = document.getElementById('previewInfo');
    var removeFile = document.getElementById('removeFile');
    var submitBtn = document.getElementById('submitBtn');
    var openEditModal = document.getElementById('openEditModal');
    var editModal = document.getElementById('editModal');
    var closeEditModal = document.getElementById('closeEditModal');
    var cancelEditModal = document.getElementById('cancelEditModal');

    function openModal(modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModalFn(modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (triggerUpload) triggerUpload.addEventListener('click', function() { openModal(uploadModal); });
    if (closeModal) closeModal.addEventListener('click', function() { closeModalFn(uploadModal); resetUpload(); });
    if (cancelModal) cancelModal.addEventListener('click', function() { closeModalFn(uploadModal); resetUpload(); });
    if (openEditModal) openEditModal.addEventListener('click', function() { openModal(editModal); });
    if (closeEditModal) closeEditModal.addEventListener('click', function() { closeModalFn(editModal); });
    if (cancelEditModal) cancelEditModal.addEventListener('click', function() { closeModalFn(editModal); });

    [uploadModal, editModal].forEach(function(modal) {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModalFn(modal);
                    if (modal === uploadModal) resetUpload();
                }
            });
        }
    });

    if (uploadZone) {
        uploadZone.addEventListener('click', function() { fileInput.click(); });
        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });
        uploadZone.addEventListener('dragleave', function() { uploadZone.classList.remove('dragover'); });
        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            if (e.dataTransfer.files[0]) handleFile(e.dataTransfer.files[0]);
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (fileInput.files[0]) handleFile(fileInput.files[0]);
        });
    }

    function handleFile(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            var sizeMB = (file.size / 1024 / 1024).toFixed(2);
            previewInfo.textContent = file.name + ' • ' + sizeMB + ' MB';
            uploadZone.style.display = 'none';
            previewBox.style.display = 'block';
            submitBtn.disabled = false;
        };
        reader.readAsDataURL(file);
    }

    if (removeFile) removeFile.addEventListener('click', function() { resetUpload(); });

    function resetUpload() {
        if (fileInput) fileInput.value = '';
        if (previewImg) previewImg.src = '';
        if (previewInfo) previewInfo.textContent = '';
        if (uploadZone) uploadZone.style.display = 'block';
        if (previewBox) previewBox.style.display = 'none';
        if (submitBtn) submitBtn.disabled = true;
    }

    var cards = document.querySelectorAll('.rental-card, .info-item, .stat-item');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        cards.forEach(function(el, i) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(16px)';
            el.style.transition = 'opacity 0.5s ease ' + (i * 0.05) + 's, transform 0.5s ease ' + (i * 0.05) + 's';
            observer.observe(el);
        });
    } else {
        cards.forEach(function(el) {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    }
});