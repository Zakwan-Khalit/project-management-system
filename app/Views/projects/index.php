<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
    <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
        <li style="display: flex; align-items: center;">
            <a href="<?= base_url('dashboard') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                <i class="fas fa-home" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Dashboard
            </a>
            <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
        </li>
        <li style="color: #ffffff; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem; background: rgba(255,255,255,0.1); border-radius: 0.2rem;">
            <i class="fas fa-project-diagram" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
            Projects
        </li>
    </ol>
</nav>

<!-- Projects Header -->
<div>
    <!-- New Project Button -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1.25rem;">
        <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1,2,3,4])): ?>
            <button onclick="createNewProject()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; padding: 0.75rem 1.25rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 18px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)';">
                <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                New Project
            </button>
        <?php endif; ?>
    </div>
    
    <!-- Stats Cards Section -->
    <div style="padding: 1.25rem;">
        <div class="row" style="display: flex; gap: 1rem;">
            <!-- Total Projects Card -->
            <div class="col" style="flex: 1; min-width: 0;">
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 0.75rem; padding: 1rem; text-align: center; border: 1px solid #e2e8f0;">
                    <i class="fas fa-project-diagram" style="font-size: 1.5rem; color: #667eea; margin-bottom: 0.5rem;"></i>
                    <h3 style="font-family: 'Poppins', sans-serif; font-weight: 600; margin-bottom: 0.2rem; font-size: 0.95rem; color: #374151;">Total</h3>
                    <div style="font-size: 1.4rem; font-weight: 700; color: #1f2937; margin-bottom: 0.2rem;" id="totalProjects">0</div>
                    <div style="color: #6b7280; font-size: 0.75rem;">Projects</div>
                </div>
            </div>
            <!-- Active Projects Card -->
            <div class="col" style="flex: 1; min-width: 0;">
                <div style="background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); border-radius: 0.75rem; padding: 1rem; text-align: center; border: 1px solid #fdba74;">
                    <i class="fas fa-play" style="font-size: 1.5rem; color: #ea580c; margin-bottom: 0.5rem;"></i>
                    <h3 style="font-family: 'Poppins', sans-serif; font-weight: 600; margin-bottom: 0.2rem; font-size: 0.95rem; color: #9a3412;">Active</h3>
                    <div style="font-size: 1.4rem; font-weight: 700; color: #9a3412; margin-bottom: 0.2rem;" id="inProgressProjects">0</div>
                    <div style="color: #c2410c; font-size: 0.75rem;">In Progress</div>
                </div>
            </div>
            <!-- Completed Projects Card -->
            <div class="col" style="flex: 1; min-width: 0;">
                <div style="background: linear-gradient(135deg, #f0fdf4 0%, #bbf7d0 100%); border-radius: 0.75rem; padding: 1rem; text-align: center; border: 1px solid #86efac;">
                    <i class="fas fa-check-circle" style="font-size: 1.5rem; color: #16a34a; margin-bottom: 0.5rem;"></i>
                    <h3 style="font-family: 'Poppins', sans-serif; font-weight: 600; margin-bottom: 0.2rem; font-size: 0.95rem; color: #166534;">Completed</h3>
                    <div style="font-size: 1.4rem; font-weight: 700; color: #166534; margin-bottom: 0.2rem;" id="completedProjects">0</div>
                    <div style="color: #15803d; font-size: 0.75rem;">Finished</div>
                </div>
            </div>
            <!-- Delayed Projects Card -->
            <div class="col" style="flex: 1; min-width: 0;">
                <div style="background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); border-radius: 0.75rem; padding: 1rem; text-align: center; border: 1px solid #f87171;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; color: #dc2626; margin-bottom: 0.5rem;"></i>
                    <h3 style="font-family: 'Poppins', sans-serif; font-weight: 600; margin-bottom: 0.2rem; font-size: 0.95rem; color: #991b1b;">Delayed</h3>
                    <div style="font-size: 1.4rem; font-weight: 700; color: #991b1b; margin-bottom: 0.2rem;" id="delayedProjects">0</div>
                    <div style="color: #dc2626; font-size: 0.75rem;">Overdue</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Controls -->
