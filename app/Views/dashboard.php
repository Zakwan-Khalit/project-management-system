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

    <!-- Project Completion Statistics (moved from reports/completion_status.php) -->
    <?php
    // You may want to load $statusData from the same source as in the report, or inject it from the controller
    if (!isset($statusData)) {
        // Example: $statusData = model('ProjectModel')->getCompletionStatusData();
        $statusData = [];
    }
    // Always show statistics, even if $statusData is empty
    $statusCounts = array_count_values(array_column($statusData, 'status'));
    $totalProjects = count($statusData);
    $lateProjects = 0;
    $activeProjects = 0;
    foreach ($statusData as $project) {
    if (strtolower($project['status']) === 'active') $activeProjects++;
    // Only count as delayed if not completed and days_late is set
    if (strtolower($project['status']) !== 'completed' && $project['days_late'] !== '-') $lateProjects++;
    }
    ?>
    <div class="row mt-4 mb-4">
        <div class="col-md-3">
            <div class="card" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body text-center">
                    <h5 class="fw-bold" style="color: #3b82f6;">Total</h5>
                    <h2 class="fw-bold" style="color: #374151;"><?= $totalProjects ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body text-center">
                    <h5 class="fw-bold" style="color: #10b981;">Completed</h5>
                    <h2 class="fw-bold" style="color: #374151;"><?= $statusCounts['Completed'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body text-center">
                    <h5 class="fw-bold" style="color: #3b82f6;">Active</h5>
                    <h2 class="fw-bold" style="color: #374151;\"><?= $activeProjects ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body text-center">
                    <h5 class="fw-bold" style="color: #ef4444;">Delayed</h5>
                    <h2 class="fw-bold" style="color: #374151;"><?= $lateProjects ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div class="card-body">
            <h5 class="fw-bold mb-3" style="color: #374151;">Project Status Distribution</h5>
            <div style="height: 300px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
    <!-- End statistics section -->

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
                <!-- <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1, 2])): ?>
                <a href="#" class="btn btn-primary" id="eventEditBtn">Edit Event</a>
                <?php endif; ?> -->
            </div>
        </div>
    </div>
</div>

<!-- Link to events CSS file for consistent calendar styling -->
<link rel="stylesheet" href="<?= base_url('assets/css/events.css') ?>">

<!-- Merge all dashboard JS into a single script block -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart.js status chart
        var statusChartEl = document.getElementById('statusChart');
        if (statusChartEl) {
            var ctx = statusChartEl.getContext('2d');
            var statusCounts = <?= json_encode($statusCounts ?? []) ?>;
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusCounts),
                    datasets: [{
                        data: Object.values(statusCounts),
                        backgroundColor: [
                            '#10b981', // Completed
                            '#3b82f6', // Active
                            '#6b7280', // Planning
                            '#f59e0b', // On Hold
                            '#ef4444'  // Cancelled
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        // FullCalendar initialization
        var calendarEl = document.getElementById('dashboardCalendar');
        if (calendarEl) {
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
                eventContent: function(arg) {
                    let timeText = '';
                    if (arg.event.start) {
                        timeText = arg.event.start.toLocaleTimeString([], {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });
                    }
                    return {
                        html: `
                            <div style="padding: 2px 4px; line-height: 1.2; font-size: 0.7rem;">
                                ${timeText ? `<div style="font-weight: 600; font-size: 0.65rem; margin-bottom: 1px; color: inherit;">${timeText}</div>` : ''}
                                <div style="font-weight: bold; word-wrap: break-word; hyphens: auto;">${arg.event.title}</div>
                            </div>
                        `
                    };
                },
                events: function(info, successCallback, failureCallback) {
                    fetch('<?= base_url("events/getCalendarEvents") ?>')
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                console.error('Calendar events error:', data.error);
                                failureCallback(data.error);
                            } else {
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
                    info.el.style.transform = 'scale(1.02)';
                },
                eventMouseLeave: function(info) {
                    info.el.style.transform = 'scale(1)';
                }
            });
            calendar.render();
            // Hide the default toolbar since we have custom controls
            var fcToolbar = document.querySelector('#dashboardCalendar .fc-toolbar');
            if (fcToolbar) fcToolbar.style.display = 'none';
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
        }

        // Event modal details
        window.showEventDetails = function(event) {
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
            const editBtn = document.getElementById('eventEditBtn');
            if (editBtn) {
                editBtn.href = `<?= base_url('events/edit/') ?>${event.id}`;
            }
            new bootstrap.Modal(document.getElementById('eventModal')).show();
        };

        // Dashboard refresh and stat card hover
        let isRefreshing = false;
        window.refreshDashboard = function() {
            if (isRefreshing) return;
            isRefreshing = true;
            const refreshBtn = document.getElementById('refresh-btn');
            const refreshIcon = document.getElementById('refresh-icon');
            const refreshText = document.getElementById('refresh-text');
            if (refreshBtn && refreshIcon && refreshText) {
                refreshBtn.disabled = true;
                refreshIcon.classList.add('fa-spin');
                refreshText.textContent = 'Refreshing...';
            }
            setTimeout(() => {
                if (refreshBtn && refreshIcon && refreshText) {
                    refreshBtn.disabled = false;
                    refreshIcon.classList.remove('fa-spin');
                    refreshText.textContent = 'Refresh';
                }
                isRefreshing = false;
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
            }, 1500);
        };

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
        setInterval(refreshDashboardData, 300000);

        // Stat card hover effect
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Error handling for missing SweetAlert2
        if (typeof Swal === 'undefined') {
            console.warn('SweetAlert2 not loaded. Using fallback alerts.');
        }
        console.log('Dashboard loaded successfully');
    });
    </script>