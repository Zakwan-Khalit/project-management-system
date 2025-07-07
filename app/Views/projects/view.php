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
                        <span style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; padding: 0.5rem 1rem; border-radius: 1rem; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;" id="projectStatus">Loading</span>
                        <span style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; padding: 0.5rem 1rem; border-radius: 1rem; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;" id="projectPriority">Loading</span>
                        <span style="color: #6b7280; font-size: 0.9rem; font-weight: 500;" id="projectOwner">Owner: Loading...</span>
                    </div>
                    <p style="color: #6b7280; line-height: 1.6; margin: 0; font-size: 1rem;" id="projectDescription">Loading project description...</p>
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
                            <a href="#" onclick="createTask()" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: #495057; text-decoration: none; transition: background-color 0.2s ease;" 
                               onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.color='#0d6efd'"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#495057'">
                                <i class="fas fa-tasks" style="width: 20px;"></i>
                                <span>New Task</span>
                            </a>
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

    <!-- Project Overview Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- Total Tasks Card -->
        <div style="background: white; border-radius: 1rem; padding: 2rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; line-height: 1;" id="totalTasks">0</div>
                    <p style="color: #6b7280; font-weight: 600; margin: 0; font-size: 0.95rem;">Total Tasks</p>
                </div>
                <i class="fas fa-tasks" style="color: #e5e7eb; font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>

        <!-- Completed Tasks Card -->
        <div style="background: white; border-radius: 1rem; padding: 2rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);"></div>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; line-height: 1;" id="completedTasks">0</div>
                    <p style="color: #6b7280; font-weight: 600; margin: 0; font-size: 0.95rem;">Completed</p>
                </div>
                <i class="fas fa-check-circle" style="color: #e5e7eb; font-size: 3rem; opacity: 0.3;"></i>
            </div>
        </div>

        <!-- Team Members Card -->
        <div style="background: white; border-radius: 1rem; padding: 2rem 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'">
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
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'">
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
                <button onclick="showTab('tasks')" id="tasks-tab" style="background: transparent; color: #6b7280; border: none; padding: 1rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; border-radius: 0; border-bottom: 3px solid transparent; white-space: nowrap; min-width: fit-content;"
                        onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(102,126,234,0.1)'; this.style.color='#667eea'; }"
                        onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; this.style.color='#6b7280'; }">
                    <i class="fas fa-tasks"></i>
                    Tasks
                </button>
                <button onclick="showTab('kanban')" id="kanban-tab" style="background: transparent; color: #6b7280; border: none; padding: 1rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; border-radius: 0; border-bottom: 3px solid transparent; white-space: nowrap; min-width: fit-content;"
                        onmouseover="if(!this.classList.contains('active')) { this.style.background='rgba(102,126,234,0.1)'; this.style.color='#667eea'; }"
                        onmouseout="if(!this.classList.contains('active')) { this.style.background='transparent'; this.style.color='#6b7280'; }">
                    <i class="fas fa-columns"></i>
                    Kanban
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
            
            <!-- Overview Tab -->
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

                        <!-- Progress Chart -->
                        <div style="margin-bottom: 2rem;">
                            <h3 style="margin: 0 0 1rem 0; font-size: 1.25rem; font-weight: 600; color: #374151; font-family: 'Poppins', sans-serif;">Progress Overview</h3>
                            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.5rem;">
                                <canvas id="progressChart" style="height: 300px; width: 100%;"></canvas>
                            </div>
                        </div>

                        <!-- Task Distribution -->
                        <div style="margin-bottom: 2rem;">
                            <h3 style="margin: 0 0 1rem 0; font-size: 1.25rem; font-weight: 600; color: #374151; font-family: 'Poppins', sans-serif;">Task Distribution</h3>
                            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.5rem;">
                                <canvas id="taskDistributionChart" style="height: 300px; width: 100%;"></canvas>
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
                                            </div>
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

                        <!-- Tasks Tab -->
                        <div class="tab-pane fade" id="tasks" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Project Tasks</h5>
                                <button class="btn btn-primary" onclick="createTask()">
                                    <i class="fas fa-plus me-1"></i>
                                    New Task
                                </button>
                            </div>
                            <div id="tasksList">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Loading tasks...
                                </div>
                            </div>
                        </div>

                        <!-- Kanban Tab -->
                        <div class="tab-pane fade" id="kanban" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Kanban Board</h5>
                                <button class="btn btn-primary" onclick="createTask()">
                                    <i class="fas fa-plus me-1"></i>
                                    New Task
                                </button>
                            </div>
                            <div id="kanbanBoard">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Loading kanban board...
                                </div>
                            </div>
                        </div>

                        <!-- Team Tab -->
                        <div class="tab-pane fade" id="team" role="tabpanel">
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
                        <div class="tab-pane fade" id="files" role="tabpanel">
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
                        <div class="tab-pane fade" id="activity" role="tabpanel">
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
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Create New Task
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createTaskForm">
                <input type="hidden" id="taskProjectId" name="project_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Task Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="taskTitle" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="taskDescription" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="taskStatus" class="form-label">Status</label>
                                <select class="form-select" id="taskStatus" name="status">
                                    <option value="todo">To Do</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="review">Review</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="taskPriority" class="form-label">Priority</label>
                                <select class="form-select" id="taskPriority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="taskDueDate" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="taskDueDate" name="due_date">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="taskAssignee" class="form-label">Assignee</label>
                        <select class="form-select" id="taskAssignee" name="assigned_to">
                            <option value="">Unassigned</option>
                            <!-- Options will be loaded via AJAX -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Get project ID from URL
