<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" type="image/c-icon" href="src/clubnexusicon.ico">
<link rel="stylesheet" type="text/css" href="styles/home.css">
<link rel="stylesheet" type="text/css" href="styles/about_us.css" />

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* Your provided CSS styles */
        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background: #fff;
            color: #000;
            height: calc(100vh);
        }
        /* Log Out Confirmation Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 10001; /* High z-index to appear over other modals */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-content h2 {
            margin-top: 0;
            color: #333;
        }

        .modal-content-wrapper img{
            min-height: 300px;
            width: auto;
        }

        .modal-content button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        #confirmYes {
            background-color: #dc3545;
            color: white;
        }

        #confirmYes:hover {
            background-color: #c82333;
        }

        #confirmNo {
            background-color: #6c757d;
            color: white;
        }

        #confirmNo:hover {
            background-color: #5a6268;
        }

        .main-content {
            width: 100%;
            margin-top: 60px;
        }

        .main-content .tabcontent{
            display:none;
        }

        #Organizations {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 16px;
            min-height:300px;
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

        .image-container h4 {
          font-size: clamp(1rem, 5vw, 3rem);
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
            font-size: min(0.9rem, 4vw);
            font-size: max(auto);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            margin: 0; padding: 0;
            overflow-y: auto;
        }
        .container-flex {
            display: flex;
            min-height: auto;
        }
        
        aside.org-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            width: auto;
            background: #fff;
            border-right: 1px solid #ddd;
            padding: 20px;
            padding-top:0;
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

        .stream {
            width: 100%;
            margin: 0 auto ;
            /* centers the container horizontally */
            padding-left: 200px;
            box-sizing: border-box;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center; /* center children horizontally */
        }

        .post-card {
            width: 720px; /* fill parent container */
            max-width: 700px; /* optional max width for readability */
            margin: 0 auto 20px auto;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
            box-sizing: border-box;
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

        .post-card h2 {
            text-align: center;
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
            width: 100%; /* full width of container */
            max-width: 100%; /* no max width restriction */
            grid-auto-rows: auto;
        }

        /* Adjust grid columns based on image count */
        .post-images-container.one-image, .post-images-container.one-image img{
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
            width: 100%;      /* fill the grid cell width */
            height: auto;     /* auto height to keep aspect ratio */
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            display: block;
            max-height: 250px; /* optional max height */
        }

        /* Overlay styling remains the same */
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
            position: static;
            padding: 0;
            background: none;
            font-size: 1.2rem;
            font-weight: bold;
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
            font-family:'Outfit', sans-serif;
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
        /* Hide posts by default, show filtered */
        .post-card.hidden {
            display: none !important;
        }

        /* Modal styles */
        #imageModal2 {
            display: none;
            position: fixed; /* important for overlay */
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            user-select: none;

            display: none; /* flex container */
            justify-content: center;
            align-items: center;
            overflow: hidden; /* prevent scrollbars */
            flex-direction: column;
            padding: 0; /* remove padding */
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
            min-height: 300px;
            border-radius: 8px;
            user-select: none;
        }

        #modalCaption {
            color: #fff;
            text-align: center;
            margin-top: 10px;
            font-size: 1rem;
            user-select: none;
        }



        #imageModal2 img {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 80vh;
            border-radius: 8px;
        }
        #imageModal2 .close, #imageModal2 .prev, #imageModal2 .next {
            position: absolute;
            width: auto;
            height:auto;
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
            display: flex; /* default visible */
            cursor: pointer;
            align-items: center;
            justify-content: center;
            user-select: none;
            z-index: 10000;
        }


        .container-flex aside{
            position:fixed;
            margin-top: 90px;
        }

        #Applicants .post-card {
            position: relative;
            padding-left:90px;
        }

        /* Container for announcements section */
        #announcementStream {
            width: 100%;
            margin: 0 auto;
            /* Padding will be 20% of the parent's width */
            box-sizing: border-box;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Sidebar for announcement status */
        aside.org-sidebar[aria-label="Announcements"] {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            width: auto;
            background: #fff;
            border-right: 1px solid #ddd;
            padding-right: 20px;
            padding-top: 0;
            box-sizing: border-box;
        }

        aside.org-sidebar[aria-label="Announcement status sidebar"] h2 {
            margin-top: 0;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        /* Status list styling */
        #announcementStatusList {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #announcementStatusList li {
            padding: 10px 15px;
            margin-bottom: 8px;
            cursor: pointer;
            border-radius: 4px;
            color: #555;
            user-select: none;
            border-left: 4px solid transparent;
            transition: background-color 0.2s, border-color 0.2s, color 0.2s;
        }

        #announcementStatusList li.active {
            background-color: #e0f0ff;
            border-left-color: #007bff;
            color: #007bff;
            font-weight: bold;
        }

        #announcementStatusList li:hover:not(.active) {
            background-color: #f0f8ff;
        }

        /* Announcement cards */
        #announcementStream .post-card {
            min-width: 720px;
            max-width: 700px;
            margin: 0 auto 20px auto;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
            box-sizing: border-box;
            transition: box-shadow 0.3s ease;
        }

        #announcementStream .post-card:hover {
            box-shadow: 0 6px 15px rgb(0 0 0 / 0.15);
        }

        /* Announcement header */
        #announcementStream .post-header {
            margin-bottom: 12px;
        }

        #announcementStream .post-header h2 {
            margin: 0 0 6px 0;
            font-size: 1.4rem;
            color: #222;
        }

        #announcementStream .post-time {
            font-size: 0.85rem;
            color: #666;
        }

        /* Announcement body */
        #announcementStream .post-body p {
            margin: 0 0 12px 0;
            color: #444;
            white-space: pre-wrap;
        }

        /* Images container */
        #announcementStream .post-images-container {
            display: grid;
            gap: 8px;
            margin-bottom: 12px;
            width: 100%;
            max-width: 100%;
            grid-auto-rows: auto;
        }

        /* Grid columns based on image count */
        #announcementStream .post-images-container.one-image {
            grid-template-columns: 1fr;
        }

        #announcementStream .post-images-container.two-images {
            grid-template-columns: repeat(2, 1fr);
        }

        #announcementStream .post-images-container.three-images {
            grid-template-columns: repeat(3, 1fr);
        }

        #announcementStream .post-images-container.four-or-more {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, auto);
        }

        /* Images and overlays */
        #announcementStream .post-images-container img.post-image,
        #announcementStream .post-images-container .image-overlay {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            display: block;
            max-height: 300px;
        }

        /* Overlay text */
        #announcementStream .image-overlay {
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

        #announcementStream .image-overlay .overlay-text {
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

        /* Status labels */
        #announcementStream .status-label {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff;
        }

        #announcementStream .status-posted {
            background-color: #28a745;
        }

        #announcementStream .status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        #announcementStream .status-denied {
            background-color: #dc3545;
        }

        /* Post actions buttons */
        #announcementStream .post-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        #announcementStream .post-actions button {
            font-family: 'Outfit', sans-serif;
            padding: 8px 0;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            color: #fff;
            font-size: 0.9rem;
            width: 200px;
            transition: background-color 0.3s ease;
        }

        #announcementStream .approve-btn {
            background-color: #0477eaff;
        }

        #announcementStream .approve-btn:hover {
            background-color: #095cafff;
        }

        #announcementStream .disapprove-btn {
            background-color: #dc3545;
        }

        #announcementStream .disapprove-btn:hover {
            background-color: #c82333;
        }

        /* Hide announcements by default, show filtered */
        #announcementStream .post-card.hidden {
            display: none !important;
        }

        /* No announcements placeholder */
        #announcementStream .no-posts-placeholder {
            text-align: center;
            margin-top: 40px;
            padding-left: 300px;
        }

        #announcementStream .no-posts-placeholder img {
            max-width: 300px;
            width: 100%;
            height: auto;
        }

        /* Applicant Stream Styles */
        #applicantStream {
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-left: 200px; /* Match sidebar width */
        }

        #applicantStream .applicant-card {
            width: 720px;
            max-width: 700px;
            margin: 0 auto 20px auto;
            padding: 15px;
            background: #e1eaf1ff; /* Matches organization card color */
            border-radius: 10px;
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer; /* Clickable for details */
            display: flex;
            flex-direction: column;
            min-height: 200px;
            justify-content: space-between;
        }

        #applicantStream .applicant-card:hover {
            box-shadow: 0 6px 15px rgb(0 0 0 / 0.15);
            transform: scale(1.01);
            border: 2px solid gray;
        }

        .applicant-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .applicant-logo {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }

        .applicant-logo-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e6f0fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: #666;
            margin-right: 12px;
        }

        .applicant-header h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #222;
            font-weight: bold;
        }

        .applicant-time {
            font-size: 0.85rem;
            color: #666;
            display: block;
        }

        .applicant-body p {
            margin: 0 0 12px 0;
            color: #444;
            white-space: pre-wrap;
        }

        .applicant-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .applicant-actions {
            display: flex;
            gap: 10px;
        }

        .applicant-actions button {
            font-family: 'Outfit', sans-serif;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            color: #fff;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
            width: auto;
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

        /* Status Labels */
        .status-label {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff;
        }

        .status-approved {
            background-color: #28a745;
        }

        .status-applying {
            background-color: #ffc107;
            color: #212529;
        }

        .status-denied {
            background-color: #dc3545;
        }

        /* Hide cards by default for filtering */
        .applicant-card.hidden {
            display: none !important;
        }

        /* No applicants placeholder */
        .no-applicants-placeholder {
            text-align: center;
            margin-top: 40px;
            padding-left: 300px;
        }

        .no-applicants-placeholder img {
            max-width: 300px;
            width: 100%;
            height: auto;
        }

        /* Sidebar for Applicants (reuse org-sidebar but scoped) */
        aside.org-sidebar[aria-label="Applicant status sidebar"] h2 {
            margin-top: 0;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        #applicantStatusList {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #applicantStatusList li {
            padding: 10px 15px;
            margin-bottom: 8px;
            cursor: pointer;
            border-radius: 4px;
            color: #555;
            user-select: none;
            border-left: 4px solid transparent;
            transition: background-color 0.2s, border-color 0.2s, color 0.2s;
        }

        #applicantStatusList li.active {
            background-color: #e0f0ff;
            border-left-color: #007bff;
            color: #007bff;
            font-weight: bold;
        }

        #applicantStatusList li:hover:not(.active) {
            background-color: #f0f8ff;
        }

        /* Applicant Modal Styles */
        .applicant-modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .applicant-modal[aria-hidden="false"] {
            display: flex;
        }

        .applicant-modal-content {
            background-color: #fff;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .applicant-close {
            position: absolute;
            right: 15px;
            top: 15px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
            z-index: 1001;
            transition: color 0.2s ease;
        }

        .applicant-close:hover,
        .applicant-close:focus {
            color: #000;
            text-decoration: none;
        }

        .applicant-close:focus {
            outline: 2px solid #007bff;
            outline-offset: 2px;
            border-radius: 50%;
        }

        /* Modal Body Content */
        #applicantModalBody {
            text-align: center;
            margin-top: 20px;
        }

        .modal-logo {
            margin-bottom: 20px;
        }

        .modal-logo img {
            border: 3px solid #ddd;
            transition: border-color 0.2s ease;
        }

        .modal-logo img:hover {
            border-color: #007bff;
        }

        .modal-name {
            font-size: 24px;
            margin: 0 0 20px 0;
            color: #333;
            font-weight: bold;
        }

        .modal-category,
        .modal-description {
            text-align: left;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            border-radius: 5px;
        }

        .modal-category p,
        .modal-description p {
            margin: 0;
            color: #555;
            line-height: 1.5;
        }

        .modal-category strong,
        .modal-description strong {
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .modal-attachment {
            margin-top: 20px;
            text-align: center;
        }

        .modal-attachment a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.2s ease;
            font-weight: 500;
        }

        .modal-attachment a:hover,
        .modal-attachment a:focus {
            background-color: #0056b3;
            text-decoration: none;
            outline: none;
        }

        .modal-attachment a:focus {
            outline: 2px solid #fff;
            outline-offset: 2px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .applicant-modal {
                padding: 10px;
            }

            .applicant-modal-content {
                width: 95%;
                padding: 20px;
                max-height: 85vh;
            }

            .modal-name {
                font-size: 20px;
            }

            .modal-category,
            .modal-description {
                padding: 8px;
            }

            .applicant-close {
                font-size: 24px;
                right: 10px;
                top: 10px;
            }
        }

        @media (max-width: 480px) {
            .applicant-modal-content {
                padding: 15px;
            }

            .modal-logo {
                width: 80px;
                height: 80px;
            }

            .modal-logo img {
                width: 80px;
                height: 80px;
            }
        }

        /* Accessibility Improvements */
        .applicant-modal[aria-hidden="true"] .applicant-modal-content {
            display: none;
        }

        .applicant-modal:focus-within .applicant-modal-content {
            outline: none;
        }

        /* Ensure body scroll is prevented when modal is open */
        body.modal-open {
            overflow: hidden;
        }


        #applicantModalBody p {
            margin-bottom: 15px;
            color: #444;
            line-height: 1.5;
        }

        /* Activity Section Styles */
        .activity-section {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
        }

        .activity-label {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff;
        }

        .activity-active {
            background-color: #28a745; /* Green for active */
        }

        .activity-inactive {
            background-color: #dc3545; /* Red for inactive */
        }

        .activity-actions {
            display: flex;
            gap: 5px;
        }

        .activity-actions button {
            font-family: 'Outfit', sans-serif;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            color: #fff;
            font-size: 0.8rem;
            transition: background-color 0.3s ease;
        }

        .activate-btn {
            background-color: #28a745; /* Green */
        }

        .activate-btn:hover {
            background-color: #218838;
        }

        .deactivate-btn {
            background-color: #ffc107; /* Orange/Yellow for deactivate */
            color: #212529;
        }

        .deactivate-btn:hover {
            background-color: #e0a800;
        }

        /* Adjust footer for multiple labels/buttons */
        .applicant-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .active-status-label { 
            margin-left: 10px; 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 0.8em; 
        }

        .status-active { 
            background: #4CAF50; 
            color: white;
            text-align: left;

        }

        .status-inactive { 
            background: #f44336; 
            color: white; 
            text-align: left;
        }

        .org-status-actions { 
            margin-left: 10px; 
        }

        .org-status-actions button { 
            padding: 5px 10px; 
            background: #2196F3; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-family: 'Outfit', sans-serif;
        }

        .org-status-actions button .deactivate-btn  { 
            padding: 5px 10px; 
            background: #f34421ff; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-family: 'Outfit', sans-serif;
        }

        .deactivate-btn { 
            background: #f44336; 
        }

        /* Additional styles for events */
        .event-time-location {
            margin-bottom: 10px;
            color: #666;
            font-size: 0.9rem;
        }

        .event-time-location strong {
            color: #333;
        }

        .status-upcoming {
            background-color: #17a2b8;
        }

        .status-past {
            background-color: #6c757d;
        }

        #Events .container-flex {
          display: flex;
          gap: 20px;
          margin-top: 10px;
        }

        #Events .org-sidebar {
          width: 200px;
          background-color: #f9f9f9;
          border-radius: 8px;
          padding: 15px;
          box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        #Events .org-sidebar h2 {
          font-size: 1.25rem;
          margin-bottom: 15px;
          color: #333;
        }

        #Events .org-sidebar ul {
          list-style: none;
          padding: 0;
          margin: 0;
        }

        #Events .org-sidebar ul li {
          padding: 10px 12px;
          margin-bottom: 8px;
          background-color: #eaeaea;
          border-radius: 6px;
          cursor: pointer;
          user-select: none;
          font-weight: 600;
          color: #555;
          transition: background-color 0.3s, color 0.3s;
        }

        #Events .org-sidebar ul li.active,
        #Events .org-sidebar ul li:hover,
        #Events .org-sidebar ul li:focus {
          background-color: #007bff;
          color: white;
          outline: none;
        }

        #Events .applicant-stream {
          flex: 1;
          padding-left: 220px; /* space for sidebar */
          display: flex;
          flex-wrap: wrap;
          gap: 20px;
        }

        #Events .applicant-card {
          background-color: white;
          border-radius: 10px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
          width: 320px;
          display: flex;
          flex-direction: column;
          overflow: hidden;
          transition: transform 0.2s ease;
          cursor: pointer;
        }

        #Events .applicant-card:hover {
          transform: translateY(-5px);
        }

        #Events .applicant-header {
          display: flex;
          gap: 15px;
          padding: 15px;
          align-items: center;
          border-bottom: 1px solid #eee;
        }

        #Events .applicant-logo {
          width: 80px;
          height: 80px;
          object-fit: cover;
          border-radius: 10px;
          background-color: #ddd;
        }

        #Events .applicant-logo-placeholder {
          width: 80px;
          height: 80px;
          background-color: #ccc;
          border-radius: 10px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: #777;
          font-size: 0.9rem;
          font-weight: 600;
        }

        #Events .applicant-header h3 {
          margin: 0;
          font-size: 1.2rem;
          color: #222;
        }

        #Events .applicant-time,
        #Events .event-location {
          display: block;
          font-size: 0.85rem;
          color: #666;
          margin-top: 4px;
        }

        #Events .applicant-body {
          padding: 15px;
          font-size: 0.95rem;
          color: #444;
          flex-grow: 1;
        }

        #Events .applicant-footer {
          padding: 15px;
          border-top: 1px solid #eee;
          display: flex;
          align-items: center;
          gap: 10px;
          flex-wrap: wrap;
        }

        #Events .status-label {
          padding: 5px 12px;
          border-radius: 20px;
          font-weight: 700;
          font-size: 0.85rem;
          color: white;
          user-select: none;
        }

        #Events .status-approved {
          background-color: #28a745; /* green */
        }

        #Events .status-applying {
          background-color: #ffc107; /* yellow */
          color: #212529;
        }

        #Events .status-denied {
          background-color: #dc3545; /* red */
        }

        #Events .applicant-actions button,
        #Events .org-status-actions button {
          background-color: #007bff;
          border: none;
          color: white;
          padding: 7px 14px;
          border-radius: 6px;
          font-weight: 600;
          cursor: pointer;
          transition: background-color 0.3s ease;
        }

        #Events .applicant-actions button:hover,
        #Events .org-status-actions button:hover {
          background-color: #0056b3;
        }

        #Events .applicant-modal {
          display: none; /* Hidden by default */
          position: fixed;
          z-index: 1000;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          overflow: auto;
          background-color: rgba(0,0,0,0.5);
        }

        #Events .applicant-modal-content {
          background-color: #fff;
          margin: 5% auto;
          padding: 30px;
          border-radius: 12px;
          width: 90%;
          max-width: 600px;
          position: relative;
        }

        #Events .applicant-close {
          color: #aaa;
          position: absolute;
          top: 15px;
          right: 20px;
          font-size: 28px;
          font-weight: bold;
          cursor: pointer;
          user-select: none;
        }

        #Events .applicant-close:hover,
        #Events .applicant-close:focus {
          color: #000;
          text-decoration: none;
          outline: none;
        }

        #Events .modal-logo img {
          border-radius: 10px;
        }

        #Events .modal-name {
          text-align: center;
          margin-top: 15px;
          font-size: 1.8rem;
          color: #222;
        }

        #Events .modal-datetime p,
        #Events .modal-location p,
        #Events .modal-description p {
          margin: 10px 0;
          font-size: 1rem;
          color: #444;
        }

        #Events .modal-attachment a {
          display: inline-block;
          margin-top: 15px;
          color: #007bff;
          font-weight: 600;
          text-decoration: none;
        }

        #Events .modal-attachment a:hover {
          text-decoration: underline;
        }

        #Events .no-applicants-placeholder {
          width: 100%;
          text-align: center;
          color: #666;
          font-size: 1.1rem;
          padding: 40px 20px;
        }

        #Events .no-applicants-placeholder img {
          margin-bottom: 20px;
          max-width: 300px;
          width: 100%;
          height: auto;
        }

        @media (max-width: 768px) {
          #Events .container-flex {
            flex-direction: column;
          }
          #Events .org-sidebar {
            width: 100%;
            margin-bottom: 20px;
          }
          #Events .applicant-stream {
            padding-left: 0;
            justify-content: center;
          }
          #Events .applicant-card {
            width: 100%;
            max-width: 400px;
          }
        }

        .applicant-header {
            display: flex;
            align-items: center;
            gap: 15px; /* space between logo and text */
          }

      .applicant-logo {
        width: 80px; /* or your preferred size */
        height: 80px;
        object-fit: cover;
        border-radius: 8px; /* optional rounded corners */
        flex-shrink: 0; /* prevent shrinking */
      }


    </style>
