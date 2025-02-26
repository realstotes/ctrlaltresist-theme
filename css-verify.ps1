<#
.SYNOPSIS
    Verifies that all CSS module files required by main.css exist.
.DESCRIPTION
    This script checks that all CSS files imported by the main CSS file exist, and prints
    a report of any missing files.
.PARAMETER MainCssFile
    Path to the main CSS file that imports modules.
.PARAMETER BasePath
    Base directory where CSS files are located.
.EXAMPLE
    .\css-verify.ps1 -MainCssFile "assets/css/main.css" -BasePath "assets/css"
#>

param(
    [Parameter(Mandatory=$true)]
    [string]$MainCssFile,
    
    [Parameter(Mandatory=$true)]
    [string]$BasePath
)

# Show startup information
Write-Host "CSS Module Verification" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "Checking main CSS file: $MainCssFile" -ForegroundColor Cyan
Write-Host "Base path for CSS modules: $BasePath" -ForegroundColor Cyan
Write-Host "---------------------------------------" -ForegroundColor Cyan

# Check if the main CSS file exists
if (-not (Test-Path $MainCssFile)) {
    Write-Host "Error: Main CSS file not found at path: $MainCssFile" -ForegroundColor Red
    exit 1
}

# Read the main CSS file
try {
    Write-Host "Reading main CSS file..." -ForegroundColor Cyan
    $content = Get-Content $MainCssFile -Raw
    if (-not $content) {
        Write-Host "Error: Main CSS file is empty or could not be read." -ForegroundColor Red
        exit 1
    }
    Write-Host "Successfully read main CSS file." -ForegroundColor Green
} catch {
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

Write-Host "`nFound $($matches.Count) CSS imports to verify:" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan

# Check each imported file
$missingCount = 0
$existCount = 0

foreach ($match in $matches) {
    $importPath = $match.Groups[1].Value.Trim()
    $fullPath = Join-Path $BasePath $importPath
    
    if (Test-Path $fullPath) {
        Write-Host "✓ $importPath" -ForegroundColor Green
        $existCount++
    } else {
        Write-Host "✗ $importPath" -ForegroundColor Red
        $missingCount++
    }
}

Write-Host "`nVerification complete!" -ForegroundColor Cyan
Write-Host "$existCount files found, $missingCount files missing." -ForegroundColor Cyan

if ($missingCount -gt 0) {
    Write-Host "`nMissing files must be created for the CSS to work properly." -ForegroundColor Yellow
    Write-Host "Run the missing-module-creator.ps1 script to generate missing module files." -ForegroundColor Yellow
    exit 1
} else {
    Write-Host "`nAll module files exist. CSS modules are properly set up!" -ForegroundColor Green
}
