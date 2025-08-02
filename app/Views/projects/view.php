<a href="javascript:history.back()" class="btn btn-outline-secondary d-inline-flex align-items-center" style="gap:0.5rem;">
    <i class="fas fa-arrow-left"></i>
    <span>Back</span>
</a>
<!-- Modern Project View Page -->
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">
    
    <!-- Project Header -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden;">
        
        <!-- Breadcrumb -->
        <div style="background: #f8fafc; padding: 1rem 2rem; border-bottom: 1px solid #e2e8f0;">
            <nav style="font-size: 0.9rem;">
                <a href="<?= base_url('dashboard') ?>" style="color: #667eea; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Dashboard</a>
                <span style="margin: 0 0.5rem; color: #9ca3af;">/</span>
                <a href="<?= base_url('projects') ?>" style="color: #667eea; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Projects</a>
                <span style="margin: 0 0.5rem; color: #9ca3af;">/</span>
                <span style="color: #6b7280; font-weight: 500;" id="projectBreadcrumb">Project Details</span>
            </nav>
        </div>
        
        <!-- Project Title Section -->
        <div style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem;">
                <div style="flex: 1; min-width: 300px;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <h1 style="margin: 0; font-size: 2rem; font-weight: 700; color: #1f2937; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 0.75rem;" id="projectTitle">
                            <i class="fas fa-project-diagram" style="color: #667eea; font-size: 1.8rem;"></i>
                            Loading...
                        </h1>
                        <span class="badge" id="projectStatus">Loading</span>
                    </div>
                    <p style="color: #6b7280; line-height: 1.6; margin: 0; font-size: 1rem;" id="projectDescription">Loading project description...</p>
                    <!-- script moved to bottom for global scope -->
                </div>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button onclick="editProject()" style="background: rgba(102,126,234,0.1); color: #667eea; border: 2px solid #667eea; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;"
                            onmouseover="this.style.background='#667eea'; this.style.color='white'"
                            onmouseout="this.style.background='rgba(102,126,234,0.1)'; this.style.color='#667eea'">
                        <i class="fas fa-edit"></i>
                        Edit Project
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Overview Cards: Only Team Members and Days Left remain -->
    <!-- Removed Team Members and Days Left cards -->

    <!-- Main Content Tabs -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        
        <!-- Tab Navigation -->
        <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0; padding: 0;">
            <div style="display: flex; overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;">
                <button onclick="showTab('overview')" id="overview-tab" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 1rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; border-radius: 0; border-bottom: 3px solid #667eea; white-space: nowrap; min-width: fit-content;">
                    <i class="fas fa-chart-line"></i>
                    Overview
                </button>
                <button onclick="showTab('activity')" id="activity-tab" style="background: transparent; color: #6b7280; border: none; padding: 1rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; border-radius: 0; border-bottom: 3px solid transparent; white-space: nowrap; min-width: fit-content;"
                        onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(102,126,234,0.1)'; this.style.color='#667eea'; }"
                        onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; this.style.color='#6b7280'; }">
                    <i class="fas fa-history"></i>
                    Activity
                </button>
                <button onclick="showTab('team')" id="team-tab" style="background: transparent; color: #6b7280; border: none; padding: 1rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; border-radius: 0; border-bottom: 3px solid transparent; white-space: nowrap; min-width: fit-content;"
                        onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(102,126,234,0.1)'; this.style.color='#667eea'; }"
                        onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; this.style.color='#6b7280'; }">
                    <i class="fas fa-users"></i>
                    Team
                </button>
            </div>
        </div>
        
        <!-- Tab Content -->
        <div style="padding: 2rem;">
            
            <!-- Overview Tab (Task-related charts removed) -->
            <div id="overview" style="display: block;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
            <!-- Project Details (left column only) -->
            <div style="margin-bottom: 2rem;">
                <h3 style="margin: 0 0 1rem 0; font-size: 1.25rem; font-weight: 600; color: #374151; font-family: 'Poppins', sans-serif;">Project Details</h3>
                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-weight: 600; color: #374151;">Start Date:</span>
                        <span style="color: #6b7280;" id="projectStartDate">Loading...</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-weight: 600; color: #374151;">End Date:</span>
                        <span style="color: #6b7280;" id="projectEndDate">Loading...</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-weight: 600; color: #374151;">Budget:</span>
                        <span style="color: #6b7280;" id="projectBudget">Loading...</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0;">
                        <span style="font-weight: 600; color: #374151;">Client:</span>
                        <span style="color: #6b7280;" id="projectClient">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <!-- Progress and Recent Activity (right column) -->
            <div class="row mb-3">
                <div class="col-sm-6">
                    <strong>Progress:</strong>
                </div>
                <div class="col-sm-6">
                    <div class="progress">
                        <div class="progress-bar" id="projectProgressBar" style="width: 0%"></div>
                    </div>
                    <small class="text-muted" id="projectProgressText">0%</small>
                </div>
            </div>
            <div class="mb-4">
                <h5>Recent Activity</h5>
                <div class="card">
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        <div id="recentActivity">
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-spinner fa-spin"></i>
                                Loading activities...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



            <!-- Team Tab -->
            <div id="team" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Team Members</h5>
                    <button class="btn btn-primary" onclick="addTeamMember()">
                        <i class="fas fa-user-plus me-1"></i>
                        Add Member
                    </button>
                </div>
                <div id="teamMembersList">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-spinner fa-spin"></i>
                        Loading team members...
                    </div>
                </div>
            </div>

            <!-- Files Tab removed -->

            <!-- Activity Tab -->
            <div id="activity" style="display: none;">
                <h5>Activity Log</h5>
                <div id="activityLog">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-spinner fa-spin"></i>
                        Loading activity log...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Removed Create Task Modal -->

