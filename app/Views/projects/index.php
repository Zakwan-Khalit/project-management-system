<!-- Projects Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1.5rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 20px 60px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
    <!-- Decorative Elements -->
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2;">
        <div>
            <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-project-diagram" style="margin-right: 1rem; color: rgba(255,255,255,0.9);"></i>
                My Projects
            </h1>
            <p style="color: rgba(255,255,255,0.95); font-size: 1.1rem; margin-bottom: 0; font-weight: 400;">
                Manage and track your projects efficiently
            </p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button onclick="createNewProject()" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: white; border-radius: 1rem; padding: 0.75rem 1.5rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.2)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                New Project
            </button>
            <button onclick="toggleView()" id="viewToggleBtn" style="background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.3); color: white; border-radius: 1rem; padding: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.25)';" onmouseout="this.style.background='rgba(255,255,255,0.15)';">
                <i class="fas fa-th" id="viewToggleIcon"></i>
            </button>
        </div>
    </div>
</div>

<!-- 4 Stats Cards in a Row -->
<div class="row" style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Total Projects Card -->
    <div class="col" style="flex: 1; min-width: 0;">
        <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(102,126,234,0.08); padding: 1.5rem; text-align: center;">
            <i class="fas fa-project-diagram" style="font-size: 2rem; color: #667eea; margin-bottom: 0.75rem;"></i>
            <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.25rem; font-size: 1.1rem;">Total</h3>
            <div style="font-size: 1.75rem; font-weight: 800; color: #4a5568; margin-bottom: 0.25rem;" id="totalProjects">0</div>
            <div style="color: #6b7280; font-size: 0.85rem;">Projects</div>
        </div>
    </div>
    <!-- Active Projects Card -->
    <div class="col" style="flex: 1; min-width: 0;">
        <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(245,158,11,0.08); padding: 1.5rem; text-align: center;">
            <i class="fas fa-play" style="font-size: 2rem; color: #f59e0b; margin-bottom: 0.75rem;"></i>
            <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.25rem; font-size: 1.1rem;">Active</h3>
            <div style="font-size: 1.75rem; font-weight: 800; color: #4a5568; margin-bottom: 0.25rem;" id="inProgressProjects">0</div>
            <div style="color: #6b7280; font-size: 0.85rem;">In Progress</div>
        </div>
    </div>
    <!-- Completed Projects Card -->
    <div class="col" style="flex: 1; min-width: 0;">
        <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(16,185,129,0.08); padding: 1.5rem; text-align: center;">
            <i class="fas fa-check-circle" style="font-size: 2rem; color: #10b981; margin-bottom: 0.75rem;"></i>
            <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.25rem; font-size: 1.1rem;">Completed</h3>
            <div style="font-size: 1.75rem; font-weight: 800; color: #4a5568; margin-bottom: 0.25rem;" id="completedProjects">0</div>
            <div style="color: #6b7280; font-size: 0.85rem;">Finished</div>
        </div>
    </div>
    <!-- Delayed Projects Card -->
    <div class="col" style="flex: 1; min-width: 0;">
        <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(239,68,68,0.08); padding: 1.5rem; text-align: center;">
            <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #ef4444; margin-bottom: 0.75rem;"></i>
            <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.25rem; font-size: 1.1rem;">Delayed</h3>
            <div style="font-size: 1.75rem; font-weight: 800; color: #4a5568; margin-bottom: 0.25rem;" id="delayedProjects">0</div>
            <div style="color: #6b7280; font-size: 0.85rem;">Overdue</div>
        </div>
    </div>
</div>

