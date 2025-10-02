<?php
include("database/org-admin.php");
?>
<!DOCTYPE html>
<head>
    <title>Admin Homepage | <?php echo $org_name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/admin.css" />


    <!----FONTS--->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        <link rel="icon" type="image/c-icon" href="src/clubnexusicon.ico">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <!---END FONTS-->
<style>
    .main-content {
    width: 100%;
    margin-top: 0;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1;
    }

    .main-content .tabcontent {
    display: none;
    }

    #Organizations {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 16px;
    min-height: 300px;
    }

    .organization-link {
    text-decoration: none !important;
    color: inherit;
    display: block;
    flex: 0 1 220px;
    box-sizing: border-box;
    }

    .organization-card {
    background-color: #b4d9f7;
    border-radius: 10px;
    padding: 15px;
    min-height: 200px;
    box-sizing: border-box;
    transition: transform 0.3s ease, border 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    }

    .organization-card:hover {
    border: 2px solid gray;
    transform: scale(1.01);
    }

    .image-container {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #e6f0fa;
    padding: 10px;
    }

    .image-container img {
    max-width: 100%;
    max-height: 100%;
    }

    .org-logo-placeholder {
    width: 100%;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #d0e4fd;
    color: #666;
    font-style: italic;
    border-radius: 8px;
    margin-bottom: 10px;
    }

    .org-name {
    text-align: center;
    font-weight: bold;
    margin: 0 0 10px 0;
    }

    .org-info {
    background-color: white;
    border-radius: 5px;
    padding: 10px;
    font-size: 14px;
    }

    .org-info div {
    margin-bottom: 4px;
    }

    .org-info strong {
    font-weight: bold;
    }

    .org-status {
    font-style: italic;
    }

    .active-status {
    color: blue;
    }

    .inactive-status {
    color: red;
    }

    .tabcontent #Post {
    font-family: 'Outfit', sans-serif;
    background: #989898ff;
    margin: 0;
    padding: 0;
    overflow-y: auto;
    }

    .container-flex {
    display: flex;
    min-height: auto;
    }

    /* Achievements container layout */
    .achievements-container {
    display: flex;
    gap: 20px;
    padding: 10px 20px;
    height: calc(100vh - 120px);
    box-sizing: border-box;
    }

    /* Sidebar */
    aside.org-sidebar {
    flex: 0 0 200px;
    position: static;
    top: 80px;
    height: calc(85vh - 200px);
    overflow-y: auto;
    background: #fff;
    border-right: 1px solid #ddd;
    padding: 20px;
    box-sizing: border-box;
    }

    aside.org-sidebar h2 {
    margin-top: 0;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #333;
    }

    #statusList {
    list-style: none;
    padding: 0;
    margin: 0;
    }

    #statusList li {
    padding: 10px 15px;
    margin-bottom: 8px;
    cursor: pointer;
    border-radius: 4px;
    color: #555;
    user-select: none;
    border-left: 4px solid transparent;
    transition: background-color 0.2s, border-color 0.2s, color 0.2s;
    }

    #statusList li.active {
    background-color: #e0f0ff;
    border-left-color: #007bff;
    color: #007bff;
    font-weight: bold;
    }

    #statusList li:hover:not(.active) {
    background-color: #f0f8ff;
    }

    /* Stream */
    .stream {
    flex: 1 1 auto;
    overflow-y: auto;
    padding-right: 10px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    margin: 0 auto;
    padding-left: 0; /* Removed padding-left: 200px; to avoid layout issues */
    }

    .post-card {
    width: 100%;
    max-width: 720px;
    max-height: 90vh;
    margin-bottom: 20px;
    padding: 15px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
    box-sizing: border-box;
    transition: box-shadow 0.3s ease;
    }

    .post-card:hover {
    box-shadow: 0 6px 15px rgb(0 0 0 / 0.15);
    }

    .post-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    }

    .post-header img.profile-pic {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 12px;
    }

    .post-header h3 {
    margin: 0;
    font-size: 1.2rem;
    color: #222;
    }

    .post-time {
    font-size: 0.85rem;
    color: #666;
    }

    .post-body p {
    margin: 0 0 12px 0;
    color: #444;
    white-space: pre-wrap;
    }

    .post-images-container {
    display: grid;
    gap: 8px;
    margin-bottom: 12px;
    width: 100%;
    max-width: 100%;
    grid-auto-rows: auto;
    }

    .post-images-container.one-image {
    grid-template-columns: 1fr;
    min-height: 450px;
    }

    .post-images-container.two-images {
    grid-template-columns: repeat(2, 1fr);
    }

    .post-images-container.three-images {
    grid-template-columns: repeat(3, 1fr);
    }

    .post-images-container.four-or-more {
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, auto);
    }

    .post-images-container img.post-image,
    .post-images-container .image-overlay {
    width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    display: block;
    max-height: 250px;
    }

    .image-overlay {
    background: rgba(0,0,0,0.4);
    color: #fff;
    font-weight: bold;
    font-size: 1.2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 6px;
    user-select: none;
    position: relative;
    }

    .image-overlay .overlay-text {
    position: absolute;
    bottom: 0;
    right: 0;
    background: rgba(0,0,0,0.6);
    color: #fff;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 0 0 6px 0;
    font-size: 0.8rem;
    }

    .post-actions {
    display: flex;
    gap: 10px;
    }

    .post-actions button {
    font-family: 'Outfit', sans-serif;
    flex: 1;
    padding: 8px 0;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    color: #fff;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
    width: 200px;
    }

    .approve-btn {
    background-color: #0477eaff;
    }

    .approve-btn:hover {
    background-color: #095cafff;
    }

    .disapprove-btn {
    background-color: #dc3545;
    }

    .disapprove-btn:hover {
    background-color: #c82333;
    }

    .status-label {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #fff;
    }

    .status-posted {
    background-color: #28a745;
    }

    .status-pending {
    background-color: #ffc107;
    color: #212529;
    }

    .status-denied {
    background-color: #dc3545;
    }

    .post-card.hidden {
    display: none !important;
    }

    /* Modal styles */
    #imageModal2 {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
    user-select: none;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    flex-direction: column;
    padding: 0;
    }

    #imageModal2 .modal-content-wrapper {
    position: relative;
    max-width: 90%;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    }

    #imageModal2 img {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 8px;
    user-select: none;
    margin: auto;
    display: block;
    }

    #modalCaption {
    color: #fff;
    text-align: center;
    margin-top: 10px;
    font-size: 1rem;
    user-select: none;
    }

    #imageModal2 .close,
    #imageModal2 .prev,
    #imageModal2 .next {
    position: absolute;
    width: auto;
    height: auto;
    top: 50%;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    user-select: none;
    padding: 10px;
    background: rgba(0,0,0,0.3);
    border-radius: 60%;
    transform: translateY(-50%);
    transition: background 0.3s;
    }

    #imageModal2 .close {
    top: 20px;
    right: 35px;
    transform: none;
    }

    #imageModal2 .close:hover,
    #imageModal2 .prev:hover,
    #imageModal2 .next:hover {
    background: rgba(0,0,0,0.6);
    }

    #imageModal2 .prev {
    left: 20px;
    }

    #imageModal2 .next {
    right: 20px;
    }

    #imageModal2 .prev,
    #imageModal2 .next {
    display: flex;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    user-select: none;
    z-index: 100;
    }

