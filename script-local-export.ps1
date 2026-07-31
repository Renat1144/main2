[CmdletBinding()]
param(
    [string]$OutputDirectory,
    [string]$ArchiveName,
    [string]$GoogleDriveSitesPath,
    [switch]$UpdateHandoff
)

$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest

function Read-DotEnv {
    param([Parameter(Mandatory = $true)][string]$Path)

    $values = @{}
    Get-Content -LiteralPath $Path -Encoding UTF8 | ForEach-Object {
        if ($_ -match '^\s*([A-Za-z_][A-Za-z0-9_]*)=(.*)\s*$') {
            $value = $matches[2].Trim()
            if ($value.Length -ge 2 -and (
                ($value.StartsWith('"') -and $value.EndsWith('"')) -or
                ($value.StartsWith("'") -and $value.EndsWith("'"))
            )) {
                $value = $value.Substring(1, $value.Length - 2)
            }
            $values[$matches[1]] = $value
        }
    }
    return $values
}

function Invoke-Compose {
    param([Parameter(Mandatory = $true)][string[]]$Arguments)

    & docker @script:composeBaseArguments @Arguments
    if ($LASTEXITCODE -ne 0) {
        throw "Docker Compose command failed with exit code $LASTEXITCODE."
    }
}

function Find-GitExecutable {
    $candidates = [System.Collections.Generic.List[string]]::new()
    $gitCommand = Get-Command git.exe -ErrorAction SilentlyContinue | Select-Object -First 1
    if ($gitCommand -and $gitCommand.Source) {
        $candidates.Add($gitCommand.Source)
    }

    if ($env:ProgramFiles) {
        $candidates.Add((Join-Path $env:ProgramFiles 'Git\cmd\git.exe'))
    }
    $programFilesX86 = [Environment]::GetEnvironmentVariable('ProgramFiles(x86)')
    if ($programFilesX86) {
        $candidates.Add((Join-Path $programFilesX86 'Git\cmd\git.exe'))
    }
    if ($env:LOCALAPPDATA) {
        $candidates.Add((Join-Path $env:LOCALAPPDATA 'Programs\Git\cmd\git.exe'))
        $githubDesktopGit = Get-ChildItem -Path (Join-Path $env:LOCALAPPDATA 'GitHubDesktop\app-*\resources\app\git\cmd\git.exe') -File -ErrorAction SilentlyContinue |
            Sort-Object LastWriteTimeUtc -Descending |
            Select-Object -First 1
        if ($githubDesktopGit) {
            $candidates.Add($githubDesktopGit.FullName)
        }
    }
    if ($env:USERPROFILE) {
        $candidates.Add((Join-Path $env:USERPROFILE 'scoop\apps\git\current\cmd\git.exe'))
        $codexRuntimeGit = Get-ChildItem -Path (Join-Path $env:USERPROFILE '.cache\codex-runtimes\*\dependencies\native\git\cmd\git.exe') -File -ErrorAction SilentlyContinue |
            Sort-Object LastWriteTimeUtc -Descending |
            Select-Object -First 1
        if ($codexRuntimeGit) {
            $candidates.Add($codexRuntimeGit.FullName)
        }
    }

    foreach ($candidate in @($candidates | Select-Object -Unique)) {
        if ($candidate -and (Test-Path -LiteralPath $candidate -PathType Leaf)) {
            return [System.IO.Path]::GetFullPath($candidate)
        }
    }

    return $null
}