<!-- Compact Filters and Controls -->
<div style="background: white; border-radius: 1rem; padding: 1rem; margin-bottom: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <!-- Filter Tabs -->
    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
        <div style="display: flex; gap: 0.3rem; flex-wrap: wrap;">
            <button onclick="filterProjects('all')" data-filter="all" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; padding: 0.4rem 0.8rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-th-large" style="margin-right: 0.3rem;"></i>All
            </button>
            <button onclick="filterProjects('planning')" data-filter="planning" style="background: rgba(159, 122, 234, 0.1); color: #9f7aea; border: 1px solid #9f7aea; border-radius: 0.5rem; padding: 0.4rem 0.8rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#9f7aea'; this.style.color='white';" onmouseout="this.style.background='rgba(159, 122, 234, 0.1)'; this.style.color='#9f7aea';">
                <i class="fas fa-lightbulb" style="margin-right: 0.3rem;"></i>Planning
            </button>
            <button onclick="filterProjects('active')" data-filter="active" style="background: rgba(237, 137, 54, 0.1); color: #ed8936; border: 1px solid #ed8936; border-radius: 0.5rem; padding: 0.4rem 0.8rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#ed8936'; this.style.color='white';" onmouseout="this.style.background='rgba(237, 137, 54, 0.1)'; this.style.color='#ed8936';">
                <i class="fas fa-play" style="margin-right: 0.3rem;"></i>Active
            </button>
            <button onclick="filterProjects('completed')" data-filter="completed" style="background: rgba(72, 187, 120, 0.1); color: #48bb78; border: 1px solid #48bb78; border-radius: 0.5rem; padding: 0.4rem 0.8rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#48bb78'; this.style.color='white';" onmouseout="this.style.background='rgba(72, 187, 120, 0.1)'; this.style.color='#48bb78';">
                <i class="fas fa-check-circle" style="margin-right: 0.3rem;"></i>Completed
            </button>
        </div>

        <!-- Search Box -->
        <div style="margin-left: auto; position: relative;">
            <input type="text" id="searchInput" placeholder="Search projects..." style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.4rem 2.5rem 0.4rem 0.75rem; color: #4a5568; font-size: 0.85rem; outline: none; transition: all 0.3s ease; width: 200px; font-weight: 500;" onfocus="this.style.borderColor='#667eea'; this.style.background='white';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
            <i class="fas fa-search" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.8rem;"></i>
        </div>
    </div>

    <!-- Additional Filters -->
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
        <select id="priorityFilter" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.4rem 0.6rem; color: #4a5568; font-size: 0.8rem; outline: none; font-weight: 600; cursor: pointer;" onfocus="this.style.borderColor='#667eea'; this.style.background='white';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
            <option value="">All Priorities</option>
            <option value="low">Low Priority</option>
            <option value="medium">Medium Priority</option>
            <option value="high">High Priority</option>
            <option value="critical">Critical Priority</option>
        </select>
        <button onclick="clearFilters()" style="background: linear-gradient(135deg, #f56565, #e53e3e); color: white; border: none; border-radius: 0.5rem; padding: 0.4rem 0.8rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;">
            <i class="fas fa-times" style="margin-right: 0.3rem;"></i>Clear
        </button>
    </div>