const projectId = window.location.pathname.split('/').pop();
let project = null;

document.addEventListener('DOMContentLoaded', function() {
    loadProjectDetails();
    
    // Load tab content when tab is shown
    document.querySelectorAll('#projectTabs button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(event) {
            const targetTab = event.target.getAttribute('data-bs-target');
            loadTabContent(targetTab.replace('#', ''));
        });
    });
});

// Load project details
function loadProjectDetails() {
    apiCall(`api/projects/${projectId}`, 'GET')
        .then(data => {
            if (data.success) {
                project = data.project;
                populateProjectDetails(project);
                loadProjectStats();
                loadTabContent('overview');
            } else {
                Swal.fire('Error', 'Project not found', 'error')
                    .then(() => window.location.href = '<?= base_url('projects') ?>');
            }
        })
        .catch(error => {
            console.error('Error loading project:', error);
            Swal.fire('Error', 'Failed to load project details', 'error');
        });
}

// Populate project details
function populateProjectDetails(project) {
    document.getElementById('projectBreadcrumb').textContent = project.name;
    document.getElementById('projectTitle').innerHTML = `
        <i class="fas fa-project-diagram me-2"></i>
        ${project.name}
    `;
    
    const statusClass = getStatusClass(project.status);
    const priorityClass = getPriorityClass(project.priority);
    
    document.getElementById('projectStatus').className = `badge bg-${statusClass}`;
    document.getElementById('projectStatus').textContent = project.status.replace('_', ' ');
    
    document.getElementById('projectPriority').className = `badge bg-${priorityClass}`;
    document.getElementById('projectPriority').textContent = project.priority;
    
    document.getElementById('projectOwner').textContent = `Owner: ${project.owner_name}`;
    document.getElementById('projectDescription').textContent = project.description || 'No description provided';
    
    document.getElementById('projectStartDate').textContent = project.start_date ? 
        new Date(project.start_date).toLocaleDateString() : 'Not set';
    document.getElementById('projectEndDate').textContent = project.end_date ? 
        new Date(project.end_date).toLocaleDateString() : 'Not set';
    document.getElementById('projectBudget').textContent = project.budget ? 
        `$${parseFloat(project.budget).toLocaleString()}` : 'Not set';
    document.getElementById('projectClient').textContent = project.client || 'Not specified';
    
    const progress = project.progress || 0;
    document.getElementById('projectProgressBar').style.width = `${progress}%`;
    document.getElementById('projectProgressText').textContent = `${progress}%`;
}

