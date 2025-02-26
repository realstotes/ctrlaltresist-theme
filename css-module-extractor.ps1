<#
.SYNOPSIS
    Analyzes and extracts CSS sections, then creates modular files.
.DESCRIPTION
    Simplified script that identifies section names in CSS files and extracts them.
.PARAMETER SourceFile
    Path to the source CSS file to extract from.
.PARAMETER OutputDir
    Base directory where module files will be created.
.EXAMPLE
    .\css-module-extractor.ps1 -SourceFile "assets/css/main-original.css" -OutputDir "assets/css"
#>

param(
    [Parameter(Mandatory=$true)]
    [string]$SourceFile,
    
    [Parameter(Mandatory=$true)]
    [string]$OutputDir
)

Write-Host "CSS Module Extractor" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "Source file: $SourceFile" -ForegroundColor Cyan
Write-Host "Output directory: $OutputDir" -ForegroundColor Cyan

# Create directories
$modulesDir = Join-Path $OutputDir "modules"
$themesDir = Join-Path $OutputDir "themes"

if (-not (Test-Path $modulesDir)) {
    Write-Host "Creating modules directory..." -ForegroundColor Cyan
    New-Item -Path $modulesDir -ItemType Directory -Force | Out-Null
}

if (-not (Test-Path $themesDir)) {
    Write-Host "Creating themes directory..." -ForegroundColor Cyan
    New-Item -Path $themesDir -ItemType Directory -Force | Out-Null
}

# Read source file
try {
    $content = Get-Content $SourceFile -Raw
    if (-not $content) {
        Write-Host "Error: Source file is empty." -ForegroundColor Red
        exit 1
    }
}
catch {
    Write-Host "Error reading source file: $_" -ForegroundColor Red
    exit 1
}

# Find all CSS section headers
$pattern = "/\* =+\s*(.*?)\s*=+ \*/"
$sectionMatches = [regex]::Matches($content, $pattern)

if ($sectionMatches.Count -eq 0) {
    Write-Host "No CSS section headers found." -ForegroundColor Yellow
    exit 0
}

Write-Host "Found $($sectionMatches.Count) CSS sections:" -ForegroundColor Green

# Create section mapping
$sectionList = @()
foreach ($match in $sectionMatches) {
    $sectionName = $match.Groups[1].Value.Trim()
    $sectionList += $sectionName
    Write-Host "- $sectionName" -ForegroundColor Green
}

# Process each section
$extractCount = 0
foreach ($section in $sectionList) {
    # Generate filename from section name
    $filename = $section.ToLower() -replace '\s+', '-'
    
    # Determine directory based on section name
    $directory = "modules"
    if ($section -match "THEME|MODE|DARK|LIGHT") {
        $directory = "themes"
    }
    
    # Full path to output file
    $outputFile = Join-Path $OutputDir "$directory/$filename.css"
    
    # Extract section content
    $sectionPattern = "(?s)/\* =+\s*$section\s*=+ \*/(.*?)(/\* =+|$)"
    if ($content -match $sectionPattern) {
        $sectionContent = $matches[1].Trim()
        
        # Create section file with header
        $moduleContent = @"
/* =============================================================================
   $section
   ========================================================================== */
$sectionContent
"@
        
        try {
            Set-Content -Path $outputFile -Value $moduleContent -Encoding UTF8
            Write-Host "Extracted '$section' to $outputFile" -ForegroundColor Green
            $extractCount++
        } 
        catch {
            Write-Host "Error saving to $outputFile : $_" -ForegroundColor Red
        }
    }
    else {
        Write-Host "Section '$section' found in header but content couldn't be extracted" -ForegroundColor Yellow
    }
}

# Create main.css with imports
$mainCssPath = Join-Path $OutputDir "main.css"
$imports = @()

# Add module imports
$modules = Get-ChildItem -Path $modulesDir -Filter "*.css" | ForEach-Object { "modules/$($_.Name)" }
foreach ($module in $modules) {
    $imports += "@import '$module';"
}

# Add theme imports
$themes = Get-ChildItem -Path $themesDir -Filter "*.css" | ForEach-Object { "themes/$($_.Name)" }
foreach ($theme in $themes) {
    $imports += "@import '$theme';"
}

$mainCssContent = @"
/* =============================================================================
   CTRL+ALT+RESIST - MAIN STYLESHEET
   ========================================================================== */

/* Import CSS modules */
$($imports -join "`n")
"@

Write-Host "`nCreating main.css with imports..." -ForegroundColor Cyan
try {
    Set-Content -Path $mainCssPath -Value $mainCssContent -Encoding UTF8
    Write-Host "Created main.css successfully!" -ForegroundColor Green
} 
catch {
    Write-Host "Error creating main.css: $_" -ForegroundColor Red
}

Write-Host "`nExtraction complete! Extracted $extractCount sections." -ForegroundColor Cyan
