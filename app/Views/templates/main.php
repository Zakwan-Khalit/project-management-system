<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) : 'Project Management System' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
    
    <!-- Font Awesome (Local Only) -->
    <link href="<?= base_url('assets/fonts/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="<?= base_url('assets/js/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- jQuery (must be before content for inline JS in views) -->
    <script src="<?= base_url('assets/js/jquery/jquery.min.js') ?>"></script>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js') ?>"></script>
    
    <!-- Chart.js -->
    <script src="<?= base_url('assets/js/chartjs/chart.umd.min.js') ?>"></script>
    
    <!-- ApexCharts -->
    <script src="<?= base_url('assets/js/apexcharts/apexcharts.min.js') ?>"></script>
    
    <!-- SortableJS -->
    <script src="<?= base_url('assets/js/sortable/Sortable.min.js') ?>"></script>
    
    <!-- SweetAlert2 -->
    <script src="<?= base_url('assets/js/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    
    <!-- Additional CSS files -->
    <?php if (isset($css_files) && is_array($css_files)): ?>
        <?php foreach ($css_files as $css_file): ?>
            <link href="<?= base_url($css_file) ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Modern Template Styles - Inline for Maximum Compatibility -->
    <style>
        /* CSS Variables */
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --font-family-sans-serif: 'Roboto', sans-serif;
            --font-family-heading: 'Poppins', sans-serif;
        }

        /* Base Styles */
        body {
            font-family: var(--font-family-sans-serif) !important;
            background-color: #f5f7fa !important;
            line-height: 1.6 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-family-heading) !important;
            font-weight: 600 !important;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            height: 100vh !important;
            width: 260px !important;
            background: #fff !important;
            border-right: 1px solid #e9ecef !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            z-index: 1000 !important;
            overflow-y: auto !important;
            box-shadow: 0 0 15px rgba(0,0,0,0.05) !important;
        }

        .sidebar.collapsed {
            width: 70px !important;
        }

        /* Hover expansion for collapsed sidebar */
        .sidebar.collapsed.hover-expanded {
            width: 260px !important;
            box-shadow: 0 0 25px rgba(0,0,0,0.15) !important;
        }

        .sidebar.collapsed.hover-expanded .sidebar-header h4 span {
            opacity: 1 !important;
            width: auto !important;
        }

        .sidebar.collapsed.hover-expanded .sidebar-header h4 i {
            margin-right: 0.5rem !important;
        }

        .sidebar.collapsed.hover-expanded .sidebar-menu .nav-link {
            justify-content: flex-start !important;
            padding: 0.875rem 1rem !important;
            margin: 0.125rem 0.5rem !important;
            border-left: 3px solid transparent !important;
        }

        .sidebar.collapsed.hover-expanded .sidebar-menu .nav-link i {
            margin-right: 0.75rem !important;
        }

        .sidebar.collapsed.hover-expanded .sidebar-menu .nav-link span {
            opacity: 1 !important;
            width: auto !important;
        }

        .sidebar-header {
            padding: 0.75rem 1rem !important;
            border-bottom: 1px solid #e9ecef !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            text-align: center !important;
            min-height: 70px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .sidebar-header h4 {
            margin: 0 !important;
            font-size: 1.125rem !important;
            font-weight: 600 !important;
            color: white !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
        }

        .sidebar-header h4 i {
            margin-right: 0.5rem !important;
            font-size: 1.25rem !important;
            transition: all 0.3s ease !important;
        }

        .sidebar.collapsed .sidebar-header h4 span {
            opacity: 0 !important;
            width: 0 !important;
            overflow: hidden !important;
        }

        .sidebar.collapsed .sidebar-header h4 i {
            margin-right: 0 !important;
        }

        .sidebar-menu {
            padding: 1rem 0 !important;
        }

        .sidebar-menu .nav-link {
            display: flex !important;
            align-items: center !important;
            padding: 0.875rem 1rem !important;
            color: #495057 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            border-left: 3px solid transparent !important;
            margin: 0.125rem 0.5rem !important;
            border-radius: 0.5rem !important;
            position: relative !important;
            white-space: nowrap !important;
        }

        .sidebar-menu .nav-link:hover {
            background-color: #f8f9fa !important;
            color: #0d6efd !important;
            border-left-color: #0d6efd !important;
            text-decoration: none !important;
        }

        .sidebar-menu .nav-link.active {
            background-color: #e7f3ff !important;
            color: #0d6efd !important;
            border-left-color: #0d6efd !important;
            font-weight: 500 !important;
        }

        .sidebar-menu .nav-link i {
            width: 20px !important;
            margin-right: 0.75rem !important;
            text-align: center !important;
            font-size: 1rem !important;
            flex-shrink: 0 !important;
        }

        .sidebar-menu .nav-link span {
            font-size: 0.875rem !important;
            font-weight: 400 !important;
            transition: all 0.3s ease !important;
        }

        /* Collapsed sidebar styles */
        .sidebar.collapsed .sidebar-menu .nav-link {
            justify-content: center !important;
            padding: 0.875rem 0.5rem !important;
            margin: 0.125rem 0.25rem !important;
            border-left: none !important;
            border-radius: 0.5rem !important;
        }

        .sidebar.collapsed .sidebar-menu .nav-link i {
            margin-right: 0 !important;
        }

        .sidebar.collapsed .sidebar-menu .nav-link span {
            opacity: 0 !important;
            width: 0 !important;
            overflow: hidden !important;
        }

        /* Tooltip for collapsed sidebar */
        .sidebar.collapsed .nav-link {
            position: relative !important;
        }

        .sidebar.collapsed .nav-link:hover::after {
            content: attr(data-tooltip) !important;
            position: absolute !important;
            left: 100% !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: #333 !important;
            color: white !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.25rem !important;
            font-size: 0.75rem !important;
            white-space: nowrap !important;
            z-index: 1050 !important;
            margin-left: 0.5rem !important;
            opacity: 0 !important;
            animation: tooltipFadeIn 0.2s ease forwards !important;
        }

        @keyframes tooltipFadeIn {
            to {
                opacity: 1 !important;
            }
        }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: 260px !important;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            min-height: 100vh !important;
            background: #f8f9fa !important;
        }

        .main-wrapper.expanded {
            margin-left: 70px !important;
        }

        /* Top Navigation Bar */
        .top-navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            padding: 0.75rem 1.5rem !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 999 !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            min-height: 70px !important;
        }

        .navbar-left {
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
        }

        .navbar-right {
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
        }

        .sidebar-toggle {
            background: none !important;
            border: none !important;
            color: white !important;
            font-size: 1.25rem !important;
            cursor: pointer !important;
            padding: 0.5rem !important;
            border-radius: 0.375rem !important;
            transition: background-color 0.2s ease !important;
        }

        .sidebar-toggle:hover {
            background-color: rgba(255,255,255,0.1) !important;
        }

        .breadcrumb {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            font-size: 0.875rem !important;
            list-style: none !important;
        }

        .breadcrumb-item {
            color: rgba(255,255,255,0.8) !important;
            display: inline !important;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">" !important;
            color: rgba(255,255,255,0.6) !important;
            padding: 0 0.5rem !important;
        }

        .breadcrumb-item.active {
            color: white !important;
        }

        .breadcrumb-item a {
            color: white !important;
            text-decoration: none !important;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline !important;
        }

        /* Custom Dropdown */
        .custom-dropdown {
            position: relative !important;
            display: inline-block !important;
        }

        .custom-dropdown-btn {
            background: rgba(255,255,255,0.1) !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            color: white !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem !important;
            font-weight: 500 !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
        }

        .custom-dropdown-btn:hover {
            background: rgba(255,255,255,0.2) !important;
            color: white !important;
        }

        .custom-dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            background: white !important;
            border: 1px solid #e9ecef !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
            padding: 0.5rem 0 !important;
            margin-top: 0.5rem !important;
            min-width: 200px !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transform: translateY(-10px) !important;
            transition: all 0.3s ease !important;
            z-index: 1050 !important;
        }

        .custom-dropdown.active .custom-dropdown-menu {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }

        .custom-dropdown-item {
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            color: #495057 !important;
            text-decoration: none !important;
            transition: background-color 0.2s ease !important;
        }

        .custom-dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #0d6efd !important;
            text-decoration: none !important;
        }

        .custom-dropdown-divider {
            height: 1px !important;
            background-color: #e9ecef !important;
            margin: 0.5rem 0 !important;
        }

        /* Main Content */
        .main-content {
            padding: 2rem !important;
            background-color: #f5f7fa !important;
            min-height: calc(100vh - 70px) !important;
        }

        /* Dashboard Cards */
        .card {
            background: white !important;
            border: none !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05) !important;
            transition: all 0.2s ease !important;
            overflow: hidden !important;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
            transform: translateY(-2px) !important;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        .card-title {
            font-size: 1rem !important;
            font-weight: 600 !important;
            color: #495057 !important;
            margin-bottom: 0.5rem !important;
        }

        .card-header {
            background: #f8f9fa !important;
            border-bottom: 1px solid #e9ecef !important;
            padding: 1rem 1.5rem !important;
        }

        /* Modern Stat Cards */
        .stat-card {
            background: white !important;
            border-radius: 1rem !important;
            padding: 2rem 1.5rem !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
            border: 1px solid #f1f3f4 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative !important;
            overflow: hidden !important;
            height: 100% !important;
        }

        .stat-card::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            height: 4px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .stat-card:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
        }

        .stat-card.bg-success::before {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        }

        .stat-card.bg-warning::before {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        }

        .stat-card.bg-danger::before {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        }

        .stat-card.bg-info::before {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
        }

        .stat-card .stat-number {
            font-size: 2.5rem !important;
            font-weight: 700 !important;
            color: #1f2937 !important;
            margin-bottom: 0.5rem !important;
            line-height: 1 !important;
        }

        .stat-card p {
            color: #6b7280 !important;
            font-weight: 600 !important;
            margin-bottom: 0.25rem !important;
            font-size: 0.95rem !important;
        }

        .stat-card small {
            color: #9ca3af !important;
            font-size: 0.825rem !important;
            font-weight: 500 !important;
        }

        .stat-card i {
            color: #e5e7eb !important;
            font-size: 3rem !important;
            opacity: 0.3 !important;
        }

        /* Dashboard Header */
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border-radius: 1rem !important;
            padding: 2rem !important;
            margin-bottom: 2rem !important;
        }

        .dashboard-header h1 {
            color: white !important;
            font-size: 2rem !important;
            font-weight: 700 !important;
            margin-bottom: 0.5rem !important;
        }

        .dashboard-header p {
            color: rgba(255,255,255,0.9) !important;
            font-size: 1rem !important;
            margin-bottom: 0 !important;
        }

        /* Modern Chart Cards */
        .chart-card {
            background: white !important;
            border-radius: 1rem !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
            border: 1px solid #f1f3f4 !important;
            overflow: hidden !important;
        }

        .chart-card .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 1.5rem !important;
        }

        .chart-card .card-title {
            color: #374151 !important;
            font-weight: 600 !important;
            font-size: 1.1rem !important;
            margin: 0 !important;
        }

        /* Project List Styling */
        .project-list .list-group-item {
            border: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 1.25rem 1.5rem !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
        }

        .project-list .list-group-item:hover {
            background: #f8fafc !important;
            transform: translateX(4px) !important;
        }

        .project-list .list-group-item:last-child {
            border-bottom: none !important;
        }

        /* Progress Bars - Enhanced */
        .progress {
            height: 8px !important;
            border-radius: 1rem !important;
            background-color: #f1f5f9 !important;
            overflow: hidden !important;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1) !important;
        }

        .progress-bar {
            border-radius: 1rem !important;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1) !important;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%) !important;
        }

        .progress-bar.bg-success {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%) !important;
        }

        .progress-bar.bg-warning {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%) !important;
        }

        .progress-bar.bg-danger {
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%) !important;
        }

        /* Task Items */
        .task-item {
            background: white !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            padding: 1.25rem !important;
            margin-bottom: 1rem !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
        }

        .task-item:hover {
            border-color: #d1d5db !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
            transform: translateY(-1px) !important;
        }

        .task-item:last-child {
            margin-bottom: 0 !important;
        }

        /* Modern Badges */
        .badge {
            padding: 0.5rem 0.875rem !important;
            border-radius: 0.75rem !important;
            font-size: 0.775rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.025em !important;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: white !important;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: white !important;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
            color: white !important;
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            color: white !important;
        }

        /* Activity Feed */
        .activity-item {
            display: flex !important;
            align-items: flex-start !important;
            padding: 1rem 0 !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .activity-item:last-child {
            border-bottom: none !important;
        }

        .activity-icon {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-right: 1rem !important;
            flex-shrink: 0 !important;
        }

        .activity-icon.bg-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
        }

        .activity-icon.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
        }

        .activity-content {
            flex: 1 !important;
        }

        .activity-content h6 {
            margin: 0 0 0.25rem 0 !important;
            font-size: 0.925rem !important;
            font-weight: 600 !important;
            color: #374151 !important;
        }

        .activity-content p {
            margin: 0 !important;
            font-size: 0.85rem !important;
            color: #6b7280 !important;
        }

        .activity-time {
            font-size: 0.775rem !important;
            color: #9ca3af !important;
            text-align: right !important;
        }

        /* Animation Classes */
        .fade-in-up {
            animation: fadeInUp 0.6s ease forwards !important;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0 !important;
                transform: translateY(30px) !important;
            }
            to {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
        }

        /* Stagger animation delay for cards */
        .fade-in-up:nth-child(1) { animation-delay: 0.1s !important; }
        .fade-in-up:nth-child(2) { animation-delay: 0.2s !important; }
        .fade-in-up:nth-child(3) { animation-delay: 0.3s !important; }
        .fade-in-up:nth-child(4) { animation-delay: 0.4s !important; }

        /* Status Badges */
        .badge {
            padding: 0.375rem 0.75rem !important;
            border-radius: 1rem !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.025em !important;
        }

        .badge.bg-success {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
        }

        .badge.bg-warning {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }

        .badge.bg-primary {
            background-color: #dbeafe !important;
            color: #1e40af !important;
        }

        .badge.bg-danger {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
        }

        /* Buttons */
        .btn {
            border-radius: 0.5rem !important;
            padding: 0.625rem 1.25rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            border: none !important;
        }

        .btn:hover {
            transform: translateY(-1px) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0056b3) !important;
            color: white !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004085) !important;
            box-shadow: 0 4px 12px rgba(13,110,253,0.3) !important;
            color: white !important;
        }

        .btn-outline-primary {
            border: 2px solid #0d6efd !important;
            color: #0d6efd !important;
            background: transparent !important;
        }

        .btn-outline-primary:hover {
            background: #0d6efd !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(13,110,253,0.3) !important;
        }

        /* Tables */
        .table {
            background: white !important;
            border-radius: 0.75rem !important;
            overflow: hidden !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05) !important;
            margin-bottom: 0 !important;
        }

        .table thead th {
            background-color: #f8f9fa !important;
            border-bottom: 2px solid #e9ecef !important;
            font-weight: 600 !important;
            color: #495057 !important;
            padding: 1rem !important;
            border-top: none !important;
        }

        .table tbody td {
            padding: 1rem !important;
            border-bottom: 1px solid #e9ecef !important;
            vertical-align: middle !important;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        .table tbody tr:last-child td {
            border-bottom: none !important;
        }

        /* Progress Bars */
        .progress {
            height: 0.5rem !important;
            border-radius: 1rem !important;
            background-color: #e9ecef !important;
            overflow: hidden !important;
        }

        .progress-bar {
            border-radius: 1rem !important;
            transition: width 0.6s ease !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%) !important;
            }
            
            .sidebar.show {
                transform: translateX(0) !important;
            }
            
            .main-wrapper {
                margin-left: 0 !important;
            }
            
            .main-wrapper.expanded {
                margin-left: 0 !important;
            }
            
            .top-navbar {
                padding: 0.5rem 1rem !important;
            }
            
            .main-content {
                padding: 1rem !important;
            }
            
            /* Mobile menu button */
            .mobile-menu-btn {
                display: block !important;
                background: none !important;
                border: none !important;
                color: white !important;
                font-size: 1.25rem !important;
                cursor: pointer !important;
                padding: 0.5rem !important;
                border-radius: 0.375rem !important;
                transition: background-color 0.2s ease !important;
            }
            
            .mobile-menu-btn:hover {
                background-color: rgba(255,255,255,0.1) !important;
            }
            
            /* Hide desktop sidebar toggle button on mobile */
            .sidebar-toggle {
                display: none !important;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none !important;
            }
            
            .sidebar-toggle {
                display: block !important;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block !important;
            width: 1rem !important;
            height: 1rem !important;
            border: 2px solid transparent !important;
            border-top: 2px solid currentColor !important;
            border-radius: 50% !important;
            animation: spin 1s linear infinite !important;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Utilities */
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
        }

        .shadow-custom {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }

        /* Alert Styles */
        .alert {
            border: none !important;
            border-radius: 0.75rem !important;
            padding: 1rem 1.25rem !important;
            margin-bottom: 1rem !important;
        }

        .alert-success {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border-left: 4px solid #10b981 !important;
        }

        .alert-danger {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border-left: 4px solid #ef4444 !important;
        }

        .alert-warning {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-left: 4px solid #f59e0b !important;
        }

        .alert-info {
            background-color: #e0f2fe !important;
            color: #0277bd !important;
            border-left: 4px solid #0dcaf0 !important;
        }
    </style>
    
    <!-- Inline CSS (for dynamic styles only) -->
    <?php if (isset($inline_css) && !empty($inline_css)): ?>
    <style>
        <?= $inline_css ?>
    </style>
    <?php endif; ?>
