<!-- Tasks Index Page -->
<div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">

    <!-- Header Section -->
    <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 1.5rem; padding: 2.5rem 2rem; margin-bottom: 3rem; box-shadow: 0 20px 60px rgba(72,187,120,0.2); position: relative; overflow: hidden;">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -30px; right: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
        <div style="position: absolute; bottom: -20px; left: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-tasks" style="margin-right: 0.75rem; color: rgba(255,255,255,0.9);"></i>
                    My Tasks
                </h1>
                <p style="color: rgba(255,255,255,0.95); font-size: 1.1rem; margin-bottom: 0; font-weight: 400;">
                    Manage and track your assigned tasks efficiently
                </p>
            </div>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <button onclick="createNewTask()" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: white; border-radius: 1rem; padding: 1rem 2rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.2)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                    New Task
                </button>
                <button onclick="toggleView()" id="viewToggleBtn" style="background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.3); color: white; border-radius: 1rem; padding: 1rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.25)';" onmouseout="this.style.background='rgba(255,255,255,0.15)';">
                    <i class="fas fa-th" id="viewToggleIcon"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div style="background: white; border-radius: 1rem; padding: 1.5rem 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <!-- Status Filter Tabs -->
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button onclick="filterTasks('all')" data-filter="all" class="filter-tab active" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border: none; border-radius: 0.75rem; padding: 0.75rem 1.5rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                    All Tasks
                </button>
                <button onclick="filterTasks('pending')" data-filter="pending" class="filter-tab" style="background: rgba(159,122,234,0.1); color: #9f7aea; border: 2px solid #9f7aea; border-radius: 0.75rem; padding: 0.75rem 1.5rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                    Pending
                </button>
                <button onclick="filterTasks('in_progress')" data-filter="in_progress" class="filter-tab" style="background: rgba(237,137,54,0.1); color: #ed8936; border: 2px solid #ed8936; border-radius: 0.75rem; padding: 0.75rem 1.5rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                    In Progress
                </button>
                <button onclick="filterTasks('completed')" data-filter="completed" class="filter-tab" style="background: rgba(72,187,120,0.1); color: #48bb78; border: 2px solid #48bb78; border-radius: 0.75rem; padding: 0.75rem 1.5rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                    Completed
                </button>
            </div>

            <!-- Search Box -->
            <div style="margin-left: auto; position: relative;">
                <input type="text" id="searchInput" placeholder="Search tasks..." style="background: #f7fafc; border: 2px solid #e2e8f0; border-radius: 0.75rem; padding: 0.75rem 3rem 0.75rem 1rem; color: #4a5568; font-size: 0.9rem; outline: none; transition: all 0.3s ease; width: 250px;" onfocus="this.style.borderColor='#48bb78'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(72,187,120,0.1)';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f7fafc'; this.style.boxShadow='none';">
                <i class="fas fa-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.9rem;"></i>
            </div>
        </div>

        <!-- Additional Filters -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <select id="projectFilter" style="background: #f7fafc; border: 2px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem 1rem; color: #4a5568; font-size: 0.9rem; outline: none;">
                <option value="">All Projects</option>
                <!-- Projects will be loaded dynamically -->
            </select>
            <select id="priorityFilter" style="background: #f7fafc; border: 2px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem 1rem; color: #4a5568; font-size: 0.9rem; outline: none;">
                <option value="">All Priorities</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>
            <select id="assigneeFilter" style="background: #f7fafc; border: 2px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem 1rem; color: #4a5568; font-size: 0.9rem; outline: none;">
                <option value="">All Assignees</option>
                <!-- Assignees will be loaded dynamically -->
            </select>
            <button onclick="clearFilters()" style="background: #f56565; color: white; border: none; border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#e53e3e';" onmouseout="this.style.background='#f56565';">
                <i class="fas fa-times" style="margin-right: 0.5rem;"></i>
                Clear
            </button>
        </div>
    </div>

    <!-- Tasks Container -->
    <div id="tasksContainer">
        <!-- Grid View -->
        <div id="gridView" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
            <!-- Loading State -->
            <div id="loadingSpinner" style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
                <div style="width: 60px; height: 60px; border: 4px solid #e2e8f0; border-top: 4px solid #48bb78; border-radius: 50%; margin: 0 auto 1rem auto; animation: spin 1s linear infinite;"></div>
                <p style="color: #6b7280; font-size: 1.1rem; font-weight: 500;">Loading tasks...</p>
            </div>
        </div>

        <!-- Table View -->
        <div id="tableView" style="display: none; background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th style="padding: 1rem; font-weight: 600; color: #4a5568;">
                                <input type="checkbox" id="selectAllTasks" style="cursor: pointer;">
                            </th>
                            <th style="padding: 1rem; font-weight: 600; color: #4a5568;">Task</th>
                            <th style="padding: 1rem; font-weight: 600; color: #4a5568;">Project</th>
                            <th style="padding: 1rem; font-weight: 600; color: #4a5568;">Status</th>
                            <th style="padding: 1rem; font-weight: 600; color: #4a5568;">Priority</th>
                            <th style="padding: 1rem; font-weight: 600; color: #4a5568;">Assignee</th>
                            <th style="padding: 1rem; font-weight: 600; color: #4a5568;">Due Date</th>
                            <th style="padding: 1rem; font-weight: 600; color: #4a5568;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tasksTableBody">
                        <tr>
                            <td colspan="8" style="text-align: center; color: #9ca3af; padding: 4rem;">
                                <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; margin-bottom: 1rem;"></i>
                                <br>Loading tasks...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div id="paginationContainer" style="display: flex; justify-content: center; margin-top: 3rem;">
        <!-- Pagination will be generated by JavaScript -->
    </div>

    <!-- No Tasks Message -->
    <div id="noTasksMessage" style="display: none; text-align: center; padding: 4rem; background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div style="color: #9ca3af; font-size: 4rem; margin-bottom: 1rem;">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <h3 style="color: #4a5568; font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem;">No Tasks Found</h3>
        <p style="color: #9ca3af; font-size: 1rem; margin-bottom: 2rem;">You don't have any tasks matching the current filters.</p>
        <button onclick="createNewTask()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border: none; border-radius: 0.75rem; padding: 1rem 2rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
            <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
            Create Your First Task
        </button>
    </div>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.filter-tab:hover {
    transform: scale(1.05);
}