<div style="background: white; border-radius: 0.75rem; box-shadow: 0 8px 25px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 1.5rem; overflow: hidden;">
    <div style="padding: 1.25rem;">
        <!-- Filter Tabs -->
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                <button onclick="filterProjects('all')" data-filter="all" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.4rem; padding: 0.4rem 0.75rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-th-large" style="margin-right: 0.4rem;"></i>All
                </button>
                <button onclick="filterProjects('planning')" data-filter="planning" style="background: rgba(159, 122, 234, 0.1); color: #9f7aea; border: 1px solid #9f7aea; border-radius: 0.4rem; padding: 0.4rem 0.75rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#9f7aea'; this.style.color='white';" onmouseout="this.style.background='rgba(159, 122, 234, 0.1)'; this.style.color='#9f7aea';">
                    <i class="fas fa-lightbulb" style="margin-right: 0.4rem;"></i>Planning
                </button>
                <button onclick="filterProjects('active')" data-filter="active" style="background: rgba(237, 137, 54, 0.1); color: #ed8936; border: 1px solid #ed8936; border-radius: 0.4rem; padding: 0.4rem 0.75rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#ed8936'; this.style.color='white';" onmouseout="this.style.background='rgba(237, 137, 54, 0.1)'; this.style.color='#ed8936';">
                    <i class="fas fa-play" style="margin-right: 0.4rem;"></i>Active
                </button>
                <button onclick="filterProjects('completed')" data-filter="completed" style="background: rgba(72, 187, 120, 0.1); color: #48bb78; border: 1px solid #48bb78; border-radius: 0.4rem; padding: 0.4rem 0.75rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#48bb78'; this.style.color='white';" onmouseout="this.style.background='rgba(72, 187, 120, 0.1)'; this.style.color='#48bb78';">
                    <i class="fas fa-check-circle" style="margin-right: 0.4rem;"></i>Completed
                </button>
                <button onclick="filterProjects('on_hold')" data-filter="on_hold" style="background: rgba(49, 151, 149, 0.1); color: #319795; border: 1px solid #319795; border-radius: 0.4rem; padding: 0.4rem 0.75rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#319795'; this.style.color='white';" onmouseout="this.style.background='rgba(49, 151, 149, 0.1)'; this.style.color='#319795';">
                    <i class="fas fa-pause" style="margin-right: 0.4rem;"></i>On Hold
                </button>
            </div>

            <!-- Search Box -->
            <div style="margin-left: auto; position: relative;">
                <input type="text" id="searchInput" placeholder="Search projects..." style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.4rem; padding: 0.4rem 2rem 0.4rem 0.75rem; color: #4a5568; font-size: 0.8rem; outline: none; transition: all 0.3s ease; width: 200px; font-weight: 500;" onfocus="this.style.borderColor='#667eea'; this.style.background='white';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                <i class="fas fa-search" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 0.8rem;"></i>
            </div>
        </div>

        <!-- Additional Filters -->
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
            <select id="priorityFilter" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.4rem; padding: 0.4rem 0.75rem; color: #4a5568; font-size: 0.8rem; outline: none; font-weight: 600; cursor: pointer;" onfocus="this.style.borderColor='#667eea'; this.style.background='white';" onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                <option value="">All Priorities</option>
                <option value="low">Low Priority</option>
                <option value="medium">Medium Priority</option>
                <option value="high">High Priority</option>
                <option value="critical">Critical Priority</option>
            </select>
            <button onclick="clearFilters()" style="background: linear-gradient(135deg, #f56565, #e53e3e); color: white; border: none; border-radius: 0.4rem; padding: 0.4rem 0.75rem; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-times" style="margin-right: 0.4rem;"></i>Clear
            </button>
        </div>
    </div>
</div>

<!-- Loading State -->
<div id="loadingContainer" style="text-align: center; padding: 2rem;">
    <div style="width: 40px; height: 40px; border: 3px solid #e2e8f0; border-top: 3px solid #667eea; border-radius: 50%; margin: 0 auto 1rem auto; animation: spin 1s linear infinite;"></div>
    <h4 style="color: #6b7280; font-weight: 600; font-family: 'Poppins', sans-serif; margin-bottom: 0.5rem; font-size: 1.1rem;">Loading projects...</h4>
    <p style="color: #9ca3af; margin: 0; font-size: 0.9rem;">Please wait</p>