</div>

    <!-- Projects Container -->
    <div style="margin-bottom: 2rem;">
        <!-- Loading State -->
        <div id="loadingContainer" style="text-align: center; padding: 2rem;">
            <div style="width: 40px; height: 40px; border: 3px solid #e2e8f0; border-top: 3px solid #667eea; border-radius: 50%; margin: 0 auto 1rem auto; animation: spin 1s linear infinite;"></div>
            <h4 style="color: #6b7280; font-weight: 600; font-family: 'Poppins', sans-serif; margin-bottom: 0.5rem; font-size: 1.1rem;">Loading projects...</h4>
            <p style="color: #9ca3af; margin: 0; font-size: 0.9rem;">Please wait</p>
        </div>

        <style>
            @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
            @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
            
            @media (max-width: 768px) {
                .horizontal-project-card > div { flex-direction: column !important; }
                .horizontal-project-card > div > div:first-child { width: 100% !important; }
                .horizontal-project-card .stats-grid { grid-template-columns: 1fr !important; }
                .horizontal-project-card .action-buttons { flex-direction: column !important; gap: 0.8rem !important; }
            }
        </style>

        <!-- Modern Horizontal Cards View -->
        <div id="gridView" style="display: none;">
            <div id="projectsGrid" style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Projects will be loaded here as horizontal cards -->
            </div>
        </div>

        <!-- Table View -->
        <div id="tableView" style="display: none;">
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid rgba(255,255,255,0.2);">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th style="padding: 1.5rem; font-weight: 700; color: #4a5568; border: none;">
                                    <input type="checkbox" id="selectAllProjects" style="cursor: pointer; transform: scale(1.2);">
                                </th>
                                <th style="padding: 1.5rem; font-weight: 700; color: #4a5568; border: none;">Project</th>
                                <th style="padding: 1.5rem; font-weight: 700; color: #4a5568; border: none;">Status</th>
                                <th style="padding: 1.5rem; font-weight: 700; color: #4a5568; border: none;">Priority</th>
                                <th style="padding: 1.5rem; font-weight: 700; color: #4a5568; border: none;">Progress</th>
                                <th style="padding: 1.5rem; font-weight: 700; color: #4a5568; border: none;">Due Date</th>
                                <th style="padding: 1.5rem; font-weight: 700; color: #4a5568; border: none;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="projectsTableBody">
                            <!-- Projects table rows will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Beautiful Empty State -->
        <div id="emptyState" style="display: none; text-align: center; padding: 2rem;">
            <div style="margin-bottom: 1rem;">
                <i class="fas fa-project-diagram" style="font-size: 3rem; color: #e2e8f0;"></i>
            </div>
            <h4 style="color: #6b7280; font-weight: 600; margin-bottom: 0.5rem; font-family: 'Poppins', sans-serif;">No Projects Found</h4>
            <p style="color: #9ca3af; font-size: 0.95rem; margin-bottom: 1.5rem;">Start your journey by creating your first project or adjust your search filters.</p>
            <button onclick="createNewProject()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 12px; padding: 0.7rem 1.4rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 18px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)';">
                <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                Create Your First Project
            </button>
        </div>
    </div>
</div>

<script>
// Global variables
let currentView = 'grid';
let currentFilter = 'all';
let projects = [];
let filteredProjects = [];

// Initialize page when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    loadProjectStats();
    loadProjects();
    
    // Setup search functionality
    const searchInput = document.getElementById('searchInput');
    const priorityFilter = document.getElementById('priorityFilter');
    
    searchInput.addEventListener('input', debounce(function() {
        applyFilters();
    }, 300));
    
    priorityFilter.addEventListener('change', function() {
        applyFilters();
    });
});

