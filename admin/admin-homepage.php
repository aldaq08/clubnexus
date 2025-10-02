<?php
 include("database/org-admin.php");
?>

<!DOCTYPE html>
<head>
    <title>Admin Homepage | <?php echo $org_name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">


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
    <script src="script/re-org.js"></script>

    <!--END SCRIPTS-->
<style>
    html, body {
    font-family: "Outfit", sans-serif;
    font-optical-sizing: auto;
    font-weight: 500; /* Changed to a valid weight */
    font-style: normal;
    background-color: white;
    height: 100%;
    width: 100%;
    margin: 0;
    }

    .header {
    position: sticky;
    width: 100%;
    }
    /********************************************************************************************************************

                                                HEAD CONTENT CSS 

    ********************************************************************************************************************/
    .head-content {
    display: grid;
    grid-template-columns: 50% 50%; /* 45% and 55% width columns */
    align-items: center;
    gap: 20px;
    background-color: white;
    margin-left: 20px;
    }

    .content-1 {
    margin-left: 50px;
    }
    .content-1 .org-logo img{
    height: 60px;
    width: auto;
    }

    .content-1 .org-name h1 {
    font-family: 'Fredoka', sans-serif;
    font-size: 24px;
    }
    .logo img {
    max-width: 130px; /* Ensure logo doesn't overflow */
    height: auto;     /* Maintain aspect ratio */
    margin: 10px;
    }


    .nav-panel ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: flex-end;
    height: 40px; /* Set a minimum height for the list */
    min-height: fit-content;
    }

    .nav-panel li {
    margin-right: 10px; /* Use pixels for consistent spacing */
    flex: 1; /* Distribute available space equally */
    min-height: 54px; /* Make each item the same height as the parent */
    display: flex; /* Center the content within the item */
    justify-content: center; /* Center horizontally */
    align-items: center;   /* Center vertically  */
    padding: 5px;

    }

    .nav-panel h3 {
    margin: 0;
    
    }

    .nav-panel a {
    font-size: 1rem;
    text-decoration: none;
    display: block; /* Make the link take the full space of the list item */
    padding: 0 10px; /* Add padding for better visual appearance */
    font-family: "Outfit", sans-serif;
    color: rgb(86, 84, 84);
    }

    .nav-panel li:nth-child(1) {
        border-bottom: 4px solid #0e66f7;
        width: 100%;
    }

    .dropdown-content a {
    font-family: "Outfit", sans-serif;
    font-size: 0.85rem;
    }




    /* Add hover effect */
    .nav-panel li:hover {
    color: #0e66f7;
    font-style: bold;
    background: #f4efefa4;

    }



    .link:hover {
        background-color: #f0f0f0; /* Example hover color */

    }

    /*******************************************************************************************************************

                                                    HEAD CONTENT 2

    *******************************************************************************************************************/
    .head-content2 {
    display: grid;
    grid-template-columns: 50% 50%; /* 45% and 55% width columns */
    align-items: center;
    justify-content: center;
    gap: 20px;
    height: 83px;
    background-color: #0e66f7;
    }

    .content-1 {
    display: flex;
    align-items: center; /* Vertically align items */
    gap: 20px;          /* Add space between elements */
    }

    .content-2 {
    display: flex;
    width: auto;
    gap: 20px;      /* Add space between elements */
    justify-content: end;
    align-items: center;
    padding: 20px;
    margin-right: 20px;
    }

    .content-2 button svg{
    fill: white;
    }

    .cont {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .org-name h1 {
    font-family: 'Outfit', sans-serif;
    color: white;
    }

    aside.sidebar {
        position: sticky;
        top: 175px;
        left: 0;
        bottom: 0;
        width: 8.50rem;
        margin-left: 0;
        min-height: calc(100vh);
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

        #Organizations {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        padding: 16px;
    }


    .sidebar span {
        font-family: 'Outfit', sans-serif;
        font-size: 0.650vw;
        text-align: center;
    }
    aside.sidebar button {
        background: none;
        border: none;
        font-size: 3.125rem;
        color: #666;
        cursor: pointer;
        height: 50px;
        justify-items: center;
        align-items: center;
        width: 60%;
    }
    aside.sidebar button.active {
    background-color: #2a7ad9;
    color: #fff;
    border-radius: 5px;
    transform: scale(1.1);
    transition: 0.3s ease-in;
    }

    aside.sidebar button:focus {
    outline: none;
    }

    aside.sidebar .divider {
        width: 40px;
        border-top: 1px solid #ccc;

    }

    .side-button {
        text-align: center;
    }

    .sidebar p {
        font-family: 'Outfit', sans-serif;
        font-size: 9px;
    }

    /* Main content */
    .main.content {
        flex-grow: 1;
        overflow-x: auto;
    }
    .modal-content .content h2{
    font-weight: bold;
    font-size: 24px;
    margin-bottom: 10%;
    }

    .modal-content .content p{
    margin-bottom: 5%;
    }
    .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
    }
    .modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 300px; /* Could be more or less, depending on screen size */
    border-radius: 8px;
    text-align: right;
    }

    .modal-content .content {
    text-align: center;
    }
    .close-button {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    text-align: right;
    }

    .close-button:hover,
    .close-button:focus {
    color: #005f7f;
    text-decoration: none;
    cursor: pointer; 
    }
    .modal button {
    font-family: 'Fredoka', sans-serif;
    font-size: 15px;
    padding: 10px 15px;
    border: none;
    gap: 20px;
    border-radius: 10px;
    background-color: #2a7ad9;
    color: white;
    cursor: pointer;
    }
    #confirmYes {
    margin-right: 40px;
    }

    #confirmNo {
    margin-left: 40px;
    }



    .modal button:hover {
    background-color: #0452b1;
    transition: 0.3s ease;
    }

    .header .right {
        justify-content: end;
    }


    /* TAB CONTENT SECTION*/
    .main-content {
    z-index: 100;
    margin-left: 9.375rem;
    }



    /*ADD DROPDOWN STYLE*/
        .dropdown {
            position: relative;
            display: inline-block;
            
        }

        .tabcontent {
            z-index: 1000;
        }
        .dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-right: 20px;
        margin-top: 8px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-shadow: 4px 4px 4px 4px rgba(0,0,0,0.15);
        width: 12.5rem;
        padding: 20px;
        display: none;
        z-index: 1050;
        }
        .dropdown-menu button {
        width: 100%;
        padding: 10px 16px;
        background: none;
        border: none;
        text-align: left;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        transition: background-color 0.25s, color 0.25s;
        }
        .dropdown-menu button:hover {
        background-color: #0056f0;
        color: white;
        font-weight: bold;
        }

            .body-content{
            display: inline;
            overflow-y: auto;
            }

            .main-content {
                position: absolute;
                padding:20px;
                display: flex ;
                grid-gap: 10px; /* Spacing between items */
                justify-content: center; /* Center horizontally */
                z-index: 1;
            }

            .body-content{
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                width: 100%;
                margin: 0 auto;
                text-align: left;
                z-index: 1;
                overflow-y: auto;
                min-height: 500px;
                
            }




            /* Media Queries for Responsiveness (adjust breakpoints as needed) */
            @media (max-width: 768px) {
            .main-content {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); /* Adjust column width */
            }
            }
            /* Vertical Tabs Container */
    .applicant-buttons {
        display: flex;
        flex-direction: column;
        width: 220px;
        border-right: 1px solid #ddd;
        padding: 20px 10px;
        background: #fff;
    }

    /* Heading at the top */
    .applicant-buttons h3 {
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 20px;
        color: #222;
    }

    /* Tab items style */
    .applicant-buttons button {
        background: transparent;
        border: none;
        text-align: left;
        padding: 10px 15px;
        font-weight: 600;
        font-size: 0.95rem;
        color: #555;
        cursor: pointer;
        border-left: 4px solid transparent;
        margin-bottom: 10px;
        border-radius: 4px 0 0 4px;
        transition: background-color 0.3s, border-color 0.3s, color 0.3s;
    }

    /* Hover effect */
    .applicant-buttons button:hover {
        background-color: #f0f7ff;
        color: #007bff;
    }

    /* Active tab style from image */
    .applicant-buttons button.active {
        background-color: #d9eaff;
        color: #007bff;
        border-left-color: #007bff;
    }

    #Applicants .tabcontent {
        display: flex;
    }

    .content {
        width: 100%;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-right: 20px;
        margin-top: 8px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-shadow: 4px 4px 4px 4px rgba(0,0,0,0.15);
        width: 15.5rem;
        padding: 20px;
        display: none;
        z-index: 3000; /* Increase z-index to be sure */
    }

    .org-logo img{
        border-radius: 50%;
    }


