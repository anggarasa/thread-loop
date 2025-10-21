<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? config('app.name') }}</title>

<x-seo
    :title="$title ?? null"
    :description="$metaDescription ?? null"
    :image="$metaImage ?? null"
    :type="$metaType ?? null"
    :noindex="$noindex ?? false"
    :canonical="$canonical ?? null"
    :url="$metaUrl ?? null"
    :published-time="$publishedTime ?? null"
    :modified-time="$modifiedTime ?? null"
    :author-name="$authorName ?? null"
>
</x-seo>

<link rel="icon" href="/assets/images/logo-ThreadLoop2-aplikasi.svg" sizes="any">
<link rel="icon" href="/assets/images/logo-ThreadLoop2-aplikasi.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/assets/images/logo-ThreadLoop2-aplikasi.svg">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
