document.addEventListener('DOMContentLoaded', function() {
  // Logout Modal Functionality (Lines 3-50)
  const logoutButton = document.getElementById('confirmButton');
  const confirmationModal = document.getElementById('confirmationModal');
  const confirmYes = document.getElementById('confirmYes');
  const confirmNo = document.getElementById('confirmNo');

  if (logoutButton) {
    logoutButton.addEventListener('click', function(e) {
      e.preventDefault();
      if (confirmationModal) confirmationModal.style.display = 'flex';
    });
  }

  if (confirmYes) {
    confirmYes.addEventListener('click', function() {
      window.location.href = 'database/logout.php';
    });
  }

  if (confirmNo) {
    confirmNo.addEventListener('click', function() {
      if (confirmationModal) confirmationModal.style.display = 'none';
    });
  }

  // Close logout modal on outside click
  if (confirmationModal) {
    confirmationModal.addEventListener('click', function(e) {
      if (e.target === confirmationModal) {
        confirmationModal.style.display = 'none';
      }
    });
  }

  // Close logout modal on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && confirmationModal && confirmationModal.style.display === 'flex') {
      confirmationModal.style.display = 'none';
    }
  });

  // TAB SWITCHING FUNCTIONALITY (Lines 51-100) - Global function for onclick handlers
  window.openTab = function(evt, tabName) {
    var i, tabcontent, tablinks;

    // Hide all tab contents
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }

    // Remove "active" class from all tab links
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the selected tab content with appropriate display style (consistent for layouts)
    const tabElement = document.getElementById(tabName);
    if (tabElement) {
      // Use 'flex' for flex-based tabs (Organizations, Org-Applicants, Applicants, Achievements)
      // Use 'block' for simple content (Events, About Us)
      if (['Organizations', 'Org-Applicants', 'Applicants', 'Achievements'].includes(tabName)) {
        tabElement.style.display = "flex";
      } else {
        tabElement.style.display = "block";
      }
    }

    // Add "active" class to the clicked tab link
    evt.currentTarget.className += " active";
  };

  // [End of Batch 1 - Continue with Batch 2 for Announcement Filtering, etc.]
});
  // ANNOUNCEMENT FILTERING AND APPROVAL (Lines 101-200)
  const announcementStatusList = document.getElementById('announcementStatusList');
  const announcementStatusItems = announcementStatusList ? announcementStatusList.querySelectorAll('li') : [];
  const announcementStream = document.getElementById('announcementStream');
  const announcements = announcementStream ? announcementStream.querySelectorAll('.post-card') : [];

  let currentAnnouncementFilter = '1'; // Default: POSTED (1)

  function filterAnnouncements(status = currentAnnouncementFilter) {
    let anyVisible = false;
    announcements.forEach(announcement => {
      const approveStatus = announcement.dataset.isApprove;
      if (approveStatus === status) {
        announcement.classList.remove('hidden');
        anyVisible = true;
      } else {
        announcement.classList.add('hidden');
      }
    });

    // Hide all placeholders first
    ['noAnnouncementsPosted', 'noAnnouncementsPending', 'noAnnouncementsDenied'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.style.display = 'none';
    });

    // Show placeholder if no announcements visible for the selected status
    if (!anyVisible) {
      if (status === '1') {
        const el = document.getElementById('noAnnouncementsPosted');
        if (el) el.style.display = 'block';
      } else if (status === '3') {
        const el = document.getElementById('noAnnouncementsPending');
        if (el) el.style.display = 'block';
      } else if (status === '0') {
        const el = document.getElementById('noAnnouncementsDenied');
        if (el) el.style.display = 'block';
      }
    }

    currentAnnouncementFilter = status;
  }

  if (announcementStatusItems.length > 0) {
    announcementStatusItems.forEach(item => {
      item.addEventListener('click', () => {
        announcementStatusItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        const status = item.dataset.status;
        filterAnnouncements(status);
      });
    });
  }

  // ACHIEVEMENT FILTERING AND APPROVAL (Lines 201-300)
  const statusList = document.getElementById('statusList');
  const statusItems = statusList ? statusList.querySelectorAll('li') : [];
  const postStream = document.getElementById('postStream');
  const posts = postStream ? postStream.querySelectorAll('.post-card') : [];

  let currentPostFilter = '1'; // Default: POSTED (1)

  function filterPosts(status = currentPostFilter) {
    let anyVisible = false;
    posts.forEach(post => {
      const approveStatus = post.dataset.isApprove;
      if (approveStatus === status) {
        post.classList.remove('hidden');
        anyVisible = true;
      } else {
        post.classList.add('hidden');
      }
    });

    // Hide all placeholders first
    ['noPostsPosted', 'noPostsPending', 'noPostsDenied'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.style.display = 'none';
    });

    // Show placeholder if no posts visible for the selected status
    if (!anyVisible) {
      if (status === '1') {
        const el = document.getElementById('noPostsPosted');
        if (el) el.style.display = 'block';
      } else if (status === '3') {
        const el = document.getElementById('noPostsPending');
        if (el) el.style.display = 'block';
      } else if (status === '0') {
        const el = document.getElementById('noPostsDenied');
        if (el) el.style.display = 'block';
      }
    }

    currentPostFilter = status;
  }

  if (statusItems.length > 0) {
    statusItems.forEach(item => {
      item.addEventListener('click', () => {
        statusItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        const status = item.dataset.status;
        filterPosts(status);
      });
    });
  }

  // Initialize filtering for announcements and achievements (default to '1' - POSTED/APPROVED) (Lines 301-305)
  filterAnnouncements('1');
  filterPosts('1');

  // Shared Update Function for Announcements/Achievements Approval (Lines 306-370)
  function updateApproval(postId, newStatus, isAnnouncement) {
    const url = isAnnouncement ? 'database/announcement-con.php' : 'database/post-con.php';
    const data = isAnnouncement
      ? { announcement_id: parseInt(postId), announcement_approve: newStatus }
      : { achievement_id: parseInt(postId), achievement_approve: newStatus };

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => {
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      return response.json();
    })
    .then(data => {
      if (data.success) {
        const containerId = isAnnouncement ? 'announcementStream' : 'postStream';
        const post = document.querySelector(`#${containerId} .post-card[data-post-id="${postId}"]`);
        if (!post) return;
        post.dataset.isApprove = newStatus.toString();

        const label = post.querySelector('.status-label');
        if (label) {
          if (newStatus === 1) {
            label.textContent = 'POSTED';
            label.className = 'status-label status-posted';
          } else if (newStatus === 0) {
            label.textContent = 'DENIED';
            label.className = 'status-label status-denied';
          } else if (newStatus === 3) {
            label.textContent = 'PENDING';
            label.className = 'status-label status-pending';
          }
        }

        const actions = post.querySelector('.post-actions');
        if (actions) actions.remove();

        const activeStatusListId = isAnnouncement ? 'announcementStatusList' : 'statusList';
        const activeStatus = document.querySelector(`#${activeStatusListId} li.active`).dataset.status;
        if (activeStatus !== newStatus.toString()) {
          post.classList.add('hidden');
        }
        alert((isAnnouncement ? 'Announcement' : 'Achievement') + ' status updated successfully.');
      } else {
        alert('Failed to update status: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      alert('Request failed: ' + error.message);
    });
  }

  // Event Delegation for Approve/Disapprove Buttons (Announcements/Achievements ONLY - FIXED CONFLICT) (Lines 371-400)
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('approve-btn') || e.target.classList.contains('disapprove-btn')) {
      // FIXED: Early exit if not in announcement/achievement containers (ignores applicant buttons)
      if (!e.target.closest('#announcementStream') && !e.target.closest('#postStream')) {
        return; // Silently ignore - let applicant handler process
      }

      const postId = e.target.dataset.postId;
      if (!postId) {
        alert('Post ID not found.');
        return;
      }
      const newStatus = e.target.classList.contains('approve-btn') ? 1 : 0;

      let isAnnouncement = false;
      if (e.target.closest('#announcementStream')) {
        isAnnouncement = true;
      } else if (e.target.closest('#postStream')) {
        isAnnouncement = false;
      } else {
        alert('Post container not found.');
        return;
      }

      if (confirm(`Are you sure you want to ${newStatus === 1 ? 'approve' : 'disapprove'} this ${isAnnouncement ? 'announcement' : 'achievement'}?`)) {
        updateApproval(postId, newStatus, isAnnouncement);
      }
    }
  });

  // [End of Batch 2 - Continue with Batch 3 for Image Modal (IIFE) ~Lines 401-550, and Applicant Handling ~Lines 551-750]
  // Image Modal for Posts/Announcements (IIFE - Unchanged) (Lines 401-550)
  (function(){
    const modal = document.getElementById('imageModal2');
    const modalImage = document.getElementById('modalImage');
    const closeBtn = modal ? modal.querySelector('.close') : null;
    const prevBtn = modal ? modal.querySelector('.prev') : null;
    const nextBtn = modal ? modal.querySelector('.next') : null;
    const modalCaption = document.getElementById('modalCaption');

    let currentImages = [];
    let currentIndex = 0;
    let currentBasePath = '';

    function normalizeUrl(url) {
      try {
        const u = new URL(url, window.location.origin);
        return u.href;
      } catch {
        return url;
      }
    }

    function showImage() {
      if (modalImage && currentImages.length > 0) {
        modalImage.src = currentBasePath + currentImages[currentIndex];
        modalImage.alt = `Image ${currentIndex + 1}`;
        if (modalCaption) modalCaption.textContent = `Image ${currentIndex + 1} of ${currentImages.length}`;

        if (prevBtn) prevBtn.style.display = currentIndex === 0 ? 'none' : 'flex';
        if (nextBtn) nextBtn.style.display = currentIndex === currentImages.length - 1 ? 'none' : 'flex';
      }
    }

    function openModal(container, event) {
      const allImagesData = container.getAttribute('data-all-images');
      if (!allImagesData) return;

      try {
        currentImages = JSON.parse(allImagesData);
      } catch (e) {
        currentImages = Array.from(container.querySelectorAll('.post-image')).map(img => img.getAttribute('data-fullsrc') || img.src);
      }

      // Determine base path based on container
      if (container.closest('#postStream')) {
        currentBasePath = "../admin/form/src/achievement/";
      } else if (container.closest('#announcementStream')) {
        currentBasePath = "../admin/form/src/announcement/";
      } else {
        currentBasePath = "";
      }

      let clickedSrc = null;
      if (event && event.target) {
        const target = event.target;
        if (target.tagName === 'IMG' && container.contains(target)) {
          clickedSrc = target.getAttribute('data-fullsrc') || target.src;
        }
      }

      const normalizedClickedSrc = clickedSrc ? normalizeUrl(clickedSrc) : null;
      const normalizedImages = currentImages.map(normalizeUrl);

      currentIndex = normalizedClickedSrc ? normalizedImages.indexOf(normalizedClickedSrc) : 0;
      if (currentIndex === -1) currentIndex = 0;

      showImage();
      if (modal) {
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
      }
    }

    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        if (modal) {
          modal.style.display = 'none';
          modal.setAttribute('aria-hidden', 'true');
        }
      });
    }

    window.addEventListener('click', (e) => {
      if (modal && e.target === modal) {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
      }
    });

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        if (currentIndex < currentImages.length - 1) {
          currentIndex++;
          showImage();
        }
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
          currentIndex--;
          showImage();
        }
      });
    }

    document.addEventListener('keydown', (e) => {
      if (modal && modal.style.display === 'flex') {
        if (e.key === 'ArrowRight') {
          if (nextBtn) nextBtn.click();
        } else if (e.key === 'ArrowLeft') {
          if (prevBtn) prevBtn.click();
        } else if (e.key === 'Escape') {
          if (closeBtn) closeBtn.click();
        }
      }
    });

    // Delegate click on images to open modal
    document.addEventListener('click', (e) => {
      let target = e.target;
      while (target && target !== document) {
        if (target.classList && target.classList.contains('clickable-image')) {
          const container = target.closest('.post-images-container');
          if (container) {
            openModal(container, e);
          }
          break;
        }
        target = target.parentNode;
      }
    });
  })();
  // APPLICANT FILTERING, MODALS, AND BUTTON HANDLERS (Lines 551-750)
  const applicantStatusList = document.getElementById('applicantStatusList');
  const applicantStatusItems = applicantStatusList ? applicantStatusList.querySelectorAll('li') : [];
  const applicantStream = document.getElementById('applicantStream');
  const applicants = applicantStream ? applicantStream.querySelectorAll('.applicant-card') : [];

  // Applicant Modal Elements
  const applicantModal = document.getElementById('applicantModal');
  const applicantModalBody = document.getElementById('applicantModalBody');
  const applicantClose = document.querySelector('.applicant-close');

  // Current Filter State
  let currentApplicantFilter = '1'; // Default: APPROVED (1)

  // Ensure modal is hidden on page load (Lines 551-570)
  if (applicantModal) {
    applicantModal.style.display = 'none';
    applicantModal.setAttribute('aria-hidden', 'true');
  }

  // Function to add org status actions (buttons) dynamically for approved cards in active/inactive tabs (Lines 571-610)
  function addOrgStatusActions(applicant) {
    const orgId = applicant.dataset.orgId;
    const isActive = applicant.dataset.isActive;
    const footer = applicant.querySelector('.applicant-footer');
    if (!footer || applicant.querySelector('.org-status-actions')) return; // Already added

    // Only add for approved organizations
    if (applicant.dataset.isApproved !== '1') return;

    const orgActions = document.createElement('div');
    orgActions.className = 'org-status-actions';
    const buttonText = parseInt(isActive) === 1 ? 'Deactivate' : 'Activate';
    const buttonClass = parseInt(isActive) === 1 ? 'deactivate-btn' : 'activate-btn';
    const button = document.createElement('button');
    button.className = buttonClass;
    button.dataset.orgId = orgId;
    button.textContent = buttonText;
    orgActions.appendChild(button);

    // Insert after applicant-actions or at the end of footer (after active-status-label if present)
    const applicantActions = footer.querySelector('.applicant-actions');
    if (applicantActions) {
      applicantActions.insertAdjacentElement('afterend', orgActions);
    } else {
      // Insert after active-status-label or at the end
      const activeLabel = footer.querySelector('.active-status-label');
      if (activeLabel) {
        activeLabel.insertAdjacentElement('afterend', orgActions);
      } else {
        footer.appendChild(orgActions);
      }
    }
  }

  // Function to remove org status actions (for switching away from active/inactive tabs) (Lines 611-615)
  function removeOrgStatusActions(applicant) {
    const orgActions = applicant.querySelector('.org-status-actions');
    if (orgActions) {
      orgActions.remove();
    }
  }

  // Function to update or create active status label (Lines 616-640)
  function updateActiveStatusLabel(applicant, isActive) {
    const labelText = parseInt(isActive) === 1 ? 'ACTIVE' : 'INACTIVE';
    const labelClass = parseInt(isActive) === 1 ? 'status-active' : 'status-inactive';
    let activeLabel = applicant.querySelector('.active-status-label');

    if (activeLabel) {
      // Update existing label
      activeLabel.textContent = labelText;
      activeLabel.className = `active-status-label ${labelClass}`;
    } else {
      // Create new label (e.g., after approval)
      activeLabel = document.createElement('span');
      activeLabel.className = `active-status-label ${labelClass}`;
      activeLabel.textContent = labelText;
      const footer = applicant.querySelector('.applicant-footer');
      if (footer) {
        // Insert after status-label
        const statusLabel = footer.querySelector('.status-label');
        if (statusLabel) {
          statusLabel.insertAdjacentElement('afterend', activeLabel);
        } else {
          footer.appendChild(activeLabel);
        }
      }
    }
  }

  // Function to remove active status label (e.g., when no longer approved) (Lines 641-645)
  function removeActiveStatusLabel(applicant) {
    const activeLabel = applicant.querySelector('.active-status-label');
    if (activeLabel) {
      activeLabel.remove();
    }
  }

  // Filtering Function (Extended for Applicant Status and Active/Inactive) (Lines 646-650 - Start)
  function filterApplicants(applicantStatus = currentApplicantFilter) {
    let anyVisible = false;
    applicants.forEach(applicant => {
      let shouldShow = false;

      // Handle Applicant Status Filtering (numeric: 0,1,2)
      if (['0', '1', '2'].includes(applicantStatus)) {
        const approved = applicant.dataset.isApproved;
        if (approved === applicantStatus) {
          shouldShow = true;
        }
      }
      // Handle Active/Inactive Filtering (strings: 'active', 'inactive') - Only for approved orgs (is_approved=1)
      else if (applicantStatus === 'active' || applicantStatus === 'inactive') {
        const approved = applicant.dataset.isApproved;
        const isActive = applicant.dataset.isActive;
        const targetActive = applicantStatus === 'active' ? '1' : '0';
        if (approved === '1' && isActive === targetActive) {
          shouldShow = true;
        }
      }

      // [End of Lines 551-650 - Continue with rest of filterApplicants function in next batch]
      if (shouldShow) {
        applicant.classList.remove('hidden');
        anyVisible = true;

        // Ensure active label is present/updated for approved cards (always visible when approved)
        if (applicant.dataset.isApproved === '1') {
          updateActiveStatusLabel(applicant, applicant.dataset.isActive);
        } else {
          removeActiveStatusLabel(applicant);
        }

        // Dynamically manage buttons: Add in active/inactive tabs, remove in others
        if (applicantStatus === 'active' || applicantStatus === 'inactive') {
          addOrgStatusActions(applicant);
        } else {
          removeOrgStatusActions(applicant);
        }
      } else {
        applicant.classList.add('hidden');
        // Remove buttons when hidden (cleanup)
        removeOrgStatusActions(applicant);
      }
    });

    // Hide All Placeholders (Lines 651-680)
    ['noApplicantsApproved', 'noApplicantsApplying', 'noApplicantsDenied', 'noActiveOrgs', 'noInactiveOrgs'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.style.display = 'none';
    });

    // Show Appropriate Placeholder if No Matches
    if (!anyVisible) {
      if (applicantStatus === '1') {
        document.getElementById('noApplicantsApproved').style.display = 'block';
      } else if (applicantStatus === '2') {
        document.getElementById('noApplicantsApplying').style.display = 'block';
      } else if (applicantStatus === '0') {
        document.getElementById('noApplicantsDenied').style.display = 'block';
      } else if (applicantStatus === 'active') {
        document.getElementById('noActiveOrgs').style.display = 'block';
      } else if (applicantStatus === 'inactive') {
        document.getElementById('noInactiveOrgs').style.display = 'block';
      }
    }

    // Cleanup: Remove buttons from all cards when not in active/inactive tabs
    if (!['active', 'inactive'].includes(applicantStatus)) {
      applicants.forEach(removeOrgStatusActions);
    }

    currentApplicantFilter = applicantStatus;
  }

  // Applicant Status Filtering Event Listeners (Now Handles Active/Inactive Tabs) (Lines 681-690)
  if (applicantStatusItems.length > 0) {
    applicantStatusItems.forEach(item => {
      item.addEventListener('click', () => {
        applicantStatusItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        const status = item.dataset.status;
        filterApplicants(status);
      });
    });
  }

  // Initialize Applicant Filtering (Default to APPROVED) (Lines 691-692)
  filterApplicants('1');

  // Update Applicant Approval Status (Approve/Deny) (Lines 693-730)
  function updateApplicantApproval(orgId, newStatus) {
    const url = 'database/org-applicant-con.php';
    const data = { org_id: parseInt(orgId), is_approved: newStatus };

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => {
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      return response.json();
    })
    .then(data => {
      if (data.success) {
        const applicant = document.querySelector(`#applicantStream .applicant-card[data-org-id="${orgId}"]`);
        if (!applicant) return;
        applicant.dataset.isApproved = newStatus.toString();

        // Update Status Label
        const statusLabel = applicant.querySelector('.status-label');
        if (statusLabel) {
          if (newStatus === 1) {
            statusLabel.textContent = 'APPROVED';
            statusLabel.className = 'status-label status-approved';
          } else if (newStatus === 0) {
            statusLabel.textContent = 'DENIED';
            statusLabel.className = 'status-label status-denied';
          } else if (newStatus === 2) {
            statusLabel.textContent = 'APPLYING';
            statusLabel.className = 'status-label status-applying';
          }
        }

        // Remove Old Actions
        const oldActions = applicant.querySelector('.applicant-actions');
        if (oldActions) oldActions.remove();

        // Handle Active Label Based on New Status
        if (newStatus === 1) {
          updateActiveStatusLabel(applicant, applicant.dataset.isActive);
        } else {
          removeActiveStatusLabel(applicant);
        }

        removeOrgStatusActions(applicant);

        // Refresh Filter
        filterApplicants(currentApplicantFilter);
        alert('Applicant status updated successfully.');
      } else {
        alert('Failed to update status: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      alert('Request failed: ' + error.message);
    });
  }

  // Update Organization Active Status (Activate/Deactivate) (Lines 731-750 - Start)
  function updateOrgActiveStatus(orgId, newActiveStatus) {
    const url = 'database/org-applicant-con.php';
    const data = { 
      org_id: parseInt(orgId), 
      is_active: newActiveStatus, 
      action: 'update_active' 
    };

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => {
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      return response.json();
    })
    .then(data => {
      if (data.success) {
        const applicant = document.querySelector(`#applicantStream .applicant-card[data-org-id="${orgId}"]`);
        if (!applicant) return;
        applicant.dataset.isActive = newActiveStatus.toString();

        // Update Active Status Label
        updateActiveStatusLabel(applicant, newActiveStatus);

        // Remove Old Org Status Actions and Add New Button
        const oldOrgActions = applicant.querySelector('.org-status-actions');
        if (oldOrgActions) oldOrgActions.remove();

        const newOrgActions = document.createElement('div');
        newOrgActions.className = 'org-status-actions';
        const buttonText = newActiveStatus === 1 ? 'Deactivate' : 'Activate';
        const buttonClass = newActiveStatus === 1 ? 'deactivate-btn' : 'activate-btn';
        const newButton = document.createElement('button');
        newButton.className = buttonClass;
        newButton.dataset.orgId = orgId;
        newButton.textContent = buttonText;
        newOrgActions.appendChild(newButton);

        // Insert after applicant-actions or active-status-label
        const applicantActions = applicant.querySelector('.applicant-actions');
        if (applicantActions) {
          applicantActions.insertAdjacentElement('afterend', newOrgActions);
        } else {
          const activeLabel = applicant.querySelector('.active-status-label');
          if (activeLabel) {
            activeLabel.insertAdjacentElement('afterend', newOrgActions);
          } else {
            const footer = applicant.querySelector('.applicant-footer');
            if (footer) footer.appendChild(newOrgActions);
          }
        }

        // Refresh Filter
        filterApplicants(currentApplicantFilter);
        const labelText = newActiveStatus === 1 ? 'ACTIVE' : 'INACTIVE';
        alert(`Organization ${labelText.toLowerCase()}d successfully.`);
      } else {
        alert('Failed to update active status: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      alert('Request failed: ' + error.message);
    });
  }

  // [End of Lines 651-750 - Continue with modal functions and event delegation in full script if needed; this completes the core batch]

