{{-- Basic --}}

@isset($description)
    <meta name="description" content="{{ $description }}">
@endisset

@if (isset($keywords) && is_array($keywords))
    <meta name="keywords" content="{{ implode(', ', $keywords) }}">
@endif

{{-- Open Graph --}}

<meta property="og:type" content="website">
<meta property="og:url" content="{{ Request::url() }}">
@isset($title)
    <meta property="og:title" content="{{ $title }}">
@endisset
@isset($description)
    <meta property="og:description" content="{{ $description }}">
@endisset
@isset($imageUrl)
    <meta property="og:image" content="{{ $imageUrl }}">
    {{-- Next tags are optional but recommended --}}
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
@endisset
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="ru_RU"> {{-- TODO: Change in case of localisation --}}

{{-- Twitter Card --}}

<meta name="twitter:card" content="summary_large_image">
@if (config('services.twitter.site_account') !== null)
    <meta name="twitter:site" content="{{ '@' . \ltrim(config('services.twitter.site_account'), '@') }}">
    {{--<meta name="twitter:creator" content="@individual_account">--}}
@endif
<meta name="twitter:url" content="{{ Request::url() }}">
@isset($title)
    <meta name="twitter:title" content="{{ $title }}">
@endisset
@isset($description)
    <meta name="twitter:description" content="{{ str_limit_careful($description, 199, '…') }}"> {{-- Max length: 200 --}}
@endisset
@isset($imageUrl)
    <meta name="twitter:image" content="{{ $imageUrl }}">
@endisset
