<div class="td-sidebar">
    <!-- Author Start-->
    <div class="widget widget_author">
        <div class="thumb">
            @if (@$author->photo)
                <img src="{{ asset('photo') }}/{{ rawurlencode($author->photo) }}" width="323px" height="300px"
                    alt="blog">
            @else
                <img src="{{ asset('assets/img/widget/author.png') }}" width="323px" height="300px" alt="blog">
            @endif
        </div>
        <div class="details">
            <h4>ABOUT AUTHOR</h4>
            <p class="mb-4">Welcome! Here you can easily type in Arabic using our LPM.</p>
            <img src="{{ asset('assets/img/widget/signature.png') }}" alt="blog">
        </div>
    </div>
    <!-- Author Start -->

    <!-- Category Start -->
    <div class="widget widget_catagory">
        <h4 class="widget-title">Unit</h4>
        <ul class="catagory-items">
            @foreach ($unit as $item)
                <li><a href="{{ route('activity.index', ['unit' => $item->name]) }}"><i class="fas fa-caret-right"></i>
                        {{ $item->name }}
                        <span>{{ $item->activityUnit->count() }}</span></a>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- Category End -->
    <!-- Recent News Start -->
    <div class="widget widget-recent-post">
        <h4 class="widget-title">Recent News</h4>
        <ul>
            @foreach ($recentActivity as $item)
                <li>
                    <div class="media">
                        <div class="media-left">
                            <img src={{ $item->image ? asset('storage/image-activity') . '/' . rawurlencode($item->image) : asset('assets/img/widget/1.png') }}
                                alt="blog" style="width: 89px; height: 92px; object-fit: cover;">
                        </div>
                        <div class="media-body align-self-center">
                            <h6 class="title"><a href="blog-details.html">{{ $item->title }}</a></h6>
                            <div class="post-info"><i
                                    class="far fa-calendar-alt"></i><span>{{ Carbon::create($item->created_at)->format('d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- Recent News End -->

    <!-- Tags Start -->
    <div class="widget widget_tag_cloud mb-0">
        <h4 class="widget-title">Tags</h4>
        <div class="tagcloud">
            @foreach ($unit as $item)
                <a href="{{ route('activity.index', ['unit' => $item->name]) }}">{{ $item->name }}</a>
            @endforeach
        </div>
    </div>
    <!-- Tags End -->
</div>