</style>
    <!--SCRIPTS-->

    <script src="script/re-org.js"></script>

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
                            <a href="admin-homepage.php"><b>Home</b></a> 
                        </li>

                        <li>
                            <a href="admin-membership.php">Membership</a>
                        </li>

                        <!--<li>
                            <a href="admin-membership.php">Admin Folder</a>
                        </li>-->

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
                        <div class="content-1"> <!---Sidebar-->
                            <div class="org-logo">
                                <img src="src/org-logo/<?php echo $org_logo; ?>" alt="Org Logo here!">
                            </div>
                            <div class="org-name">
                                <h1><?php echo $org_name; ?></h1>
                            </div>
                        </div>  
                        <div class="content-2">
                            <button aria-haspopup="true" aria-expanded="false" aria-label="Add" class="add-btn" id="addBtn" type="button"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="40" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                                </svg>
                            </button>
                        </div>

                        <div class="dropdown-menu" id="dropdownMenu" role="menu" aria-label="Add dropdown menu">
                            <button type="button" role="menuitem" id="createEventBtn">Create EVENT</button>
                            <button type="button" role="menuitem" id="createPostBtn">Create ACHIEVEMENT</button>
                            <button type="button" role="menuitem" id="createAnnouncementBtn">Create ANNOUNCEMENT</button>
                        </div>
        </div>
