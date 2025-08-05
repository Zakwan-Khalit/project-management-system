<!-- Reports & Analytics Dashboard -->
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
            <li style="color: #f7fafc; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem;">
                <i class="fas fa-chart-bar" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Reports
            </li>
        </ol>
    </nav>

    <!-- Report Selection Card -->
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
                <div style="padding: 2rem;">
                    <h5 style="margin: 0 0 1.5rem 0; font-size: 1.25rem; font-weight: 600; color: #1f2937; font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-filter me-2" style="color: #667eea;"></i>
                        Select Report Type
                    </h5>
                    
                    <!-- Report Type Selector -->
                    <div class="mb-4">
                        <label for="reportType" class="form-label" style="font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Report Type</label>
                        <select id="reportType" class="form-select" style="border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.75rem 1rem; font-size: 0.95rem; transition: all 0.3s ease; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
                            <option value="">-- Select Report Type --</option>
                            <option value="project_progress">Project Progress Report</option>
                            <option value="completed_project">Completed Project Progress Report</option>
                            <option value="completion_status">Project Completion Status Report</option>
                            <option value="fte_report">Full-Time Equivalent (FTE) Report</option>
                        </select>
                    </div>

                    <!-- Generate Button -->
                    <div class="mt-4">
                        <button id="generateReportBtn" class="btn" disabled 
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; padding: 0.75rem 2rem; border-radius: 0.5rem; font-weight: 500; font-size: 0.95rem; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(102, 126, 234, 0.25); opacity: 0.6;"
                                onmouseover="if(!this.disabled) { this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)' }"
                                onmouseout="if(!this.disabled) { this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(102, 126, 234, 0.25)' }">
                            <i class="fas fa-chart-bar me-2"></i>
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.getElementById('reportType');
    const generateBtn = document.getElementById('generateReportBtn');

    // Update generate button state when report type changes
    reportTypeSelect.addEventListener('change', function() {
        const reportType = this.value;
        
        if (reportType) {
            generateBtn.disabled = false;
            generateBtn.style.opacity = '1';
            generateBtn.style.cursor = 'pointer';
        } else {
            generateBtn.disabled = true;
            generateBtn.style.opacity = '0.6';
            generateBtn.style.cursor = 'not-allowed';
        }
    });

    // Generate report
    generateBtn.addEventListener('click', function() {
        const reportType = reportTypeSelect.value;
        
        if (!reportType) return;
        
        let url = '';
        switch (reportType) {
            case 'project_progress':
                url = `<?= base_url('reports/project-progress') ?>`;
                break;
            case 'completed_project':
                url = `<?= base_url('reports/completed-project') ?>`;
                break;
            case 'completion_status':
                url = `<?= base_url('reports/completion-status') ?>`;
                break;
            default:
                return;
        }
        
        window.location.href = url;
    });
});
</script>
