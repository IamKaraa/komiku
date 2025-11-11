@extends('layouts.admin')

@section('title', 'Dashboard') {{-- This title is now used for the browser tab, not the header --}}

@section('content')
<!-- Welcome Card -->
<div class="bg-blue-500 dark:bg-blue-800 text-white p-6 rounded-xl shadow-lg mb-6">
    <h3 class="text-xl font-bold">Halo, Admin!</h3>
    <p class="mt-1">Selamat datang kembali! Mari kita lihat perkembangan komikmu hari ini dan tetap semangat berkarya!</p>
</div>

<!-- Brief Statistic -->
<h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3">Brief Statistic</h3>
<div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    <!-- Stat Cards -->
    <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-blue-100 dark:bg-blue-900/50 p-4 rounded-xl shadow-lg transition-transform transform hover:scale-105">
            <div class="flex items-center">
                <i data-lucide="book-open" class="w-8 h-8 text-blue-500 dark:text-blue-400"></i>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Comic</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalComics) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-green-100 dark:bg-green-900/50 p-4 rounded-xl shadow-lg transition-transform transform hover:scale-105">
            <div class="flex items-center">
                <i data-lucide="users" class="w-8 h-8 text-green-500 dark:text-green-400"></i>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-300">Active User</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($activeUsers) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-[#778DA9] dark:bg-slate-600 text-white p-4 rounded-xl shadow-lg transition-transform transform hover:scale-105">
            <div class="flex items-center">
                <i data-lucide="user-check" class="w-8 h-8 text-white"></i>
                <div class="ml-4">
                    <p class="text-sm dark:text-gray-200">User Pending Approval</p>
                    <p class="text-2xl font-bold dark:text-white">{{ number_format($pendingUsers) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Comics Card -->
    <div class="bg-pink-100 dark:bg-pink-900/50 p-4 rounded-xl shadow-lg flex flex-col items-center justify-center transition-transform transform hover:scale-105">
        <p class="font-bold text-gray-800 dark:text-gray-200 mb-2">Popular Comics</p>
        <img src="{{ $popularComic->thumbnail_path }}" alt="{{ $popularComic->title }}" class="w-24 h-32 object-cover rounded-md shadow-md">
        <p class="mt-2 text-base font-semibold text-gray-700 dark:text-gray-300">{{ Str::limit($popularComic->title, 15) }}</p>
        <p class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($popularComic->views_count) }}</p>
    </div>
</div>

<!-- Summary Graph -->
<div class="mt-8">
    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3">Summary Graph</h3>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300">Genre popularity</h4>
        <div id="genre-chart"></div>
    </div>
</div>

<!-- Daily Comic View -->
<div class="mt-8">
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300">Daily Comic View</h4>
        <div id="daily-view-chart"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const htmlEl = document.documentElement;
    let genreChart, dailyViewChart;

    function getTheme() {
        return htmlEl.classList.contains('dark') ? 'dark' : 'light';
    }

    function getChartOptions(theme) {
        const isDark = theme === 'dark';
        const gridColor = isDark ? '#4A5568' : '#e7e7e7';
        const textColor = isDark ? '#E0E1DD' : '#373d3f';

        const genreOptions = {
            series: [{
                name: 'Total Comics',
                data: {!! json_encode($genreData) !!}
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false },
                foreColor: textColor,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 8,
                },
            },
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: {!! json_encode($genreLabels) !!},
            },
            yaxis: { title: { text: 'Total' } },
            fill: { opacity: 0.85 }, // Sedikit transparansi untuk efek lebih terang
            colors: ['#66BB6A'], // Green 400 - lebih terang dari sebelumnya, tapi masih gelap
            grid: { borderColor: gridColor },
            tooltip: {
                theme: theme,
                y: { formatter: (val) => val + " comics" }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                offsetY: -20
            }
        };

        const dailyViewOptions = {
            series: [{
                name: "New Comics Uploaded",
                data: {!! json_encode($dailyViews->pluck('count')) !!}
            }],
            chart: {
                height: 350,
                type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                },
                toolbar: { show: false },
                foreColor: textColor,
            },
            colors: ['#42A5F5', '#AB47BC'], // Blue 400 dan Purple 400 - lebih terang dari sebelumnya, tapi masih gelap
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 4
            },
            grid: {
                borderColor: gridColor,
                row: {
                    colors: [isDark ? '#2D3748' : '#f3f3f3', 'transparent'],
                    opacity: 0.5
                },
            },
            markers: { size: 1 },
            xaxis: {
                categories: {!! json_encode($dailyViews->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d'))) !!},
                title: { text: 'Days' }
            },
            yaxis: {
                title: { text: 'Total Comics' },
                min: 0
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
            },
            tooltip: { theme: theme }
        };

        return { genreOptions, dailyViewOptions };
    }

    function renderCharts(theme) {
        const { genreOptions, dailyViewOptions } = getChartOptions(theme);

        if (genreChart) {
            genreChart.updateOptions(genreOptions);
        } else {
            genreChart = new ApexCharts(document.querySelector("#genre-chart"), genreOptions);
            genreChart.render();
        }

        if (dailyViewChart) {
            dailyViewChart.updateOptions(dailyViewOptions);
        } else {
            dailyViewChart = new ApexCharts(document.querySelector("#daily-view-chart"), dailyViewOptions);
            dailyViewChart.render();
        }
    }

    // Initial render
    renderCharts(getTheme());

    // Listen for theme changes
    themeToggleBtn.addEventListener('click', function() {
        // We need a slight delay for the class to be updated on the html element
        setTimeout(() => renderCharts(getTheme()), 50);
    });
});
</script>
@endpush