<?php
// Fetch all organizations for sidebar
require_once ("database/post-con.php");
require ("database/user-con.php");
?>
<!DOCTYPE html>
<head>
    <title>Achievements | <?php echo $username; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="styles/achievement.css" />
    <link rel="stylesheet" type="text/css" href="styles/search.css" />
    <link rel="stylesheet" type="text/css" href="styles/notification.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css" />
    <link rel="stylesheet" type="text/css" href="styles/main-content.css" />
<style>
      html, body {
        font-family: "Outfit", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500; /* Changed to a valid weight */
        font-style: normal;
        background-color: rgba(235, 233, 233, 0.87);
        height: 100%;
        width: 100%;
        margin: 0;
        overflow-y:auto;
        overflow-x: hidden;
      }
    .stream {
      max-width: 70%;
      margin: 20px auto;
      border-radius: 8px;
      overflow-y: auto; /* Scroll inside stream */
      height: calc(100vh - 220px); /* Adjust height to fit viewport minus header */
      margin-right: 40px;
      margin-right:40px;

    }
    .stream {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
      }
      .stream::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
      }
    .post-card {
      background-color: white;
      margin-bottom: 30px;
      border-radius: 8px;
      border-color: 1px solid gray;
      transition: box-shadow 0.3s ease;
      outline: 3px solid rgba(134, 132, 132, 0.1);
    }
    .post-card:hover {
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .profile-pic {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 12px;
      flex-shrink: 0;
      object-fit: cover;
    }
    .post-header {
      padding: 16px;
      display: flex;
      align-items: center;
    }
    .post-header h3 {
      margin: 0;
      font-size: 1rem;
      font-weight: 600;
    }
    .post-time {
      color: #65676b;
      font-size: 0.875rem;
    }
    .post-body {
      padding: 16px;
    }
    .post-images-container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 4px;
      padding: 16px;
    }
    .post-image {
      max-width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 0 0 px 0px;
      cursor: pointer;
      display: block;
      margin: 0 auto;
    }
    .image-overlay {
      position: relative;
      cursor: pointer;
    }
    .overlay-text {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.6);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1.2rem;
      font-weight: 500;
      border-radius: 6px;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(74, 74, 74, 0.9);
      overflow: auto;
    }
    .modal-content {
      position: relative;
      max-width: 800px;
      margin: 70px auto;
      text-align: center;
    }

    .modal-image {
      max-width: 100%;
      max-height: 80vh;
      object-fit: contain;
      display: block;
      margin: 0 auto;
      border-radius: 8px;
    }
    .close {
      position: absolute;
      top: -10px;
      right: -10px;
      color: white;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      background: rgba(0,0,0,0.5);
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .close:hover {
      background: rgba(0,0,0,0.8);
    }
    .prev, .next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      font-size: 2rem;
      color: white;
      background: rgba(251, 251, 251, 0.5);
      border: none;
      padding: 10px;
      cursor: pointer;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .prev { left: 10px; }
    .next { right: 10px; }
    .prev:hover, .next:hover {
      background: rgba(173, 172, 172, 0.8);
      color: black;
    }
    .image-counter {
      margin-top: 10px;
      color: white;
      font-size: 0.9rem;
    }

    /* Sidebar container and layout */
      .container-flex {
        display: flex;
        width: 1200px;
        margin: 20px auto;
        gap: 20px;      
        box-sizing: border-box;

      }

      /* Sidebar styles */
      .org-sidebar {
        position: fixed;
        top: 190px; /* same as your margin-top for stream, adjust if needed */
        left: 20px; /* adjust horizontal position as needed */
        width: 300px;
        height: 520px;
       /* height: calc(100vh - 100px); /* full viewport height minus top offset */
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        overflow-y: auto;
        z-index: 0; /* ensure it stays above other content */
        margin: 0; /* remove margin if any */
      }


      .org-sidebar h2 {
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 1.25rem;
        text-align:center;
      }

      .org-sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
      }

      .org-sidebar li {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 6px;
        margin-bottom: 6px;
        transition: background-color 0.2s ease;
      }

      .org-sidebar li:hover,
      .org-sidebar li.active {
        background-color: #e4e6eb;
      }

      .org-sidebar img.org-logo {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
      }
      
      .sidebar {
            width: 7%;
            max-height: 50px;
            margin-top: 0;
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 2.5%;
            border-color: none;
      }
      