function Get-GitProjectFiles {
    param(
        [Parameter(Mandatory = $true)][string]$GitExecutable,
        [Parameter(Mandatory = $true)][string]$WorkingDirectory
    )

    $startInfo = New-Object System.Diagnostics.ProcessStartInfo
    $startInfo.FileName = $GitExecutable
    $startInfo.WorkingDirectory = $WorkingDirectory
    $startInfo.Arguments = '-c core.quotepath=false ls-files -z --cached --others --exclude-standard'
    $startInfo.UseShellExecute = $false
    $startInfo.CreateNoWindow = $true
    $startInfo.RedirectStandardOutput = $true
    $startInfo.RedirectStandardError = $true
    $startInfo.StandardOutputEncoding = New-Object System.Text.UTF8Encoding($false)
    $startInfo.StandardErrorEncoding = New-Object System.Text.UTF8Encoding($false)

    $process = New-Object System.Diagnostics.Process
    $process.StartInfo = $startInfo
    if (-not $process.Start()) {
        throw 'Git could not start to list the current project files.'
    }

    $standardOutputTask = $process.StandardOutput.ReadToEndAsync()
    $standardErrorTask = $process.StandardError.ReadToEndAsync()
    $process.WaitForExit()
    $standardOutput = $standardOutputTask.GetAwaiter().GetResult()
    $standardError = $standardErrorTask.GetAwaiter().GetResult().Trim()
    $exitCode = $process.ExitCode
    $process.Dispose()

    if ($exitCode -ne 0) {
        if ($standardError) {
            throw "Git could not list the current project files: $standardError"
        }
        throw "Git could not list the current project files (exit code $exitCode)."
    }

    return @($standardOutput -split "`0" | Where-Object { $_ })
}

function Find-GoogleDriveSitesPath {
    param([string]$ExplicitPath)

    if ($ExplicitPath) {
        return [System.IO.Path]::GetFullPath($ExplicitPath)
    }

    $candidates = @(
        'G:\Мой диск\sites',
        'G:\My Drive\sites',
        'G:\sites',
        'G:\Мой диск\Codex Drive',
        'G:\My Drive\Codex Drive',
        'G:\Codex Drive'
    )
    if ($env:CODEX_DRIVE_PATH) {
        $candidates += $env:CODEX_DRIVE_PATH
    }
    if ($env:GOOGLE_DRIVE_SITES_PATH) {
        $candidates += $env:GOOGLE_DRIVE_SITES_PATH
    }

    $fileSystemDrives = @(Get-PSDrive -PSProvider FileSystem -ErrorAction SilentlyContinue)
    foreach ($drive in $fileSystemDrives) {
        $isGoogleDrive = ($drive.Description -like '*Google Drive*') -or ($drive.DisplayRoot -like '*Google Drive*')
        if ($isGoogleDrive -or $drive.Name -eq 'G') {
            $candidates += (Join-Path $drive.Root 'Мой диск\sites')
            $candidates += (Join-Path $drive.Root 'My Drive\sites')
            $candidates += (Join-Path $drive.Root 'sites')
            $candidates += (Join-Path $drive.Root 'Мой диск\Codex Drive')
            $candidates += (Join-Path $drive.Root 'My Drive\Codex Drive')
            $candidates += (Join-Path $drive.Root 'Codex Drive')
            $topLevelFolders = @(Get-ChildItem -LiteralPath $drive.Root -Directory -Force -ErrorAction SilentlyContinue)
            foreach ($topLevelFolder in $topLevelFolders) {
                $candidates += (Join-Path $topLevelFolder.FullName 'sites')
                $candidates += (Join-Path $topLevelFolder.FullName 'Codex Drive')
            }
        }
    }

    $candidates += (Join-Path $env:USERPROFILE 'Google Drive\sites')
    $candidates += (Join-Path $env:USERPROFILE 'My Drive\sites')
    $candidates += (Join-Path $env:USERPROFILE 'Мой диск\sites')
    $candidates += (Join-Path $env:USERPROFILE 'Google Drive\Codex Drive')
    $candidates += (Join-Path $env:USERPROFILE 'My Drive\Codex Drive')
    $candidates += (Join-Path $env:USERPROFILE 'Мой диск\Codex Drive')

    $uniqueCandidates = @($candidates | Select-Object -Unique)
    foreach ($candidate in $uniqueCandidates) {
        if ($candidate -and (Test-Path -LiteralPath $candidate -PathType Container)) {
            return [System.IO.Path]::GetFullPath($candidate)
        }
    }

    throw 'Google Drive transfer folder "sites" was not found. Start Google Drive Desktop or pass -GoogleDriveSitesPath with the local path to that folder.'
}

