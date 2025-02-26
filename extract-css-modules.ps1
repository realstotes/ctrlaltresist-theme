<#
.SYNOPSIS
    Extracts CSS sections into modular files.
.DESCRIPTION
    This script extracts CSS sections from a main CSS file into separate modular files
    based on section comments. It creates the necessary directory structure if it doesn't exist.
.PARAMETER SourceFile
    Path to the source CSS file to extract from.
.PARAMETER OutputDir
    Base directory where module files will be created.
.EXAMPLE
    .\extract-css-modules.ps1 -SourceFile "assets/css/main.css" -OutputDir "assets/css"
#>

param(
    [Parameter(Mandatory=$true)]
    [string]$SourceFile,
    
    [Parameter(Mandatory=$true)]
    [string]$OutputDir
)

# Create directory structure if it doesn't exist
$modulesDir = Join-Path $OutputDir "modules"
$themesDir = Join-Path $OutputDir "themes"

if (-not (Test-Path $modulesDir)) {
    Write-Host "Creating modules directory: $modulesDir"
    New-Item -Path $modulesDir -ItemType Directory -Force | Out-Null
}

if (-not (Test-Path $themesDir)) {
    Write-Host "Creating themes directory: $themesDir"
    New-Item -Path $themesDir -ItemType Directory -Force | Out-Null
}

# Define the sections to extract with their target files - updated with EXACT section names from the CSS file
$sectionMappings = @{
    "VARIABLES AND CORE SETTINGS" = "modules/variables.css"
    "RESET AND BASE STYLES" = "modules/reset.css"
    "TOP BAR AND TRENDING SECTION" = "modules/topbar.css"
    "HEADER AND NAVIGATION" = "modules/header.css"
    "FEATURED NEWS SECTION" = "modules/featured-news.css"
    "MAIN CONTENT AREA" = "modules/content.css"
    "SIDEBAR STYLING" = "modules/sidebar.css"
    "FOOTER STYLING" = "modules/footer.css"
    "DARK MODE TOGGLE" = "modules/dark-mode-toggle.css"
    "DARK MODE STYLING" = "themes/dark-theme.css"
    "UTILITIES AND ANIMATIONS" = "modules/utilities.css"
    "RESPONSIVE STYLES" = "modules/responsive.css"
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

# Extract and save each section
$extractCount = 0
foreach ($section in $sectionMappings.Keys) {
    $pattern = "(?s)/\* =+\s*$section\s*=+ \*/(.*?)(/\* =+|$)"
    
    if ($content -match $pattern) {
        $sectionContent = $matches[1].Trim()
        $targetFile = Join-Path $OutputDir $sectionMappings[$section]
        
        # Add the section header
        $moduleContent = @"
/* =============================================================================
   $section
   ========================================================================== */
$sectionContent
"@
        
        try {
            Set-Content -Path $targetFile -Value $moduleContent -Encoding UTF8
            Write-Host "Extracted '$section' to $targetFile" -ForegroundColor Green
            $extractCount++
        } catch {
            Write-Host "Error saving to $targetFile : $_" -ForegroundColor Red
        }
    } else {
        Write-Host "Section '$section' not found in source file" -ForegroundColor Yellow
    }
}

# Create an empty light theme file with default structure
$lightThemeFile = Join-Path $themesDir "light-theme.css"
$lightThemeContent = @"
/* =============================================================================
   LIGHT THEME (DEFAULT)
   ========================================================================== */

/* 
 * This is the default theme. The base styles are already defined in the main stylesheets.
 * This file can be used to add light theme specific overrides if needed.
 */

:root {
    /* Light theme variables already defined in variables.css */
}

/* Add any light theme specific overrides below */

"@

try {
    Set-Content -Path $lightThemeFile -Value $lightThemeContent -Encoding UTF8
    Write-Host "Created light theme file at $lightThemeFile" -ForegroundColor Green
} catch {
    Write-Host "Error creating light theme file: $_" -ForegroundColor Red
}

Write-Host "`nExtraction complete! Extracted $extractCount sections." -ForegroundColor Cyan
Write-Host "Remember to update your main.css to import these modules." -ForegroundColor Cyan

# Create or update main.css with import statements that match your original main.css structure
$mainCssPath = Join-Path $OutputDir "main.css"
$mainCssContent = @"
/* =============================================================================
   CTRL+ALT+RESIST - MAIN STYLESHEET
   ========================================================================== */

/* Import CSS modules */
@import 'modules/variables.css';
@import 'modules/reset.css';
@import 'modules/topbar.css';
@import 'modules/header.css';
@import 'modules/featured-news.css';
@import 'modules/content.css';
@import 'modules/sidebar.css';
@import 'modules/footer.css';
@import 'modules/dark-mode-toggle.css';
@import 'modules/utilities.css';
@import 'modules/responsive.css';

/* Import theme files */
@import 'themes/light-theme.css';
@import 'themes/dark-theme.css';
"@

Write-Host "`nUpdating main.css with import statements..." -ForegroundColor Cyan
try {
    Set-Content -Path $mainCssPath -Value $mainCssContent -Encoding UTF8
    Write-Host "Updated main.css successfully!" -ForegroundColor Green
} catch {
    Write-Host "Error updating main.css: $_" -ForegroundColor Red
}