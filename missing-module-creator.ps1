<#
.SYNOPSIS
    Creates empty CSS module files for any missing modules.
.DESCRIPTION
    This script checks which modules are missing from the imports in main.css
    and creates empty skeleton files for them.
.PARAMETER MainCssFile
    Path to the main CSS file with imports.
.PARAMETER BasePath
    Base directory where CSS files should be created.
.EXAMPLE
    .\missing-module-creator.ps1 -MainCssFile "assets/css/main.css" -BasePath "assets/css"
#>

param(
    [Parameter(Mandatory=$true)]
    [string]$MainCssFile,
    
    [Parameter(Mandatory=$true)]
    [string]$BasePath
)

# Show startup information
Write-Host "CSS Module Creator" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "Reading from: $MainCssFile" -ForegroundColor Cyan
Write-Host "Creating files in: $BasePath" -ForegroundColor Cyan
Write-Host "---------------------------------------" -ForegroundColor Cyan

# Check if the main CSS file exists
if (-not (Test-Path $MainCssFile)) {
    Write-Host "Error: Main CSS file not found at path: $MainCssFile" -ForegroundColor Red
    exit 1
}

# Define template content for different module types
$moduleTemplates = @{
    "variables.css" = "/* =============================================================================
   VARIABLES AND CORE SETTINGS
   ========================================================================== */
:root {
    /* Primary Color Scheme */
    --primary-color: #e63946;      /* Red accent */
    --secondary-color: #1a1a2e;    /* Dark blue/navy */
    --accent-color: #4361ee;       /* Bright blue */
    
    /* Add your variables here */
}";

    "reset.css" = "/* =============================================================================
   RESET AND BASE STYLES
   ========================================================================== */
body {
    margin: 0;
    padding: 0;
    line-height: 1.6;
    font-family: sans-serif;
}

/* Add more reset styles here */";

    "topbar.css" = "/* =============================================================================
   TOP BAR AND TRENDING SECTION
   ========================================================================== */
.top-bar {
    background-color: var(--light-topbar-bg);
    color: var(--white);
    padding: 10px 0;
}

/* Add more top bar styles here */";

    "header.css" = "/* =============================================================================
   HEADER AND NAVIGATION
   ========================================================================== */
header {
    background-color: var(--light-header-bg);
    padding: 15px 0;
}

/* Add more header styles here */";

    "featured-news.css" = "/* =============================================================================
   FEATURED NEWS SECTION
   ========================================================================== */
.featured-news {
    padding: 30px 0;
    background-color: var(--light-section-bg);
}

/* Add more featured news styles here */";

    "content.css" = "/* =============================================================================
   MAIN CONTENT AREA
   ========================================================================== */
.main-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    padding: 30px 0;
}

/* Add more content styles here */";

    "sidebar.css" = "/* =============================================================================
   SIDEBAR STYLING
   ========================================================================== */
.sidebar {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

/* Add more sidebar styles here */";

    "footer.css" = "/* =============================================================================
   FOOTER STYLING
   ========================================================================== */
footer {
    background-color: var(--light-footer-bg);
    color: var(--white);
    padding: 50px 0 20px;
}

/* Add more footer styles here */";

    "dark-mode-toggle.css" = "/* =============================================================================
   DARK MODE TOGGLE
   ========================================================================== */
.dark-mode-toggle {
    display: flex;
    align-items: center;
    cursor: pointer;
}

/* Add more toggle styles here */";

    "utilities.css" = "/* =============================================================================
   UTILITIES AND ANIMATIONS
   ========================================================================== */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Add more utility styles here */";

    "dark-theme.css" = "/* =============================================================================
   DARK MODE STYLING
   ========================================================================== */
body.dark-mode {
    background-color: var(--dark-bg);
    color: var(--dark-text);
}

/* Add more dark theme styles here */";

    "light-theme.css" = "/* =============================================================================
   LIGHT THEME (DEFAULT)
   ========================================================================== */
/* 
 * This is the default theme. The base styles are already defined in the main stylesheets.
 * This file can be used to add light theme specific overrides if needed.
 */

:root {
    /* Light theme variables already defined in variables.css */
}

/* Add any light theme specific overrides below */";

    "navigation.css" = "/* =============================================================================
   NAVIGATION STYLES
   ========================================================================== */
nav ul {
    display: flex;
    list-style: none;
    gap: 5px;
    margin: 0;
    padding: 0;
}

/* Add more navigation styles here */";

    "articles.css" = "/* =============================================================================
   ARTICLE STYLES
   ========================================================================== */
.article {
    margin-bottom: 30px;
}

/* Add more article styles here */";

    "default" = "/* =============================================================================
   PLACEHOLDER MODULE
   ========================================================================== */
/* This is a placeholder for styles that were not extracted from the original CSS file. */
/* Add your styles here. */";
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

Write-Host "`nChecking for missing CSS modules (total imports: $($matches.Count))..." -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan

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
        
        # Select template content
        $templateContent = $moduleTemplates["default"]
        if ($moduleTemplates.ContainsKey($fileName)) {
            $templateContent = $moduleTemplates[$fileName]
        }
        
        # Create the file
        try {
            Set-Content -Path $fullPath -Value $templateContent -Encoding UTF8
            Write-Host "  ✓ Created $fileName" -ForegroundColor Green
            $missingCount++
        }
        catch {
            Write-Host "  ✗ Failed to create $fileName : $_" -ForegroundColor Red
        }
    }
}

Write-Host "`nModule creation complete!" -ForegroundColor Cyan
if ($missingCount -gt 0) {
    Write-Host "Created $missingCount missing module files." -ForegroundColor Green
}
else {
    Write-Host "No missing module files to create. All modules already exist." -ForegroundColor Green
}
Write-Host "$existCount files already existed." -ForegroundColor Cyan
