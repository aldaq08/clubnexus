<?php
 include("database/org-admin.php");
?>

<!DOCTYPE html>
<head>
    <title>Admin Membership | <?php echo $org_name; ?></title>
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
    overflow: hidden; /* Keep this, but dropdown will extend beyond due to absolute positioning */
    }
    .header {
        position: sticky;
        width: 100%;
        z-index: 1; /* Increased: Above main content (z-index 100/1000) but below dropdown */
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
        border-radius:50%;
        border-color: 4px solid #ccc;
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

        .nav-panel li:nth-child(2) {
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
            position: relative; /* Already present, but ensure stacking context */
            z-index: 3000; /* New: Match header to keep dropdown parent high in stack */
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
        z-index: 1000;
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
        .main-content {
            z-index: 100; /* Keep low to stay behind header/dropdown */
            margin-left: 9.375rem;
            position: absolute; /* Already absolute */
            padding: 20px;
            display: flex;
            grid-gap: 10px; /* Spacing between items */
            justify-content: center; /* Center horizontally */
        }
        .modal-content .content3 h2{
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
                z-index: 2000; /* Increased slightly from 1000 to be above main content but below dropdown */
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

        .modal-content .content3 {
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



        /*ADD DROPDOWN STYLE*/
    /* ADD DROPDOWN STYLE */
            .dropdown {
                position: relative;
                display: inline-block;
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
                z-index: 9999; /* Increased to highest: Ensures it's on top of everything (modals, tabs, sidebar) */
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
                    grid-template-columns: 1fr;
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
            min-width: 800px;
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
            z-index: 3000; /* Increase z-index to be sure */
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
            z-index: 50; /* Increased from 20: Above main content but below dropdown/header */
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
                            <a href="admin-homepage.php">Home</a> 
                        </li>

                        <li>
                            <a href="admin-membership.php"><b>Membership</b></a>
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

                        <div class="dropdown-menu" id="dropdownMenu" role="menu" aria-label="Add dropdown menu" style="z-index: 1000;">
                            <button type="button" role="menuitem" id="createPostBtn">Add Member</button>
                            <button type="button" role="menuitem" id="createEventBtn">Add Adviser</button>
                            <button type="button" role="menuitem" id="createAnnouncementBtn">Add Officer</button>
                        </div>
        </div>




</div>

    <div style="position: fixed; height: calc(100vh - 64px);">
            <aside class="sidebar" aria-label="Sidebar navigation">

                <button class="tablinks active" onclick="openTab(event, 'Applicants')" aria-label="Members" title="Org Profile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                    </svg>
                <p>Membership</p>
                </button>

                <button class="tablinks" onclick="openTab(event, 'Officers')" aria-label="Officers" title="Officers">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
                        <path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                        <path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.4 5.4 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2z"/>
                        </svg>
                    <p>Officers</p>
                </button>

                <button class="tablinks" onclick="openTab(event, 'Founders')" aria-label="Founders" title="Founders">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                        <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                <p>Founding Members</p>
                </button>

                <div style="border-top: 1.2px solid #ccc; width: 75%;" ></div>

                <button class="tablinks" onclick="openTab(event, 'Admin')" aria-label="Members" title="Organizations">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                    <p>User Admin</p>
                </button>

                <button class="tab" id="confirmButton" aria-label="Logout" title="Logout" >
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="27" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                
                </svg>
                <p>Logout</p>
                </button>

            </aside>
        </div>

<!---CONFIRMATION LOGOUT-->
<div id="confirmationModal" class="modal">
        
        <div class="modal-content">
            <div class="content3">
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
<div class="tabcontent" id="Applicants">
 <?php include("database/membership-con.php");?>
    <style>
        /* Layout */
        .tabcontent {
            display: flex;
            gap: 30px; /* space between sidebar and content */
            padding: 20px 0;
            box-sizing: border-box;
            z-index: 100;
        }

        /* Sidebar fixed width */
        .mem-sidebar {
            flex: 0 0 220px; /* fixed width */
            background: #fff;
            box-shadow: 1px 0 5px rgba(0,0,0,0.1);
            padding-top: 20px;
            box-sizing: border-box;
            height: calc(100vh);
            margin: 0;
            min-width: 200px;
        }

        /* Content takes remaining space */
        .content {
            flex: 1 1 auto;
            padding: 0 10px;
            overflow-y: auto;
        }

        /* Members section full width */
        .members-section {
            width: 100%;
        }

        /* Sidebar styles */
        .mem-sidebar h2 {
            font-weight: 700;
            font-size: 18px;
            padding: 0 20px 15px;
            border-bottom: 2px solid #2980b9;
            margin-bottom: 20px;
            color: #222;
        }
        .mem-sidebar ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        .mem-sidebar ul li {
            cursor: pointer;
            padding: 12px 20px;
            font-weight: 600;
            color: #555;
            border-left: 4px solid transparent;
            transition: background 0.3s, color 0.3s;
        }
        .mem-sidebar ul li:hover {
            background: #e8f3fe;
            color: #2980b9;
        }
        .mem-sidebar ul li.active,
        .mem-sidebar ul li.active:hover {
            background: #d7eaff;
            color: #007bff;
            border-left: 4px solid #007bff;
            font-weight: 700;
        }

        /* Members grid */
        .org-chart-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 20px 30px; /* row gap 20px, column gap 30px */
            justify-items: center; /* center each officer block horizontally */
            padding: 10px 0;
            font-family: 'Outfit', sans-serif;
            min-width: 1000px;
            overflow-y: auto;
        }
        .officer {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 190px;
        }
        .officer img {
            display: inline-flex;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ccc; /* fallback */
            margin-bottom: 1px;
            object-fit: cover;
        }
        .officer strong {
            display: block;
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 14px;
            color: #111;
        }
        .officer small {
            font-size: 12px;
            color: #555;
        }

        /* Modal action buttons */
        #modalActionButtons button {
            margin: 0 10px;
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }
        #modalActionButtons button.approve {
            background-color: #28a745;
            color: white;
        }
        #modalActionButtons button.disapprove {
            background-color: #dc3545;
            color: white;
        }

        #filesModal {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.6);
            display: none; /* toggled to flex */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        #modalActionButtons button {
            outline-offset: 0;
            outline: none;
            box-sizing: border-box;
            /* Optional: add consistent border to avoid size change */
            border: 2px solid transparent;
        }

        #modalActionButtons button:focus {
            outline: none;
            border-color: #0056b3; /* or your focus color */
        }

        .officer button.remove-btn {
            margin-top: 8px;
            padding: 6px 12px;
            font-size: 13px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .officer button.remove-btn:hover {
            background-color: #c0392b;
        }

        #modalFileList > div {
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            background: #fafafa;
        }
        #modalFileList h5 {
            font-weight: 600;
            color: #2980b9;
        }
        #modalFileList {
            max-height: 60vh;
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* IE and Edge */
        }

        #modalFileList::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        #modalFileList > div {
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            background: #fafafa;
        }

        #modalFileList h5 {
            font-weight: 600;
            color: #2980b9;
        }

        #modalFilesSection img {
            width: 50%;
            height: auto;

        }

    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Changed selector from '.sidebar ul li' to '.mem-sidebar ul li'
            const sidebarItems = document.querySelectorAll('.mem-sidebar ul li');
            const sectionIDs = ['approve', 'pending', 'denied'];
            let activeSection = 'approve';  // default active section

            function showSection(section) {
                sidebarItems.forEach(li => {
                    li.classList.toggle('active', li.dataset.section === section);
                });
                sectionIDs.forEach(id => {
                    const el = document.getElementById(id);
                    if(el) {
                        el.style.display = (id === section) ? 'grid' : 'none';
                    }
                });
            }

            sidebarItems.forEach(item => {
                item.addEventListener('click', () => {
                    showSection(item.dataset.section);
                });
            });

            showSection(activeSection);
        });

            function showFilesModal(element) {
                const filesJson = element.getAttribute('data-files') || '{}';
                const memberName = element.getAttribute('data-name') || '';
                const memberCourseCode = element.getAttribute('data-course') || '';
                const memberPhotoFileName = element.getAttribute('data-photo') || '';
                const memberId = element.getAttribute('data-id');
                const memberStatus = element.getAttribute('data-status') || '';
                const studentDescription = element.getAttribute('data-description') || '';
                const files = JSON.parse(filesJson);

                const modal = document.getElementById('filesModal');
                const modalName = document.getElementById('modalMemberName');
                const modalCourse = document.getElementById('modalMemberCourse');
                const modalPhoto = document.getElementById('modalPhoto');
                const modalDescription = document.getElementById('modalDescription');
                const modalList = document.getElementById('modalFileList');
                const modalButtons = document.getElementById('modalActionButtons');

                const courseMap = {
                    'bsit': 'Bachelor of Science in Information Technology',
                    'bsed': 'Bachelor of Science in Secondary Education',
                    'beed': 'Bachelor of Elementary Education',
                    'bshm': 'Bachelor of Science in Hospitality Management',
                    'bsoa': 'Bachelor of Science in Office Administration',
                    'bsa': 'Bachelor of Science in Agriculture'
                };

                modalName.textContent = memberName;
                modalCourse.textContent = courseMap[memberCourseCode.toLowerCase()] || 'Course not specified';
                modalPhoto.src = "../reg-user/src/membership/" + memberPhotoFileName;
                modalPhoto.alt = memberName + ' Photo';
                modalDescription.textContent = studentDescription || 'No description provided.';

                modalList.innerHTML = '';
                modalButtons.innerHTML = '';

                if (Object.keys(files).length === 0) {
                    modalList.innerHTML = '<p>No files uploaded.</p>';
                } else {
                    const fileOrder = ['Entry 1', 'Entry 2', 'Entry 3', 'RF'];
                    fileOrder.forEach(label => {
                        const fileName = files[label];
                        if (!fileName) return;

                        const filePath = "../reg-user/src/membership/" + fileName;

                        // Create download button
                        const btn = document.createElement('a');
                        btn.href = filePath;
                        btn.download = fileName;
                        btn.target = '_blank';
                        btn.rel = 'noopener noreferrer';
                        btn.textContent = `Download ${label}`;
                        btn.style.cssText = `
                            display: inline-block;
                            padding: 10px 18px;
                            background-color: #007bff;
                            color: white;
                            border-radius: 6px;
                            text-decoration: none;
                            font-weight: 600;
                            text-align: center;
                            transition: background-color 0.3s;
                            width: 100%;
                            box-sizing: border-box;
                        `;
                        btn.addEventListener('mouseover', () => btn.style.backgroundColor = '#0056b3');
                        btn.addEventListener('mouseout', () => btn.style.backgroundColor = '#007bff');

                        modalList.appendChild(btn);
                    });
                }

                // Action buttons based on status
                if (memberStatus === 'pending') {
                    modalButtons.innerHTML = `
                        <button class="approve" onclick="approveMember(${memberId})">Approve</button>
                        <button class="disapprove" onclick="disapproveMember(${memberId})">Disapprove</button>
                    `;
                } else if (memberStatus === 'denied') {
                    modalButtons.innerHTML = `
                        <button class="approve" onclick="approveMember(${memberId})">Approve</button>
                        <button class="remove-btn" onclick="removeMember(${memberId})" style="background-color:#e74c3c; color:#fff; border:none; padding:8px 16px; border-radius:4px; cursor:pointer; font-weight:600;">Remove</button>
                    `;
                } else if (memberStatus === 'approve' || memberStatus === 'approved') {
                    modalButtons.innerHTML = `
                        <button class="remove-btn" onclick="removeMember(${memberId})" style="background-color:#e74c3c; color:#fff; border:none; padding:8px 16px; border-radius:4px; cursor:pointer; font-weight:600;">Remove</button>
                        <button class="disapprove" onclick="disapproveMember(${memberId})">Disapprove</button>
                    `;
                }

                modal.style.display = 'flex';
            }

                function closeFilesModal() {
                    document.getElementById('filesModal').style.display = 'none';
                }

                // Optional: close modal on clicking outside content
                document.getElementById('filesModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeFilesModal();
                    }
                });

                const courseMap = {
                    'bsit': 'Bachelor of Science in Information Technology',
                    'bsed': 'Bachelor of Science in Secondary Education',
                    'beed': 'Bachelor of Elementary Education',
                    'bshm': 'Bachelor of Science in Hospitality Management',
                    'bsoa': 'Bachelor of Science in Office Administration',
                    'bsa': 'Bachelor of Science in Agriculture'
                };


                // Example approve/disapprove functions - replace with your AJAX or form logic
                function updateMemberStatus(memberId, status) {
                        fetch('update_member_status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `membership_id=${encodeURIComponent(memberId)}&status=${encodeURIComponent(status)}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(`Member status updated to "${status}".`);
                                closeFilesModal();
                                // Optionally, refresh the page or update UI dynamically
                                location.reload(); // simple way to refresh and show updated lists
                            } else {
                                alert('Failed to update status: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(() => {
                            alert('Network error while updating status.');
                        });
                    }

            function approveMember(memberId) {
                updateMemberStatus(memberId, 'approve');
            }

            function disapproveMember(memberId) {
                updateMemberStatus(memberId, 'denied');
            }

            function removeMember(memberId) {
                if (!confirm('Are you sure you want to remove this member?')) {
                    return;
                }

                fetch('update_member_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `membership_id=${encodeURIComponent(memberId)}&action=delete`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Member removed successfully.');
                        location.reload();
                    } else {
                        alert('Failed to remove member: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(() => {
                    alert('Network error while removing member.');
                });
            }

            const modalFileList = document.getElementById('modalFileList');
                modalFileList.innerHTML = ''; // clear previous

                const files = {
                'Entry 1': 'entry1.pdf',
                'Entry 2': 'entry2.pdf',
                'Entry 3': 'entry3.pdf',
                'RF': 'rf.pdf'
                };

                Object.entries(files).forEach(([label, filename]) => {
                if (filename && filename.trim() !== '') {
                    const filePath = "../reg-user/src/membership/" + filename;

                    const btn = document.createElement('a');
                    btn.href = filePath;
                    btn.download = filename;
                    btn.target = '_blank';
                    btn.rel = 'noopener noreferrer';
                    btn.textContent = `Download ${label}`;
                    btn.style.cssText = `
                    display: inline-block;
                    padding: 10px 18px;
                    background-color: #007bff;
                    color: white;
                    border-radius: 6px;
                    text-decoration: none;
                    font-weight: 600;
                    text-align: center;
                    transition: background-color 0.3s;
                    `;
                    btn.addEventListener('mouseover', () => btn.style.backgroundColor = '#0056b3');
                    btn.addEventListener('mouseout', () => btn.style.backgroundColor = '#007bff');

                    modalFileList.appendChild(btn);
                }
                });



    </script>

    <div class="mem-sidebar">
        <h2>Membership Status</h2>
        <ul>
            <li data-section="approve" class="active">Approved Members</li>
            <li data-section="pending">Applying Members</li>
            <li data-section="denied">DENIED</li>
        </ul>
    </div>

    <div class="content">
        <div id="approve" class="members-section" style="display:none;">
            <?php renderMembersSection($members['approve'], 'approve'); ?>
        </div>

        <div id="pending" class="members-section" style="display:none;">
            <?php renderMembersSection($members['pending'], 'pending'); ?>
        </div>

        <div id="denied" class="members-section">
            <?php renderMembersSection($members['denied'], 'denied'); ?>
        </div>
    </div>

    <?php $conn->close(); ?>
