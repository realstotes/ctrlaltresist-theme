<#
.SYNOPSIS
    Creates CSS module files for any missing modules.
.DESCRIPTION
    Simplified version that creates basic CSS module files.
.PARAMETER MainCssFile
    Path to the main CSS file with imports.
.PARAMETER BasePath
    Base directory where CSS files should be created.
.EXAMPLE
    .\simple-module-creator.ps1 -MainCssFile "assets/css/main.css" -BasePath "assets/css"
#>

param(
    [Parameter(Mandatory=$true)]
    [string]$MainCssFile,
    
    [Parameter(Mandatory=$true)]
    [string]$BasePath
)

Write-Host "CSS Module Creator" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "Reading from: $MainCssFile" -ForegroundColor Cyan
Write-Host "Creating files in: $BasePath" -ForegroundColor Cyan

# Check if main CSS file exists
if (-not (Test-Path $MainCssFile)) {
    Write-Host "Error: Main CSS file not found at path: $MainCssFile" -ForegroundColor Red
    exit 1
}

# Read the main CSS file
try {
    $content = Get-Content $MainCssFile -Raw
    if (-not $content) {
        Write-Host "Error: Main CSS file is empty." -ForegroundColor Red
        exit 1
    }
}
catch {
    Write-Host "Error reading main CSS file: $_" -ForegroundColor Red
    exit 1
}

# Extract all import statements
$importPattern = "@import\s+'([^']+)'"
$matches = [regex]::Matches($content, $importPattern)

if ($matches.Count -eq 0) {
    Write-Host "No imports found in the main CSS file." -ForegroundColor Yellow
    exit 0
}

Write-Host "Found $($matches.Count) CSS imports" -ForegroundColor Green

# Check each imported file and create if missing
$missingCount = 0
$existCount = 0

foreach ($match in $matches) {
    $importPath = $match.Groups[1].Value.Trim()
    $fullPath = Join-Path $BasePath $importPath
    
    Write-Host "Checking: $importPath" -NoNewline
    
    if (Test-Path $fullPath) {
        Write-Host " - Exists" -ForegroundColor Green
        $existCount++
    }
    else {
        Write-Host " - Missing (will create)" -ForegroundColor Yellow
        
        # Create directory if it doesn't exist
        $directory = [System.IO.Path]::GetDirectoryName($fullPath)
        if (-not (Test-Path $directory)) {
            Write-Host "  Creating directory: $directory" -ForegroundColor Yellow
            New-Item -Path $directory -ItemType Directory -Force | Out-Null
        }
        
        # Get the file name for template selection
        $fileName = [System.IO.Path]::GetFileName($importPath)
        
        # Create basic template based on filename
        $moduleName = [System.IO.Path]::GetFileNameWithoutExtension($importPath).ToUpper().Replace("-", " ")
        $templateContent = "/* =============================================================================
   $moduleName
   ========================================================================== */
/* This is a placeholder module for $fileName */
"
        
        # Create the file
        try {
            Set-Content -Path $fullPath -Value $templateContent -Encoding UTF8
            Write-Host "  Created $fileName" -ForegroundColor Green
            $missingCount++
        }
        catch {
            Write-Host "  Failed to create $fileName : $_" -ForegroundColor Red
        }
    }
}

Write-Host "`nModule creation complete!" -ForegroundColor Cyan
Write-Host "Created $missingCount missing module files." -ForegroundColor Green
Write-Host "$existCount files already existed." -ForegroundColor Cyan
