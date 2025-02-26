<#
.SYNOPSIS
    Analyzes a CSS file and extracts all section names for easier modularization.
.DESCRIPTION
    This script provides a detailed analysis of CSS file sections to help with the extract-css-modules.ps1 script.
.PARAMETER SourceFile
    Path to the source CSS file to analyze.
.EXAMPLE
    .\extract-css-analyzer.ps1 -SourceFile "assets/css/main-original.css"
#>

param(
    [Parameter(Mandatory=$true)]
    [string]$SourceFile
)

# Show startup information
Write-Host "CSS Section Analyzer" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "Analyzing file: $SourceFile" -ForegroundColor Cyan
Write-Host "---------------------------------------" -ForegroundColor Cyan

# Check if the source file exists
if (-not (Test-Path $SourceFile)) {
    Write-Host "Error: Source file not found at path: $SourceFile" -ForegroundColor Red
    exit 1
}

# Read the source file
try {
    $content = Get-Content $SourceFile -Raw
    if (-not $content) {
        Write-Host "Error: Source file is empty or could not be read." -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "Error reading source file: $_" -ForegroundColor Red
    exit 1
}

# Pattern to match all CSS section headers
$pattern = "/\* =+\s*(.*?)\s*=+ \*/"

# Find all matches
$matches = [regex]::Matches($content, $pattern)
$totalSections = $matches.Count

if ($totalSections -eq 0) {
    Write-Host "No CSS section headers found in the file." -ForegroundColor Yellow
    exit 0
}

Write-Host "Found $totalSections CSS sections:" -ForegroundColor Green
Write-Host "=======================================" -ForegroundColor Cyan

# Output each section name
$sectionNames = @()
foreach ($match in $matches) {
    $sectionName = $match.Groups[1].Value.Trim()
    $sectionNames += $sectionName
    Write-Host "- $sectionName" -ForegroundColor Green
}

# Create a section mappings template for extract-css-modules.ps1
Write-Host "`nSection mappings for extract-css-modules.ps1:" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host '$sectionMappings = @{'

foreach ($name in $sectionNames) {
    # Convert section name to a suitable filename
    $filename = $name.ToLower() -replace '\s+', '-'
    
    # Determine appropriate directory (themes vs modules)
    $directory = "modules"
    if ($name -match "THEME|MODE") {
        $directory = "themes"
    }
    
    Write-Host "    `"$name`" = `"$directory/$filename.css`""
}

Write-Host '}'

# Save the generated mappings to a file for easy copy-paste
$outputFile = [System.IO.Path]::GetDirectoryName($SourceFile) + "/section-mappings.txt"
try {
    $mappingOutput = '$sectionMappings = @{' + [Environment]::NewLine
    foreach ($name in $sectionNames) {
        $filename = $name.ToLower() -replace '\s+', '-'
        $directory = "modules"
        if ($name -match "THEME|MODE") {
            $directory = "themes"
        }
        $mappingOutput += "    `"$name`" = `"$directory/$filename.css`"" + [Environment]::NewLine
    }
    $mappingOutput += '}'
    
    Set-Content -Path $outputFile -Value $mappingOutput
    Write-Host "`nSaved section mappings to $outputFile" -ForegroundColor Green
} catch {
    Write-Host "Error saving section mappings: $_" -ForegroundColor Red
}