<script>
// Add Team Member button handler
function addTeamMember() {
    Swal.fire({
        title: 'Add Project Member',
        html: `
            <div class="mb-3 text-start">
                <label for="departmentDropdown" class="form-label">Department</label>
                <select id="departmentDropdown" class="form-select">
                    <option value="">-- Please Select --</option>
                </select>
            </div>
            <div class="mb-3 text-start">
                <label for="userDropdown" class="form-label">Users</label>
                <select id="userDropdown" class="form-select" multiple disabled style="width:100%">
                    <option value="">-- Please Select Department First --</option>
                </select>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Add',
        cancelButtonText: 'Cancel',
        focusConfirm: false,
        didOpen: () => {
            // Fetch departments
            $.ajax({
                url: `${baseUrl}/projects/departments`,
                method: 'GET',
                dataType: 'json',
                success: function(resp) {
                    if (resp.success && resp.departments) {
                        let opts = '<option value="">-- Please Select --</option>';
                        resp.departments.forEach(function(dep) {
                            opts += `<option value="${dep.id}">${dep.name}</option>`;
                        });
                        $('#departmentDropdown').html(opts);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading departments:', error);
                    Swal.fire('Error', 'Failed to load departments.', 'error');
                }
            });
            // Init Select2 for users
            $('#userDropdown').select2({
                dropdownParent: $('.swal2-modal'),
                width: '100%',
                placeholder: '-- Please Select Department First --',
                allowClear: true
            });
            // Department change handler
            $('#departmentDropdown').on('change', function() {
                let depId = $(this).val();
                if (!depId) {
                    $('#userDropdown').prop('disabled', true).html('<option value="">-- Please Select --</option>').trigger('change');
                } else {
                    $('#userDropdown').prop('disabled', false).html('');
                    // Fetch users for department
                    $.ajax({
                        url: `${baseUrl}/projects/departmentUsers/` + depId + `?project_id=${projectId}`,
                        method: 'GET',
                        dataType: 'json',
                        success: function(resp) {
                            let opts = '';
                            if (resp.success && resp.users && resp.users.length > 0) {
                                resp.users.forEach(function(u) {
                                    opts += `<option value="${u.id}">${u.first_name} ${u.last_name}</option>`;
                                });
                            } else {
                                opts = '<option value="">No users found</option>';
                            }
                            $('#userDropdown').html(opts).trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading department users:', error);
                            $('#userDropdown').html('<option value="">Error loading users</option>').trigger('change');
                        }
                    });
                }
            });
        },
        preConfirm: () => {
            let depId = $('#departmentDropdown').val();
            let userIds = $('#userDropdown').val();
            if (!depId || !userIds || userIds.length === 0) {
                Swal.showValidationMessage('Please select a department and at least one user.');
                return false;
            }
            // AJAX to add members to project
            return $.ajax({
                url: `${baseUrl}/projects/add_project_members`,
                method: 'POST',
                data: {
                    project_id: projectId,
                    department_id: depId,
                    user_ids: userIds
                },
                dataType: 'json'
            }).then(function(resp) {
                if (resp.success) {
                    loadTeamMembers();
                    return true;
                } else {
                    Swal.showValidationMessage(resp.message || 'Unknown error');
                    return false;
                }
            }).catch(function(xhr, status, error) {
                console.error('Error adding project members:', error);
                Swal.showValidationMessage('Server error');
                return false;
            });
                return false;
            });
        }
    });
}
// Upload File button handler
function uploadFile() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Upload File',
            text: 'This feature is not implemented yet.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    } else {
        alert('Upload File: This feature is not implemented yet.');
    }
}
// Get project ID from URL (assumes /projects/view/{id})
const urlParts = window.location.pathname.split('/');
let projectId = urlParts[urlParts.indexOf('view') + 1];
if (!projectId) projectId = '1'; // fallback for demo

function getStatusClass(status) {
    const classes = {
        'planning': 'info',
        'in_progress': 'primary',
        'on_hold': 'warning',
        'completed': 'success',
        'cancelled': 'danger',
        'todo': 'info',
        'review': 'warning',
        'done': 'success'
    };
    return classes[status] || 'secondary';
}
function getPriorityClass(priority) {
    const classes = {
        'low': 'success',
        'medium': 'info',
        'high': 'warning',
        'urgent': 'danger'
    };
    return classes[priority] || 'secondary';
}
function getActivityColor(action) {
    const colors = {
        'created': 'success',
        'updated': 'info',
        'deleted': 'danger',
        'commented': 'primary'
    };
    return colors[action] || 'secondary';
}
function getActivityIcon(action) {
    const icons = {
        'created': 'fa-plus',
        'updated': 'fa-edit',
        'deleted': 'fa-trash',
        'commented': 'fa-comment'
    };
    return icons[action] || 'fa-circle';
}
function timeAgo(date) {
    const now = new Date();
    const past = new Date(date);
    const diff = now - past;
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    if (minutes < 60) return `${minutes} minutes ago`;
    if (hours < 24) return `${hours} hours ago`;
    return `${days} days ago`;
}
function editProject() {
    window.location.href = `<?= base_url('projects') ?>/${projectId}/edit`;
}
function showTab(tabName) {
    const tabs = ['overview', 'team', 'activity'];
    tabs.forEach(tab => {
        const tabContent = document.getElementById(tab);
        const tabButton = document.getElementById(tab + '-tab');
        if (tabContent) tabContent.style.display = 'none';
        if (tabButton) {
            tabButton.style.background = 'transparent';
            tabButton.style.color = '#6b7280';
            tabButton.style.borderBottomColor = 'transparent';
            tabButton.classList.remove('active');
        }
    });
    const selectedTab = document.getElementById(tabName);
    const selectedButton = document.getElementById(tabName + '-tab');
    if (selectedTab) selectedTab.style.display = 'block';
    if (selectedButton) {
        selectedButton.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        selectedButton.style.color = 'white';
        selectedButton.style.borderBottomColor = '#667eea';
        selectedButton.classList.add('active');
    }
    switch(tabName) {
        case 'team':
            loadTeamMembers();
            break;
        case 'activity':
            loadActivityLog();
            break;
    }
}
function loadProjectDetails() {
    fetch(`${baseUrl}/projects/getProject/${projectId}`)
        .then(res => res.json())
        .then(data => {
            const project = data.project || {};
            console.log('Project Data:', data); // Debug output to console
            // Use the exact field names from your backend debug output
            const name = project.name || 'Untitled';
            const status = project.status_name || 'Unknown';
            const statusColor = project.status_color || '#e2e8f0';
            const owner = (project.owner_name && project.owner_name.trim() !== '') ? project.owner_name : 'Unknown';
            const description = project.description || '';
            const startDate = project.start_date || 'N/A';
            const endDate = project.end_date || 'N/A';
            const budget = project.budget || 'N/A';
            const client = project.client || 'N/A';
            const totalTasks = typeof project.total_tasks !== 'undefined' ? project.total_tasks : 0;
            const completedTasks = typeof project.completed_tasks !== 'undefined' ? project.completed_tasks : 0;
            const progress = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

            const elTitle = document.getElementById('projectTitle');
            if (elTitle) elTitle.innerHTML = `<i class='fas fa-project-diagram' style='color: #667eea; font-size: 1.8rem;'></i> ${name}`;
            const elStatus = document.getElementById('projectStatus');
            if (elStatus) {
                elStatus.textContent = status;
                elStatus.style.background = statusColor;
            }
            // Removed project owner display
            const elDesc = document.getElementById('projectDescription');
            if (elDesc) elDesc.textContent = description;
            const descDetail = document.getElementById('projectDescriptionDetail');
            if (descDetail) descDetail.textContent = description;
            const elStart = document.getElementById('projectStartDate');
            if (elStart) elStart.textContent = startDate;
            const elEnd = document.getElementById('projectEndDate');
            if (elEnd) elEnd.textContent = endDate;
            const elBudget = document.getElementById('projectBudget');
            if (elBudget) elBudget.textContent = budget;
            const elClient = document.getElementById('projectClient');
            if (elClient) elClient.textContent = client;
            const elBar = document.getElementById('projectProgressBar');
            if (elBar) elBar.style.width = progress + '%';
            const elText = document.getElementById('projectProgressText');
            if (elText) elText.textContent = `${progress}% (${completedTasks}/${totalTasks})`;
        });
}
function loadProjectStats() {
    // Removed: fetch and update for team members and days left cards
}
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('addDropdown');
    const button = e.target.closest('button[onclick="toggleAddDropdown()"]');
    if (!button && dropdown && dropdown.style.opacity === '1') {
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
        dropdown.style.transform = 'translateY(-10px)';
    }
});
function loadTeamMembers() {
    $.ajax({
        url: '<?= base_url('projects/members') ?>/' + projectId,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Team Members Data:', data); // Debug output to console
            if (data.success) {
                renderTeamMembers(data.members);
            } else {
                document.getElementById('teamMembersList').innerHTML = '<div class="text-center text-danger py-4">Failed to load team members.</div>';
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading team members:', error);
            document.getElementById('teamMembersList').innerHTML = '<div class="text-center text-danger py-4">Failed to load team members.</div>';
        }
    });
}
function renderTeamMembers(members) {
    if (!members || members.length === 0) {
        document.getElementById('teamMembersList').innerHTML = '<div class="text-center text-muted py-4">No team members found.</div>';
        return;
    }
    let html = '<ul class="list-group">';
    members.forEach(m => {
        console.log(m);
        html += `<li class="list-group-item d-flex align-items-center"><i class="fas fa-user-circle me-2 text-secondary"></i> ${m.first_name} ${m.last_name} (${m.role})</li>`;
    });
    html += '</ul>';
    document.getElementById('teamMembersList').innerHTML = html;
}

function loadActivityLog() {
    $.ajax({
        url: '<?= base_url('projects/recentActivity') ?>/' + projectId,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                renderRecentActivity(data.activities);
            } else {
                document.getElementById('activityLog').innerHTML = '<div class="text-center text-danger py-4">Failed to load activity log.</div>';
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading activity log:', error);
            document.getElementById('activityLog').innerHTML = '<div class="text-center text-danger py-4">Failed to load activity log.</div>';
        }
    });
}

// Prevent ReferenceError if renderRecentActivity is missing
if (typeof renderRecentActivity !== 'function') {
    function renderRecentActivity(activities) {
        document.getElementById('activityLog').innerHTML = '<div class="text-center text-muted py-4">No activity log available.</div>';
    }
}
// On page load
document.addEventListener('DOMContentLoaded', function() {
    window.baseUrl = '<?= base_url() ?>'.replace(/\/$/, '');
    loadProjectDetails();
    loadProjectStats();
    showTab('overview');
});
</script>
