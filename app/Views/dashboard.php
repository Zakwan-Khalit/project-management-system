<!-- Dashboard Container -->
<div class="container-fluid" id="dashboard-container">
    <!-- Error Message Display -->
    <?php if (isset($error_message)): ?>
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem;">
            <div style="display: flex; align-items: center;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                <strong>Error:</strong>&nbsp;<?= esc($error_message) ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Modern Dashboard Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem; padding: 2rem 1.5rem; margin-bottom: 2rem; box-shadow: 0 15px 45px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
        <div style="position: absolute; bottom: -20px; left: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <h1 style="color: white; font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 800; margin-bottom: 0.75rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-tachometer-alt" style="margin-right: 0.75rem; color: rgba(255,255,255,0.9);"></i>
                    Dashboard
                </h1>
            </div>
        </div>
    </div>

    <!-- Calendar View Widget -->
    <div class="row" style="margin-bottom: 2rem;">
        <div class="col-12">
            <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f1f3f4;">
                <!-- Header -->
                <div style="background: linear-gradient(135deg, #3788d8 0%, #2c5aa0 100%); color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
                    <div style="position: relative; z-index: 2;">
                        <h4 style="color: white; font-size: 1.25rem; font-weight: 600; margin: 0; font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-calendar-alt" style="margin-right: 0.5rem;"></i>
                            Calendar View
                        </h4>
                        <p style="color: rgba(255,255,255,0.9); margin: 0.25rem 0 0 0; font-size: 0.9rem;">Your scheduled events and activities</p>
                    </div>
                    <div style="display: flex; gap: 0.5rem; position: relative; z-index: 2;">
                        <button id="calendarToday" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.4rem 0.8rem; border-radius: 0.4rem; text-decoration: none; font-weight: 500; transition: all 0.3s ease; backdrop-filter: blur(10px); font-size: 0.8rem; cursor: pointer;" onmouseover="this.style.background='rgba(255,255,255,0.3)';" onmouseout="this.style.background='rgba(255,255,255,0.2)';">
                            Today
                        </button>
                        <button id="calendarPrev" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.4rem 0.6rem; border-radius: 0.4rem; text-decoration: none; font-weight: 500; transition: all 0.3s ease; backdrop-filter: blur(10px); font-size: 0.8rem; cursor: pointer;" onmouseover="this.style.background='rgba(255,255,255,0.3)';" onmouseout="this.style.background='rgba(255,255,255,0.2)';">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="calendarNext" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.4rem 0.6rem; border-radius: 0.4rem; text-decoration: none; font-weight: 500; transition: all 0.3s ease; backdrop-filter: blur(10px); font-size: 0.8rem; cursor: pointer;" onmouseover="this.style.background='rgba(255,255,255,0.3)';" onmouseout="this.style.background='rgba(255,255,255,0.2)';">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <a href="<?= base_url('events') ?>" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.4rem 0.8rem; border-radius: 0.4rem; text-decoration: none; font-weight: 500; transition: all 0.3s ease; backdrop-filter: blur(10px); font-size: 0.8rem;" onmouseover="this.style.background='rgba(255,255,255,0.3)';" onmouseout="this.style.background='rgba(255,255,255,0.2)';">
                            View All
                        </a>
                    </div>
                </div>
                
                <!-- Calendar Container -->
                <div style="padding: 1rem;">
                    <div id="dashboardCalendar" style="max-height: 600px; overflow: hidden; font-family: 'Poppins', sans-serif;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Quick View Modal (same as events/index.php) -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventModalBody">
                <!-- Event details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?= base_url('events') ?>" class="btn btn-primary">View All Events</a>
                <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1, 2])): ?>
                <a href="#" class="btn btn-primary" id="eventEditBtn">Edit Event</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Link to events CSS file for consistent calendar styling -->
