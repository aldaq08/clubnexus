
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('overlay');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const closeSidebar = document.getElementById('closeSidebar');

      function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        sidebar.setAttribute('aria-hidden', 'false');
        sidebar.focus();
      }

      function closeSidebarFunc() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        sidebar.setAttribute('aria-hidden', 'true');
        sidebarToggle.focus();
      }

      sidebarToggle.addEventListener('click', openSidebar);
      closeSidebar.addEventListener('click', closeSidebarFunc);
      overlay.addEventListener('click', closeSidebarFunc);

      // Trap focus inside sidebar when open
      sidebar.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
          const focusableElements = sidebar.querySelectorAll('a, button');
          const firstElement = focusableElements[0];
          const lastElement = focusableElements[focusableElements.length - 1];

          if (e.shiftKey) {
            if (document.activeElement === firstElement) {
              e.preventDefault();
              lastElement.focus();
            }
          } else {
            if (document.activeElement === lastElement) {
              e.preventDefault();
              firstElement.focus();
            }
          }
        }
        if (e.key === 'Escape') {
          closeSidebarFunc();
        }
      });
    });