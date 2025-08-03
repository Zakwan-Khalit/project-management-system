<!-- Modern Project View Page -->
    
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1.5rem;">
        <ol style="display: flex; list-style: none; padding: 1rem 1.25rem; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.75rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2); border: none;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('dashboard') ?>" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.25rem 0.5rem; border-radius: 0.375rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.15)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)'">
                    <i class="fas fa-tachometer-alt" style="margin-right: 0.5rem; font-size: 0.9rem;"></i>
                    Dashboard
                </a>
                <span style="margin: 0 0.75rem; color: #e2e8f0; font-size: 1.1rem; font-weight: 300;">›</span>
            </li>
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('projects') ?>" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.25rem 0.5rem; border-radius: 0.375rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.15)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)'">
                    <i class="fas fa-project-diagram" style="margin-right: 0.5rem; font-size: 0.9rem;"></i>
                    Projects
                </a>
                <span style="margin: 0 0.75rem; color: #e2e8f0; font-size: 1.1rem; font-weight: 300;">›</span>
            </li>
            <li style="color: #f7fafc; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; padding: 0.25rem 0.5rem; background: rgba(255,255,255,0.1); border-radius: 0.375rem; backdrop-filter: blur(10px);">
                <i class="fas fa-eye" style="margin-right: 0.5rem; font-size: 0.85rem; opacity: 0.9;"></i>
                Project Details
            </li>
        </ol>
    </nav>
    
    <!-- Back Button -->
    <a href="javascript:history.back()" class="btn btn-outline-secondary d-inline-flex align-items-center" style="gap:0.5rem; margin-bottom: 1.5rem;">
        <i class="fas fa-arrow-left"></i>
        <span>Back</span>
    </a>
    
    <!-- Project Header -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden;">
        
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
                        <!-- Recent Activity and Progress (right column) -->
                        <div class="row mb-3" style="padding-top: 2.5rem;">
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
                            <h5>Project Activities</h5>
                            <div class="card">
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    <div id="projectComponents">
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-spinner fa-spin"></i>
                                            Loading Activities...
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Activity Log</h5>
                    <span class="badge bg-secondary" id="activityCount">0 activities</span>
                </div>
                <div id="activityLog" style="max-height: 500px; overflow-y: auto;">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-spinner fa-spin"></i>
                        Loading activity log...
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
                    <option value="">-- Please Select --</option>
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
                                    opts += `<option value="${u.id}">${u.full_name}</option>`;
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
        'commented': 'primary',
        'team_member_added': 'success',
        'team_member_removed': 'warning',
        'task_created': 'info',
        'task_updated': 'primary',
        'task_completed': 'success',
        'status_changed': 'warning',
        'file_uploaded': 'secondary',
        'comment_added': 'primary'
    };
    return colors[action] || 'secondary';
}
function getActivityIcon(action) {
    const icons = {
        'created': 'fa-plus',
        'updated': 'fa-edit',
        'deleted': 'fa-trash',
        'commented': 'fa-comment',
        'team_member_added': 'fa-user-plus',
        'team_member_removed': 'fa-user-minus',
        'task_created': 'fa-tasks',
        'task_updated': 'fa-check-circle',
        'task_completed': 'fa-check-double',
        'status_changed': 'fa-exchange-alt',
        'file_uploaded': 'fa-file-upload',
        'comment_added': 'fa-comment-dots'
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
    console.log('Switching to tab:', tabName); // Debug: log tab switches
    
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
            console.log('Loading team members because team tab was selected');
            loadTeamMembers();
            break;
        case 'activity':
            console.log('Loading activity log because activity tab was selected');
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
        })
        .catch(error => {
            console.error('Error loading project details:', error);
            const elTitle = document.getElementById('projectTitle');
            if (elTitle) elTitle.innerHTML = `<i class='fas fa-project-diagram' style='color: #667eea; font-size: 1.8rem;'></i> Error Loading Project`;
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
    // Prevent multiple simultaneous calls
    if (window.loadingTeamMembers) {
        console.log('Team members already loading, skipping...');
        return;
    }
    
    window.loadingTeamMembers = true;
    console.log('Loading team members for project:', projectId);
    
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
        },
        complete: function() {
            window.loadingTeamMembers = false;
        }
    });
}
function renderTeamMembers(members) {
    console.log('Rendering team members:', members); // Debug: log the exact data being rendered
    
    if (!members || members.length === 0) {
        document.getElementById('teamMembersList').innerHTML = '<div class="text-center text-muted py-4">No team members found.</div>';
        return;
    }
    
    let html = '<ul class="list-group">';
    members.forEach((m, index) => {
        console.log(`Member ${index}:`, m); // Debug: log each member
        html += `
            <li class="list-group-item d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-circle me-2 text-secondary"></i> 
                    <span>${m.full_name} (${m.role})</span>
                </div>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteTeamMember(${m.user_id || m.id}, '${m.full_name}')" title="Remove from project">
                    <i class="fas fa-trash"></i>
                </button>
            </li>
        `;
    });
    html += '</ul>';
    
    console.log('Generated HTML length:', html.length); // Debug: check HTML output
    document.getElementById('teamMembersList').innerHTML = html;
}