// Load project statistics
async function loadProjectStats() {
    try {
        const response = await fetch('<?= base_url('projects/getProjectStats') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        console.log('Stats response:', data);
        
        if (data.success && data.stats) {
            updateStats(data.stats);
        } else {
            console.error('Failed to load stats:', data.message);
            updateStats({ total: 0, completed: 0, in_progress: 0, delayed: 0 });
        }
    } catch (error) {
        console.error('Error loading project stats:', error);
        updateStats({ total: 0, completed: 0, in_progress: 0, delayed: 0 });
    }
}

// Update statistics display
function updateStats(stats) {
    document.getElementById('totalProjects').textContent = stats.total || 0;
    document.getElementById('completedProjects').textContent = stats.completed || 0;
    document.getElementById('inProgressProjects').textContent = stats.in_progress || 0;
    document.getElementById('delayedProjects').textContent = stats.delayed || 0;
}

// Load projects from server
async function loadProjects() {
    try {
        showLoading();
        
        const response = await fetch('<?= base_url('projects/getProjects') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        console.log('Projects response:', data);
        
        if (data.success && data.projects) {
            projects = data.projects;
            applyFilters();
        } else {
            console.error('Failed to load projects:', data.message);
            showEmptyState();
        }
    } catch (error) {
        console.error('Error loading projects:', error);
        showEmptyState();
    } finally {
        hideLoading();
    }
}

// Apply current filters to projects
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const priorityFilter = document.getElementById('priorityFilter').value;
    
    filteredProjects = projects.filter(project => {
        // Status filter
        if (currentFilter !== 'all' && project.status !== currentFilter) {
            return false;
        }
        
        // Priority filter
        if (priorityFilter && project.priority !== priorityFilter) {
            return false;
        }
        
        // Search filter
        if (searchTerm) {
            const searchFields = [
                project.name || '',
                project.description || ''
            ].join(' ').toLowerCase();
            
            if (!searchFields.includes(searchTerm)) {
                return false;
            }
        }
        
        return true;
    });
    
    if (currentView === 'grid') {
        renderGridView();
    } else {
        renderTableView();
    }
}

// Render projects in grid view - Modern Horizontal Cards
function renderGridView() {
    const gridView = document.getElementById('gridView');
    const projectsGrid = document.getElementById('projectsGrid');
    const emptyState = document.getElementById('emptyState');
    
    if (filteredProjects.length === 0) {
        gridView.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    gridView.style.display = 'block';
    
    // Clear existing content
    projectsGrid.innerHTML = '';
    
    // Add projects as horizontal cards
    filteredProjects.forEach((project, index) => {
        const cardHTML = createHorizontalProjectCard(project);
        projectsGrid.insertAdjacentHTML('beforeend', cardHTML);
    });
    
    // Add animation to cards
    setTimeout(() => {
        const cards = projectsGrid.querySelectorAll('.horizontal-project-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateX(-30px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateX(0)';
            }, index * 100);
        });
    }, 50);
}

