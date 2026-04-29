@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h2 class="text-2xl font-bold mb-6 text-green-800">Data Pengunjung Website</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Total Kunjungan (Hits)</p>
            <h3 class="text-3xl font-bold">{{ number_format($totalHits, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Pengunjung Unik</p>
            <h3 class="text-3xl font-bold">{{ number_format($uniqueUsers, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Negara</th>
                        <th class="px-6 py-4">Browser / Device</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($visitors as $v)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $v->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 font-mono text-blue-600">{{ $v->ip_address }}</td>
                        <td class="px-6 py-4">
                            @if($v->country == 'United States')
                                🇺🇸 <span class="text-red-500">Bot/US</span>
                            @else
                                🇮🇩 {{ $v->country ?? 'Unknown' }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            <span class="truncate block w-64" title="{{ $v->browser }}">
                                {{ $v->browser ?? 'Empty (Potential Bot)' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-gray-50">
            {{ $visitors->links() }}
        </div>
    </div>
</div>
@endsection