</div>

        <div class="tabcontent" id="Officers" style="display: none;">Welcome to Officers!</div>
        <div class="tabcontent" id="Founders" style="display: none;"> Welcome to Founding Members</div>
        <div class="tabcontent" id="Admin" style="display: none;"> Welcome to Admin Page</div>

</div>


        <!-- Modal -->
<div id="filesModal" style="
    display:none; 
    position:fixed; 
    top:0; left:0; 
    width:100vw; height:100vh; 
    background:rgba(0,0,0,0.6); 
    justify-content:center; 
    align-items:center; 
    z-index:2000;
">
    <div style="
        background:#fff; 
        padding:30px 40px; 
        border-radius:8px; 
        max-width:600px; 
        width:90vw;
        min-height: 500px; 
        position:relative; 
        text-align:center; 
        display: flex;
        flex-direction: column;
        align-items: center;
    ">
        <!-- Close button -->
        <button onclick="closeFilesModal()" 
            style="
                position:absolute; 
                top:15px; 
                right:15px; 
                background:none; 
                border:none; 
                font-size:28px; 
                cursor:pointer; 
                color:#555;
                transition: color 0.3s;
            "
            onmouseover="this.style.color='#2980b9'"
            onmouseout="this.style.color='#555'"
            aria-label="Close modal"
        >&times;</button>

        <!-- Profile Picture -->
        <img id="modalPhoto" src="" alt="Applicant Photo" 
            style="
                width:120px; 
                height:120px; 
                border-radius:50%; 
                object-fit:cover; 
                border: 3px solid #2980b9; 
                margin-bottom: 15px;
            "
        />

        <!-- Member Name -->
        <h3 id="modalMemberName" style="
            margin: 0 0 8px 0; 
            font-weight:700; 
            font-size:22px; 
            color:#222;
        "></h3>

        <!-- Member Course -->
        <div id="modalMemberCourse" style="
            font-size: 14px; 
            color: #555; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #2980b9; 
            padding-bottom: 8px;
            width: 100%;
            max-width: 400px;
        "></div>

        <!-- Student Description -->
        <div id="modalDescription" style="font-size: 15px; color: #333; margin: 20px 0; padding: 15px 20px; border: 1px solid #ccc; border-radius: 6px; text-align: left; white-space: pre-wrap; max-height: 150px; overflow-y: auto; width: 100%; max-width: 400px;"></div>

        <!-- Uploaded Files Section -->
        <div id="modalFilesSection" style="text-align:left; margin-top: 15px; width: 100%; max-width: 400px;">
            <h4 style="border-bottom:1px solid #ccc; padding-bottom:5px; margin-bottom:15px; color: #2980b9; font-weight: 600;">Uploaded Files</h4>
            <div id="modalFileList" style="
                display:flex; 
                flex-direction: column; 
                gap: 12px;
            "></div>
        </div>

        <!-- Action Buttons -->
        <div id="modalActionButtons" style="
            margin-top: 30px; 
            text-align:center; 
            width: 100%;
            max-width: 400px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        ">
            <!-- Buttons inserted dynamically -->
        </div>
    </div>
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
        case "Applicants":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "Adviser":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "Officers":
        document.getElementById(tabName).style.display = "flex";
        break;
        case "Applicants":
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

    // Prevent dropdown from closing when clicking inside
    document.getElementById('createPostBtn').addEventListener('click', () => {
        window.location.href = 'form/create-member.php';
    });
    document.getElementById('createEventBtn').addEventListener('click', () => {
        window.location.href = 'form/create-adviser.php';
    });
    document.getElementById('createAnnouncementBtn').addEventListener('click', () => {
        window.location.href = 'form/create-officer.php';
    });
</script>

</body>

</html>
