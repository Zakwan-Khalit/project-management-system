<!-- Project Completion Status Report -->
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
                <i class="fas fa-chart-pie" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Completion Status Report
            </li>
        </ol>
    </nav>

    <!-- Export Buttons -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden;">
        <div style="padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #1f2937; font-family: 'Poppins', sans-serif;">
                    <i class="fas fa-download me-2" style="color: #f59e0b;"></i>
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

    <!-- Completion Status Table -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        <div style="padding: 2rem;">
            <div style="overflow-x: auto;">
                <table class="table table-bordered mb-0" id="statusTable" style="min-width: 700px;">
                    <thead style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                        <tr>
                            <th style="padding: 1rem; font-weight: 600; text-align: center; border-color: rgba(255,255,255,0.2);">Num</th>
                            <th style="padding: 1rem; font-weight: 600; border-color: rgba(255,255,255,0.2);">Project Name</th>
                            <th style="padding: 1rem; font-weight: 600; text-align: center; border-color: rgba(255,255,255,0.2);">Status</th>
                            <th style="padding: 1rem; font-weight: 600; text-align: center; border-color: rgba(255,255,255,0.2);">Days Early</th>
                            <th style="padding: 1rem; font-weight: 600; text-align: center; border-color: rgba(255,255,255,0.2);">Days Late</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($statusData)): ?>
                            <?php foreach ($statusData as $index => $row): ?>
                                <tr style="transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#fffbeb'" onmouseout="this.style.backgroundColor='transparent'">
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #374151;"><?= ($index + 1) ?></td>
                                    <td style="padding: 1rem; font-weight: 500; color: #374151;"><?= esc($row['project_name']) ?></td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <?php 
                                        $statusColors = [
                                            'Completed' => '#10b981',
                                            'Active' => '#3b82f6',
                                            'Planning' => '#6b7280',
                                            'On Hold' => '#f59e0b',
                                            'Cancelled' => '#ef4444'
                                        ];
                                        $statusColor = $statusColors[$row['status']] ?? '#6b7280';
                                        ?>
                                        <span style="padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600; color: white; background-color: <?= $statusColor ?>;">
                                            <?= esc($row['status']) ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: <?= $row['days_early'] !== '-' ? '#10b981' : '#6b7280' ?>;">
                                        <?= esc($row['days_early']) ?>
                                    </td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: <?= $row['days_late'] !== '-' ? '#ef4444' : '#6b7280' ?>;">
                                        <?php if ($row['days_late'] !== '-' && is_numeric($row['days_late'])): ?>
                                            <?= esc($row['days_late']) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280; font-style: italic;">
                                    No projects available.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <?php if (!empty($statusData)): ?>
    <div class="row mt-4">
        <?php 
        $statusCounts = array_count_values(array_column($statusData, 'status'));
        $totalProjects = count($statusData);
        $onTimeProjects = 0;
        $lateProjects = 0;
        $earlyProjects = 0;
        
        foreach ($statusData as $project) {
            if ($project['days_early'] !== '-') $earlyProjects++;
            if ($project['days_late'] !== '-') $lateProjects++;
            if ($project['days_early'] === '-' && $project['days_late'] === '-') $onTimeProjects++;
        }
        ?>
        
        <div class="col-md-3">
            <div class="card" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body text-center">
                    <h5 class="fw-bold" style="color: #3b82f6;">Total Projects</h5>
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
                    <h5 class="fw-bold" style="color: #10b981;">Early/On Time</h5>
                    <h2 class="fw-bold" style="color: #374151;"><?= $earlyProjects + $onTimeProjects ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body text-center">
                    <h5 class="fw-bold" style="color: #ef4444;">Behind Schedule</h5>
                    <h2 class="fw-bold" style="color: #374151;"><?= $lateProjects ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Distribution Chart -->
    <div class="card mt-4" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div class="card-body">
            <h5 class="fw-bold mb-3" style="color: #374151;">Project Status Distribution</h5>
            <div style="height: 300px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function exportCSV() {
    window.location.href = '<?= base_url('reports/export-csv/completion_status') ?>';
}

function exportExcel() {
    // Create a new workbook
    const wb = XLSX.utils.book_new();
    
    // Add report information as first rows
    const ws_data = [
        ['Report Type: Project Completion Status Report'],
        [`Generated: ${new Date().toLocaleString()}`],
        [], // Empty row
        ['Project Name', 'Status', 'Days Early', 'Days Late']
    ];
    
    // Get table data
    const table = document.getElementById('statusTable');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const rowData = [];
        for (let i = 0; i < row.cells.length; i++) {
            rowData.push(row.cells[i].textContent.trim());
        }
        ws_data.push(rowData);
    });
    
    const ws = XLSX.utils.aoa_to_sheet(ws_data);
    XLSX.utils.book_append_sheet(wb, ws, 'Completion Status');
    
    XLSX.writeFile(wb, `completion_status_report_${new Date().toISOString().split('T')[0]}.xlsx`);
}

function exportPDF() {
    const element = document.getElementById('statusTable');
    
    // Create a container with report information and table
    const container = document.createElement('div');
    container.style.padding = '20px';
    container.style.backgroundColor = 'white';
    
    // Add report type
    const reportType = document.createElement('h1');
    reportType.textContent = 'Project Completion Status Report';
    reportType.style.textAlign = 'center';
    reportType.style.marginBottom = '10px';
    reportType.style.color = '#1f2937';
    reportType.style.fontSize = '24px';
    container.appendChild(reportType);
    
    // Add generation date
    const dateHeader = document.createElement('p');
    dateHeader.textContent = `Generated: ${new Date().toLocaleString()}`;
    dateHeader.style.textAlign = 'center';
    dateHeader.style.marginBottom = '20px';
    dateHeader.style.color = '#6b7280';
    dateHeader.style.fontSize = '12px';
    container.appendChild(dateHeader);
    
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
        
        // Remove the temporary container
        document.body.removeChild(container);
        
        pdf.save(`completion_status_report_${new Date().toISOString().split('T')[0]}.pdf`);
    });
}

// Status Chart
<?php if (!empty($statusData)): ?>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusCounts = <?= json_encode($statusCounts) ?>;
    
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
});
<?php endif; ?>
</script>