</style>
    <!----FONTS--->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
<link rel="icon" type="image/c-icon" href="src/clubnexusicon.ico">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <!---END FONTS-->

    <!--SCRIPTS-->
    <!--<script src="https://cdn.tailwindcss.com"></script>-->
    <script src="script/sidebar.js"></script>
    <!--<script src="script/event-calendar.js"></script>-->

    <!--END SCRIPTS-->
      
</head>

<body>
<div class="header">
    <!--------HEADER 1----------->
        <div class="head-content">
            <div class="logo"> <!--Logo-->
                <img src="src/web_logo.png">  
            </div>

            <div class="nav-panel"> <!--Navigation Panel-->
                <nav>
                    <ul>
                        <li>
                            <a href="home.php">Home</a> 
                        </li>

                        <li>
                            <a href="achievement.php"><b>Achievements</b></a>
                        </li>

                        <li>
                            <a href="announcement.php">Announcements</a>
                        </li>

                        <li>
                            <a href="about_us.php">About Us</a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    <!--------END HEADER 1------->

<!------------------------------------- HEADER 2-------------------------------->
        <div class="head-content2">
                        <div class="content-1" style="height: 80px;"> <!---Sidebar Button-->
                        <div class="sidebar">
                            <button id="sidebarToggle" aria-label="Open sidebar" title="Open sidebar" type="button">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bars" class="svg-inline--fa fa-bars fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width:1.80rem; height:1.80rem; color:white;">
                                <path fill="currentColor" d="M16 132h416c8.837 0 16-7.163 16-16v-16c0-8.837-7.163-16-16-16H16C7.163 84 0 91.163 0 100v16c0 8.837 7.163 16 16 16zm416 96H16c-8.837 0-16 7.163-16 16v16c0 8.837 7.163 16 16 16h416c8.837 0 16-7.163 16-16v-16c0-8.837-7.163-16-16-16zm0 160H16c-8.837 0-16 7.163-16 16v16c0 8.837 7.163 16 16 16h416c8.837 0 16-7.163 16-16v-16c0-8.837-7.163-16-16-16z"></path>
                                </svg>
                            </button>
                        </div>

                        <div id="overlay"></div>

                        <!-- Sidebar Popup -->
        <aside id="sidebar" aria-label="Sidebar navigation" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1">
            <div class="close-button-container">
                <button id="closeSidebar" aria-label="Close sidebar" title="Close sidebar" type="button">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times-circle" class="svg-inline--fa fa-times-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width:1.875rem; height:1.875rem; color:#006494;">
                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.6c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65 65c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65-65-65-65c-4.7-4.7-4.7-12.3 0-17L174 134.4c4.7-4.7 12.3-4.7 17 0l65 65 65-65c4.7-4.7 12.3-4.7 17 0l26.6 26.6c4.7 4.7 4.7 12.3 0 17l-65 65 65 65z"></path>
                    </svg>
                </button>
            </div>


            <!-- Profile container fixed at bottom right -->
            <div class="profile-container" role="region" aria-label="User profile">
                <!-- Photo and name stacked -->
                <div class="profile-icon-wrapper" aria-hidden="true">
                    <img src="src/userprofile/<?php echo htmlspecialchars($user_photo); ?>" alt="User  photo" style="width:5rem; height:5rem; border-radius:50%;">
                </div>
                <h2 class="profile-name"><?php echo $username; ?></h2>

                <!-- User profile link icon -->
                <div class="profile-icons-row" aria-hidden="true" style="justify-content:flex-end;">
                    <a href="forms/userprofile.php" title="User Profile">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>

                <!-- Navigation links outside the yellow profile -->
            <nav aria-label="Main navigation" class="nav-sidebar">
                <a href="forms/dashboard.php" class="dashboard" role="link" tabindex="0">
                    <div class="icon-wrapper">
                        <div class="icon-bg" aria-hidden="true">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke-width="2" d="M5 3a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5Zm14 18a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4ZM5 11a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H5Zm14 2a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4Z"/>
                            </svg>
                        </div>
                    </div>
                    <span class="side-label">Dashboard</span>
                </a>
                <a href="login_form.php" class="dashboard" role="link" tabindex="0">
                    <div class="icon-wrapper">
                        <div class="icon-bg" aria-hidden="true">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                            </svg>
                        </div>
                    </div>
                    <span class="side-label">Log out</span>
                </a>
            </nav>
        </aside>

                            <div class="categories">
                                <div class="post-label">
                                    <b>Achievements</b>
                                </div>

                            </div> 
                        </div> 
                        
                        <div class="content-2">

                            <div class="cont">
                                <form class="search-bar" action="">
                                        <input class="search-input" required="" name="search" type="search" autocomplete="off">
                                        <button type="submit" class="search-btn">
                                            <span>Search</span>
                                        </button>
                                </form>
                            </div>

                            <div class="cont">
                                <button id="openCalendarBtn" class="calendar"><!---EVENT CALENDAR-----> 
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" height="24" fill="none" class="svg-icon"><g stroke-width="2" stroke-linecap="round" stroke="#0075A3"><rect y="5" x="4" width="16" rx="2" height="16"></rect><path d="m8 3v4"></path><path d="m16 3v4"></path><path d="m4 11h16"></path></g></svg>
                                </button> 
                            </div>


                            <!--NOTIFICATION BUTTON-->
                            <div class="cont">
                                <button class="button">
                                        <svg class="bell" viewBox="0 0 448 512">
                                            <path
                                            d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"
                                            ></path>
                                        </svg>
                                        <div class="arrow">›</div>
                                        <div class="dot"></div>
                                </button>
                            </div>
                            <!-----END NOTOFICATION BUTTON-->


                        </div>

               </div>      

        </div>
