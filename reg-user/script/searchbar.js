document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('searchInput');
  const resultsContainer = document.getElementById('searchResults');

  let debounceTimeout = null;

  searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimeout);
    const query = searchInput.value.trim();

    if (query.length === 0) {
      resultsContainer.innerHTML = '';
      return;
    }

    debounceTimeout = setTimeout(() => {
      fetch(`../reg-user/database/search-con.php?search=${encodeURIComponent(query)}`)
        .then(response => response.json())
          .then(data => {
            resultsContainer.innerHTML = '';

            if (data.length === 0) {
              resultsContainer.innerHTML = '<p>No organizations found.</p>';
              return;
            }
            
            const header = document.createElement('h2');
            header.textContent = 'Search Results';
            header.style.textAlign = 'center';
            header.style.color = '#0672a1';
            header.style.marginBottom = '20px';
            resultsContainer.appendChild(header);

            data.forEach(org => {
              const orgDiv = document.createElement('div');
              orgDiv.style.textAlign = 'center';
              orgDiv.style.cursor = 'pointer';
              orgDiv.style.marginBottom = '20px';

              orgDiv.innerHTML = `
                <a href="../forms/org-profile.php?id=${org.org_id}" style="text-decoration:none; color:inherit;">
                  <img src="src/org-logo/${org.org_logo || 'default-logo.png'}" alt="${org.org_name} Logo" style="width:100px; height:100px; object-fit:cover; border-radius:50%; border: 2px solid #0672a1;">
                  <div style="margin-top: 8px; font-weight: bold;">${org.org_name}</div>
                </a>
              `;

              resultsContainer.appendChild(orgDiv);
            });
          })

        .catch(err => {
          console.error('Search error: ', err);
          resultsContainer.innerHTML = '<p>Error loading results.</p>';
        });
    }, 300);
  });
});
