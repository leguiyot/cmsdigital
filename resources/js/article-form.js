/**
 * Article Form JavaScript
 * Maneja la funcionalidad del formulario de artículos:
 * - Auto-resize de textareas
 * - Contadores de caracteres
 * - Preview de imágenes y videos
 * - Gestión de eliminación de media
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeTextareas();
    initializeCharacterCounters();
    initializeFormDebug();
});

// === INICIALIZACIÓN ===

function initializeTextareas() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
}

function initializeCharacterCounters() {
    // Contador para excerpt
    const excerpt = document.getElementById('excerpt');
    if (excerpt) {
        const excerptCounter = document.createElement('div');
        excerptCounter.className = 'text-xs text-gray-500 mt-1 text-right';
        excerpt.parentNode.appendChild(excerptCounter);

        function updateExcerptCounter() {
            const length = excerpt.value.length;
            excerptCounter.textContent = `${length}/500 caracteres`;
            excerptCounter.className = length > 500 ? 'text-xs text-red-500 mt-1 text-right' : 'text-xs text-gray-500 mt-1 text-right';
        }

        excerpt.addEventListener('input', updateExcerptCounter);
        updateExcerptCounter();
    }

    // Contador para meta description
    const metaDesc = document.getElementById('meta_description');
    if (metaDesc) {
        const metaDescCounter = document.createElement('div');
        metaDescCounter.className = 'text-xs text-gray-500 mt-1 text-right';
        metaDesc.parentNode.appendChild(metaDescCounter);

        function updateMetaDescCounter() {
            const length = metaDesc.value.length;
            metaDescCounter.textContent = `${length}/160 caracteres`;
            metaDescCounter.className = length > 160 ? 'text-xs text-red-500 mt-1 text-right' : 'text-xs text-gray-500 mt-1 text-right';
        }

        metaDesc.addEventListener('input', updateMetaDescCounter);
        updateMetaDescCounter();
    }
}

function initializeFormDebug() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('=== FORMULARIO ENVIÁNDOSE ===');
            const formData = new FormData(form);
            
            for (let [key, value] of formData.entries()) {
                if (key === 'remove_videos' || key.includes('video')) {
                    console.log(`FORM DATA - ${key}: ${value}`);
                }
            }
            
            const removalInput = form.querySelector('input[name="remove_videos"]');
            if (removalInput) {
                console.log('INPUT DE ELIMINACIÓN ENCONTRADO:', removalInput.value);
            } else {
                console.log('❌ NO SE ENCONTRÓ INPUT DE ELIMINACIÓN');
            }
        });
    }
}

// === FEATURED IMAGE FUNCTIONS ===

function previewFeaturedImage(input) {
    const preview = document.getElementById('featured_image_preview');
    const previewImg = document.getElementById('featured_image_preview_img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function clearFeaturedImagePreview() {
    const input = document.getElementById('featured_image');
    const preview = document.getElementById('featured_image_preview');
    
    input.value = '';
    preview.classList.add('hidden');
}

function removeFeaturedImage(button) {
    if (confirm('¿Estás seguro de que quieres eliminar la imagen destacada?')) {
        const form = document.querySelector('form');
        
        const existingInput = form.querySelector('input[name="remove_featured_image"]');
        if (existingInput) {
            existingInput.remove();
        }
        
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_featured_image';
        hiddenInput.value = '1';
        form.appendChild(hiddenInput);
        
        const buttonContainer = button ? button.closest('.mb-4') : null;
        if (buttonContainer) {
            buttonContainer.style.display = 'none';
        }
    }
}

// === GALLERY FUNCTIONS ===

function previewGalleryImages(input) {
    const preview = document.getElementById('gallery_preview');
    const container = document.getElementById('gallery_preview_container');
    
    container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'relative';
                
                imgContainer.innerHTML = `
                    <img src="${e.target.result}" alt="Vista previa ${index + 1}" 
                         class="w-full h-20 object-cover rounded border border-gray-200" loading="lazy">
                    <button type="button" onclick="removeGalleryPreview(this, ${index})" 
                            class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 text-xs">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                container.appendChild(imgContainer);
            };
            
            reader.readAsDataURL(file);
        });
    }
}

function removeGalleryImage(mediaId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
        const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_gallery_images[]';
        hiddenInput.value = mediaId;
        form.appendChild(hiddenInput);
        
        const imageContainer = document.querySelector(`[data-gallery-id="${mediaId}"]`);
        if (imageContainer) {
            imageContainer.style.display = 'none';
        }
    }
}

function removeGalleryPreview(button, index) {
    const container = button.closest('.relative');
    if (container) {
        container.remove();
    }
}

// === VIDEO FUNCTIONS ===

function previewVideos(input) {
    const preview = document.getElementById('video_preview');
    const container = document.getElementById('video_preview_container');
    
    container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('video/')) {
                const videoUrl = URL.createObjectURL(file);
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                
                // Validar tamaño de archivo (40MB máximo)
                if (file.size > 40 * 1024 * 1024) {
                    alert(`El archivo "${file.name}" (${fileSize} MB) excede el límite de 40MB.`);
                    input.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                
                const videoContainer = document.createElement('div');
                videoContainer.className = 'relative group border border-gray-200 rounded-lg p-3';
                videoContainer.innerHTML = `
                    <div class="aspect-video bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                        <video class="w-full h-full object-cover rounded-lg" controls preload="metadata">
                            <source src="${videoUrl}" type="${file.type}">
                            Tu navegador no soporta videos.
                        </video>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-600">
                            <p class="font-medium">${file.name}</p>
                            <p>${fileSize} MB</p>
                        </div>
                        <button type="button" onclick="removeVideoPreview(this)" 
                                class="bg-red-600 hover:bg-red-700 text-white p-1 rounded text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
                
                container.appendChild(videoContainer);
            }
        });
    }
}

function removeVideoPreview(button) {
    const container = button.closest('.relative.group');
    if (container) {
        container.remove();
    }
}

function removeVideo(mediaId) {
    if (confirm('¿Estás seguro de que quieres eliminar este video?')) {
        console.log('Marcando video para eliminación:', mediaId);
        
        const form = document.querySelector('form');
        
        // Usar un campo único para todos los IDs de videos a eliminar
        let videoRemovalInput = form.querySelector('input[name="remove_videos"]');
        if (!videoRemovalInput) {
            videoRemovalInput = document.createElement('input');
            videoRemovalInput.type = 'hidden';
            videoRemovalInput.name = 'remove_videos';
            videoRemovalInput.value = '';
            form.appendChild(videoRemovalInput);
            console.log('✅ Input de eliminación creado:', videoRemovalInput);
        }
        
        // Agregar el ID al string separado por comas
        const currentIds = videoRemovalInput.value ? videoRemovalInput.value.split(',') : [];
        if (!currentIds.includes(mediaId.toString())) {
            currentIds.push(mediaId.toString());
            videoRemovalInput.value = currentIds.join(',');
            console.log('✅ Video agregado a lista de eliminación. Lista actual:', videoRemovalInput.value);
        }
        
        // Hide the video container
        const videoContainer = document.querySelector(`[data-video-id="${mediaId}"]`);
        if (videoContainer) {
            videoContainer.style.display = 'none';
            console.log('✅ Video container ocultado');
        }
        
        alert(`Video marcado para eliminación (ID: ${mediaId}). Lista actual: ${videoRemovalInput.value}`);
    }
}
