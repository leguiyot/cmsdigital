// Simplified image functions for article form
console.log('Image functions loaded');

function previewFeaturedImage(input) {
    if (input.files && input.files[0]) {
        const preview = document.getElementById('featured_image_preview');
        const previewImg = document.getElementById('featured_image_preview_img');
        
        if (preview && previewImg) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
}

function removeFeaturedImage() {
    // Clear file input
    const fileInput = document.getElementById('featured_image');
    if (fileInput) {
        fileInput.value = '';
    }
    
    // Clear selected media
    const selectedInput = document.querySelector('input[name="selected_media_id"]');
    if (selectedInput) {
        selectedInput.remove();
    }
    
    // Hide preview
    const preview = document.getElementById('featured_image_preview');
    if (preview) {
        preview.classList.add('hidden');
    }
    
    // Remove current image display
    const currentImageDiv = document.querySelector('.bg-white.rounded-lg.shadow.p-6 .mb-4');
    if (currentImageDiv) {
        currentImageDiv.remove();
    }
    
    alert('Imagen eliminada');
}

// Make functions global
window.openImageGallery = openImageGallery;
window.closeImageGallery = closeImageGallery;
window.previewFeaturedImage = previewFeaturedImage;
window.removeFeaturedImage = removeFeaturedImage;
window.selectImage = selectImage;