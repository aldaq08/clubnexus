  // Sidebar filtering
  const statusList = document.getElementById('statusList');
  const statusItems = statusList.querySelectorAll('li');
  const postStream = document.getElementById('postStream');
  const posts = postStream.querySelectorAll('.post-card');

  function filterPosts(status) {
    posts.forEach(post => {
      if (post.dataset.isApprove === status) {
        post.classList.remove('hidden');
      } else {
        post.classList.add('hidden');
      }
    });
  }

  statusItems.forEach(item => {
    item.addEventListener('click', () => {
      statusItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      const status = item.dataset.status;
      filterPosts(status);
    });
  });

  // Initialize showing POSTED posts
  filterPosts('1');

  // Approve / Disapprove button handlers
  function updateApproval(postId, newStatus) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'database/post-con.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status === 200) {
        const post = document.querySelector(`.post-card[data-post-id="${postId}"]`);
        if (!post) return;
        post.dataset.isApprove = newStatus.toString();

        const label = post.querySelector('.status-label');
        if (label) {
          if (newStatus == 1) {
            label.textContent = 'POSTED';
            label.className = 'status-label status-posted';
          } else if (newStatus == 0) {
            label.textContent = 'DENIED';
            label.className = 'status-label status-denied';
          }
        }

        const actions = post.querySelector('.post-actions');
        if (actions) actions.remove();

        const activeStatus = document.querySelector('#statusList li.active').dataset.status;
        if (activeStatus !== newStatus.toString()) {
          post.classList.add('hidden');
        }
        alert('Post status updated successfully.');
      } else {
        alert('Failed to update post status.');
      }
    };
    xhr.send(`achievement_id=${encodeURIComponent(postId)}&achievement_approve=${encodeURIComponent(newStatus)}`);
  }

  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('approve-btn')) {
      const postId = e.target.dataset.postId;
      if (confirm('Approve this post?')) {
        updateApproval(postId, 1);
      }
    }
    if (e.target.classList.contains('disapprove-btn')) {
      const postId = e.target.dataset.postId;
      if (confirm('Disapprove this post?')) {
        updateApproval(postId, 0);
      }
    }
  });


  // Modal image popup with next/prev buttons
  const modalPic = document.getElementById('imageModal');
  const modalImg = document.getElementById('modalImage');
  const closeBtn = modalPic.querySelector('.close');
  const prevBtn = modalPic.querySelector('.prev');
  const nextBtn = modalPic.querySelector('.next');
  const modalCaption = document.getElementById('modalCaption');


    let currentPostId = null;
    let currentIndex = 0;

    // Function to get all clickable images for a given postId, sorted by data-index
    function getImagesForPost(postId) {
      const imgs = Array.from(document.querySelectorAll(`.clickable-image[data-post-id="${postId}"]`));
      imgs.sort((a, b) => parseInt(a.dataset.index, 10) - parseInt(b.dataset.index, 10));
      return imgs;
    }

    function showModal(postId, index) {
      currentPostId = postId;
      currentIndex = index;
      const imgs = getImagesForPost(postId);
      if (!imgs || imgs.length === 0) return;
      const img = imgs[currentIndex];
      modalImg.src = img.dataset.fullsrc;
      modalImg.alt = img.alt || 'Expanded image';
      modalPic.style.display = 'block';
      updateNavButtons();
    }

    function updateNavButtons() {
      const imgs = getImagesForPost(currentPostId);
      prevBtn.style.display = (currentIndex > 0) ? 'block' : 'none';
      nextBtn.style.display = (currentIndex < imgs.length - 1) ? 'block' : 'none';
    }

    // Attach click listeners to all clickable images (including those hidden by filtering)
    document.querySelectorAll('.clickable-image').forEach(img => {
      img.addEventListener('click', () => {
        const postId = img.dataset.postId;
        const index = parseInt(img.dataset.index, 10);
        showModal(postId, index);
      });
    });

    closeBtn.addEventListener('click', () => {
      modalPic.style.display = 'none';
      modalImg.src = '';
    });

    prevBtn.addEventListener('click', () => {
      const imgs = getImagesForPost(currentPostId);
      if (currentIndex > 0) {
        currentIndex--;
        const img = imgs[currentIndex];
        modalImg.src = img.dataset.fullsrc;
        modalImg.alt = img.alt || 'Expanded image';
        updateNavButtons();
        modalCaption.textContent = `Image ${currentIndex + 1} of ${imgs.length}`;
      }
    });

    nextBtn.addEventListener('click', () => {
      const imgs = getImagesForPost(currentPostId);
      if (currentIndex < imgs.length - 1) {
        currentIndex++;
        const img = imgs[currentIndex];
        modalImg.src = img.dataset.fullsrc;
        modalImg.alt = img.alt || 'Expanded image';
        updateNavButtons();
        modalCaption.textContent = `Image ${currentIndex + 1} of ${imgs.length}`;
      }
    });


    function showModal(postId, index) {
        currentPostId = postId;
        currentIndex = index;
        const imgs = getImagesForPost(postId);
        if (!imgs || imgs.length === 0) return;
        const img = imgs[currentIndex];
        modalImg.src = img.dataset.fullsrc;
        modalImg.alt = img.alt || 'Expanded image';
        modal.style.display = 'block';
        updateNavButtons();

        // Set caption text: "Image X of Y"
        modalCaption.textContent = `Image ${currentIndex + 1} of ${imgs.length}`;
      }


    // Close modal on clicking outside the image
      modalPic.addEventListener('click', (e) => {
        if (e.target === modalPic) {
          modalPic.style.display = 'none';
          modalImg.src = '';
        }
      });


    // Optional: close modal on ESC key and navigate with arrow keys
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modalPic.style.display === 'block') {
        modalPic.style.display = 'none';
        modalImg.src = '';
      }
      if (modalPic.style.display === 'block') {
        if (e.key === 'ArrowLeft') {
          prevBtn.click();
        } else if (e.key === 'ArrowRight') {
          nextBtn.click();
        }
      }
    });

   



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

    // Hide all placeholders first
    document.getElementById('noPostsPosted').style.display = 'none';
    document.getElementById('noPostsPending').style.display = 'none';
    document.getElementById('noPostsDenied').style.display = 'none';

    // Show placeholder if no posts visible for the selected status
    if (!anyVisible) {
      if (status === '1') {
        document.getElementById('noPostsPosted').style.display = 'block';
      } else if (status === '3') {
        document.getElementById('noPostsPending').style.display = 'block';
      } else if (status === '0') {
        document.getElementById('noPostsDenied').style.display = 'block';
      }
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    filterPosts('1'); // Show POSTED posts by default
  });

  document.querySelectorAll('.clickable-image').forEach(img => {
    img.addEventListener('click', (event) => {
      event.stopPropagation();  // Prevent click bubbling
      const postId = img.dataset.postId;
      const index = parseInt(img.dataset.index, 10);
      showModal(postId, index);
    });
  });


