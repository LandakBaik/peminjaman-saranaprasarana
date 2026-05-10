<?php

namespace App\Utils;

class LoadingScreen {
    private string $title;
    private string $message;

    public function __construct(
        string $title = "Memproses...",
        string $message = "Mohon tunggu sebentar"
    ) {
        $this->title = $title;
        $this->message = $message;
    }

    public function render(): void {
        ?>
        <style>
            #loading-overlay {
                position: fixed;
                inset: 0;
                width: 100%;
                height: 100%;
                background: rgba(15, 23, 42, 0.75);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);

                display: flex;
                justify-content: center;
                align-items: center;

                z-index: 9999;

                opacity: 0;
                visibility: hidden;

                transition:
                    opacity 0.5s ease,
                    visibility 0.5s ease;
            }

            #loading-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            .overlay-content {
                text-align: center;
                color: white;

                transform: translateY(20px) scale(0.95);
                opacity: 0;

                transition: all 0.5s ease;
            }

            #loading-overlay.active .overlay-content {
                transform: translateY(0) scale(1);
                opacity: 1;
            }

            .loader-ring {
                width: 70px;
                height: 70px;

                border: 5px solid rgba(255,255,255,0.1);
                border-top: 5px solid #38bdf8;
                border-radius: 50%;

                margin: 0 auto 25px;

                animation: spin-loader 1s linear infinite;
            }

            @keyframes spin-loader {
                to {
                    transform: rotate(360deg);
                }
            }

            .overlay-title {
                font-size: 26px;
                font-weight: 700;
                margin-bottom: 10px;
                font-family: 'Outfit', sans-serif;
            }

            .overlay-msg {
                font-size: 16px;
                opacity: 0.8;
                font-family: 'Outfit', sans-serif;
                min-height: 24px;
            }

            .progress-bar {
                width: 240px;
                height: 6px;

                background: rgba(255,255,255,0.1);

                border-radius: 999px;
                overflow: hidden;

                margin: 25px auto 0;
            }

            .progress-fill {
                height: 100%;
                width: 0%;

                background: #38bdf8;

                border-radius: 999px;

                animation: progressAnim 3s ease forwards;
            }

            @keyframes progressAnim {
                from {
                    width: 0%;
                }
                to {
                    width: 100%;
                }
            }
        </style>

        <div id="loading-overlay">
            <div class="overlay-content">

                <div class="loader-ring"></div>

                <div class="overlay-title">
                    <?= htmlspecialchars($this->title) ?>
                </div>

                <div class="overlay-msg" id="loading-message">
                    <?= htmlspecialchars($this->message) ?>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>

            </div>
        </div>

        <script>
            function showLoadingOverlay(redirectUrl = null) {

                const overlay = document.getElementById('loading-overlay');

                overlay.classList.add('active');

                const messages = [
                    "Menghubungkan sistem...",
                    "Menyiapkan dashboard...",
                    "Memuat data pengguna...",
                    "Hampir selesai..."
                ];

                let index = 0;

                const msgElement = document.getElementById('loading-message');

                const interval = setInterval(() => {

                    msgElement.innerText = messages[index];

                    index = (index + 1) % messages.length;

                }, 800);

                if (redirectUrl) {

                    setTimeout(() => {

                        clearInterval(interval);

                        overlay.style.opacity = '0';

                        setTimeout(() => {
                            window.location.href = redirectUrl;
                        }, 500);

                    }, 3000);
                }
            }
        </script>
        <?php
    }
}
