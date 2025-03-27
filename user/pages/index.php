<?php
// Include database connection if needed for future expansion
include('../../includes/connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Monitoring System</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        height: 100vh;
        overflow: hidden;
    }
    
    .hero-title {
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .explore-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.5);
    }
    
    .explore-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px -5px rgba(59, 130, 246, 0.6);
    }
    
    .explore-btn:active {
        transform: translateY(1px);
    }
    
    .explore-btn::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -60%;
        width: 200%;
        height: 200%;
        background: rgba(255,255,255,0.2);
        transform: rotate(30deg);
        transition: all 0.3s ease;
    }
    
    .explore-btn:hover::after {
        left: 100%;
    }
    
    .floating {
        animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>

<body class="flex items-center justify-center">
    <div class="text-center px-4">
        <!-- Logo Added Here -->
        <div class="flex justify-center mb-8 animate__animated animate__fadeIn">
            <img src="../../img/logo.png" alt="Logo" class="h-20">
        </div>
        
        <!-- Animated Title -->
        <h1 class="hero-title text-5xl md:text-7xl font-bold text-gray-800 mb-8 animate__animated animate__fadeInDown">
            Online Product Price Monitoring <span class="text-blue-600">System</span>
        </h1>
        
        <!-- Floating Animated Button -->
        <div class="floating pulse">
            <a href="price_monitoring.php" class="explore-btn inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold text-lg py-4 px-12 rounded-full">
                Explore Now
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block ml-2 animate__animated animate__fadeInRight" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
        
        <!-- Subtle animated circles in background -->
        <div class="fixed -z-10">
            <div class="absolute top-1/4 left-1/4 w-32 h-32 rounded-full bg-blue-100 opacity-20 animate__animated animate__pulse animate__infinite"></div>
            <div class="absolute bottom-1/3 right-1/4 w-48 h-48 rounded-full bg-blue-200 opacity-10 animate__animated animate__pulse animate__infinite animate__slower"></div>
            <div class="absolute top-1/3 right-1/3 w-24 h-24 rounded-full bg-blue-300 opacity-15 animate__animated animate__pulse animate__infinite animate__slow"></div>
        </div>
    </div>

    <!-- GSAP for advanced animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <script>
        // Advanced animation on load
        document.addEventListener('DOMContentLoaded', () => {
            gsap.from('.hero-title', {
                duration: 1.5,
                y: -50,
                opacity: 0,
                ease: 'back.out(1.7)'
            });
            
            gsap.from('.floating', {
                duration: 1.5,
                y: 50,
                opacity: 0,
                delay: 0.3,
                ease: 'elastic.out(1, 0.5)'
            });
            
            // Add animation for the logo
            gsap.from('img[alt="Logo"]', {
                duration: 1,
                scale: 0.5,
                opacity: 0,
                ease: 'back.out(1.7)'
            });
        });
        
        // Button hover effect enhancement
        const btn = document.querySelector('.explore-btn');
        btn.addEventListener('mouseenter', () => {
            gsap.to(btn, { 
                duration: 0.3,
                scale: 1.05,
                ease: 'power2.out'
            });
        });
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { 
                duration: 0.3,
                scale: 1,
                ease: 'power2.out'
            });
        });
    </script>
</body>
</html>