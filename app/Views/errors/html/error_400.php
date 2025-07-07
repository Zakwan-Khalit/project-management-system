<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bad Request - Project Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; font-family: 'Roboto', sans-serif; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    
    <div style="background: white; border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.2); overflow: hidden; width: 100%; max-width: 600px; margin: 20px; text-align: center;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 3rem 2rem 2rem 2rem;">
            <div style="width: 120px; height: 120px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto;">
                <i class="fas fa-times-circle" style="font-size: 3.5rem;"></i>
            </div>
            <h1 style="margin: 0; font-size: 4rem; font-weight: 700; font-family: 'Poppins', sans-serif; line-height: 1;">400</h1>
            <p style="margin: 1rem 0 0 0; font-size: 1.25rem; opacity: 0.9; font-weight: 500;">Bad Request</p>
        </div>

        <!-- Content -->
        <div style="padding: 3rem 2rem;">
            <h2 style="margin: 0 0 1rem 0; font-size: 1.5rem; font-weight: 600; color: #374151; font-family: 'Poppins', sans-serif;">Invalid Request</h2>
            <p style="color: #6b7280; line-height: 1.6; margin: 0 0 2.5rem 0; font-size: 1rem;">
                The request you sent to the server was invalid or malformed. 
                Please check your input and try again.
            </p>
            
            <!-- Action Buttons -->
            <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
                <a href="/" style="display: inline-flex; align-items: center; gap: 0.75rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; padding: 1rem 2rem; border-radius: 0.75rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102,126,234,0.3); min-width: 200px; justify-content: center;"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.4)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102,126,234,0.3)'">
                    <i class="fas fa-home"></i>
                    <span>Go to Homepage</span>
                </a>
                
                <button onclick="history.back()" style="display: inline-flex; align-items: center; gap: 0.75rem; background: rgba(102,126,234,0.1); color: #667eea; border: 2px solid #667eea; padding: 1rem 2rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; min-width: 200px; justify-content: center;"
                        onmouseover="this.style.background='#667eea'; this.style.color='white'"
                        onmouseout="this.style.background='rgba(102,126,234,0.1)'; this.style.color='#667eea'">
                    <i class="fas fa-arrow-left"></i>
                    <span>Go Back</span>
                </button>
            </div>
            
            <!-- Help Text -->
            <div style="margin-top: 2.5rem; padding: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem;">
                <h3 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600; color: #374151;">Common Causes:</h3>
                <ul style="margin: 0; padding-left: 1.5rem; color: #6b7280; font-size: 0.9rem; line-height: 1.5; text-align: left;">
                    <li>Missing or invalid form data</li>
                    <li>Malformed URL parameters</li>
                    <li>Expired or invalid session</li>
                    <li>Invalid file upload</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Floating Elements for Visual Appeal -->
    <div style="position: fixed; top: 15%; left: 10%; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 5s ease-in-out infinite;"></div>
    <div style="position: fixed; top: 70%; right: 15%; width: 60px; height: 60px; background: rgba(255,255,255,0.05); border-radius: 50%; animation: float 4s ease-in-out infinite reverse;"></div>
    <div style="position: fixed; bottom: 20%; left: 20%; width: 100px; height: 100px; background: rgba(255,255,255,0.08); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            h1 {
                font-size: 3rem !important;
            }
            
            h2 {
                font-size: 1.25rem !important;
            }
        }
    </style>
</body>
</html>
            color: #222;
        }
        .wrap {
            max-width: 1024px;
            margin: 5rem auto;
            padding: 2rem;
            background: #fff;
            text-align: center;
            border: 1px solid #efefef;
            border-radius: 0.5rem;
            position: relative;
        }
        pre {
            white-space: normal;
            margin-top: 1.5rem;
        }
        code {
            background: #fafafa;
            border: 1px solid #efefef;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            display: block;
        }
        p {
            margin-top: 1.5rem;
        }
        .footer {
            margin-top: 2rem;
            border-top: 1px solid #efefef;
            padding: 1em 2em 0 2em;
            font-size: 85%;
            color: #999;
        }
        a:active,
        a:link,
        a:visited {
            color: #dd4814;
        }
    </style>
</head>
<body>
<div class="wrap">
    <h1>400</h1>

    <p>
        <?php if (ENVIRONMENT !== 'production') : ?>
            <?= nl2br(esc($message)) ?>
        <?php else : ?>
            <?= lang('Errors.sorryBadRequest') ?>
        <?php endif; ?>
    </p>
</div>
</body>
</html>
