@extends('layouts.home.template')
@section('title', 'News - LPM UII Dalwa')
@section('content')
    <!-- Page Banner -->
    {{-- <div class="page-banner overlay">
        <picture class="media media-bg">
            <source media="(max-width: 575px)" srcset="{{ asset('home') }}/assets/img/banner/page-banner-575.jpg" />
            <source media="(max-width: 991px)" srcset="{{ asset('home') }}/assets/img/banner/page-banner-991.jpg" />
            <img src="{{ asset('home') }}/assets/img/banner/page-banner.jpg" width="1920" height="520" loading="eager"
                alt="Page Banner Image" />
        </picture>
        <div class="page-banner-content">
            <div class="container text-center">
                <h1 class="heading text-80 fw-700" data-aos="fade-up">
                    News
                </h1>
                <ul class="breadcrumb list-unstyled" data-aos="fade-up" data-aos-delay="100">
                    <li>
                        <a href="index.html" class="text text-18" aria-label="Home Page">
                            Home
                        </a>
                    </li>
                    <li>
                        <svg width="8" height="12" viewBox="0 0 8 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.08929 5.40903C7.24552 5.5653 7.33328 5.77723 7.33328 5.9982C7.33328 6.21917 7.24552 6.43109 7.08929 6.58736L2.37512 11.3015C2.29825 11.3811 2.2063 11.4446 2.10463 11.4883C2.00296 11.532 1.89361 11.5549 1.78296 11.5559C1.67231 11.5569 1.56258 11.5358 1.46016 11.4939C1.35775 11.452 1.2647 11.3901 1.18646 11.3119C1.10822 11.2336 1.04634 11.1406 1.00444 11.0382C0.962537 10.9357 0.941453 10.826 0.942414 10.7154C0.943376 10.6047 0.966364 10.4954 1.01004 10.3937C1.05371 10.292 1.1172 10.2001 1.19679 10.1232L5.32179 5.9982L1.19679 1.8732C1.04499 1.71603 0.960996 1.50553 0.962894 1.28703C0.964793 1.06853 1.05243 0.859522 1.20694 0.705015C1.36145 0.550508 1.57046 0.462868 1.78896 0.460969C2.00745 0.45907 2.21795 0.543066 2.37512 0.694864L7.08929 5.40903Z"
                                fill="currentColor" />
                        </svg>
                    </li>
                    <li>
                        <a role="link" aria-disabled="true" class="text text-18 active">
                            News
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div> --}}

    <div style="height: 50px"></div>
    <!-- Blog List -->
    <div class="page-blog mt-100" style="margin-bottom: 100px">
        <div class="container">
            <drawer-opener class="open-sidebar svg-wrapper text text-20 fw-500 d-lg-none" data-drawer=".drawer-blog-sidebar"
                data-aos="fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                </svg>
                Filter
            </drawer-opener>
            <div class="row">
                <div class="col-12 col-lg-7">
                    <div class="blog-list-wrapper">
                        @foreach ($news as $item)
                            <div class="card-blog-list" data-aos="fade-up">
                                <div class="card-blog-list-media radius18">
                                    <div class="media">
                                        <img src="{{ $item->url_image }}" alt="blog image" width="1000" height="707"
                                            loading="lazy" />
                                    </div>
                                </div>

                                <div class="card-blog-content">
                                    <div class="card-blog-meta">
                                        <div class="card-blog-meta-item text text-18">
                                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.00098 0.650391C11.499 0.650391 13.5437 2.69437 13.5439 5.19238C13.5439 7.69056 11.4992 9.73535 9.00098 9.73535C6.50299 9.73517 4.45898 7.69045 4.45898 5.19238C4.45919 2.69448 6.50308 0.650569 9.00098 0.650391Z"
                                                    stroke="currentColor" stroke-width="1.3" />
                                                <path
                                                    d="M5.2041 11.4092C5.22954 11.4041 5.2933 11.4126 5.34375 11.4502L5.34863 11.4531C6.41552 12.2405 7.68474 12.6464 8.99902 12.6465C10.3135 12.6465 11.5834 12.2407 12.6504 11.4531L12.6553 11.4502C12.6717 11.4383 12.7412 11.4086 12.8506 11.418C14.4691 11.6454 15.9118 12.559 16.8516 13.9482L16.8555 13.9531C17.0155 14.1843 17.152 14.4246 17.2607 14.6719C17.1428 14.8756 17.0147 15.073 16.8711 15.2705L16.7158 15.4775L16.708 15.4883C16.4195 15.8798 16.0836 16.2387 15.7285 16.5938C15.4317 16.8905 15.0922 17.1871 14.7559 17.4395C13.0785 18.6922 11.0607 19.3506 8.97656 19.3506C6.89732 19.3505 4.88498 18.6944 3.20996 17.4473C2.84577 17.1514 2.51261 16.8807 2.22559 16.5938L2.21875 16.5859L2.21094 16.5801L1.95215 16.3242C1.69963 16.0639 1.46736 15.7886 1.24609 15.4883L1.24316 15.4834L0.944336 15.0703C0.86428 14.9535 0.788425 14.8348 0.71875 14.7178C0.835661 14.4569 0.982086 14.185 1.14258 13.9531L1.14355 13.9541L1.15137 13.9424C2.06835 12.5567 3.53571 11.6401 5.16504 11.416L5.18457 11.4131L5.2041 11.4092Z"
                                                    stroke="currentColor" stroke-width="1.3" />
                                            </svg>
                                            {{ $item->author->name }}
                                        </div>
                                        <div class="card-blog-meta-item text text-18" aria-label="Blog Comments">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_9060_14106)">
                                                    <path
                                                        d="M16.1251 3H14.5001V4H16.0001V15H2.00013V4H3.50013V3H1.87513C1.75825 3.00195 1.6429 3.02691 1.53566 3.07345C1.42843 3.11999 1.33141 3.1872 1.25016 3.27125C1.1689 3.35529 1.105 3.45451 1.0621 3.56325C1.0192 3.67199 0.998142 3.78812 1.00013 3.905V15.095C0.998142 15.2119 1.0192 15.328 1.0621 15.4367C1.105 15.5455 1.1689 15.6447 1.25016 15.7288C1.33141 15.8128 1.42843 15.88 1.53566 15.9265C1.6429 15.9731 1.75825 15.998 1.87513 16H16.1251C16.242 15.998 16.3574 15.9731 16.4646 15.9265C16.5718 15.88 16.6688 15.8128 16.7501 15.7288C16.8314 15.6447 16.8953 15.5455 16.9382 15.4367C16.9811 15.328 17.0021 15.2119 17.0001 15.095V3.905C17.0021 3.78812 16.9811 3.67199 16.9382 3.56325C16.8953 3.45451 16.8314 3.35529 16.7501 3.27125C16.6688 3.1872 16.5718 3.11999 16.4646 3.07345C16.3574 3.02691 16.242 3.00195 16.1251 3Z"
                                                        fill="currentColor" />
                                                    <path d="M4 7H5V8H4V7Z" fill="currentColor" />
                                                    <path d="M7 7H8V8H7V7Z" fill="currentColor" />
                                                    <path d="M10 7H11V8H10V7Z" fill="currentColor" />
                                                    <path d="M13 7H14V8H13V7Z" fill="currentColor" />
                                                    <path d="M4 9.5H5V10.5H4V9.5Z" fill="currentColor" />
                                                    <path d="M7 9.5H8V10.5H7V9.5Z" fill="currentColor" />
                                                    <path d="M10 9.5H11V10.5H10V9.5Z" fill="currentColor" />
                                                    <path d="M13 9.5H14V10.5H13V9.5Z" fill="currentColor" />
                                                    <path d="M4 12H5V13H4V12Z" fill="currentColor" />
                                                    <path d="M7 12H8V13H7V12Z" fill="currentColor" />
                                                    <path d="M10 12H11V13H10V12Z" fill="currentColor" />
                                                    <path d="M13 12H14V13H13V12Z" fill="currentColor" />
                                                    <path
                                                        d="M5 5C5.13261 5 5.25979 4.94732 5.35355 4.85355C5.44732 4.75979 5.5 4.63261 5.5 4.5V1.5C5.5 1.36739 5.44732 1.24021 5.35355 1.14645C5.25979 1.05268 5.13261 1 5 1C4.86739 1 4.74021 1.05268 4.64645 1.14645C4.55268 1.24021 4.5 1.36739 4.5 1.5V4.5C4.5 4.63261 4.55268 4.75979 4.64645 4.85355C4.74021 4.94732 4.86739 5 5 5Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M13 5C13.1326 5 13.2598 4.94732 13.3536 4.85355C13.4473 4.75979 13.5 4.63261 13.5 4.5V1.5C13.5 1.36739 13.4473 1.24021 13.3536 1.14645C13.2598 1.05268 13.1326 1 13 1C12.8674 1 12.7402 1.05268 12.6464 1.14645C12.5527 1.24021 12.5 1.36739 12.5 1.5V4.5C12.5 4.63261 12.5527 4.75979 12.6464 4.85355C12.7402 4.94732 12.8674 5 13 5Z"
                                                        fill="currentColor" />
                                                    <path d="M6.5 3H11.5V4H6.5V3Z" fill="currentColor" />
                                                </g>
                                                <defs>
                                                    <clipPath>
                                                        <rect width="18" height="18" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            {{ $item->published_at_formatted }}
                                        </div>
                                        <div class="card-blog-meta-item text text-18">
                                            <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1.125 1.25H16.875V11.375H9L4.5 15.3125V11.375H1.125V1.25Z"
                                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            {{ $item->newsComment->count() }} Comments
                                        </div>

                                    </div>

                                    <h2 class="card-blog-heading heading text-32">
                                        <a href="{{ route('news.detail', ['slug' => $item->slug]) }}"
                                            class="heading text-32">
                                            {{ $item->title }}
                                        </a>
                                    </h2>

                                    <p class="blog-excerpt text text-16">
                                        {!! Str::limit(strip_tags(Purifier::clean(\Summernote::clean($item->body ?? '-'))), 200, '...') !!}
                                    </p>

                                    <div class="buttons">
                                        <a href="{{ route('news.detail', ['slug' => $item->slug]) }}" class="button--cta"
                                            aria-label="See blog details">
                                            Read More
                                            <svg viewBox="0 0 11 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2.16668 0.833333C2.16668 0.61232 2.25448 0.400358 2.41076 0.244078C2.56704 0.0877975 2.779 0 3.00001 0H9.66668C9.88769 0 10.0997 0.0877975 10.2559 0.244078C10.4122 0.400358 10.5 0.61232 10.5 0.833333V7.5C10.5 7.72101 10.4122 7.93297 10.2559 8.08926C10.0997 8.24554 9.88769 8.33333 9.66668 8.33333C9.44567 8.33333 9.2337 8.24554 9.07742 8.08926C8.92114 7.93297 8.83335 7.72101 8.83335 7.5V2.845L1.92251 9.75583C1.76535 9.90763 1.55484 9.99163 1.33635 9.98973C1.11785 9.98783 0.908839 9.90019 0.754332 9.74568C0.599825 9.59118 0.512184 9.38216 0.510285 9.16367C0.508387 8.94517 0.592382 8.73467 0.744181 8.5775L7.65501 1.66667H3.00001C2.779 1.66667 2.56704 1.57887 2.41076 1.42259C2.25448 1.26631 2.16668 1.05435 2.16668 0.833333Z"
                                                    fill="currentColor" />
                                            </svg>
                                            <span class="visually-hidden">To see blog details, click here</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if ($news->count() == 0)
                            <div class="text text-16 fw-500">
                                No data available
                            </div>
                        @endif
                    </div>
                </div>
                @include('news.side-right')
            </div>

            {{ $news->links('vendor.pagination.custom') }}
        </div>
    </div>

@endsection
