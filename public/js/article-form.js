// Minimal JS for article form: preview, clear and remove featured image

function previewFeaturedImage(input) {
    const preview = document.getElementById('featured_image_preview');
    const previewImg = document.getElementById('featured_image_preview_img');
    if (!preview || !previewImg) return;

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearFeaturedImagePreview() {
    const preview = document.getElementById('featured_image_preview');
    const previewImg = document.getElementById('featured_image_preview_img');
    const fileInput = document.getElementById('featured_image');

    if (preview) preview.classList.add('hidden');
    if (previewImg) previewImg.src = '';
    if (fileInput) fileInput.value = '';
}

function removeFeaturedImage() {
    // Remove any selected media id input
    const selectedMediaInput = document.querySelector('input[name="selected_media_id"]');
    if (selectedMediaInput) selectedMediaInput.remove();

    // Hide preview and clear file input
    clearFeaturedImagePreview();

    // Remove the displayed current image block if present
    const currentImageDiv = document.querySelector('.current-featured-image');
    if (currentImageDiv) currentImageDiv.remove();

    // Optionally add a hidden input to mark removal for server-side handling
    const form = document.querySelector('form');
    if (form && !form.querySelector('input[name="remove_featured_image"]')) {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'remove_featured_image';
        hidden.value = '1';
        form.appendChild(hidden);
    }

    // Friendly UI feedback
    try {
        alert('Imagen destacada eliminada');
    } catch (e) {
        // ignore
    }
}

// Expose functions to global scope (in case template calls them inline)
window.previewFeaturedImage = previewFeaturedImage;
window.clearFeaturedImagePreview = clearFeaturedImagePreview;
window.removeFeaturedImage = removeFeaturedImage;
