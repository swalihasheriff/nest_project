<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- Fonts --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- AdminLTE CSS --}}
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">

    {{-- Page specific CSS --}}
    @stack('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">

    {{-- HEADER --}}
    @include('layouts.header')

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- MAIN CONTENT --}}
    <main class="app-main">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('layouts.footer')

</div>

{{-- SCRIPTS  --}}

{{-- Bootstrap --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


{{-- AdminLTE --}}
<script src="{{ asset('js/adminlte.js') }}"></script>

{{-- ApexCharts (used in dashboard) --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>

{{-- Page specific JS --}}
@stack('scripts')

</body>
</html>
