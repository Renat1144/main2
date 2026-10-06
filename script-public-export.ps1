[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest
# Only anonymous rendered pages and an explicit list of already-public assets.
# Never copy the database, uploads, WordPress users, config or transfer archives.
$projectRoot = $PSScriptRoot
$outputRoot = Join-Path $projectRoot 'public-site'
$origin = 'http://localhost:8080'
$assets = @(
    'wp-content/themes/custom-site-theme/assets/css/site.css',
    'wp-content/themes/custom-site-theme/assets/js/site.js',
    'wp-content/themes/custom-site-theme/assets/images/favicon.svg',
    'wp-content/themes/custom-site-theme/assets/images/photo1.png',
    'wp-content/themes/custom-site-theme/assets/images/photo2.png',
    'wp-content/themes/custom-site-theme/assets/images/photo3.png',
    'wp-content/themes/custom-site-theme/assets/images/photo4.png',
    'wp-content/plugins/project-blocks/assets/home-blocks.css'
)
$pages = @(
    @{ Route = '/'; File = 'index.html'; Prefix = './' },
    @{ Route = '/master1/'; File = 'master1/index.html'; Prefix = '../' }
)
$generated = @()
foreach ($page in $pages) {
    $response = Invoke-WebRequest -Uri ($origin + $page.Route) -UseBasicParsing
    if ($response.StatusCode -ne 200) { throw 'Public page did not return HTTP 200.' }
    $html = $response.Content
    if ($html -match 'id=["'']wpadminbar|wordpress_logged_in|wpApiSettings|_wpnonce|Fatal error|Warning:') {
        throw 'Page contains an authenticated interface or a PHP diagnostic; export stopped.'
    }
    # API/discovery endpoints have no meaning on GitHub Pages.
    $html = [regex]::Replace($html, '<link\b[^>]*(?:wp-json|xmlrpc\.php|rel=["''](?:canonical|shortlink)["''])[^>]*>', '', 'IgnoreCase')
    $html = [regex]::Replace($html, '<script\b[^>]*type=["'']speculationrules["''][^>]*>.*?</script>', '', 'Singleline,IgnoreCase')
    $html = [regex]::Replace($html, '<script\b[^>]*>.*?</script>', {
        param($match)
        if ($match.Value -match 'wp-emoji-settings|_wpemojiSettings') { return '' }
        return $match.Value
    }, 'Singleline,IgnoreCase')
    $html = $html.Replace($origin + '/', $page.Prefix)
    if ($html -match 'localhost|127\.0\.0\.1|wp-login\.php|wp-admin/|wp-config\.php') {
        throw 'Private/local URL found in rendered page.'
    }
    foreach ($match in [regex]::Matches($html, '(?:src|href)=["'']([^"'']+)["'']')) {
        $target = $match.Groups[1].Value.Split('?')[0]
        if ($target -match 'wp-content/|wp-includes/') {
            $asset = $target -replace '^(\./|\.\./)', ''
            if ($assets -notcontains $asset) { throw "Unapproved asset: $asset" }
        }
    }
    $generated += @{ Path = (Join-Path $outputRoot $page.File); Html = $html }
}
foreach ($asset in $assets) {
    $source = Join-Path $projectRoot $asset
    if (-not (Test-Path -LiteralPath $source -PathType Leaf)) { throw "Missing asset: $asset" }
}
foreach ($page in $generated) {
    New-Item -ItemType Directory -Path (Split-Path $page.Path -Parent) -Force | Out-Null
    [IO.File]::WriteAllText($page.Path, $page.Html, [Text.UTF8Encoding]::new($false))
}
foreach ($asset in $assets) {
    $destination = Join-Path $outputRoot $asset
    New-Item -ItemType Directory -Path (Split-Path $destination -Parent) -Force | Out-Null
    Copy-Item -LiteralPath (Join-Path $projectRoot $asset) -Destination $destination -Force
}
Write-Host 'Exported two anonymous public pages and eight approved assets to public-site.'
