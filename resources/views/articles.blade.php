@extends('layouts.app')

@section('content')
     <!-- BAGIAN KONTEN: Artikel Terbaru -->
	<section class="py-16 px-4 md:px-8">
		<div class="container mx-auto">
			<h2 data-key="article" class="text-3xl md:text-4xl font-bold text-center text-green-700 mb-12">Artikel Terbaru</h2>
			
			<!-- Kontainer grid untuk menampilkan kartu artikel -->
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				
				@forelse ($articles as $article)
					<div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:scale-105 flex flex-col">
						
						{{-- Kontainer Media: Menjaga rasio agar SVG dan IMG seragam tanpa memotong konten --}}
						<div class="w-full aspect-video bg-gray-50 rounded-t-xl overflow-hidden flex items-center justify-center">
							@if($article->img_preview)
								{{-- h-full w-full object-contain memastikan gambar tidak terpotong (letterboxing) --}}
								{{-- Jika ingin memenuhi area tanpa peduli potongan, gunakan object-cover --}}
								<img src="{{ asset('storage/' . $article->img_preview) }}" 
									 alt="{{ $article->judul }}" 
									 class="w-full h-full object-cover"
									 onerror="this.src='https://placehold.co/600x400/e5e7eb/374151?text=No+Image'">
							@else
								{{-- SVG sekarang mengikuti tinggi kontainer aspect-video --}}
								<svg 
									xmlns="http://www.w3.org/2000/svg" 
									fill="none" 
									viewBox="0 0 24 24" 
									stroke-width="1" 
									stroke="currentColor" 
									class="w-20 h-20 text-gray-300 transition-colors duration-300 hover:text-gray-400"
								>
									<path 
										stroke-linecap="round" 
										stroke-linejoin="round" 
										d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" 
									/>
								</svg>
							@endif
						</div>

						<div class="p-6">
							<h3 data-key="article_title" class="text-xl font-semibold text-gray-800 mb-2">{{ $article->judul }}</h3>
							<p data-isodateartikel="{{ \Carbon\Carbon::parse($article->tanggal_terbit)->toIso8601String() ?? '' }}" class="tglartikel text-sm text-gray-500 mb-4">...</p>
							<p class="text-gray-600 text-sm leading-relaxed mb-4">{!! Str::limit($article->isi, 100) !!}</p>
							<a href="{{ route('articles.show', $article->id) }}" class="text-green-600 font-semibold hover:underline"><span data-key="article_show">Baca Selengkapnya</a>
						</div>
					</div>
				@empty
					<div class="text-center p-10 col-span-full">
						<p data-key="buatlahartikel" class="text-2xl font-bold text-gray-700 mb-5">Buatlah artikel pada situs ini</p>
						<a href="{{ route('articles.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
							<span data-key="buatartikel">Buat Artikel</span>
						</a>
					</div>
				@endforelse

				{{-- Slot kosong --}}
				@for ($i = count($articles); $i < 3; $i++)
					<div class="bg-gray-200 rounded-xl shadow-lg transition-shadow duration-300">
						<div class="p-6 text-center h-full flex flex-col items-center justify-center min-h-[300px]">
							<h3 class="text-xl font-semibold text-gray-500 mb-2"><span data-key="numberartikel">Artikel</span> {{ $i + 1 }}</h3>
							<p data-key="akanrilis" class="text-sm text-gray-400">Akan segera dirilis...</p>
						</div>
					</div>
				@endfor
			</div>
		</div>
	</section>
@endsection