<?php include_once("header-ai-for-business.php"); ?>
<title>AI for Business Registration</title>
<link rel="stylesheet" href="assets/css/ai-for-business.css">

<style>
    #header {
        border-bottom: none;
    }

    .text-holder span {
        opacity: 0;
        animation-name: textAnimationBlur;
        animation-duration: 1s;
        animation-fill-mode: forwards;
        -webkit-backface-visibility: hidden;
    }

    @keyframes textAnimationBlur {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .event-date {
        position: fixed;
        top: 35%;
        right: -9%;
        /* left: -7%; */
        z-index: 1000;
        transform: rotate(90deg);
        background: rgba(12, 28, 34, 0.9);
        padding: 10px 20px;
        border-radius: 5px;
        color: #fff;
    }

    .event-date p {
        margin: 0;
        font-size: 16px;

        text-transform: uppercase;
        color: #fff !important;
        font-weight: 600;
        font-family: "transducer_test_regularRg";
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        pointer-events: none;
    }

    .event-date span {
        margin: 0 5px;
    }

    /* Dropdown Button */
    .dropbtn {
        background-color: transparent;
        color: #0c1c22;
        padding: 0;
        font-size: 40px;
        border: none;
    }

    .dropbtn:hover a,
    .dropbtn:hover a:hover,
    .dropbtn:hover a:focus {
        color: #0c1c22;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
        position: relative;
        display: block;
        width: fit-content;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #0d1e25;
        /* min-width: 160px; */
        width: 100%;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
        color: #102730;
        padding: 5px 16px;
        text-decoration: none;
        display: block;
        font-size: 22px;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {
        background-color: #102730;
    }

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Change the background color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn,
    .dropdown:hover .dropbtn a {
        background-color: #FFFF00;
        color: #0c1c22 !important;

    }

    /* AI Workshop Header Styles */
    .ai-workshop-header {
        background: #0c1c22 !important;
        /* opaque header */
        border-bottom: 1px solid #000;
        /* darker bottom border */
        padding: 15px 0;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .ai-header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .ai-workshop-title {
        text-align: left;
    }

    .ai-workshop-main-title {
        color: #fff;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .ai-workshop-subtitle {
        color: #fff;
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        margin-top: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .ai-header-center {
        text-align: center;
    }

    .ai-event-details {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #fff;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .ai-event-separator {
        color: #fff;
        font-weight: 300;
    }

    .ai-header-right {
        text-align: right;
    }

    .ai-header-buttons {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
    }

    .ai-btn {
        padding: 12px 24px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        display: inline-block;
        min-width: 100px;
        text-align: center;
    }

    .ai-btn-register {
        background: linear-gradient(135deg, #00b4d8, #0096c7);
        color: #fff;
        box-shadow: 0 4px 15px rgba(0, 180, 216, 0.3);
    }

    .ai-btn-register:hover {
        background: linear-gradient(135deg, #0096c7, #0077b6);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 180, 216, 0.4);
        color: #fff;
    }

    .ai-btn-faq {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 2px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    .ai-btn-faq:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        color: #fff;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .ai-workshop-main-title {
            font-size: 1.5rem;
        }

        .ai-workshop-subtitle {
            font-size: 0.9rem;
        }

        .ai-event-details {
            font-size: 1rem;
        }

        .ai-btn {
            padding: 10px 20px;
            font-size: 0.8rem;
            min-width: 80px;
        }
    }

    @media (max-width: 768px) {
        .ai-workshop-header {
            padding: 10px 0;
        }

        .ai-header-container {
            padding: 0 15px;
        }

        .ai-workshop-main-title {
            font-size: 1.3rem;
        }

        .ai-workshop-subtitle {
            font-size: 0.8rem;
        }

        .ai-event-details {
            font-size: 0.9rem;
            flex-direction: column;
            gap: 5px;
        }

        .ai-event-separator {
            display: none;
        }

        .ai-header-buttons {
            gap: 10px;
            justify-content: center;
        }

        .ai-btn {
            padding: 8px 16px;
            font-size: 0.75rem;
            min-width: 70px;
        }
    }

    @media (max-width: 576px) {
        .ai-workshop-main-title {
            font-size: 1.1rem;
        }

        .ai-workshop-subtitle {
            font-size: 0.7rem;
        }

        .ai-event-details {
            font-size: 0.8rem;
        }

        .ai-header-buttons {
            flex-direction: column;
            gap: 8px;
        }

        .ai-btn {
            padding: 6px 12px;
            font-size: 0.7rem;
            min-width: 60px;
        }
    }
</style>

<link href="https://db.onlinewebfonts.com/c/9b2873c414cc836c8104c5cfca409287?family=Transducer+Test+Black+Condensed"
    rel="stylesheet">


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16840408600"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'AW-16840408600');
</script>





<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-YMQZ665X2Z"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'G-YMQZ665X2Z');
</script>



<!-- Google Tag Manager -->
<script>
    (function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-PGL756B4');
</script>
<!-- End Google Tag Manager -->

<!-- Google tag (gtag.js) -->




<!-- Meta Pixel Code -->
<script>
    !function (f, b, e, v, n, t, s) {
        if (f.fbq) return; n = f.fbq = function () {
            n.callMethod ?
            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
        n.queue = []; t = b.createElement(e); t.async = !0;
        t.src = v; s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '470302849479612');
    fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=470302849479612&ev=PageView&noscript=1" /></noscript>
<!-- End Meta Pixel Code -->

<section class="what-we-do bg2 pad-0 pos-relative mt-130 " style="display: none;">
    <div class="liner-full"><img src="assets/img/home-2shadw.png" class="img-fluid"></div>

    <div class="container-custom ">

        <div class="section-title inner-main text-center logo-w" style="padding-bottom:10px;">
            <div><img src="assets/img/shiv-home1.png" class="img-fluid"></div>

            <div style="padding:5px;"><img src="assets/img/presents.png" class="img-fluid" width="60"></div>
            <div class="p-t-b-15 rohini"><img src="assets/img/Synapse-logo-home.png" class="img-fluid"></div>
            <div>
                <p class="mb-15">POWERED BY</p>
                <img src="assets/img/glenlevit-white.png" class="img-fluid">
            </div>
        </div>
    </div>
</section>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <!--<link href="https://db.onlinewebfonts.com/c/29b618e51713f91ccd7706cf0543a3f9?family=Transducer+Test+Regular" rel="stylesheet">-->
    <link href="https://db.onlinewebfonts.com/c/c6f2d806c4a0870eb17c9e323ce61585?family=Transducer+Test+Hairline"
        rel="stylesheet">
    <!-- EO DUBAI -->
    <style>
        .section-bg-image{
            min-height: 80vh !important;
        }
        .registration-section {
            /* background: url('assets/img/registration/bg.png') no-repeat center center;
    background-size: cover; */
            /* min-height: 100vh; */
            padding: 60px 0;
            /* padding-top: 100px; */
            /* Added to push content down from the top */
            position: relative;
            margin-top: -180px;
        }

        /* .registration-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 1;
} */

        .registration-content {
            position: relative;
            z-index: 2;
        }

        .registration-header {
            text-align: left;
            margin-bottom: 50px;
            padding-left: 50px;
            position: relative;
            /* background: url('https://placehold.co/600x400/000/000') no-repeat center center; */
            background: url('assets/img/ai-forbusiness-bg.jpg') no-repeat center top;
            background-size: cover;
            padding: 150px 100px 300px;
        }

        .registration-header h1 {
            color: white;
            font-size: 4rem;
            font-weight: 300;
            line-height: 1.1;
            margin-bottom: 10px;
            /* font-family: 'Poppins', sans-serif; */
        }

        .registration-header .subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.3rem;
            font-weight: 300;
            margin: 0;
        }


        .registration-form-container {
            max-width: 800px;
            margin: 0 auto;
            /* margin-top: -150px; */
            /* Removed negative margin */
            background: #0c1c22;
            border-radius: 25px;
            /* padding: 50px; */
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .registration-form h3 {
            color: white;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 40px;
            text-align: left;
            /* font-family: 'Poppins', sans-serif; */
        }

        .form-row {
            display: flex;
            gap: 25px;
            margin-bottom: 25px;
        }

        .form-group {
            flex: 1;
        }

        .form-control {
            width: 100%;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 18px 20px;
            font-size: 1rem;
            color: #495057;
            transition: all 0.3s ease;
            /* font-family: 'Poppins', sans-serif; */
            box-sizing: border-box;
        }

        .form-control::placeholder {
            color: #6c757d;
            font-weight: 400;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .discount-notice {
            font-weight: 500;
            /* font-family: 'Poppins', sans-serif; */
            margin-top: 20px;
            /* Adjusted margin-top */
        }

        .discount-message-applied {
            background: #d4edda;
            /* Light green background */
            color: #155724;
            /* Dark green text */
            border: 1px solid #c3e6cb;
            /* Green border */
            padding: 20px 30px;
            border-radius: 12px;
            text-align: center;
            margin: 20px 0;
            font-size: 1.1rem;
            font-weight: 500;
            /* font-family: 'Poppins', sans-serif; */
            display: none;
            /* Hidden by default */
        }

        .payment-summary {
            display: none;
            justify-content: space-between;
            align-items: center;
            margin: 35px 0;
            font-size: 1.3rem;
            /* font-family: 'Poppins', sans-serif; */
        }

        .payment-label {
            color: white;
            font-weight: 400;
        }

        .payment-amount {
            /* font-size: 1.8rem; */
            font-weight: 600;
        }

        .continue-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 18px 50px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            /* font-family: 'Poppins', sans-serif; */
            float: right;
        }

        .continue-btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        }

        /* Tablet Styles */
        @media (max-width: 1024px) {
            .registration-header {
                padding: 100px 50px 200px;
            }

            .registration-header h1 {
                font-size: 3.5rem;
            }


            .registration-form-container {
                margin-top: 50px;
                margin-left: 20px;
                margin-right: 20px;
                padding: 40px;
            }
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .registration-section {
                padding: 40px 0;
            }

            .registration-header {
                text-align: center;
                padding: 80px 20px 120px;
                background-position: center;
            }

            .registration-header h1 {
                font-size: 2.8rem;
                line-height: 1.2;
            }

            .registration-header .subtitle {
                font-size: 1.1rem;
            }


            .registration-form-container {
                margin: 30px 15px 0;
                padding: 30px 25px;
            }

            .registration-form h3 {
                font-size: 1.6rem;
                margin-bottom: 30px;
            }

            .form-row {
                flex-direction: column;
                gap: 20px;
            }

            .form-control {
                padding: 16px 18px;
                font-size: 0.95rem;
            }

            .discount-notice {
                margin: 25px 0;
                padding: 15px 20px;
                font-size: 1rem;
            }

            .payment-summary {
                margin: 25px 0;
                font-size: 1.1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .continue-btn {
                width: 100%;
                padding: 16px 40px;
                font-size: 1rem;
                float: none;
            }
        }

        /* Small Mobile Styles */
        @media (max-width: 480px) {
            .registration-header {
                padding: 100px 15px 100px;
            }

            .registration-header h1 {
                font-size: 2.2rem;
            }


            .registration-form-container {
                margin: 25px 10px 0;
                padding: 25px 20px;
            }

        }

        /* Styles for the policy modal */
        .policy-modal-overlay {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1000;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.7);
            /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        }

        .policy-modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 30px;
            border-radius: 15px;
            width: 80%;
            /* Could be more responsive */
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .policy-modal-content h4 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .policy-modal-content ul {
            list-style: disc;
            padding-left: 20px;
            margin-bottom: 20px;
        }

        .policy-modal-content li {
            color: #495057;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .policy-modal-content strong {
            color: #2c3e50;
        }

        .policy-modal-close-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }

    .policy-modal-close-btn:hover {
        background: #0056b3;
    }

    /* Styles for the custom alert card */
    .custom-alert-overlay {
        display: none;
        position: fixed;
        z-index: 1001; /* Higher than policy modal */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
    }

    .custom-alert-card {
        background-color: #fefefe;
        margin: auto;
        padding: 30px;
        border-radius: 15px;
        width: 80%;
        max-width: 400px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        position: relative;
        text-align: center;
    }

    .custom-alert-card h4 {
        color: #2c3e50;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .custom-alert-card p {
        color: #495057;
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    .custom-alert-ok-btn {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .custom-alert-ok-btn:hover {
        background: #0056b3;
    }
</style>

<div id="hero-section" class="section-bg-image" style="background-image: url('assets/img/ai-forbusiness-bg.jpg');">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="section-content">
                    <h1 class="section-title">
                        AI FOR <span>BUSINESS</span>
                    </h1>
                    <p class="section-subtitle">
                        Unlock the power of AI for your business with Singularity Faculty
                    </p>
                </div>
            </div>
            <div class="col-lg-6">

            </div>
        </div>
    </div>
    <!-- Overlay for better text readability -->
    <div class="section-overlay"></div>
</div>

    <section class="registration-section" id="register-now">
        <div class="">
            <div class="registration-content">
                <div class="registration-form-container">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h4 style="font-weight: bold; color: white;">Registration Fee: AED 5999</h4>
                        
                    </div>
                    <h3 style="color: white;">Registration Form</h3>
                    <form id="registrationForm" action="#" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" id="emailInput"
                                    placeholder="Email ID*" required="">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" id="nameInput" placeholder="Name*"
                                    required="">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <select class="form-control" name="chapter" id="chapterSelect" required="">
                                    <option value="" disabled="" selected="">EO Chapter/Guest*</option>
                                    <option value="EO Dubai Member">EO Dubai Member</option>
                                    <option value="EO Dubai Spouse">EO Dubai Spouse</option>
                                    <option value="EO Dubai Accelerator">EO Dubai Accelerator</option>
                                    <option value="EO Dubai Next Gen">EO Dubai Next Gen</option>
                                    <option value="EO Dubai Key Executive">EO Dubai Key Executive</option>
                                    <option value="EO Global Key Executive">EO Global Key Executive</option>
                                    <option value="Guest">Guest</option>
                                    <li></li>
                                    <hr class="dropdown-divider" style="color: #ededed">
                                    <option value="EO Adelaide">EO Adelaide</option>
                                    <option value="EO Albany">EO Albany</option>
                                    <option value="EO Andhra Pradesh">EO Andhra Pradesh</option>
                                    <option value="EO APAC Bridge">EO APAC Bridge</option>
                                    <option value="EO APAC Platinum One Bridge">EO APAC Platinum One Bridge</option>
                                    <option value="EO Argentina">EO Argentina</option>
                                    <option value="EO Arizona">EO Arizona</option>
                                    <option value="EO Atlanta">EO Atlanta</option>
                                    <option value="EO Atlantic Canada">EO Atlantic Canada</option>
                                    <option value="EO Austin">EO Austin</option>
                                    <option value="EO Austria">EO Austria</option>
                                    <option value="EO Bahrain">EO Bahrain</option>
                                    <option value="EO Baltimore">EO Baltimore</option>
                                    <option value="EO Bangalore">EO Bangalore</option>
                                    <option value="EO Bangkok Metropolitan">EO Bangkok Metropolitan</option>
                                    <option value="EO Bangladesh">EO Bangladesh</option>
                                    <option value="EO Barcelona">EO Barcelona</option>
                                    <option value="EO Beijing">EO Beijing</option>
                                    <option value="EO Beijing Metropolitan">EO Beijing Metropolitan</option>
                                    <option value="EO Belgium">EO Belgium</option>
                                    <option value="EO Berlin">EO Berlin</option>
                                    <option value="EO Beyond Japan Bridge">EO Beyond Japan Bridge</option>
                                    <option value="EO Bhopal">EO Bhopal</option>
                                    <option value="EO Birmingham">EO Birmingham</option>
                                    <option value="EO Bogota">EO Bogota</option>
                                    <option value="EO Boston">EO Boston</option>
                                    <option value="EO Cairo">EO Cairo</option>
                                    <option value="EO Calgary">EO Calgary</option>
                                    <option value="EO Canada Bridge">EO Canada Bridge</option>
                                    <option value="EO Cancun-Riviera Maya">EO Cancun-Riviera Maya</option>
                                    <option value="EO Cape Town">EO Cape Town</option>
                                    <option value="EO Charleston">EO Charleston</option>
                                    <option value="EO Charlotte">EO Charlotte</option>
                                    <option value="EO Chengdu - Coming Soon">EO Chengdu - Coming Soon</option>
                                    <option value="EO Chennai">EO Chennai</option>
                                    <option value="EO Chicago">EO Chicago</option>
                                    <option value="EO China East">EO China East</option>
                                    <option value="EO Cincinnati">EO Cincinnati</option>
                                    <option value="EO Cleveland">EO Cleveland</option>
                                    <option value="EO Coimbatore">EO Coimbatore</option>
                                    <option value="EO Colorado">EO Colorado</option>
                                    <option value="EO Columbus">EO Columbus</option>
                                    <option value="EO Connecticut">EO Connecticut</option>
                                    <option value="EO Costa Rica">EO Costa Rica</option>
                                    <option value="EO Dalian">EO Dalian</option>
                                    <option value="EO Dallas">EO Dallas</option>
                                    <option value="EO DC">EO DC</option>
                                    <option value="EO Detroit">EO Detroit</option>
                                    <option value="EO Dominican Republic">EO Dominican Republic</option>
                                    <option value="EO Dubai">EO Dubai</option>
                                    <option value="EO Durban">EO Durban</option>
                                    <option value="EO Edmonton">EO Edmonton</option>
                                    <option value="EO El Salvador">EO El Salvador</option>
                                    <option value="EO Europe Bridge">EO Europe Bridge</option>
                                    <option value="EO Fort Worth">EO Fort Worth</option>
                                    <option value="EO Germany - Rhine Ruhr">EO Germany - Rhine Ruhr</option>
                                    <option value="EO Germany - Southwest">EO Germany - Southwest</option>
                                    <option value="EO Goa">EO Goa</option>
                                    <option value="EO Greater Bay Area - Coming Soon">EO Greater Bay Area - Coming Soon
                                    </option>
                                    <option value="EO Greater China Bridge">EO Greater China Bridge</option>
                                    <option value="EO Greece">EO Greece</option>
                                    <option value="EO Guadalajara">EO Guadalajara</option>
                                    <option value="EO Guangzhou">EO Guangzhou</option>
                                    <option value="EO Guatemala">EO Guatemala</option>
                                    <option value="EO Guayaquil">EO Guayaquil</option>
                                    <option value="EO Gujarat">EO Gujarat</option>
                                    <option value="EO Gurgaon">EO Gurgaon</option>
                                    <option value="EO Hamburg">EO Hamburg</option>
                                    <option value="EO Hawaii">EO Hawaii</option>
                                    <option value="EO Hokkaido">EO Hokkaido</option>
                                    <option value="EO Hokuriku">EO Hokuriku</option>
                                    <option value="EO Hong Kong">EO Hong Kong</option>
                                    <option value="EO Houston">EO Houston</option>
                                    <option value="EO Hyderabad">EO Hyderabad</option>
                                    <option value="EO Ibaraki">EO Ibaraki</option>
                                    <option value="EO Idaho">EO Idaho</option>
                                    <option value="EO Indianapolis">EO Indianapolis</option>
                                    <option value="EO Indonesia">EO Indonesia</option>
                                    <option value="EO Indonesia East">EO Indonesia East</option>
                                    <option value="EO Indore">EO Indore</option>
                                    <option value="EO Inland Empire">EO Inland Empire</option>
                                    <option value="EO Iowa">EO Iowa</option>
                                    <option value="EO Ireland">EO Ireland</option>
                                    <option value="EO Islamabad">EO Islamabad</option>
                                    <option value="EO Israel">EO Israel</option>
                                    <option value="EO Italy">EO Italy</option>
                                    <option value="EO Jaipur">EO Jaipur</option>
                                    <option value="EO Jammu and Kashmir - Coming Soon">EO Jammu and Kashmir - Coming
                                        Soon</option>
                                    <option value="EO Japan Korea Bridge">EO Japan Korea Bridge</option>
                                    <option value="EO Jeddah">EO Jeddah</option>
                                    <option value="EO Johannesburg">EO Johannesburg</option>
                                    <option value="EO Jordan">EO Jordan</option>
                                    <option value="EO Kanagawa">EO Kanagawa</option>
                                    <option value="EO Kansai Metropolitan">EO Kansai Metropolitan</option>
                                    <option value="EO Kansas City">EO Kansas City</option>
                                    <option value="EO Karachi">EO Karachi</option>
                                    <option value="EO Kelowna">EO Kelowna</option>
                                    <option value="EO Kenya">EO Kenya</option>
                                    <option value="EO Kerala">EO Kerala</option>
                                    <option value="EO Kobe">EO Kobe</option>
                                    <option value="EO Kolkata">EO Kolkata</option>
                                    <option value="EO Korea">EO Korea</option>
                                    <option value="EO Kuwait">EO Kuwait</option>
                                    <option value="EO Kyoto">EO Kyoto</option>
                                    <option value="EO Kyushu">EO Kyushu</option>
                                    <option value="EO LAC Bridge">EO LAC Bridge</option>
                                    <option value="EO Lahore">EO Lahore</option>
                                    <option value="EO Las Vegas">EO Las Vegas</option>
                                    <option value="EO Lebanon">EO Lebanon</option>
                                    <option value="EO Los Angeles">EO Los Angeles</option>
                                    <option value="EO Los Angeles - Valley">EO Los Angeles - Valley</option>
                                    <option value="EO Louisiana">EO Louisiana</option>
                                    <option value="EO Madrid">EO Madrid</option>
                                    <option value="EO Malaysia">EO Malaysia</option>
                                    <option value="EO Melbourne">EO Melbourne</option>
                                    <option value="EO MEPA Bridge">EO MEPA Bridge</option>
                                    <option value="EO Merida">EO Merida</option>
                                    <option value="EO Mexico City">EO Mexico City</option>
                                    <option value="EO Minnesota">EO Minnesota</option>
                                    <option value="EO Monterrey">EO Monterrey</option>
                                    <option value="EO Montreal">EO Montreal</option>
                                    <option value="EO Morocco - Coming Soon">EO Morocco - Coming Soon</option>
                                    <option value="EO Mumbai">EO Mumbai</option>
                                    <option value="EO Munich">EO Munich</option>
                                    <option value="EO Nagoya">EO Nagoya</option>
                                    <option value="EO Nagpur">EO Nagpur</option>
                                    <option value="EO Nashville">EO Nashville</option>
                                    <option value="EO Navi Mumbai">EO Navi Mumbai</option>
                                    <option value="EO Nebraska">EO Nebraska</option>
                                    <option value="EO Nepal">EO Nepal</option>
                                    <option value="EO Netherlands">EO Netherlands</option>
                                    <option value="EO New Delhi">EO New Delhi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="tel" class="form-control" name="phone" id="phoneInput" placeholder="Phone*"
                                    required="">
                            </div>
                        </div>

                        <div id="noShowPenaltyContainer" style="margin-top: 35px; color: white;">
                            <!-- Adjusted margin-top -->
                            <label>
                                <input type="checkbox" name="no_show_consent">
                                <span style="margin-left: 10px;">I confirm that I have read and accept the <a href="event-policy.php" target="_blank" style="color: #007bff;">Event Policy</a> and authorize EO Dubai to process applicable event fees or charges, including the no-show penalty of AED 3,999 (applicable only for EO Dubai Members/Spouses), as stated in the Event Policy.</span>
                            </label>
                        </div>


                        <div class="discount-message-applied" id="appliedDiscountMessage">
                            <!-- Discount message will be inserted here by JavaScript -->
                        </div>

                        <div class="payment-summary">
                            <span class="payment-label">To be paid: <span class="payment-amount" id="paymentAmount">AED
                                    5999</span></span>
                            <div class="text-right">
                                <button type="submit" class="continue-btn">Register</button>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Custom Alert Card Structure -->
                <div id="customAlertOverlay" class="custom-alert-overlay">
                    <div class="custom-alert-card">
                        <h4>Category Mismatch</h4>
                        <p>Please select another category since You are not from above category.</p>
                        <button id="customAlertOkBtn" class="custom-alert-ok-btn">OK</button>
                    </div>
                </div>


            </div>
        </div>
    </section>


    <script>
            document.addEventListener('DOMContentLoaded', function () {
            let verifiedMemberType = null; // To store the member type from backend verification
            let currentPricing = {
                currency: 'AED',
                'EO Dubai Member': { amount: 0, penalty: 3999 },
                'EO Dubai Spouse': { amount: 0, penalty: 3999 },
                'EO Dubai Accelerator': { amount: 2999 },
                'EO Dubai Next Gen': { amount: 2999 },
                'EO Dubai Key Executive': { amount: 3999 },
                'EO Global Key Executive': { amount: 3999 },
                'EO Jordan': { amount: 3999 },
                'Guest': { amount: 5999 } // Guest category (formerly Others)
            };

            const emailInput = document.getElementById('emailInput');
            const nameInput = document.getElementById('nameInput');
            const phoneInput = document.getElementById('phoneInput');
            const chapterSelect = document.getElementById('chapterSelect');
            const appliedDiscountMessage = document.getElementById('appliedDiscountMessage');
            const paymentAmount = document.getElementById('paymentAmount');
            const paymentSummary = document.querySelector('.payment-summary');
            const reversibleChargeNotice = document.getElementById('reversibleChargeNotice');
            const penaltyCheckboxContainer = document.getElementById('noShowPenaltyContainer');
            const noShowConsentCheckbox = document.querySelector('input[name="no_show_consent"]');
            const registrationForm = document.getElementById('registrationForm');
            const submitButton = document.querySelector('.continue-btn');
            const customAlertOverlay = document.getElementById('customAlertOverlay'); // New: Custom alert overlay
            const customAlertOkBtn = document.getElementById('customAlertOkBtn');     // New: Custom alert OK button

            // Input event listener for dynamic discount check (only on email blur as per user's request)
            emailInput.addEventListener('blur', handleDiscountCheck);
            // Removed phoneInput and nameInput blur listeners to simplify trigger
            chapterSelect.addEventListener('change', handleChapterChange);
            customAlertOkBtn.addEventListener('click', function() {
                customAlertOverlay.style.display = 'none';
            });

            async function handleDiscountCheck() {
                const email = emailInput.value.trim();
                const phone = phoneInput.value.trim(); // Get phone input as well

                console.log(`[handleDiscountCheck] Email: ${email}, Phone: ${phone}`);

                // Hide message and reset price by default
                if (appliedDiscountMessage) {
                    appliedDiscountMessage.textContent = '';
                    appliedDiscountMessage.style.display = 'none';
                }

                if (email || phone) { // Check if either email or phone is provided
                    // Always proceed with API call
                    console.log(`[handleDiscountCheck] Value of phoneInput.value before fetch: '${phoneInput.value}'`);
                    console.log(`[handleDiscountCheck] Fetching /api/verify-user for email: ${email}, phone: ${phone}`);
                    try {
                        const response = await fetch('http://backend-production-c14ce.up.railway.app/api/verify-user', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ email, phone }), // Send both email and phone
                        });

                        console.log(`[handleDiscountCheck] Response status: ${response.status}`);
                        if (!response.ok) {
                            console.error(`[handleDiscountCheck] Network response not ok: ${response.status}`);
                            throw new Error('Network response was not ok');
                        }
                        const data = await response.json();
                        console.log('[handleDiscountCheck] /api/verify-user response data:', data);
                        verifiedMemberType = data.member_type; // Store the verified member type

                        if (data.success && data.is_member) {
                            nameInput.value = data.name;
                            phoneInput.value = data.phone;
                            chapterSelect.value = data.member_type;
                            console.log(`[handleDiscountCheck] chapterSelect.value set to: ${chapterSelect.value}`);

                            if (appliedDiscountMessage) {
                                appliedDiscountMessage.textContent = data.discount_message || 'Membership discount applied!';
                                appliedDiscountMessage.style.display = 'block';
                            }
                        } else {
                            // Not a recognized member, reset verifiedMemberType and fields
                            verifiedMemberType = null;
                            // nameInput.value = ''; // Do not clear name/phone if not found, user might be typing
                            // phoneInput.value = '';
                            chapterSelect.value = 'Guest'; // Reset chapter to Guest if not a member
                            if (appliedDiscountMessage) {
                                appliedDiscountMessage.textContent = '';
                                appliedDiscountMessage.style.display = 'none';
                            }
                        }
                        updatePaymentAmount(); // Update payment amount after verification
                    } catch (error) {
                        console.error('[handleDiscountCheck] Error verifying email:', error);
                        verifiedMemberType = null; // Reset verified member type on error
                        // nameInput.value = '';
                        // phoneInput.value = '';
                        chapterSelect.value = 'Guest'; // Reset chapter to Guest on error
                        if (appliedDiscountMessage) {
                            appliedDiscountMessage.textContent = '';
                            appliedDiscountMessage.style.display = 'none';
                        }
                        updatePaymentAmount(); // Update payment amount on error
                    }
                } else {
                    // No email or phone, so reset verifiedMemberType and update payment
                    verifiedMemberType = null;
                    // nameInput.value = '';
                    // phoneInput.value = '';
                    chapterSelect.value = 'Guest'; // Reset chapter to Guest
                    if (appliedDiscountMessage) {
                        appliedDiscountMessage.textContent = '';
                        appliedDiscountMessage.style.display = 'none';
                    }
                    updatePaymentAmount();
                }
            }

            function handleChapterChange() {
                const selectedChapter = chapterSelect.value;
                const restrictedCategories = ['EO Dubai Member', 'EO Dubai Spouse', 'EO Dubai Accelerator', 'EO Dubai Next Gen', 'EO Dubai Key Executive'];

                // If a restricted category is manually selected, but not verified by backend, show alert
                // This check now relies solely on `verifiedMemberType` from the backend
                if (restrictedCategories.includes(selectedChapter) && selectedChapter !== verifiedMemberType) {
                    customAlertOverlay.style.display = 'flex'; // Display the custom alert card
                    chapterSelect.value = 'Guest'; // Reset to "Guest"
                }
                updatePaymentAmount();
            }


            // Function to update no-show consent visibility and required status
            function updateNoShowConsentVisibilityAndRequired() {
                if (penaltyCheckboxContainer && noShowConsentCheckbox) {
                    penaltyCheckboxContainer.style.display = 'block';
                    noShowConsentCheckbox.setAttribute('required', 'required');
                }
            }

            // Function to update payment amount based on selected chapter
            function updatePaymentAmount() {
                const selectedChapter = chapterSelect.value;

                let amount = 0;
                let chapterForPricing = verifiedMemberType || selectedChapter; // Prioritize verified type

                console.log(`[updatePaymentAmount] selectedChapter: ${selectedChapter}, verifiedMemberType: ${verifiedMemberType}, chapterForPricing: ${chapterForPricing}`);

                if (chapterForPricing === 'EO Dubai Member' || chapterForPricing === 'EO Dubai Spouse') {
                    amount = currentPricing[chapterForPricing].amount; // AED 0
                    // reversibleChargeNotice.style.display = 'block'; // Removed as per request
                } else if (chapterForPricing === 'EO Dubai Accelerator' || chapterForPricing === 'EO Dubai Next Gen' || chapterForPricing === 'EO Dubai Key Executive' || chapterForPricing === 'EO Global Key Executive') {
                    amount = currentPricing[chapterForPricing].amount; // AED 3999
                    // reversibleChargeNotice.style.display = 'none'; // Removed as per request
                } else if (chapterForPricing === 'Guest') {
                    amount = currentPricing['Guest'].amount; // AED 5999
                    // reversibleChargeNotice.style.display = 'none'; // Removed as per request
                } else {
                    // If chapterForPricing is not recognized or empty, default to Guest price
                    amount = currentPricing['Guest'].amount;
                    // reversibleChargeNotice.style.display = 'none'; // Removed as per request
                }
                // Ensure reversibleChargeNotice is always hidden as per request
                if (reversibleChargeNotice) {
                    reversibleChargeNotice.style.display = 'none';
                }

                if (paymentAmount) {
                    paymentAmount.textContent = `AED ${amount}`;
                }
                if (paymentSummary) {
                    paymentSummary.style.display = 'flex';
                    console.log('[updatePaymentAmount] Payment summary displayed.');
                }

                // Update button text based on amount
                if (submitButton) {
                    if (amount === 0) {
                        submitButton.textContent = 'Register';
                    } else {
                        submitButton.textContent = 'Continue to Payment';
                    }
                }

                updateNoShowConsentVisibilityAndRequired();
            }

            // Initial state
            if (paymentAmount) {
                paymentAmount.textContent = 'AED 5999'; // Default initial display for "Guest"
            } // Yeah
            // Chapter selection event listener for updating payment amount
            chapterSelect.addEventListener('change', updatePaymentAmount);

            // Initial call to set correct state on page load
            updatePaymentAmount();

            // Form submission - Redirect to Telr Checkout
            registrationForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const selectedChapter = formData.get('chapter');
                
                // Get amount directly from the displayed payment amount
                const displayedAmountText = paymentAmount.textContent.replace('AED', '').trim();
                let amount = parseFloat(displayedAmountText);

                let chapterForSubmission = verifiedMemberType || selectedChapter;

                const currency = currentPricing.currency;
                const noShowConsentChecked = noShowConsentCheckbox ? noShowConsentCheckbox.checked : false;

                if (!selectedChapter || selectedChapter === "") { // Explicitly check for empty string
                    alert('Please select an EO Chapter/Guest before proceeding.');
                    return;
                }

                const requiredFields = ['email', 'name', 'chapter', 'phone'];
                for (const field of requiredFields) {
                    if (!formData.get(field)) {
                        alert(`Please fill in the ${field} field.`);
                        return;
                    }
                }

                if (!noShowConsentChecked) {
                    alert('Please agree to the Event Policy.');
                    return;
                }

                const originalButtonText = submitButton.textContent;
                submitButton.disabled = true;
                submitButton.textContent = 'Processing...';

                if (amount === 0) {
                    console.log('DEBUG: Free registration. Calling /api/free-registration endpoint.');
                    const freeRegistrationApiUrl = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
                        ? 'http://localhost:3001/api/free-registration'
                        : 'https://backend-production-c14ce.up.railway.app/api/free-registration';

                    const submitData = {
                        name: formData.get('name'),
                        email: formData.get('email'),
                        phone: formData.get('phone'),
                        chapter: chapterForSubmission,
                        noShowConsent: noShowConsentChecked,
                        plan: 'regular' // Default to regular
                    };

                    const urlEncodedData = new URLSearchParams(submitData).toString();

                    try {
                        const response = await fetch(freeRegistrationApiUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: urlEncodedData,
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Server error response for free registration:', errorData);
                            throw new Error(`Server error: ${errorData.error || 'Unknown error'} - Details: ${errorData.details || 'No details provided'}`);
                        }

                        const data = await response.json();
                        if (data.redirectUrl) {
                            window.location.href = data.redirectUrl;
                        } else {
                            throw new Error('No redirect URL received for free registration');
                        }
                    } catch (error) {
                        console.error('Error during free registration:', error);
                        alert(`Free registration failed: ${error.message}. Please try again or contact support.`);
                        submitButton.disabled = false;
                        submitButton.textContent = originalButtonText;
                    }
                    return; // Exit after handling free registration
                }

                try {
                    console.log('DEBUG: paymentAmount.textContent:', paymentAmount.textContent);
                    console.log('DEBUG: displayedAmountText:', displayedAmountText);
                    console.log('DEBUG: Parsed amount (before toFixed):', amount);
                    console.log('DEBUG: Amount to be sent (toFixed(2)):', amount.toFixed(2));

                    const backendApiUrl = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
                        ? 'http://localhost:3001/api/create-checkout-session'
                        : 'https://backend-production-c14ce.up.railway.app/api/create-checkout-session';

                    const submitData = {
                        amount: amount.toFixed(2),
                        currency: currency.toLowerCase(),
                        plan: 'regular', // Default to regular
                        name: formData.get('name'),
                        email: formData.get('email'),
                        phone: formData.get('phone'),
                        chapter: chapterForSubmission,
                        noShowConsent: noShowConsentChecked
                    };

                    const urlEncodedData = new URLSearchParams(submitData).toString();

                    const response = await fetch(backendApiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: urlEncodedData,
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        console.error('Server error response:', errorData);
                        throw new Error(`Server error: ${errorData.error || 'Unknown error'} - Details: ${errorData.details || 'No details provided'}`);
                    }

                    const data = await response.json();

                    if (data.redirectUrl) {
                        localStorage.setItem('telr_order_ref', data.telrRef); // Store Telr order reference
                        window.location.href = data.redirectUrl;
                    } else {
                        throw new Error('No redirect URL received from Telr');
                    }
                } catch (error) {
                    console.error('Error during registration/checkout:', error);
                    alert(`Registration failed: ${error.message}. Please try again or contact support.`);
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                }
            });

        });
    </script>
