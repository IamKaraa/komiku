@extends('user.layout.user-app')

@section('content')

@php
    $genres = [
        'Romance', 'Comedy', 'Action', 'Fantasy', 'Drama',
        'Mystery', 'Slice of Life', 'Adventure', 'Sci-Fi', 'Horror'
    ];
@endphp

<div style="background:#E0E1DD; padding:90px 0; min-height:80vh;">

    <h2 style="
        font-size:32px; 
        font-weight:750; 
        margin-bottom:40px; 
        color:#0D1B2A; 
        text-align:center;
    ">
        Ranking Comic
    </h2>

    <div style="
        max-width:1300px; 
        margin:0 auto;
        background:#778DA9; 
        padding:25px; 
        border-radius:15px; 
        box-shadow:0 4px 12px rgba(0,0,0,0.15);
    ">

        <h3 style="color:#0D1B2A; font-weight:700; margin-bottom:15px;">
            Top Ranking All Genres
        </h3>

        <div style="
            display:flex;
            overflow-x:auto;
            gap:25px;
            padding-bottom:15px;
            scrollbar-width:thin;
        ">
            @for ($i = 1; $i <= 10; $i++)
                @php
                    $genre1 = $genres[array_rand($genres)];
                    $genre2 = $genres[array_rand($genres)];
                    $rating = max(3.5, min(5, 5 - ($i * 0.05)));
                    $reads = rand(50, 300);
                @endphp

                <div style="
                    min-width:230px;
                    background:#E0E1DD;
                    border-radius:12px;
                    padding:15px;
                    box-shadow:0 3px 8px rgba(0,0,0,0.1);
                    border-left:6px solid #1B263B;
                ">

                    <div style="font-size:26px; font-weight:800; color:#1B263B; margin-bottom:10px;">
                        #{{ $i }}
                    </div>

                    <img src="{{ asset('images/comic1'.$i.'.jpg') }}"
                        style="
                            width:100%;
                            height:300px;
                            object-fit:cover;
                            border-radius:10px;
                        ">

                    <div style="
                        font-size:20px;
                        font-weight:700;
                        color:#0D1B2A;
                        margin-top:10px;
                    ">
                        Judul Comic {{ $i }}
                    </div>

                    <div style="font-size:15px; color:#415A77; margin-top:4px;">
                        {{ $genre1 }} • {{ $genre2 }}
                    </div>

                    <div style="font-size:17px; font-weight:600; color:#1B263B; margin-top:6px;">
                        ⭐ {{ number_format($rating, 1) }} • {{ $reads }}k Reads
                    </div>

                </div>
            @endfor
        </div>
    </div>

    @php
        $genreLists = ['Romance', 'Action', 'Fantasy'];
    @endphp

    @foreach ($genreLists as $g)

    <div style="
        max-width:1300px; 
        margin:40px auto 0;
        background:#778DA9; 
        padding:25px; 
        border-radius:15px; 
        box-shadow:0 4px 12px rgba(0,0,0,0.15);
    ">

        <h3 style="color:#0D1B2A; font-weight:700; margin-bottom:15px;">
            {{ $g }} Ranking
        </h3>

        <div style="
            display:flex;
            overflow-x:auto;
            gap:25px;
            padding-bottom:15px;
            scrollbar-width:thin;
        ">
            @for ($i = 1; $i <= 10; $i++)
                @php
                    $genre2 = $genres[array_rand($genres)];
                    $rating = max(3.6, min(5, 5 - ($i * 0.04)));
                    $reads = rand(60, 350);
                @endphp

                <div style="
                    min-width:230px;
                    background:#E0E1DD;
                    border-radius:12px;
                    padding:15px;
                    box-shadow:0 3px 8px rgba(0,0,0,0.1);
                    border-left:6px solid #1B263B;
                ">

                    <div style="font-size:26px; font-weight:800; color:#1B263B; margin-bottom:10px;">
                        #{{ $i }}
                    </div>

                    <img src="{{ asset('images/'.$g.$i.'.jpg') }}"
                        style="
                            width:100%;
                            height:300px;
                            object-fit:cover;
                            border-radius:10px;
                        ">

                    <div style="
                        font-size:20px;
                        font-weight:700;
                        color:#0D1B2A;
                        margin-top:10px;
                    ">
                        {{ $g }} Comic {{ $i }}
                    </div>

                    <div style="font-size:15px; color:#415A77; margin-top:4px;">
                        {{ $g }} • {{ $genre2 }}
                    </div>

                    <div style="font-size:17px; font-weight:600; color:#1B263B; margin-top:6px;">
                        ⭐ {{ number_format($rating, 1) }} • {{ $reads }}k Reads
                    </div>

                </div>
            @endfor
        </div>

    </div>

    @endforeach

</div>

@endsection