</div>

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    
    /* Dropdown menu positioning fix */
    .horizontal-project-card .dropdown-menu {
        position: absolute !important;
        transform: translateY(0) !important;
        inset: auto 0px auto auto !important;
        margin: 0 !important;
        top: 100% !important;
        z-index: 9999 !important;
    }
    
    .horizontal-project-card .dropdown {
        position: relative !important;
        overflow: visible !important;
    }
    
    .horizontal-project-card {
        overflow: visible !important;
    }
    
    #gridView {
        overflow: visible !important;
    }
    
    .horizontal-project-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    @media (max-width: 768px) {
        .horizontal-project-card > div {
            flex-direction: column !important;
        }
        
        .horizontal-project-card > div > div:first-child {
            width: 100% !important;
        }
        
        .horizontal-project-card .stats-grid {
            grid-template-columns: 1fr !important;
        }
        
        .horizontal-project-card .action-buttons {
            flex-direction: column !important;
            gap: 0.8rem !important;
        }
    }
</style>

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
            <?php if (in_array($roleId, [1,2,3,4])): ?>
            <button onclick="createNewProject()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 12px; padding: 0.7rem 1.4rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 18px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)';">
                <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                Create Your First Project
            </button>
            <?php endif; ?>
        </div>

<!-- Modern Horizontal Cards View - Separate Container -->
<div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-top: 1.5rem; overflow: visible; margin-bottom: 500px;">
    <div id="gridView" style="display: block; padding: 1.5rem; overflow: visible;">
        <!-- Projects will be loaded here as horizontal cards -->
    </div>
</div>

<script>
// Utility functions
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

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

// Global variables
let currentView = 'grid';
let currentFilter = 'all';
let projects = [];
let filteredProjects = [];
let isInitialized = false; // Prevent double initialization

// Initialize page when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (!isInitialized) {
        initializePage();
        isInitialized = true;
    }
});

// Fallback initialization
$(document).ready(function() {
    if (!isInitialized) {
        initializePage();
        isInitialized = true;
    }
});

function initializePage() {
    console.log('Initializing page...');
    
    // Setup search functionality
    const searchInput = document.getElementById('searchInput');
    const priorityFilter = document.getElementById('priorityFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            applyFilters();
        }, 300));
    }
    
    if (priorityFilter) {
        priorityFilter.addEventListener('change', function() {
            applyFilters();
        });
    }
    
    // Setup select all checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAllProjects');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const projectCheckboxes = document.querySelectorAll('.project-checkbox');
            projectCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Load data
    console.log('Loading project stats and projects...');
    loadProjectStats();
    loadProjects();
}

// Load project statistics
function loadProjectStats() {
    if (typeof $ === 'undefined') {
        updateStats({ total: 0, completed: 0, in_progress: 0, delayed: 0 });
        return;
    }
    
    $.ajax({
        url: '<?= base_url('projects/getProjectStats') ?>',
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.success && data.stats) {
                updateStats(data.stats);
            } else {
                updateStats({ total: 0, completed: 0, in_progress: 0, delayed: 0 });
            }
        },
        error: function(xhr, status, error) {
            updateStats({ total: 0, completed: 0, in_progress: 0, delayed: 0 });
        }
    });
}

// Update statistics display
function updateStats(stats) {
    const elements = {
        totalProjects: document.getElementById('totalProjects'),
        completedProjects: document.getElementById('completedProjects'),
        inProgressProjects: document.getElementById('inProgressProjects'),
        delayedProjects: document.getElementById('delayedProjects')
    };
    
    if (elements.totalProjects) elements.totalProjects.textContent = stats.total || 0;
    if (elements.completedProjects) elements.completedProjects.textContent = stats.completed || 0;
    if (elements.inProgressProjects) elements.inProgressProjects.textContent = stats.in_progress || 0;
    if (elements.delayedProjects) elements.delayedProjects.textContent = stats.delayed || 0;
}

