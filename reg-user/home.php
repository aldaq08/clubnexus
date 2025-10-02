<?php
require ("database/user-con.php");
?>
<!DOCTYPE html lang="en">
<head>
    <title>Homepage | <?php echo $username; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="styles/home.css" />
    <link rel="stylesheet" type="text/css" href="styles/calendar.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css" />
    <!----FONTS--->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        <link rel="icon" type="image/c-icon" href="src/clubnexusicon.ico">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <!---END FONTS-->

    <script src="script/sidebar.js"></script>

<style>
    html, body {
    font-family: "Outfit", sans-serif;
    font-optical-sizing: auto;
    font-weight: 500; /* Changed to a valid weight */
    font-style: normal;
    background-color: rgba(237, 237, 237, 0.96);
    width: 100%;
    margin: 0;
    overflow-y:auto;
    overflow-x: hidden;
    display: flex;
    flex-direction: column; /* Stack items vertically */
    height: 100%; /* Ensure the body takes up the full viewport height */
    margin: 0; /* Reset default body margin */
    }

    .header {
        width: 100%;
        position: sticky; /* Keep header at the top */
        top: 0; /* Stick to the top */
        z-index: 100; /* Ensure it's above other content */
        background-color: white; /* Or any background color to cover content */
    }

    .main-content {
        height: calc(100vh - 200px);
        overflow-y: auto; /* Enable vertical scrolling */
        margin: 15px;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
    }

    .body-content {
        margin-left: 1%;
        overflow: auto; /* or scroll */
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
        }
        /* For Chrome, Safari, and Opera */
        .body-content::-webkit-scrollbar {
        display: none;
        }


    .body-content {
        height: calc(100vh);
        overflow-y: auto;
    }

    .organization-card {
        width: 250px;
        background-color: #b4d9f7; /* Light blue background */
        border-radius: 10px;
        padding: 15px;
        margin: 10px;
        font-family: 'Outfit', sans-serif;
        box-sizing: border-box;
        min-height:350px;

        }
    .organization-card:hover {
            border:2px solid gray;
            transition:0.3s ease;
            transform: scale(1.01);
        }

        .image-container {
            min-width: 200px;
            min-height: 200px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e6f0fa;
            padding:10px;
        }

        .image-container img {
            min-width: 200px;
            min-height: 200px;
            max-width: 200px;
            max-height: 200px;
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
            font-style: "Fredoka", sans-serif;
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

        .active-members {
            font-style: italic;
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

        .search-content {
        background-color: white;
        color: #0075A3;
        font-family: "Outfit", sans-serif;
        padding: 10px 20px;
        font-size: 14px;
        border: 2px solid #0075A3;
        border-radius: 10px;
        width: 200px;
        max-height: 500px;
        overflow-y: auto;
        text-align: left;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
         scrollbar-width: none; /* Firefox */
         -ms-overflow-style: none; /* IE and Edge */
        }



        #container {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 250px;
        z-index: 50; 
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
    <!--END SCRIPTS-->
</head>

<body>

<div class="header">

    <!--------HEADER 1----------->
        <div class="head-content" style="  position: sticky;">
            <div class="logo"> <!--Logo-->
                <img src="src/web_logo.png">  
            </div>

            <div class="nav-panel"> <!--Navigation Panel-->
                <nav>
                    <ul>
                        <li>
                            <a href="home.php"><b>Home</b></a> 
                        </li>

                        <li>
                            <a href="achievement.php">Achievements</a>
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
                    <div class="content-1"> <!---Sidebar-->
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
                            <div class="category">
                            <button class="dropbtn"><b>APPLY NOW? &nbsp;▼</b></button>
                            <div class="dropdown-content">
                                <a href="forms/accre-requirements.php">New Organization</a>
                            </div>
                            </div>
                        </div>
                        </div>  
                        
                        <div class="content-2">

                            <div class="cont" style="position: relative; display: inline-block;"> <!-- relative container -->
                            <form class="search-bar" onsubmit="return false;">
                                <input id="searchInput" class="search-input" name="search" type="search" autocomplete="off" placeholder="Search organization...">
                                <button type="submit" class="search-btn">
                                <span>Search</span>
                                </button>
                            </form>

                            <div id="container" class="search-content" style="display: none; position: absolute; top: 100%; left: 0; z-index: 50;">
                                <div id="searchResults" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(1, 1fr)); gap: 16px; margin-top: 16px;">
                                <!-- search results go here -->
                                </div>
                            </div>
                            </div>

                            <div class="cont">
                                <button id="openCalendarBtn" class="calendar" title="Event Calendar"><!---EVENT CALENDAR-----> 
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" height="24" fill="none" class="svg-icon"><g stroke-width="2" stroke-linecap="round" stroke="#0075A3"><rect y="5" x="4" width="16" rx="2" height="16"></rect><path d="m8 3v4"></path><path d="m16 3v4"></path><path d="m4 11h16"></path></g></svg>
                                </button> 
                            </div>

                             <div id="calendarOverlay" class="calendar-overlay"></div>

                                <div id="calendarContainer" class="calendar-container">
                                    <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-white">
                                        <div class="flex items-center gap-4">
                                            <button id="prevMonthBtn" class="text-gray-500 hover:text-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <h2 class="text-2xl font-medium text-gray-800" id="currentMonthYear"></h2>
                                            <button id="nextMonthBtn" class="text-gray-500 hover:text-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                        <button id="closeCalendarBtn" class="text-gray-500 hover:text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="calendar-body">
                                        <div class="month-sidebar">
                                            <h3 class="font-normal text-gray-700 mb-3">Months</h3>
                                            <div id="monthList"></div>
                                        </div>
                                        
                                        <div class="calendar-main">
                                            <div class="grid grid-cols-7 gap-1 mb-4 pb-2 border-b border-gray-200">
                                                <div class="text-center font-medium text-gray-500 text-sm font-outfit uppercase pb-1">Sun</div>
                                                <div class="text-center font-medium text-gray-500 text-sm font-outfit uppercase pb-1">Mon</div>
                                                <div class="text-center font-medium text-gray-500 text-sm uppercase pb-1">Tue</div>
                                                <div class="text-center font-medium text-gray-500 text-sm uppercase pb-1">Wed</div>
                                                <div class="text-center font-medium text-gray-500 text-sm uppercase pb-1">Thu</div>
                                                <div class="text-center font-medium text-gray-500 text-sm uppercase pb-1">Fri</div>
                                                <div class="text-center font-medium text-gray-500 text-sm uppercase pb-1">Sat</div>
                                            </div>
                                            
                                            <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>
                                        </div>
                                        
                                        <div class="events-sidebar">
                                            <div class="flex justify-between items-center mb-3">
                                                <h3 class="font-normal text-gray-700">Events</h3>
                                               <!-- <button id="addEventBtn" class="text-blue-500 hover:text-blue-700 text-sm font-medium flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Add Event
                                                </button>-->
                                            </div>
                                            <div id="eventsList" class="border-t pt-3">
                                                <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/5feb2888-99ce-4420-91d4-606fcdae1a8c.png" alt="Illustration of a calendar with events marked by colorful pins" class="mb-4 rounded-lg" />
                                                <p class="text-gray-500 text-sm">Select a date to view events</p>
                                            </div>
                                            <div id="eventFormContainer" class="hidden pt-4">
                                                <div class="bg-white rounded-lg p-4 shadow-md border">
                                                   <!-- <h4 class="font-medium text-gray-800 mb-3">Add New Event</h4>
                                                    <form id="eventForm" class="space-y-3">
                                                        <div>
                                                            <label class="block text-sm text-gray-600 mb-1">Event Title</label>
                                                            <input type="text" id="eventTitle" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm text-gray-600 mb-1">Date</label>
                                                            <input type="date" id="eventDate" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm text-gray-600 mb-1">Time</label>
                                                            <input type="time" id="eventTime" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm text-gray-600 mb-1">Description</label>
                                                            <textarea id="eventDescription" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2"></textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm text-gray-600 mb-1">Location</label>
                                                            <input type="text" id="eventLocation" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm text-gray-600 mb-1">Poster Image</label>
                                                            <input type="file" id="eventPoster" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                        </div>
                                                        <div class="flex justify-end space-x-2 mt-4">
                                                            <button type="button" id="cancelEventBtn" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                                                            <button type="submit" class="px-3 py-1.5 text-sm text-white bg-blue-500 hover:bg-blue-600 rounded-md">Add Event</button>
                                                        </div>
                                                    </form>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="imageModal" class="image-modal">
                                    <span class="close-modal">&times;</span>
                                    <div class="image-modal-content">
                                        <img id="expandedImage" src="" alt="Expanded view" src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/807a95c6-db1f-4d86-8b8f-e340930227c8.png">
                                    </div>
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


<!--MAIN CONTENT-->

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const container = document.getElementById('container');
    const resultsContainer = document.getElementById('searchResults');

    let debounceTimeout = null;

    function showNoResultsMessage(message) {
      resultsContainer.innerHTML = `<p style="grid-column: 1 / -1; text-align: center;">${message}</p>`;
      container.style.display = 'block';
    }

    searchInput.addEventListener('input', () => {
      clearTimeout(debounceTimeout);
      const query = searchInput.value.trim();

      if (query.length === 0) {
        resultsContainer.innerHTML = '';
        container.style.display = 'none';
        return;
      }

      debounceTimeout = setTimeout(() => {
        fetch(`../reg-user/database/search-con.php?search=${encodeURIComponent(query)}`)
          .then(response => response.json())
          .then(data => {
            resultsContainer.innerHTML = '';

            if (data.length === 0) {
              showNoResultsMessage('No organizations found.');
              return;
            }

            // Show container since we have results
            container.style.display = 'block';


            data.forEach(org => {
              const orgDiv = document.createElement('div');
              orgDiv.style.cursor = 'pointer';
              orgDiv.style.marginBottom = '20px';
              orgDiv.style.display = 'flex';
              orgDiv.style.alignItems = 'center';
              orgDiv.style.gap = '12px';

              orgDiv.innerHTML = `
                <a href="forms/org-profile.php?id=${org.org_id}" style="text-decoration:none; color:inherit; display: flex; align-items: center; gap: 12px; width: 100%;">
                  <img src="src/org-logo/${org.org_logo || 'default-logo.png'}" alt="${org.org_name} Logo" style="width:50px; height:50px; object-fit:cover; border-radius:50%; border: 2px solid #0672a1; flex-shrink: 0;">
                  <div style="font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;">${org.org_name}</div>
                </a>
              `;

              resultsContainer.appendChild(orgDiv);
            });

            // Add break line/separator after all results
            const breakLine = document.createElement('hr');
            breakLine.style.gridColumn = '1 / -1';
            breakLine.style.marginTop = '20px';
            breakLine.style.border = '1px solid #0075A3';
            resultsContainer.appendChild(breakLine);

          })
          .catch(err => {
            console.error('Search error: ', err);
            showNoResultsMessage('Error loading results.');
          });
      }, 300);
    });
  });