</div>
<div style="height: calc(100vh - 64px);">
            <aside class="sidebar" aria-label="Sidebar navigation">

                <button class="tablinks active" onclick="openTab(event, 'Organizations')" aria-label="Members" title="Org Profile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-building-fill-exclamation" viewBox="0 0 16 16">
                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v7.256A4.5 4.5 0 0 0 12.5 8a4.5 4.5 0 0 0-3.59 1.787A.5.5 0 0 0 9 9.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .39-.187A4.5 4.5 0 0 0 8.027 12H6.5a.5.5 0 0 0-.5.5V16H3a1 1 0 0 1-1-1zm2 1.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m3 0v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                    <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1.5a.5.5 0 0 0 1 0V11a.5.5 0 0 0-.5-.5m0 4a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                    </svg>
                <p>Org Profile</p>
                </button>

                <button class="tablinks" onclick="openTab(event, 'Events')" aria-label="Calendar" title="Event Calendar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg>
                    <p>Event Calendar</p>
                </button>

                <button class="tablinks" onclick="openTab(event, 'Achievements')" aria-label="Documents" title="Posts">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
                <p>Achievements</p>
                </button>

                <button class="tablinks" onclick="openTab(event, 'Announcements')" aria-label="Announcement" title="Announcement">
                <svg width="100%" height="27" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 7.99992V11.9999M10.25 5.49991H6.8C5.11984 5.49991 4.27976 5.49991 3.63803 5.82689C3.07354 6.11451 2.6146 6.57345 2.32698 7.13794C2 7.77968 2 8.61976 2 10.2999L2 11.4999C2 12.4318 2 12.8977 2.15224 13.2653C2.35523 13.7553 2.74458 14.1447 3.23463 14.3477C3.60218 14.4999 4.06812 14.4999 5 14.4999V18.7499C5 18.9821 5 19.0982 5.00963 19.1959C5.10316 20.1455 5.85441 20.8968 6.80397 20.9903C6.90175 20.9999 7.01783 20.9999 7.25 20.9999C7.48217 20.9999 7.59826 20.9999 7.69604 20.9903C8.64559 20.8968 9.39685 20.1455 9.49037 19.1959C9.5 19.0982 9.5 18.9821 9.5 18.7499V14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg> 
                <p>Announcements</p>
                </button>


                <div style="border-top: 1.2px solid #ccc; width: 75%;" ></div>

                <button class="tablinks" onclick="openTab(event, 'Organizations')" aria-label="Members" title="Organizations">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                    <p>User Admin</p>
                </button>


                <button class="tablinks" id="confirmButton" aria-label="Logout" title="Logout" >
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                
                </svg>
                <p>Logout</p>
                </button>
                
        </aside> <!------END HEADER----->

</div>

<div id="confirmationModal" class="modal">
        
        <div class="modal-content">
            <div class="content">
                <h2>Confirmation</h2>

                <br>
                <p>Do you want to proceed Logout?</p>
                <br>

                <button id="confirmYes">Yes</button>
                <button id="confirmNo">No</button>
            </div>
        </div>
</div><!--- END CONFIRMATION LOGOUT-->



<!--MAIN CONTENT-->

<div class="main-content">

    <div class="body-content">
        <div class="tabcontent" id="Organizations">Welcome to Org Profile!</div>
        <div class="tabcontent" id="Events">Welcome to Events!</div>