// Load project stats
function loadProjectStats() {
    fetch(`<?= base_url('api/projects') ?>/${projectId}/stats`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalTasks').textContent = data.stats.total_tasks || 0;
                document.getElementById('completedTasks').textContent = data.stats.completed_tasks || 0;
                document.getElementById('teamMembers').textContent = data.stats.team_members || 0;
                document.getElementById('daysLeft').textContent = data.stats.days_left || 0;
            }
        });
}

// Load tab content
function loadTabContent(tab) {
    switch(tab) {
        case 'overview':
            loadOverviewCharts();
            loadRecentActivity();
            break;
        case 'tasks':
            loadTasks();
            break;
        case 'kanban':
            loadKanbanBoard();
            break;
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

// Load overview charts
function loadOverviewCharts() {
    // Progress Chart
    fetch(`<?= base_url('api/projects') ?>/${projectId}/progress-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createProgressChart(data.chartData);
            }
        });
    
    // Task Distribution Chart
    fetch(`<?= base_url('api/projects') ?>/${projectId}/task-distribution`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createTaskDistributionChart(data.chartData);
            }
        });
}

// Create progress chart
function createProgressChart(data) {
    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Progress %',
                data: data.values,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// Create task distribution chart
function createTaskDistributionChart(data) {
    const ctx = document.getElementById('taskDistributionChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: [
                    '#6c757d', // todo
                    '#0d6efd', // in_progress
                    '#ffc107', // review
                    '#198754'  // done
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Load recent activity
function loadRecentActivity() {
    fetch(`<?= base_url('api/projects') ?>/${projectId}/recent-activity`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderRecentActivity(data.activities);
            }
        });
}

// Render recent activity
function renderRecentActivity(activities) {
    const container = document.getElementById('recentActivity');
    if (activities.length === 0) {
        container.innerHTML = '<div class="text-center text-muted py-3">No recent activity</div>';
        return;
    }
    
    container.innerHTML = activities.map(activity => `
        <div class="d-flex mb-3">
            <div class="flex-shrink-0">
                <div class="activity-icon bg-${getActivityColor(activity.action)} rounded-circle">
                    <i class="fas ${getActivityIcon(activity.action)}"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="small text-muted">${timeAgo(activity.created_at)}</div>
                <div>${activity.description}</div>
                <small class="text-muted">by ${activity.user_name}</small>
            </div>
        </div>
    `).join('');
}

// Load tasks
function loadTasks() {
    fetch(`<?= base_url('api/projects') ?>/${projectId}/tasks`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderTasks(data.tasks);
            }
        });
}

// Render tasks
function renderTasks(tasks) {
    const container = document.getElementById('tasksList');
    if (tasks.length === 0) {
        container.innerHTML = '<div class="text-center text-muted py-4">No tasks yet. Create your first task!</div>';
        return;
    }
    
    container.innerHTML = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Assignee</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${tasks.map(task => `
                        <tr>
                            <td>
                                <div class="fw-bold">${task.title}</div>
                                <small class="text-muted">${task.description || ''}</small>
                            </td>
                            <td>
                                <span class="badge bg-${getStatusClass(task.status)}">${task.status.replace('_', ' ')}</span>
                            </td>
                            <td>
                                <span class="badge bg-${getPriorityClass(task.priority)}">${task.priority}</span>
                            </td>
                            <td>
                                ${task.assignee_name || '<span class="text-muted">Unassigned</span>'}
                            </td>
                            <td>
                                ${task.due_date ? new Date(task.due_date).toLocaleDateString() : 
                                  '<span class="text-muted">No due date</span>'}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="editTask(${task.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteTask(${task.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

// Utility functions
function getStatusClass(status) {
    const classes = {
        'planning': 'info',
        'in_progress': 'primary',
        'on_hold': 'warning',
        'completed': 'success',
        'cancelled': 'danger',
        'todo': 'secondary',
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

// Action functions
function editProject() {
    // Redirect to edit page or open modal
    window.location.href = `<?= base_url('projects') ?>/${projectId}/edit`;
}

function createTask() {
    document.getElementById('taskProjectId').value = projectId;
    loadTaskAssignees();
    new bootstrap.Modal(document.getElementById('createTaskModal')).show();
}

function loadTaskAssignees() {
    fetch(`<?= base_url('api/projects') ?>/${projectId}/members`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('taskAssignee');
                select.innerHTML = '<option value="">Unassigned</option>';
                data.members.forEach(member => {
                    const option = document.createElement('option');
                    option.value = member.user_id;
                    option.textContent = `${member.first_name} ${member.last_name}`;
                    select.appendChild(option);
                });
            }
        });
}

// Handle create task form
document.getElementById('createTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    fetch('<?= base_url('api/tasks') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success', 'Task created successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('createTaskModal')).hide();
            loadTasks();
            loadProjectStats();
            e.target.reset();
        } else {
            Swal.fire('Error', data.message || 'Failed to create task', 'error');
        }
    });
});