.filter-tab.active {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%) !important;
    color: white !important;
}

@media (max-width: 768px) {
    #tasksContainer #gridView {
        grid-template-columns: 1fr;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
}
</style>

<script>
let currentView = 'grid';
let currentFilters = {
    status: 'all',
    search: '',
    project_id: '',
    priority: '',
    assigned_to: ''
};
let currentPage = 1;
let tasksData = [];

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadTasks();
    initializeEventListeners();
    loadFilterOptions();
});

function initializeEventListeners() {
    // Search input
    document.getElementById('searchInput').addEventListener('input', debounce(function() {
        currentFilters.search = this.value;
        currentPage = 1;
        loadTasks();
    }, 300));

    // Filter dropdowns
    document.getElementById('projectFilter').addEventListener('change', function() {
        currentFilters.project_id = this.value;
        currentPage = 1;
        loadTasks();
    });

    document.getElementById('priorityFilter').addEventListener('change', function() {
        currentFilters.priority = this.value;
        currentPage = 1;
        loadTasks();
    });

    document.getElementById('assigneeFilter').addEventListener('change', function() {
        currentFilters.assigned_to = this.value;
        currentPage = 1;
        loadTasks();
    });

    // Select all checkbox
    document.getElementById('selectAllTasks').addEventListener('change', function() {
        const taskCheckboxes = document.querySelectorAll('.task-checkbox');
        taskCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
}

function loadTasks() {
    showLoading();
    
    // Build query parameters
    const params = new URLSearchParams({
        page: currentPage,
        ...currentFilters
    });

    fetch(`<?= base_url('tasks/getTasks') ?>?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                tasksData = data.tasks;
                renderTasks(data.tasks);
                hideLoading();
            } else {
                showError('Failed to load tasks');
            }
        })
        .catch(error => {
            console.error('Error loading tasks:', error);
            showError('Failed to load tasks');
        });
}

function renderTasks(tasks) {
    if (tasks.length === 0) {
        showNoTasks();
        return;
    }

    hideNoTasks();
    
    if (currentView === 'grid') {
        renderGridView(tasks);
    } else {
        renderTableView(tasks);
    }
}

function renderGridView(tasks) {
    const gridContainer = document.getElementById('gridView');
    
    gridContainer.innerHTML = tasks.map(task => {
        const statusColor = getStatusColor(task.status);
        const priorityColor = getPriorityColor(task.priority);
        const isOverdue = task.due_date && new Date(task.due_date) < new Date() && task.status !== 'completed';
        
        return `
            <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem; box-shadow: 0 8px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; ${isOverdue ? 'border-left: 4px solid #f56565;' : ''}" onmouseover="this.style.transform='translateY(-8px) scale(1.02)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 8px 30px rgba(0,0,0,0.08)';" onclick="viewTask(${task.id})">
                
                <!-- Task Header -->
                <div style="background: ${statusColor}; color: white; padding: 1.5rem; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
                    <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <h3 style="color: white; font-size: 1.3rem; font-weight: 700; margin: 0 0 0.5rem 0; font-family: 'Poppins', sans-serif;">${escapeHtml(task.title)}</h3>
                            <span style="background: rgba(255,255,255,0.2); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">${task.status.replace('_', ' ')}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="background: rgba(255,255,255,0.2); color: white; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600;">
                                <i class="fas fa-flag" style="margin-right: 0.25rem;"></i>
                                ${task.priority}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task Content -->
                <div style="padding: 1.5rem;">
                    <p style="color: #6b7280; font-size: 0.95rem; line-height: 1.6; margin: 0 0 1.5rem 0;">
                        ${task.description ? escapeHtml(task.description.substring(0, 100) + (task.description.length > 100 ? '...' : '')) : 'No description available'}
                    </p>
                    
                    <!-- Task Meta Info -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 0.75rem;">
                        <div style="text-align: center;">
                            <div style="color: #9ca3af; font-size: 0.8rem; margin-bottom: 0.25rem;">Project</div>
                            <div style="color: #4a5568; font-weight: 600; font-size: 0.9rem;">${escapeHtml(task.project_name || 'No Project')}</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #9ca3af; font-size: 0.8rem; margin-bottom: 0.25rem;">Due Date</div>
                            <div style="color: ${isOverdue ? '#f56565' : '#4a5568'}; font-weight: 600; font-size: 0.9rem;">${task.due_date ? formatDate(task.due_date) : 'No due date'}</div>
                        </div>
                    </div>
                    
                    <!-- Assignee Info -->
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 0.75rem;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: ${statusColor}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                            ${getInitials((task.assigned_first_name && task.assigned_last_name) ? task.assigned_first_name + ' ' + task.assigned_last_name : 'Unassigned')}
                        </div>
                        <div>
                            <div style="color: #4a5568; font-weight: 600; font-size: 0.9rem;">${escapeHtml((task.assigned_first_name && task.assigned_last_name) ? task.assigned_first_name + ' ' + task.assigned_last_name : 'Unassigned')}</div>
                            <div style="color: #9ca3af; font-size: 0.8rem;">Assigned to</div>
                        </div>
                    </div>
                </div>
                
                <!-- Task Actions -->
                <div style="padding: 0 1.5rem 1.5rem 1.5rem; display: flex; gap: 0.75rem;">
                    <button onclick="event.stopPropagation(); viewTask(${task.id})" style="flex: 1; background: ${statusColor}; color: white; border: none; border-radius: 0.75rem; padding: 0.75rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'; this.style.opacity='0.9';" onmouseout="this.style.transform='scale(1)'; this.style.opacity='1';">
                        <i class="fas fa-eye" style="margin-right: 0.5rem;"></i>
                        View
                    </button>
                    <button onclick="event.stopPropagation(); editTask(${task.id})" style="flex: 1; background: rgba(72,187,120,0.1); color: #48bb78; border: 2px solid #48bb78; border-radius: 0.75rem; padding: 0.75rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#48bb78'; this.style.color='white';" onmouseout="this.style.background='rgba(72,187,120,0.1)'; this.style.color='#48bb78';">
                        <i class="fas fa-edit" style="margin-right: 0.5rem;"></i>
                        Edit
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

function renderTableView(tasks) {
    const tableBody = document.getElementById('tasksTableBody');
    
    tableBody.innerHTML = tasks.map(task => {
        const isOverdue = task.due_date && new Date(task.due_date) < new Date() && task.status !== 'completed';
        
        return `
            <tr style="cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#f8fafc';" onmouseout="this.style.backgroundColor='white';" onclick="viewTask(${task.id})">
                <td style="padding: 1rem;">
                    <input type="checkbox" class="task-checkbox" value="${task.id}" onclick="event.stopPropagation();">
                </td>
                <td style="padding: 1rem;">
                    <div style="font-weight: 600; color: #4a5568; margin-bottom: 0.25rem;">${escapeHtml(task.title)}</div>
                    <div style="color: #9ca3af; font-size: 0.9rem;">${task.description ? escapeHtml(task.description.substring(0, 50) + '...') : 'No description'}</div>
                </td>
                <td style="padding: 1rem; color: #6b7280;">${escapeHtml(task.project_name || 'No Project')}</td>
                <td style="padding: 1rem;">
                    <span style="background: ${getStatusColor(task.status, true)}; color: ${getStatusColor(task.status)}; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                        ${task.status.replace('_', ' ')}
                    </span>
                </td>
                <td style="padding: 1rem;">
                    <span style="background: ${getPriorityColor(task.priority, true)}; color: ${getPriorityColor(task.priority)}; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600;">
                        ${task.priority}
                    </span>
                </td>
                <td style="padding: 1rem; color: #6b7280;">${escapeHtml((task.assigned_first_name && task.assigned_last_name) ? task.assigned_first_name + ' ' + task.assigned_last_name : 'Unassigned')}</td>
                <td style="padding: 1rem; color: ${isOverdue ? '#f56565' : '#6b7280'}; ${isOverdue ? 'font-weight: 600;' : ''}">${task.due_date ? formatDate(task.due_date) : 'No due date'}</td>
                <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem;">
                        <button onclick="event.stopPropagation(); viewTask(${task.id})" style="background: #48bb78; color: white; border: none; border-radius: 0.5rem; padding: 0.5rem; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#38a169';" onmouseout="this.style.background='#48bb78';">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="event.stopPropagation(); editTask(${task.id})" style="background: #ed8936; color: white; border: none; border-radius: 0.5rem; padding: 0.5rem; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#dd6b20';" onmouseout="this.style.background='#ed8936';">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');
    
    if (!pagination || pagination.total_pages <= 1) {
        container.innerHTML = '';
        return;
    }
    
    let html = '<div style="display: flex; gap: 0.5rem; align-items: center;">';
    
    // Previous button
    if (pagination.current_page > 1) {
        html += `<button onclick="changePage(${pagination.current_page - 1})" style="background: white; color: #48bb78; border: 2px solid #48bb78; border-radius: 0.5rem; padding: 0.5rem 1rem; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#48bb78'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#48bb78';">
            <i class="fas fa-chevron-left"></i> Previous
        </button>`;
    }
    
    // Page numbers
    for (let i = 1; i <= pagination.total_pages; i++) {
        if (i === pagination.current_page) {
            html += `<button style="background: #48bb78; color: white; border: 2px solid #48bb78; border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 600;">${i}</button>`;
        } else {
            html += `<button onclick="changePage(${i})" style="background: white; color: #6b7280; border: 2px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem 1rem; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.borderColor='#48bb78'; this.style.color='#48bb78';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#6b7280';">${i}</button>`;
        }
    }
    
    // Next button
    if (pagination.current_page < pagination.total_pages) {
        html += `<button onclick="changePage(${pagination.current_page + 1})" style="background: white; color: #48bb78; border: 2px solid #48bb78; border-radius: 0.5rem; padding: 0.5rem 1rem; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#48bb78'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#48bb78';">
            Next <i class="fas fa-chevron-right"></i>
        </button>`;
    }
    
    html += '</div>';
    container.innerHTML = html;
}

// Utility Functions
function filterTasks(status) {
    currentFilters.status = status;
    currentPage = 1;
    
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.filter === status) {
            tab.classList.add('active');
        }
    });
    
    loadTasks();
}

function toggleView() {
    currentView = currentView === 'grid' ? 'table' : 'grid';
    
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const toggleIcon = document.getElementById('viewToggleIcon');
    
    if (currentView === 'grid') {
        gridView.style.display = 'grid';
        tableView.style.display = 'none';
        toggleIcon.className = 'fas fa-th';
    } else {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        toggleIcon.className = 'fas fa-list';
    }
    
    renderTasks(tasksData);
}

function clearFilters() {
    currentFilters = {
        status: 'all',
        search: '',
        project_id: '',
        priority: '',
        assigned_to: ''
    };
    currentPage = 1;
    
    // Reset form elements
    document.getElementById('searchInput').value = '';
    document.getElementById('projectFilter').value = '';
    document.getElementById('priorityFilter').value = '';
    document.getElementById('assigneeFilter').value = '';
    
    // Reset active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.filter === 'all') {
            tab.classList.add('active');
        }
    });
    
    loadTasks();
}

function changePage(page) {
    currentPage = page;
    loadTasks();
}

function showLoading() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    if (loadingSpinner) {
        loadingSpinner.style.display = 'block';
    }
    
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    
    if (currentView === 'grid') {
        gridView.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 4rem;"><div style="width: 60px; height: 60px; border: 4px solid #e2e8f0; border-top: 4px solid #48bb78; border-radius: 50%; margin: 0 auto 1rem auto; animation: spin 1s linear infinite;"></div><p style="color: #6b7280; font-size: 1.1rem; font-weight: 500;">Loading tasks...</p></div>';
    } else {
        document.getElementById('tasksTableBody').innerHTML = '<tr><td colspan="8" style="text-align: center; color: #9ca3af; padding: 4rem;"><i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; margin-bottom: 1rem;"></i><br>Loading tasks...</td></tr>';
    }
    
    hideNoTasks();
}

function hideLoading() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    if (loadingSpinner) {
        loadingSpinner.style.display = 'none';
    }
}

function showNoTasks() {
    document.getElementById('tasksContainer').style.display = 'none';
    document.getElementById('noTasksMessage').style.display = 'block';
    document.getElementById('paginationContainer').innerHTML = '';
}

function hideNoTasks() {
    document.getElementById('tasksContainer').style.display = 'block';
    document.getElementById('noTasksMessage').style.display = 'none';
}

function showError(message) {
    hideLoading();
    // You can implement SweetAlert2 or another notification system here
    console.error(message);
}

// Action Functions
function createNewTask() {
    window.location.href = '<?= base_url('tasks/create') ?>';
}

function viewTask(taskId) {
    window.location.href = '<?= base_url('tasks/view/') ?>' + taskId;
}

function editTask(taskId) {
    window.location.href = '<?= base_url('tasks/edit/') ?>' + taskId;
}

// Helper Functions
function getStatusColor(status, light = false) {
    const colors = {
        'pending': light ? 'rgba(159,122,234,0.1)' : 'linear-gradient(135deg, #9f7aea 0%, #805ad5 100%)',
        'in_progress': light ? 'rgba(237,137,54,0.1)' : 'linear-gradient(135deg, #ed8936 0%, #dd6b20 100%)',
        'review': light ? 'rgba(246,173,85,0.1)' : 'linear-gradient(135deg, #f6ad55 0%, #ed8936 100%)',
        'completed': light ? 'rgba(72,187,120,0.1)' : 'linear-gradient(135deg, #48bb78 0%, #38a169 100%)',
        'done': light ? 'rgba(72,187,120,0.1)' : 'linear-gradient(135deg, #48bb78 0%, #38a169 100%)'
    };
    return colors[status] || (light ? 'rgba(107,114,128,0.1)' : 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)');
}

function getPriorityColor(priority, light = false) {
    const colors = {
        'low': light ? 'rgba(72,187,120,0.1)' : '#48bb78',
        'medium': light ? 'rgba(246,173,85,0.1)' : '#f6ad55',
        'high': light ? 'rgba(237,137,54,0.1)' : '#ed8936',
        'critical': light ? 'rgba(245,101,101,0.1)' : '#f56565'
    };
    return colors[priority] || (light ? 'rgba(107,114,128,0.1)' : '#6b7280');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function getInitials(name) {
    return name.split(' ').map(word => word.charAt(0)).join('').substring(0, 2).toUpperCase();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function loadFilterOptions() {
    // Load filter options from controller
    fetch('<?= base_url('tasks/getFilterOptions') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate project filter
                const projectFilter = document.getElementById('projectFilter');
                if (projectFilter) {
                    projectFilter.innerHTML = '<option value="all">All Projects</option>';
                    data.projects.forEach(project => {
                        const option = document.createElement('option');
                        option.value = project.id;
                        option.textContent = project.name;
                        projectFilter.appendChild(option);
                    });
                }

                // Populate assignee filter
                const assigneeFilter = document.getElementById('assigneeFilter');
                if (assigneeFilter) {
                    assigneeFilter.innerHTML = '<option value="all">All Assignees</option>';
                    data.users.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.first_name + ' ' + user.last_name;
                        assigneeFilter.appendChild(option);
                    });
                }
            }
        })
        .catch(error => console.error('Error loading filter options:', error));
}
</script>
