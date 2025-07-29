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
                    <h1 style="margin: 0 0 1rem 0; font-size: 2rem; font-weight: 700; color: #1f2937; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 0.75rem;" id="projectTitle">
                        <i class="fas fa-project-diagram" style="color: #667eea; font-size: 1.8rem;"></i>
                        Loading...
                    </h1>
                    <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
                        <span class="badge" id="projectStatus">Loading</span>
                        <span style="color: #6b7280; font-size: 0.9rem; font-weight: 500;" id="projectOwner">Owner: Loading...</span>
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
                    <div style="position: relative; display: inline-block;">
                        <button onclick="toggleAddDropdown()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;"
                                onmouseover="this.style.transform='translateY(-2px)'"
                                onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-plus"></i>
                            Add
                            <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                        </button>
                        <div id="addDropdown" style="position: absolute; top: 100%; right: 0; background: white; border: 1px solid #e9ecef; border-radius: 0.75rem; box-shadow: 0 10px 25px rgba(0,0,0,0.15); padding: 0.5rem 0; margin-top: 0.5rem; min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease; z-index: 1050;">
                            <!-- Removed New Task dropdown entry -->
                            <a href="#" onclick="addTeamMember()" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: #495057; text-decoration: none; transition: background-color 0.2s ease;"
                               onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.color='#0d6efd'"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#495057'">
                                <i class="fas fa-user-plus" style="width: 20px;"></i>
                                <span>Team Member</span>
                            </a>
                            <a href="#" onclick="uploadFile()" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: #495057; text-decoration: none; transition: background-color 0.2s ease;"
                               onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.color='#0d6efd'"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#495057'">
                                <i class="fas fa-paperclip" style="width: 20px;"></i>
                                <span>File Attachment</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Overview Cards: Only Team Members and Days Left remain -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Team Members Card -->
        <div style="background: white; border-radius: 1rem; padding: 2rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)';">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);"></div>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; line-height: 1;" id="teamMembers">0</div>
                    <p style="color: #6b7280; font-weight: 600; margin: 0; font-size: 0.95rem;">Team Members</p>
                </div>
                <i class="fas fa-users" style="color: #e5e7eb; font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
        <!-- Days Left Card -->
        <div style="background: white; border-radius: 1rem; padding: 2rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)';">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);"></div>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; line-height: 1;" id="daysLeft">0</div>
                    <p style="color: #6b7280; font-weight: 600; margin: 0; font-size: 0.95rem;">Days Left</p>
                </div>
                <i class="fas fa-calendar-alt" style="color: #e5e7eb; font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        
        <!-- Tab Navigation -->
        <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0; padding: 0;">
            <div style="display: flex; overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;">
                <button onclick="showTab('overview')" id="overview-tab" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 1rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; border-radius: 0; border-bottom: 3px solid #667eea; white-space: nowrap; min-width: fit-content;">
                    <i class="fas fa-chart-line"></i>
                    Overview
                </button>
                <button onclick="showTab('team')" id="team-tab" style="background: transparent; color: #6b7280; border: none; padding: 1rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; border-radius: 0; border-bottom: 3px solid transparent; white-space: nowrap; min-width: fit-content;"
                        onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(102,126,234,0.1)'; this.style.color='#667eea'; }"
                        onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; this.style.color='#6b7280'; }">
                    <i class="fas fa-users"></i>
                    Team
                </button>
                <button onclick="showTab('files')" id="files-tab" style="background: transparent; color: #6b7280; border: none; padding: 1rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; border-radius: 0; border-bottom: 3px solid transparent; white-space: nowrap; min-width: fit-content;"
                        onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(102,126,234,0.1)'; this.style.color='#667eea'; }"
                        onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; this.style.color='#6b7280'; }">
                    <i class="fas fa-folder"></i>
                    Files
                </button>
                <button onclick="showTab('activity')" id="activity-tab" style="background: transparent; color: #6b7280; border: none; padding: 1rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; border-radius: 0; border-bottom: 3px solid transparent; white-space: nowrap; min-width: fit-content;"
                        onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(102,126,234,0.1)'; this.style.color='#667eea'; }"
                        onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; this.style.color='#6b7280'; }">
                    <i class="fas fa-history"></i>
                    Activity
                </button>
            </div>
        </div>
        
        <!-- Tab Content -->
        <div style="padding: 2rem;">
            
            <!-- Overview Tab (Task-related charts removed) -->
            <div id="overview" style="display: block;">
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                    <div>
                        <!-- Project Description -->
                        <div style="margin-bottom: 2rem;">
                            <h3 style="margin: 0 0 1rem 0; font-size: 1.25rem; font-weight: 600; color: #374151; font-family: 'Poppins', sans-serif;">Project Description</h3>
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.5rem;">
                                <p style="margin: 0; color: #6b7280; line-height: 1.6;" id="projectDescriptionDetail">Loading project description...</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- Project Details -->
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
                        <!-- Recent Activity -->
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

            <!-- Files Tab -->
            <div id="files" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Project Files</h5>
                    <button class="btn btn-primary" onclick="uploadFile()">
                        <i class="fas fa-upload me-1"></i>
                        Upload File
                    </button>
                </div>
                <div id="filesList">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-spinner fa-spin"></i>
                        Loading files...
                    </div>
                </div>
            </div>

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
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Add Team Member',
            text: 'This feature is not implemented yet.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    } else {
        alert('Add Team Member: This feature is not implemented yet.');
    }
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
    const tabs = ['overview', 'team', 'files', 'activity'];
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
        case 'files':
            loadFiles();
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
            const elOwner = document.getElementById('projectOwner');
            if (elOwner) elOwner.textContent = 'Owner: ' + owner;
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
    fetch(`${baseUrl}/projects/getStats/${projectId}`)
        .then(res => res.json())
        .then(data => {
            const stats = data.stats || {};
            const elTeam = document.getElementById('teamMembers');
            if (elTeam) elTeam.textContent = stats.team_members || 0;
            const elDays = document.getElementById('daysLeft');
            if (elDays) elDays.textContent = stats.days_left || 0;
        });
}
function toggleAddDropdown() {
    const dropdown = document.getElementById('addDropdown');
    const isVisible = dropdown && dropdown.style.opacity === '1';
    if (!dropdown) return;
    if (isVisible) {
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
        dropdown.style.transform = 'translateY(-10px)';
    } else {
        dropdown.style.opacity = '1';
        dropdown.style.visibility = 'visible';
        dropdown.style.transform = 'translateY(0)';
    }
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
            if (data.success) {
                renderTeamMembers(data.members);
            } else {
                document.getElementById('teamMembersList').innerHTML = '<div class="text-center text-danger py-4">Failed to load team members.</div>';
            }
        },
        error: function() {
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
        html += `<li class="list-group-item d-flex align-items-center"><i class="fas fa-user-circle me-2 text-secondary"></i> ${m.first_name} ${m.last_name}</li>`;
    });
    html += '</ul>';
    document.getElementById('teamMembersList').innerHTML = html;
}
function loadFiles() {
    document.getElementById('filesList').innerHTML = '<div class="text-center text-muted py-4">No files uploaded for this project.</div>';
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
        error: function() {
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