function deleteTeamMember(userId, memberName) {
    Swal.fire({
        title: 'Remove Team Member?',
        text: `Are you sure you want to remove "${memberName}" from this project?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, remove them!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Removing...',
                text: 'Please wait while we remove the team member.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make AJAX call to soft delete team member
            $.ajax({
                url: `${baseUrl}/projects/remove_project_member`,
                method: 'POST',
                data: {
                    project_id: projectId,
                    user_id: userId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Removed!',
                            text: `${memberName} has been removed from the project.`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Reload the team members list
                        loadTeamMembers();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'Failed to remove team member.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error removing team member:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while removing the team member. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}

function loadActivityLog() {
    console.log('Loading activity log for project:', projectId);
    
    $.ajax({
        url: '<?= base_url('projects/recentActivity') ?>/' + projectId,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Activity Log Data:', data);
            if (data.success) {
                renderActivityLog(data.activities);
                // Update activity count
                const count = data.activities ? data.activities.length : 0;
                $('#activityCount').text(`${count} ${count === 1 ? 'activity' : 'activities'}`);
            } else {
                document.getElementById('activityLog').innerHTML = '<div class="text-center text-danger py-4">Failed to load activity log.</div>';
                $('#activityCount').text('0 activities');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading activity log:', error);
            document.getElementById('activityLog').innerHTML = '<div class="text-center text-danger py-4">Failed to load activity log. Please try again.</div>';
            $('#activityCount').text('0 activities');
        }
    });
}

function renderActivityLog(activities) {
    console.log('Rendering activity log:', activities);
    
    if (!activities || activities.length === 0) {
        document.getElementById('activityLog').innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-history fa-3x mb-3" style="color: #e2e8f0;"></i>
                <h6>No Activity Found</h6>
                <p class="mb-0">No recent activity has been recorded for this project.</p>
            </div>
        `;
        return;
    }
    
    let html = '<div class="timeline" style="position: relative;">';
    activities.forEach((activity, index) => {
        const timeAgoText = timeAgo(activity.date_created || activity.created_at);
        const activityIcon = getActivityIcon(activity.action || 'created');
        const activityColor = getActivityColor(activity.action || 'created');
        const userName = activity.full_name || 'Unknown User';
        const description = activity.description || activity.details || 'Activity performed';
        
        html += `
            <div class="timeline-item mb-4" style="position: relative; padding-left: 60px;">
                <div class="timeline-marker" style="position: absolute; left: 0; top: 0; width: 44px; height: 44px; background: linear-gradient(135deg, var(--bs-${activityColor}) 0%, var(--bs-${activityColor}) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <i class="fas ${activityIcon} text-white" style="font-size: 0.9rem;"></i>
                </div>
                <div class="timeline-content" style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-1" style="color: #374151; font-weight: 600;">${description}</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">${timeAgoText}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle me-2 text-secondary" style="font-size: 0.875rem;"></i>
                        <span class="text-muted" style="font-size: 0.875rem;">${userName}</span>
                        <span class="badge bg-light text-dark ms-auto" style="font-size: 0.7rem;">${activity.action}</span>
                    </div>
                    ${activity.details && activity.details !== description ? `
                        <div class="mt-2">
                            <small class="text-muted">${activity.details}</small>
                        </div>
                    ` : ''}
                </div>
                ${index < activities.length - 1 ? '<div class="timeline-line" style="position: absolute; left: 22px; top: 44px; width: 2px; height: 30px; background: #e2e8f0;"></div>' : ''}
            </div>
        `;
    });
    html += '</div>';
    
    document.getElementById('activityLog').innerHTML = html;
}

function loadProjectComponents() {
    $.ajax({
        url: '<?= base_url('projects/get_project_scopes') ?>',
        method: 'GET',
        data: {
            project_id: projectId
        },
        dataType: 'json',
        success: function(data) {
            if (data.success && data.scopes && data.scopes.length > 0) {
                renderProjectComponents(data.scopes);
            } else {
                document.getElementById('projectComponents').innerHTML = '<div class="text-center text-muted py-3">No components found.</div>';
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading project components:', error);
            document.getElementById('projectComponents').innerHTML = '<div class="text-center text-muted py-3">Error loading components.</div>';
        }
    });
}

function renderProjectComponents(scopes) {
    // Clear the loading state first
    $('#projectComponents').empty();
    
    let hasComponents = false;
    
    scopes.forEach(scope => {
        if (scope.templates && scope.templates.length > 0) {
            // Sort templates by ID
            scope.templates.sort((a, b) => parseInt(a.id) - parseInt(b.id));
            
            hasComponents = true;
            
            scope.templates.forEach(template => {
                // Get progress for this template
                $.ajax({
                    url: '<?= base_url('projects/get_tasks_by_template/') ?>' + template.id + '/' + projectId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(taskRes) {
                        let progress = 0;
                        if (taskRes.success && Array.isArray(taskRes.tasks) && taskRes.tasks.length > 0) {
                            let sum = 0;
                            let count = 0;
                            taskRes.tasks.forEach(t => {
                                Object.values(t).forEach(val => {
                                    if (typeof val === 'string' && val.trim().endsWith('%')) {
                                        let num = parseFloat(val.replace(/[^\d.]/g, ''));
                                        if (!isNaN(num)) {
                                            sum += num;
                                            count++;
                                        }
                                    }
                                });
                            });
                            if (count > 0) {
                                progress = Math.round(sum / count);
                            }
                        }

                        const componentHtml = `
                            <div class="component-item d-flex align-items-center justify-content-between py-2 px-3 mb-2" 
                                 data-component-id="${template.id}"
                                 style="border-radius: 0.5rem; border: 1px solid #e2e8f0; cursor: pointer; transition: background-color 0.2s;"
                                 onmouseover="this.style.backgroundColor='#f8fafc'" 
                                 onmouseout="this.style.backgroundColor='white'"
                                 onclick="window.location.href='<?= base_url('activity/activity_dynamic/') ?>${template.id}?project_id=${projectId}'">>
                                <div class="component-info d-flex align-items-center">
                                    <span class="component-name" style="font-weight: 500; color: #374151;">${template.name}</span>
                                </div>
                                <div class="component-actions d-flex align-items-center gap-2">
                                    <div class="progress-indicator d-flex flex-column align-items-center">
                                        <div style="width:36px; height:36px; position:relative;">
                                            <svg width="36" height="36" viewBox="0 0 36 36">
                                                <circle cx="18" cy="18" r="14" stroke="#e2e8f0" stroke-width="4" fill="none" />
                                                <circle cx="18" cy="18" r="14" stroke="${progress === 100 ? '#10b981' : '#06b6d4'}" stroke-width="4" fill="none" stroke-dasharray="${Math.PI * 2 * 14}" stroke-dashoffset="${Math.PI * 2 * 14 * (1 - progress / 100)}" stroke-linecap="round" transform="rotate(-90 18 18)" />
                                            </svg>
                                            <span style="position:absolute; left:0; right:0; top:0; bottom:0; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; color:#4a5568;">${progress}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        $('#projectComponents').append(componentHtml);
                        
                        // Re-sort components by ID after each append
                        sortComponentsByID();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading template progress:', error);
                        const componentHtml = `
                            <div class="component-item d-flex align-items-center justify-content-between py-2 px-3 mb-2" 
                                 data-component-id="${template.id}"
                                 style="border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                                <div class="component-info d-flex align-items-center">
                                    <span class="component-name" style="font-weight: 500; color: #374151;">${template.name}</span>
                                </div>
                                <div class="component-actions d-flex align-items-center gap-2">
                                    <div class="progress-indicator d-flex flex-column align-items-center">
                                        <div style="width:36px; height:36px; position:relative; display:flex; align-items:center; justify-content:center;">
                                            <span style="font-size:0.75rem; font-weight:700; color:#6b7280;">N/A</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#projectComponents').append(componentHtml);
                        
                        // Re-sort components by ID after each append
                        sortComponentsByID();
                    }
                });
            });
        }
    });
    
    // If no components were found at all
    if (!hasComponents) {
        document.getElementById('projectComponents').innerHTML = '<div class="text-center text-muted py-3">No components found.</div>';
    }
}

// Function to sort components by ID
function sortComponentsByID() {
    const container = $('#projectComponents');
    const components = container.find('.component-item').toArray();
    
    components.sort((a, b) => {
        const idA = parseInt($(a).data('component-id'));
        const idB = parseInt($(b).data('component-id'));
        return idA - idB;
    });
    
    container.empty();
    components.forEach(component => {
        container.append(component);
    });
}

// Prevent ReferenceError if renderRecentActivity is missing
if (typeof renderRecentActivity !== 'function') {
    function renderRecentActivity(activities) {
        // Load activity in the overview tab's recent activity section
        if (!activities || activities.length === 0) {
            document.getElementById('recentActivity').innerHTML = '<div class="text-center text-muted py-3">No recent activity.</div>';
            return;
        }
        
        let html = '';
        activities.slice(0, 5).forEach(activity => { // Show only first 5 activities
            const timeAgoText = timeAgo(activity.created_at || activity.date);
            html += `
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-circle text-primary me-2" style="font-size: 0.5rem;"></i>
                    <div class="flex-grow-1">
                        <div class="small">${activity.description || activity.message || 'Activity'}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">${timeAgoText}</div>
                    </div>
                </div>
            `;
        });
        
        document.getElementById('recentActivity').innerHTML = html || '<div class="text-center text-muted py-3">No recent activity.</div>';
    }
}
// On page load
document.addEventListener('DOMContentLoaded', function() {
    window.baseUrl = '<?= base_url() ?>'.replace(/\/$/, '');
    loadProjectDetails();
    loadProjectStats();
    
    // Load project components for overview tab
    loadProjectComponents();
    
    showTab('overview');
});
</script>