<link rel="stylesheet" href="<?= base_url('assets/css/events.css') ?>">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize FullCalendar exactly like events index page
    var calendarEl = document.getElementById('dashboardCalendar');
    if (!calendarEl) return;
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        dayMaxEvents: 3,
        moreLinkClick: 'popover',
        height: 'auto',
        allDaySlot: true,
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        slotDuration: '01:00:00',
        expandRows: true,
        events: function(info, successCallback, failureCallback) {
            fetch('<?= base_url("events/getCalendarEvents") ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Calendar events error:', data.error);
                        failureCallback(data.error);
                    } else {
                        console.log('Dashboard events loaded:', data); // Debug logging
                        // Ensure events have proper date format for all views
                        const processedEvents = data.map(event => {
                            const processedEvent = {
                                ...event,
                                start: event.start,
                                end: event.end || event.start, // Ensure end date exists
                                allDay: event.allDay || false
                            };
                            return processedEvent;
                        });
                        successCallback(processedEvents);
                    }
                })
                .catch(error => {
                    console.error('Dashboard calendar fetch error:', error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            showEventDetails(info.event);
        },
        eventMouseEnter: function(info) {
            // Add hover effects
            info.el.style.transform = 'scale(1.02)';
        },
        eventMouseLeave: function(info) {
            info.el.style.transform = 'scale(1)';
        }
    });

    calendar.render();

    // Hide the default toolbar since we have custom controls
    document.querySelector('#dashboardCalendar .fc-toolbar').style.display = 'none';

    // Calendar controls
    document.getElementById('calendarToday').addEventListener('click', function() {
        calendar.today();
    });

    document.getElementById('calendarPrev').addEventListener('click', function() {
        calendar.prev();
    });

    document.getElementById('calendarNext').addEventListener('click', function() {
        calendar.next();
    });

    function showEventDetails(event) {
        document.getElementById('eventModalTitle').textContent = event.title;
        
        let details = `
            <div class="event-detail-item">
                <strong>Type:</strong> ${event.extendedProps.type}
            </div>
            <div class="event-detail-item">
                <strong>Start:</strong> ${new Date(event.start).toLocaleString()}
            </div>
            <div class="event-detail-item">
                <strong>End:</strong> ${new Date(event.end).toLocaleString()}
            </div>
        `;
        
        if (event.extendedProps.description) {
            details += `
                <div class="event-detail-item">
                    <strong>Description:</strong> ${event.extendedProps.description}
                </div>
            `;
        }
        
        if (event.extendedProps.location) {
            details += `
                <div class="event-detail-item">
                    <strong>Location:</strong> ${event.extendedProps.location}
                </div>
            `;
        }
        
        if (event.extendedProps.project) {
            details += `
                <div class="event-detail-item">
                    <strong>Project:</strong> ${event.extendedProps.project}
                </div>
            `;
        }
        
        document.getElementById('eventModalBody').innerHTML = details;
        
        // Only set href if edit button exists (user has permission)
        const editBtn = document.getElementById('eventEditBtn');
        if (editBtn) {
            editBtn.href = `<?= base_url('events/edit/') ?>${event.id}`;
        }
        
        new bootstrap.Modal(document.getElementById('eventModal')).show();
    }

    // Make showEventDetails globally available
    window.showEventDetails = showEventDetails;
});
</script>

<script>
// Dashboard functionality
let isRefreshing = false;

function refreshDashboard() {
    if (isRefreshing) return;
    
    isRefreshing = true;
    const refreshBtn = document.getElementById('refresh-btn');
    const refreshIcon = document.getElementById('refresh-icon');
    const refreshText = document.getElementById('refresh-text');
    
    // Update button state
    refreshBtn.disabled = true;
    refreshIcon.classList.add('fa-spin');
    refreshText.textContent = 'Refreshing...';
    
    // Simulate refresh (in a real app, this would make AJAX calls to refresh data)
    setTimeout(() => {
        // Reset button state
        refreshBtn.disabled = false;
        refreshIcon.classList.remove('fa-spin');
        refreshText.textContent = 'Refresh';
        isRefreshing = false;
        
        // Show success message
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Dashboard Refreshed!',
                text: 'Your dashboard has been updated with the latest data.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
        
        // Optionally reload the page to get fresh data
        // window.location.reload();
    }, 1500);
}

// Real-time refresh function (can be called via AJAX)
function refreshDashboardData() {
    fetch('<?= base_url('dashboard/refresh') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Dashboard data refreshed successfully');
        } else {
            console.error('Failed to refresh dashboard data:', data.message);
        }
    })
    .catch(error => {
        console.error('Error refreshing dashboard:', error);
    });
}

// Auto-refresh dashboard every 5 minutes
setInterval(refreshDashboardData, 300000);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded successfully');
    
    // Add loading animation to stat cards on hover
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// Error handling for missing SweetAlert2
if (typeof Swal === 'undefined') {
    console.warn('SweetAlert2 not loaded. Using fallback alerts.');
}
</script>
