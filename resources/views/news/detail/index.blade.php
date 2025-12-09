@extends('layouts.home.template')
@section('title', $news->title . ' - LPM UII Dalwa')
@push('meta')
    <meta property="og:title" content="{{ $news->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($news->body), 150) }}">
    <meta property="og:image" content="{{ $news->url_image }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:type" content="article">
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-…" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')

    <div style="height: 50px"></div>

    <!-- Blog List -->
    <div class="page-blog-details mt-100" style="margin-bottom: 50px">
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
                    <div class="blog-details">
                        <div class="card-blog-list" data-aos="fade-up">
                            <div class="card-blog-list-media radius18">
                                <div class="media">
                                    <img src="{{ $news->url_image }}" alt="blog image" width="1000" height="707"
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
                                        {{ $news->author->name }}
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
                                                <clipPath id="clip0_9060_14106">
                                                    <rect width="18" height="18" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        {{ $news->published_at_formatted }}
                                    </div>
                                    <a class="card-blog-meta-item text text-18" href="#blog-comments">
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.125 1.25H16.875V11.375H9L4.5 15.3125V11.375H1.125V1.25Z"
                                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{ $news->newsComment->count() }} Comments

                                    </a>

                                </div>

                                <h2 class="card-blog-heading heading text-50">
                                    {{ $news->title }}
                                </h2>

                                <div class="blog-description">
                                    {!! strip_tags($news->body, '<table><thead><tbody><tr><th><td><div><a><p><ul><ol><li><img><strong><em><b><i>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="blog-share" data-aos="fade-up">
                        <div class="blog-share-item">
                            <h2 class="label heading text-16 fw-500">Categories:</h2>
                            <ul class="sidebar-tags list-unstyled">
                                @foreach ($news->categories as $item)
                                    <li>
                                        <a class="subheading subheading-bg text-18"
                                            href="{{ route('news.index', ['category' => $item->name]) }}" aria-label="tag">
                                            {{ ucfirst($item->name) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @php
                            $shareUrl = $news->url ?? request()->fullUrl();
                            $encodedUrl = urlencode($shareUrl);
                            $shareTitle = urlencode($news->title ?? ($title ?? config('app.name')));
                        @endphp

                        <style>
                            /* Semua ikon di dalam .blog-share-item jadi hitam */
                            .blog-share-item .social-icons i {
                                color: #000;
                                /* warna hitam */
                                font-size: 1.2rem;
                                /* atur ukuran jika perlu */
                            }

                            .blog-share-item .social-icons button {
                                background: none;
                                border: none;
                                padding: 0;
                                cursor: pointer;
                            }
                        </style>

                        <div class="blog-share-item">
                            <h2 class="label heading text-16 fw-500">Share:</h2>

                            <ul class="social-icons list-unstyled d-flex gap-3">
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
                                        target="_blank" rel="noopener">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encodedUrl }}"
                                        target="_blank" rel="noopener">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $shareTitle }}"
                                        target="_blank" rel="noopener">
                                        <i class="fab fa-x-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $encodedUrl }}"
                                        target="_blank" rel="noopener">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/uii_dalwa" target="_blank" rel="noopener">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                                {{-- Tombol copy link --}}
                                <li>
                                    <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $shareUrl }}'); this.innerHTML='<i class=&quot;fas fa-clipboard-check&quot;></i>'; setTimeout(()=>this.innerHTML='<i class=&quot;fas fa-link&quot;></i>',1500)">
                                        <i class="fas fa-link"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- <div class="comments-section scroll-margin" id="blog-comments">
                        <h2 class="comment-section-heading heading text-36" data-aos="fade-up">
                            {{ $news->newsComment->count() }} Comments
                        </h2>
                        <ul class="comments-area list-unstyled">
                            @foreach ($news->newsComment->whereNull('parent_comment_id') as $item)
                                <li class="comments-item" data-aos="fade-up" id="comment_{{ $item->id }}">
                                    <div class="commentator-img">
                                        <img src="{{ asset('home') }}/assets/img/favicon.png"
                                            style="height: 100%;object-fit:contain" alt="image" width="110"
                                            height="110" loading="lazy" />
                                    </div>

                                    <div class="comment-details w-100">
                                        <div class="comments-top">
                                            <div class="comments-meta">
                                                <div class="comment-date text text-16">
                                                    {{ $item->comment_date }}
                                                </div>
                                                <h2 class="commentator-name heading text-22">
                                                    {{ $item->name }}
                                                </h2>
                                            </div>
                                            <div class="button-reply text text-16 fw-500"
                                                onclick="replyComment({{ $item->id }}, '{{ $item->name }}')">
                                                <svg viewBox="0 0 18 14" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9.16927 13.6615L0.835938 6.99479L9.16927 0.328125V4.49479C13.7716 4.49479 17.5026 8.22579 17.5026 12.8281C17.5026 13.0555 17.4935 13.2809 17.4756 13.5037C16.2197 11.12 13.7176 9.49479 10.8359 9.49479H9.16927V13.6615Z"
                                                        fill="currentColor" />
                                                </svg>
                                                Reply
                                            </div>
                                        </div>
                                        <p class="comment-bottom text text-16">
                                            {{ $item->content }}
                                        </p>
                                    </div>
                                </li>
                                @foreach ($item->replies as $itemReply)
                                    <li class="comments-item replied-item" data-aos="fade-up"
                                        id="comment_{{ $itemReply->id }}">
                                        <div class="commentator-img">
                                            <img src="{{ asset('home') }}/assets/img/favicon.png"
                                                style="height: 100%;object-fit:contain" alt="image" width="110"
                                                height="110" loading="lazy" />
                                        </div>

                                        <div class="comment-details w-100">
                                            <div class="comments-top">
                                                <div class="comments-meta">
                                                    <div class="comment-date text text-16">
                                                        {{ $itemReply->comment_date }}
                                                    </div>
                                                    <h2 class="commentator-name heading text-22">
                                                        {{ $itemReply->name }}
                                                    </h2>
                                                </div>
                                                <div class="button-reply text text-16 fw-500"
                                                    onclick="replyComment({{ $item->id }}, '{{ $itemReply->name }}')">
                                                    <svg viewBox="0 0 18 14" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.16927 13.6615L0.835938 6.99479L9.16927 0.328125V4.49479C13.7716 4.49479 17.5026 8.22579 17.5026 12.8281C17.5026 13.0555 17.4935 13.2809 17.4756 13.5037C16.2197 11.12 13.7176 9.49479 10.8359 9.49479H9.16927V13.6615Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                    Reply
                                                </div>
                                            </div>
                                            <p class="comment-bottom text text-16">
                                                {{ $itemReply->content }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>

                    <div class="comments-form">
                        <div class="comments-form-headings">
                            <div class="mb-3 d-none" id="reply">
                                <h5>Reply for comment: <span class="color-base" id="name-reply">Tes</span>
                                    <div class="float-end">
                                        <div class="button-reply text text-16 fw-500" onclick="cancelReply()">
                                            <!-- Ikon X -->
                                            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16">
                                                <path d="M1 1L15 15M15 1L1 15" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" />
                                            </svg>
                                            Cancel Reply
                                        </div>
                                    </div>
                                </h5>
                            </div>
                            <h2 class="heading text-36" data-aos="fade-up">
                                Leave a reply
                            </h2>
                            <p class="text text-16" data-aos="fade-up">
                                Your email address will not be published. Required fields
                                are marked *
                            </p>
                        </div>
                        <form action="{{ route('news.storeComment', ['news' => $news]) }}" method="POST"
                            class="form contact-form" id="form-reply" onsubmit="disableSubmit(this)">
                            @csrf
                            <input type="hidden" name="parent_comment_id">
                            <div class="field w-half" data-aos="fade-up">
                                <label for="CommentFormname" class="visually-hidden">
                                    Your Name
                                </label>
                                <input id="CommentFormname" class="text text-16" type="text" placeholder="Your Name*"
                                    name="name" required />
                            </div>
                            <div class="field w-half" data-aos="fade-up">
                                <label for="CommentFormemail" class="visually-hidden">
                                    Your Email
                                </label>
                                <input id="CommentFormemail" class="text text-16" type="text"
                                    placeholder="Your Email*" name="email" required />
                            </div>
                            <div class="field" data-aos="fade-up">
                                <label for="CommentFormbody" class="visually-hidden">
                                    Type your message
                                </label>
                                <textarea id="CommentFormbody" class="text text-16" rows="4" placeholder="Type your message" name="content"
                                    required></textarea>
                            </div>
                            <div class="form-button" data-aos="fade-up">
                                <button type="submit" class="button button--primary" aria-label="Post Comment">
                                    Post Comment
                                    <span class="svg-wrapper">
                                        <svg class="icon-20" width="20" height="20" viewBox="0 0 20 20"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M13.3365 7.84518L6.16435 15.0173L4.98584 13.8388L12.158 6.66667H5.83652V5H15.0032V14.1667H13.3365V7.84518Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div> --}}
                </div>
                @include('news.side-right')
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function replyComment(newsId, name) {
            document.getElementById('reply').classList.remove('d-none');
            document.querySelector('#form-reply [name="parent_comment_id"]').value = newsId;
            document.getElementById('name-reply').textContent = name.toUpperCase();
        }

        function cancelReply() {
            document.getElementById('reply').classList.add('d-none');
            document.querySelector('#form-reply [name="parent_comment_id"]').value = '';
        }
    </script>
@endpush
