/**
 * Blog Form Handler
 * 
 * Handles front-end form interactions, content editing, file uploads,
 * and user experience enhancements for the blog submission form.
 *
 * @package PersonalProjectWP
 */

(function() {
  'use strict';

  // Initialize when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initSubmitPostForm();
    initContentEditor();
    initExcerptCounter();
    initFeaturedImageUpload();
    initFormValidation();
  });

  /**
   * Initialize submit post form
   */
  function initSubmitPostForm() {
    const form = document.getElementById('submit-post-form');
    if(!form) return;

    // Add loading state on submit
    form.addEventListener('submit', function(e) {
      const submitBtn = form.querySelector('#submit-btn');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Publishing...';
      submitBtn.classList.add('loading');
    });
  }

  /**
   * Initialize contenteditable editor
   */
  function initContentEditor() {
    const editor = document.getElementById('post_content');
    const hiddenInput = document.getElementById('post_content_hidden');

    if(!editor) return;

    // Sync content to hidden input on input
    editor.addEventListener('input', function() {
      hiddenInput.value = editor.innerHTML;
    });

    // Toolbar button actions
    const toolbarBtns = document.querySelectorAll('.toolbar-btn');
    toolbarBtns.forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const action = this.dataset.action;
        const value = prompt(`Enter ${action}:`, '');
        if(value !== null) {
          document.execCommand(action, false, value);
          editor.focus();
        }
      });
    });

    // Handle keyboard shortcuts
    editor.addEventListener('keydown', function(e) {
      if(e.ctrlKey || e.metaKey) {
        switch(e.key.toLowerCase()) {
          case 'b':
            e.preventDefault();
            document.execCommand('bold', false, null);
            break;
          case 'i':
            e.preventDefault();
            document.execCommand('italic', false, null);
            break;
          case 'u':
            e.preventDefault();
            document.execCommand('underline', false, null);
            break;
        }
      }
    });

    // Set hidden input value initially
    hiddenInput.value = editor.innerHTML;
  }

  /**
   * Initialize excerpt character counter
   */
  function initExcerptCounter() {
    const excerptField = document.getElementById('post_excerpt');
    const excerptCount = document.getElementById('excerpt-count');

    if(!excerptField || !excerptCount) return;

    // Update count on input
    excerptField.addEventListener('input', function() {
      excerptCount.textContent = this.value.length;
    });

    // Set initial count
    excerptCount.textContent = excerptField.value.length;
  }

  /**
   * Initialize featured image upload
   */
  function initFeaturedImageUpload() {
    const uploadBtn = document.getElementById('upload-featured-image');
    const removeBtn = document.querySelector('#remove-featured-image');
    const imageIdInput = document.getElementById('featured_image_id');
    const imagePreview = document.getElementById('featured-image-preview');

    if(!uploadBtn) return;

    // Open media uploader
    uploadBtn.addEventListener('click', function(e) {
      e.preventDefault();

      // Use WordPress media uploader if available
      if(typeof wp !== 'undefined' && wp.media) {
        const mediaUploader = wp.media({
          title: 'Choose Featured Image',
          button: { text: 'Use as Featured Image' },
          multiple: false,
          library: { type: 'image' }
        });

        mediaUploader.on('select', function() {
          const attachment = mediaUploader.state().get('selection').first().toJSON();
          setFeaturedImage(attachment.id, attachment.url);
        });

        mediaUploader.open();
      } else {
        // Fallback: simple file input
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.addEventListener('change', function() {
          uploadImageFile(this.files[0]);
        });
        input.click();
      }
    });

    // Remove featured image
    if(removeBtn) {
      removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        imageIdInput.value = '';
        imagePreview.innerHTML = `
          <div class="placeholder">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M42 6H6C4.9 6 4 6.9 4 8v32c0 1.1.9 2 2 2h36c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 32H6V8h36v30zm-6-10l-7-10-5 7-4-5-4 6h22z" fill="currentColor"/>
            </svg>
            <p>Click to upload or drag and drop</p>
          </div>
        `;
        uploadBtn.style.display = 'block';
      });
    }

    // Drag and drop
    imagePreview.addEventListener('dragover', function(e) {
      e.preventDefault();
      this.style.borderColor = 'var(--color-secondary)';
      this.style.background = 'rgba(52, 152, 219, 0.1)';
    });

    imagePreview.addEventListener('dragleave', function() {
      this.style.borderColor = '';
      this.style.background = '';
    });

    imagePreview.addEventListener('drop', function(e) {
      e.preventDefault();
      this.style.borderColor = '';
      this.style.background = '';

      if(e.dataTransfer.files.length) {
        uploadImageFile(e.dataTransfer.files[0]);
      }
    });

    /**
     * Upload image file via AJAX
     */
    function uploadImageFile(file) {
      if(!file.type.startsWith('image/')) {
        alert('Please select a valid image file.');
        return;
      }

      const formData = new FormData();
      formData.append('action', 'ppwp_upload_featured_image');
      formData.append('image', file);
      formData.append('_wpnonce', ppwpBlog.upload_nonce);

      uploadBtn.disabled = true;
      uploadBtn.textContent = 'Uploading...';

      fetch(ppwpBlog.ajax_url, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          setFeaturedImage(data.data.id, data.data.url);
        } else {
          alert('Upload failed: ' + (data.data || 'Unknown error'));
        }
        uploadBtn.disabled = false;
        uploadBtn.textContent = 'Choose Image';
      })
      .catch(error => {
        console.error('Upload error:', error);
        alert('Upload failed. Please try again.');
        uploadBtn.disabled = false;
        uploadBtn.textContent = 'Choose Image';
      });
    }

    /**
     * Set featured image in form
     */
    function setFeaturedImage(imageId, imageUrl) {
      imageIdInput.value = imageId;
      imagePreview.innerHTML = `
        <img src="${imageUrl}" alt="Featured Image">
        <button type="button" class="btn btn-small btn-danger" id="remove-featured-image">
          Remove Image
        </button>
      `;
      uploadBtn.style.display = 'none';

      // Reattach remove button listener
      const newRemoveBtn = imagePreview.querySelector('#remove-featured-image');
      if(newRemoveBtn) {
        newRemoveBtn.addEventListener('click', function(e) {
          e.preventDefault();
          imageIdInput.value = '';
          imagePreview.innerHTML = `
            <div class="placeholder">
              <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M42 6H6C4.9 6 4 6.9 4 8v32c0 1.1.9 2 2 2h36c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 32H6V8h36v30zm-6-10l-7-10-5 7-4-5-4 6h22z" fill="currentColor"/>
              </svg>
              <p>Click to upload or drag and drop</p>
            </div>
          `;
          uploadBtn.style.display = 'block';
        });
      }
    }
  }

  /**
   * Initialize form validation
   */
  function initFormValidation() {
    const form = document.getElementById('submit-post-form');
    const titleInput = document.getElementById('post_title');
    const contentEditor = document.getElementById('post_content');
    const contentHidden = document.getElementById('post_content_hidden');

    if(!form) return;

    // Real-time validation feedback
    if(titleInput) {
      titleInput.addEventListener('input', function() {
        const length = this.value.length;
        if(length === 0) {
          this.classList.remove('valid');
          this.classList.add('error');
        } else if(length > 200) {
          this.classList.add('error');
        } else {
          this.classList.remove('error');
          this.classList.add('valid');
        }
      });
    }

    if(contentEditor) {
      contentEditor.addEventListener('input', function() {
        const wordCount = getWordCount(this.textContent);
        if(wordCount >= 100) {
          this.classList.remove('error');
          this.classList.add('valid');
        } else {
          this.classList.add('error');
        }
      });
    }

    // Form submission validation
    form.addEventListener('submit', function(e) {
      const title = titleInput ? titleInput.value.trim() : '';
      const content = contentHidden.value.trim();
      const wordCount = getWordCount(content);

      let hasErrors = false;

      if(!title) {
        showFieldError(titleInput, 'Title is required');
        hasErrors = true;
      } else if(title.length > 200) {
        showFieldError(titleInput, 'Title must be less than 200 characters');
        hasErrors = true;
      }

      if(!content || wordCount < 100) {
        showFieldError(contentEditor, `Content must be at least 100 words (currently ${wordCount})`);
        hasErrors = true;
      }

      if(hasErrors) {
        e.preventDefault();
        scrollToFirstError();
      }
    });

    /**
     * Get word count from text
     */
    function getWordCount(text) {
      return text.trim().split(/\s+/).filter(word => word.length > 0).length;
    }

    /**
     * Show field error
     */
    function showFieldError(field, message) {
      let errorEl = field.nextElementSibling;
      if(!errorEl || !errorEl.classList.contains('field-error')) {
        errorEl = document.createElement('div');
        errorEl.className = 'field-error';
        field.parentNode.insertBefore(errorEl, field.nextSibling);
      }
      errorEl.textContent = message;
      field.classList.add('error');
    }

    /**
     * Scroll to first error
     */
    function scrollToFirstError() {
      const firstError = form.querySelector('.error');
      if(firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus();
      }
    }
  }

})();
