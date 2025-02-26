<#
.SYNOPSIS
    Analyzes CSS file to identify all section names.
.DESCRIPTION
    This script scans a CSS file and identifies all section headers, helping to verify
    the correct section names for extraction.
.PARAMETER SourceFile
    Path to the source CSS file to analyze.
.EXAMPLE
    .\analyze-css.ps1 -SourceFile "assets/css/main.css"
#>

param(
    [Parameter(Mandatory=$true)]
    [string]$SourceFile
)

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

Write-Host "Found $(($matches | Measure-Object).Count) CSS sections:" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan

# Output each section name
$sectionNames = @()
foreach ($match in $matches) {
    $sectionName = $match.Groups[1].Value.Trim()
    $sectionNames += $sectionName
    Write-Host "- $sectionName" -ForegroundColor Green
}

# Output a clean section mapping definition that can be used in the extract script
Write-Host "`nSection mapping template for extract-css-modules.ps1:" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host '$sectionMappings = @{'

foreach ($name in $sectionNames) {
    # Convert section name to a suitable filename
    $filename = $name.ToLower() -replace '\s+', '-'
    Write-Host "    `"$name`" = `"modules/$filename.css`""
}

Write-Host '}'
