<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Thank You</title>
    <meta content="THE WORLD'S MOST THOUGHT PROVOKING SCIENCE TECH SOCIETY CONFERENCE" name="description">
    <meta content="synapsecon clave" name="keywords">

    <!--OpenGraph meta-tags-->
    
    
    
    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="../index.html">
    <meta property="og:type" content="website">
    <meta property="og:title" content="SYNAPSE">
    <meta property="og:description" content="The world's most thought provoking science tech society conference">
     <!--<meta property="og:image" content="assets/img/SynapseIcon.png">-->
    <meta property="og:image" content="assets/img/favicon.png">


    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="synapseconclave.com">
    <meta property="twitter:url" content="../index.html">
    <meta name="twitter:title" content="SYNAPSE">
    <meta name="twitter:description" content="India's most thought provoking science tech society conference">
    <!--<meta name="twitter:image" content="assets/img/SynapseIcon.png">-->
    <meta name="twitter:image" content="assets/img/favicon.png">



    <link href="assets/css/vission.css" rel="stylesheet">
    <!-- Favicons -->
    <!--<link rel="icon" href="assets/img/SynapseIcon.png" type="image/x-icon" />-->
    <link rel="icon" href="assets/img/favicon.png" type="image/x-icon" />
    <!-- image -->
    <!--<meta property="og:image"  content="https://synapseconclave.com/dubai/assets/img/og-img.png">-->
    <!--<meta name="twitter:image" content="https://synapseconclave.com/dubai/assets/img/og-img.png">-->
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CRaleway:300,300i,400,400i,500,500i,600,600i,700,700i%7CPoppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <link href="https://db.onlinewebfonts.com/c/019787561c6d828d2329282a3b274358?family=Paralucent+W00+Light"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
<link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <!-- Template Main CSS File -->
<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/responsive.css" rel="stylesheet">
    <style>
    #header{
        border-bottom: none;
    }
    .text-holder span {
            opacity: 0;
            animation-name: textAnimationBlur;
            animation-duration: 1s;
            animation-fill-mode: forwards;
            -webkit-backface-visibility: hidden;
        }

        @keyframes  textAnimationBlur {
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
            display: flex
;
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
        .dropdown:hover .dropbtn,.dropdown:hover .dropbtn a  {
            background-color: #FFFF00;
            color: #0c1c22 !important;

        }
        
        /* AI Workshop Header Styles */
        .ai-workshop-header {
            background: #0c1c22 !important; /* opaque header */
            border-bottom: 1px solid #000; /* darker bottom border */
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
            
            .ai-workshop-title {
                text-align: center;
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
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-16840408600');
</script>





<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-YMQZ665X2Z"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-YMQZ665X2Z');
</script>



    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
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
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '470302849479612');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=470302849479612&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    
    
    
</head>

<body>
    <div id="show-search-panel">

<img class="menu-glow menu-glow1" src="assets/img/about_glow_2025.svg">
<img class="menu-glow menu-glow2" src="assets/img/about_glow_2025.svg">
<img class="menu-glow menu-glow3" src="assets/img/about_glow_2025.svg">
<img class="menu-line menu-line1" src="assets/img/menu_line.svg">
<img class="menu-line menu-line2" src="assets/img/menu_line.svg">


        <div class="fadeclosepanel">
            <div class="close-icon-srch" id="close-search"><img src="assets/img/close.svg"></div>
            <div class="container-custom d-flex align-items-center header2025">
                <div class="nav-wrap row w-100 ">
                    <div class="col-md-4 col-12">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="https://www.synapsevision.in/" target="_blank">Vision</a></li>
                            

                            <div class="dropdown">
                                <li class="dropbtn"><a href="#">Speakers</a></li>
                                <div class="dropdown-content">
                                    <li><a href="/synapse/speakers.php">2025</a></li>
                                    <li><a href="/synapse/../2024/speakers.html" target="_blank">2024</a></li>
                                </div>
                            </div>
                            
                            <div class="dropdown">
                                <li class="dropbtn"><a href="#">Program</a></li>
                                <div class="dropdown-content">
                                    <li><a href="/synapse/program.php">2025</a></li>
                                    <li><a href="/synapse/../2024/schedule.html" target="_blank">2024</a></li>
                                </div>
                            </div>
                        </ul>
                    </div>
                    <div class="col-md-4 col-12">
                        <ul>
                            <div class="dropdown">
                                <li class="dropbtn"><a href="#">Sessions</a></li>
                                <div class="dropdown-content">
                                    <li><a href="/synapse/session_recording.php">2025</a></li>
                                    <li><a href="/synapse/../2024/session_recording.html" target="_blank">2024</a></li>
                                </div>
                            </div>
                            
                            
                            <div class="dropdown">
                                <li class="dropbtn"><a href="#">Visuals</a></li>
                                <div class="dropdown-content">
                                    <li><a href="#">2025</a></li>
                                    <li><a href="/synapse/../2024/visual.html" target="_blank">2024</a></li>
                                </div>
                            </div>
                            <!--<li><a href="../india.html" target="_blank">SYNAPSE 2025</a></li>-->
                            <!--<li><a href="../2024/index.html" target="_blank">SYNAPSE 2024</a></li>-->
                             <div class="dropdown">
                                <li class="dropbtn"><a href="#">SYNAPSE</a></li>
                                <div class="dropdown-content">
                                    <li><a href="/synapse/../india.html" target="_blank">2025</a></li>
                                    <li><a href="/synapse/../2024/index.html" target="_blank">2024</a></li>
                                </div>
                            </div>
                            

                            <div class="dropdown">
                                <li class="dropbtn"><a href="#">Gallery</a></li>
                                <div class="dropdown-content">
                                    <li><a href="/synapse/gallery.php">2025</a></li>
                                    <li><a href="/synapse/../2024/gallery.html" target="_blank">2024</a></li>
                                </div>
                            </div>
                        </ul>
                    </div>
                    
                    <div class="col-md-4 col-12">
                        <ul>
                            <li><a href="/synapse/testimonials.php">Testimonials</a></li>
                            <li><a href="/synapse/thought_seeder.php">Thought Seeder</a></li>
                         
                            
                            <!--<li><a href="/synapse/sponsors.html">Sponsors</a></li>-->
                            <li><a href="/synapse/aboutus.php">About Us</a></li>
                            <li><a href="/synapse/contactus.php" id="conclick">Contact Us</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- ======= Header ======= -->
    <nav id="header" class="navbar fixed-top navbar-expand-lg d-flex flex-column ai-workshop-header">
        <div class="container-fluid ai-header-container">
            <div class="row w-100 align-items-center">
                <!-- Left Section -->
                <div class="col-lg-3 col-md-3 col-12 ai-header-left">
                    <div class="ai-workshop-title">
                        <!-- <h1 class="ai-workshop-main-title">AI & Innovation Workshop</h1> -->
                        <!-- <p class="section-subtitle text-white m-0">AI & Innovation Workshop</p> -->
                        <p class="ai-workshop-subtitle">
                            <b>
                            AI For Business
                            </b>
                            <br>
                            <b>
                                Hosted by
                            </b>
                            <img src="assets/img/logo-powered.png" alt="" class="img-fluid" width="45">
                        </p>
                    </div>
                </div>
                
                <!-- Center Section -->
                <div class="col-lg-6 col-md-6 col-12 ai-header-center">
                    <div class="ai-event-details">
                        <span class="ai-event-date">23-24 January | Marriott Palm Jumeirah</span>
        
                    </div>
                </div>
                
                <!-- Right Section -->
                <div class="col-lg-3 col-md-3 col-12 ai-header-right">
                    <div class="ai-header-buttons">
                        <a href="registration.php" target="_blank" class="ai-btn ai-btn-register">Register</a>
                        <a href="#faqs" class="ai-btn ai-btn-faq">FAQs</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="what-we-do bg2 pad-0 pos-relative mt-130 " style="display: none;">
        <div class="liner-full"><img src="assets/img/home-2shadw.png" class="img-fluid"></div>

        <div class="container-custom ">

            <div class="section-title inner-main text-center logo-w" style="padding-bottom:10px;">
                <div><img src="assets/img/shiv-home1.png" class="img-fluid"></div>

                <div style="padding:5px;"><img src="assets/img/presents.png" class="img-fluid"
                        width="60"></div>
                <div class="p-t-b-15 rohini"><img src="assets/img/Synapse-logo-home.png"
                        class="img-fluid"></div>
                <div>
                    <p class="mb-15">POWERED BY</p>
                    <img src="assets/img/glenlevit-white.png" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
    <main id="main">
        
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <!--<link href="https://db.onlinewebfonts.com/c/29b618e51713f91ccd7706cf0543a3f9?family=Transducer+Test+Regular" rel="stylesheet">-->
    <link href="https://db.onlinewebfonts.com/c/c6f2d806c4a0870eb17c9e323ce61585?family=Transducer+Test+Hairline"
        rel="stylesheet">
    <style>
    
    .textSlide{
    background: linear-gradient(42deg, rgba(123, 114, 90, 1) 0%, rgba(43, 43, 35, 1) 10%, rgba(43, 45, 35, 3) 90%, rgba(123, 114, 99, 1) 100%);
    position: relative;
    overflow: hidden;
    z-index: 3;
    min-height:100px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.marquee-with-options a {
    color: #fff;
    font-size: 24px;
}

.Textheading{
    width: 200px;
    color: #fff;
    font-size: 24px;
    padding-left: 50px;
}
    
    
    
    
        .card-body {
            height: 300px;
        }

        .pad-set-100 {
            padding-top: 100px;
            padding-bottom: 65px;
        }

        .closest {
            background-color: #ffe607 !important;
            position: absolute !important;
            z-index: 1;
            right: -10px;
            top: -20px;
            border-radius: 100% !important;
            padding: 10px !important;
            opacity: 1 !important;
            width: 1rem !important;
        }

        .cloud_words {
            display: none;
            animation: fade-in 2s forwards;
        }

        .text-left {
            text-align: left;
        }

        .gene {
            background-color: #5C5C4D;
            padding: 5px;
            color: #fff;
            font-weight: normal;
            text-transform: uppercase;
            font-size: 13px;
        }

        .gene1 {
            font-size: 19px;
            color: #000;
            font-weight: bold;
            letter-spacing: normal;
            padding-top: 1rem;
        }

        .gene2 {
            font-size: 20px;
            color: #000;
            font-weight: 500;
            letter-spacing: normal;
        }

        .liz {
            color: #AA5B05;
        }

        .swiper-container {
            position: relative;
        }

        .card11 {
            box-shadow: #5c96b299 0px 7px 29px 0px;
        }

        .swiper {
            width: 80%;
            max-width: 1280px;
        }

        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;

        }
        
        
        
        
        
        
        
    
        
        
        
        
        
        
        /*watch start*/
        
        
        .live_stream {
            background-color: #0c1c22;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            padding: 100px 0px;
            position: relative;
        }

        .watchGlow{
            max-width: 500px;
            position: absolute;
            top:10%;
            left: 5%;
        }

        .watchGlow1{
            max-width: 500px;
            position: absolute;
            top:10%;
            right: 5%;
        }


        .live_boxContent {
            /* margin: 0 auto; */
            display: flex;
            flex-direction: column;
            z-index: 5;
        }

        .live_boxContent .boxTop {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logoLive1 {
            max-width: 370px;
            width: 100%;
        }

        .logoLive2 {
            max-width: 250px;
            width: 100%;
            margin-bottom: 30px;
        }

        .boxTop h3 {
            color: #cacaca;
            font-size: 20px;
            line-height: 40px;
        }

        .live_heading {
            color: #fffb00;
            font-size: 60px;
        }

        .live-btns {
            display: flex;
            flex-direction: column;
            margin: 0 auto;
            gap: 25px;
            margin-top: 30px;
        }

        #btns {
            padding: 5px 10px;
            font-size: 25px;
            border: 1px dotted yellow;
            background: transparent;
            color: white;
            line-height: 28px;
        }

        #watch {
            font-size: 18px;
            padding: 10px 30px;
            background: yellow;
            color: black;
            border: 1px solid transparent;
            transition: all 0.3sease-in-out;
        }

        .watchBody,
        .modal-content {
            padding: 0 !important;
        }

        .live_stream .close_btn {
            position: absolute;
            top: 0;
            right: 0;
            /* background: #ffff00; */
            /* color: #000; */
            padding: 5px;
            width: 20px;
            height: 20px;
        }


        @media (max-width: 768px) {
            .logoLive1 {
                max-width: 290px;
            }

            .logoLive2 {
                max-width: 200px;
            }

            .live_heading {
                font-size: 33px;

            }
            .main-vdo{
                margin-top: 100px;
            }
            .speaker_reg_btn_header{
                padding: 5px 8px;
            }
            .logo-box{
                width: 100% !important;
                display: flex;
                justify-content: space-between;
            }
        }
        
        /*Watch end*/
        
        
    
        
        .testimonialSection {
            background: #0c1c22;
            padding: 50px 0;
            position: relative;
            overflow: hidden;
            display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  background: #0c1c22; 
        }
        
        .testimonialSection a{
           z-index:5; 
        }
        
        .testi_glow{
            position: absolute;
            right: 0;
            max-width: 400px;
        }
         .testi_glow1{
            position: absolute;
            left: 10%;
            bottom: -115px;
            max-width: 300px;
        }

        .testimonialSlider {
            width: 100%;
            max-width: 1200px;
            padding: 20px 0;
        }

        .testimonialSection .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .testimonial_innerBox {
            /* Your existing styles */
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            padding:0 30px;
        }

        .testimonial_innerBox h3 {
            color: #ffff00;
            font-size: 20px;
        }

        .testimonial_innerBox h4 {
            color: #fff;
            font-size: 18px;
        }
        
         .testimonial_innerBox h4 span{
             display:none;
         }

        .testimonial_innerBox p {
            font-size: 22px;
            color: #fff;
            font-family: "Transducer Test Hairline";
            font-weight: 600;
        }

        .testimonialSection .swiper-button-next,
        .testimonialSection .swiper-button-prev {
            color: #ffff00;
            font-size: 30px;
        }

        .testimonialSection .swiper-pagination-bullet-active {
            background: #ffff00;
        }
        
        
        @media (max-width: 768px) {
            .testimonial_innerBox h4 {
            font-size: 14px;
        }
        .testimonial_innerBox h3 {
            color: #ffff00;
            font-size: 16px;
        }
        .testimonial_innerBox p {
            font-size: 18px;
        }
        }
        
        
        
        
        
    </style>
