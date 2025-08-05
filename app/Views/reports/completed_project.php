<!-- Completed Project Progress Report -->
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
                <i class="fas fa-check-circle" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Completed Project Report
            </li>
        </ol>
    </nav>

    <!-- Export Buttons -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden;">
        <div style="padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #1f2937; font-family: 'Poppins', sans-serif;">
                    <i class="fas fa-download me-2" style="color: #10b981;"></i>
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

    <!-- Completed Projects Table -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        <div style="padding: 2rem;">
            <div style="overflow-x: auto;">
                <table class="table table-bordered mb-0" id="completedTable" style="min-width: 800px;">
                    <thead style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                        <tr>
                            <th rowspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border-color: rgba(255,255,255,0.2); vertical-align: middle;">Scope</th>
                            <th rowspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border-color: rgba(255,255,255,0.2); vertical-align: middle;">Scope Details</th>
                            <th colspan="3" style="padding: 1rem; font-weight: 600; text-align: center; border-color: rgba(255,255,255,0.2);">Percentage (%)</th>
                            <th rowspan="2" style="padding: 1rem; font-weight: 600; text-align: center; border-color: rgba(255,255,255,0.2); vertical-align: middle;">Variant (%)</th>
                        </tr>
                        <tr style="background: rgba(255,255,255,0.1);">
                            <th style="padding: 0.75rem; font-weight: 500; text-align: center; border-color: rgba(255,255,255,0.2);">Total</th>
                            <th style="padding: 0.75rem; font-weight: 500; text-align: center; border-color: rgba(255,255,255,0.2);">Planned</th>
                            <th style="padding: 0.75rem; font-weight: 500; text-align: center; border-color: rgba(255,255,255,0.2);">Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($completedData)): ?>
                            <?php foreach ($completedData as $index => $row): ?>
                                <tr style="transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f0fdf4'" onmouseout="this.style.backgroundColor='transparent'">
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #374151;"><?= ($index + 1) ?></td>
                                    <td style="padding: 1rem; font-weight: 500; color: #374151;"><?= esc($row['scope_details']) ?></td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #374151;"><?= esc($row['total_percentage']) ?></td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #374151;"><?= esc($row['planned_percentage']) ?></td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #374151;"><?= esc($row['actual_percentage']) ?></td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: <?= $row['variant'] >= 0 ? '#10b981' : '#ef4444' ?>;">
                                        <?= esc($row['variant']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280; font-style: italic;">
                                    No completed projects available.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <?php if (!empty($completedData)): ?>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body text-center">
                    <h5 class="fw-bold" style="color: #10b981;">Total Completed Projects</h5>
                    <h2 class="fw-bold" style="color: #374151;"><?= count(array_unique(array_column($completedData, 'scope_details'))) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body text-center">
                    <h5 class="fw-bold" style="color: #667eea;">Average Completion</h5>
                    <h2 class="fw-bold" style="color: #374151;">
                        <?= number_format(array_sum(array_column($completedData, 'actual_percentage')) / count($completedData), 1) ?>%
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="border-radius: 1rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body text-center">
                    <h5 class="fw-bold" style="color: #f59e0b;">Performance Variance</h5>
                    <h2 class="fw-bold" style="color: <?= array_sum(array_column($completedData, 'variant')) >= 0 ? '#10b981' : '#ef4444' ?>;">
                        <?= number_format(array_sum(array_column($completedData, 'variant')) / count($completedData), 1) ?>%
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
function exportCSV() {
    window.location.href = '<?= base_url('reports/export-csv/completed_project') ?>';
}

function exportExcel() {
    // Create a new workbook
    const wb = XLSX.utils.book_new();
    
    // Add report information as first rows
    const ws_data = [
        ['Report Type: Completed Project Progress Report'],
        [`Generated: ${new Date().toLocaleString()}`],
        [], // Empty row
        ['Scope', 'Scope Details', 'Total %', 'Planned %', 'Actual %', 'Variant %']
    ];
    
    // Get table data
    const table = document.getElementById('completedTable');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const rowData = [];
        for (let i = 0; i < row.cells.length; i++) {
            rowData.push(row.cells[i].textContent.trim());
        }
        ws_data.push(rowData);
    });
    
    const ws = XLSX.utils.aoa_to_sheet(ws_data);
    XLSX.utils.book_append_sheet(wb, ws, 'Completed Projects');
    
    XLSX.writeFile(wb, `completed_projects_report_${new Date().toISOString().split('T')[0]}.xlsx`);
}

function exportPDF() {
    const element = document.getElementById('completedTable');
    
    // Create a container with report information and table
    const container = document.createElement('div');
    container.style.padding = '20px';
    container.style.backgroundColor = 'white';
    
    // Add report type
    const reportType = document.createElement('h1');
    reportType.textContent = 'Completed Project Progress Report';
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
        
        pdf.save(`completed_projects_report_${new Date().toISOString().split('T')[0]}.pdf`);
    });
}
</script>
