@extends('guest.layouts.app')

@section('content')
	<!-- breadcrumb-section -->
	<div class="breadcrumb-section" style="background-image: url({{ asset($data['banner'][0]) }})">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<h1>{{ $ref['title'] }}</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- latest news -->
	<div class="latest-news mt-150 mb-150">
		<div class="container">
			<div class="row">
				@if($data['etotal'] > 0)
				@foreach ($data['events'] as $event)
				<div class="col-lg-4 col-md-6">
					<div class="single-latest-news">
						<a href="{{ route('fo.event.show', $event->slug) }}">
							<div class="latest-news-bg news-bg-none" style="background-image: url('{{ asset('storage/' .$event->cover_image) }}')"></div>
						</a>
						<div class="news-text-box">
							<h3><a href="{{ route('fo.event.show', $event->slug) }}">{{ $event->title }}</a></h3>
							<p class="blog-meta">
								<span class="author"><i class="fas fa-user"></i>{{ $event->name }}</span>
								<span class="date"><i class="fas fa-calendar"></i>{{ $event->updated_at }}</span>
							</p>
							<p class="excerpt">{!! $event->description !!}</p>
							<a href="{{ route('fo.event.show', $event->slug) }}" class="read-more-btn">read more <i class="fas fa-angle-right"></i></a>
						</div>
					</div>
				</div>
				@endforeach
				@else
					<h5>Tidak Ada Event yang Tersedia</h5>
				@endif
			</div>
			<div class="row">
			    <div class="col text-center">
			        <nav aria-label="Page navigation">
			            <ul class="pagination justify-content-center">
			                {{ $data['events']->links() }}
			            </ul>
			        </nav>
			    </div>
			</div>
		</div>
	</div>
	<!-- end latest news -->
 
@endsection