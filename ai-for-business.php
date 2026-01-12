<?php include_once("header-ai-for-business.php"); ?>

<style>
    #main{
        padding-top: 0;
    }
    /* AI Business Section Background */
    .ai-business-section {
        background-image: url('assets/img/ai-forbusiness-s2.png');
        /* background-size: cover; */
        background-position: center;
        background-repeat: no-repeat;
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }

    .highlights-list {
        color: white;
        font-size: 1.1rem;
        line-height: 1.8;
    }

    /* Reusable Section Styles */
    .section-bg-image {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        position: relative;
        display: flex;
        align-items: center;
    }

    .section-content {
        color: white;
        z-index: 2;
        position: relative;
    }

    .section-title {
        font-size: 4.5rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        line-height: 1.1;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.6), 0 0 20px rgba(255, 255, 255, 0.6);
    }

    .section-title1 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        line-height: 1.1;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #fff;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.6), 0 0 20px rgba(255, 255, 255, 0.6);
    }

    .section-title span {
        color: #ffff00;
        text-shadow: 0 0 20px rgba(255, 255, 0, 0.5);
    }

    .section-subtitle {
        font-size: 1.4rem;
        margin-bottom: 3rem;
        opacity: 0.95;
        font-weight: 300;
        line-height: 1.4;
        max-width: 450px;
    }

    .section-buttons {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: white;
        color: #333;
        border: none;
        padding: 18px 36px;
        font-weight: 700;
        border-radius: 50px;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .btn-highlight {
        background: linear-gradient(145deg, #1a1a2e, #16213e);
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        display: inline-block;
        font-weight: 700;
        font-size: 1.1rem;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 2px solid #00b4d8;
        box-shadow:
            0 0 20px rgba(0, 180, 216, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .btn-highlight::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 180, 216, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-highlight:hover {
        transform: translateY(-2px);
        box-shadow:
            0 0 30px rgba(0, 180, 216, 0.6),
            0 4px 15px rgba(0, 0, 0, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        border-color: #00d4ff;
    }

    .btn-highlight:hover::before {
        left: 100%;
    }

    .section-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 1;
    }

    .divider-line {
        height: 2px;
        background: linear-gradient(90deg, #00b4d8, transparent);
        margin: 2rem 0;
    }

    .info-section {
        /* margin-bottom: 2rem; */
    }

    .info-section h5 {
        font-weight: 500;
        font-size: 28px;
    }

    .info-title {
        font-size: 1.1rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .info-subtitle {
        font-size: 1.3rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .info-text {
        font-size: 1.1rem;
        color: #d9e2e6;
    }

    .program-title {
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .program-title span {
        /* color: #ffff00; */
        font-weight: 600;
    }

    .program-subtitle {
        font-size: 16px;
        color: #d9e2e6;
    }

    /* Speaker Profiles Section */
    .speaker-profiles-section {
        background-image: url('assets/img/ai-forbusiness-s34.png');
        /* background-size: cover; */
        background-position: center;
        background-repeat: no-repeat;
        /* padding: 4rem 0; */
        position: relative;
        overflow: hidden;
    }

    .speaker-card {
        perspective: 1000px;
        height: 400px;
        margin-bottom: 2rem;
    }

    .speaker-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.8s;
        transform-style: preserve-3d;
        cursor: pointer;
    }

    .speaker-card:hover .speaker-card-inner {
        transform: rotateY(180deg);
    }

    .speaker-card-front,
    .speaker-card-back {
        position: absolute;
        width: 100%;
        /* height: 10.speaker-card-front0%; */
        backface-visibility: hidden;
        border-radius: 15px;
        overflow: hidden;
        /* box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); */
    }

    .speaker-card-front {
        /* background: linear-gradient(145deg, #ffffff, #f8f9fa); */
    }

    .speaker-card-back {
        background: linear-gradient(145deg, #1a1a2e, #16213e);
        transform: rotateY(180deg);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2rem;

        height: 100%;
    }

    .speaker-image {
        /* width: 100%; */
        /* height: 250px; */
        height: 350px;
        object-fit: cover;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
    }

    .speaker-info {
        padding: 1.5rem;
        /* background: linear-gradient(145deg, #1a1a2e, #16213e); */
        color: white;
        height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .speaker-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
    }

    .speaker-title {
        font-size: 1rem;
        color: #d9e2e6;
        line-height: 1.4;
    }

    .speaker-back-content {
        text-align: center;
    }

    .speaker-back-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #ffff00;
    }

    .speaker-back-description {
        font-size: 0.9rem;
        line-height: 1.6;
        color: #d9e2e6;
        margin-bottom: 1rem;
    }

    .speaker-back-highlight {
        font-size: 0.8rem;
        color: #00b4d8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* FAQ Section Styles */
    .faq-section {
        /* background: #0c1c22; */
        padding: 60px 0;
        position: relative;
        overflow: hidden;
    }

    .faq-section .Ellipsenewright {
        position: absolute;
        right: -10%;
        top: -20%;
        max-width: 500px;
        opacity: 0.3;
    }

    .faq-section .section-title1 {
        color: #fff;
    }

    .faq-section .accordion-item {
        background: transparent;
        border: none;
        margin-top: 10px;
    }

    .faq-section .accordion-item:first-child {
        margin-top: 0;
    }

    .faq-section .accordion-button {
        background: linear-gradient(145deg, #1a1a2e, #16213e);
        color: #fff;
        position: relative;
        padding: 1rem 2rem;
        border-radius: 50px;
        border: 2px solid #00b4d8;
        box-shadow:
            0 0 20px rgba(0, 180, 216, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .faq-section .accordion-button:hover {
        transform: translateY(-2px);
        box-shadow:
            0 0 30px rgba(0, 180, 216, 0.6),
            0 4px 15px rgba(0, 0, 0, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        border-color: #00d4ff;
    }

    .faq-section .accordion-button:not(.collapsed) {
        background: linear-gradient(145deg, #1a1a2e, #16213e);
        border-color: #00d4ff;
        box-shadow:
            0 0 30px rgba(0, 180, 216, 0.6),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    .faq-section .accordion-button::after {
        background-image: none !important;
        content: "\f078";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: #ffff00;
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        transition: transform .2s ease;
    }

    .faq-section .accordion-button:not(.collapsed)::after {
        content: "\f077";
    }

    .faq-section .accordion-body {
        color: #d9e2e6;
    }

    .faq-section .accordion .accordion-item {
        background: transparent;
        border: none;
        margin-top: 8px;
    }

    .faq-section .accordion .accordion-button {
        background: linear-gradient(145deg, #1a1a2e, #16213e);
        color: #fff;
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        border: 2px solid #00b4d8;
        box-shadow:
            0 0 15px rgba(0, 180, 216, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .faq-section .accordion .accordion-button:hover {
        transform: translateY(-1px);
        box-shadow:
            0 0 25px rgba(0, 180, 216, 0.5),
            0 2px 10px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        border-color: #00d4ff;
    }

    .faq-section .accordion .accordion-button:not(.collapsed) {
        background: linear-gradient(145deg, #1a1a2e, #16213e);
        border-color: #00d4ff;
        box-shadow:
            0 0 25px rgba(0, 180, 216, 0.5),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    .accordion-item:last-of-type .accordion-button.collapsed{
        border-bottom-right-radius: 50px;
        border-bottom-left-radius: 50px;
    }
    @media (max-width: 768px) {
        .Humanstechsub {
            margin: 0;
        }

        .Textheading {
            width: 250px !important;
        }

        .section-title {
            font-size: 2.8rem !important;
            letter-spacing: 1px !important;
        }

        .section-subtitle {
            font-size: 1.1rem !important;
            margin-bottom: 2rem !important;
        }

        .section-buttons {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem !important;
        }

        .section-buttons .btn {
            width: 100%;
            max-width: 280px;
            margin-bottom: 0.5rem;
            padding: 16px 32px !important;
            font-size: 0.95rem !important;
        }

        .highlights-section .col-lg-3 {
            margin-bottom: 1rem;
        }

        .highlights-section .col-lg-9 div {
            font-size: 0.9rem !important;
        }

        .highlights-list {
            font-size: 1rem !important;
            line-height: 1.6 !important;
        }

        .btn-highlight {
            margin-bottom: 1.5rem !important;
            padding: 0.8rem 1.5rem !important;
        }

        /* Speaker Cards Mobile */
        .speaker-card {
            height: 350px !important;
            margin-bottom: 1.5rem !important;
        }

        .speaker-image {
            height: 200px !important;
        }

        .speaker-info {
            height: 150px !important;
            padding: 1rem !important;
        }

        .speaker-name {
            font-size: 1.1rem !important;
        }

        .speaker-title {
            font-size: 0.9rem !important;
        }

        .speaker-card-back {
            padding: 1.5rem !important;
        }

        .speaker-back-title {
            font-size: 1rem !important;
            margin-bottom: 0.8rem !important;
        }

        .speaker-back-description {
            font-size: 0.8rem !important;
            margin-bottom: 0.8rem !important;
        }

        .speaker-back-highlight {
            font-size: 0.7rem !important;
        }

        /* FAQ Mobile Styles */
        .faq-section .accordion-button {
            padding: 0.8rem 1.5rem !important;
            font-size: 0.9rem !important;
            letter-spacing: 0.5px !important;
        }

        .faq-section .accordion .accordion-button {
            padding: 0.6rem 1.2rem !important;
            font-size: 0.8rem !important;
            letter-spacing: 0.3px !important;
        }
    }
</style>
<!-- ======= Hero Section ======= -->
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
                    <div class="section-buttons">
                        <button class="btn btn-primary text-uppercase" data-bs-toggle="modal"
                            data-bs-target="#exampleModal1">
                            Agenda
                        </button>
                        <a href="register.php" target="_blank" class="btn btn-primary text-uppercase">
                            Register
                        </a>
                            </div>
                        </div>
                    </div>
            <div class="col-lg-6">

                </div>
            </div>
        </div>
    <!-- Overlay for better text readability -->
    <div class="section-overlay"></div>
    </div><!-- End Hero -->


    <section class="section_3" style="display:none;">
        <h3 class="rusja" data-aos="fade-right" data-aos-duration="500">World&rsquo;s most <span
                    style="color: #ffff00;">thought provoking</span></h3>
    <h3 class="rusja" data-aos="fade-right" data-aos-duration="500">science tech society conference</h3>
    </section>


    <section id="what-we-do" class="what-we-do bg1">

    <div class="left1-OliveGlow"><img src="assets/img/OliveGlow.svg" class="img-fluid" style="width:100%"></div>
    <div class="left2-OliveGlow"><img src="assets/img/OliveGlow.svg" class="img-fluid" style="width:100%"></div>

        <div class="liner-full"><img src="assets/img/banner-bg.png" class="img-fluid"></div>
        <div class="container-custom" style="display:none;">
            <div class="section-title inner-main">
                <img src="assets/img/grayball.png" class="img-fluid"
                     style="position: absolute;z-index: 999;left: -180px;width: 490px;top: -60px;">

                <div class="row">
                    <div class="col-lg-4 col-4 text-left">

                    <img src="upload/site/171569517048.gif" class="speakkerss" />
                    </div>
                    <div class="col-lg-8 rumebs col-8">
                        <span class="ml-5">Meet Thinkers | Doers | Boundary Shifters </span>
                    </div>
                </div>
                <img src="assets/img/grayball.png" class="img-fluid"
                     style="position: absolute;z-index: 999;right: -180px;width: 490px;top: -60px;">
            </div>
        </div>
    </section>


<!-- AI for Business Section 2 -->
<section class="ai-business-section">
    <div class="container">
        <div class="row align-items-center mb-5 pb-5">
            <div class="col-lg-3">
                <div class="btn-highlight">
                    Highlights
            </div>
                    </div>
            <div class="col-lg-9">
                <div class="swiper highlights-swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="highlights-list">Future Trends & Exponential Thinking</div>
                        </div>
                        <div class="swiper-slide">
                            <div class="highlights-list">Build Your AI Canvas v1</div>
                        </div>
                        <div class="swiper-slide">
                            <div class="highlights-list">Prompt-Engineering Bootcamp</div>
                        </div>
                        <div class="swiper-slide">
                            <div class="highlights-list">Agentic AI & Workflow Automation</div>
                        </div>
                        <div class="swiper-slide">
                            <div class="highlights-list">Data Readiness Clinic</div>
                        </div>
                        <div class="swiper-slide">
                            <div class="highlights-list">Rapid-Prototype Lab</div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <!-- Left Column - Highlights -->
            <div class="col-lg-6"></div>

            <!-- Right Column - Event Details -->
            <div class="col-lg-6">
                <div class="section-content">
                    <h2 class="section-title">
                        AI FOR BUSINESS
                    </h2>

                    <p class="section-subtitle">
                        Unlock the power of AI for your business with Singularity Faculty
                    </p>

                    <div class="divider-line"></div>

            <div class="row">
                        <div class="col-lg-4">
                            <div class="info-section">
                                <h5 class="info-title">
                                    HOSTED <br>
                                    <b>BY EO DUBAI</b>
                                </h5>
                            </div>
                            </div>

                        <div class="col-lg-8">
                            <div class="info-section">
                                <div class="program-title">
                                    <span>AI Strategy to Prototype:</span> <br> Build Your 90-Day Pilot in 2 Days
                            </div>
                                <div class="program-subtitle">
                                    A Hands-On Journey for Business Leaders
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="divider-line"></div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="info-section">
                                <h5>
                                    <b>
                                        23-24 JANUARY
                                    </b>
                                </h5>
                                <div class="info-text">
                                    Marriott Palm Jumeirah
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="info-section">
                                <h5 style="font-weight: 500; font-size: 22px;">
                                    <b>Registration Fee*: AED 5,999</b>
                                </h5>
                                <div class="info-text" style="font-size: 14px; line-height: 1.5;">
                                    *Discounts applicable for EO Dubai Members, Spouses, Accelerators, Next Gen Members, and Key Executives upon email validation.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-buttons mt-5">
                        <a href="register.php" target="_blank" class="btn btn-primary text-uppercase">
                            Register
                        </a>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
</section>

<!-- Speaker Profiles Section -->
<section class="speaker-profiles-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 mt-4">
                <h2 class="section-title1">Speaker Profiles</h2>
                            </div>
                            </div>
        <div class="row">
            <!-- Speaker 1 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="speaker-card">
                    <div class="speaker-card-inner">
                        <div class="speaker-card-front">
                            <div class="speaker-image" style="background-image: url('assets/img/speaker1.png');"></div>
                            <div class="speaker-info">
                                <div class="speaker-name">Dr. Adam Pantanowitz</div>
                                <div class="speaker-title">Expert at Singularity University</div>
                        </div>
                    </div>
                        <div class="speaker-card-back">
                            <div class="speaker-back-content">
                                <div class="speaker-back-title">Dr. Adam Pantanowitz</div>
                                <div class="speaker-back-description">
                                    Biomedical engineer, AI strategist, and innovation expert. Co-founder of AURA, dsrupt.ai, and PRTL Medical. Creator of “Brainternet,” the world’s first brain-to-internet connection. Global Fellow of the IET (UK). Specialist in AI implementation, healthcare innovation, and digital transformation.
                </div>
                                <div class="speaker-back-highlight">Keynote Speaker</div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Speaker 2 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="speaker-card">
                    <div class="speaker-card-inner">
                        <div class="speaker-card-front">
                            <div class="speaker-image" style="background-image: url('assets/img/speaker2.png');"></div>
                            <div class="speaker-info">
                                <div class="speaker-name">Prof. Benjamin Rosman</div>
                                <div class="speaker-title">TIME 100 Most Influential in AI 2025</div>
                            </div>
                            </div>
                        <div class="speaker-card-back">
                            <div class="speaker-back-content">
                                <div class="speaker-back-title">Prof. Benjamin Rosman</div>
                                <div class="speaker-back-description">
                                    Professor of Computer Science at the University of the Witwatersrand and Director of the MIND Institute. Founder of Lelapa AI and Deep Learning Indaba. Named among TIME 100 Most Influential in AI (2025). Expert in reinforcement learning and autonomous systems.
                        </div>
                                <div class="speaker-back-highlight">Research Expert</div>
                    </div>
                </div>
                            </div>
                            </div>
                        </div>

            <!-- Speaker 3 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="speaker-card">
                    <div class="speaker-card-inner">
                        <div class="speaker-card-front">
                            <div class="speaker-image" style="background-image: url('assets/img/speaker3.png');"></div>
                            <div class="speaker-info">
                                <div class="speaker-name">Laila Pawlak</div>
                                <div class="speaker-title">Founder & CEO, Rehumanize Institute</div>
                            </div>
                            </div>
                        <div class="speaker-card-back">
                            <div class="speaker-back-content">
                                <div class="speaker-back-title">Laila Pawlak</div>
                                <div class="speaker-back-description">
                                    Founder & CEO of Rehumanize Institute and former CEO of SingularityU Nordic. Amazon #1 bestselling author and UN Fellow. Twice nominated Female Entrepreneur of the Year (Denmark). Global speaker on impact leadership, exponential technologies, and the future of work.
                        </div>
                                <div class="speaker-back-highlight">Ethics Leader</div>
                    </div>
                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

    <section id="faqs" class="faq-section">
        <img class="Ellipsenewright desktop" src="assets/img/Glow_Synapse_2025.svg" />
        <h2 class="section-title1 text-center mt-5">FAQs</h2>
        <div class="container-custom">

            <div class="accordion" id="faqAccordionOuter">
                <!-- Item 1 (with nested) -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            General Event Information
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#faqAccordionOuter">
                        <div class="accordion-body">
                            <div class="accordion mt-3" id="faqAccordionInner1">
                                <!-- Nested item 1.1 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading1_1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse1_1"
                                            aria-expanded="false" aria-controls="collapse1_1">
                                            1. What is the EO Dubai AI-Focused Implementation Program?
                                        </button>
                                    </h2>
                                    <div id="collapse1_1" class="accordion-collapse collapse"
                                        aria-labelledby="heading1_1" data-bs-parent="#faqAccordionInner1">
                                        <div class="accordion-body">
                                            This is a 2-day immersive workshop designed to move EO members from AI strategy to hands-on implementation. Over two days, participants will explore frameworks, tools, and real-world use cases, then build AI prototypes and 90-day pilot plans they can take back to their companies. The program is inspired by Singularity University-style experiential learning and tailored for EO entrepreneurs.
                            </div>
                            </div>
                        </div>
                                <!-- Nested item 1.2 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading1_2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse1_2"
                                            aria-expanded="false" aria-controls="collapse1_2">
                                            2. Who can attend the event?
                                        </button>
                                    </h2>
                                    <div id="collapse1_2" class="accordion-collapse collapse"
                                        aria-labelledby="heading1_2" data-bs-parent="#faqAccordionInner1">
                                        <div class="accordion-body">
                                            The program is open to EO Dubai members, SLPs, NextGen members, EOA, Key Executives, and EO members from other chapters. Each session is designed for business leaders and teams who want to integrate AI practically into their business models.
                    </div>
                </div>
                            </div>
                                <!-- Nested item 1.3 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading1_3">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse1_3"
                                            aria-expanded="false" aria-controls="collapse1_3">
                                            3. Is the program free for EO Dubai members?
                                        </button>
                                    </h2>
                                    <div id="collapse1_3" class="accordion-collapse collapse"
                                        aria-labelledby="heading1_3" data-bs-parent="#faqAccordionInner1">
                                        <div class="accordion-body">
                                            Yes. EO Dubai members and SLPs can attend at no cost.
                            </div>
                        </div>
                    </div>
                                <!-- Nested item 1.4 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading1_4">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse1_4"
                                            aria-expanded="false" aria-controls="collapse1_4">
                                            4. What is the participation rate for other EO members?
                                        </button>
                                    </h2>
                                    <div id="collapse1_4" class="accordion-collapse collapse"
                                        aria-labelledby="heading1_4" data-bs-parent="#faqAccordionInner1">
                                        <div class="accordion-body">
                                            The participation fee is AED 1,500 per person for other EO attendees.
                </div>
                            </div>
                            </div>
                                <!-- Nested item 1.5 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading1_5">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse1_5"
                                            aria-expanded="false" aria-controls="collapse1_5">
                                            5. How does the group discount work for teams of five or more EO members from outside Dubai?
                                        </button>
                                    </h2>
                                    <div id="collapse1_5" class="accordion-collapse collapse"
                                        aria-labelledby="heading1_5" data-bs-parent="#faqAccordionInner1">
                                        <div class="accordion-body">
                                            Groups of five or more EO members attending together from other chapters will receive a group discount and will pay only $1000 per attendee. The exact discounted rate will be shared at registration.
                        </div>
                    </div>
                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                <!-- Item 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Registration and Payment
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                        data-bs-parent="#faqAccordionOuter">
                        <div class="accordion-body">
                            <div class="accordion mt-3" id="faqAccordionInner2">
                                <!-- Nested item 2.1 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading2_1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse2_1"
                                            aria-expanded="false" aria-controls="collapse2_1">
                                            6. How do I register for the event?
                                        </button>
                                    </h2>
                                    <div id="collapse2_1" class="accordion-collapse collapse"
                                        aria-labelledby="heading2_1" data-bs-parent="#faqAccordionInner2">
                                        <div class="accordion-body">
                                            Registration can be completed through the EO Dubai events portal or link shared via email. Spaces are limited to 120 participants, so early registration is encouraged.
                            </div>
                            </div>
                        </div>
                                <!-- Nested item 2.2 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading2_2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse2_2"
                                            aria-expanded="false" aria-controls="collapse2_2">
                                            7. Are there any discounts for group registrations?
                                        </button>
                                    </h2>
                                    <div id="collapse2_2" class="accordion-collapse collapse"
                                        aria-labelledby="heading2_2" data-bs-parent="#faqAccordionInner2">
                                        <div class="accordion-body">
                                            Yes. Groups of five or more EO members from outside Dubai receive a special discounted rate.
                    </div>
                </div>
                            </div>
                                <!-- Nested item 2.3 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading2_3">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse2_3"
                                            aria-expanded="false" aria-controls="collapse2_3">
                                            8. What forms of payment are accepted?
                                        </button>
                                    </h2>
                                    <div id="collapse2_3" class="accordion-collapse collapse"
                                        aria-labelledby="heading2_3" data-bs-parent="#faqAccordionInner2">
                                        <div class="accordion-body">
                                            Payments can be made via credit card while completing the registration form.
                            </div>
                        </div>
                    </div>
                                <!-- Nested item 2.4 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading2_4">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse2_4"
                                            aria-expanded="false" aria-controls="collapse2_4">
                                            9. Can non-members attend the event, and what is the process for approval?
                                        </button>
                                    </h2>
                                    <div id="collapse2_4" class="accordion-collapse collapse"
                                        aria-labelledby="heading2_4" data-bs-parent="#faqAccordionInner2">
                                        <div class="accordion-body">
                                            Non-members may apply to attend if nominated/booked by an EO member.
                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                            </div>

                <!-- Item 3 (with nested) -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Program and Agenda
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                        data-bs-parent="#faqAccordionOuter">
                        <div class="accordion-body">
                            <div class="accordion mt-3" id="faqAccordionInner3">
                                <!-- Nested item 3.1 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading3_1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse3_1"
                                            aria-expanded="false" aria-controls="collapse3_1">
                                            10. What are the key agenda items for the two-day program?
                                        </button>
                                    </h2>
                                    <div id="collapse3_1" class="accordion-collapse collapse"
                                        aria-labelledby="heading3_1" data-bs-parent="#faqAccordionInner3">
                                        <div class="accordion-body">
                                            <ul style="margin:0; padding-left:1rem;">
                                                <li><strong>Day 1: AI Foundations & Opportunity Mapping</strong> – Frameworks, readiness assessment, sector case studies, and building your first AI Canvas.</li>
                                                <li><strong>Day 2: Tools, Prototyping & Pilot Commitments</strong> – Prompt engineering, workflow automation, data readiness clinics, rapid prototyping, and 90-day pilot presentations.</li>
                                            </ul>
                            </div>
                            </div>
                        </div>
                                <!-- Nested item 3.2 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading3_2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse3_2"
                                            aria-expanded="false" aria-controls="collapse3_2">
                                            11. Will there be hands-on workshops and opportunities for prototype building?
                                        </button>
                                    </h2>
                                    <div id="collapse3_2" class="accordion-collapse collapse"
                                        aria-labelledby="heading3_2" data-bs-parent="#faqAccordionInner3">
                                        <div class="accordion-body">
                                            Absolutely. Both days are designed around "show-then-do" learning. Participants will build live prototypes, AI workflows, and pilot plans specific to their businesses.
                    </div>
                </div>
                            </div>
                                <!-- Nested item 3.3 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading3_3">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse3_3"
                                            aria-expanded="false" aria-controls="collapse3_3">
                                            12. What kind of topics will the keynote and workshops cover?
                                        </button>
                                    </h2>
                                    <div id="collapse3_3" class="accordion-collapse collapse"
                                        aria-labelledby="heading3_3" data-bs-parent="#faqAccordionInner3">
                                        <div class="accordion-body">
                                            <ul style="margin:0; padding-left:1rem;">
                                                <li>AI 2025–2027 for Mid-Market Firms</li>
                                                <li>Data, Talent & Process Readiness</li>
                                                <li>Prompt Engineering Bootcamp</li>
                                                <li>Agentic AI and Workflow Automation</li>
                                                <li>Low-/No-Code Prototyping Labs</li>
                                                <li>90-Day AI Pilot Commitments</li>
                                            </ul>
                            </div>
                        </div>
                    </div>
                                <!-- Nested item 3.4 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading3_4">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse3_4"
                                            aria-expanded="false" aria-controls="collapse3_4">
                                            13. Are meals and coffee breaks included in the program?
                                        </button>
                                    </h2>
                                    <div id="collapse3_4" class="accordion-collapse collapse"
                                        aria-labelledby="heading3_4" data-bs-parent="#faqAccordionInner3">
                                        <div class="accordion-body">
                                            Yes. The event includes networking lunches, coffee breaks, and refreshments both days.
                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item 4 (with nested) -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Learning and Networking
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                        data-bs-parent="#faqAccordionOuter">
                        <div class="accordion-body">
                            <div class="accordion mt-3" id="faqAccordionInner4">
                                <!-- Nested item 4.1 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading4_1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse4_1"
                                            aria-expanded="false" aria-controls="collapse4_1">
                                            14. Will there be any pre-event learning provided?
                                        </button>
                                    </h2>
                                    <div id="collapse4_1" class="accordion-collapse collapse"
                                        aria-labelledby="heading4_1" data-bs-parent="#faqAccordionInner4">
                                        <div class="accordion-body">
                                            Yes. Participants will receive a pre-event learning pack three weeks before the program.
                    </div>
                </div>
            </div>
                                <!-- Nested item 4.2 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading4_2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse4_2"
                                            aria-expanded="false" aria-controls="collapse4_2">
                                            15. What resources are sent to participants before the event?
                                        </button>
                                    </h2>
                                    <div id="collapse4_2" class="accordion-collapse collapse"
                                        aria-labelledby="heading4_2" data-bs-parent="#faqAccordionInner4">
                                        <div class="accordion-body">
                                            <ul style="margin:0; padding-left:1rem;">
                                                <li>An AI-Maturity Self-Assessment (10-minute survey)</li>
                                                <li>A 60-minute Forum Mini-Sprint on the AI Canvas</li>
                                                <li>A Reading Pack (McKinsey State of AI Digest + WEF SME Toolkit Extract)</li>
                                            </ul>
        </div>
    </div>
                    </div>
                                <!-- Nested item 4.3 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading4_3">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse4_3"
                                            aria-expanded="false" aria-controls="collapse4_3">
                                            16. Are there follow-up clinics or support after the event finishes?
                                        </button>
                                    </h2>
                                    <div id="collapse4_3" class="accordion-collapse collapse"
                                        aria-labelledby="heading4_3" data-bs-parent="#faqAccordionInner4">
                                        <div class="accordion-body">
                                            Yes. EO Dubai will host a 30-Day Virtual Progress Clinic and Quarterly Demo Days to help participants implement and showcase their AI pilots.
                    </div>
                </div>
                </div>
                                <!-- Nested item 4.4 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading4_4">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse4_4"
                                            aria-expanded="false" aria-controls="collapse4_4">
                                            17. How does the networking and peer learning structure work?
                                        </button>
                                    </h2>
                                    <div id="collapse4_4" class="accordion-collapse collapse"
                                        aria-labelledby="heading4_4" data-bs-parent="#faqAccordionInner4">
                                        <div class="accordion-body">
                                            Participants will collaborate in small peer groups throughout both days, with shared canvases, prototype sessions, and peer feedback pitches. A new EO AI Circles network will also be launched for continued accountability and collaboration.
            </div>
        </div>
        </div>
    </div>
                        </div>
                        </div>
                    </div>

                <!-- Item 5 (with nested) -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Logistics
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                        data-bs-parent="#faqAccordionOuter">
                        <div class="accordion-body">
                            <div class="accordion mt-3" id="faqAccordionInner5">
                                <!-- Nested item 5.1 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading5_1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse5_1"
                                            aria-expanded="false" aria-controls="collapse5_1">
                                            18. What is the event location?
                                        </button>
                                    </h2>
                                    <div id="collapse5_1" class="accordion-collapse collapse"
                                        aria-labelledby="heading5_1" data-bs-parent="#faqAccordionInner5">
                                        <div class="accordion-body">
                                            The program will be held at Marriott The Palm, Dubai.
                </div>
                </div>
            </div>
                                <!-- Nested item 5.2 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading5_2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse5_2"
                                            aria-expanded="false" aria-controls="collapse5_2">
                                            19. Is parking or accommodation provided for attendees?
                                        </button>
                                    </h2>
                                    <div id="collapse5_2" class="accordion-collapse collapse"
                                        aria-labelledby="heading5_2" data-bs-parent="#faqAccordionInner5">
                                        <div class="accordion-body">
                                            Valet parking will be available for all participants. Accommodation is not included but can be booked directly with Marriott at a preferred EO rate (details to follow).
        </div>
        </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </div>
            </div>
    </section>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background-image: url(assets/img/whyattend.svg);background-size: cover;background-position: center;background-repeat: no-repeat;background-color: #0b1419;    padding: 20px;
color: #fff;">
                <!-- <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div> -->
                <div class="close_btn text-right" style="padding-right: 3rem;"><span data-bs-dismiss="modal"
                    role="button"><img src="assets/img/cross2025.svg" /></span></div>
                <div class="modal-body">
                    <h2>
                        <div class="line"></div>
                        WHY ATTEND
                    </h2>
                    <p>We are living in a time of exponential change.</p>
                    <p>From geopolitics to genes. Business to behavior. Politics to parenting. Terror. War. Energy.
                        Entertainment. Economics. Cosmos to our tiniest cells. From birth to death itself —
                        we're being changed while we sleep.</p>
                    <p>The connective thread is science & technology.</p>
                    <p>Al. Gene editing. Quantum computing. Age reversal.
                        Big Data. Big Tech.</p>
                    <p>SYNAPSE is a door to the most fascinating & urgent questions of our time. We cannot afford not to
                        understand. engage. celebrate. examine. debate.</p>

                    <p>SYNAPSE will place science and tech centre square in our lives. Probe its promise & peril. Bring
                        together the most unusual combination of minds.</p>
                    <p>
                        Technologists. Scientists. Entrepreneurs. Political leaders.
                        Artists. Writers. Directors. Philosophers. Ethicists. Economists.
                        It will not just present the cutting-edge.
                    </p>
                    <p>It will connect you to it.<br>
                        This is science & tech as never before.<br>
                        Intersected with society.
                    </p>

                    <p>2 days. 40 speakers.<br>
                        Debate. Dialogue. Discovery.<br>
                        Concerts. Cocktails. Gala dinners.
                    </p>
                    <p>A weekend of life-altering perspective.</p>

                    <!--<span id="myBtn" class="btn btn-warning rounded-0 text-uppercase "><a-->
                    <!--        href="https://synapseconclave.com/dubai/register" class="text-black"> Register Now</a></span>-->
                </div>


            </div>
        </div>
    </div>


    <!-- Modal 2-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background-image: url(assets/img/whyattend.svg);background-size: cover;background-position: center;background-repeat: no-repeat;background-color: #0b1419;    padding: 20px;
     color: #fff;">
                <!-- <div class="modal-header">
                                                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div> -->
                <div class="close_btn text-right"><span data-bs-dismiss="modal" role="button">X</span></div>
                <div class="modal-body">
                    <h2 class="mb-20">
                        <div class="line"></div>
                        WHAT TO EXPECT
                    </h2>

                    <p><b>A weekend of adjectives. Exciting. Jaw dropping. Mind blowing. Thought provoking.</b></p>

                    <p>A Nobel Laureate whose Eureka moment enabled the vaccine revolution.</p>
                    <p>
                        A master actor whose body of work testifies to what machines can't do. Yet.
                    </p>
                    <p>A biohacker putting her own body to the test, to turn back the biological clock.</p>
                    <p>Path-breaking pioneers working at the edge of
                        science and fiction. Technology and policy.
                        Algorithms and philosophy.</b>
                    </p>
                    <p>Personal stories. Bodies of work.
                        Feats and failures.
                        Arguments and analysis.</p>
                    <p>On automation anxiety. Bias of bytes. Code of life.
                        The future of democracy, globalisation, and human civilisation.
                        New weapons of war and springboards for social good.</p>
                    <p><b>
                            Meet a mesmeric world of dreamers and doers.
                            The intersection of Nature. Humans.Technology.Conversation. Concerts. Cocktails.
                        </b>
                    </p>
                    <p><b>If you're curious – or concerned –
                            about the forces shaping the world</b></p>

                    <!--<span id="myBtn" class="btn btn-warning rounded-0 text-uppercase "><a-->
                    <!--        href="https://synapseconclave.com/dubai/register" class="text-black"> Register Now</a></span>-->
                </div>


            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="exampleModalvision" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <button type="button" class="btn-close closest" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body" style="padding: 0;">
                    <div class="card card11" style="border: 0;border-radius: 0;box-shadow: unset;">
                        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalvision">
                            <div class="videoCoverImage">
                                <div
                                        onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
                                    <img class="thumb" style="cursor: pointer; width:100%;" id="modal_img"
                                         src="assets/img/film.png">
                                </div>
                                <div id="thevideo" style="display: none;">
                                    <iframe width="100%" height="624px" id="iframe_src_link"
                                            src="https://www.youtube.com/embed/oNPTWlU4u4g?rel=0;&amp;autoplay=1&amp;mute=1&amp;loop=1"
                                            frameborder="0" allowfullscreen="" include=""></iframe>
                                </div>
                            </div>

                        </a>
                        <div class="card-body" style="    height: auto;">
                            <h5 class="card-title"><span class="gene" id="modal_title"></span></h5>
                            <p class="card-text gene1" id="modal_heading"></p>
                            <p class="card-text gene2"><small class="text-muted" id="modal_description"></small></p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


    <script>
        function showvideo(d) {
            $("#modal_title").text(d.getAttribute("data-visual_data_title"));
            $("#modal_heading").text(d.getAttribute("data-visual_data_heading"));
            $("#modal_description").text(d.getAttribute("data-visual_data_short"));
            $("#modal_img").attr("src", d.getAttribute("data-visual_data_img"));
            $("#iframe_src_link").attr("src", d.getAttribute("data-link"));
            $("#exampleModalvision").modal("show");
        }

        const swiper = new Swiper('.swiper', {
            // Optional parameters
            loop: true,
            centeredSlides: false,
            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                // when window width is >= 320px
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                // when window width is >= 480px
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 40
                },
                // when window width is >= 640px
                1280: {
                    slidesPerView: 3,
                    spaceBetween: 20
                }
            }
        });
    </script>


    <script>
        // Initialize Swiper
        const testimonialSwiper = new Swiper('.testimonialSlider', {
            // Optional parameters
            loop: true,
            slidesPerView: 1,
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },

            // If you want to show multiple slides at once
            breakpoints: {
                768: {
                    slidesPerView: 1,
                },
                1024: {
                    slidesPerView: 1,
                }
            },

            // Navigation arrows
            navigation: {
                nextEl: '.testimonialSection .swiper-button-next',
                prevEl: '.testimonialSection .swiper-button-prev',
            },

            // Pagination
            pagination: {
                el: '.testimonialSection .swiper-pagination',
                clickable: true,
            },
        });
    </script>

    <!-- FAQ Section: Nested Accordion -->


    </main><!-- End #main -->

<?php include_once("footer.php"); ?>
