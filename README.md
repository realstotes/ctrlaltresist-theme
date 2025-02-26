# CTRL+ALT+RESIST CSS Modularization

This directory contains scripts to manage the modular CSS architecture for the CTRL+ALT+RESIST website.

## Scripts

### extract-css-modules.ps1

Extracts CSS sections from a main CSS file into modular files based on section comments.

**Usage:**
```powershell
.\extract-css-modules.ps1 -SourceFile "assets/css/main-original.css" -OutputDir "assets/css"
```

### analyze-css.ps1

Analyzes a CSS file to identify and list all section names, helping with debugging or mapping.

**Usage:**
```powershell
.\analyze-css.ps1 -SourceFile "assets/css/main-original.css"
```

### css-verify.ps1

Verifies that all CSS modules imported by the main CSS file exist.

**Usage:**
```powershell
.\css-verify.ps1 -MainCssFile "assets/css/main.css" -BasePath "assets/css"
```

## CSS Architecture

The CSS is organized into modular files to improve maintainability:

### Modules

- **variables.css** - Core settings and CSS variables
- **reset.css** - Browser reset and base styles
- **topbar.css** - Top bar and trending section
- **header.css** - Header and main navigation
- **featured-news.css** - Featured news section styles
- **content.css** - Main content area styles
- **sidebar.css** - Sidebar styling
- **footer.css** - Footer styles
- **dark-mode-toggle.css** - Dark mode toggle component
- **utilities.css** - Utility classes and animations
- **responsive.css** - Responsive design styles

### Themes

- **light-theme.css** - Light theme (default) styles
- **dark-theme.css** - Dark theme styles and overrides

## Workflow

1. Make changes to individual module files
2. The main.css file imports all modules through @import statements
3. For production, consider using a CSS bundler to combine all files

## Maintenance

If you need to extract sections again from a monolithic CSS file:

1. Run `analyze-css.ps1` to identify section names
2. Update section mappings in `extract-css-modules.ps1` if needed
3. Run `extract-css-modules.ps1` to create modular files
4. Verify all files exist with `css-verify.ps1`