function Add-HandoffSnapshot {
    param(
        [Parameter(Mandatory = $true)][string]$Path,
        [Parameter(Mandatory = $true)][string]$ComposeProjectName,
        [string]$SiteUrl
    )

    if (-not (Test-Path -LiteralPath $Path -PathType Leaf)) {
        throw 'PROJECT_HANDOFF.md is missing. Export was stopped before the archive was created.'
    }

    $timestamp = (Get-Date).ToUniversalTime().ToString("yyyy-MM-dd HH:mm:ss 'UTC'")
    $entry = @"

### $timestamp — Windows

- Действие: запущено штатное завершение работы перед созданием приватного архива переноса.
- Docker Compose project: ``$ComposeProjectName``.
- Локальный адрес WordPress: ``$SiteUrl``.
- Архив считается готовым только после появления ZIP и соответствующего файла ``.sha256``.
"@
    Add-Content -LiteralPath $Path -Value $entry -Encoding UTF8
}

$projectPath = Split-Path -Parent $MyInvocation.MyCommand.Path
$envPath = Join-Path $projectPath '.env'
$composePath = Join-Path $projectPath 'docker-compose.yml'
$uploadsPath = Join-Path $projectPath 'wp-content\uploads'
$projectFolderName = Split-Path $projectPath -Leaf

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    throw 'Docker Desktop was not found. Install and start Docker Desktop, then run the export again.'
}
if (-not (Test-Path -LiteralPath $envPath -PathType Leaf)) {
    throw 'The local .env file was not found. Start the project once or create .env from .env.example.'
}
if (-not (Test-Path -LiteralPath $composePath -PathType Leaf)) {
    throw 'docker-compose.yml was not found next to this script.'
}

$settings = Read-DotEnv -Path $envPath
$composeProjectName = if ($settings.ContainsKey('COMPOSE_PROJECT_NAME') -and $settings['COMPOSE_PROJECT_NAME']) {
    $settings['COMPOSE_PROJECT_NAME']
} else {
    $projectFolderName
}

$script:composeBaseArguments = @(
    'compose',
    '--project-name', $composeProjectName,
    '--env-file', $envPath,
    '--file', $composePath
)

if ($UpdateHandoff) {
    $handoffPath = Join-Path $projectPath 'PROJECT_HANDOFF.md'
    $handoffSiteUrl = if ($settings.ContainsKey('WP_SITE_URL')) { $settings['WP_SITE_URL'] } else { '' }
    Add-HandoffSnapshot -Path $handoffPath -ComposeProjectName $composeProjectName -SiteUrl $handoffSiteUrl
}

$usingGoogleDrive = -not [bool]$OutputDirectory
if ($usingGoogleDrive) {
    $sitesPath = Find-GoogleDriveSitesPath -ExplicitPath $GoogleDriveSitesPath
    $OutputDirectory = $sitesPath
}
$OutputDirectory = [System.IO.Path]::GetFullPath($OutputDirectory)
New-Item -ItemType Directory -Path $OutputDirectory -Force | Out-Null

if (-not $ArchiveName) {
    $ArchiveName = '{0}-{1}-windows.zip' -f $projectFolderName, (Get-Date -Format 'dd-MM-yyyy-HHmmss')
}
if ($ArchiveName -ne [System.IO.Path]::GetFileName($ArchiveName) -or -not $ArchiveName.EndsWith('.zip', [System.StringComparison]::OrdinalIgnoreCase)) {
    throw 'ArchiveName must be a plain file name ending in .zip.'
}

$archivePath = Join-Path $OutputDirectory $ArchiveName
$uploadingArchivePath = $archivePath + '.uploading'
if (Test-Path -LiteralPath $archivePath) {
    throw "The archive already exists: $archivePath"
}

