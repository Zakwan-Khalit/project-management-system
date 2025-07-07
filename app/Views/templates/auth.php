<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Project Management System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="<?= base_url('assets/fonts/fontawesome/css/all.min.css') ?>" rel="stylesheet">
    
    <!-- Font Awesome CDN (fallback) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- SweetAlert2 CSS -->
    <link href="<?= base_url('assets/js/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Auth Template Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
        }
        
        .auth-content {
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
    </style>
    
    <?php if (isset($additional_css)): ?>
        <?= $additional_css ?>
    <?php endif; ?>
</head>
<body>
    
    <!-- Auth Content -->
    <main class="auth-content">
        <?= $content ?>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js') ?>"></script>
    
    <!-- jQuery -->
    <script src="<?= base_url('assets/js/jquery/jquery.min.js') ?>"></script>
    <script>
    // jQuery fallback
    if (typeof $ === 'undefined') {
        document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>');
    }
    </script>
    
    <!-- SweetAlert2 -->
    <script src="<?= base_url('assets/js/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script>
    // SweetAlert2 fallback
    if (typeof Swal === 'undefined') {
        document.write('<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"><\/script>');
    }
    </script>
    
    <?php if (isset($additional_js)): ?>
        <?= $additional_js ?>
    <?php endif; ?>
    
</body>
</html>
