<?php
require("database/announcement-con.php");
include ("database/user-con.php");
?>
<!DOCTYPE html>
<head>
    <title>Announcement | <?php echo $username; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="styles/announcement-style.css" />
    <link rel="stylesheet" type="text/css" href="styles/search.css" />
    <link rel="stylesheet" type="text/css" href="styles/calendar.css" />
    <link rel="stylesheet" type="text/css" href="styles/calendar2.css" />
    <link rel="stylesheet" type="text/css" href="styles/notification.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css"/>
    <link rel="stylesheet" type="text/css" href="styles/main-content.css" />
<style>
    body {
      font-family: 'Outfit', sans-serif;
      margin: 0;
      padding: 0;
      background-color: rgba(235, 233, 233, 0.87);
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
    .main-content {
        height: calc(100vh - 200px);
        overflow-y: auto; /* Enable vertical scrolling */
        margin: 15px;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
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
      padding-bottom:0;
    }
    .post-images-container {
      display: grid;
      grid-template-columns: repeat(1, 1fr);
      gap: 4px;
      padding: 16px;
      padding-top:0;
    }
    .post-image {
      max-width: 100%;
      max-height: 600px;
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
        max-width: 1150px;
        margin: 20px auto;
        gap: 20px;
        padding: 20px;
        padding-top: 0; /* to keep your existing margin-top for stream */
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

      .post-body h2{
        font-size:20px;
        font-weight:bold;
        text-align:center;
        margin-bottom:10px;
        margin: 0;
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
    <script src="script/tabs.js"></script>
    <!--<script src="https://cdn.tailwindcss.com"></script>-->
    <script src="script/sidebar.js"></script>
    <script src="script/transition-effect.js"></script>
   <!-- <script src="script/event-calendar.js"></script>-->




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
                            <a href="achievement.php">Achievements</a>
                        </li>

                        <li>
                            <a href="announcement.php"><b>Announcements</b></a>
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
                    <div class="content-1" style="height: 80px;"> <!---Sidebar-->
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
                                    <b>Announcements</b>
                                </div>

                            </div> 
                    </div>  
                        
                        <div class="content-2"><!-----EVENT CALENDAR----->

                            <div class="cont">
                                <form class="search-bar" action="">
                                        <input class="search-input" required="" name="search" type="search" autocomplete="off">
                                        <button type="submit" class="search-btn">
                                            <span>Search</span>
                                        </button>
                                </form>
                            </div>

                            <div class="cont">
                                <button id="openCalendarBtn" class="calendar" title="Event Calendar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" height="24" fill="none" class="svg-icon"><g stroke-width="2" stroke-linecap="round" stroke="#0075A3"><rect y="5" x="4" width="16" rx="2" height="16"></rect><path d="m8 3v4"></path><path d="m16 3v4"></path><path d="m4 11h16"></path></g></svg>
                                </button> 
                            </div><!---END EVENT CALENDAR-----> 

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
                                            </div>
                                            <div id="eventsList" class="border-t pt-3">
                                                <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/5feb2888-99ce-4420-91d4-606fcdae1a8c.png" alt="Illustration of a calendar with events marked by colorful pins" class="mb-4 rounded-lg" />
                                                <p class="text-gray-500 text-sm">Select a date to view events</p>
                                            </div>
                                            <div id="eventFormContainer" class="hidden pt-4">
                                                <div class="bg-white rounded-lg p-4 shadow-md border">
                                                    <h4 class="font-medium text-gray-800 mb-3">Add New Event</h4>
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
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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

<div class="main-content">
    <div class="container-flex" style="margin-top:10px;">
        <!-- Sidebar -->
        <aside class="org-sidebar" aria-label="Organizations sidebar">
            <h2>Organizations</h2>
            <ul id="orgList" role="listbox" aria-label="Organizations list">
                <li data-org-id="all" class="active" role="option" tabindex="0" aria-selected="true">All Organizations</li>
                <?php foreach ($organizations as $org): ?>
                    <li data-org-id="<?php echo htmlspecialchars($org['org_id']); ?>" role="option" tabindex="0" aria-selected="false">
                        <?php if (!empty($org['org_logo'])): ?>
                            <img src="src/org-logo/<?php echo htmlspecialchars($org['org_logo']); ?>" alt="<?php echo htmlspecialchars($org['org_name']); ?> logo" class="org-logo" onerror="this.style.display='none';" />
                        <?php endif; ?>
                        <span><?php echo htmlspecialchars($org['org_name']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <!-- Announcements Stream -->
        <div class="stream" id="announcementStream" style="flex: 1; overflow-y: auto; max-height: 80vh;">
            <?php if (empty($announcements)): ?>
                <div id="noAnnouncementsMessage" style="text-align:center; margin-top:40px;">
                    <img src="src/nopost.png" alt="No announcements found" style="max-width:300px; width:100%; height:auto;" />
                    <p>No announcements found.</p>
                </div>
            <?php else: ?>
                <div id="noAnnouncementsMessage" style="display:none; text-align:center; margin-top:40px;">
                    <img src="src/nopost.png" alt="No announcements found" style="max-width:300px; width:100%; height:auto;" />
                    <p>No announcements found for this organization.</p>
                </div>
                <?php
                function getAnnouncementImages($filesString) {
                    if (empty($filesString)) return [];
                    return array_map('trim', explode(',', $filesString));
                }
                foreach ($announcements as $announcement):
                    $images = getAnnouncementImages($announcement['announcement_file'] ?? '');
                    $title = htmlspecialchars($announcement['announcement_title'] ?? '');
                    $description = nl2br(htmlspecialchars($announcement['announcement_text'] ?? ''));
                    $created_at_raw = $announcement['created_at'] ?? '';
                    $announcement_id = htmlspecialchars($announcement['announcement_id'] ?? '');
                    $org_logo = htmlspecialchars($announcement['org_logo'] ?? '');
                    $org_name = htmlspecialchars($announcement['org_name'] ?? '');
                    $org_id = htmlspecialchars($announcement['org_id'] ?? '');

                    $formatted_time = '';
                    if (!empty($created_at_raw)) {
                        try {
                            $dateTime = new DateTime($created_at_raw);
                            $formatted_time = $dateTime->format('M d, Y \a\t h:i A');
                        } catch (Exception $e) {
                            $formatted_time = 'Invalid Date';
                        }
                    }

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
                <div class="post-card" data-post-id="<?php echo $announcement_id; ?>" data-org-id="<?php echo $org_id; ?>">
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
                        <h2><?php echo $title; ?></h2>
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
                                 data-post-id="<?php echo $announcement_id; ?>"
                                 data-index="<?php echo $i; ?>"
                                 title="Click to view images">
                                <img src="../admin/form/src/announcement/<?php echo htmlspecialchars($images[$i]); ?>"
                                     alt="<?php echo $description ? strip_tags($description) : 'Announcement image'; ?>"
                                     class="post-image" />
                                <div class="overlay-text">+<?php echo $count - 4; ?> more</div>
                            </div>
                            <?php else: ?>
                                <img src="../admin/form/src/announcement/<?php echo htmlspecialchars($images[$i]); ?>"
                                     alt="<?php echo $description ? strip_tags($description) : 'Announcement image'; ?>"
                                     class="post-image clickable-image"
                                     data-fullsrc="<?php echo htmlspecialchars($images[$i]); ?>"
                                     data-post-id="<?php echo $announcement_id; ?>"
                                     data-index="<?php echo $i; ?>"
                                     style="cursor:pointer;"
                                />
                            <?php endif; endfor; ?>
                        </div>

                        <?php if ($count > 4):
                            for ($i = 4; $i < $count; $i++):
                        ?>
                        <img src="../admin/form/src/announcement/<?php echo htmlspecialchars($images[$i]); ?>"
                             alt="<?php echo $description ? strip_tags($description) : 'Announcement image'; ?>"
                             class="post-image clickable-image"
                             data-fullsrc="<?php echo htmlspecialchars($images[$i]); ?>"
                             data-post-id="<?php echo $announcement_id; ?>"
                             data-index="<?php echo $i; ?>"
                             style="display:none;"
                        />
                        <?php endfor; endif; ?>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal HTML (needed for image modal) -->
<div id="imageModal" class="modal" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1">
    <div class="modal-content">
        <button class="close" aria-label="Close modal">&times;</button>
        <button class="prev" aria-label="Previous image">&#10094;</button>
        <button class="next" aria-label="Next image">&#10095;</button>
        <img id="modalImage" src="" alt="" class="modal-image" />
        <div class="image-counter" id="imageCounter"></div>
    </div>
</div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const closeBtn = modal.querySelector('.close');
        const prevBtn = modal.querySelector('.prev');
        const nextBtn = modal.querySelector('.next');
        const imageCounter = document.getElementById('imageCounter');

        let currentImages = [];
        let currentIndex = 0;

        function openModal(container, event) {
            const allImagesData = container.getAttribute('data-all-images');
            if (!allImagesData) return;

            try {
                let images = JSON.parse(allImagesData);
                // Extract just the filename from each image path
                currentImages = images.map(img => {
                    // If it's already just a filename, return as is
                    if (!img.includes('/')) return img;
                    // Otherwise, extract the filename from the path
                    return img.split('/').pop();
                });
            } catch (e) {
                // Fallback: extract filenames from img src attributes
                currentImages = Array.from(container.querySelectorAll('.post-image')).map(img => {
                    return img.src.split('/').pop();
                });
            }

            let clickedSrc = null;
            if (event && event.target) {
                const target = event.target;
                if (target.tagName === 'IMG' && container.contains(target)) {
                    // Extract filename from clicked image src
                    clickedSrc = target.src.split('/').pop();
                }
            }

            currentIndex = clickedSrc ? currentImages.indexOf(clickedSrc) : 0;
            if (currentIndex === -1) currentIndex = 0;

            showImage();
            modal.style.display = 'block';
            modal.setAttribute('aria-hidden', 'false');
            modal.focus();
        }

        function showImage() {
            const basePath = "../admin/form/src/announcement/";
            modalImage.src = basePath + currentImages[currentIndex];
            modalImage.alt = `Image ${currentIndex + 1}`;
            imageCounter.textContent = `${currentIndex + 1} / ${currentImages.length}`;

            prevBtn.style.display = currentIndex === 0 ? 'none' : 'flex';
            nextBtn.style.display = currentIndex === currentImages.length - 1 ? 'none' : 'flex';
        }

        document.addEventListener('click', function(e) {
            if (
                e.target.classList.contains('overlay-text') ||
                (e.target.classList.contains('post-image') && e.target.closest('.post-images-container'))
            ) {
                const container = e.target.closest('.post-images-container');
                openModal(container, e);
            }
        });

        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        });

        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }
        });

        nextBtn.addEventListener('click', function() {
            if (currentIndex < currentImages.length - 1) {
                currentIndex++;
                showImage();
            }
        });

        prevBtn.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                showImage();
            }
        });

        document.addEventListener('keydown', function(e) {
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

        // Sidebar filtering
        const orgList = document.getElementById('orgList');
        const announcementStream = document.getElementById('announcementStream');
        const noAnnouncementsMessage = document.getElementById('noAnnouncementsMessage');

        function filterAnnouncements(orgId) {
            const posts = announcementStream.querySelectorAll('.post-card');
            let visibleCount = 0;
            if (orgId === 'all') {
                posts.forEach(post => {
                    post.style.display = '';
                    visibleCount++;
                });
            } else {
                posts.forEach(post => {
                    if (post.dataset.orgId === orgId) {
                        post.style.display = '';
                        visibleCount++;
                    } else {
                        post.style.display = 'none';
                    }
                });
            }
            noAnnouncementsMessage.style.display = visibleCount === 0 ? 'block' : 'none';
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

        orgList.addEventListener('click', function(e) {
            const li = e.target.closest('li');
            if (!li) return;
            const orgId = li.dataset.orgId;
            filterAnnouncements(orgId);
            setActiveOrg(li);
        });

        orgList.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                const li = e.target.closest('li');
                if (!li) return;
                e.preventDefault();
                const orgId = li.dataset.orgId;
                filterAnnouncements(orgId);
                setActiveOrg(li);
            }
        });

        // Initialize filter on page load
        const defaultLi = orgList.querySelector('li[data-org-id="all"]');
        if (defaultLi) {
            setActiveOrg(defaultLi);
            filterAnnouncements('all');
        }
    });
</script>
</body>