$temporaryRoot = Join-Path $projectPath 'backups'
New-Item -ItemType Directory -Path $temporaryRoot -Force | Out-Null
$temporaryPath = Join-Path $temporaryRoot ('.transfer-temp-export-' + [Guid]::NewGuid().ToString('N'))
$workingArchivePath = Join-Path $temporaryRoot ('.transfer-build-' + [Guid]::NewGuid().ToString('N') + '.zip')
$containerDump = '/tmp/wordpress-site2-export-{0}.sql' -f [Guid]::NewGuid().ToString('N')
$containerDumpCreated = $false
New-Item -ItemType Directory -Path $temporaryPath | Out-Null

try {
    Write-Host 'Starting the WordPress database...'
    Invoke-Compose -Arguments @('up', '-d', 'db', 'wordpress')

    $databaseReady = $false
    $deadline = (Get-Date).AddMinutes(3)
    do {
        $previousErrorActionPreference = $ErrorActionPreference
        $ErrorActionPreference = 'SilentlyContinue'
        & docker @script:composeBaseArguments exec -T db sh -lc 'mariadb --skip-column-names --batch --user="$MARIADB_USER" --password="$MARIADB_PASSWORD" "$MARIADB_DATABASE" -e SELECT@@version >/dev/null 2>&1' *> $null
        $databaseCheckExitCode = $LASTEXITCODE
        $ErrorActionPreference = $previousErrorActionPreference
        if ($databaseCheckExitCode -eq 0) {
            $databaseReady = $true
            break
        }
        Start-Sleep -Seconds 2
    } while ((Get-Date) -lt $deadline)

    if (-not $databaseReady) {
        throw 'The WordPress database did not become ready within three minutes.'
    }

    Write-Host 'Creating a consistent database dump...'
    $dumpCommand = 'mariadb-dump --single-transaction --quick --skip-lock-tables --default-character-set=utf8mb4 --user="$MARIADB_USER" --password="$MARIADB_PASSWORD" "$MARIADB_DATABASE" > "' + $containerDump + '"'
    Invoke-Compose -Arguments @('exec', '-T', 'db', 'sh', '-lc', $dumpCommand)
    $containerDumpCreated = $true

    $databasePath = Join-Path $temporaryPath 'database.sql'
    Invoke-Compose -Arguments @('cp', ('db:' + $containerDump), $databasePath)
    if (-not (Test-Path -LiteralPath $databasePath -PathType Leaf) -or (Get-Item -LiteralPath $databasePath).Length -eq 0) {
        throw 'The database dump is empty. The transfer archive was not created.'
    }

    Copy-Item -LiteralPath $envPath -Destination (Join-Path $temporaryPath 'local.env')

    $wpConfigPath = Join-Path $projectPath 'wp-config.php'
    $wpConfigIncluded = Test-Path -LiteralPath $wpConfigPath -PathType Leaf
    if ($wpConfigIncluded) {
        Copy-Item -LiteralPath $wpConfigPath -Destination (Join-Path $temporaryPath 'wp-config.php')
    }

    $localAdminPath = Join-Path $projectPath '.local-admin.txt'
    $localAdminIncluded = Test-Path -LiteralPath $localAdminPath -PathType Leaf
    if ($localAdminIncluded) {
        Copy-Item -LiteralPath $localAdminPath -Destination (Join-Path $temporaryPath 'local-admin.txt')
    }

    $archiveUploadsPath = Join-Path $temporaryPath 'uploads'
    New-Item -ItemType Directory -Path $archiveUploadsPath | Out-Null
    Write-Host 'Copying WordPress uploads from the container...'
    Invoke-Compose -Arguments @('exec', '-T', 'wordpress', 'sh', '-lc', 'mkdir -p /var/www/html/wp-content/uploads')
    Invoke-Compose -Arguments @('cp', 'wordpress:/var/www/html/wp-content/uploads/.', $archiveUploadsPath)
    $uploadFiles = @(Get-ChildItem -LiteralPath $archiveUploadsPath -Force -Recurse -File -ErrorAction SilentlyContinue)

    $uploadBytes = 0L
    if ($uploadFiles.Count -gt 0) {
        $uploadBytes = [long](($uploadFiles | Measure-Object -Property Length -Sum).Sum)
    }

    Write-Host 'Copying the current project files and work scripts...'
    $archiveProjectFilesPath = Join-Path $temporaryPath 'project-files'
    New-Item -ItemType Directory -Path $archiveProjectFilesPath | Out-Null
    $projectRelativeFiles = [System.Collections.Generic.List[string]]::new()
    Get-ChildItem -LiteralPath $projectPath -Force -File | ForEach-Object {
        $name = $_.Name
        $isCoreRootFile = $name -match '^(index\.php|license\.txt|readme\.html|xmlrpc\.php|wp-.*\.php)$'
        $isPrivateOrGenerated = $name -in @('.env', 'wp-config.php', '.local-admin.txt') -or
            $name -match '(?i)(\.zip(\.sha256)?$|\.sql$|\.log$|\.sqlite3?$|\.db$|\.uploading$)'
        if (-not $isCoreRootFile -and -not $isPrivateOrGenerated) {
            $projectRelativeFiles.Add($name)
        }
    }

    $wpContentPath = Join-Path $projectPath 'wp-content'
    Get-ChildItem -LiteralPath $wpContentPath -Force -Recurse -File -ErrorAction SilentlyContinue | ForEach-Object {
        $relativePath = $_.FullName.Substring($projectPath.Length).TrimStart('\', '/')
        if ($relativePath -notmatch '(^|[\\/])(uploads|cache|upgrade|languages|node_modules)([\\/]|$)') {
            $projectRelativeFiles.Add($relativePath)
        }
    }

    foreach ($relativePath in @($projectRelativeFiles | Sort-Object -Unique)) {
        if (-not $relativePath -or [System.IO.Path]::IsPathRooted($relativePath) -or $relativePath -match '(^|[\\/])\.\.([\\/]|$)') {
            throw "Unsafe project path selected for export: $relativePath"
        }
        $sourcePath = Join-Path $projectPath $relativePath
        $destinationPath = Join-Path $archiveProjectFilesPath $relativePath
        $destinationParent = Split-Path -Parent $destinationPath
        New-Item -ItemType Directory -Path $destinationParent -Force | Out-Null
        Copy-Item -LiteralPath $sourcePath -Destination $destinationPath -Force
    }

    $requiredScripts = @(
        'script-local-export.sh',
        'script-local-export.ps1',
        'script-local-export.command',
        'script-local-export.cmd',
        'script-local-import.sh',
        'script-local-import.ps1',
        'script-local-import.command',
        'script-local-import.cmd',
        'START_WORK.command',
        'START_WORK.cmd',
        'FINISH_WORK.command',
        'FINISH_WORK.cmd'
    )
    foreach ($requiredScript in $requiredScripts) {
        if (-not (Test-Path -LiteralPath (Join-Path $archiveProjectFilesPath $requiredScript) -PathType Leaf)) {
            throw "A required work script was not collected: $requiredScript"
        }
    }
    if (-not (Test-Path -LiteralPath (Join-Path $archiveProjectFilesPath 'PROJECT_HANDOFF.md') -PathType Leaf)) {
        throw 'PROJECT_HANDOFF.md was not collected into the transfer archive.'
    }

    $projectFiles = @(Get-ChildItem -LiteralPath $archiveProjectFilesPath -Force -Recurse -File)
    $projectFilesBytes = 0L
    if ($projectFiles.Count -gt 0) {
        $projectFilesBytes = [long](($projectFiles | Measure-Object -Property Length -Sum).Sum)
    }

    $manifest = [ordered]@{
        format = 'wordpress-site2-transfer'
        formatVersion = 3
        createdAtUtc = (Get-Date).ToUniversalTime().ToString('o')
        sourcePlatform = 'Windows'
        projectFolderName = $projectFolderName
        composeProjectName = $composeProjectName
        siteUrl = if ($settings.ContainsKey('WP_SITE_URL')) { $settings['WP_SITE_URL'] } else { '' }
        databaseFile = 'database.sql'
        uploadsDirectory = 'uploads'
        projectFilesDirectory = 'project-files'
        uploadFileCount = $uploadFiles.Count
        uploadBytes = $uploadBytes
        projectFileCount = $projectFiles.Count
        projectFilesBytes = $projectFilesBytes
        includesEnvironment = $true
        includesWpConfig = $wpConfigIncluded
        includesLocalAdminFile = $localAdminIncluded
        includesProjectFiles = $true
        includesWorkScripts = $true
        includesProjectHandoff = $true
        privateArchive = $true
    }
    $manifest | ConvertTo-Json -Depth 4 | Set-Content -LiteralPath (Join-Path $temporaryPath 'manifest.json') -Encoding UTF8
    $transferFileCount = @(Get-ChildItem -LiteralPath $temporaryPath -Force -Recurse -File).Count

    Write-Host 'Packing the private cross-platform transfer archive...'
    Add-Type -AssemblyName System.IO.Compression
    Add-Type -AssemblyName System.IO.Compression.FileSystem
    $archiveStream = [System.IO.File]::Open(
        $workingArchivePath,
        [System.IO.FileMode]::CreateNew,
        [System.IO.FileAccess]::ReadWrite,
        [System.IO.FileShare]::None
    )
    try {
        $zipArchive = [System.IO.Compression.ZipArchive]::new(
            $archiveStream,
            [System.IO.Compression.ZipArchiveMode]::Create,
            $false
        )
        try {
            [void]$zipArchive.CreateEntry('uploads/')
            [void]$zipArchive.CreateEntry('project-files/')
            Get-ChildItem -LiteralPath $temporaryPath -Force -Recurse -File | ForEach-Object {
                $relativePath = $_.FullName.Substring($temporaryPath.Length).TrimStart('\', '/')
                $portableEntryName = $relativePath.Replace('\', '/')
                [void][System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(
                    $zipArchive,
                    $_.FullName,
                    $portableEntryName,
                    [System.IO.Compression.CompressionLevel]::Optimal
                )
            }
        } finally {
            $zipArchive.Dispose()
        }
    } finally {
        $archiveStream.Dispose()
    }

    $archiveHash = (Get-FileHash -LiteralPath $workingArchivePath -Algorithm SHA256).Hash
    Move-Item -LiteralPath $workingArchivePath -Destination $uploadingArchivePath
    Move-Item -LiteralPath $uploadingArchivePath -Destination $archivePath
    $checksumPath = $archivePath + '.sha256'
    $checksumLine = '{0}  {1}' -f $archiveHash.ToLowerInvariant(), $ArchiveName
    Set-Content -LiteralPath $checksumPath -Value $checksumLine -Encoding ASCII -NoNewline
    $archiveItem = Get-Item -LiteralPath $archivePath

    Write-Host ''
    Write-Host 'Transfer archive created successfully:' -ForegroundColor Green
    Write-Host $archiveItem.FullName
    Write-Host ('Size: {0:N1} MB' -f ($archiveItem.Length / 1MB))
    Write-Host "Files packed into the archive: $transferFileCount"
    Write-Host "SHA-256: $archiveHash"
    if ($usingGoogleDrive) {
        Write-Host "Google Drive folder: $OutputDirectory"
        Write-Host 'Wait until Google Drive shows that syncing is complete before importing on the other computer.'
    } else {
        Write-Host "Output folder: $OutputDirectory"
    }
    Write-Warning 'This ZIP contains the database, WordPress accounts and local secrets. Keep it private and never upload it to GitHub.'
} finally {
    if ($containerDumpCreated) {
        & docker @script:composeBaseArguments exec -T db rm -f $containerDump *> $null
    }
    if (Test-Path -LiteralPath $temporaryPath) {
        Remove-Item -LiteralPath $temporaryPath -Recurse -Force
    }
    if (Test-Path -LiteralPath $workingArchivePath) {
        Remove-Item -LiteralPath $workingArchivePath -Force
    }
    if (Test-Path -LiteralPath $uploadingArchivePath) {
        Remove-Item -LiteralPath $uploadingArchivePath -Force
    }
}