// Load other tab content functions (simplified for brevity)
function loadKanbanBoard() {
    document.getElementById('kanbanBoard').innerHTML = `
        <iframe src="<?= base_url('tasks/kanban') ?>/${projectId}" 
                style="width: 100%; height: 600px; border: none;"></iframe>
    `;
}

function loadTeamMembers() {
    document.getElementById('teamMembersList').innerHTML = '<div class="text-muted">Team members functionality coming soon...</div>';
}

function loadFiles() {
    document.getElementById('filesList').innerHTML = '<div class="text-muted">File management functionality coming soon...</div>';
}

function loadActivityLog() {
    document.getElementById('activityLog').innerHTML = '<div class="text-muted">Full activity log functionality coming soon...</div>';
}

// Modern Tab Functionality
function showTab(tabName) {
    // Hide all tabs
    const tabs = ['overview', 'tasks', 'kanban', 'team', 'files', 'activity'];
    tabs.forEach(tab => {
        const tabContent = document.getElementById(tab);
        const tabButton = document.getElementById(tab + '-tab');
        
        if (tabContent) {
            tabContent.style.display = 'none';
        }
        
        if (tabButton) {
            // Reset tab button styles
            tabButton.style.background = 'transparent';
            tabButton.style.color = '#6b7280';
            tabButton.style.borderBottomColor = 'transparent';
            tabButton.classList.remove('active');
        }
    });
    
    // Show selected tab
    const selectedTab = document.getElementById(tabName);
    const selectedButton = document.getElementById(tabName + '-tab');
    
    if (selectedTab) {
        selectedTab.style.display = 'block';
    }
    
    if (selectedButton) {
        selectedButton.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        selectedButton.style.color = 'white';
        selectedButton.style.borderBottomColor = '#667eea';
        selectedButton.classList.add('active');
    }
    
    // Load content based on tab
    switch(tabName) {
        case 'tasks':
            loadTasks();
            break;
        case 'kanban':
            loadKanbanBoard();
            break;
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

// Dropdown Toggle Functionality
function toggleAddDropdown() {
    const dropdown = document.getElementById('addDropdown');
    const isVisible = dropdown.style.opacity === '1';
    
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

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('addDropdown');
    const button = e.target.closest('button[onclick="toggleAddDropdown()"]');
    
    if (!button && dropdown && dropdown.style.opacity === '1') {
        dropdown.style.opacity = '0';
        dropdown.style.visibility = 'hidden';
        dropdown.style.transform = 'translateY(-10px)';
    }
});

// Initialize default tab
document.addEventListener('DOMContentLoaded', function() {
    showTab('overview');
});
</script>
