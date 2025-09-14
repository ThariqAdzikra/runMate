<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RunMate Auth')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center; 
            justify-content: flex-start; 
            padding: 20px 8%; 
            position: relative;
            overflow-x: hidden;
            background-color: #ffffff; /* Latar belakang kiri menjadi putih */
        }
        
        body::before {
            display: none;
        }

        body::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 55%; /* Gambar latar di sisi kanan diperlebar */
            background-image: url("{{ asset('image/runmateBg.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 2;
            opacity: 0.8;
        }

        .auth-container {
            position: relative;
            z-index: 10;
            /* Gradient background untuk form */
            background: linear-gradient(135deg, #365a91 0%, #63c6ee 100%);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 25px 45px rgba(0, 0, 0, 0.1),
                0 0 80px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.8s ease-out;
            max-width: 450px;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            /* Mengubah warna teks logo menjadi putih solid */
            color: #ffffff;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .logo p {
            /* Mengubah warna teks deskripsi logo */
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }
        
        /* Styling untuk Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            /* Mengubah warna label form */
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 15px 20px;
            /* Mengubah style input field agar kontras dengan background baru */
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input::placeholder {
            /* Mengubah warna placeholder */
            color: rgba(255, 255, 255, 0.5);
        }

        .form-select {
            cursor: pointer;
        }

        .form-select option {
            background: #365a91; /* Warna background option */
            color: #ffffff;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.2);
            border-color: #63c6ee;
            box-shadow: 0 0 0 3px rgba(99, 198, 238, 0.2);
            transform: translateY(-2px);
        }

        /* Styling untuk Tombol */
        .btn-primary {
            width: 100%;
            padding: 16px;
            /* Gradient background baru untuk tombol */
            background: linear-gradient(135deg, #ed9774 0%, #e85a4f 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            /* Shadow yang disesuaikan dengan warna tombol baru */
            box-shadow: 0 8px 20px rgba(237, 151, 116, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(237, 151, 116, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Styling untuk Pesan Error/Sukses */
        .error-message, .success-message {
            color: #ffffff;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .error-message {
            background: rgba(232, 90, 79, 0.5); /* Latar belakang error lebih jelas */
            border: 1px solid rgba(232, 90, 79, 0.8);
        }

        .success-message {
            background: rgba(0, 184, 148, 0.5); /* Latar belakang success lebih jelas */
            border: 1px solid rgba(0, 184, 148, 0.8);
        }
        

        /* Progress Bar */
        .progress-container {
            margin-bottom: 30px;
        }

        .progress-text {
             /* Mengubah warna teks progress */
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #ffffff; /* Fill progress bar menjadi putih */
        }

        /* Section Title */
        .section-title {
             /* Mengubah warna section title */
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            font-weight: 600;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        /* Button styling for secondary actions */
        .btn-secondary {
            flex: 0 0 auto;
            text-align: center;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: #ffffff;
            padding: 16px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            line-height: 1;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.7);
        }

        /* Google button specific styling */
        .google-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            color: #333;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .google-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Divider styling */
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .divider-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            padding: 0 15px;
        }

        /* Link styling */
        .auth-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.3s ease;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .forgot-password {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #ffffff;
        }

        /* Form helper text */
        .form-helper {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
        }
        
        /* Mengubah warna teks error individual */
        [style*="color: #ff6b6b"], [style*="color: #e74c3c"] {
            color: white !important;
            background-color: rgba(0,0,0,0.2);
            padding: 2px 4px;
            border-radius: 4px;
        }

        /* Button group */
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        /* Responsiveness */
        @media (min-width: 992px) {
            body {
                background-color: #ffffff;
            }
        }

        @media (max-width: 992px) {
            body {
                justify-content: center; 
                padding: 20px;
                background-color: #f4f7f6; /* Fallback bg untuk mobile */
            }
            
            body::after {
                width: 100%;
                background-position: center;
                opacity: 0.2;
            }
        }

        @media (max-width: 768px) {
            .auth-container {
                padding: 30px 20px;
                margin: 10px;
                max-width: none;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .button-group {
                flex-direction: column;
                gap: 10px;
            }
            
            .logo h1 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .auth-container {
                padding: 25px 15px;
            }
        }
    </style>
</head>
<body>
    @yield('content')
    @stack('scripts')
</body>
</html>