// Render projects in table view
function renderTableView() {
    const tableView = document.getElementById('tableView');
    const tableBody = document.getElementById('projectsTableBody');
    const emptyState = document.getElementById('emptyState');
    
    if (filteredProjects.length === 0) {
        tableView.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    tableView.style.display = 'block';
    
    tableBody.innerHTML = filteredProjects.map(project => createProjectRow(project)).join('');
}

// Create compact horizontal project card HTML
function createHorizontalProjectCard(project) {
    const progressPercentage = project.total_tasks > 0 ? Math.round((project.completed_tasks / project.total_tasks) * 100) : 0;
    const statusBadge = getStatusBadge(project.status);
    const priorityBadge = getPriorityBadge(project.priority);
    const dueDate = project.end_date ? formatDate(project.end_date) : 'No due date';
    
    return `
        <div class="horizontal-project-card" style="background: white; border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: all 0.3s ease; border: 1px solid #f1f3f4;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)';">
            <div style="display: flex; align-items: center; padding: 1rem; gap: 1rem;">
                
                <!-- Project Info Section -->
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; align-items: start; justify-content: space-between; margin-bottom: 0.5rem;">
                        <h4 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #1a202c; font-family: 'Poppins', sans-serif; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">${project.name}</h4>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            ${statusBadge}
                            ${priorityBadge}
                        </div>
                    </div>
                    <p style="margin: 0; color: #6b7280; font-size: 0.85rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${project.description || 'No description available'}</p>
                </div>
                
                <!-- Stats Section -->
                <div style="display: flex; gap: 1rem; align-items: center; flex-shrink: 0;">
                    <!-- Progress -->
                    <div style="text-align: center; min-width: 60px;">
                        <div style="position: relative; width: 45px; height: 45px; margin: 0 auto 0.25rem;">
                            <svg width="45" height="45" style="transform: rotate(-90deg);">
                                <circle cx="22.5" cy="22.5" r="18" stroke="#e2e8f0" stroke-width="3" fill="none"></circle>
                                <circle cx="22.5" cy="22.5" r="18" stroke="#48bb78" stroke-width="3" fill="none" stroke-dasharray="${(progressPercentage / 100) * 113} 113" stroke-linecap="round"></circle>
                            </svg>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 0.7rem; font-weight: 600; color: #4a5568;">${progressPercentage}%</div>
                        </div>
                        <div style="font-size: 0.7rem; color: #6b7280;">Progress</div>
                    </div>
                    
                    <!-- Tasks -->
                    <div style="text-align: center; min-width: 50px;">
                        <div style="font-size: 1.25rem; font-weight: 700; color: #4a5568; font-family: 'Poppins', sans-serif;">${project.total_tasks || 0}</div>
                        <div style="font-size: 0.7rem; color: #6b7280;">Tasks</div>
                    </div>
                    
                    <!-- Team -->
                    <div style="text-align: center; min-width: 50px;">
                        <div style="font-size: 1.25rem; font-weight: 700; color: #4a5568; font-family: 'Poppins', sans-serif;">${project.member_count || 0}</div>
                        <div style="font-size: 0.7rem; color: #6b7280;">Team</div>
                    </div>
                    
                    <!-- Due Date -->
                    <div style="text-align: center; min-width: 80px;">
                        <div style="font-size: 0.8rem; font-weight: 600; color: #4a5568;">
                            <i class="fas fa-calendar-alt" style="margin-right: 0.25rem; color: #9ca3af;"></i>
                            ${dueDate}
                        </div>
                        <div style="font-size: 0.7rem; color: #6b7280;">Due Date</div>
                    </div>
                </div>
                
                <!-- Actions Section -->
                <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
                    <button onclick="viewProject(${project.id})" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; padding: 0.5rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-1px)';" onmouseout="this.style.transform='translateY(0)';" title="View Project">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="kanbanView(${project.id})" style="background: rgba(102, 126, 234, 0.1); color: #667eea; border: 1px solid #667eea; border-radius: 0.5rem; padding: 0.5rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#667eea'; this.style.color='white';" onmouseout="this.style.background='rgba(102, 126, 234, 0.1)'; this.style.color='#667eea';" title="Kanban Board">
                        <i class="fas fa-columns"></i>
                    </button>
                    <div class="dropdown">
                        <button style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem; color: #6b7280; cursor: pointer; transition: all 0.3s ease;" data-bs-toggle="dropdown" onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f8fafc';" title="More Options">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu shadow-lg" style="border-radius: 0.75rem; border: none; padding: 0.25rem;">
                            <li><a class="dropdown-item" href="#" onclick="editProject(${project.id})" style="border-radius: 0.5rem; padding: 0.5rem; margin: 0.1rem; font-size: 0.85rem;">
                                <i class="fas fa-edit me-2 text-warning"></i>Edit
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="duplicateProject(${project.id})" style="border-radius: 0.5rem; padding: 0.5rem; margin: 0.1rem; font-size: 0.85rem;">
                                <i class="fas fa-copy me-2 text-info"></i>Duplicate
                            </a></li>
                            <li><hr class="dropdown-divider" style="margin: 0.25rem;"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteProject(${project.id})" style="border-radius: 0.5rem; padding: 0.5rem; margin: 0.1rem; font-size: 0.85rem;">
                                <i class="fas fa-trash me-2"></i>Delete
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    `;
}
                            </div>
                            <div class="due-date-info">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <span>${dueDate}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn-action btn-primary-action" onclick="viewProject(${project.id})">
                            <i class="fas fa-eye me-2"></i>View
                        </button>
                        <button class="btn-action btn-secondary-action" onclick="kanbanView(${project.id})">
                            <i class="fas fa-columns me-2"></i>Kanban
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Create project row HTML for table view
function createProjectRow(project) {
    const progressPercentage = project.total_tasks > 0 ? Math.round((project.completed_tasks / project.total_tasks) * 100) : 0;
    const statusBadge = getStatusBadge(project.status);
    const priorityBadge = getPriorityBadge(project.priority);
    const dueDate = project.end_date ? formatDate(project.end_date) : 'No due date';
    
    return `
        <tr>
            <td><input type="checkbox" class="project-checkbox" value="${project.id}"></td>
            <td>
                <div class="d-flex align-items-center">
                    <div>
                        <div class="fw-bold">${project.name}</div>
                        <small class="text-muted">${project.description || 'No description'}</small>
                    </div>
                </div>
            </td>
            <td>${statusBadge}</td>
            <td>${priorityBadge}</td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="progress me-2" style="width: 100px; height: 6px;">
                        <div class="progress-bar" style="width: ${progressPercentage}%"></div>
                    </div>
                    <small>${progressPercentage}%</small>
                </div>
            </td>
            <td>${dueDate}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="viewProject(${project.id})"><i class="fas fa-eye me-2"></i>View</a></li>
                        <li><a class="dropdown-item" href="#" onclick="editProject(${project.id})"><i class="fas fa-edit me-2"></i>Edit</a></li>
                        <li><a class="dropdown-item" href="#" onclick="kanbanView(${project.id})"><i class="fas fa-columns me-2"></i>Kanban</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteProject(${project.id})"><i class="fas fa-trash me-2"></i>Delete</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    `;
}

// Get status badge HTML - Compact Design
function getStatusBadge(status) {
    const baseStyle = "padding: 0.25rem 0.6rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center;";
    
    const badges = {
        planning: `<span style="${baseStyle} background: rgba(159, 122, 234, 0.2); color: #9f7aea; border: 1px solid rgba(159, 122, 234, 0.3);"><i class="fas fa-lightbulb me-1" style="font-size: 0.6rem;"></i>Planning</span>`,
        active: `<span style="${baseStyle} background: rgba(237, 137, 54, 0.2); color: #ed8936; border: 1px solid rgba(237, 137, 54, 0.3);"><i class="fas fa-play me-1" style="font-size: 0.6rem;"></i>Active</span>`,
        completed: `<span style="${baseStyle} background: rgba(72, 187, 120, 0.2); color: #48bb78; border: 1px solid rgba(72, 187, 120, 0.3);"><i class="fas fa-check-circle me-1" style="font-size: 0.6rem;"></i>Completed</span>`,
        on_hold: `<span style="${baseStyle} background: rgba(49, 151, 149, 0.2); color: #319795; border: 1px solid rgba(49, 151, 149, 0.3);"><i class="fas fa-pause me-1" style="font-size: 0.6rem;"></i>On Hold</span>`,
        cancelled: `<span style="${baseStyle} background: rgba(245, 101, 101, 0.2); color: #f56565; border: 1px solid rgba(245, 101, 101, 0.3);"><i class="fas fa-times-circle me-1" style="font-size: 0.6rem;"></i>Cancelled</span>`
    };
    return badges[status] || `<span style="${baseStyle} background: rgba(113, 128, 150, 0.2); color: #718096; border: 1px solid rgba(113, 128, 150, 0.3);"><i class="fas fa-question me-1" style="font-size: 0.6rem;"></i>Unknown</span>`;
}

// Get priority badge HTML - Compact Design
function getPriorityBadge(priority) {
    const baseStyle = "padding: 0.25rem 0.6rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 600; display: inline-flex; align-items: center;";
    
    const badges = {
        low: `<span style="${baseStyle} background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%); color: #22543d;"><i class="fas fa-arrow-down me-1" style="font-size: 0.6rem;"></i>Low</span>`,
        medium: `<span style="${baseStyle} background: linear-gradient(135deg, #bee3f8 0%, #90cdf4 100%); color: #2a4365;"><i class="fas fa-minus me-1" style="font-size: 0.6rem;"></i>Medium</span>`,
        high: `<span style="${baseStyle} background: linear-gradient(135deg, #fed7aa 0%, #f6ad55 100%); color: #744210;"><i class="fas fa-arrow-up me-1" style="font-size: 0.6rem;"></i>High</span>`,
        critical: `<span style="${baseStyle} background: linear-gradient(135deg, #fed7d7 0%, #fc8181 100%); color: #742a2a;"><i class="fas fa-exclamation me-1" style="font-size: 0.6rem;"></i>Critical</span>`
    };
    return badges[priority] || `<span style="${baseStyle} background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%); color: #4a5568;"><i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>Normal</span>`;
}

// Filter projects by status
function filterProjects(status) {
    currentFilter = status;
    
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active', 'btn-primary');
        tab.classList.add('btn-outline-primary');
    });
    
    const activeTab = document.querySelector(`[data-filter="${status}"]`);
    if (activeTab) {
        activeTab.classList.remove('btn-outline-primary');
        activeTab.classList.add('active', 'btn-primary');
    }
    
    applyFilters();
}

