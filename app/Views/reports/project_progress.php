<!-- Project Progress Report -->
<div class="container-fluid">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
        <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('dashboard') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-home" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                </a>
                <span style="margin: 0 0.5rem; color: #9ca3af; font-size: 0.65rem;">›</span>
            </li>
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('reports') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-chart-bar" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                    Reports
                </a>
                <span style="margin: 0 0.5rem; color: #9ca3af; font-size: 0.65rem;">›</span>
            </li>
            <li style="color: #f7fafc; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem;">
                <i class="fas fa-tasks" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Project Progress Report
            </li>
        </ol>
    </nav>

    <!-- Project Selection -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden;">
        <div style="padding: 2rem;">
            <h5 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 600; color: #1f2937; font-family: 'Poppins', sans-serif;">
                <i class="fas fa-filter me-2" style="color: #667eea;"></i>
                Select Project
            </h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="projectSelect" class="form-label" style="font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Choose Project</label>
                    <select class="form-select" id="projectSelect" style="border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.3s ease; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                        <option value="">-- Select Project --</option>
                        <?php if (!empty($projects)): ?>
                            <?php foreach ($projects as $project): ?>
                                <option value="<?= esc($project['id']) ?>"><?= esc($project['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="button" class="btn" id="generateReportBtn" onclick="loadProjectData()" disabled style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; padding: 0.75rem 2rem; border-radius: 0.5rem; font-weight: 500; font-size: 0.95rem; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(102, 126, 234, 0.25); opacity: 0.6;" onmouseover="if(!this.disabled) { this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)' }" onmouseout="if(!this.disabled) { this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(102, 126, 234, 0.25)' }">
                    <i class="fas fa-chart-bar me-2"></i><span id="btnText">Generate Report</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Export Buttons (initially hidden) -->
    <div id="exportSection" style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden; display: none;">
        <div style="padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #1f2937; font-family: 'Poppins', sans-serif;">
                    <i class="fas fa-download me-2" style="color: #667eea;"></i>
                    Export Options
                </h5>
                <div class="d-flex gap-2">
                    <button onclick="exportCSV()" class="btn btn-outline-primary" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500; transition: all 0.3s ease;">
                        <i class="fas fa-file-csv me-2"></i>CSV
                    </button>
                    <button onclick="exportExcel()" class="btn btn-outline-success" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500; transition: all 0.3s ease;">
                        <i class="fas fa-file-excel me-2"></i>Excel
                    </button>
                    <button onclick="exportPDF()" class="btn btn-outline-danger" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500; transition: all 0.3s ease;">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Progress Table -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        <div style="padding: 2rem;">
            <!-- Project Name Header -->
            <div id="projectNameHeader" style="margin-bottom: 1.5rem; display: none;">
                <h4 style="margin: 0; font-size: 1.5rem; font-weight: 600; color: #1f2937; font-family: 'Poppins', sans-serif; text-align: center; padding: 1rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                    <span id="projectNameDisplay"></span> Progress Report
                </h4>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="table table-bordered mb-0" id="progressTable" style="min-width: 1200px; border-collapse: collapse;">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th rowspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border: 1px solid #d1d5db; vertical-align: middle;">No</th>
                            <th rowspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border: 1px solid #d1d5db; vertical-align: middle;">Activity</th>
                            <th colspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border: 1px solid #d1d5db; vertical-align: middle;">Planned Date</th>
                            <th colspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border: 1px solid #d1d5db; vertical-align: middle;">Actual Date</th>
                            <th rowspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border: 1px solid #d1d5db; vertical-align: middle;">Planned<br>Percentage<br>(%)</th>
                            <th rowspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border: 1px solid #d1d5db; vertical-align: middle;">Actual<br>Percentage<br>(%)</th>
                            <th rowspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border: 1px solid #d1d5db; vertical-align: middle;">Variant<br>(%)</th>
                            <th rowspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border: 1px solid #d1d5db; vertical-align: middle;">Status</th>
                        </tr>
                        <tr style="background: rgba(255,255,255,0.1);">
                            <th style="padding: 0.75rem; font-weight: 500; text-align: center; border: 1px solid #d1d5db;">Start</th>
                            <th style="padding: 0.75rem; font-weight: 500; text-align: center; border: 1px solid #d1d5db;">End</th>
                            <th style="padding: 0.75rem; font-weight: 500; text-align: center; border: 1px solid #d1d5db;">Start</th>
                            <th style="padding: 0.75rem; font-weight: 500; text-align: center; border: 1px solid #d1d5db;">End</th>
                        </tr>
                    </thead>
                    <tbody id="progressTableBody">
                        <tr>
                            <td colspan="10" style="padding: 2rem; text-align: center; color: #6b7280; font-style: italic; border: 1px solid #d1d5db;">
                                <i class="fas fa-info-circle me-2" style="color: #667eea;"></i>
                                Please select a project to view its progress data.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Status Legend -->
    <div class="card mt-4" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div class="card-body">
            <h5 class="fw-bold mb-3" style="color: #374151;">Status Legend</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background-color: #10b981; border-radius: 0.25rem; margin-right: 0.75rem;"></div>
                        <span style="font-size: 0.9rem; color: #374151;">Finished Ahead of Schedule (>0%)</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background-color: #f59e0b; border-radius: 0.25rem; margin-right: 0.75rem;"></div>
                        <span style="font-size: 0.9rem; color: #374151;">Follow Schedule</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background-color: #8b5cf6; border-radius: 0.25rem; margin-right: 0.75rem;"></div>
                        <span style="font-size: 0.9rem; color: #374151;">Finished Late</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background-color: #ef4444; border-radius: 0.25rem; margin-right: 0.75rem;"></div>
                        <span style="font-size: 0.9rem; color: #374151;">Behind Schedule (0% < -10%)</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background-color: #374151; border-radius: 0.25rem; margin-right: 0.75rem;"></div>
                        <span style="font-size: 0.9rem; color: #374151;">Pending Execution</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Ensure no crossing lines in the header */
#progressTable thead th {
    border-bottom: 2px solid #d1d5db; /* Adjust border thickness */
    border-right: 1px solid #d1d5db; /* Ensure no overlapping */
}
#progressTable thead tr {
    border-collapse: separate; /* Prevent overlapping borders */
}
#progressTable {
    border: 2px solid #d1d5db; /* Enhance outer table border visibility */
}
</style>