<div class="tabcontent" id="Achievements" style="display:none;">
  <div class="achievements-container">
    <!-- Sidebar: Achievement Status -->
    <aside class="org-sidebar" aria-label="Achievement status sidebar">
      <h2>Achievement Status</h2>
      <ul id="statusList" role="listbox" aria-label="Post status list">
        <li data-status="1" class="active" role="option" tabindex="0">POSTED</li>
        <li data-status="3" role="option" tabindex="0">PENDING</li>
        <li data-status="0" role="option" tabindex="0">DENIED</li>
      </ul>
    </aside>

    <!-- Posts Stream -->
    <div class="stream" id="postStream" aria-live="polite" aria-relevant="additions removals">
      <!-- Placeholders for no posts -->
      <div id="noPostsPosted" class="no-posts-placeholder" style="display:none; text-align:center; margin-top:40px;">
        <img src="src/nopost.png" alt="No posted posts found" style="max-width:300px; width:100%; height:auto;" />
        <p>No posted posts found.</p>
      </div>
      <div id="noPostsPending" class="no-posts-placeholder" style="display:none; text-align:center; margin-top:40px;">
        <img src="src/nopost.png" alt="No pending posts found" style="max-width:300px; width:100%; height:auto;" />
        <p>No pending posts found.</p>
      </div>
      <div id="noPostsDenied" class="no-posts-placeholder" style="display:none; text-align:center; margin-top:40px;">
        <img src="src/nopost.png" alt="No denied posts found" style="max-width:300px; width:100%; height:auto;" />
        <p>No denied posts found.</p>
      </div>

      <?php
      require("database/achievement-con.php");

      function getImagesArray($imagesField) {
          if (empty($imagesField)) return [];
          $images = json_decode($imagesField, true);
          if (json_last_error() === JSON_ERROR_NONE && is_array($images)) {
              return $images;
          }
          $urls = array_filter(array_map('trim', explode(',', $imagesField)));
          return $urls;
      }

      if (empty($posts)) {
          echo '<div style="text-align:center; margin-top:40px;">
                  <img src="src/nopost.png" alt="No posts found" style="max-width:300px; width:100%; height:auto;" />
                </div>';
      } else {
          foreach ($posts as $post):
              $achievement_approve = intval($post['achievement_approve'] ?? -1);
              if (!in_array($achievement_approve, [0,1,3])) continue;

              $images = getImagesArray($post['achievement_files'] ?? '');
              $org_logo = htmlspecialchars($post['org_logo'] ?? '');
              $org_name = htmlspecialchars($post['org_name'] ?? '');
              $created_at_raw = $post['created_at'] ?? '';
              $org_id = htmlspecialchars($post['org_id'] ?? '');
              $achievement_id = htmlspecialchars($post['achievement_id'] ?? '');

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

              $count = count($images);
              $containerClass = '';
              if ($count === 1) {
                  $containerClass = 'one-image';
              } elseif ($count === 2) {
                  $containerClass = 'two-images';
              } elseif ($count === 3) {
                  $containerClass = 'three-images';
              } else {
                  $containerClass = 'four-or-more';
              }
      ?>
      <div class="post-card" data-is-approve="<?php echo $achievement_approve; ?>" data-org-id="<?php echo $org_id; ?>" data-post-id="<?php echo $achievement_id; ?>">
          <div class="post-header">
              <?php if ($org_logo): ?>
                  <img class="profile-pic" src="<?php echo $org_logo; ?>" alt="<?php echo $org_name; ?> logo" onerror="this.style.display='none';" />
              <?php endif; ?>
              <div>
                  <h3><?php echo $org_name; ?></h3>
                  <span class="post-time"><?php echo $formatted_time; ?></span>
              </div>
          </div>
          <div class="post-body">
              <p><?php echo $description; ?></p>
          </div>

          <?php if (!empty($images)): ?>
              <div class="post-images-container <?php echo $containerClass; ?>" data-all-images='<?php echo json_encode($images, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
                  <?php
                  $maxBoxes = 4;
                  $showCount = min($count, $maxBoxes);

                  for ($i = 0; $i < $showCount; $i++):
                      if ($i === 3 && $count > 4):
                  ?>
                      <div class="image-overlay clickable-image"
                          data-fullsrc="<?php echo htmlspecialchars($images[$i]); ?>"
                          data-post-id="<?php echo $achievement_id; ?>"
                          data-index="<?php echo $i; ?>"
                          title="Click to view images">
                          <img src="<?php echo htmlspecialchars($images[$i]); ?>"
                              alt="<?php echo $description ? strip_tags($description) : 'Post image'; ?>"
                              class="post-image" />
                          <div class="overlay-text">+<?php echo $count - 4; ?> more</div>
                      </div>
                  <?php else: ?>
                      <img src="<?php echo htmlspecialchars($images[$i]); ?>"
                          alt="<?php echo $description ? strip_tags($description) : 'Post image'; ?>"
                          class="post-image clickable-image"
                          data-fullsrc="<?php echo htmlspecialchars($images[$i]); ?>"
                          data-post-id="<?php echo $achievement_id; ?>"
                          data-index="<?php echo $i; ?>"
                          style="cursor:pointer;"
                      />
                  <?php endif; endfor; ?>
              </div>

              <?php
              if ($count > 4):
                  for ($i = 4; $i < $count; $i++):
              ?>
                  <img src="<?php echo htmlspecialchars($images[$i]); ?>"
                      alt="<?php echo $description ? strip_tags($description) : 'Post image'; ?>"
                      class="post-image clickable-image"
                      data-fullsrc="<?php echo htmlspecialchars($images[$i]); ?>"
                      data-post-id="<?php echo $achievement_id; ?>"
                      data-index="<?php echo $i; ?>"
                      style="display:none;"
                  />
              <?php endfor; endif; ?>
          <?php endif; ?>

          <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
              <?php if ($achievement_approve === 1): ?>
                  <span class="status-label status-posted">POSTED</span>
              <?php elseif ($achievement_approve === 3): ?>
                  <span class="status-label status-pending">PENDING</span>
              <?php elseif ($achievement_approve === 0): ?>
                  <span class="status-label status-denied">DENIED</span>
              <?php endif; ?>
          </div>
      </div>
      <?php
          endforeach;
      }
      ?>
    </div>
  </div>
