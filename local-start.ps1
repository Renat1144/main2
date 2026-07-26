$ErrorActionPreference = 'Stop'

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    throw 'Docker Desktop was not found. Install and start Docker Desktop, then run this script again.'
}

$projectPath = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location -LiteralPath $projectPath

Get-Content -Encoding UTF8 -LiteralPath (Join-Path $projectPath '.env') | ForEach-Object {
    if ($_ -match '^([A-Z0-9_]+)=(.*)$') {
        [Environment]::SetEnvironmentVariable($matches[1], $matches[2], 'Process')
    }
}

docker compose up -d db wordpress

$deadline = (Get-Date).AddMinutes(4)
do {
    Start-Sleep -Seconds 3
    try {
        $response = Invoke-WebRequest -Uri 'http://localhost:8080/wp-admin/install.php' -UseBasicParsing -TimeoutSec 5
        $ready = $response.StatusCode -eq 200
    } catch {
        $ready = $false
    }
} until ($ready -or (Get-Date) -ge $deadline)

if (-not $ready) {
    throw 'WordPress did not become available in four minutes. Check Docker Desktop status.'
}

$previousErrorActionPreference = $ErrorActionPreference
$ErrorActionPreference = 'Continue'
docker compose run --rm wpcli core is-installed 2>$null
$coreInstalled = $LASTEXITCODE -eq 0
$ErrorActionPreference = $previousErrorActionPreference

if (-not $coreInstalled) {
    docker compose run --rm wpcli core install `
        --url=$env:WP_SITE_URL `
        --title=$env:WP_SITE_TITLE `
        --admin_user=$env:WP_ADMIN_USER `
        --admin_password=$env:WP_ADMIN_PASSWORD `
        --admin_email=$env:WP_ADMIN_EMAIL `
        --locale=ru_RU `
        --skip-email
}

docker compose run --rm wpcli theme activate custom-site-theme
docker compose run --rm wpcli rewrite structure '/%postname%/'
docker compose run --rm wpcli rewrite flush

Write-Host 'Site: http://localhost:8080'
Write-Host 'Admin panel: http://localhost:8080/wp-admin/'