</style>
        
</head>

<body>

<div id="page-container">
  <!-- Content for the current page -->

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

                        <!---<li>
                            <a href="admin-folder.php">Admin Folder</a>
                        </li>--->

                        <li>
                            <a href="addmin-about_us.php">About Us</a>
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
                                <img src="../reg-user/src/org-logo/<?php echo $org_logo; ?>" alt="Org Logo here!">
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

        <div style="position: fixed; height: calc(100vh - 64px);">
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

                <button class="tablinks" onclick="openTab(event, 'Admin')" aria-label="Members" title="User Admin">
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

<!---CONFIRMATION LOGOUT-->
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
        <div class="tabcontent" id="Organizations"> Applicants
        </div>

        <div class="tabcontent" id="Events" style="display: none;">Welcome to Events!</div>
        <div class="tabcontent" id="Achievements" style="display: none;"> Welcome to About Us Page</div>
        <div class="tabcontent" id="Announcements" style="display: none;">Welcome to Announcements!</div>
        <div class="tabcontent" id="Admin" style="display: none;"> Welcome to Admin Page</div>

</div>

</div>


    </div>
</div>

<script>
    
    /*LOGOUT BUTTON CONFIRMATTION*/
    /*LOGOUT BUTTON CONFIRMATTION*/
    const modal = document.getElementById('confirmationModal');
    const confirmButton = document.getElementById('confirmButton');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');

    confirmButton.addEventListener('click', function() {
        modal.style.display = 'block';
    });


    confirmYes.addEventListener('click', function() {
        window.location.replace('../reg-user/login_form.php');
    });

    confirmNo.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
        modal.style.display = 'none';
        }
    });

    window.history.pushState(null, '', window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, '', window.location.href);
    };

    /*END LOGOUT BUTTON CONFIRMATTION*/
    /*END LOGOUT BUTTON CONFIRMATTION*/



    /*TAB ACTIVE BUTTON INDICATION*/ 
    function openTab(evt, tabName) {
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

    // Show the selected tab content with appropriate display style
    switch(tabName) {
        case "Organizations":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "Events":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "Achievements":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "Announcements":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "Admin":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "About Us":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "tab7":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "tab8":
        document.getElementById(tabName).style.display = "contents";
        break;
        default:
        document.getElementById(tabName).style.display = "flex"; // fallback
    }

    // Add "active" class to the clicked button only
    evt.currentTarget.classList.add("active");
    }


    /*ADD BTN DROPDOWN FUNCTIONALITY*/
    // Dropdown functionality
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

    // Close dropdown when clicking outside
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
</script>

</body>

</html>
