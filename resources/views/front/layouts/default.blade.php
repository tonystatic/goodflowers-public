<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" itemscope itemtype="http://schema.org/WebSite" prefix="og: http://ogp.me/ns#">
    <head itemscope itemtype="http://schema.org/WebSite">
        @include('front.layouts.meta')
        <title>
            @section('title')
                {{ config('app.name') }}
            @show
        </title>
        @include('front.layouts.styles')
        @include('front.layouts.head-scripts')
        @stack('head')
    </head>
    <body itemscope itemtype="http://schema.org/WebPage">
        @include('front.layouts.top-scripts')

        @if (Route::currentRouteName() !== 'front.index')
            @include('front.layouts.header')
        @endif

        @include('front.layouts.alerts')

        <div class="main-container{{ Route::currentRouteName() === 'front.index' ? ' home__main-container' : '' }}" role="main">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        @include('front.layouts.scripts')
        @stack('scripts')
    </body>
</html>