<title>Super Admin Page</title>
</head>
<body>
<div id="imageModal2" style="">
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
      <img id="modalImage" src="../admin/form/src/announcement/" alt="Expanded image" />
      <div id="modalCaption" aria-live="polite"></div>
    </div>
</div>
<!------HEADER----->
<div class="header">
  <div class="left">
  <div class="profile" title="User profile">
    
    <button class="u-profile">
      <a href="../reg-user/userprofile.html">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
      </svg>
      </a>
    </button>

  </div>
    <div class="logo-container" aria-label="Club Nexus Logo">
      <img src="src/web_logo.png" alt="Club Nexus Logo" />
    </div>
  
    </div>

  <div class="right">

  <div class="input-container" title="Search Bar">
    <input id="searchInput" type="search" name="search" class="input" placeholder="Search something...">
    <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24" class="icon"><g stroke-width="0" id="SVGRepo_bgCarrier"></g><g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g><g id="SVGRepo_iconCarrier"> <rect fill="white" height="24" width="24"></rect> <path fill="" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM9 11.5C9 10.1193 10.1193 9 11.5 9C12.8807 9 14 10.1193 14 11.5C14 12.8807 12.8807 14 11.5 14C10.1193 14 9 12.8807 9 11.5ZM11.5 7C9.01472 7 7 9.01472 7 11.5C7 13.9853 9.01472 16 11.5 16C12.3805 16 13.202 15.7471 13.8957 15.31L15.2929 16.7071C15.6834 17.0976 16.3166 17.0976 16.7071 16.7071C17.0976 16.3166 17.0976 15.6834 16.7071 15.2929L15.31 13.8957C15.7471 13.202 16 12.3805 16 11.5C16 9.01472 13.9853 7 11.5 7Z" clip-rule="evenodd" fill-rule="evenodd"></path> </g></svg>
  </div>


  <div class="notification" title="Notification">
    <button class="button">
      <svg viewBox="0 0 448 512" class="bell"><path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"></path></svg>
    </button>
  </div>

  </div>



