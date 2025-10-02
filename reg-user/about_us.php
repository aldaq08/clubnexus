<?php
require ("database/user-con.php");
?>
<!DOCTYPE html>
<head>
    <title>About Us</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="styles/about_us.css" />
    <link rel="stylesheet" type="text/css" href="styles/search.css" />
    <link rel="stylesheet" type="text/css" href="styles/calendar.css" />
    <link rel="stylesheet" type="text/css" href="styles/notification.css" />
    <link rel="stylesheet" type="text/css" href="styles/sidebar.css"/>


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
    <script src="script/sidebar.js"></script>
    <script src="script/transition-effect.js"></script>
    <script src="script/about-us.js"></script>

    <!--END
     SCRIPTS-->
    <style>
        body {
            width: 100%;
            overflow-x: hidden;
        }

        .header {
        position: sticky;
        top: 0;
        z-index: 100;
        background: white;
        width: 100%;
        /* add box-shadow or border-bottom if you want separation */
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .main-content {
        height: calc(100vh - 120px); /* adjust 120px to your header + any other fixed elements height */
        overflow-y: auto;
        padding: 10px;
        box-sizing: border-box;
        }

        .body-content {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        width: 100%;
        /* no overflow here, scroll is on main-content */
        }
    </style>
        

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
                            <a href="announcement.php">Announcements</a>
                        </li>

                        <li>
                            <a href="about_us.php"><b>About Us</b></a>
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

                        <div class="label">
                                <h2>ABOUT US</h2>
                        </div>
                    </div>  
                        

        </div>


                        
</div>

<div class="page-wrap">    
    <header>
        <div class="logo-container" style="text-align:center; padding-top:10px;">
            <img src="src/silent_breacher.png" alt="Team Logo" style="height:120px; width:120px; border-radius:50%; object-fit:cover; border:4px solid white; box-shadow:0 0 10px rgba(0,0,0,0.1);">
        </div>
        <div class="header-content">
            <h1>SILENT BREACHER</h1>
            <p class="subtitle">Meet the passionate professionals who make everything possible. Click on any team member to learn more about them.</p>
        </div>
    </header>
    
    <div class="team-container">
        <h2 class="team-title">MEMBERS</h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="member-image">
                    <img src="" alt="Adviser Pic">
                </div>
                <div class="member-basic">
                    <h3 class="member-name">Prof. Weena P. Bulnes</h3>
                    <p class="member-role">CAPSONE ADVISER</p>
                </div>
                <div class="member-details">
                    <p class="member-bio">Sarah founded our company in 2010 with a vision to revolutionize the industry. With over 15 years of experience, she leads with passion and strategic insight.</p>
                    <p>Favorite Quote: "Innovation is seeing what everybody has seen and thinking what nobody has thought."</p>
                    <div class="member-social">
                        <a href="https://facebook.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#4267B2">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1DA1F2">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#E1306C">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-image">
                    <img src="" alt="Team Leader/HACKER">
                </div>
                <div class="member-basic">
                    <h3 class="member-name">Jefferson A. Daquiado</h3>
                    <p class="member-role">HACKER</p>
                </div>
                <div class="member-details">
                    <p class="member-bio">David oversees all technological developments and ensures our products remain at the cutting edge. He holds multiple patents in software architecture.</p>
                    <p>Fun Fact: Avid rock climber and coffee enthusiast who can solve a Rubik's cube in under 30 seconds.</p>
                    <div class="member-social">
                        <a href="https://facebook.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#4267B2">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1DA1F2">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#E1306C">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
            </div>
            
            <div class="team-member">
                <div class="member-image">
                    <img src="" alt="Hustler Pic">
                </div>
                <div class="member-basic">
                    <h3 class="member-name">John C. Darato</h3>
                    <p class="member-role">HUSTLER</p>
                </div>
                <div class="member-details">
                    <p class="member-bio">Priya brings operational excellence with her background in management consulting. She ensures all departments work harmoniously towards our goals.</p>
                    <p>Personal Motto: "Efficiency is doing things right; effectiveness is doing the right things."</p>
                    <div class="member-social">
                        <a href="https://facebook.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#4267B2">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1DA1F2">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#E1306C">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-image">
                    <img src="" alt="HIPSTER pic">
                </div>
                <div class="member-basic">
                    <h3 class="member-name">Khent Bryant Alvezo</h3>
                    <p class="member-role">HIPSTER</p>
                </div>
                <div class="member-details">
                    <p class="member-bio">Sarah founded our company in 2010 with a vision to revolutionize the industry. With over 15 years of experience, she leads with passion and strategic insight.</p>
                    <p>Favorite Quote: "Innovation is seeing what everybody has seen and thinking what nobody has thought."</p>
                    <div class="member-social">
                        <a href="https://facebook.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#4267B2">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1DA1F2">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#E1306C">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-image">
                    <img src="" alt="DOCUMENT CONTROLLER Pic">
                </div>
                <div class="member-basic">
                    <h3 class="member-name">Jhonmars M. Añosa</h3>
                    <p class="member-role">DOCUMENT CONTROLLER</p>
                </div>
                <div class="member-details">
                    <p class="member-bio">Sarah founded our company in 2010 with a vision to revolutionize the industry. With over 15 years of experience, she leads with passion and strategic insight.</p>
                    <p>Favorite Quote: "Innovation is seeing what everybody has seen and thinking what nobody has thought."</p>
                    <div class="member-social">
                        <a href="https://facebook.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#4267B2">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1DA1F2">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#E1306C">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        

    </div>
    
</div>




</body>