// Load projects from server
function loadProjects() {
    if (typeof $ === 'undefined') {
        console.error('jQuery is not available! Cannot make AJAX calls.');
        return;
    }
    
    showProjectsLoading();
    
    $.ajax({
        url: '<?= base_url('projects/getProjects') ?>',
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.success && data.projects) {
                projects = data.projects;
                applyFilters();
            } else {
                console.error('Failed to load projects:', data.message || 'Unknown error');
                showEmptyState();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error, 'Status:', status, 'Response:', xhr.responseText);
            showEmptyState();
        },
        complete: function() {
            hideProjectsLoading();
        }
    });
}

// Apply current filters to projects
function applyFilters() {
    const searchInput = document.getElementById('searchInput');
    const priorityFilterElement = document.getElementById('priorityFilter');
    
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const priorityFilter = priorityFilterElement ? priorityFilterElement.value : '';
    
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

    // Ensure the correct view is visible
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    
    if (gridView && tableView) {
        if (currentView === 'grid') {
            tableView.style.display = 'none';
            gridView.style.display = 'block';
        } else {
            gridView.style.display = 'none';
            tableView.style.display = 'block';
        }
    }
}

// Render projects in grid view - Modern Horizontal Cards
function renderGridView() {
    const gridView = document.getElementById('gridView');
    const emptyState = document.getElementById('emptyState');
    
    if (!gridView || !emptyState) {
        console.error('Required DOM elements not found for grid view');
        return;
    }
    
    if (filteredProjects.length === 0) {
        gridView.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    gridView.style.display = 'block';
    
    // Clear existing content
    gridView.innerHTML = '';
    
    // Add projects as horizontal cards
    filteredProjects.forEach((project, index) => {
        const cardHTML = createHorizontalProjectCard(project);
        gridView.insertAdjacentHTML('beforeend', cardHTML);
    });
    
    // Add animation to cards
    setTimeout(() => {
        const cards = gridView.querySelectorAll('.horizontal-project-card');
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
    
    if (!tableView || !tableBody || !emptyState) {
        console.error('Required DOM elements not found for table view');
        return;
    }
    
    if (filteredProjects.length === 0) {
        tableView.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    tableView.style.display = 'block';
    
    const tableRows = filteredProjects.map(project => {
        return createProjectRow(project);
    });
    
    tableBody.innerHTML = tableRows.join('');
    
    // Re-setup select all functionality after rendering table
    const selectAllCheckbox = document.getElementById('selectAllProjects');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false; // Reset the checkbox
        selectAllCheckbox.addEventListener('change', function() {
            const projectCheckboxes = document.querySelectorAll('.project-checkbox');
            projectCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
}

// Create compact horizontal project card HTML
function createHorizontalProjectCard(project) {
    const progressPercentage = project.total_tasks > 0 ? Math.round((project.completed_tasks / project.total_tasks) * 100) : 0;
    const statusBadge = getStatusBadge(project.status);
    const priorityBadge = getPriorityBadge(project.priority);
    const dueDate = project.end_date ? formatDate(project.end_date) : 'No due date';
    
    return `
        <div class="horizontal-project-card" style="display: flex; align-items: center; padding: 1rem; gap: 1rem; margin-bottom: 1rem; border-bottom: 1px solid #f1f3f4; position: relative;">
                
                <!-- Project Info Section -->
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; align-items: start; justify-content: space-between; margin-bottom: 0.5rem;">
                        <h4 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #1a202c; font-family: 'Poppins', sans-serif; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">${project.name}</h4>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            ${statusBadge}
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
                        <div style="color: #6b7280; font-size: 0.7rem;">Tasks</div>
                    </div>
                    
                    <!-- Team -->
                    <div style="text-align: center; min-width: 50px;">
                        <div style="font-size: 1.25rem; font-weight: 700; color: #4a5568; font-family: 'Poppins', sans-serif;">${project.member_count || 0}</div>
                        <div style="color: #6b7280; font-size: 0.7rem;">Members</div>
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
                    <div class="dropdown" style="position: relative;">
                        <button style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem; color: #6b7280; cursor: pointer; transition: all 0.3s ease;" data-bs-toggle="dropdown" onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f8fafc';">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu shadow-lg"
                            style="border-radius: 0.75rem; border: none; padding: 0.25rem; min-width: 180px; position: absolute; right: 0; top: 100%; z-index: 9999; background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.15); margin-top: 0.25rem;">
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
            <td style="padding: 1rem;"><input type="checkbox" class="project-checkbox" value="${project.id}" style="cursor: pointer; transform: scale(1.2);"></td>
            <td style="padding: 1rem;">
                <div style="display: flex; align-items: center;">
                    <div>
                        <div style="font-weight: 600; color: #1a202c; margin-bottom: 0.25rem;">${project.name}</div>
                        <small style="color: #6b7280;">${project.description || 'No description'}</small>
                    </div>
                </div>
            </td>
            <td style="padding: 1rem;">${statusBadge}</td>
            <td style="padding: 1rem;">${priorityBadge}</td>
            <td style="padding: 1rem;">
                <div style="display: flex; align-items: center;">
                    <div style="background: #f1f5f9; border-radius: 1rem; overflow: hidden; width: 100px; height: 8px; margin-right: 0.5rem;">
                        <div style="background: linear-gradient(90deg, #48bb78, #38a169); height: 100%; width: ${progressPercentage}%; transition: width 0.3s ease;"></div>
                    </div>
                    <small style="font-weight: 600; color: #4a5568;">${progressPercentage}%</small>
                </div>
            </td>
            <td style="padding: 1rem;">
                <div style="font-size: 0.85rem; color: #4a5568;">
                    <i class="fas fa-calendar-alt" style="margin-right: 0.25rem; color: #9ca3af;"></i>
                    ${dueDate}
                </div>
            </td>
            <td style="padding: 1rem;">
                <div class="dropdown">
                    <button style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem; color: #6b7280; cursor: pointer; transition: all 0.3s ease;" data-bs-toggle="dropdown" onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f8fafc';">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu shadow-lg" style="border-radius: 0.75rem; border: none; padding: 0.25rem;">
                        <li><a class="dropdown-item" href="#" onclick="viewProject(${project.id})" style="border-radius: 0.5rem; padding: 0.5rem; margin: 0.1rem; font-size: 0.85rem;">
                            <i class="fas fa-eye me-2 text-primary"></i>View
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="editProject(${project.id})" style="border-radius: 0.5rem; padding: 0.5rem; margin: 0.1rem; font-size: 0.85rem;">
                            <i class="fas fa-edit me-2 text-warning"></i>Edit
                        </a></li>
                        <li><hr class="dropdown-divider" style="margin: 0.25rem;"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteProject(${project.id})" style="border-radius: 0.5rem; padding: 0.5rem; margin: 0.1rem; font-size: 0.85rem;">
                            <i class="fas fa-trash me-2"></i>Delete
                        </a></li>
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
    
    // Update active tab - reset all filter buttons
    document.querySelectorAll('[data-filter]').forEach(tab => {
        const tabStatus = tab.getAttribute('data-filter');
        
        if (tabStatus === status) {
            // Active tab styling
            if (tabStatus === 'all') {
                tab.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                tab.style.color = 'white';
                tab.style.border = 'none';
            } else {
                // Get the color for specific status tabs
                const colors = {
                    planning: { bg: '#9f7aea', border: '#9f7aea' },
                    active: { bg: '#ed8936', border: '#ed8936' },
                    completed: { bg: '#48bb78', border: '#48bb78' },
                    on_hold: { bg: '#319795', border: '#319795' }
                };
                const color = colors[tabStatus] || { bg: '#667eea', border: '#667eea' };
                tab.style.background = color.bg;
                tab.style.color = 'white';
                tab.style.border = `1px solid ${color.border}`;
            }
        } else {
            // Inactive tab styling
            if (tabStatus === 'all') {
                tab.style.background = 'rgba(102, 126, 234, 0.1)';
                tab.style.color = '#667eea';
                tab.style.border = '1px solid #667eea';
            } else {
                const colors = {
                    planning: { bg: 'rgba(159, 122, 234, 0.1)', color: '#9f7aea', border: '#9f7aea' },
                    active: { bg: 'rgba(237, 137, 54, 0.1)', color: '#ed8936', border: '#ed8936' },
                    completed: { bg: 'rgba(72, 187, 120, 0.1)', color: '#48bb78', border: '#48bb78' },
                    on_hold: { bg: 'rgba(49, 151, 149, 0.1)', color: '#319795', border: '#319795' }
                };
                const color = colors[tabStatus] || { bg: 'rgba(102, 126, 234, 0.1)', color: '#667eea', border: '#667eea' };
                tab.style.background = color.bg;
                tab.style.color = color.color;
                tab.style.border = `1px solid ${color.border}`;
            }
        }
    });
    
    applyFilters();
}

// Clear all filters
function clearFilters() {
    const searchInput = document.getElementById('searchInput');
    const priorityFilter = document.getElementById('priorityFilter');
    
    if (searchInput) searchInput.value = '';
    if (priorityFilter) priorityFilter.value = '';
    
    filterProjects('all');
}

// Toggle between grid and table view
function toggleView() {
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    
    if (!gridView || !tableView) {
        console.error('Required elements for view toggle not found');
        return;
    }
    
    if (currentView === 'grid') {
        currentView = 'table';
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        renderTableView();
    } else {
        currentView = 'grid';
        tableView.style.display = 'none';
        gridView.style.display = 'block';
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
    if (typeof Swal === 'undefined') {
        alert('SweetAlert2 is not loaded');
        return;
    }
    
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
            if (typeof $ === 'undefined') {
                Swal.fire('Error!', 'jQuery is not available.', 'error');
                return;
            }
            
            // Make AJAX call to duplicate project
            $.ajax({
                url: '<?= base_url('projects/duplicate/') ?>' + projectId,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(data) {
                    if (data.success) {
                        Swal.fire('Duplicated!', 'Project has been duplicated successfully.', 'success');
                        loadProjects();
                        loadProjectStats();
                    } else {
                        Swal.fire('Error!', 'Failed to duplicate project.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An error occurred while duplicating the project.', 'error');
                }
            });
        }
    });
}

function deleteProject(projectId) {
    if (typeof Swal === 'undefined') {
        if (confirm('Are you sure you want to delete this project?')) {
            // Fallback without SweetAlert
            window.location.href = '<?= base_url('projects/delete/') ?>' + projectId;
        }
        return;
    }
    
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
            if (typeof $ === 'undefined') {
                Swal.fire('Error!', 'jQuery is not available.', 'error');
                return;
            }
            
            // Make AJAX call to delete project
            $.ajax({
                url: '<?= base_url('projects/delete/') ?>' + projectId,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(data) {
                    if (data.success) {
                        Swal.fire('Deleted!', 'Project has been deleted.', 'success');
                        loadProjects();
                        loadProjectStats();
                    } else {
                        Swal.fire('Error!', 'Failed to delete project.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An error occurred while deleting the project.', 'error');
                }
            });
        }
    });
}

// Utility functions for UI
function showProjectsLoading() {
    try {
        const loadingContainer = document.getElementById('loadingContainer');
        const gridView = document.getElementById('gridView');
        const tableView = document.getElementById('tableView');
        const emptyState = document.getElementById('emptyState');

        if (loadingContainer) loadingContainer.style.display = 'block';
        if (gridView) gridView.style.display = 'none';
        if (tableView) tableView.style.display = 'none';
        if (emptyState) emptyState.style.display = 'none';
    } catch (error) {
        console.error('Error in showProjectsLoading:', error);
    }
}

function hideProjectsLoading() {
    try {
        const loadingContainer = document.getElementById('loadingContainer');
        if (loadingContainer) loadingContainer.style.display = 'none';
    } catch (error) {
        console.error('Error in hideProjectsLoading:', error);
    }
}

function showEmptyState() {
    const elements = {
        loadingContainer: document.getElementById('loadingContainer'),
        gridView: document.getElementById('gridView'),
        tableView: document.getElementById('tableView'),
        emptyState: document.getElementById('emptyState')
    };
    
    if (elements.loadingContainer) elements.loadingContainer.style.display = 'none';
    if (elements.gridView) elements.gridView.style.display = 'none';
    if (elements.tableView) elements.tableView.style.display = 'none';
    if (elements.emptyState) elements.emptyState.style.display = 'block';
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


