<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($urls as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
@if(!empty($page['lastmod']))
        <lastmod>{{ $page['lastmod'] }}</lastmod>
@endif
@if(!empty($page['changefreq']))
        <changefreq>{{ $page['changefreq'] }}</changefreq>
@endif
@if(!empty($page['priority']))
        <priority>{{ $page['priority'] }}</priority>
@endif
@if(!empty($page['image']))
        <image:image>
            <image:loc>{{ $page['image'] }}</image:loc>
@if(!empty($page['image_title']))
            <image:title>{{ htmlspecialchars($page['image_title'], ENT_XML1, 'UTF-8') }}</image:title>
@endif
        </image:image>
@endif
    </url>
@endforeach
</urlset>
