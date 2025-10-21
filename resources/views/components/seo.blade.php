@php
    $siteName = config('app.name');
    $computedTitle = $title ?? $siteName;
    $defaultDescription = 'ThreadLoop adalah platform media sosial modern yang dibangun dengan Laravel dan Livewire, memungkinkan pengguna untuk berbagi konten, berinteraksi, dan terhubung dengan pengguna lain di seluruh dunia.';
    $computedDescription = trim($description ?? '') !== '' ? trim($description) : $defaultDescription;
    $computedUrl = $url ?? url()->current();
    $computedCanonical = $canonical ?? $computedUrl;
    $computedType = $type ?? 'website';
    $computedImage = $image ?? asset('assets/images/logo-ThreadLoop2-aplikasi.svg');
    $locale = str_replace('_', '-', app()->getLocale());
@endphp

<meta name="description" content="{{ $computedDescription }}">
@if(!empty($noindex))
<meta name="robots" content="noindex, nofollow">
@endif
<link rel="canonical" href="{{ $computedCanonical }}">

<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $locale }}">
<meta property="og:type" content="{{ $computedType }}">
<meta property="og:title" content="{{ $computedTitle }}">
<meta property="og:description" content="{{ $computedDescription }}">
<meta property="og:url" content="{{ $computedUrl }}">
@if($computedImage)
<meta property="og:image" content="{{ $computedImage }}">
@endif
@if(!empty($publishedTime))
<meta property="article:published_time" content="{{ $publishedTime }}">
@endif
@if(!empty($modifiedTime))
<meta property="article:modified_time" content="{{ $modifiedTime }}">
@endif
@if(!empty($authorName))
<meta name="author" content="{{ $authorName }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $computedTitle }}">
<meta name="twitter:description" content="{{ $computedDescription }}">
@if($computedImage)
<meta name="twitter:image" content="{{ $computedImage }}">
@endif

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $siteName,
    'url' => url('/'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => url('/search') . '?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>