</div>


<!---SIDEBAR BUTTON-->
<div style="position: relative; -height: calc(100vh - 50px);">
  <aside class="sidebar" aria-label="Sidebar navigation">
    <button class="tablinks active" onclick="openTab(event, 'Organizations')" aria-label="Members" title="Organizations">
      <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="24" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
      </svg>
      <span>Organization</span>
    </button>

    <button class="tablinks" onclick="openTab(event, 'Org-Applicants')" aria-label="Notes" title="Organization Applicants">
      <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="24" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
        <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
      </svg>
      <span>Applicants</span>
    </button>

    <button class="tablinks" onclick="openTab(event, 'Applicants')" aria-label="Announcments" title="Organization Announcements">
        <svg width="100%" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M22 7.99992V11.9999M10.25 5.49991H6.8C5.11984 5.49991 4.27976 5.49991 3.63803 5.82689C3.07354 6.11451 2.6146 6.57345 2.32698 7.13794C2 7.77968 2 8.61976 2 10.2999L2 11.4999C2 12.4318 2 12.8977 2.15224 13.2653C2.35523 13.7553 2.74458 14.1447 3.23463 14.3477C3.60218 14.4999 4.06812 14.4999 5 14.4999V18.7499C5 18.9821 5 19.0982 5.00963 19.1959C5.10316 20.1455 5.85441 20.8968 6.80397 20.9903C6.90175 20.9999 7.01783 20.9999 7.25 20.9999C7.48217 20.9999 7.59826 20.9999 7.69604 20.9903C8.64559 20.8968 9.39685 20.1455 9.49037 19.1959C9.5 19.0982 9.5 18.9821 9.5 18.7499V14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg> 
      <span>Announcement</span>
    </button>

    <button class="tablinks" onclick="openTab(event, 'Events')" aria-label="Calendar" title="Event Calendar">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="24" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
          <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
          <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
        </svg>
        <span>Events</span>
    </button>

    <button class="tablinks" onclick="openTab(event, 'Achievements')" aria-label="Documents" title="Posts">
      <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="24" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
      </svg>
      <span>Achievements</span>
    </button>


    <div class="gap" style="height: 10%;"></div>
    <div class="divider"></div>
    
    <button class="tablinks" onclick="openTab(event, 'About Us')" aria-label="About Us" title="About Us">
      <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="24" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
      </svg>
      <span>About Us</span>
    </button>

    <button class="tablinks" id="confirmButton" aria-label="Logout" title="Logout" >
      <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="24" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
      
      </svg>
      <span>Logout</span>
    </button>
    
  </aside> <!------END HEADER----->

</div>

<!---CONFIRMATION LOGOUT-->
<div id="confirmationModal" class="modal">
        
        <div class="modal-content">
            <div class="content">
                <h2>Confirmation</h2>
                <p>Do you want to proceed Logout?</p>
                <button id="confirmYes">Yes</button>
                <button id="confirmNo">No</button>
            </div>
        </div>
</div><!--- END CONFIRMATION LOGOUT-->