<script>
let currentProject = null;

document.addEventListener('DOMContentLoaded', function() {
    const projectSelect = document.getElementById('projectSelect');
    const generateBtn = document.getElementById('generateReportBtn');

    // Enable/disable generate button based on selection
    projectSelect.addEventListener('change', function() {
        if (this.value) {
            generateBtn.disabled = false;
            generateBtn.style.opacity = '1';
            generateBtn.style.cursor = 'pointer';
        } else {
            generateBtn.disabled = true;
            generateBtn.style.opacity = '0.6';
            generateBtn.style.cursor = 'not-allowed';
            
            // Reset the display when no project is selected
            hideProjectName();
            hideExportSection();
            currentProject = null;
            // Reset table to initial state
            const tbody = document.getElementById('progressTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" style="padding: 2rem; text-align: center; color: #6b7280; font-style: italic; border: 1px solid #d1d5db;">
                        <i class="fas fa-info-circle me-2" style="color: #667eea;"></i>
                        Please select a project to view its progress data.
                    </td>
                </tr>
            `;
        }
    });
});

async function loadProjectData() {
    const projectSelect = document.getElementById('projectSelect');
    const projectId = projectSelect.value;
    
    if (!projectId) {
        alert('Please select a project');
        return;
    }

    const generateBtn = document.getElementById('generateReportBtn');
    const btnText = document.getElementById('btnText');
    const originalText = btnText.innerHTML;
    
    // Show loading state
    generateBtn.disabled = true;
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
    
    try {
        const response = await fetch('<?= base_url('reports/get-project-progress-data') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `project_id=${projectId}`
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'Failed to fetch project data');
        }

        if (data.success) {
            currentProject = data.project;
            updateProjectInfo(data.project.name);
            populateTable(data.progressData);
            showExportSection();
        } else {
            throw new Error(data.error || 'Failed to fetch project data');
        }

    } catch (error) {
        console.error('Error:', error);
        alert('Error loading project data: ' + error.message);
        
        // Reset table to empty state
        const tbody = document.getElementById('progressTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="10" style="padding: 2rem; text-align: center; color: #ef4444; font-style: italic; border: 1px solid #d1d5db;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error loading project data. Please try again.
                </td>
            </tr>
        `;
        hideProjectName();
        hideExportSection();
    } finally {
        // Reset button state
        generateBtn.disabled = false;
        btnText.innerHTML = originalText;
    }
}

function updateProjectInfo(projectName) {
    const projectNameHeader = document.getElementById('projectNameHeader');
    const projectNameDisplay = document.getElementById('projectNameDisplay');
    projectNameDisplay.textContent = projectName;
    projectNameHeader.style.display = 'block';
}

function populateTable(progressData) {
    const tbody = document.getElementById('progressTableBody');
    
    if (!progressData || progressData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="10" style="padding: 2rem; text-align: center; color: #6b7280; font-style: italic; border: 1px solid #d1d5db;">
                    <i class="fas fa-info-circle me-2" style="color: #667eea;"></i>
                    No progress data available for this project.
                </td>
            </tr>
        `;
        hideProjectName();
        return;
    }

    let html = '';
    progressData.forEach(row => {
        if (row.type === 'scope') {
            // Scope header row - spans all columns
            html += `
                <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <td style="padding: 1rem; text-align: center; font-weight: 700; border: 1px solid #d1d5db;">${escapeHtml(row.num)}</td>
                    <td colspan="9" style="padding: 1rem; font-weight: 700; font-size: 1.1rem; border: 1px solid #d1d5db;">${escapeHtml(row.activity)}</td>
                </tr>
            `;
        } else {
            // Component data row
            const variantColor = parseFloat(row.variant) >= 0 ? '#10b981' : '#ef4444';
            html += `
                <tr style="transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f8faff'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border: 1px solid #d1d5db;">${escapeHtml(row.num)}</td>
                    <td style="padding: 1rem; font-weight: 500; color: #374151; border: 1px solid #d1d5db; padding-left: 2rem;">${escapeHtml(row.activity)}</td>
                    <td style="padding: 1rem; text-align: center; color: #6b7280; border: 1px solid #d1d5db;">${escapeHtml(row.planned_start)}</td>
                    <td style="padding: 1rem; text-align: center; color: #6b7280; border: 1px solid #d1d5db;">${escapeHtml(row.planned_end)}</td>
                    <td style="padding: 1rem; text-align: center; color: #6b7280; border: 1px solid #d1d5db;">${escapeHtml(row.actual_start)}</td>
                    <td style="padding: 1rem; text-align: center; color: #6b7280; border: 1px solid #d1d5db;">${escapeHtml(row.actual_end)}</td>
                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border: 1px solid #d1d5db;">${escapeHtml(row.planned_percentage)}</td>
                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border: 1px solid #d1d5db;">${escapeHtml(row.actual_percentage)}</td>
                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: ${variantColor}; border: 1px solid #d1d5db;">${escapeHtml(row.variant)}</td>
                    <td style="padding: 1rem; text-align: center; border: 1px solid #d1d5db; background-color: ${escapeHtml(row.status_color)};"></td>
                </tr>
            `;
        }
    });
    
    tbody.innerHTML = html;
}

function showExportSection() {
    document.getElementById('exportSection').style.display = 'block';
}

function hideExportSection() {
    document.getElementById('exportSection').style.display = 'none';
}

function hideProjectName() {
    document.getElementById('projectNameHeader').style.display = 'none';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Export functions
function exportCSV() {
    if (!currentProject) {
        alert('Please select a project first');
        return;
    }
    window.location.href = `<?= base_url('reports/export-csv/project_progress/') ?>${currentProject.id}`;
}

function exportExcel() {
    if (!currentProject) {
        alert('Please select a project first');
        return;
    }
    
    // Create a new workbook
    const wb = XLSX.utils.book_new();
    
    // Add report information as first rows
    const ws_data = [
        ['Report Type: Project Progress Report'],
        [`Project: ${currentProject.name}`],
        [`Generated: ${new Date().toLocaleString()}`],
        [], // Empty row
        ['NUM', 'Activity', 'Planned Start', 'Planned End', 'Actual Start', 'Actual End', 'Planned %', 'Actual %', 'Variant %', 'Status']
    ];
    
    // Get table data (skip scope rows for Excel)
    const table = document.getElementById('progressTable');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (row.cells.length === 10) { // Only component rows
            const rowData = [];
            for (let i = 0; i < row.cells.length; i++) {
                rowData.push(row.cells[i].textContent.trim());
            }
            ws_data.push(rowData);
        }
    });
    
    const ws = XLSX.utils.aoa_to_sheet(ws_data);
    XLSX.utils.book_append_sheet(wb, ws, 'Project Progress');
    
    const projectName = currentProject.name;
    XLSX.writeFile(wb, `${projectName.replace(/\s+/g, '_')}_progress_report.xlsx`);
}

function exportPDF() {
    if (!currentProject) {
        alert('Please select a project first');
        return;
    }

    const element = document.getElementById('progressTable');
    const projectName = currentProject.name;

    // Create a container with report information and table
    const container = document.createElement('div');
    container.style.padding = '20px';
    container.style.backgroundColor = 'white';

    // Add project name with 'Progress Report' (no 'Project: ' prefix)
    const title = document.createElement('h2');
    title.textContent = `${projectName} Progress Report`;
    title.style.textAlign = 'center';
    title.style.marginBottom = '10px';
    title.style.color = '#1f2937';
    title.style.fontSize = '18px';
    container.appendChild(title);

    // Clone the table
    const tableClone = element.cloneNode(true);
    container.appendChild(tableClone);

    // Temporarily add to document for rendering
    document.body.appendChild(container);

    html2canvas(container, {
        scale: 1,
        useCORS: true,
        allowTaint: true
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF('l', 'mm', 'a4');
        const imgWidth = 280;
        const pageHeight = 210;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;

        let position = 0;
        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        // Add generation date at the bottom right of the last page
        const dateText = `Generated: ${new Date().toLocaleString()}`;
        pdf.setFontSize(10);
        pdf.setTextColor(107, 114, 128); // Tailwind Gray-500
        pdf.text(dateText, pdf.internal.pageSize.width - 10, pdf.internal.pageSize.height - 10, { align: 'right' });

        pdf.save(`${projectName.replace(/\s+/g, '_')}_progress_report.pdf`);

        // Remove temporary container
        document.body.removeChild(container);
    }).catch(error => {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
        // Remove temporary container
        document.body.removeChild(container);
    });
}
</script>