</div>


<div class="container-flex" style="margin-top:10px;">
    <!-- Sidebar -->
    <aside class="org-sidebar" aria-label="Organizations sidebar">
      <h2>Organizations</h2>
      <ul id="orgList" role="listbox" aria-label="Organizations list">
        <li data-org-id="all" class="active" role="option" tabindex="0">All Organizations</li>
        <?php foreach ($organizations as $org): ?>
          <li data-org-id="<?php echo htmlspecialchars($org['org_id']); ?>" role="option" tabindex="0">
            <?php if (!empty($org['org_logo'])): ?>
              <img src="src/org-logo/<?php echo htmlspecialchars($org['org_logo']); ?>" alt="<?php echo htmlspecialchars($org['org_name']); ?> logo" class="org-logo" onerror="this.style.display='none';" />
            <?php endif; ?>
            <span><?php echo htmlspecialchars($org['org_name']); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </aside>


    <!-- Posts Stream -->
<div class="stream" id="postStream" style="flex: 1;">
  <?php
  if (empty($posts)) {
      // Display image when no posts found
      echo '<div style="text-align:center; margin-top:40px;">
              <img src="src/nopost.png" alt="No posts found" style="max-width:300px; width:100%; height:auto;" />
              <p>No achievement found!</p>
            </div>';
  } else {
      // Helper function inside PHP block (if not already defined)
      function getImagesArray($imagesField) {
        if (empty($imagesField)) return [];
        $images = json_decode($imagesField, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($images)) {
          return $images;
        }
        $urls = array_filter(array_map('trim', explode(',', $imagesField)));
        if (count($urls) === 1 && !empty($urls[0])) {
          return [$urls[0]];
        }
        return $urls;
      }

      foreach ($posts as $post):
        $images = getImagesArray($post['achievement_files'] ?? '');
        $org_logo = htmlspecialchars($post['org_logo'] ?? '');
        $org_name = htmlspecialchars($post['org_name'] ?? '');
        $created_at_raw = $post['created_at'] ?? '';
        $org_id = htmlspecialchars($post['org_id'] ?? '');

        $formatted_time = '';
        if (!empty($created_at_raw)) {
          try {
            $dateTime = new DateTime($created_at_raw);
            $formatted_time = $dateTime->format('M d, Y \a\t h:i A');
          } catch (Exception $e) {
            $formatted_time = 'Invalid Date';
          }
        }

        $description = nl2br(htmlspecialchars($post['achievement_description'] ?? ''));
  ?>
  
  <div class="post-card" data-org-id="<?php echo $org_id; ?>">
      <div class="post-header">
        <?php if ($org_logo): ?>
          <img class="profile-pic" src="src/org-logo/<?php echo $org_logo; ?>" alt="<?php echo $org_name; ?> logo" onerror="this.style.display='none';" />
        <?php endif; ?>
        <div>
          <h3><?php echo $org_name; ?></h3>
          <span class="post-time"><?php echo $formatted_time; ?></span>
        </div>
      </div>
      <div class="post-body">
        <p><?php echo $description; ?></p>
      </div>
      <div class="post-images-container" data-post-id="<?php echo htmlspecialchars($post['achievement_id']); ?>"
           data-all-images='<?php echo json_encode($images, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'
           <?php if (empty($images)) echo 'style="display:none;"'; ?>>
        <?php
        if (!empty($images)):
          $count = count($images);
          $showCount = min(3, $count);
          for ($i = 0; $i < $showCount; $i++):
        ?>
          <img src="../admin/form/src/achievement/<?php echo htmlspecialchars($images[$i]); ?>" alt="<?php echo $description ? strip_tags($description) : 'Post image'; ?>" class="post-image" />
        <?php endfor; ?>

        <?php if ($count > 3): ?>
          <div class="image-overlay">
            <img src="../admin/form/src/achievement/<?php echo htmlspecialchars($images[3]); ?>" alt="<?php echo $description ? strip_tags($description) : 'Post image'; ?>" class="post-image" />
            <div class="overlay-text">+<?php echo $count - 3; ?> more</div>
          </div>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  <?php
      endforeach;
  }
  ?>
