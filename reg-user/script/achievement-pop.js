(function(){
  const modal = document.getElementById('imageModal');
  const modalImage = document.getElementById('modalImage');
  const closeBtn = modal.querySelector('.close');
  const prevBtn = modal.querySelector('.prev');
  const nextBtn = modal.querySelector('.next');
  const imageCounter = document.getElementById('imageCounter');

  let currentImages = [];
  let currentIndex = 0;

  function normalizeUrl(url) {
    try {
      const u = new URL(url, window.location.origin);
      return u.origin + u.pathname.replace(/\/$/, '');
    } catch {
      return url;
    }
  }

  function showImage() {
    modalImage.src = currentImages[currentIndex];
    modalImage.alt = `Image ${currentIndex + 1}`;
    imageCounter.textContent = `${currentIndex + 1} / ${currentImages.length}`;

    prevBtn.style.display = currentIndex === 0 ? 'none' : 'flex';
    nextBtn.style.display = currentIndex === currentImages.length - 1 ? 'none' : 'flex';
  }

  function openModal(container, event) {
    const allImagesData = container.getAttribute('data-all-images');
    if (!allImagesData) return;

    try {
      currentImages = JSON.parse(allImagesData);
    } catch (e) {
      currentImages = Array.from(container.querySelectorAll('.post-image')).map(img => img.src);
    }

    let clickedSrc = null;
    if (event && event.target) {
      const target = event.target;
      if (target.tagName === 'IMG' && container.contains(target)) {
        clickedSrc = target.src;
      }
    }

    const normalizedClickedSrc = clickedSrc ? normalizeUrl(clickedSrc) : null;
    const normalizedImages = currentImages.map(normalizeUrl);

    currentIndex = normalizedClickedSrc ? normalizedImages.indexOf(normalizedClickedSrc) : 0;
    if (currentIndex === -1) currentIndex = 0;

    showImage();
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
  }

  closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
  });

  window.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');
    }
  });

  nextBtn.addEventListener('click', () => {
    if (currentIndex < currentImages.length - 1) {
      currentIndex++;
      showImage();
    }
  });

  prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
      currentIndex--;
      showImage();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (modal.style.display === 'flex') {
      if (e.key === 'ArrowRight') {
        nextBtn.click();
      } else if (e.key === 'ArrowLeft') {
        prevBtn.click();
      } else if (e.key === 'Escape') {
        closeBtn.click();
      }
    }
  });

  // Delegate click on images to open modal
  document.addEventListener('click', (e) => {
    if (
      e.target.classList.contains('overlay-text') ||
      (e.target.classList.contains('post-image') && e.target.closest('.post-images-container'))
    ) {
      const container = e.target.closest('.post-images-container');
      openModal(container, e);
    }
  });
})();