</script>

<div class="main-content" style="position: relative; z-index: 0;">
    <div class="body-content" style="display: grid; grid-template-columns: repeat(5, 1fr); align-items: start;">
        <?php
            $pdo = require 'database/db_connection.php';
            $query = 'SELECT org_id, org_logo, org_name, is_active, is_approved FROM organizations WHERE is_approved = 1';
            try {
                $stmt = $pdo->query($query);
                if (!$stmt) {
                    throw new Exception("Query preparation failed.");
                }
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  
                if (count($records) > 0) {
                    foreach ($records as $record) {
                        $orgId = $record['org_id'];
                        echo '<a class="organization-link" href="forms/org-profile.php?id=' . urlencode($orgId) . '">';
                        echo '<div class="organization-card">';
                        if (!empty($record['org_logo'])) {
                            echo '<div class="image-container"><img src="src/org-logo/' . htmlspecialchars($record['org_logo']) . '" alt="Organization Logo"></div>';
                        } else {
                            echo '<div class="org-logo-placeholder">No logo available</div>';
                        }
                        echo '<h4 class="org-name">' . htmlspecialchars($record['org_name']) . '</h4>';
                        echo '<div class="org-info">';
                        echo '<div><strong>STATUS:</strong> <span class="org-status" style="color: ' . (isset($record['is_active']) ? ($record['is_active'] ? 'blue' : 'red') : 'red') . ';">' . (isset($record['is_active']) ? ($record['is_active'] ? 'ACTIVE' : 'INACTIVE') : 'INACTIVE') . '</span></div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                } else {
                    echo '<p>No approved organizations found.</p>';
                }
            } catch (Exception $e) {
                echo '<p>Error fetching organizations: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        ?>
    </div>
</div>


</body>

</html>