</div>
<!-- Modal for image popup -->


        <div class="tabcontent" id="Applicants">Welcome to Applicants!</div>
        <div class="tabcontent" id="Announcements">Welcome to Announcements!</div>
        <div class="tabcontent" id="About Us" > Welcome to About Us Page</div>

    </div>
</div>

<!-- Modal for image popup -->
<div id="imageModal2" style="display:none;">
  <span class="close" title="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
        </svg>
  </span>
  <span class="prev" title="Previous">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
        </svg>
  </span>
  <span class="next" title="Next">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
        </svg>
  </span>
  <div class="modal-content-wrapper">
    <img id="modalImage" src="" alt="Expanded image" />
    <div id="modalCaption" aria-live="polite"></div>
  </div>
</div>


<!---CONFIRMATION LOGOUT-->



</body>
<script>
    /* LOGOUT BUTTON CONFIRMATION */
document.addEventListener('DOMContentLoaded', () => {
    // --- Logout Confirmation Modal ---
    const modal = document.getElementById('confirmationModal');
    const confirmButton = document.getElementById('confirmButton');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');

    if (confirmButton && modal) {
        confirmButton.addEventListener('click', () => {
            modal.style.display = 'block';
        });
    }

    if (confirmYes) {
        confirmYes.addEventListener('click', () => {
            window.location.replace('../reg-user/login_form.php');
        });
    }

    if (confirmNo && modal) {
        confirmNo.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

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

    // --- Tab Active Button Indication ---
    window.openTab = function(evt, tabName) {
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

        if (evt && evt.currentTarget) {
            evt.currentTarget.classList.add("active");
        }
    };

    // --- Add Button Dropdown Functionality ---
    const addBtn = document.getElementById('addBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    if (addBtn && dropdownMenu) {
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

        const createPostBtn = document.getElementById('createPostBtn');
        if (createPostBtn) {
            createPostBtn.addEventListener('click', () => {
                window.location.href = 'form/create-achievement.php';
            });
        }

        const createEventBtn = document.getElementById('createEventBtn');
        if (createEventBtn) {
            createEventBtn.addEventListener('click', () => {
                window.location.href = 'form/create-event.php';
            });
        }

        const createAnnouncementBtn = document.getElementById('createAnnouncementBtn');
        if (createAnnouncementBtn) {
            createAnnouncementBtn.addEventListener('click', () => {
                window.location.href = 'form/create-announcement.php';
            });
        }
    }

    // --- Achievement Status Filter and Modal Image Popup ---
    const statusList = document.getElementById('statusList');
    const postStream = document.getElementById('postStream');

    if (statusList && postStream) {
        const statusItems = statusList.querySelectorAll('li');
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
    }

    // Modal image popup
    const modal = document.getElementById('imageModal2');
    const modalImage = document.getElementById('modalImage');
    const closeBtn = modal ? modal.querySelector('.close') : null;
    const prevBtn = modal ? modal.querySelector('.prev') : null;
    const nextBtn = modal ? modal.querySelector('.next') : null;
    const modalCaption = document.getElementById('modalCaption');

    let currentImages = [];
    let currentIndex = 0;

    function showImage() {
        if (!modalImage || !modalCaption || !prevBtn || !nextBtn) return;
        modalImage.src = currentImages[currentIndex];
        modalImage.alt = `Image ${currentIndex + 1}`;
        modalCaption.textContent = `Image ${currentIndex + 1} of ${currentImages.length}`;
        prevBtn.style.display = currentIndex === 0 ? 'none' : 'flex';
        nextBtn.style.display = currentIndex === currentImages.length - 1 ? 'none' : 'flex';
    }

    function openModal(container, clickedSrc) {
        if (!container || !modal) return;
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

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        });
    }

    window.addEventListener('click', e => {
        if (e.target === modal) {
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

    document.addEventListener('keydown', e => {
        if (modal && modal.style.display === 'flex') {
            if (e.key === 'ArrowRight') nextBtn && nextBtn.click();
            else if (e.key === 'ArrowLeft') prevBtn && prevBtn.click();
            else if (e.key === 'Escape') closeBtn && closeBtn.click();
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

    
</script>
</html>
