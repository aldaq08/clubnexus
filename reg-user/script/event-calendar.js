document.addEventListener('DOMContentLoaded', function() {
            // Calendar state
            const state = {
                currentDate: new Date(),
                selectedDate: new Date(),
                events: {
                    '2023-05-15': [
                        { id: 1, title: 'Team Meeting', time: '10:00 AM', description: 'Weekly sync with the product team' },
                        { id: 2, title: 'Client Call', time: '2:30 PM', description: 'Discuss new project requirements' }
                    ],
                    '2023-05-20': [
                        { id: 3, title: 'Doctor Appointment', time: '9:00 AM', description: 'Annual checkup' }
                    ],
                    '2023-06-05': [
                        { id: 4, title: 'Conference', time: 'All day', description: 'Tech conference downtown' }
                    ],
                    '2023-06-12': [
                        { id: 5, title: 'Birthday Party', time: '7:00 PM', description: 'Sarah\'s birthday celebration' }
                    ],
                    '2023-07-08': [
                        { id: 6, title: 'Project Deadline', time: '11:00 AM', description: 'Final submission for Q3 project' }
                    ],
                    '2023-07-10': [
                        { id: 7, title: 'Team Lunch', time: '12:30 PM', description: 'Monthly team bonding lunch' }
                    ]
                }
            };

            // DOM Elements
            const openCalendarBtn = document.getElementById('openCalendarBtn');
            const closeCalendarBtn = document.getElementById('closeCalendarBtn');
            const addEventBtn = document.getElementById('addEventBtn');
            const cancelEventBtn = document.getElementById('cancelEventBtn');
            const eventForm = document.getElementById('eventForm');
            const prevMonthBtn = document.getElementById('prevMonthBtn');
            const nextMonthBtn = document.getElementById('nextMonthBtn');
            const calendarContainer = document.getElementById('calendarContainer');
            const calendarOverlay = document.getElementById('calendarOverlay');
            const monthList = document.getElementById('monthList');
            const calendarGrid = document.getElementById('calendarGrid');
            const currentMonthYear = document.getElementById('currentMonthYear');
            const eventsList = document.getElementById('eventsList');
            // Open/close calendar
            openCalendarBtn.addEventListener('click', () => {
                calendarContainer.style.display = 'block';
                calendarOverlay.style.display = 'block';
                renderCalendar();
                renderMonthList();
                renderEvents();
            });

            closeCalendarBtn.addEventListener('click', () => {
                calendarContainer.style.display = 'none';
                calendarOverlay.style.display = 'none';
            });

            calendarOverlay.addEventListener('click', () => {
                calendarContainer.style.display = 'none';
                calendarOverlay.style.display = 'none';
            });

            prevMonthBtn.addEventListener('click', () => {
                state.currentDate = new Date(state.currentDate.getFullYear(), state.currentDate.getMonth() - 1, 1);
                renderCalendar();
                updateMonthListActiveState();
            });

            nextMonthBtn.addEventListener('click', () => {
                state.currentDate = new Date(state.currentDate.getFullYear(), state.currentDate.getMonth() + 1, 1);
                renderCalendar();
                updateMonthListActiveState();
            });

            // Event form handlers
            addEventBtn.addEventListener('click', () => {
                document.getElementById('eventFormContainer').classList.remove('hidden');
                eventsList.style.display = 'none';
            });

            cancelEventBtn.addEventListener('click', () => {
                document.getElementById('eventFormContainer').classList.add('hidden');
                eventsList.style.display = 'block';
            });

            eventForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const title = document.getElementById('eventTitle').value;
                const date = document.getElementById('eventDate').value;
                const time = document.getElementById('eventTime').value;
                const description = document.getElementById('eventDescription').value;
                const location = document.getElementById('eventLocation').value;
                const posterFile = document.getElementById('eventPoster').files[0];
                let poster = '';
                if (posterFile) {
                    poster = URL.createObjectURL(posterFile);
                }
                
                const dateStr = date;
                if (!state.events[dateStr]) {
                    state.events[dateStr] = [];
                }
                
                const newEvent = {
                    id: Date.now(),
                    title: title,
                    time: time,
                    description: description,
                    location: location,
                    poster: poster
                };
                
                state.events[dateStr].push(newEvent);
                
                // Reset and hide form
                eventForm.reset();
                document.getElementById('eventFormContainer').classList.add('hidden');
                eventsList.style.display = 'block';
                
                // Re-render calendar and events
                renderCalendar();
                renderEvents();
            });

            // Calendar functions
            function renderCalendar() {
                calendarGrid.innerHTML = '';
                
                const year = state.currentDate.getFullYear();
                const month = state.currentDate.getMonth();
                
                // Update month/year display
                currentMonthYear.textContent = `${getMonthName(month)} ${year}`;
                
                // Get first day of month and total days in month
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const daysInLastMonth = new Date(year, month, 0).getDate();
                
                // Add days from last month
                for (let i = 0; i < firstDay; i++) {
                    const day = daysInLastMonth - firstDay + i + 1;
                    const date = new Date(year, month - 1, day);
                    
                    const dayCell = document.createElement('div');
                    dayCell.className = 'day-cell text-gray-400';
                    dayCell.innerHTML = `
                        <span class="date-indicator">${day}</span>
                    `;
                    calendarGrid.appendChild(dayCell);
                }
                
                // Add days for current month
                const today = new Date();
                const selectedDateStr = formatDateKey(state.selectedDate);
                
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(year, month, day);
                    const dateStr = formatDateKey(date);
                    const isToday = date.toDateString() === today.toDateString();
                    const isSelected = dateStr === selectedDateStr;
                    const hasEvents = state.events[dateStr] && state.events[dateStr].length > 0;
                    
                    const dayCell = document.createElement('div');
                    dayCell.className = `day-cell ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${hasEvents ? 'has-event' : ''}`;
                    dayCell.innerHTML = `
                        <span class="date-indicator">${day}</span>
                    `;
                    
                    if (hasEvents) {
                        const eventCount = state.events[dateStr].length;
                        const eventText = eventCount === 1 ? '1 event' : `${eventCount} events`;
                        dayCell.innerHTML += `<div class="event-marker" title="${state.events[dateStr].map(e => e.title).join('\n')}">${eventText}</div>`;
                    }
                    
                    dayCell.addEventListener('click', () => {
                        state.selectedDate = date;
                        renderCalendar();
                        renderEvents();
                    });
                    
                    calendarGrid.appendChild(dayCell);
                }
                
                // Add days from next month
                const totalCells = 42; // 6 weeks * 7 days
                const daysAdded = firstDay + daysInMonth;
                
                for (let i = daysAdded; i < totalCells; i++) {
                    const day = i - daysAdded + 1;
                    const date = new Date(year, month + 1, day);
                    
                    const dayCell = document.createElement('div');
                    dayCell.className = 'day-cell text-gray-400';
                    dayCell.innerHTML = `
                        <span class="date-indicator">${day}</span>
                    `;
                    calendarGrid.appendChild(dayCell);
                }
            }
            
            function renderMonthList() {
                monthList.innerHTML = '';
                
                const currentYear = state.currentDate.getFullYear();
                const months = Array.from({ length: 12 }, (_, i) => new Date(currentYear, i, 1));
                
                months.forEach((month, index) => {
                    const btn = document.createElement('button');
                    btn.className = `month-btn ${index === state.currentDate.getMonth() ? 'active' : ''}`;
                    btn.textContent = getMonthName(index);
                    
                    btn.addEventListener('click', () => {
                        state.currentDate = month;
                        state.selectedDate = null; // Clear selected date
                        renderCalendar();
                        
                        // Update active button
                        document.querySelectorAll('.month-btn').forEach(el => el.classList.remove('active'));
                        btn.classList.add('active');
                        
                        // Show all events for the month
                        renderMonthEvents(month.getMonth(), month.getFullYear());
                    });
                    
                    monthList.appendChild(btn);
                });
            }
            
            function renderEvents() {
                eventsList.innerHTML = '';
                document.getElementById('eventFormContainer').classList.add('hidden');
                
                if (!state.selectedDate) {
                    renderMonthEvents(state.currentDate.getMonth(), state.currentDate.getFullYear());
                    return;
                }
                
                document.getElementById('eventDate').value = formatDateKey(state.selectedDate).split('T')[0];
                const dateStr = formatDateKey(state.selectedDate);
                
                if (state.events[dateStr]) {
                    const events = state.events[dateStr];
                    
                    events.forEach(event => {
                        const eventEl = document.createElement('div');
                        eventEl.className = 'event-item';
                        eventEl.innerHTML = `
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-gray-800">${event.title}</div>
                                    <div class="text-xs text-gray-500 mt-1">${formatDisplayDate(state.selectedDate)} • ${event.time}</div>
                                    ${event.location ? `<div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        ${event.location}
                                    </div>` : ''}
                                </div>
                                <div class="w-2 h-2 rounded-full bg-blue-500 mt-1"></div>
                            </div>
                            ${event.poster ? `<img src="${event.poster}" alt="${event.title} poster" class="w-full h-auto rounded-md mt-2 mb-2 cursor-pointer hover:opacity-90" style="max-height: 150px; object-fit: cover;" onclick="expandImage('${event.poster}')">` : ''}
                            <div class="text-sm text-gray-600 mt-2">${event.description}</div>
                        `;
                        eventsList.appendChild(eventEl);
                    });
                } else {
                    eventsList.innerHTML = `
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/cbe23985-c920-41ec-95ce-495d3f34e616.png" alt="No events illustration showing an empty calendar page with a pencil" class="mb-4 rounded-lg" />
                        <p class="text-gray-500 text-sm">No events scheduled for ${formatDisplayDate(state.selectedDate)}</p>
                    `;
                }
            }
            
            // Helper functions
            function getMonthName(monthIndex) {
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                return months[monthIndex];
            }
            
            function formatDateKey(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
            
            function formatDisplayDate(date) {
                return `${getMonthName(date.getMonth())} ${date.getDate()}, ${date.getFullYear()}`;
            }

            // Image modal functions
            function expandImage(src) {
                const modal = document.getElementById('imageModal');
                const modalImg = document.getElementById('expandedImage');
                modal.style.display = 'flex';
                modalImg.src = src;
            }
            
            document.querySelector('.close-modal').addEventListener('click', function() {
                document.getElementById('imageModal').style.display = 'none';
            });
            
            // Close modal when clicking outside image
            document.getElementById('imageModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });

            function renderMonthEvents(month, year) {
                eventsList.innerHTML = '';
                document.getElementById('eventFormContainer').classList.add('hidden');
                
                // Get all events for the month
                const monthEvents = [];
                const monthStr = String(month + 1).padStart(2, '0');
                
                for (const [dateStr, events] of Object.entries(state.events)) {
                    const [eventYear, eventMonth] = dateStr.split('-');
                    if (eventYear == year && eventMonth == monthStr) {
                        monthEvents.push(...events);
                    }
                }
                
                if (monthEvents.length > 0) {
                    monthEvents.forEach(event => {
                        const eventDate = new Date(event.date || dateStr);
                        const eventEl = document.createElement('div');
                        eventEl.className = 'event-item';
                        eventEl.innerHTML = `
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-gray-800">${event.title}</div>
                                    <div class="text-xs text-gray-500 mt-1">${formatDisplayDate(eventDate)} • ${event.time}</div>
                                    ${event.location ? `<div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round", stroke-linejoin="round", stroke-width="2", d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round", stroke-linejoin="round", stroke-width="2", d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        ${event.location}
                                    </div>` : ''}
                                </div>
                                <div class="w-2 h-2 rounded-full bg-blue-500 mt-1"></div>
                            </div>
                            ${event.poster ? `<img src="${event.poster}" alt="${event.title} poster" class="w-full h-auto rounded-md mt-2 mb-2 cursor-pointer hover:opacity-90" style="max-height: 150px; object-fit: cover;" onclick="expandImage('${event.poster}')">` : ''}
                            <div class="text-sm text-gray-600 mt-2">${event.description}</div>
                        `;
                        eventsList.appendChild(eventEl);
                    });
                } else {
                    eventsList.innerHTML = `
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/cbe23985-c920-41ec-95ce-495d3f34e616.png" alt="No events illustration showing an empty calendar page with a pencil" class="mb-4 rounded-lg" />
                        <p class="text-gray-500 text-sm">No events scheduled for ${getMonthName(month)} ${year}</p>
                    `;
                }
            }

            function updateMonthListActiveState() {
                document.querySelectorAll('.month-btn').forEach((btn, index) => {
                    if (index === state.currentDate.getMonth()) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }

            function loadEvents(month, year) {
                let url = '/php/get_events.php';
                if (month && year) {
                    url += `?month=${month}&year=${year}`;
                }
                    fetch(url)
                        .then(response => response.json())
                        .then(events => {
                            // Process events and update your calendar state
                            // Example: group events by date
                            const eventsByDate = {};
                            events.forEach(event => {
                                if (!eventsByDate[event.event_date]) {
                                    eventsByDate[event.event_date] = [];
                                }
                                eventsByDate[event.event_date].push({
                                    id: event.id,
                                    title: event.title,
                                    time: event.event_time,
                                    description: event.description,
                                    location: event.location,
                                    poster: event.poster
                                });
                            });
                            // Update your calendar state and re-render
                            state.events = eventsByDate;
                            renderCalendar();
                            renderEvents();
                        })
                        .catch(err => console.error('Error loading events:', err));
                }

        });