<div class="main-content" style="width: 90%;" style="display: flex;">
    <div class="tabcontent" id="Organizations">
        <?php
            include("database/db_connection.php");

            // Only select approved organizations (is_approved = 1)
            $query = 'SELECT org_id, org_logo, org_name, is_active, is_approved FROM organizations WHERE is_approved = 1';
            $result = mysqli_query($mysqli, $query);

            while ($record = mysqli_fetch_assoc($result)) {
                $orgId = $record['org_id'];
                echo '<a class="organization-link" href="forms/org-profile.php?id=' . urlencode($orgId) . '">';
                echo '<div class="organization-card">';
                if (!empty($record['org_logo'])) {
                    echo '<div class="image-container"><img src="../reg-user/src/org-logo/' . htmlspecialchars($record['org_logo']) . '" alt="Organization Logo"></div>';
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
        ?>
    </div>

    <div class="tabcontent" id="Applicants">
          <?php
                include("database/db_connection.php");

                // Fetch announcements with relevant fields
                $query = "SELECT a.announcement_id, a.org_id, a.user_id, a.announcement_title, a.announcement_text, a.announcement_file, a.announcement_approve, a.created_at,
                                o.org_logo, o.org_name
                        FROM announcements a
                        LEFT JOIN organizations o ON a.org_id = o.org_id
                        ORDER BY a.created_at DESC";

                $result = mysqli_query($mysqli, $query);

                $announcements = [];
                while ($row = mysqli_fetch_assoc($result)) {
                $announcements[] = $row;
                }


                // Helper function to parse announcement_file into array of image URLs
                function getAnnouncementImages($filesString) {
                  if (empty($filesString)) return [];
                  // Assuming files are stored as comma-separated URLs or filenames
                  return array_map('trim', explode(',', $filesString));
                }
              ?>
              <div class="container-flex" style="margin-top:10px;">
                <!-- Sidebar -->
                <aside class="org-sidebar" aria-label="Announcement status sidebar">
                  <h2>Announcement Status</h2>
                  <ul id="announcementStatusList" role="listbox" aria-label="Announcement status list">
                    <li data-status="1" class="active" role="option" tabindex="0">POSTED</li>
                    <li data-status="3" role="option" tabindex="0">PENDING</li>
                    <li data-status="0" role="option" tabindex="0">DENIED</li>
                  </ul>
                </aside>

                <!-- Announcement Stream -->
                <div class="stream" id="announcementStream" style="flex: 1;">
                  <div id="noAnnouncementsPosted" class="no-posts-placeholder" style="display:none; text-align:center; margin-top:40px;">
                    <img src="src/nopost.png" alt="No posted announcements found" style="max-width:300px; width:100%; height:auto;" />
                    <p>No posted announcements found.</p>
                  </div>
                  <div id="noAnnouncementsPending" class="no-posts-placeholder" style="display:none; text-align:center; margin-top:40px;">
                    <img src="src/nopost.png" alt="No pending announcements found" style="max-width:300px; width:100%; height:auto;" />
                    <p>No pending announcements found.</p>
                  </div>
                  <div id="noAnnouncementsDenied" class="no-posts-placeholder" style="display:none; text-align:center; margin-top:40px;">
                    <img src="src/nopost.png" alt="No denied announcements found" style="max-width:300px; width:100%; height:auto;" />
                    <p>No denied announcements found.</p>
                  </div>

                  <?php
                  if (empty($announcements)) {
                    echo '<div style="text-align:center; margin-top:40px;">
                          </div>';
                  } else {
                    foreach ($announcements as $announcement):
                      $status = intval($announcement['announcement_approve'] ?? -1);
                      if (!in_array($status, [0,1,3])) continue;

                      $images = getAnnouncementImages($announcement['announcement_file'] ?? '');
                      $title = htmlspecialchars($announcement['announcement_title'] ?? '');
                      $description = nl2br(htmlspecialchars($announcement['announcement_text'] ?? ''));
                      $created_at_raw = $announcement['created_at'] ?? '';
                      $announcement_id = htmlspecialchars($announcement['announcement_id'] ?? '');

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
                  <div class="post-card" data-is-approve="<?php echo $status; ?>" data-post-id="<?php echo $announcement_id; ?>">
                    <div class="post-header">
                        <?php if (!empty($announcement['org_logo'])): ?>
                        <img class="profile-pic" src="../reg-user/src/org-logo/<?php echo htmlspecialchars($announcement['org_logo']); ?>" alt="<?php echo htmlspecialchars($announcement['org_name']); ?> logo" onerror="this.style.display='none';" />
                        <?php endif; ?>
                        <div>
                        <h3><?php echo htmlspecialchars($announcement['org_name']); ?></h3>

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

                      <?php
                        if ($count > 4):
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

                    <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                      <?php if ($status === 1): ?>
                        <span class="status-label status-posted">POSTED</span>
                      <?php elseif ($status === 3): ?>
                        <span class="status-label status-pending">PENDING</span>
                        <div class="post-actions" style="display: flex; justify-content: flex-end; gap: 10px;">
                          <button class="approve-btn" data-post-id="<?php echo $announcement_id; ?>">Approve</button>
                          <button class="disapprove-btn" data-post-id="<?php echo $announcement_id; ?>">Disapprove</button>
                        </div>
                      <?php elseif ($status === 0): ?>
                        <span class="status-label status-denied">DENIED</span>
                        <div class="post-actions" style="display: flex; justify-content: flex-end; gap: 10px; padding:20px;">
                          <button class="approve-btn" data-post-id="<?php echo $announcement_id; ?>">Approve</button>
                        </div>
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


<div class="tabcontent" id="Events">
  <?php
    include("database/db_connection.php");
    $query = "SELECT e.event_id, e.approval_letter, e.event_name, e.event_description, 
                     e.event_poster, e.event_date, e.event_time, e.event_location, 
                     e.is_approve, e.org_id, o.org_name, o.org_logo
              FROM events e
              LEFT JOIN organizations o ON e.org_id = o.org_id
              WHERE e.is_approve IN (0, 1, 2) 
              ORDER BY e.event_date DESC, e.event_time DESC";
    $result = mysqli_query($mysqli, $query);

    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $events[] = $row;
    }
  ?>
  <div class="container-flex" style="margin-top:10px;">
    <!-- Sidebar for Event Status -->
    <aside class="org-sidebar" aria-label="Event status sidebar">
      <h2>Event Status</h2>
      <ul id="eventStatusList" role="listbox" aria-label="Event status list">
        <li data-status="1" class="active" role="option" tabindex="0">APPROVED</li>
        <li data-status="2" role="option" tabindex="0">PENDING</li>
        <li data-status="0" role="option" tabindex="0">DENIED</li>
      </ul>
    </aside>

    <!-- Event Stream -->
    <div class="applicant-stream" id="eventStream" style="flex: 1; padding-left: 220px;">
      <!-- Placeholders for no results -->
      <div id="noEventsApproved" class="no-applicants-placeholder" style="display:none; text-align:center; margin-top:40px;">
        <img src="src/nopost.png" alt="No approved events found" style="max-width:300px; width:100%; height:auto;" />
        <p>No approved events found.</p>
      </div>
      <div id="noEventsPending" class="no-applicants-placeholder" style="display:none; text-align:center; margin-top:40px;">
        <img src="src/nopost.png" alt="No pending events found" style="max-width:300px; width:100%; height:auto;" />
        <p>No pending events found.</p>
      </div>
      <div id="noEventsDenied" class="no-applicants-placeholder" style="display:none; text-align:center; margin-top:40px;">
        <img src="src/nopost.png" alt="No denied events found" style="max-width:300px; width:100%; height:auto;" />
        <p>No denied events found.</p>
      </div>

      <?php if (empty($events)): ?>
        <div class="no-applicants-placeholder" style="display: block; text-align: center; margin-top: 40px; padding-left: 0;">
          <img src="src/nopost.png" alt="No events found" style="max-width:300px; width:100%; height:auto;" />
          <p>No events found.</p>
        </div>
      <?php else: ?>
        <?php foreach ($events as $event): 
          $status = intval($event['is_approve'] ?? -1);
          if (!in_array($status, [0,1,2])) continue;

          $eventId = htmlspecialchars($event['event_id'] ?? '');
          $approvalLetter = htmlspecialchars($event['approval_letter'] ?? '');
          $eventName = htmlspecialchars($event['event_name'] ?? '');
          $eventDescription = htmlspecialchars($event['event_description'] ?? '');
          $eventPoster = htmlspecialchars($event['event_poster'] ?? '');
          $eventDate = $event['event_date'] ?? '';
          $eventTime = $event['event_time'] ?? '';
          $eventLocation = htmlspecialchars($event['event_location'] ?? '');
          $orgId = htmlspecialchars($event['org_id'] ?? '');
          $orgName = htmlspecialchars($event['org_name'] ?? '');
          $orgLogo = htmlspecialchars($event['org_logo'] ?? '');

          // Format date and time
          $formattedDate = '';
          $formattedTime = '';
          try {
            if (!empty($eventDate)) {
              $dateObj = new DateTime($eventDate);
              $formattedDate = $dateObj->format('M d, Y');
            }
            if (!empty($eventTime)) {
              $timeObj = new DateTime($eventTime);
              $formattedTime = $timeObj->format('h:i A');
            }
          } catch (Exception $e) {
            $formattedDate = 'Invalid Date';
            $formattedTime = 'Invalid Time';
          }

          // Status label text and class
          $statusText = ($status == 1) ? 'APPROVED' : (($status == 2) ? 'PENDING' : 'DENIED');
          $statusClass = ($status == 1) ? 'status-approved' : (($status == 2) ? 'status-applying' : 'status-denied');
        ?>
          <div class="applicant-card" data-is-approved="<?php echo $status; ?>" data-event-id="<?php echo $eventId; ?>" style="cursor: pointer;">
            <div class="applicant-header">
              <!-- Organization Logo -->
              <?php if (!empty($orgLogo)): ?>
                <img class="applicant-logo" src="../reg-user/src/org-logo/<?php echo $orgLogo; ?>" alt="<?php echo $orgName; ?> logo" onerror="this.style.display='none';" />
              <?php else: ?>
                <div class="applicant-logo-placeholder">No Logo</div>
              <?php endif; ?>
              
              <div>
                <!-- Organization Name -->
                <h3><?php echo $orgName; ?></h3>
                <!-- Event Name -->
                <h4 style="margin: 5px 0; color: #333; font-weight: bold;"><?php echo $eventName; ?></h4>
                <span class="applicant-time"><?php echo $formattedDate . ' at ' . $formattedTime; ?></span>
                <span class="event-location"><?php echo $eventLocation; ?></span>
              </div>
            </div>
            
            <?php if (!empty($eventPoster)): ?>
              <div class="image-container" style="margin: 10px 0;">
                <img src="../admin/form/src/event/posters/<?php echo $eventPoster; ?>" alt="<?php echo $eventName; ?> poster" style="max-height: 200px; width: auto; border-radius: 8px;" onerror="this.style.display='none';" />
              </div>
            <?php endif; ?>
            
            <div class="applicant-body">
              <p><?php echo htmlspecialchars(substr(strip_tags($eventDescription), 0, 100)); ?>...</p>
            </div>
            
            <div class="applicant-footer">
              <span class="status-label <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
              <?php if ($status == 2): // Pending ?>
                <div class="applicant-actions">
                  <button class="approve-btn" data-event-id="<?php echo $eventId; ?>">Approve</button>
                  <button class="disapprove-btn" data-event-id="<?php echo $eventId; ?>">Deny</button>
                </div>
              <?php elseif ($status == 0): // Denied ?>
                <div class="applicant-actions">
                  <button class="approve-btn" data-event-id="<?php echo $eventId; ?>">Approve</button>
                </div>
              <?php endif; ?>
            </div>

            <!-- Hidden modal content -->
            <div class="event-modal-content" style="display: none;">
              <!-- Organization Logo for Modal -->
              <?php if (!empty($orgLogo)): ?>
                <div class="modal-org-logo" style="text-align: center; margin: 20px 0;">
                  <img src="../reg-user/src/org-logo/<?php echo $orgLogo; ?>" alt="<?php echo $orgName; ?> logo" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #ddd;" onerror="this.style.display='none';" />
                </div>
              <?php endif; ?>
              
              <!-- Organization Name -->
              <h2 class="modal-org-name" style="text-align: center; margin: 10px 0 20px 0; color: #333;"><?php echo $orgName; ?></h2>
              
              <!-- Event Poster -->
              <?php if (!empty($eventPoster)): ?>
                <div class="modal-event-poster" style="text-align: center; margin: 20px 0;">
                  <img src="../admin/form/src/event/posters/<?php echo $eventPoster; ?>" alt="<?php echo $eventName; ?> poster" style="max-width: 100%; max-height: 400px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);" />
                </div>
              <?php endif; ?>
              
              <!-- Event Details -->
              <div class="modal-event-details" style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
                <h3 style="margin-top: 0; color: #0477ea;"><?php echo $eventName; ?></h3>
                
                <div class="modal-datetime" style="margin: 15px 0;">
                  <p style="margin: 5px 0;"><strong>Date:</strong> <?php echo $formattedDate; ?></p>
                  <p style="margin: 5px 0;"><strong>Time:</strong> <?php echo $formattedTime; ?></p>
                </div>
                
                <div class="modal-location" style="margin: 15px 0;">
                  <p style="margin: 5px 0;"><strong>Location:</strong> <?php echo $eventLocation; ?></p>
                </div>
                
                <div class="modal-description" style="margin: 15px 0;">
                  <p style="margin: 5px 0;"><strong>Description:</strong></p>
                  <p style="margin: 10px 0; line-height: 1.6; white-space: pre-wrap;"><?php echo nl2br(htmlspecialchars($eventDescription)); ?></p>
                </div>
              </div>
              
              <!-- Approval Letter Download -->
              <?php if (!empty($approvalLetter)): ?>
                <div class="modal-approval-letter" style="text-align: center; margin: 20px 0;">
                  <a href="../admin/form/src/event/approval-letter/<?php echo $approvalLetter; ?>" 
                     download="Approval Letter for <?php echo preg_replace('/[^a-zA-Z0-9]/', ' ', $eventName); ?>.pdf" 
                     style="display: inline-block; background: #0477ea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; transition: background 0.3s;">
                    📄 Download Approval Letter
                  </a>
                  <p style="margin: 10px 0 0 0; color: #666; font-size: 0.9em;">
                    File: <?php echo $approvalLetter; ?>
                  </p>
                </div>
              <?php else: ?>
                <div class="modal-no-approval" style="text-align: center; margin: 20px 0; color: #666;">
                  <p>No approval letter available</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- Modal for Event Details -->
  <div id="eventModal" class="applicant-modal">
    <div class="applicant-modal-content" style="max-width: 700px; max-height: 90vh; overflow-y: auto;">
      <span class="applicant-close">&times;</span>
      <div id="eventModalBody"></div>
    </div>
  </div>
</div>

<script>
  // JavaScript for Event Modal and Approval
  document.addEventListener('DOMContentLoaded', function() {
    const eventModal = document.getElementById('eventModal');
    const eventModalBody = document.getElementById('eventModalBody');
    const eventClose = document.querySelector('.applicant-close');
    const eventCards = document.querySelectorAll('#eventStream .applicant-card');

    // Open modal when event card is clicked (except buttons)
    eventCards.forEach(card => {
      card.addEventListener('click', function(e) {
        // Don't open modal if clicking approve/deny buttons
        if (e.target.closest('.applicant-actions')) {
          return;
        }
        
        const modalContent = this.querySelector('.event-modal-content');
        if (modalContent) {
          eventModalBody.innerHTML = modalContent.innerHTML;
          eventModal.style.display = 'flex';
          eventModal.setAttribute('aria-hidden', 'false');
          document.body.style.overflow = 'hidden';
        }
      });
    });

    // Close modal handlers
    if (eventClose) {
      eventClose.addEventListener('click', function() {
        eventModal.style.display = 'none';
        eventModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = 'auto';
      });
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
      if (e.target === eventModal) {
        eventModal.style.display = 'none';
        eventModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = 'auto';
      }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && eventModal.style.display === 'flex') {
        eventModal.style.display = 'none';
        eventModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = 'auto';
      }
    });

    // Event approval functionality
    const eventStream = document.getElementById('eventStream');
    if (eventStream) {
      eventStream.addEventListener('click', function(e) {
        if (e.target.classList.contains('approve-btn') || e.target.classList.contains('disapprove-btn')) {
          const eventId = e.target.getAttribute('data-event-id');
          const action = e.target.classList.contains('approve-btn') ? 'approve' : 'deny';
          
          if (confirm(`Are you sure you want to ${action} this event?`)) {
            updateEventStatus(eventId, action);
          }
          
          e.stopPropagation(); // Prevent opening modal when clicking buttons
        }
      });
    }

    // Function to update event status
    function updateEventStatus(eventId, action) {
      const url = 'database/event-approval-con.php';
      
      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          event_id: eventId,
          action: action
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(`Event ${action}d successfully.`);
          location.reload(); // Reload to show updated status
        } else {
          alert('Error: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
      });
    }
  });
