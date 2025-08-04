<!-- Events & Schedule Dashboard -->
<div class="container-fluid">
    <!-- Error Display -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= esc($error_message) ?>
        </div>
    <?php endif; ?>

    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
                <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
                    <li style="display: flex; align-items: center;">
                        <a href="<?= base_url('dashboard') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                            <i class="fas fa-home" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                        </a>
                        <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
                    </li>
                    <li style="color: #f7fafc; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; padding: 0.2rem 0.4rem; background: rgba(255,255,255,0.1); border-radius: 0.3rem; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar-alt" style="margin-right: 0.4rem; font-size: 0.75rem; opacity: 0.9;"></i>
                        Events & Schedule
                    </li>
                </ol>
            </nav>
            
            <!-- Create Event Button -->
            <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1, 2])): ?>
            <div style="margin-bottom: 2rem; display: flex; justify-content: flex-end;">
                <button onclick="window.location.href='<?= base_url('events/create') ?>'" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.75rem; padding: 0.75rem 1.5rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 18px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)';">
                    <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                    Create Event
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="calendar-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-calendar me-2"></i>
                        Calendar View
                    </h5>
                    <div class="calendar-controls">
                        <button class="btn btn-outline-primary btn-sm" id="calendarToday">Today</button>
                        <button class="btn btn-outline-secondary btn-sm" id="calendarPrev">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="calendarNext">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events List -->
    <div class="row">
        <div class="col-12">
            <div class="events-list-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-list me-2"></i>
                        Upcoming Events
                    </h5>
                    <div class="filter-controls">
                        <select class="form-select form-select-sm" id="eventTypeFilter">
                            <option value="">All Types</option>
                            <option value="meeting">Meetings</option>
                            <option value="deadline">Deadlines</option>
                            <option value="milestone">Milestones</option>
                            <option value="training">Training</option>
                            <option value="review">Reviews</option>
                            <option value="other">Other</option>
                        </select>
                        <select class="form-select form-select-sm" id="projectFilter">
                            <option value="">All Projects</option>
                            <?php foreach ($projects as $project): ?>
                                <option value="<?= $project['id'] ?>"><?= esc($project['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="events-list">
                        <?php if (empty($events)): ?>
                            <div class="no-events">
                                <i class="fas fa-calendar-times"></i>
                                <h6>No Events Found</h6>
                                <p>No events have been scheduled yet. 
                                    <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1, 2])): ?>
                                        <a href="<?= base_url('events/create') ?>">Create your first event</a>
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <div class="event-item" data-type="<?= esc($event['event_type']) ?>" data-project="<?= esc($event['project_id'] ?? '') ?>">
                                    <div class="event-type-indicator bg-<?= getEventTypeColor($event['event_type']) ?>"></div>
                                    <div class="event-content">
                                        <div class="event-header">
                                            <h6 class="event-title"><?= esc($event['title']) ?></h6>
                                            <div class="event-meta">
                                                <span class="event-type badge bg-<?= getEventTypeColor($event['event_type']) ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $event['event_type'])) ?>
                                                </span>
                                                <?php if ($event['project_name']): ?>
                                                    <span class="event-project badge bg-secondary">
                                                        <?= esc($event['project_name']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="event-details">
                                            <div class="event-datetime">
                                                <i class="fas fa-clock me-1"></i>
                                                <?= date('M d, Y g:i A', strtotime($event['start_datetime'])) ?>
                                                <?php if ($event['start_datetime'] !== $event['end_datetime']): ?>
                                                    - <?= date('g:i A', strtotime($event['end_datetime'])) ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($event['location']): ?>
                                                <div class="event-location">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    <?= esc($event['location']) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($event['description']): ?>
                                                <div class="event-description">
                                                    <?= esc($event['description']) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($event['attendees_names']): ?>
                                                <div class="event-attendees">
                                                    <i class="fas fa-users me-1"></i>
                                                    <?= esc($event['attendees_names']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="event-actions">
                                        <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1, 2])): ?>
                                        <a href="<?= base_url('events/edit/' . $event['id']) ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteEvent(<?= $event['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Quick View Modal -->
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
                <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1, 2])): ?>
                <a href="#" class="btn btn-primary" id="eventEditBtn">Edit Event</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Link to external CSS file -->
<link rel="stylesheet" href="<?= base_url('assets/css/events.css') ?>">

<!-- FullCalendar CSS and JS are included in main.php -->

<!-- Events JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize FullCalendar
    var calendarEl = document.getElementById('calendar');
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
                        console.log('Events loaded:', data); // Debug logging
                        // Ensure events have proper date format for all views
                        const processedEvents = data.map(event => {
                            const processedEvent = {
                                ...event,
                                start: event.start,
                                end: event.end || event.start, // Ensure end date exists
                                allDay: event.allDay || false
                            };
                            console.log('Processed event:', processedEvent); // Debug logging
                            return processedEvent;
                        });
                        successCallback(processedEvents);
                    }
                })
                .catch(error => {
                    console.error('Calendar fetch error:', error);
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

    // Event filtering
    document.getElementById('eventTypeFilter').addEventListener('change', filterEvents);
    document.getElementById('projectFilter').addEventListener('change', filterEvents);

    function filterEvents() {
        const typeFilter = document.getElementById('eventTypeFilter').value;
        const projectFilter = document.getElementById('projectFilter').value;
        const eventItems = document.querySelectorAll('.event-item');

        eventItems.forEach(item => {
            const eventType = item.dataset.type;
            const eventProject = item.dataset.project;
            
            let showItem = true;
            
            if (typeFilter && eventType !== typeFilter) {
                showItem = false;
            }
            
            if (projectFilter && eventProject !== projectFilter) {
                showItem = false;
            }
            
            item.style.display = showItem ? 'flex' : 'none';
        });
    }

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

function deleteEvent(eventId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `<?= base_url('events/delete/') ?>${eventId}`;
        }
    });
}
</script>

<?php
// Helper function for event type colors
function getEventTypeColor($type) {
    $colors = [
        'meeting' => 'primary',
        'deadline' => 'danger',
        'milestone' => 'success',
        'training' => 'warning',
        'review' => 'info',
        'other' => 'secondary'
    ];
    return $colors[$type] ?? 'secondary';
}
?>