// Clear all filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('priorityFilter').value = '';
    filterProjects('all');
}

// Toggle between grid and table view
function toggleView() {
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const toggleIcon = document.getElementById('viewToggleIcon');
    
    if (currentView === 'grid') {
        currentView = 'table';
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        toggleIcon.className = 'fas fa-th-list';
        renderTableView();
    } else {
        currentView = 'grid';
        tableView.style.display = 'none';
        gridView.style.display = 'flex';
        toggleIcon.className = 'fas fa-th';
        renderGridView();
    }
}

// Project actions
function viewProject(projectId) {
    window.location.href = '<?= base_url('projects/view/') ?>' + projectId;
}

function editProject(projectId) {
    window.location.href = '<?= base_url('projects/edit/') ?>' + projectId;
}

function kanbanView(projectId) {
    window.location.href = '<?= base_url('tasks/kanban/') ?>' + projectId;
}

function createNewProject() {
    window.location.href = '<?= base_url('projects/create') ?>';
}

function duplicateProject(projectId) {
    Swal.fire({
        title: 'Duplicate Project',
        text: "This will create a copy of the project with all tasks.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#667eea',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, duplicate it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX call to duplicate project
            fetch(`<?= base_url('projects/duplicate/') ?>${projectId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Duplicated!', 'Project has been duplicated successfully.', 'success');
                    loadProjects();
                    loadProjectStats();
                } else {
                    Swal.fire('Error!', 'Failed to duplicate project.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while duplicating the project.', 'error');
            });
        }
    });
}

function deleteProject(projectId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX call to delete project
            fetch(`<?= base_url('projects/delete/') ?>${projectId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Project has been deleted.', 'success');
                    loadProjects();
                    loadProjectStats();
                } else {
                    Swal.fire('Error!', 'Failed to delete project.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while deleting the project.', 'error');
            });
        }
    });
}

// Utility functions
function showLoading() {
    document.getElementById('loadingContainer').style.display = 'block';
    document.getElementById('gridView').style.display = 'none';
    document.getElementById('tableView').style.display = 'none';
    document.getElementById('emptyState').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loadingContainer').style.display = 'none';
}

function showEmptyState() {
    document.getElementById('loadingContainer').style.display = 'none';
    document.getElementById('gridView').style.display = 'none';
    document.getElementById('tableView').style.display = 'none';
    document.getElementById('emptyState').style.display = 'block';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
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

// Initialize Masonry Layout for Vertical Cards
function initMasonryLayout() {
    const grid = document.querySelector('.masonry-grid');
    if (!grid) return;
    
    // Simple CSS-based masonry using CSS Grid
    grid.style.display = 'grid';
    grid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(350px, 1fr))';
    grid.style.gridAutoRows = 'auto';
    grid.style.gap = '2rem';
    
    // Add animation
    const cards = grid.querySelectorAll('.project-card-container');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}
</script>