</script>

<!-- JavaScript to handle Approve/Deny -->


    <div class="tabcontent" id="Org-Applicants">
      <?php
        include("database/db_connection.php");
        $query = "SELECT org_id, org_logo, org_name, org_description, category, accreditation_attachment, is_approved, is_active, created_at 
                  FROM organizations 
                  WHERE is_approved IN (0, 1, 2) 
                  ORDER BY created_at DESC";
        $result = mysqli_query($mysqli, $query);

        $applicants = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $applicants[] = $row;
        }

        // Category mapping
        $categoryMap = [
          'student-government' => 'Student Body Organization',
          'culture' => 'Culture & Religious',
          'art' => 'Art',
          'sports' => 'Sports',
          'e-sports' => 'Electronic Sports',
          'educational' => 'Educational'
        ];
      ?>
      <div class="container-flex" style="margin-top:10px;">
        <!-- Sidebar for Applicant Status -->
        <aside class="org-sidebar" aria-label="Applicant status sidebar">
          <h2>Applicant Status</h2>
          <ul id="applicantStatusList" role="listbox" aria-label="Applicant status list">
            <li data-status="1" class="active" role="option" tabindex="0">APPROVED</li>
            <li data-status="2" role="option" tabindex="0">APPLYING</li>
            <li data-status="0" role="option" tabindex="0">DENIED</li>
            <li data-status="active" role="option" tabindex="0">ACTIVE</li>
            <li data-status="inactive" role="option" tabindex="0">INACTIVE</li>
          </ul>
        </aside>

        

        <!-- Applicant Stream -->
        <div class="applicant-stream" id="applicantStream" style="flex: 1; padding-left: 220px;">
          <!-- Placeholders for no results -->
          <div id="noApplicantsApproved" class="no-applicants-placeholder" style="display:none; text-align:center; margin-top:40px;">
            <img src="src/nopost.png" alt="No approved applicants found" style="max-width:300px; width:100%; height:auto;" />
            <p>No approved applicants found.</p>
          </div>
          <div id="noApplicantsApplying" class="no-applicants-placeholder" style="display:none; text-align:center; margin-top:40px;">
            <img src="src/nopost.png" alt="No applying applicants found" style="max-width:300px; width:100%; height:auto;" />
            <p>No applying applicants found.</p>
          </div>
          <div id="noApplicantsDenied" class="no-applicants-placeholder" style="display:none; text-align:center; margin-top:40px;">
            <img src="src/nopost.png" alt="No denied applicants found" style="max-width:300px; width:100%; height:auto;" />
            <p>No denied applicants found.</p>
          </div>
          <!-- Added placeholders for active and inactive -->
          <div id="noActiveOrgs" class="no-applicants-placeholder" style="display:none; text-align:center; margin-top:40px;">
            <img src="src/nopost.png" alt="No active organizations found" style="max-width:300px; width:100%; height:auto;" />
            <p>No active organizations found.</p>
          </div>
          <div id="noInactiveOrgs" class="no-applicants-placeholder" style="display:none; text-align:center; margin-top:40px;">
            <img src="src/nopost.png" alt="No inactive organizations found" style="max-width:300px; width:100%; height:auto;" />
            <p>No inactive organizations found.</p>
          </div>

          <?php if (empty($applicants)): ?>
            <div class="no-applicants-placeholder" style="display: block; text-align: center; margin-top: 40px; padding-left: 0;">
              <img src="src/nopost.png" alt="No applicants found" style="max-width: 300px; width: 100%; height: auto;" />
              <p>No applicants found in the database.</p>
            </div>
          <?php else: ?>
            <?php foreach ($applicants as $applicant): 
              $status = intval($applicant['is_approved'] ?? -1);
              $isActive = intval($applicant['is_active'] ?? 0); // Default to 0 if not set; adjust as needed
              if (!in_array($status, [0,1,2])) continue;

              $orgId = htmlspecialchars($applicant['org_id'] ?? '');
              $orgLogo = htmlspecialchars($applicant['org_logo'] ?? '');
              $orgName = htmlspecialchars($applicant['org_name'] ?? '');
              $orgDescription = htmlspecialchars($applicant['org_description'] ?? '');
              $category = htmlspecialchars($applicant['category'] ?? '');
              $accreditation_attachment = htmlspecialchars($applicant['accreditation_attachment'] ?? '');
              $createdAt = $applicant['created_at'] ?? '';

              // Map category
              $categoryName = isset($categoryMap[$category]) ? $categoryMap[$category] : $category;

              // Format timestamp
              $formattedTime = '';
              if (!empty($createdAt)) {
                try {
                  $dateTime = new DateTime($createdAt);
                  $formattedTime = $dateTime->format('M d, Y \a\t h:i A');
                } catch (Exception $e) {
                  $formattedTime = 'Invalid Date';
                }
              }

              // Status label text
              $statusText = ($status == 1) ? 'APPROVED' : (($status == 2) ? 'APPLYING' : 'DENIED');
              $statusClass = ($status == 1) ? 'status-approved' : (($status == 2) ? 'status-applying' : 'status-denied');
            ?>
              <div class="applicant-card" data-is-approved="<?php echo $status; ?>" data-is-active="<?php echo $isActive; ?>" data-org-id="<?php echo $orgId; ?>">
                <div class="applicant-header">
                  <?php if (!empty($orgLogo)): ?>
                    <img class="applicant-logo" src="../reg-user/src/org-logo/<?php echo $orgLogo; ?>" alt="<?php echo $orgName; ?> logo" onerror="this.style.display='none';" />
                  <?php else: ?>
                    <div class="applicant-logo-placeholder">No Logo</div>
                  <?php endif; ?>
                  <div>
                    <h3><?php echo $orgName; ?></h3>
                    <span class="applicant-time"><?php echo $formattedTime; ?></span>
                  </div>
                </div>
                <div class="applicant-body">
                  <p><?php echo htmlspecialchars(substr(strip_tags($orgDescription), 0, 100)); ?>...</p> <!-- Truncated preview, escaped -->
                </div>
                <div class="applicant-footer">
                  <span class="status-label <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                  <?php if ($status == 1): // Approved - display active/inactive status beside approval status ?>
                    <span class="active-status-label <?php echo $isActive == 1 ? 'status-active' : 'status-inactive'; ?>"><?php echo $isActive == 1 ? 'ACTIVE' : 'INACTIVE'; ?></span>
                  <?php endif; ?>
                  <?php if ($status == 2): // Applying/Pending ?>
                    <div class="applicant-actions">
                      <button class="approve-btn" data-org-id="<?php echo $orgId; ?>">Approve</button>
                      <button class="disapprove-btn" data-org-id="<?php echo $orgId; ?>">Deny</button>
                    </div>
                  <?php elseif ($status == 0): // Denied ?>
                    <div class="applicant-actions">
                      <button class="approve-btn" data-org-id="<?php echo $orgId; ?>">Approve</button>
                    </div>
                  <?php endif; ?>
                  <!-- Org status actions for approved organizations (is_approved=1) - buttons only for active/inactive management -->
                  <?php if ($status == 1): // Approved - add activate/deactivate buttons based on is_active ?>
                    <div class="org-status-actions">
                      <?php if ($isActive == 1): ?>
                        <button class="deactivate-btn" data-org-id="<?php echo $orgId; ?>">Deactivate</button>
                      <?php else: ?>
                        <button class="activate-btn" data-org-id="<?php echo $orgId; ?>">Activate</button>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>
                <!-- Hidden details for modal -->
                <div class="applicant-details" style="display:none;">
                  <?php if (!empty($orgLogo)): ?>
                    <div class="modal-logo" style="border-radius: 50%; overflow: hidden; width: 100px; height: 100px; margin: 0 auto;">
                      <img src="../reg-user/src/org-logo/<?php echo $orgLogo; ?>" alt="<?php echo $orgName; ?> logo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" />
                    </div>
                  <?php endif; ?>
                  <h2 class="modal-name"><?php echo $orgName; ?></h2>
                  <div class="modal-category">
                    <p><strong>Category:</strong> <?php echo $categoryName; ?></p>
                  </div>
                  <div class="modal-description">
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($orgDescription)); ?></p>
                  </div>
                  <?php if (!empty($accreditation_attachment)): ?>
                    <div class="modal-attachment">
                      <a href="../reg-user/src/accreditation/<?php echo $accreditation_attachment; ?>" download="Accreditation File of <?php echo $orgName; ?>">Download Accreditation Attachment</a>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Modal for Applicant Details -->
      <div id="applicantModal" class="applicant-modal">
        <div class="applicant-modal-content">
          <span class="applicant-close">&times;</span>
          <div id="applicantModalBody"></div>
        </div>
      </div>
      
    </div>


    <div class="tabcontent" id="Achievements">
                <?php
                    require("database/stream-con.php");
                ?>
                <div class="container-flex" style="margin-top:10px;">
                        <!-- Sidebar -->
                        <aside class="org-sidebar" aria-label="Post status sidebar">
                          <h2>Achievement Status</h2>
                              <ul id="statusList" role="listbox" aria-label="Post status list">
                                <li data-status="1" class="active" role="option" tabindex="0">POSTED</li>
                                <li data-status="3" role="option" tabindex="0">PENDING</li>
                                <li data-status="0" role="option" tabindex="0">DENIED</li>
                              </ul>
                        </aside>

                        <!-- Posts Stream -->
                      <div class="stream" id="postStream" style="flex: 1;">
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
                        if (empty($posts)) {
                            // No posts found, show image
                            echo '<div style="text-align:center; margin-top:40px;">
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

                                // Determine image container class based on count
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
                              <img class="profile-pic" src="../reg-user/src/org-logo/<?php echo $org_logo; ?>" alt="<?php echo $org_name; ?> logo" onerror="this.style.display='none';" />
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

                                // Show first 4 images (with overlay on 4th if more)
                                for ($i = 0; $i < $showCount; $i++):
                                  if ($i === 3 && $count > 4):
                              ?>
                                <div class="image-overlay clickable-image"
                                    data-fullsrc="<?php echo htmlspecialchars($images[$i]); ?>"
                                    data-post-id="<?php echo $achievement_id; ?>"
                                    data-index="<?php echo $i; ?>"
                                    title="Click to view images">
                                  <img src="../admin/form/src/achievement/<?php echo htmlspecialchars($images[$i]); ?>"
                                      alt="<?php echo $description ? strip_tags($description) : 'Post image'; ?>"
                                      class="post-image" />
                                  <div class="overlay-text">+<?php echo $count - 4; ?> more</div>
                                </div>
                              <?php else: ?>
                                <img src="../admin/form/src/achievement/<?php echo htmlspecialchars($images[$i]); ?>"
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
                              // Add hidden images beyond the 4th for modal navigation
                              if ($count > 4):
                                for ($i = 4; $i < $count; $i++):
                            ?>
                              <img src="../admin/form/src/achievement/<?php echo htmlspecialchars($images[$i]); ?>"
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
                              <div class="post-actions" style="display: flex; justify-content: flex-end; gap: 10px;">
                                <button class="approve-btn" data-post-id="<?php echo $achievement_id; ?>">Approve</button>
                                <button class="disapprove-btn" data-post-id="<?php echo $achievement_id; ?>">Disapprove</button>
                              </div>
                            <?php elseif ($achievement_approve === 0): ?>
                              <span class="status-label status-denied">DENIED</span>
                              <div class="post-actions" style="display: flex; justify-content: flex-end; gap: 10px; padding: 20px;">
                                <button class="approve-btn" data-post-id="<?php echo $achievement_id; ?>">Approve</button>
                              </div>
                            <?php endif; ?>

                          </div>
                        </div>
                        <?php
                            endforeach;
                        }
                        ?>
                      </div>
    </div>

    <div class="tabcontent" id="About Us" > 
      Welcome to About Us Page
    </div>
