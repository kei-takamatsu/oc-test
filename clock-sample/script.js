function updateClock() {
    const now = new Date();
    
    // Time components
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    // DOM update
    document.getElementById('hours').textContent = hours;
    document.getElementById('minutes').textContent = minutes;
    document.getElementById('seconds').textContent = seconds;
    
    // Date components
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const date = now.getDate();
    const days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
    const day = days[now.getDay()];
    
    document.getElementById('date-display').textContent = `${year}.${month}.${date} ${day}`;
}

function generateCalendar() {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();
    const today = now.getDate();

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const calendarEl = document.getElementById('calendar');
    calendarEl.innerHTML = '';

    // Weekday headers
    const weekdays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
    weekdays.forEach(day => {
        const dayEl = document.createElement('div');
        dayEl.className = 'calendar-header';
        dayEl.textContent = day;
        calendarEl.appendChild(dayEl);
    });

    // Empty cells for padding
    for (let i = 0; i < firstDay; i++) {
        const emptyEl = document.createElement('div');
        emptyEl.className = 'calendar-day empty';
        calendarEl.appendChild(emptyEl);
    }

    // Days of the month
    for (let d = 1; d <= daysInMonth; d++) {
        const dayEl = document.createElement('div');
        dayEl.className = 'calendar-day';
        if (d === today) dayEl.classList.add('today');
        dayEl.textContent = d;
        calendarEl.appendChild(dayEl);
    }
}

// Initial call
updateClock();
generateCalendar();

// Update every second
setInterval(updateClock, 1000);

// Add subtle interaction effect
document.querySelector('.glass-card').addEventListener('mousemove', (e) => {
    const card = e.currentTarget;
    const rect = card.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    
    const rotateX = (y - centerY) / 10;
    const rotateY = (centerX - x) / 10;
    
    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
});

document.querySelector('.glass-card').addEventListener('mouseleave', (e) => {
    const card = e.currentTarget;
    card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0)`;
});
