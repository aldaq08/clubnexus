<?php
include("database/org-admin.php");
?>
<!DOCTYPE html>
<head>
    <title>Admin Homepage</title>
    <link rel="stylesheet" type="text/css" href="styles/admin.css"/>
    <script src="script/re-org.js"></script>
</head>
<body>
<style>
    /* Base Styles */
html, body {
    font-family: "Outfit", sans-serif;
    font-optical-sizing: auto;
    font-weight: 500;
    font-style: normal;
    background-color: white;
    height: 100%;
    width: 100%;
    margin: 0;
}

/* Grid Layout Styles */
.parent {
    height: 100%;
    display: grid;
    grid-template-columns: auto 1fr; /* Modified: auto for content-based sizing */
    grid-template-rows: auto 1fr; /* auto for div1, 1fr for the rest */
    grid-column-gap: 0;
    grid-row-gap: 0;
    height: 100vh; /* Ensure the parent takes up the full viewport height */
    overflow: hidden; /* Prevent parent from scrolling */
}

.div1 {
    width: 100%;
    grid-area: 1 / 1 / 2 / 3; /* Modified: span across both columns */
    position: sticky;
    top: 0; /* stick to the top */
    background-color: #eee; /* For visibility */
    z-index: 1; /* Ensure it stays on top of the scrolling content */
    height: auto; /* Let content define the height */
}

.div2 {
    width:7%;
    grid-area: 2 / 1 / 3 / 2;
    width: 300px; /* Size based on content */
}

.div3 {
    width: 15%;
    grid-area: 2 / 2 / 3 / 3;
    width: auto; /* Size based on content */
}

.div4 {
    width: 78%;
    grid-area: 2 / 3 / 3 / 4;
    width: auto; /* Size based on content */
}

.div2,
.div3,
.div4 {
    position: relative;
    overflow-y: auto; /* Enable vertical scrolling */
    height: auto; /* Let content define the height within the scrollable area */
    background-color: #ddd; /* For visibility */
    padding: 10px; /* Add some padding for better readability */
    white-space: nowrap; /* Prevent text from wrapping */
}

/* Example content to make the divs scrollable */
.div2::before, .div3::before, .div4::before {
    white-space: pre-line; /* Respects line breaks */
    display: block;
}

/* Header Styles */
.header {
    width: 100%;
    z-index: 100; /* Ensure header stays on top */
    background-color: white; /* Add a background color for better visibility */
}

/* Head Content Styles */
.head-content {
    display: grid;
    grid-template-columns: 50% 50%;
    align-items: center;
    gap: 20px;
    margin-left: 20px;
}

.logo img {
    max-width: 130px;
    height: auto;
    margin: 10px;
}

/* Navigation Panel Styles */
.nav-panel ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: flex-end;
    height: 40px;
    min-height: fit-content;
}

.nav-panel li {
    margin-right: 10px;
    flex: 1;
    min-height: 54px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 5px;
}

.nav-panel a {
    font-size: 1rem;
    text-decoration: none;
    display: block;
    padding: 0 10px;
    font-family: "Outfit", sans-serif;
    color: rgb(86, 84, 84);
    text-align: center; /* Center the link text */
}

.nav-panel li:nth-child(2) {
    border-bottom: 4px solid #0e66f7;
}

.nav-panel li:hover {
    color: #0e66f7;
    background: #f4efefa4;
}

/* Head Content 2 Styles */
.head-content2 {
    display: grid;
    grid-template-columns: 50% 50%;
    align-items: center;
    justify-content: center;
    gap: 20px;
    height: 83px;
    background-color: #0e66f7;
    padding: 0 20px; /* Add padding to the sides */
}

/* Content 1 Styles */
.content-1 {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-left: 20px; /* Keep the margin */
}

.content-1 .org-logo img {
    height: 60px;
    width: auto;
}

.content-1 .org-name h1 {
    font-family: 'Fredoka', sans-serif;
    font-size: 24px;
    color: white;
    margin: 0; /* Remove default h1 margins */
}

/* Content 2 Styles */
.content-2 {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 20px;
    margin-right: 20px;
    z-index: 5000;
}

.content-2 button svg {
    fill: white;
}

/* Sidebar Styles */
.div2 aside.sidebar {
    top: 175px; /* Adjust based on header height */
    left: 0;
    bottom: 0;
    background: white;
    border-right: 1px solid #ccc;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 24px 0;
    gap: 32px;
    overflow-y: auto;
    z-index: 20;
}

.div2 aside.sidebar span {
    font-family: 'Outfit', sans-serif;
    font-size: 0.650vw;
    text-align: center;
}

.div2 aside.sidebar button {
    background: none;
    border: none;
    font-size: 3.125rem;
    color: #666;
    cursor: pointer;
    height: 50px;
    width: 60%;
}
.div2 aside.sidebar button p{
    font-size: 14px;
}

.div2 aside.sidebar button.active {
    background-color: #2a7ad9;
    color: #fff;
    border-radius: 5px;
    transform: scale(1.1);
    transition: 0.3s ease-in;
}

.div2 aside.sidebar .divider {
    width: 40px;
    border-top: 1px solid #ccc;
}

.tablinks {
    width: 100%;
    text-align: left; /* optional: align text to left */
    padding: 10px 15px; /* adjust padding as needed */
    box-sizing: border-box;
}

.div2 .sidebar {
       display: flex;
       flex-direction: column; /* stack items vertically */
       overflow-y: auto; /* vertical scroll if needed */
       overflow-x: hidden; /* no horizontal scroll */
       width: 250px; /* or your desired width */
}
   

</style>
<div class="parent">
        <div class="div1">
            <div class="header">
                <!--------HEADER 1----------->
                <div class="head-content">
                    <div class="logo"> <!--Logo-->
                        <img src="src/web_logo.png" alt="Web Logo">
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
                            <img src="<?php echo $org_logo; ?>" alt="Org Logo here!">
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
        </div>
        <div class="div2" style="height: calc(100vh - 64px); display: flex; flex-direction: column; gap: 10px; overflow-y: auto; overflow-x:hidden;">
            <aside class="sidebar" aria-label="Sidebar navigation">
                <div style="height: calc(100vh - 64px);">
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

                    <div style="border-top: 1.2px solid #ccc; width: 75%;"></div>

                    <button class="tablinks" onclick="openTab(event, 'Organizations')" aria-label="Members" title="Organizations">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                        <p>User Admin</p>
                    </button>

                    <button class="tablinks" id="confirmButton" aria-label="Logout" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>
                        <p>Logout</p>
                    </button>
                </div>
            </aside>
        </div>
        </div>
        <div class="div3">
            
        </div>
        <div class="div4"></div>
</div>
</body>
</html>