</div>


<script>
  // Logout Functionality
  document.addEventListener('DOMContentLoaded', function() {
    const logoutButton = document.getElementById('confirmButton');
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');

    if (logoutButton) {
      logoutButton.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent any default tab behavior
        confirmationModal.style.display = 'flex'; // Show modal
      });
    }

    if (confirmYes) {
      confirmYes.addEventListener('click', function() {
        window.location.href = 'database/logout.php';
      });
    }

    if (confirmNo) {
      confirmNo.addEventListener('click', function() {
        confirmationModal.style.display = 'none'; // Hide modal
      });
    }

    // Close modal on outside click (optional)
    if (confirmationModal) {
      confirmationModal.addEventListener('click', function(e) {
        if (e.target === confirmationModal) {
          confirmationModal.style.display = 'none';
        }
      });
    }

    // Close modal on Escape key (optional)
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && confirmationModal.style.display === 'flex') {
        confirmationModal.style.display = 'none';
      }
    });
  });

  // TAB SWITCHING FUNCTIONALITY
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
        document.getElementById(tabName).style.display = "block";
        break;
      case "Org-Applicants":
        document.getElementById(tabName).style.display = "inline-block";
        break;
      case "Achievements":
        document.getElementById(tabName).style.display = "grid";
        break;
      case "Applicants":
        document.getElementById(tabName).style.display = "inline-block";
        break;
      case "About Us":
        document.getElementById(tabName).style.display = "inline-flex";
        break;
      case "Announcement2":
        document.getElementById(tabName).style.display = "contents";
        break;
      default:
        document.getElementById(tabName).style.display = "flex"; // fallback
    }

    // Add "active" class to the clicked tab link
    evt.currentTarget.className += " active";
  }

  // ANNOUNCEMENT FILTERING AND APPROVAL
const announcementStatusList = document.getElementById('announcementStatusList');
  const announcementStatusItems = announcementStatusList ? announcementStatusList.querySelectorAll('li') : [];
  const announcementStream = document.getElementById('announcementStream');
  const announcements = announcementStream ? announcementStream.querySelectorAll('.post-card') : [];

  // ACHIEVEMENT FILTERING AND APPROVAL
  const statusList = document.getElementById('statusList');
  const statusItems = statusList ? statusList.querySelectorAll('li') : [];
  const postStream = document.getElementById('postStream');
  const posts = postStream ? postStream.querySelectorAll('.post-card') : [];

  function filterAnnouncements(status) {
    let anyVisible = false;
    announcements.forEach(announcement => {
      if (announcement.dataset.isApprove === status) {
        announcement.classList.remove('hidden');
        anyVisible = true;
      } else {
        announcement.classList.add('hidden');
      }
    });

    // Hide all placeholders first
    ['noAnnouncementsPosted', 'noAnnouncementsPending', 'noAnnouncementsDenied'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.style.display = 'none';
    });

    // Show placeholder if no announcements visible for the selected status
    if (!anyVisible) {
      if (status === '1') {
        document.getElementById('noAnnouncementsPosted').style.display = 'block';
      } else if (status === '3') {
        document.getElementById('noAnnouncementsPending').style.display = 'block';
      } else if (status === '0') {
        document.getElementById('noAnnouncementsDenied').style.display = 'block';
      }
    }
  }

  announcementStatusItems.forEach(item => {
    item.addEventListener('click', () => {
      announcementStatusItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      const status = item.dataset.status;
      filterAnnouncements(status);
    });
  });

  // Initialize showing POSTED announcements by default
  document.addEventListener('DOMContentLoaded', () => {
    filterAnnouncements('1');
  });

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

    // Hide all placeholders first
    ['noPostsPosted', 'noPostsPending', 'noPostsDenied'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.style.display = 'none';
    });

    // Show placeholder if no posts visible for the selected status
    if (!anyVisible) {
      if (status === '1') {
        document.getElementById('noPostsPosted').style.display = 'block';
      } else if (status === '3') {
        document.getElementById('noPostsPending').style.display = 'block';
      } else if (status === '0') {
        document.getElementById('noPostsDenied').style.display = 'block';
      }
    }
  }

  statusItems.forEach(item => {
    item.addEventListener('click', () => {
      statusItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      const status = item.dataset.status;
      filterPosts(status);
    });
  });

  // Initialize showing POSTED posts by default
  document.addEventListener('DOMContentLoaded', () => {
    filterPosts('1');
  });

  function updateApproval(postId, newStatus, isAnnouncement) {
    const url = isAnnouncement ? 'database/announcement-con.php' : 'database/post-con.php';
    const data = isAnnouncement
      ? { announcement_id: parseInt(postId), announcement_approve: newStatus }
      : { achievement_id: parseInt(postId), achievement_approve: newStatus };

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => {
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      return response.json();
    })
    .then(data => {
      if (data.success) {
        const containerId = isAnnouncement ? 'announcementStream' : 'postStream';
        const post = document.querySelector(`#${containerId} .post-card[data-post-id="${postId}"]`);
        if (!post) return;
        post.dataset.isApprove = newStatus.toString();

        const label = post.querySelector('.status-label');
        if (label) {
          if (newStatus === 1) {
            label.textContent = 'POSTED';
            label.className = 'status-label status-posted';
          } else if (newStatus === 0) {
            label.textContent = 'DENIED';
            label.className = 'status-label status-denied';
          } else if (newStatus === 3) {
            label.textContent = 'PENDING';
            label.className = 'status-label status-pending';
          }
        }

        const actions = post.querySelector('.post-actions');
        if (actions) actions.remove();

        const activeStatusListId = isAnnouncement ? 'announcementStatusList' : 'statusList';
        const activeStatus = document.querySelector(`#${activeStatusListId} li.active`).dataset.status;
        if (activeStatus !== newStatus.toString()) {
          post.classList.add('hidden');
        }
        alert((isAnnouncement ? 'Announcement' : 'Achievement') + ' status updated successfully.');
      } else {
        alert('Failed to update status: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      alert('Request failed: ' + error.message);
    });
  }


  // Event delegation for approve/disapprove buttons
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('approve-btn') || e.target.classList.contains('disapprove-btn')) {
      const postId = e.target.dataset.postId;
      if (!postId) {
        alert('Post ID not found.');
        return;
      }
      const newStatus = e.target.classList.contains('approve-btn') ? 1 : 0;

      // Determine if this is announcement or achievement by checking DOM hierarchy
      let isAnnouncement = false;
      if (e.target.closest('#announcementStream')) {
        isAnnouncement = true;
      } else if (e.target.closest('#postStream')) {
        isAnnouncement = false;
      } else {
        alert('Post container not found.');
        return;
      }

      if (confirm(`Are you sure you want to ${newStatus === 1 ? 'approve' : 'disapprove'} this ${isAnnouncement ? 'announcement' : 'achievement'}?`)) {
        updateApproval(postId, newStatus, isAnnouncement);
      }
    }
  });

  (function(){
    const modal = document.getElementById('imageModal2');
    const modalImage = document.getElementById('modalImage');
    const closeBtn = modal.querySelector('.close');
    const prevBtn = modal.querySelector('.prev');
    const nextBtn = modal.querySelector('.next');
    const modalCaption = document.getElementById('modalCaption');

    let currentImages = [];
    let currentIndex = 0;
    let currentBasePath = '';

    function normalizeUrl(url) {
      try {
        const u = new URL(url, window.location.origin);
        return u.href;
      } catch {
        return url;
      }
    }

    function showImage() {
      modalImage.src = currentBasePath + currentImages[currentIndex];
      modalImage.alt = `Image ${currentIndex + 1}`;
      modalCaption.textContent = `Image ${currentIndex + 1} of ${currentImages.length}`;

      prevBtn.style.display = currentIndex === 0 ? 'none' : 'flex';
      nextBtn.style.display = currentIndex === currentImages.length - 1 ? 'none' : 'flex';
    }

    function openModal(container, event) {
      const allImagesData = container.getAttribute('data-all-images');
      if (!allImagesData) return;

      try {
        currentImages = JSON.parse(allImagesData);
      } catch (e) {
        currentImages = Array.from(container.querySelectorAll('.post-image')).map(img => img.getAttribute('data-fullsrc') || img.src);
      }

      // Determine base path based on container
      if (container.closest('#postStream')) {
        currentBasePath = "../admin/form/src/achievement/";
      } else if (container.closest('#announcementStream')) {
        currentBasePath = "../admin/form/src/announcement/";
      } else {
        currentBasePath = ""; // fallback or set default path
      }

      let clickedSrc = null;
      if (event && event.target) {
        const target = event.target;
        if (target.tagName === 'IMG' && container.contains(target)) {
          clickedSrc = target.getAttribute('data-fullsrc') || target.src;
        }
      }

      const normalizedClickedSrc = clickedSrc ? normalizeUrl(clickedSrc) : null;
      const normalizedImages = currentImages.map(normalizeUrl);

      currentIndex = normalizedClickedSrc ? normalizedImages.indexOf(normalizedClickedSrc) : 0;
      if (currentIndex === -1) currentIndex = 0;

      showImage();
      modal.style.display = 'flex';
      modal.setAttribute('aria-hidden', 'false');
    }

    closeBtn.addEventListener('click', () => {
      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');
    });

    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
      }
    });

    nextBtn.addEventListener('click', () => {
      if (currentIndex < currentImages.length - 1) {
        currentIndex++;
        showImage();
      }
    });

    prevBtn.addEventListener('click', () => {
      if (currentIndex > 0) {
        currentIndex--;
        showImage();
      }
    });

    document.addEventListener('keydown', (e) => {
      if (modal.style.display === 'flex') {
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
    document.addEventListener('click', (e) => {
      let target = e.target;
      while (target && target !== document) {
        if (target.classList && target.classList.contains('clickable-image')) {
          const container = target.closest('.post-images-container');
          if (container) {
            openModal(container, e);
          }
          break;
        }
        target = target.parentNode;
      }
    });
})();

// JavaScript for Org-Applicants Tab
// Element Selection
const applicantStatusList = document.getElementById('applicantStatusList');
const applicantStatusItems = applicantStatusList ? applicantStatusList.querySelectorAll('li') : [];
const applicantStream = document.getElementById('applicantStream');
const applicants = applicantStream ? applicantStream.querySelectorAll('.applicant-card') : [];

// Modal Elements
const applicantModal = document.getElementById('applicantModal');
const applicantModalBody = document.getElementById('applicantModalBody');
const applicantClose = document.querySelector('.applicant-close');

// Current Filter State
let currentApplicantFilter = '1'; // Default: APPROVED (1)

// Filtering Function (Extended for Applicant Status and Active/Inactive)
function filterApplicants(applicantStatus = currentApplicantFilter) {
  let anyVisible = false;
  applicants.forEach(applicant => {
    let shouldShow = false;

    // Handle Applicant Status Filtering (numeric: 0,1,2)
    if (['0', '1', '2'].includes(applicantStatus)) {
      const approved = applicant.dataset.isApproved;
      if (approved === applicantStatus) {
        shouldShow = true;
      }
    }
    // Handle Active/Inactive Filtering (strings: 'active', 'inactive') - Only for approved orgs (is_approved=1)
    else if (applicantStatus === 'active' || applicantStatus === 'inactive') {
      const approved = applicant.dataset.isApproved;
      const isActive = applicant.dataset.isActive;
      const targetActive = applicantStatus === 'active' ? '1' : '0';
      if (approved === '1' && isActive === targetActive) {
        shouldShow = true;
      }
    }

    if (shouldShow) {
      applicant.classList.remove('hidden');
      anyVisible = true;

      // Additional: Manage button visibility for approved orgs (hide in APPROVED tab, show in ACTIVE/INACTIVE tabs)
      if (applicant.dataset.isApproved === '1') {
        const orgActions = applicant.querySelector('.org-status-actions');
        if (orgActions) {
          if (applicantStatus === '1') {
            orgActions.style.display = 'none';
          } else if (applicantStatus === 'active' || applicantStatus === 'inactive') {
            orgActions.style.display = 'block';
          }
        }
      }
    } else {
      applicant.classList.add('hidden');
      // When hiding, reset button display (in case it was shown before)
      const orgActions = applicant.querySelector('.org-status-actions');
      if (orgActions) {
        orgActions.style.display = '';
      }
    }
  });

  // Hide All Placeholders
  ['noApplicantsApproved', 'noApplicantsApplying', 'noApplicantsDenied', 'noActiveOrgs', 'noInactiveOrgs'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = 'none';
  });

  // Show Appropriate Placeholder if No Matches
  if (!anyVisible) {
    if (applicantStatus === '1') {
      document.getElementById('noApplicantsApproved').style.display = 'block';
    } else if (applicantStatus === '2') {
      document.getElementById('noApplicantsApplying').style.display = 'block';
    } else if (applicantStatus === '0') {
      document.getElementById('noApplicantsDenied').style.display = 'block';
    } else if (applicantStatus === 'active') {
      document.getElementById('noActiveOrgs').style.display = 'block';
    } else if (applicantStatus === 'inactive') {
      document.getElementById('noInactiveOrgs').style.display = 'block';
    }
  }
}

