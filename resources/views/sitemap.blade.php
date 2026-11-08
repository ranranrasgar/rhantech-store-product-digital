<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($urls as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
@if($page['lastmod'])
        <lastmod>{{ $page['lastmod'] }}</lastmod>
@endif
    </url>
@endforeach
</urlset>
