<?php include_once("header-Thank-You.php"); ?>
<title>Thank You</title>

    <main id="main">
        

    <style>
        .qte-bx{
            background: rgba(12, 28, 34, 0.8);
        }
    .pagination-wrapper {
            margin: 40px 0;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .page-item {
            margin: 0;
            /* border-right: 1px solid #dee2e6; */
        }

        .page-item:last-child {
            border-right: none;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 10px;
            color: #ffffff;
            background-color: transparent;
            border: none;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 20px;
        }

        .page-link:hover {
            background-color: transparent;
            color: #ffff00;
        }

        .page-item.active .page-link {
            background-color: transparent;
            color: #ffff00;
            border-color: transparent;
            border: none;
        }

        .page-item.disabled .page-link {
            color: #b7b7b7;
            pointer-events: none;
            background-color: transparent;
            opacity: 0.7;
        }

        /* Arrow styling */
        /* .page-link[aria-label="Previous"]::before,
                        .page-link[aria-label="Next"]::after {
                            display: inline-block;
                            margin: 0 0.25rem;
                        }

                        .page-link[aria-label="Previous"]::before {
                            content: "←";
                        }

                        .page-link[aria-label="Next"]::after {
                            content: "→";
                        } */
                        .Testimonials_section{
                            height: 100vh;
                        }
                        .pt-50{
                            padding-top: 100px;
                        }
    </style>

<div class="" style="background-image: url('assets/img/ai-forbusiness-s2.png'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 100vh; display: flex;">
    <div class="row container m-auto">
        <div class="col-lg-12 p-4">
            <div class="qte-bx mb-100" id="thankYouContent">
                <p class="info">
                    Thank you for registering for <b>AI FOR BUSINESS</b> !
                    <br><br>
                    Your registration is complete. A confirmation email with your QR code has been sent to your email address.
                </p>     
                <div class="pt-3">
                    <a href="https://eodubai.com/ai-for-business"><span class="ai-btn ai-btn-register">Home</span></a>
                </div>                           
            </div>
        </div>
    </div>
</div>

    </main><!-- End #main -->

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            // Read order_id or order_ref from URL query parameters
            const urlParams = new URLSearchParams(window.location.search);
            const orderId = urlParams.get('order_id') || urlParams.get('order_ref');
            const thankYouContent = document.getElementById('thankYouContent');

            if (orderId) {
                const backendApiUrl = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
                    ? 'http://localhost:3001/api/telr/verify'
                    : 'https://backend-production-c14ce.up.railway.app/api/telr/verify';

                try {
                    const response = await fetch(backendApiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ orderId: orderId })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to verify payment');
                    }

                    const data = await response.json();

                    if (data.success && data.registration) {
                        const reg = data.registration;
                        thankYouContent.innerHTML = `
                            <p class="info">
                                Thank you, <strong>${reg.name}</strong>, for registering for the AI FOR BUSINESS Workshop! <br><br>
                                Your registration is complete. A confirmation email with your QR code has been sent to <strong>${reg.email}</strong>.
                            </p>
                            <div style="text-align: center; margin: 30px 0;">
                                <h3 style="color: #ffff00; margin-bottom: 20px;">Your Event QR Code</h3>
                                <img src="${reg.qrCodeUrl}" alt="QR Code" style="max-width: 250px; border: 5px solid #ffff00; padding: 10px; border-radius: 15px;" />
                                <p style="margin-top: 20px; font-size: 1.1rem; color: #fff;">Please present this QR code at the event entrance.</p>
                            </div>
                            <div style="text-align: center; margin-top: 30px;">
                                <a href="https://eodubai.com/ai-for-business" class="ai-btn ai-btn-register">Home</a>
                            </div>
                        `;
                    } else {
                        // Fallback content as specified by the user
                        thankYouContent.innerHTML = `
                            <p class="info">
                                Payment verification failed. Please contact support if you believe this is an error.
                            </p>
                            <div class="pt-3">
                                <a href="https://eodubai.com/ai-for-business"><span class="ai-btn ai-btn-register">Home</span></a>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Error verifying payment:', error);
                    // Fallback content on error as specified by the user
                    thankYouContent.innerHTML = `
                        <p class="info">
                            There was an error processing your payment. Please contact support.
                        </p>
                        <div class="pt-3">
                            <a href="https://eodubai.com/ai-for-business"><span class="ai-btn ai-btn-register">Home</span></a>
                        </div>
                    `;
                }
            } else {
                // If no orderId is present in the URL, display the generic thank you message
                thankYouContent.innerHTML = `
                    <p class="info">
                        Thank you for registering for <b>AI FOR BUSINESS</b> !
                        <br><br>
                        Your payment and registration are complete. A confirmation email containing your payment receipt and event QR code will be sent to your registered email address within 24 hours.
                        <br><br>
                        Please check your inbox or spam folder for the confirmation email.
                    </p>
                    <div class="pt-3">
                        <a href="https://eodubai.com/ai-for-business"><span class="ai-btn ai-btn-register">Home</span></a>
                    </div>
                `;
            }
        });
    </script>