// Applicant Status Filtering Event Listeners (Now Handles Active/Inactive Tabs)
applicantStatusItems.forEach(item => {
  item.addEventListener('click', () => {
    applicantStatusItems.forEach(i => i.classList.remove('active'));
    item.classList.add('active');
    currentApplicantFilter = item.dataset.status;
    filterApplicants(currentApplicantFilter);
  });
});

// Initialization: Default to APPROVED
document.addEventListener('DOMContentLoaded', () => {
  filterApplicants('1');
});

// Update Applicant Approval Status (Approve/Deny) - Fixed to use org_id
function updateApplicantApproval(orgId, newStatus) {
  const url = 'database/org-applicant-con.php'; // Backend handler (ensure it expects org_id)
  const data = { org_id: parseInt(orgId), is_approved: newStatus };

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(data)
  })
  .then(response => {
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    return response.json();
  })
  .then(data => {
    if (data.success) {
      const applicant = document.querySelector(`#applicantStream .applicant-card[data-org-id="${orgId}"]`);
      if (!applicant) return;
      applicant.dataset.isApproved = newStatus.toString();

      // Update Status Label
      const statusLabel = applicant.querySelector('.status-label');
      if (statusLabel) {
        if (newStatus === 1) {
          statusLabel.textContent = 'APPROVED';
          statusLabel.className = 'status-label status-approved';
          // If newly approved, add active status label and default to inactive (or fetch current is_active if needed)
          let activeLabel = applicant.querySelector('.active-status-label');
          if (!activeLabel) {
            activeLabel = document.createElement('span');
            activeLabel.className = 'active-status-label status-inactive'; // Default to inactive
            activeLabel.textContent = 'INACTIVE';
            statusLabel.insertAdjacentElement('afterend', activeLabel);
          }
          // Add default org-status-actions for newly approved (inactive) - visibility will be handled by filter
          let orgActions = applicant.querySelector('.org-status-actions');
          if (!orgActions) {
            orgActions = document.createElement('div');
            orgActions.className = 'org-status-actions';
            const activateBtn = document.createElement('button');
            activateBtn.className = 'activate-btn';
            activateBtn.dataset.orgId = orgId;
            activateBtn.textContent = 'Activate';
            orgActions.appendChild(activateBtn);
            const footer = applicant.querySelector('.applicant-footer');
            if (footer) footer.appendChild(orgActions);
          }
        } else if (newStatus === 0) {
          statusLabel.textContent = 'DENIED';
          statusLabel.className = 'status-label status-denied';
          // Remove active status label and org actions for denied
          const activeLabel = applicant.querySelector('.active-status-label');
          if (activeLabel) activeLabel.remove();
          const orgActions = applicant.querySelector('.org-status-actions');
          if (orgActions) orgActions.remove();
        } else if (newStatus === 2) {
          statusLabel.textContent = 'APPLYING';
          statusLabel.className = 'status-label status-applying';
          // Remove active status label and org actions for applying
          const activeLabel = applicant.querySelector('.active-status-label');
          if (activeLabel) activeLabel.remove();
          const orgActions = applicant.querySelector('.org-status-actions');
          if (orgActions) orgActions.remove();
        }
      }

      // Remove Old Actions
      const oldActions = applicant.querySelector('.applicant-actions');
      if (oldActions) oldActions.remove();

      // Refresh Filter (this will handle button visibility based on current filter)
      filterApplicants(currentApplicantFilter);
      alert('Applicant status updated successfully.');
    } else {
      alert('Failed to update status: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    alert('Request failed: ' + error.message);
  });
}

// New Function: Update Organization Active Status (Activate/Deactivate)
function updateOrgActiveStatus(orgId, newActiveStatus) {
  // Assuming a backend endpoint for updating is_active (e.g., extend existing or new file like 'database/org-active-con.php')
  // Adjust URL and data structure as per your backend
  const url = 'database/org-applicant-con.php'; // Reuse existing or change to 'database/org-active-con.php'
  const data = { 
    org_id: parseInt(orgId), 
    is_active: newActiveStatus, 
    action: 'update_active' // Optional: to distinguish from approval updates in backend
  };

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(data)
  })
  .then(response => {
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    return response.json();
  })
  .then(data => {
    if (data.success) {
      const applicant = document.querySelector(`#applicantStream .applicant-card[data-org-id="${orgId}"]`);
      if (!applicant) return;
      applicant.dataset.isActive = newActiveStatus.toString();

      // Update Active Status Label (now present in PHP for approved orgs)
      let activeLabel = applicant.querySelector('.active-status-label');
      if (activeLabel) {
        const labelText = newActiveStatus === 1 ? 'ACTIVE' : 'INACTIVE';
        const labelClass = newActiveStatus === 1 ? 'status-active' : 'status-inactive';
        activeLabel.textContent = labelText;
        activeLabel.className = `active-status-label ${labelClass}`;
      }

      // Remove Old Org Status Actions and Add New Button
      const oldOrgActions = applicant.querySelector('.org-status-actions');
      if (oldOrgActions) oldOrgActions.remove();

      const newOrgActions = document.createElement('div');
      newOrgActions.className = 'org-status-actions';
      const buttonText = newActiveStatus === 1 ? 'Deactivate' : 'Activate';
      const buttonClass = newActiveStatus === 1 ? 'deactivate-btn' : 'activate-btn';
      const newButton = document.createElement('button');
      newButton.className = buttonClass;
      newButton.dataset.orgId = orgId;
      newButton.textContent = buttonText;
      newOrgActions.appendChild(newButton);

      // Insert after applicant-actions or status-label (for approved orgs)
      const footer = applicant.querySelector('.applicant-footer');
      if (footer) {
        const applicantActions = applicant.querySelector('.applicant-actions');
        if (applicantActions) {
          applicantActions.insertAdjacentElement('afterend', newOrgActions);
        } else {
          footer.appendChild(newOrgActions);
        }
      }

      // Refresh Filter (this will handle button visibility based on current filter)
      filterApplicants(currentApplicantFilter);
      alert(`Organization ${newActiveStatus === 1 ? 'activated' : 'deactivated'} successfully.`);
    } else {
      alert('Failed to update active status: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    alert('Request failed: ' + error.message);
  });
}

// Event Delegation for Buttons and Card Clicks (Extended for Activate/Deactivate)
document.addEventListener('click', function(e) {
  // Approve/Deny Buttons
  if (e.target.classList.contains('approve-btn') || e.target.classList.contains('disapprove-btn')) {
    const orgId = e.target.dataset.orgId;
    if (!orgId) {
      alert('Organization ID not found.');
      return;
    }
    const newStatus = e.target.classList.contains('approve-btn') ? 1 : 0;

    if (confirm(`Are you sure you want to ${newStatus === 1 ? 'approve' : 'deny'} this applicant?`)) {
      updateApplicantApproval(orgId, newStatus);
    }
  }
  // New: Activate/Deactivate Buttons
  else if (e.target.classList.contains('activate-btn') || e.target.classList.contains('deactivate-btn')) {
    const orgId = e.target.dataset.orgId;
    if (!orgId) {
      alert('Organization ID not found.');
      return;
    }
    const newActiveStatus = e.target.classList.contains('activate-btn') ? 1 : 0;
    const action = newActiveStatus === 1 ? 'activate' : 'deactivate';

    if (confirm(`Are you sure you want to ${action} this organization?`)) {
      updateOrgActiveStatus(orgId, newActiveStatus);
    }
  }
  // Card Click: Open Modal (Prevent if Clicking Buttons)
  else {
    let target = e.target;
    while (target && target !== document) {
      if (target.classList && target.classList.contains('applicant-card')) {
        if (!e.target.closest('.applicant-actions') && !e.target.closest('.org-status-actions')) {
          openApplicantModal(target);
        }
        break;
      }
      target = target.parentNode;
    }
  }
});

// Modal Functions
function openApplicantModal(applicantCard) {
  const details = applicantCard.querySelector('.applicant-details');
  if (!details) return;

  // Populate Modal Body
  applicantModalBody.innerHTML = details.innerHTML;

  // Show Modal
  applicantModal.style.display = 'flex';
  applicantModal.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden'; // Prevent body scroll
}

// Close Modal Handlers
if (applicantClose) {
  applicantClose.addEventListener('click', () => {
    applicantModal.style.display = 'none';
    applicantModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = 'auto'; // Restore body scroll
  });
}

window.addEventListener('click', (e) => {
  if (e.target === applicantModal) {
    applicantModal.style.display = 'none';
    applicantModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = 'auto';
  }
});

// Keyboard Support (Escape to Close)
document.addEventListener('keydown', (e) => {
  if (applicantModal.style.display === 'flex') {
    if (e.key === 'Escape') {
      if (applicantClose) applicantClose.click();
    }
  }
});

document.addEventListener('DOMContentLoaded', function () {
  const eventsTab = document.getElementById('Events');
  if (!eventsTab) return; // Exit if Events tab not present

  const eventStatusList = document.getElementById('eventStatusList');
  const eventStatusItems = eventStatusList ? eventStatusList.querySelectorAll('li') : [];
  const eventStream = document.getElementById('eventStream');
  const eventCards = eventStream ? eventStream.querySelectorAll('.applicant-card') : [];

  // Filter events by approval status
  function filterEvents(status) {
    let anyVisible = false;
    eventCards.forEach(card => {
      if (card.getAttribute('data-is-approved') === status) {
        card.classList.remove('hidden');
        anyVisible = true;
      } else {
        card.classList.add('hidden');
      }
    });

    // Hide all placeholders first
    ['noEventsApproved', 'noEventsPending', 'noEventsDenied'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.style.display = 'none';
    });

    // Show placeholder if no events visible for the selected status
    if (!anyVisible) {
      if (status === '1') {
        const el = document.getElementById('noEventsApproved');
        if (el) el.style.display = 'block';
      } else if (status === '2') {
        const el = document.getElementById('noEventsPending');
        if (el) el.style.display = 'block';
      } else if (status === '0') {
        const el = document.getElementById('noEventsDenied');
        if (el) el.style.display = 'block';
      }
    }
  }

  // Remove focus from buttons after click to prevent stuck highlight
  eventStream.querySelectorAll('.applicant-actions button').forEach(button => {
    button.addEventListener('click', function () {
      this.blur();
      // Add your approve/deny AJAX logic here if needed
    });
  });

  // Event status filter click handlers
  eventStatusItems.forEach(item => {
    item.addEventListener('click', () => {
      eventStatusItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      const status = item.dataset.status;
      filterEvents(status);
    });
  });

  // Initialize showing APPROVED events by default
  filterEvents('1');

  // Modal elements
  const eventModal = document.getElementById('eventModal');
  const eventModalBody = document.getElementById('eventModalBody');
  const eventModalClose = eventModal ? eventModal.querySelector('.applicant-close') : null;

  // Open modal on event card click (except buttons)
  eventCards.forEach(card => {
    card.addEventListener('click', function (e) {
      if (e.target.closest('button')) return; // Ignore clicks on buttons

      const details = card.querySelector('.applicant-details');
      if (details) {
        eventModalBody.innerHTML = details.innerHTML;
        eventModal.style.display = 'block';
        eventModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
      }
    });
  });

  // Close modal on close button click
  if (eventModalClose) {
    eventModalClose.addEventListener('click', () => {
      eventModal.style.display = 'none';
      eventModal.setAttribute('aria-hidden', 'true');
      eventModalBody.innerHTML = '';
      document.body.style.overflow = 'auto';
    });
  }

  // Close modal on outside click
  window.addEventListener('click', (e) => {
    if (e.target === eventModal) {
      eventModal.style.display = 'none';
      eventModal.setAttribute('aria-hidden', 'true');
      eventModalBody.innerHTML = '';
      document.body.style.overflow = 'auto';
    }
  });

  // Close modal on Escape key
  document.addEventListener('keydown', (e) => {
    if (eventModal.style.display === 'block' || eventModal.style.display === 'flex') {
      if (e.key === 'Escape') {
        if (eventModalClose) eventModalClose.click();
      }
    }
  });

  // Event delegation for approve/deny buttons inside event cards
  eventStream.addEventListener('click', function (e) {
    if (e.target.classList.contains('approve-btn') || e.target.classList.contains('disapprove-btn')) {
      const eventId = e.target.dataset.eventId;
      if (!eventId) {
        alert('Event ID not found.');
        return;
      }
      const newStatus = e.target.classList.contains('approve-btn') ? 1 : 0;
      const actionText = newStatus === 1 ? 'approve' : 'deny';

      if (confirm(`Are you sure you want to ${actionText} this event?`)) {
        // Example AJAX call to update event approval status
        fetch('database/event-approval-con.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ event_id: parseInt(eventId), is_approve: newStatus })
        })
        .then(response => {
          if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
          return response.json();
        })
        .then(data => {
          if (data.success) {
            // Update card data attribute and status label
            const card = eventStream.querySelector(`.applicant-card[data-event-id="${eventId}"]`);
            if (!card) return;
            card.dataset.isApproved = newStatus.toString();

            const label = card.querySelector('.status-label');
            if (label) {
              if (newStatus === 1) {
                label.textContent = 'APPROVED';
                label.className = 'status-label status-approved';
              } else {
                label.textContent = 'DENIED';
                label.className = 'status-label status-denied';
              }
            }

            // Remove action buttons after approval/denial
            const actions = card.querySelector('.applicant-actions');
            if (actions) actions.remove();

            // Re-filter events to update visibility
            const activeStatus = eventStatusList.querySelector('li.active').dataset.status;
            filterEvents(activeStatus);

            alert('Event status updated successfully.');
          } else {
            alert('Failed to update event status: ' + (data.message || 'Unknown error'));
          }
        })
        .catch(error => {
          alert('Request failed: ' + error.message);
        });
      }
      e.target.blur(); // Remove focus highlight
    }
  });
});

