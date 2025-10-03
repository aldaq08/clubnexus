<?php
require_once ('database/db_connection.php');

// Get current month and year from URL parameters or use current date
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Fetch approved events for the current month
$sql = "
    SELECT 
        e.*,
        u.first_name,
        u.last_name,
        o.org_name,
        o.org_logo,
        DAY(e.event_date) as event_day
    FROM events e
    INNER JOIN organizations o ON e.org_id = o.org_id
    INNER JOIN users u ON e.user_id = u.user_id
    WHERE e.is_approve = 1 
    AND MONTH(e.event_date) = ? 
    AND YEAR(e.event_date) = ?
    ORDER BY e.event_date ASC, e.event_time ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$currentMonth, $currentYear]);
$monthEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group events by day for calendar display
$eventsByDay = [];
foreach ($monthEvents as $event) {
    $day = $event['event_day'];
    if (!isset($eventsByDay[$day])) {
        $eventsByDay[$day] = [];
    }
    $eventsByDay[$day][] = $event;
}

// Prepare events data for JavaScript
$eventsData = [];
foreach ($monthEvents as $event) {
    $dateKey = $event['event_date'];
    $eventsData[$dateKey] = [
        'title' => $event['event_name'],
        'description' => $event['event_description'],
        'poster' => $event['event_poster'],
        'time' => $event['event_time'],
        'location' => $event['event_location'],
        'org_name' => $event['org_name'],
        'org_logo' => $event['org_logo'],
        'posted_by' => $event['first_name'] . ' ' . $event['last_name']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Calendar with Database Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #2d85db, #ffde59, #0075a3);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: 'Outfit', sans-serif;
        }

        .calendar-wrapper {
            width: 100%;
            max-width: 1200px;
            height: 95vh;
            display: flex;
            gap: 15px;
        }

        .toggle-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 10px;
        }

        .toggle-btn {
            background: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            font-family: 'Fredoka', sans-serif;
            font-weight: 600;
            color: #0075a3;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .toggle-btn:hover {
            background: #f0f7ff;
            transform: translateY(-2px);
        }

        .toggle-btn.active {
            background: #2d85db;
            color: white;
        }

        .calendar-container {
            flex: 1;
            display: flex;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            height: 100%;
        }

        .month-list-section {
            width: 250px;
            padding: 20px;
            background-color: #f0f7ff;
            border-right: 1px solid #eaeaea;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .month-list-section.hidden {
            width: 0;
            padding: 0;
            overflow: hidden;
        }

        .month-section {
            flex: 1.2;
            padding: 25px;
            background: #f8f9fa;
            border-right: 1px solid #eaeaea;
            transition: all 0.3s ease;
        }

        .month-section.full-width {
            flex: 1;
            border-right: none;
        }

        .events-section {
            width: 400px;
            padding: 25px;
            background-color: white;
            transition: all 0.3s ease;
            overflow-y: scroll; /* Keep scroll for content */
        }

        /* For WebKit browsers (Chrome, Safari, Opera) */
        .events-section::-webkit-scrollbar {
            display: none;
        }

        .events-section.hidden {
            width: 0;
            padding: 0;
            overflow: hidden;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #0075a3;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eaeaea;
            font-family: 'Fredoka', sans-serif;
        }

        .close-section-btn {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: #2d85db;
            padding: 5px;
            border-radius: 5px;
            transition: background-color 0.3s;
            float: right;
        }

        .close-section-btn:hover {
            background-color: rgba(45, 133, 219, 0.1);
        }

        .month-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 100%;
            overflow-y: auto;
        }

        .month-list-item {
            padding: 12px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            color: #555;
            font-family: 'Outfit', sans-serif;
        }

        .month-list-item:hover {
            background-color: #e6f0ff;
        }

        .month-list-item.active {
            background-color: #2d85db;
            color: white;
        }

        .month-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .month-title {
            font-size: 24px;
            font-weight: 600;
            color: #0075a3;
            font-family: 'Fredoka', sans-serif;
        }

        .nav-btn {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #2d85db;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-family: 'Fredoka', sans-serif;
        }

        .nav-btn:hover {
            background-color: rgba(45, 133, 219, 0.1);
        }

        .weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: 600;
            color: #0075a3;
            margin-bottom: 10px;
            font-family: 'Fredoka', sans-serif;
        }

        .days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }

        .day {
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            position: relative;
            font-family: 'Outfit', sans-serif;
        }

        .day:hover {
            background-color: #e6f0ff;
        }

        .day.current-month {
            color: #333;
        }

        .day.other-month {
            color: #aaa;
        }

        .day.today {
            background-color: #ffde59;
            color: #333;
            font-weight: 600;
        }

        .day.has-event::after {
            content: '';
            position: absolute;
            bottom: 5px;
            width: 5px;
            height: 5px;
            background-color: #0075a3;
            border-radius: 50%;
        }

        .events-title {
            font-size: 22px;
            font-weight: 600;
            color: #0075a3;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f1f1;
            font-family: 'Fredoka', sans-serif;
        }

        .events-list {
            max-height: calc(100% - 60px);
            overflow-y: auto;
        }

        .event-item {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #2d85db;
            transition: transform 0.2s;
            cursor: pointer;
            overflow-x: hidden;
            scrollbar-width: none; /* For Firefox */
            -ms-overflow-style: none;  /* For Internet Explorer and Edge */
        }

        .event-item:hover {
            transform: translateX(5px);
            background-color: #f0f7ff;
        }

        .event-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .org-logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
            border: 2px solid #2d85db;
        }

        .org-logo-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ffde59;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: bold;
            color: #333;
            border: 2px solid #2d85db;
            font-family: 'Outfit', sans-serif;
        }

        .event-org-info {
            flex: 1;
        }

        .org-name {
            font-weight: 600;
            color: #0075a3;
            font-size: 14px;
            font-family: 'Outfit', sans-serif;
        }

        .posted-by {
            font-size: 12px;
            color: #666;
            font-family: 'Outfit', sans-serif;
        }

        .event-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
            font-family: 'Outfit', sans-serif;
        }

        .event-title {
            font-weight: 600;
            color: #0075a3;
            margin-bottom: 8px;
            font-size: 16px;
            font-family: 'Outfit', sans-serif;
        }

        .event-description {
            font-size: 14px;
            color: #555;
            line-height: 1.4;
            font-family: 'Outfit', sans-serif;
        }

        .event-poster-popup {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .event-details-popup {
            margin-top: 15px;
        }

        .event-detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
            font-family: 'Outfit', sans-serif;
        }

        .event-detail-item::before {
            margin-right: 8px;
        }

        .event-time::before {
            content: '⏰';
        }

        .event-location::before {
            content: '📍';
        }

        .event-org-popup::before {
            content: '🏢';
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .popup-content {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: translateY(20px);
            transition: transform 0.3s;
        }

        .popup-overlay.active .popup-content {
            transform: translateY(0);
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .popup-title {
            font-size: 22px;
            font-weight: 600;
            color: #0075a3;
            font-family: 'Fredoka', sans-serif;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
            transition: color 0.2s;
        }

        .close-btn:hover {
            color: #333;
        }

        .popup-date {
            font-size: 16px;
            color: #2d85db;
            margin-bottom: 15px;
            font-weight: 500;
            font-family: 'Outfit', sans-serif;
        }

        .popup-description {
            line-height: 1.6;
            color: #555;
            margin-bottom: 15px;
            font-family: 'Outfit', sans-serif;
        }

        .no-events {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 20px;
            font-family: 'Outfit', sans-serif;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: white;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            font-family: 'Fredoka', sans-serif;
            font-weight: 600;
            color: #0075a3;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 100;
        }

        .back-button:hover {
            background: #f0f7ff;
            transform: translateY(-2px);
        }

        @media (max-width: 900px) {
            .calendar-wrapper {
                flex-direction: column;
                height: auto;
            }
            
            .toggle-buttons {
                flex-direction: row;
                order: 2;
            }
            
            .calendar-container {
                flex-direction: column;
                height: auto;
            }
            
            .month-list-section, .events-section {
                width: 100%;
            }
            
            .month-list {
                flex-direction: row;
                flex-wrap: wrap;
                max-height: none;
            }
            
            .month-list-item {
                flex: 1 0 25%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    
    <button class="back-button" onclick="window.location.href = 'home.php';">
        ← Back
    </button>

    <div class="calendar-wrapper">
        <div class="toggle-buttons">
            <button class="toggle-btn active" id="toggleMonths">
                📅 Months
            </button>
            <button class="toggle-btn active" id="toggleEvents">
                📋 Events
            </button>
        </div>

        <div class="calendar-container">
            <div class="month-list-section" id="monthListSection">
                <div class="section-title">
                    Months
                    <button class="close-section-btn" id="closeMonths">×</button>
                </div>
                <div class="month-list">
                    <!-- Month list will be populated by JavaScript -->
                </div>
            </div>

            <div class="month-section" id="monthSection">
                <div class="month-header">
                    <button class="nav-btn prev-btn">&lt;</button>
                    <div class="month-title">
                        <?php 
                        $monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                                      'July', 'August', 'September', 'October', 'November', 'December'];
                        echo $monthNames[$currentMonth - 1] . ' ' . $currentYear; 
                        ?>
                    </div>
                    <button class="nav-btn next-btn">&gt;</button>
                </div>
                <div class="weekdays">
                    <div>Sun</div>
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                </div>
                <div class="days-grid">
                    <!-- Days will be populated by JavaScript -->
                </div>
            </div>

            <div class="events-section" id="eventsSection">
                <div class="section-title">
                    Events for <?php echo $monthNames[$currentMonth - 1]; ?>
                    <button class="close-section-btn" id="closeEvents">×</button>
                </div>
                <div class="events-list">
                    <?php if (count($monthEvents) > 0): ?>
                        <?php foreach ($monthEvents as $event): ?>
                            <div class="event-item" onclick="openEventPopup('<?php echo $event['event_date']; ?>')">
                                <div class="event-header">
                                    <?php if (!empty($event['org_logo'])): ?>
                                        <img src="src/org-logo/<?php echo htmlspecialchars($event['org_logo']); ?>" 
                                             alt="<?php echo htmlspecialchars($event['org_name']); ?>" 
                                             class="org-logo">
                                    <?php else: ?>
                                        <div class="org-logo-placeholder">
                                            <?php echo substr($event['org_name'], 0, 2); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="event-org-info">
                                        <div class="org-name"><?php echo htmlspecialchars($event['org_name']); ?></div>
                                        <div class="posted-by">Posted by: <?php echo htmlspecialchars($event['first_name'] . ' ' . $event['last_name']); ?></div>
                                    </div>
                                </div>
                                <div class="event-date">
                                    <?php echo date('F j, Y', strtotime($event['event_date'])); ?> 
                                    at <?php echo date('g:i A', strtotime($event['event_time'])); ?>
                                </div>
                                <div class="event-title"><?php echo htmlspecialchars($event['event_name']); ?></div>
                                <div class="event-description">
                                    <?php echo htmlspecialchars(substr($event['event_description'], 0, 100)); ?>
                                    <?php if (strlen($event['event_description']) > 100): ?>...<?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-events">No approved events scheduled for this month.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="popup-overlay">
        <div class="popup-content">
            <div class="popup-header">
                <h3 class="popup-title">Event Details</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="popup-date" id="popupDate">June 15, 2023</div>
            <div id="popupPoster"></div>
            <div class="popup-description" id="popupDescription">
                This is where the detailed information about the event will appear.
            </div>
            <div class="event-details-popup" id="popupDetails"></div>
        </div>
    </div>

    <script>
        // Events data from PHP
        const eventsData = <?php echo json_encode($eventsData); ?>;
        const eventsByDay = <?php echo json_encode($eventsByDay); ?>;

        // Current date
        let currentDate = new Date();
        let currentMonth = <?php echo $currentMonth - 1; ?>; // JavaScript months are 0-indexed
        let currentYear = <?php echo $currentYear; ?>;

        // DOM Elements
        const monthTitle = document.querySelector('.month-title');
        const daysGrid = document.querySelector('.days-grid');
        const eventsList = document.querySelector('.events-list');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const popupOverlay = document.querySelector('.popup-overlay');
        const closeBtn = document.querySelector('.close-btn');
        const popupTitle = document.querySelector('.popup-title');
        const popupDate = document.querySelector('.popup-date');
        const popupDescription = document.querySelector('.popup-description');
        const popupPoster = document.getElementById('popupPoster');
        const popupDetails = document.getElementById('popupDetails');
        const monthList = document.querySelector('.month-list');
        
        // Toggle buttons and sections
        const toggleMonthsBtn = document.getElementById('toggleMonths');
        const toggleEventsBtn = document.getElementById('toggleEvents');
        const closeMonthsBtn = document.getElementById('closeMonths');
        const closeEventsBtn = document.getElementById('closeEvents');
        const monthListSection = document.getElementById('monthListSection');
        const eventsSection = document.getElementById('eventsSection');
        const monthSection = document.getElementById('monthSection');

        // Initialize calendar
        function initCalendar() {
            renderMonthList();
            renderCalendar();
            setupEventListeners();
        }

        // Setup event listeners for toggle buttons
        function setupEventListeners() {
            toggleMonthsBtn.addEventListener('click', () => {
                monthListSection.classList.toggle('hidden');
                monthSection.classList.toggle('full-width');
                toggleMonthsBtn.classList.toggle('active');
            });

            toggleEventsBtn.addEventListener('click', () => {
                eventsSection.classList.toggle('hidden');
                monthSection.classList.toggle('full-width');
                toggleEventsBtn.classList.toggle('active');
            });

            closeMonthsBtn.addEventListener('click', () => {
                monthListSection.classList.add('hidden');
                monthSection.classList.add('full-width');
                toggleMonthsBtn.classList.remove('active');
            });

            closeEventsBtn.addEventListener('click', () => {
                eventsSection.classList.add('hidden');
                monthSection.classList.add('full-width');
                toggleEventsBtn.classList.remove('active');
            });
        }

        // Render month list
        function renderMonthList() {
            monthList.innerHTML = '';
            
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                               'July', 'August', 'September', 'October', 'November', 'December'];
            
            monthNames.forEach((month, index) => {
                const monthItem = document.createElement('div');
                monthItem.classList.add('month-list-item');
                
                // Highlight current month
                if (index === currentMonth) {
                    monthItem.classList.add('active');
                }
                
                monthItem.textContent = month;
                
                monthItem.addEventListener('click', () => {
                    // Navigate to selected month
                    window.location.href = `?month=${index + 1}&year=${currentYear}`;
                });
                
                monthList.appendChild(monthItem);
            });
        }

        // Render calendar for current month
        function renderCalendar() {
            // Clear previous days
            daysGrid.innerHTML = '';
            
            // Update month title
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                               'July', 'August', 'September', 'October', 'November', 'December'];
            monthTitle.textContent = `${monthNames[currentMonth]} ${currentYear}`;
            
            // Get first day of month and number of days
            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay();
            
            // Previous month days
            const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
            for (let i = 0; i < startingDay; i++) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('day', 'other-month');
                dayElement.textContent = prevMonthLastDay - startingDay + i + 1;
                daysGrid.appendChild(dayElement);
            }
            
            // Current month days
            const today = new Date();
            for (let i = 1; i <= daysInMonth; i++) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('day', 'current-month');
                dayElement.textContent = i;
                
                // Check if it's today
                if (currentYear === today.getFullYear() && 
                    currentMonth === today.getMonth() && 
                    i === today.getDate()) {
                    dayElement.classList.add('today');
                }
                
                // Check if day has event
                const dateString = formatDate(currentYear, currentMonth + 1, i);
                if (eventsData[dateString]) {
                    dayElement.classList.add('has-event');
                }
                
                // Add click event
                dayElement.addEventListener('click', () => openEventPopup(formatDate(currentYear, currentMonth + 1, i)));
                
                daysGrid.appendChild(dayElement);
            }
            
            // Next month days
            const totalCells = 42; // 6 rows x 7 days
            const remainingCells = totalCells - (startingDay + daysInMonth);
            for (let i = 1; i <= remainingCells; i++) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('day', 'other-month');
                dayElement.textContent = i;
                daysGrid.appendChild(dayElement);
            }
            
            // Update active month in month list
            document.querySelectorAll('.month-list-item').forEach((item, index) => {
                if (index === currentMonth) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }

        // Format date as YYYY-MM-DD
        function formatDate(year, month, day) {
            return `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        }

        // Format date for display
        function formatDisplayDate(date) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options);
        }

        // Open popup with event details
        function openEventPopup(dateString) {
            const event = eventsData[dateString];
            
            if (event) {
                popupTitle.textContent = event.title;
                popupDate.textContent = formatDisplayDate(dateString);
                popupDescription.textContent = event.description;
                
                // Add event poster if available
                if (event.poster) {
                    popupPoster.innerHTML = `<img src="${event.poster}" alt="${event.title}" class="event-poster-popup">`;
                } else {
                    popupPoster.innerHTML = '';
                }
                
                // Add event details
                let detailsHTML = '';
                if (event.time) {
                    detailsHTML += `<div class="event-detail-item event-time">${event.time}</div>`;
                }
                if (event.location) {
                    detailsHTML += `<div class="event-detail-item event-location">${event.location}</div>`;
                }
                if (event.org_name) {
                    detailsHTML += `<div class="event-detail-item event-org-popup">${event.org_name}</div>`;
                }
                if (event.posted_by) {
                    detailsHTML += `<div class="event-detail-item">Posted by: ${event.posted_by}</div>`;
                }
                popupDetails.innerHTML = detailsHTML;
            } else {
                popupTitle.textContent = 'No Event';
                popupDate.textContent = formatDisplayDate(dateString);
                popupDescription.textContent = 'No events scheduled for this day.';
                popupPoster.innerHTML = '';
                popupDetails.innerHTML = '';
            }
            
            popupOverlay.classList.add('active');
        }

        // Close popup
        function closePopup() {
            popupOverlay.classList.remove('active');
        }

        // Event listeners
        prevBtn.addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            window.location.href = `?month=${currentMonth + 1}&year=${currentYear}`;
        });

        nextBtn.addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            window.location.href = `?month=${currentMonth + 1}&year=${currentYear}`;
        });

        closeBtn.addEventListener('click', closePopup);
        popupOverlay.addEventListener('click', (e) => {
            if (e.target === popupOverlay) {
                closePopup();
            }
        });

        // Initialize the calendar
        initCalendar();
    </script>
</body>
</html>