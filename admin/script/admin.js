/* LOGOUT BUTTON CONFIRMATION */
const modal = document.getElementById('confirmationModal');
const confirmButton = document.getElementById('confirmButton');
const confirmYes = document.getElementById('confirmYes');
const confirmNo = document.getElementById('confirmNo');

confirmButton.addEventListener('click', () => {
  modal.style.display = 'block';
});

confirmYes.addEventListener('click', () => {
  window.location.replace('../reg-user/login_form.php');
});

confirmNo.addEventListener('click', () => {
  modal.style.display = 'none';
});

window.addEventListener('click', (event) => {
  if (event.target === modal) {
    modal.style.display = 'none';
  }
});

// Prevent back navigation after logout confirmation modal
window.history.pushState(null, '', window.location.href);
window.onpopstate = () => {
  window.history.pushState(null, '', window.location.href);
};


/* TAB ACTIVE BUTTON INDICATION */
function openTab(evt, tabName) {
  const tabcontent = document.getElementsByClassName("tabcontent");
  for (let i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  const tablinks = document.getElementsByClassName("tablinks");
  for (let i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  const tab = document.getElementById(tabName);
  if (!tab) return;

  switch(tabName) {
    case "Organizations": tab.style.display = "flex"; break;
    case "Events": tab.style.display = "block"; break;
    case "Achievements": tab.style.display = "block"; break;
    case "Applicants": tab.style.display = "inline-block"; break;
    case "Announcements": tab.style.display = "table"; break;
    case "About Us": tab.style.display = "inline-flex"; break;
    case "tab7": tab.style.display = "list-item"; break;
    case "tab8": tab.style.display = "contents"; break;
    default: tab.style.display = "flex";
  }

  evt.currentTarget.classList.add("active");
}


/* ADD BUTTON DROPDOWN FUNCTIONALITY */
const addBtn = document.getElementById('addBtn');
const dropdownMenu = document.getElementById('dropdownMenu');

addBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  const isVisible = dropdownMenu.style.display === 'block';
  if (isVisible) {
    dropdownMenu.style.display = 'none';
    addBtn.setAttribute('aria-expanded', 'false');
  } else {
    dropdownMenu.style.display = 'block';
    addBtn.setAttribute('aria-expanded', 'true');
  }
});

document.addEventListener('click', () => {
  dropdownMenu.style.display = 'none';
  addBtn.setAttribute('aria-expanded', 'false');
});

document.getElementById('createPostBtn').addEventListener('click', () => {
  window.location.href = 'form/create-achievement.php';
});
document.getElementById('createEventBtn').addEventListener('click', () => {
  window.location.href = 'form/create-event.php';
});
document.getElementById('createAnnouncementBtn').addEventListener('click', () => {
  window.location.href = 'form/create-announcement.php';
});


/* SIDEBAR FILTERING AND MODAL IMAGE POPUP */
document.addEventListener('DOMContentLoaded', () => {
  // Achievement status filter
  const statusList = document.getElementById('statusList');
  const statusItems = statusList.querySelectorAll('li');
  const postStream = document.getElementById('postStream');
  const posts = postStream.querySelectorAll('.post-card');

  function filterPosts(status) {
    let anyVisible = false;
    posts.forEach(post => {
      if (post.dataset.isApprove === status) {
        post.classList.remove('hidden');
        anyVisible = true;
      } else {
        post.classList.add('hidden');
      }
    });

    document.getElementById('noPostsPosted').style.display = 'none';
    document.getElementById('noPostsPending').style.display = 'none';
    document.getElementById('noPostsDenied').style.display = 'none';

    if (!anyVisible) {
      if (status === '1') document.getElementById('noPostsPosted').style.display = 'block';
      else if (status === '3') document.getElementById('noPostsPending').style.display = 'block';
      else if (status === '0') document.getElementById('noPostsDenied').style.display = 'block';
    }
  }

  statusItems.forEach(item => {
    item.addEventListener('click', () => {
      statusItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      filterPosts(item.dataset.status);
    });
  });

  filterPosts('1'); // default filter

  // Modal image popup
  const modal = document.getElementById('imageModal2');
  const modalImage = document.getElementById('modalImage');
  const closeBtn = modal.querySelector('.close');
  const prevBtn = modal.querySelector('.prev');
  const nextBtn = modal.querySelector('.next');
  const modalCaption = document.getElementById('modalCaption');

  let currentImages = [];
  let currentIndex = 0;

  function normalizeUrl(url) {
    try {
      return new URL(url, window.location.origin).href;
    } catch {
      return url;
    }
  }

  function showImage() {
    modalImage.src = currentImages[currentIndex];
    modalImage.alt = `Image ${currentIndex + 1}`;
    modalCaption.textContent = `Image ${currentIndex + 1} of ${currentImages.length}`;
    prevBtn.style.display = currentIndex === 0 ? 'none' : 'flex';
    nextBtn.style.display = currentIndex === currentImages.length - 1 ? 'none' : 'flex';
  }

  function openModal(container, clickedSrc) {
    const allImagesData = container.getAttribute('data-all-images');
    if (!allImagesData) return;

    try {
      currentImages = JSON.parse(allImagesData);
    } catch {
      currentImages = Array.from(container.querySelectorAll('.post-image')).map(img => img.src);
    }

    currentIndex = currentImages.indexOf(clickedSrc);
    if (currentIndex === -1) currentIndex = 0;

    showImage();
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
  }

  closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
  });

  window.addEventListener('click', e => {
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

  document.addEventListener('keydown', e => {
    if (modal.style.display === 'flex') {
      if (e.key === 'ArrowRight') nextBtn.click();
      else if (e.key === 'ArrowLeft') prevBtn.click();
      else if (e.key === 'Escape') closeBtn.click();
    }
  });

  document.addEventListener('click', e => {
    let target = e.target;
    while (target && target !== document) {
      if (target.classList && target.classList.contains('clickable-image')) {
        const container = target.closest('.post-images-container');
        if (container) openModal(container, target.src);
        break;
      }
      target = target.parentNode;
    }
  });
});