// Fixed JavaScript for Events Section
document.addEventListener('DOMContentLoaded', function () {
  const eventsTab = document.getElementById('Events');
  if (!eventsTab) return;

  // Fix the syntax error
  const currentUserId = <?php echo json_encode($_SESSION['user_id'] ?? null); ?>;

  const eventStatusList = document.getElementById('eventStatusList');
  const eventStatusItems = eventStatusList ? eventStatusList.querySelectorAll('li') : [];
  const eventStream = document.getElementById('eventStream');
  const eventCards = eventStream ? eventStream.querySelectorAll('.applicant-card') : [];

  // Filter events by approval status
  function filterEvents(status) {
    let anyVisible = false;
    eventCards.forEach(card => {
      if (card.getAttribute('data-is-approved') === status) {
        card.classList.remove('hidden');
        anyVisible = true;
      } else {
        card.classList.add('hidden');
      }
    });

    // Hide all placeholders first
    ['noEventsApproved', 'noEventsPending', 'noEventsDenied'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.style.display = 'none';
    });

    // Show placeholder if no events visible for the selected status
    if (!anyVisible) {
      if (status === '1') {
        const el = document.getElementById('noEventsApproved');
        if (el) el.style.display = 'block';
      } else if (status === '2') {
        const el = document.getElementById('noEventsPending');
        if (el) el.style.display = 'block';
      } else if (status === '0') {
        const el = document.getElementById('noEventsDenied');
        if (el) el.style.display = 'block';
      }
    }
  }

  // Event status filter click handlers
  eventStatusItems.forEach(item => {
    item.addEventListener('click', () => {
      eventStatusItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      const status = item.dataset.status;
      filterEvents(status);
    });
  });

  // Initialize showing APPROVED events by default
  filterEvents('1');

  // Event approval/denial functionality
  function sendEventApprovalRequest(eventId, orgId, action) {
    if (!currentUserId) {
      alert('User not logged in.');
      return;
    }

    // Use the same endpoint as other approvals
    const url = 'database/event-approval-con.php';
    
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        event_id: eventId,
        org_id: orgId,
        user_id: currentUserId,
        action: action // 'approve' or 'deny'
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert(`Event ${action}d successfully.`);
        location.reload();
      } else {
        alert('Error: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while processing your request.');
    });
  }

  // Event delegation for approve/deny buttons
  eventStream.addEventListener('click', function(e) {
    if (e.target.classList.contains('approve-btn')) {
      const eventId = e.target.getAttribute('data-event-id');
      const orgId = e.target.getAttribute('data-org-id');
      if (confirm('Are you sure you want to approve this event?')) {
        sendEventApprovalRequest(eventId, orgId, 'approve');
      }
    }
    
    if (e.target.classList.contains('disapprove-btn')) {
      const eventId = e.target.getAttribute('data-event-id');
      const orgId = e.target.getAttribute('data-org-id');
      if (confirm('Are you sure you want to deny this event?')) {
        sendEventApprovalRequest(eventId, orgId, 'deny');
      }
    }
  });

  // Remove focus from buttons after click
  eventStream.querySelectorAll('.applicant-actions button').forEach(button => {
    button.addEventListener('click', function() {
      this.blur();
    });
  });
});


</script>

</body>
</html>