</head>
<body class="project-management-body">
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>
                <i class="fas fa-project-diagram"></i>
                <span>PMS</span>
            </h4>
        </div>
        <div class="sidebar-menu">
            <a href="<?= base_url('dashboard') ?>" class="nav-link <?= current_url() == base_url('dashboard') ? 'active' : '' ?>" data-tooltip="Dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= base_url('projects') ?>" class="nav-link <?= strpos(current_url(), 'projects') !== false ? 'active' : '' ?>" data-tooltip="Projects">
                <i class="fas fa-project-diagram"></i>
                <span>Projects</span>
            </a>
            <a href="<?= base_url('tasks') ?>" class="nav-link <?= strpos(current_url(), 'tasks') !== false && strpos(current_url(), 'myTasks') === false && strpos(current_url(), 'kanban') === false ? 'active' : '' ?>" data-tooltip="All Tasks">
                <i class="fas fa-tasks"></i>
                <span>All Tasks</span>
            </a>
            <a href="<?= base_url('tasks/myTasks') ?>" class="nav-link <?= strpos(current_url(), 'myTasks') !== false ? 'active' : '' ?>" data-tooltip="My Tasks">
                <i class="fas fa-clipboard-check"></i>
                <span>My Tasks</span>
            </a>
            <a href="<?= base_url('tasks/kanban_select') ?>" class="nav-link <?= strpos(current_url(), 'kanban') !== false ? 'active' : '' ?>" data-tooltip="Kanban">
                <i class="fas fa-columns"></i>
                <span>Kanban</span>
            </a>
            <a href="<?= base_url('reports') ?>" class="nav-link <?= strpos(current_url(), 'reports') !== false ? 'active' : '' ?>" data-tooltip="Reports">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <a href="<?= base_url('profile') ?>" class="nav-link <?= strpos(current_url(), 'profile') !== false ? 'active' : '' ?>" data-tooltip="Profile">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="<?= base_url('logout') ?>" class="nav-link" data-tooltip="Logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Top Navigation -->
        <nav class="top-navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle" id="sidebarToggle" type="button" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="mobile-menu-btn" id="mobileMenuBtn" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                <?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <?php foreach ($breadcrumbs as $breadcrumb): ?>
                                <?php if (isset($breadcrumb['url'])): ?>
                                    <li class="breadcrumb-item">
                                        <a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a>
                                    </li>
                                <?php else: ?>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        <?= $breadcrumb['title'] ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                <?php endif; ?>
            </div>
            <div class="navbar-right">
                <?php 
                try {
                    if (function_exists('is_logged_in') && is_logged_in()): 
                ?>
                    <div class="custom-dropdown">
                        <button class="custom-dropdown-btn" type="button" onclick="toggleUserDropdown()">
                            <i class="fas fa-user"></i>
                            <span><?= esc(function_exists('user_name') ? user_name() : 'User') ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="custom-dropdown-menu" id="userDropdownMenu">
                            <a href="<?= base_url('profile') ?>" class="custom-dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>Profile</span>
                            </a>
                            <a href="<?= base_url('settings') ?>" class="custom-dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                            <div class="custom-dropdown-divider"></div>
                            <a href="<?= base_url('logout') ?>" class="custom-dropdown-item">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                <?php 
                    else: 
                ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                <?php 
                    endif; 
                } catch (Exception $e) {
                    // If there's an error, show login button
                ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                <?php 
                } 
                ?>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <?= isset($content) ? $content : '' ?>
        </main>
    </div>
    
    <!-- Additional JS files -->
    <?php if (isset($js_files) && is_array($js_files)): ?>
        <?php foreach ($js_files as $js_file): ?>
            <script src="<?= base_url($js_file) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Template JS -->
    <script>
        // Custom dropdown functionality
        window.toggleUserDropdown = function() {
            const dropdown = document.querySelector('.custom-dropdown');
            dropdown.classList.toggle('active');
        };
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.custom-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
        
        // Close dropdown when pressing escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const dropdown = document.querySelector('.custom-dropdown');
                if (dropdown) {
                    dropdown.classList.remove('active');
                }
            }
        });

        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            
            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    if (window.innerWidth > 768) {
                        // Desktop: collapse/expand
                        sidebar.classList.toggle('collapsed');
                        mainWrapper.classList.toggle('expanded');
                        
                        // Save state in localStorage
                        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                    }
                });
            }
            
            // Mobile menu button
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Restore sidebar state on desktop
            if (window.innerWidth > 768) {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainWrapper.classList.add('expanded');
                }
            }
            
            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                } else {
                    sidebar.classList.remove('collapsed');
                    mainWrapper.classList.remove('expanded');
                }
            });
            
            // Expand sidebar on hover when collapsed (desktop only)
            let hoverTimeout;
            sidebar.addEventListener('mouseenter', function() {
                if (window.innerWidth > 768 && sidebar.classList.contains('collapsed')) {
                    clearTimeout(hoverTimeout);
                    sidebar.classList.add('hover-expanded');
                }
            });
            
            sidebar.addEventListener('mouseleave', function() {
                if (window.innerWidth > 768 && sidebar.classList.contains('collapsed')) {
                    hoverTimeout = setTimeout(() => {
                        sidebar.classList.remove('hover-expanded');
                    }, 300);
                }
            });
        });

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('alert-dismissible')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
        });

        // Add loading state to buttons
        function showLoading(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="loading me-2"></span>Loading...';
            button.disabled = true;
            
            return function() {
                button.innerHTML = originalText;
                button.disabled = false;
            };
        }

        // Global AJAX setup
        window.fetchWithLoading = function(url, options = {}, button = null) {
            let hideLoading = null;
            
            if (button) {
                hideLoading = showLoading(button);
            }
            
            return fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    ...options.headers
                },
                ...options
            }).finally(() => {
                if (hideLoading) hideLoading();
            });
        };

        // jQuery AJAX wrapper with loading
        window.ajaxWithLoading = function(options, button = null) {
            let hideLoading = null;
            
            if (button) {
                hideLoading = showLoading(button);
            }
            
            // Set default headers
            const defaultHeaders = {
                'X-Requested-With': 'XMLHttpRequest'
            };
            
            // Merge headers
            options.headers = { ...defaultHeaders, ...(options.headers || {}) };
            
            // Add complete callback to hide loading
            const originalComplete = options.complete;
            options.complete = function(...args) {
                if (hideLoading) hideLoading();
                if (originalComplete) originalComplete.apply(this, args);
            };
            
            return $.ajax(options);
        };

        // Global fetch to AJAX converter
        window.apiCall = function(url, options = {}) {
            const method = options.method || 'GET';
            const data = options.body || options.data;
            
            const ajaxOptions = {
                url: url,
                type: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(options.headers || {})
                }
            };
            
            // Handle FormData
            if (data instanceof FormData) {
                ajaxOptions.data = data;
                ajaxOptions.processData = false;
                ajaxOptions.contentType = false;
            } else if (data) {
                ajaxOptions.data = data;
            }
            
            return $.ajax(ajaxOptions);
        };

        // Inline JavaScript
        <?= isset($inline_js) ? $inline_js : '' ?>
    </script>
</body>
</html>