</div>
<div>




  <!-- Modal for viewing images individually -->
  <div id="imageModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <button class="prev">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
        </svg></button>
      <button class="next">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
        </svg></button>
      <img id="modalImage" src="" alt="" class="modal-image" />
      <div class="image-counter" id="imageCounter"></div>
    </div>
  </div>

<script>
  const modal = document.getElementById('imageModal');
  const modalImage = document.getElementById('modalImage');
  const closeBtn = document.querySelector('.close');
  const prevBtn = document.querySelector('.prev');
  const nextBtn = document.querySelector('.next');
  const imageCounter = document.getElementById('imageCounter');

  let currentImages = [];
  let currentIndex = 0;
  let isAchievement = false; // track if current images are achievements

  function openModal(container, event) {
    const allImagesData = container.getAttribute('data-all-images');
    if (!allImagesData) return;

    try {
      currentImages = JSON.parse(allImagesData);
    } catch (e) {
      currentImages = Array.from(container.querySelectorAll('.post-image')).map(img => img.src);
    }

    // Determine if this is achievement container (#postStream)
    isAchievement = container.closest('#postStream') !== null;

    let clickedSrc = null;
    if (event && event.target) {
      const target = event.target;
      if (target.tagName === 'IMG' && container.contains(target)) {
        clickedSrc = target.getAttribute('data-fullsrc') || target.src;
      }
    }

    // Normalize URLs for comparison (strip query params, trailing slashes, etc.)
    function normalizeUrl(url) {
      try {
        const u = new URL(url, window.location.origin);
        return u.origin + u.pathname.replace(/\/$/, '');
      } catch {
        return url;
      }
    }

    // For achievements, normalize images with base path; else keep as is
    const normalizedImages = isAchievement
      ? currentImages.map(img => normalizeUrl("../admin/form/src/achievement/" + img))
      : currentImages.map(normalizeUrl);

    const normalizedClickedSrc = clickedSrc ? normalizeUrl(clickedSrc) : null;

    currentIndex = normalizedClickedSrc ? normalizedImages.indexOf(normalizeUrl(clickedSrc)) : 0;
    if (currentIndex === -1) currentIndex = 0;

    showImage();
    modal.style.display = 'block';
  }

  function showImage() {
    if (isAchievement) {
      modalImage.src = "../admin/form/src/achievement/" + currentImages[currentIndex];
    } else {
      // For non-achievement (e.g. announcements), use full URL or data as is
      modalImage.src = currentImages[currentIndex];
    }
    modalImage.alt = `Image ${currentIndex + 1}`;
    imageCounter.textContent = `${currentIndex + 1} / ${currentImages.length}`;

    prevBtn.style.display = currentIndex === 0 ? 'none' : 'flex';
    nextBtn.style.display = currentIndex === currentImages.length - 1 ? 'none' : 'flex';
  }

  closeBtn.addEventListener('click', function () {
    modal.style.display = 'none';
  });

  window.addEventListener('click', function (e) {
    if (e.target == modal) {
      modal.style.display = 'none';
    }
  });

  nextBtn.addEventListener('click', function () {
    if (currentIndex < currentImages.length - 1) {
      currentIndex++;
      showImage();
    }
  });

  prevBtn.addEventListener('click', function () {
    if (currentIndex > 0) {
      currentIndex--;
      showImage();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (modal.style.display === 'block') {
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
  document.addEventListener('click', function (e) {
    if (
      e.target.classList.contains('overlay-text') ||
      (e.target.classList.contains('post-image') && e.target.closest('.post-images-container'))
    ) {
      const container = e.target.closest('.post-images-container');
      openModal(container, e);
    }
  });

  // Smooth scrolling for post cards (excluding modal elements)
  document.addEventListener('click', function(event) {
    const postCards = document.querySelectorAll('.post-card');
    postCards.forEach(post => {
      if (
        !event.target.closest('#imageModal') &&
        !event.target.classList.contains('close') &&
        !event.target.classList.contains('overlay-text') &&
        !event.target.classList.contains('prev') &&
        !event.target.classList.contains('next') &&
        !event.target.classList.contains('post-image') &&
        post.contains(event.target)
      ) {
        // Removed scrollIntoView to prevent unintended scroll behavior
      }
    });
  });

  const orgList = document.getElementById('orgList');
  const postStream = document.getElementById('postStream');

  orgList.addEventListener('click', (e) => {
    const li = e.target.closest('li');
    if (!li) return;

    filterPostsByOrg(li.dataset.orgId);
    setActiveOrg(li);
  });

  orgList.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      const li = e.target.closest('li');
      if (!li) return;
      e.preventDefault();
      filterPostsByOrg(li.dataset.orgId);
      setActiveOrg(li);
    }
  });

  function filterPostsByOrg(orgId) {
    const posts = postStream.querySelectorAll('.post-card');
    if (orgId === 'all') {
      posts.forEach(post => post.style.display = '');
    } else {
      posts.forEach(post => {
        if (post.dataset.orgId === orgId) {
          post.style.display = '';
        } else {
          post.style.display = 'none';
        }
      });
    }
  }

  function setActiveOrg(selectedLi) {
    const items = orgList.querySelectorAll('li');
    items.forEach(item => {
      item.classList.remove('active');
      item.setAttribute('aria-selected', 'false');
    });
    selectedLi.classList.add('active');
    selectedLi.setAttribute('aria-selected', 'true');
  }

  // Initialize with "All Organizations" active
  setActiveOrg(orgList.querySelector('li[data-org-id="all"]'));
</script>


</body>
</html>