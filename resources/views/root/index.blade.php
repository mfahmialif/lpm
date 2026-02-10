@extends('layouts.home.template')
@section('title', 'LPM UII Dalwa - Lembaga Penjaminan Mutu')
@section('content')
    <!-- Hero Banner -->
    <div class="hero-banner with-floating-header">
        <div class="media media-bg">
            <img src="{{ asset('home') }}/assets/img/slider/slider-bg.jpg" alt="LPM UII Dalwa background" width="1920"
                height="100" loading="eager" />
        </div>
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-12">
                    <div class="content section-headings">
                        <div class="subheading text-20 subheading-bg" data-aos="fade-up">
                            <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 14 14" fill="none">
                                <g clip-path="url(#clip0_9088_4143)">
                                    <path
                                        d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                        fill="CurrentColor" />
                                </g>
                                <defs>
                                    <clipPath>
                                        <rect width="14" height="14" fill="CurrentColor" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <span>Global Excellence</span>
                            <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 14 14" fill="none">
                                <g clip-path="url(#clip0_9088_4143)">
                                    <path
                                        d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                        fill="CurrentColor" />
                                </g>
                                <defs>
                                    <clipPath>
                                        <rect width="14" height="14" fill="CurrentColor" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <h2 class="heading text-80 fw-700" data-aos="fade-up" data-aos-delay="100">
                            Welcome to<br>
                            <span class="decorated-text"><span>LPM</span></span>
                            UII Dalwa
                        </h2>
                        <div class="text text-18" data-aos="fade-up" data-aos-delay="150">
                            We provide dedicated and sincere quality assurance services, fostering blessings in
                            every step of academic development.
                        </div>
                        <div class="hero-button-wrap buttons" data-aos="fade-up" data-aos-delay="200">
                            <a href="services.html" class="button button--primary" aria-label="hero button">
                                Get Started
                                <span class="svg-wrapper">
                                    <svg class="icon-20" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13.3365 7.84518L6.16435 15.0173L4.98584 13.8388L12.158 6.66667H5.83652V5H15.0032V14.1667H13.3365V7.84518Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                            </a>
                            <a href="tel:(307)555-0133" class="hero-phone-call" aria-label="Phone number" data-aos="fade-up"
                                data-aos-delay="50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 44 44"
                                    fill="none">
                                    <path
                                        d="M23.8337 3.67188C28.2097 3.67188 32.4066 5.41026 35.5009 8.50461C38.5953 11.599 40.3337 15.7958 40.3337 20.1719M23.8337 11.0052C26.2648 11.0052 28.5964 11.971 30.3155 13.6901C32.0346 15.4091 33.0003 17.7407 33.0003 20.1719M25.359 30.3799C25.7376 30.5538 26.1642 30.5935 26.5684 30.4925C26.9727 30.3915 27.3304 30.1559 27.5828 29.8244L28.2337 28.9719C28.5752 28.5165 29.0181 28.1469 29.5272 27.8923C30.0363 27.6377 30.5978 27.5052 31.167 27.5052H36.667C37.6395 27.5052 38.5721 27.8915 39.2597 28.5791C39.9473 29.2668 40.3337 30.1994 40.3337 31.1719V36.6719C40.3337 37.6443 39.9473 38.577 39.2597 39.2646C38.5721 39.9522 37.6395 40.3385 36.667 40.3385C27.9148 40.3385 19.5212 36.8618 13.3325 30.6731C7.14377 24.4844 3.66699 16.0907 3.66699 7.33854C3.66699 6.36608 4.0533 5.43345 4.74093 4.74582C5.42857 4.05818 6.3612 3.67188 7.33366 3.67188H12.8337C13.8061 3.67188 14.7387 4.05818 15.4264 4.74582C16.114 5.43345 16.5003 6.36608 16.5003 7.33854V12.8385C16.5003 13.4078 16.3678 13.9692 16.1132 14.4783C15.8587 14.9875 15.489 15.4303 15.0337 15.7719L14.1757 16.4154C13.8391 16.6724 13.6019 17.0379 13.5043 17.45C13.4067 17.8621 13.4548 18.2952 13.6403 18.6759C16.1459 23.765 20.2668 27.8807 25.359 30.3799Z"
                                        stroke="CurrentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <div class="hero-call">
                                    <div class="text text-14">Need help?</div>
                                    <div class="text text-16">+62 852-3627-9490</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <banner-slider class="banner-slider">
                        <div class="banner-badge svg-wrapper" data-aos="fade-right">
                            <svg class="infinite-rotate" viewBox="0 0 176 176" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M88 132L81.6588 100.294L63.7999 112.2L75.7058 94.3411L44 88L75.7058 81.6588L63.7999 63.7999L81.6588 75.7059L88 44L94.3411 75.7058L112.2 63.7999L100.294 81.6588L132 88L100.294 94.3412L112.2 112.2L94.3412 100.294L88 132Z"
                                    fill="#20282D" />

                                <!-- === Circular text: UII DALWA === -->
                                <defs>
                                    <!-- Radius 62 dari pusat (88,88). Ubah 62 jika perlu lebih masuk/keluar. -->
                                    <path id="badgeCircleTop" d="M88,88 m-62,0 a62,62 0 1,1 124,0 a62,62 0 1,1 -124,0" />
                                    <!-- Arah kebalikan untuk teks bawah agar tetap tegak -->
                                    <path id="badgeCircleBottom" d="M88,88 m62,0 a62,62 0 1,0 -124,0 a62,62 0 1,0 124,0" />
                                </defs>

                                <g font-family="Inter, Arial, sans-serif" font-size="11" font-weight="700"
                                    letter-spacing="2.2" fill="#1C2539">
                                    <!-- Atas -->
                                    <text>
                                        <textPath href="#badgeCircleTop" startOffset="50%" text-anchor="middle">
                                            â€¢ UII DALWA LPM â€¢ UII DALWA LPM â€¢ UII DALWA LPM
                                        </textPath>
                                    </text>
                                    <!-- Bawah -->
                                </g>
                                <!-- === /Circular text === -->
                            </svg>
                        </div>

                        <div class="main-slider" data-aos="fade-down">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    <!-- Slides -->
                                    <div class="swiper-slide">
                                        <div class="main-img">
                                            <img src="{{ asset('home') }}/assets/img/slider/s1.jpg" width="992"
                                                height="717" loading="eager" alt="Image" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="main-img">
                                            <img src="{{ asset('home') }}/assets/img/slider/s2.jpg" width="992"
                                                height="717" loading="lazy" alt="Image" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="main-img">
                                            <img src="{{ asset('home') }}/assets/img/slider/s3.jpg" width="992"
                                                height="717" loading="lazy" alt="Image" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="main-img">
                                            <img src="{{ asset('home') }}/assets/img/slider/s2.jpg" width="992"
                                                height="717" loading="lazy" alt="Image" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="thumb-slider" data-aos="fade-up">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    <!-- Slides -->
                                    <div class="swiper-slide">
                                        <div class="thumb-img">
                                            <img src="{{ asset('home') }}/assets/img/slider/s1sm.jpg" width="160"
                                                height="140" loading="lazy" alt="Slider image" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="thumb-img">
                                            <img src="{{ asset('home') }}/assets/img/slider/s2sm.jpg" width="160"
                                                height="140" loading="lazy" alt="Slider image" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="thumb-img">
                                            <img src="{{ asset('home') }}/assets/img/slider/s3sm.jpg" width="160"
                                                height="140" loading="lazy" alt="Slider image" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="thumb-img">
                                            <img src="{{ asset('home') }}/assets/img/slider/s2sm.jpg" width="160"
                                                height="140" loading="lazy" alt="Slider image" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-button-prev">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                    viewBox="0 0 16 14" fill="none">
                                    <path d="M7.125 13L1 7M1 7L7.125 1M1 7H15" stroke="CurrentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="swiper-button-next">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                    viewBox="0 0 16 14" fill="none">
                                    <path d="M8.875 13L15 7M15 7L8.875 1M15 7H1" stroke="CurrentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </banner-slider>
                </div>
            </div>
        </div>
    </div>


    <!-- Visi Misi Section - Premium Design -->
    <div style="background: linear-gradient(180deg, #0f1419 0%, #1a2332 50%, #0f1419 100%); padding: 100px 0; position: relative; overflow: hidden;">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
            <div style="position: absolute; top: 10%; left: 5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(212, 175, 55, 0.03) 0%, transparent 70%);"></div>
            <div style="position: absolute; bottom: 10%; right: 5%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(212, 175, 55, 0.02) 0%, transparent 70%);"></div>
        </div>
        
        <div class="container" style="position: relative; z-index: 1;">
            <!-- Section Header -->
            <div class="text-center" style="margin-bottom: 60px;" data-aos="fade-up">
                <div style="display: inline-flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <span style="width: 40px; height: 2px; background: linear-gradient(90deg, transparent, #d4af37);"></span>
                    <span style="color: #d4af37; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 3px;">Lembaga Penjaminan Mutu</span>
                    <span style="width: 40px; height: 2px; background: linear-gradient(90deg, #d4af37, transparent);"></span>
                </div>
                <h2 style="color: #fff; font-size: 42px; font-weight: 700; margin: 0;">
                    Visi <span style="color: #d4af37;">&</span> Misi LPM
                </h2>
            </div>
            
            <div class="row" style="gap: 30px 0;">
                <!-- Visi Card -->
                <div class="col-lg-5 col-12" data-aos="fade-right" data-aos-delay="100">
                    <div style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 20px; padding: 40px; height: 100%; position: relative; overflow: hidden; transition: all 0.4s ease;">
                        <!-- Gold accent line -->
                        <div style="position: absolute; top: 0; left: 30px; right: 30px; height: 3px; background: linear-gradient(90deg, transparent, #d4af37, transparent);"></div>
                        
                        <!-- Icon -->
                        <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 25px; box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0f1419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                        </div>
                        
                        <!-- Title -->
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                            <h3 style="color: #fff; font-size: 32px; font-weight: 700; margin: 0;">Visi</h3>
                            <span style="width: 30px; height: 2px; background: #d4af37;"></span>
                        </div>
                        
                        <!-- Content -->
                        <p style="color: rgba(255,255,255,0.85); font-size: 17px; line-height: 1.8; margin: 0;">
                            Menjadi lembaga penjaminan mutu yang <span style="color: #d4af37; font-weight: 600;">profesional</span> dan <span style="color: #d4af37; font-weight: 600;">kredibel</span> dalam mengawal tata kelola institusi yang 
                            <span style="color: #d4af37; font-weight: 600;">excellent</span> di tingkat ASEAN.
                        </p>
                        
                        <!-- Corner decoration -->
                        <div style="position: absolute; bottom: 20px; right: 20px; width: 40px; height: 40px; border-right: 2px solid rgba(212, 175, 55, 0.3); border-bottom: 2px solid rgba(212, 175, 55, 0.3);"></div>
                    </div>
                </div>
                
                <!-- Misi Card -->
                <div class="col-lg-7 col-12" data-aos="fade-left" data-aos-delay="200">
                    <div style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px); border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 20px; padding: 40px; height: 100%; position: relative; overflow: hidden; transition: all 0.4s ease;">
                        <!-- Gold accent line -->
                        <div style="position: absolute; top: 0; left: 30px; right: 30px; height: 3px; background: linear-gradient(90deg, transparent, #d4af37, transparent);"></div>
                        
                        <!-- Icon -->
                        <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 25px; box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0f1419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        
                        <!-- Title -->
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 25px;">
                            <h3 style="color: #fff; font-size: 32px; font-weight: 700; margin: 0;">Misi</h3>
                            <span style="width: 30px; height: 2px; background: #d4af37;"></span>
                        </div>
                        
                        <!-- Mission Items -->
                        <ol style="margin: 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 15px;">
                            <li style="display: flex; align-items: flex-start; gap: 15px; padding: 15px; background: rgba(212, 175, 55, 0.05); border-radius: 12px; border-left: 3px solid #d4af37; transition: all 0.3s ease;">
                                <span style="min-width: 32px; height: 32px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 700; font-size: 14px;">1</span>
                                <span style="color: rgba(255,255,255,0.85); font-size: 15px; line-height: 1.6;">Membangun budaya mutu pada setiap unit kerja berdasarkan nilai-nilai Iman, Ilmu, dan Amal.</span>
                            </li>
                            <li style="display: flex; align-items: flex-start; gap: 15px; padding: 15px; background: rgba(212, 175, 55, 0.05); border-radius: 12px; border-left: 3px solid #d4af37; transition: all 0.3s ease;">
                                <span style="min-width: 32px; height: 32px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 700; font-size: 14px;">2</span>
                                <span style="color: rgba(255,255,255,0.85); font-size: 15px; line-height: 1.6;">Melaksanakan tata kelola dan tata pamong yang baik dan profesional berbasis integritas dan nilai-nilai Islam.</span>
                            </li>
                            <li style="display: flex; align-items: flex-start; gap: 15px; padding: 15px; background: rgba(212, 175, 55, 0.05); border-radius: 12px; border-left: 3px solid #d4af37; transition: all 0.3s ease;">
                                <span style="min-width: 32px; height: 32px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 700; font-size: 14px;">3</span>
                                <span style="color: rgba(255,255,255,0.85); font-size: 15px; line-height: 1.6;">Menyusun panduan standar dan manual prosedur penjaminan mutu internal secara sistematis dan berkelanjutan.</span>
                            </li>
                            <li style="display: flex; align-items: flex-start; gap: 15px; padding: 15px; background: rgba(212, 175, 55, 0.05); border-radius: 12px; border-left: 3px solid #d4af37; transition: all 0.3s ease;">
                                <span style="min-width: 32px; height: 32px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 700; font-size: 14px;">4</span>
                                <span style="color: rgba(255,255,255,0.85); font-size: 15px; line-height: 1.6;">Melakukan pendampingan akreditasi program studi dan lembaga secara intensif.</span>
                            </li>
                            <li style="display: flex; align-items: flex-start; gap: 15px; padding: 15px; background: rgba(212, 175, 55, 0.05); border-radius: 12px; border-left: 3px solid #d4af37; transition: all 0.3s ease;">
                                <span style="min-width: 32px; height: 32px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 700; font-size: 14px;">5</span>
                                <span style="color: rgba(255,255,255,0.85); font-size: 15px; line-height: 1.6;">Melaksanakan evaluasi internal dan eksternal secara berkala untuk memastikan peningkatan mutu yang berkesinambungan.</span>
                            </li>
                        </ol>
                        
                        <!-- Corner decoration -->
                        <div style="position: absolute; bottom: 20px; right: 20px; width: 40px; height: 40px; border-right: 2px solid rgba(212, 175, 55, 0.3); border-bottom: 2px solid rgba(212, 175, 55, 0.3);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategi Pencapaian Section -->
    <div style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%); padding: 100px 0; position: relative;">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center" style="margin-bottom: 60px;" data-aos="fade-up">
                <div style="display: inline-flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <span style="width: 40px; height: 2px; background: linear-gradient(90deg, transparent, #d4af37);"></span>
                    <span style="color: #d4af37; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 3px;">Rencana Strategis</span>
                    <span style="width: 40px; height: 2px; background: linear-gradient(90deg, #d4af37, transparent);"></span>
                </div>
                <h2 style="color: #1a2332; font-size: 42px; font-weight: 700; margin: 0;">
                    Strategi <span style="color: #d4af37;">Pencapaian</span> LPM
                </h2>
            </div>
            
            <div class="row" style="gap: 25px 0;">
                <!-- Strategy 1 -->
                <div class="col-lg-6 col-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="strategi-card" style="background: #fff; border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 16px; padding: 30px; height: 100%; border-left: 4px solid #d4af37; transition: all 0.3s ease; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                            <div style="min-width: 50px; height: 50px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 800; font-size: 20px;">01</div>
                            <h4 style="color: #1a2332; font-size: 18px; font-weight: 700; margin: 0;">Peningkatan Standar Mutu Internal</h4>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Menyusun dan merevisi standar mutu sesuai perkembangan regulasi.</span>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Melakukan benchmarking dengan institusi bereputasi.</span>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Mengintegrasikan nilai-nilai keislaman dalam setiap standar.</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Strategy 2 -->
                <div class="col-lg-6 col-12" data-aos="fade-up" data-aos-delay="150">
                    <div class="strategi-card" style="background: #fff; border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 16px; padding: 30px; height: 100%; border-left: 4px solid #d4af37; transition: all 0.3s ease; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                            <div style="min-width: 50px; height: 50px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 800; font-size: 20px;">02</div>
                            <h4 style="color: #1a2332; font-size: 18px; font-weight: 700; margin: 0;">Pengembangan Kapasitas SDM</h4>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Pelatihan auditor mutu internal secara berkala.</span>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Workshop penyusunan dokumen akreditasi.</span>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Studi banding ke lembaga penjaminan mutu lain.</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Strategy 3 -->
                <div class="col-lg-6 col-12" data-aos="fade-up" data-aos-delay="200">
                    <div class="strategi-card" style="background: #fff; border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 16px; padding: 30px; height: 100%; border-left: 4px solid #d4af37; transition: all 0.3s ease; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                            <div style="min-width: 50px; height: 50px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 800; font-size: 20px;">03</div>
                            <h4 style="color: #1a2332; font-size: 18px; font-weight: 700; margin: 0;">Penguatan Sistem Monitoring & Evaluasi</h4>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Audit mutu internal (AMI) setiap semester.</span>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Survei kepuasan stakeholder secara periodik.</span>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Rapat tinjauan manajemen (RTM) tahunan.</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Strategy 4 -->
                <div class="col-lg-6 col-12" data-aos="fade-up" data-aos-delay="250">
                    <div class="strategi-card" style="background: #fff; border: 1px solid rgba(212, 175, 55, 0.2); border-radius: 16px; padding: 30px; height: 100%; border-left: 4px solid #d4af37; transition: all 0.3s ease; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                            <div style="min-width: 50px; height: 50px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 800; font-size: 20px;">04</div>
                            <h4 style="color: #1a2332; font-size: 18px; font-weight: 700; margin: 0;">Digitalisasi Sistem Penjaminan Mutu</h4>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Pengembangan sistem informasi penjaminan mutu.</span>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Dashboard monitoring capaian mutu real-time.</span>
                            </div>
                            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span style="color: #555; font-size: 14px; line-height: 1.6;">Integrasi data dengan sistem akademik pusat.</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Strategy 5 - Full Width with Dark Theme -->
                <div class="col-12" data-aos="fade-up" data-aos-delay="300">
                    <div class="strategi-card" style="background: linear-gradient(135deg, #1a2332 0%, #0f1419 100%); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 16px; padding: 40px; border-left: 4px solid #d4af37; transition: all 0.3s ease; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                            <div style="min-width: 60px; height: 60px; background: linear-gradient(135deg, #d4af37 0%, #aa8a2e 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #0f1419; font-weight: 800; font-size: 22px;">05</div>
                            <h4 style="color: #fff; font-size: 22px; font-weight: 700; margin: 0;">Pendampingan Akreditasi Berkelanjutan</h4>
                        </div>
                        <div class="row" style="gap: 15px 0;">
                            <div class="col-lg-4 col-md-6 col-12">
                                <div style="display: flex; align-items: flex-start; gap: 14px; padding: 20px; background: rgba(212, 175, 55, 0.08); border-radius: 14px; border: 1px solid rgba(212, 175, 55, 0.15); height: 100%;">
                                    <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <span style="color: rgba(255,255,255,0.9); font-size: 15px; line-height: 1.7;">Mendampingi proses reakreditasi program studi secara intensif.</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div style="display: flex; align-items: flex-start; gap: 14px; padding: 20px; background: rgba(212, 175, 55, 0.08); border-radius: 14px; border: 1px solid rgba(212, 175, 55, 0.15); height: 100%;">
                                    <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <span style="color: rgba(255,255,255,0.9); font-size: 15px; line-height: 1.7;">Memastikan seluruh data dan dokumen akreditasi valid, mutakhir, dan sahih.</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div style="display: flex; align-items: flex-start; gap: 14px; padding: 20px; background: rgba(212, 175, 55, 0.08); border-radius: 14px; border: 1px solid rgba(212, 175, 55, 0.15); height: 100%;">
                                    <svg style="flex-shrink: 0; margin-top: 2px;" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <span style="color: rgba(255,255,255,0.9); font-size: 15px; line-height: 1.7;">Mengintegrasikan hasil akreditasi sebagai dasar peningkatan mutu berkelanjutan.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .strategi-card:hover {
            border-color: rgba(212, 175, 55, 0.5);
            box-shadow: 0 15px 50px rgba(212, 175, 55, 0.1);
            transform: translateY(-3px);
        }
    </style>

    <!-- Recent Projects -->
    <project-slider class="project-slider mt-100">
        <div class="container">
            <div class="section-headings headings-width text-center">
                <div class="subheading text-20 subheading-bg" data-aos="fade-up" data-aos-delay="10">
                    <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                        viewBox="0 0 14 14" fill="none">
                        <g clip-path="url(#clip0_9088_4143)">
                            <path
                                d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                fill="CurrentColor" />
                        </g>
                        <defs>
                            <clipPath>
                                <rect width="14" height="14" fill="CurrentColor" />
                            </clipPath>
                        </defs>
                    </svg>
                    <span>Recent Projects</span>
                    <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                        viewBox="0 0 14 14" fill="none">
                        <g clip-path="url(#clip0_9088_4143)">
                            <path
                                d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                fill="CurrentColor" />
                        </g>
                        <defs>
                            <clipPath>
                                <rect width="14" height="14" fill="CurrentColor" />
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <h2 class="heading text-50" data-aos="fade-up" data-aos-delay="20">
                    Explore the Recent Works We Have Done!
                </h2>
            </div>
        </div>

        <div class="section-content">
            <div class="container-fluid slider-container" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper">
                    <div class="swiper-wrapper justify-content-center">
                        @foreach ($activity as $item)
                            <div class="swiper-slide">
                                <a class="card-project radius18" aria-label="project details" href="{{ route('activity.detail', ['slug' => $item->slug]) }}">
                                    <img src="{{ $item->url_image }}" alt="project image"
                                        width="645" height="690" loading="lazy" />
                                    <div class="card-project-content-absolute">
                                        <div class="card-project-content">
                                            <h2 class="heading text-24">{{ $item->title }}</h2>
                                            <p class="text text-16">{{ $item->unit->pluck('name')->implode(', ') }}</p>
                                        </div>
                                    </div>
                                    <span class="svg-wrapper icon-project-link">
                                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="32" cy="32" r="32" fill="white" />
                                            <path
                                                d="M26.1667 39C25.8167 39 25.5833 38.8833 25.35 38.65C24.8833 38.1833 24.8833 37.4833 25.35 37.0167L37.0167 25.35C37.4833 24.8833 38.1833 24.8833 38.65 25.35C39.1167 25.8167 39.1167 26.5167 38.65 26.9833L26.9833 38.65C26.75 38.8833 26.5167 39 26.1667 39Z"
                                                fill="#20282D" />
                                            <path
                                                d="M37.8332 37.8333C37.1332 37.8333 36.6665 37.3667 36.6665 36.6667V27.3333H27.3332C26.6332 27.3333 26.1665 26.8667 26.1665 26.1667C26.1665 25.4667 26.6332 25 27.3332 25H37.8332C38.5332 25 38.9998 25.4667 38.9998 26.1667V36.6667C38.9998 37.3667 38.5332 37.8333 37.8332 37.8333Z"
                                                fill="#20282D" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="swiper-nav-border" data-aos="fade-up" data-aos-delay="150">
                    <div class="swiper-nav-inner">
                        <div class="swiper-button-prev">
                            <svg viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.910711 5.40903C0.754485 5.5653 0.666722 5.77723 0.666722 5.9982C0.666722 6.21917 0.754485 6.43109 0.910711 6.58736L5.62488 11.3015C5.70175 11.3811 5.7937 11.4446 5.89537 11.4883C5.99704 11.532 6.10639 11.5549 6.21704 11.5559C6.32769 11.5569 6.43742 11.5358 6.53984 11.4939C6.64225 11.452 6.7353 11.3901 6.81354 11.3119C6.89178 11.2336 6.95366 11.1406 6.99556 11.0382C7.03746 10.9357 7.05855 10.826 7.05759 10.7154C7.05662 10.6047 7.03364 10.4954 6.98996 10.3937C6.94629 10.292 6.8828 10.2001 6.80321 10.1232L2.67821 5.9982L6.80321 1.8732C6.95501 1.71603 7.039 1.50553 7.03711 1.28703C7.03521 1.06853 6.94757 0.859522 6.79306 0.705015C6.63855 0.550508 6.42954 0.462868 6.21104 0.460969C5.99255 0.45907 5.78205 0.543066 5.62488 0.694864L0.910711 5.40903Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                        <div class="swiper-button-next">
                            <svg viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.08929 5.40903C7.24552 5.5653 7.33328 5.77723 7.33328 5.9982C7.33328 6.21917 7.24552 6.43109 7.08929 6.58736L2.37512 11.3015C2.29825 11.3811 2.2063 11.4446 2.10463 11.4883C2.00296 11.532 1.89361 11.5549 1.78296 11.5559C1.67231 11.5569 1.56258 11.5358 1.46016 11.4939C1.35775 11.452 1.2647 11.3901 1.18646 11.3119C1.10822 11.2336 1.04634 11.1406 1.00444 11.0382C0.962537 10.9357 0.941453 10.826 0.942414 10.7154C0.943376 10.6047 0.966364 10.4954 1.01004 10.3937C1.05371 10.292 1.1172 10.2001 1.19679 10.1232L5.32179 5.9982L1.19679 1.8732C1.04499 1.71603 0.960996 1.50553 0.962894 1.28703C0.964793 1.06853 1.05243 0.859522 1.20694 0.705015C1.36145 0.550508 1.57046 0.462868 1.78896 0.460969C2.00745 0.45907 2.21795 0.543066 2.37512 0.694864L7.08929 5.40903Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </project-slider>

    <!-- FAQ -->
    <div class="faq mt-100" id="faq">
        <div class="container">
            <div class="row faq-row">
                <div class="col-lg-6 col-12">
                    <div class="section-headings">
                        <div class="subheading text-20 subheading-bg" data-aos="fade-up">
                            <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 14 14" fill="none">
                                <g clip-path="url(#clip0_9088_4143)">
                                    <path
                                        d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                        fill="CurrentColor" />
                                </g>
                                <defs>
                                    <clipPath>
                                        <rect width="14" height="14" fill="CurrentColor" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <span>Questions</span>
                            <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 14 14" fill="none">
                                <g clip-path="url(#clip0_9088_4143)">
                                    <path
                                        d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                        fill="CurrentColor" />
                                </g>
                                <defs>
                                    <clipPath>
                                        <rect width="14" height="14" fill="CurrentColor" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <h2 class="heading text-50" data-aos="fade-up" data-aos-delay="50">
                            Have any questions? here some answers
                        </h2>
                        <div class="text text-18" data-aos="fade-up" data-aos-delay="80">
                            In relation to websites and apps, UI design considers the look, interactivity of the
                            making product. It's all about making sure that the user interface.
                        </div>
                        <div class="buttons" data-aos="fade-up" data-aos-delay="100">
                            <a href="about.html" class="button button--primary" aria-label="Ask Your Question">
                                Ask Your Question
                                <span class="svg-wrapper">
                                    <svg class="icon-20" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13.3365 7.84518L6.16435 15.0173L4.98584 13.8388L12.158 6.66667H5.83652V5H15.0032V14.1667H13.3365V7.84518Z"
                                            fill="CurrentColor" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                        <div class="image-absolute" data-aos="zoom-in">
                            <img src="{{ asset('home') }}/assets/img/faq/question.png" width="104" height="180"
                                loading="lazy" alt="Image">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <faq-accordion>
                        <div class="accordion-list">
                            <div class="accordion-block" data-aos="fade-up">
                                <div class="accordion-opener heading text-22">
                                    What is the main role of LPM UII Dalwa?
                                    <div class="svg-wrapper">
                                        <!-- icon tetap -->
                                    </div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text text-18">
                                        The Quality Assurance Institute (LPM) UII Dalwa ensures academic quality
                                        through planning, implementation, monitoring, evaluation, and continuous
                                        improvement guided by sincerity and devotion.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block" data-aos="fade-up" data-aos-delay="50">
                                <div class="accordion-opener heading text-22">
                                    How does LPM carry out quality assurance?
                                    <div class="svg-wrapper"></div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text text-18">
                                        LPM implements quality assurance by establishing standards, conducting
                                        internal audits, monitoring learning processes, and evaluating programs to
                                        ensure academic excellence with blessings.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block" data-aos="fade-up" data-aos-delay="100">
                                <div class="accordion-opener heading text-22">
                                    What values guide LPM UII Dalwa?
                                    <div class="svg-wrapper"></div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text text-18">
                                        Our vision and mission are rooted in devotion, sincerity, and blessings,
                                        ensuring that all quality assurance activities uphold Islamic values.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block" data-aos="fade-up" data-aos-delay="150">
                                <div class="accordion-opener heading text-22">
                                    How does LPM support academic development?
                                    <div class="svg-wrapper"></div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text text-18">
                                        LPM supports faculties and study programs through training, mentoring,
                                        evaluation, and continuous improvement programs to strengthen academic
                                        quality.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-block" data-aos="fade-up" data-aos-delay="200">
                                <div class="accordion-opener heading text-22">
                                    What is the ultimate goal of LPM?
                                    <div class="svg-wrapper"></div>
                                </div>
                                <div class="accordion-content">
                                    <div class="accordion-content-inner text text-18">
                                        The ultimate goal is to create a culture of quality that ensures academic
                                        excellence, institutional integrity, and sustainable blessings for UII
                                        Dalwa.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </faq-accordion>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured News -->
    <div class="featured-blog mt-100 section-padding">
        <div class="container">
            <div class="section-headings text-center">
                <div class="subheading subheading-bg text-20" data-aos="fade-up">
                    <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                        viewBox="0 0 14 14" fill="none">
                        <g clip-path="url(#clip0_9088_4143)">
                            <path
                                d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                fill="CurrentColor" />
                        </g>
                        <defs>
                            <clipPath>
                                <rect width="14" height="14" fill="CurrentColor" />
                            </clipPath>
                        </defs>
                    </svg>
                    <span>Our News</span>
                    <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                        viewBox="0 0 14 14" fill="none">
                        <g clip-path="url(#clip0_9088_4143)">
                            <path
                                d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                fill="CurrentColor" />
                        </g>
                        <defs>
                            <clipPath>
                                <rect width="14" height="14" fill="CurrentColor" />
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <h2 class="heading text-50" data-aos="fade-up" data-aos-delay="50">
                    Latest News From Us
                </h2>
            </div>
            <div class="section-content">
                <div class="row product-grid justify-content-center">
                    @foreach ($news as $item)
                        <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-blog radius18">
                                <div class="card-blog-top">
                                    <div class="card-blog-meta">
                                        <div class="card-blog-meta-item text text-18">
                                            <svg width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M8.0007 0.046875C6.95088 0.046875 5.94406 0.463912 5.20173 1.20624C4.4594 1.94858 4.04236 2.95539 4.04236 4.00521C4.04236 5.05502 4.4594 6.06184 5.20173 6.80417C5.94406 7.5465 6.95088 7.96354 8.0007 7.96354C9.05051 7.96354 10.0573 7.5465 10.7997 6.80417C11.542 6.06184 11.959 5.05502 11.959 4.00521C11.959 2.95539 11.542 1.94858 10.7997 1.20624C10.0573 0.463912 9.05051 0.046875 8.0007 0.046875ZM5.29236 4.00521C5.29236 3.28691 5.57771 2.59804 6.08562 2.09013C6.59353 1.58222 7.2824 1.29688 8.0007 1.29688C8.71899 1.29688 9.40787 1.58222 9.91578 2.09013C10.4237 2.59804 10.709 3.28691 10.709 4.00521C10.709 4.7235 10.4237 5.41238 9.91578 5.92029C9.40787 6.4282 8.71899 6.71354 8.0007 6.71354C7.2824 6.71354 6.59353 6.4282 6.08562 5.92029C5.57771 5.41238 5.29236 4.7235 5.29236 4.00521ZM8.0007 9.21354C6.0732 9.21354 4.29653 9.65187 2.9807 10.3919C1.68403 11.1219 0.709031 12.2269 0.709031 13.5885V13.6735C0.708198 14.6419 0.707364 15.8569 1.7732 16.7252C2.29736 17.1519 3.03153 17.456 4.0232 17.656C5.01653 17.8577 6.31236 17.9635 8.0007 17.9635C9.68903 17.9635 10.984 17.8577 11.979 17.656C12.9707 17.456 13.704 17.1519 14.229 16.7252C15.2949 15.8569 15.2932 14.6419 15.2924 13.6735V13.5885C15.2924 12.2269 14.3174 11.1219 13.0215 10.3919C11.7049 9.65187 9.92903 9.21354 8.0007 9.21354ZM1.95903 13.5885C1.95903 12.8794 2.47736 12.1094 3.5932 11.4819C4.68986 10.8652 6.24653 10.4635 8.00153 10.4635C9.75486 10.4635 11.3115 10.8652 12.4082 11.4819C13.5249 12.1094 14.0424 12.8794 14.0424 13.5885C14.0424 14.6785 14.009 15.2919 13.439 15.7552C13.1307 16.0069 12.614 16.2527 11.7307 16.431C10.8499 16.6094 9.6457 16.7135 8.0007 16.7135C6.3557 16.7135 5.1507 16.6094 4.2707 16.431C3.38736 16.2527 2.8707 16.0069 2.56236 15.756C1.99236 15.2919 1.95903 14.6785 1.95903 13.5885Z"
                                                    fill="currentColor" />
                                            </svg>
                                            {{ $item->author->name }}
                                        </div>
                                        <div class="card-blog-meta-item text text-18">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M13.1667 10.6667C13.3877 10.6667 13.5996 10.5789 13.7559 10.4226C13.9122 10.2663 14 10.0543 14 9.83333C14 9.61232 13.9122 9.40036 13.7559 9.24408C13.5996 9.0878 13.3877 9 13.1667 9C12.9457 9 12.7337 9.0878 12.5774 9.24408C12.4211 9.40036 12.3333 9.61232 12.3333 9.83333C12.3333 10.0543 12.4211 10.2663 12.5774 10.4226C12.7337 10.5789 12.9457 10.6667 13.1667 10.6667ZM13.1667 14C13.3877 14 13.5996 13.9122 13.7559 13.7559C13.9122 13.5996 14 13.3877 14 13.1667C14 12.9457 13.9122 12.7337 13.7559 12.5774C13.5996 12.4211 13.3877 12.3333 13.1667 12.3333C12.9457 12.3333 12.7337 12.4211 12.5774 12.5774C12.4211 12.7337 12.3333 12.9457 12.3333 13.1667C12.3333 13.3877 12.4211 13.5996 12.5774 13.7559C12.7337 13.9122 12.9457 14 13.1667 14ZM9.83333 9.83333C9.83333 10.0543 9.74554 10.2663 9.58926 10.4226C9.43297 10.5789 9.22101 10.6667 9 10.6667C8.77899 10.6667 8.56702 10.5789 8.41074 10.4226C8.25446 10.2663 8.16667 10.0543 8.16667 9.83333C8.16667 9.61232 8.25446 9.40036 8.41074 9.24408C8.56702 9.0878 8.77899 9 9 9C9.22101 9 9.43297 9.0878 9.58926 9.24408C9.74554 9.40036 9.83333 9.61232 9.83333 9.83333ZM9.83333 13.1667C9.83333 13.3877 9.74554 13.5996 9.58926 13.7559C9.43297 13.9122 9.22101 14 9 14C8.77899 14 8.56702 13.9122 8.41074 13.7559C8.25446 13.5996 8.16667 13.3877 8.16667 13.1667C8.16667 12.9457 8.25446 12.7337 8.41074 12.5774C8.56702 12.4211 8.77899 12.3333 9 12.3333C9.22101 12.3333 9.43297 12.4211 9.58926 12.5774C9.74554 12.7337 9.83333 12.9457 9.83333 13.1667ZM4.83333 10.6667C5.05435 10.6667 5.26631 10.5789 5.42259 10.4226C5.57887 10.2663 5.66667 10.0543 5.66667 9.83333C5.66667 9.61232 5.57887 9.40036 5.42259 9.24408C5.26631 9.0878 5.05435 9 4.83333 9C4.61232 9 4.40036 9.0878 4.24408 9.24408C4.0878 9.40036 4 9.61232 4 9.83333C4 10.0543 4.0878 10.2663 4.24408 10.4226C4.40036 10.5789 4.61232 10.6667 4.83333 10.6667ZM4.83333 14C5.05435 14 5.26631 13.9122 5.42259 13.7559C5.57887 13.5996 5.66667 13.3877 5.66667 13.1667C5.66667 12.9457 5.57887 12.7337 5.42259 12.5774C5.26631 12.4211 5.05435 12.3333 4.83333 12.3333C4.61232 12.3333 4.40036 12.4211 4.24408 12.5774C4.0878 12.7337 4 12.9457 4 13.1667C4 13.3877 4.0878 13.5996 4.24408 13.7559C4.40036 13.9122 4.61232 14 4.83333 14Z"
                                                    fill="currentColor" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M4.83268 0.453125C4.99844 0.453125 5.15741 0.518973 5.27462 0.636183C5.39183 0.753394 5.45768 0.912365 5.45768 1.07812V1.71396C6.00935 1.70312 6.61685 1.70312 7.28518 1.70312H10.7127C11.3818 1.70312 11.9893 1.70312 12.541 1.71396V1.07812C12.541 0.912365 12.6069 0.753394 12.7241 0.636183C12.8413 0.518973 13.0003 0.453125 13.166 0.453125C13.3318 0.453125 13.4907 0.518973 13.608 0.636183C13.7252 0.753394 13.791 0.912365 13.791 1.07812V1.76729C14.0077 1.78396 14.2127 1.80479 14.4068 1.83063C15.3835 1.96229 16.1743 2.23896 16.7985 2.86229C17.4218 3.48646 17.6985 4.27729 17.8302 5.25396C17.9577 6.20396 17.9577 7.41646 17.9577 8.94812V10.7081C17.9577 12.2398 17.9577 13.4531 17.8302 14.4023C17.6985 15.379 17.4218 16.1698 16.7985 16.794C16.1743 17.4173 15.3835 17.694 14.4068 17.8256C13.4568 17.9531 12.2443 17.9531 10.7127 17.9531H7.28602C5.75435 17.9531 4.54102 17.9531 3.59185 17.8256C2.61518 17.694 1.82435 17.4173 1.20018 16.794C0.576849 16.1698 0.300182 15.379 0.168516 14.4023C0.0410156 13.4523 0.0410156 12.2398 0.0410156 10.7081V8.94812C0.0410156 7.41646 0.0410156 6.20312 0.168516 5.25396C0.300182 4.27729 0.576849 3.48646 1.20018 2.86229C1.82435 2.23896 2.61518 1.96229 3.59185 1.83063C3.78602 1.80479 3.99185 1.78396 4.20768 1.76729V1.07812C4.20768 0.912365 4.27353 0.753394 4.39074 0.636183C4.50795 0.518973 4.66692 0.453125 4.83268 0.453125ZM3.75768 3.06979C2.92018 3.18229 2.43685 3.39396 2.08435 3.74646C1.73185 4.09896 1.52018 4.58229 1.40768 5.42062C1.38852 5.56229 1.37268 5.71229 1.35935 5.86979H16.6393C16.626 5.71146 16.6102 5.56229 16.591 5.41979C16.4785 4.58229 16.2668 4.09896 15.9143 3.74646C15.5618 3.39396 15.0785 3.18229 14.2402 3.06979C13.3843 2.95479 12.2552 2.95312 10.666 2.95312H7.33268C5.74352 2.95312 4.61518 2.95479 3.75768 3.06979ZM1.29102 8.99479C1.29102 8.28312 1.29102 7.66396 1.30185 7.11979H16.6968C16.7077 7.66396 16.7077 8.28312 16.7077 8.99479V10.6615C16.7077 12.2506 16.706 13.3798 16.591 14.2365C16.4785 15.074 16.2668 15.5573 15.9143 15.9098C15.5618 16.2623 15.0785 16.474 14.2402 16.5865C13.3843 16.7015 12.2552 16.7031 10.666 16.7031H7.33268C5.74352 16.7031 4.61518 16.7015 3.75768 16.5865C2.92018 16.474 2.43685 16.2623 2.08435 15.9098C1.73185 15.5573 1.52018 15.074 1.40768 14.2356C1.29268 13.3798 1.29102 12.2506 1.29102 10.6615V8.99479Z"
                                                    fill="currentColor" />
                                            </svg>
                                            {{ $item->published_at_formatted }}
                                        </div>
                                    </div>
                                    <h2 class="card-blog-heading heading text-22">
                                        <a href="{{ route('news.detail', ['slug' => $item->slug]) }}"
                                            class="heading text-22">
                                            {{ $item->title }}
                                        </a>
                                    </h2>
                                </div>
                                <a class="card-blog-bottom" href="{{ route('news.detail', ['slug' => $item->slug]) }}"
                                    aria-label="Blog details">
                                    <span
                                        class="blog-tag subheading subheading-bg text-16 fw-500">{{ $item->categories->pluck('name')->implode(', ') }}</span>
                                    <div class="media">
                                        <img src="{{ $item->url_image }}" alt="blog image" width="1000"
                                            height="707" loading="lazy" />
                                    </div>
                                    <div class="buttons">
                                        <div class="button button--primary">
                                            Read More
                                            <svg viewBox="0 0 11 10" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2.16668 0.833333C2.16668 0.61232 2.25448 0.400358 2.41076 0.244078C2.56704 0.0877975 2.779 0 3.00001 0H9.66668C9.88769 0 10.0997 0.0877975 10.2559 0.244078C10.4122 0.400358 10.5 0.61232 10.5 0.833333V7.5C10.5 7.72101 10.4122 7.93297 10.2559 8.08926C10.0997 8.24554 9.88769 8.33333 9.66668 8.33333C9.44567 8.33333 9.2337 8.24554 9.07742 8.08926C8.92114 7.93297 8.83335 7.72101 8.83335 7.5V2.845L1.92251 9.75583C1.76535 9.90763 1.55484 9.99163 1.33635 9.98973C1.11785 9.98783 0.908839 9.90019 0.754332 9.74568C0.599825 9.59118 0.512184 9.38216 0.510285 9.16367C0.508387 8.94517 0.592382 8.73467 0.744181 8.5775L7.65501 1.66667H3.00001C2.779 1.66667 2.56704 1.57887 2.41076 1.42259C2.25448 1.26631 2.16668 1.05435 2.16668 0.833333Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="buttons buttons-discover" data-aos="fade-up">
                    <a href="{{ route('news.index') }}" class="button button--primary"
                        aria-label="Discover more blog posts">
                        Discover More
                        <span class="svg-wrapper">
                            <svg class="icon-20" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.3365 7.84518L6.16435 15.0173L4.98584 13.8388L12.158 6.66667H5.83652V5H15.0032V14.1667H13.3365V7.84518Z"
                                    fill="CurrentColor" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
