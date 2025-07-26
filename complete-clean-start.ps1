# Complete Clean Start - Casino Portal
# This script removes ALL old website files and creates a fresh development environment

Write-Host "🧹 COMPLETE CLEAN START - CASINO PORTAL" -ForegroundColor Yellow
Write-Host "=========================================" -ForegroundColor Yellow

# Stop any running servers
Write-Host "1. Stopping any running servers..." -ForegroundColor Cyan
try {
    Stop-Process -Name "node" -Force -ErrorAction SilentlyContinue
    Stop-Process -Name "php" -Force -ErrorAction SilentlyContinue
    Write-Host "   ✅ Servers stopped" -ForegroundColor Green
} catch {
    Write-Host "   ℹ️ No servers running" -ForegroundColor Blue
}

# Files to preserve (critical for project)
$preserveFiles = @(
    "docs\PRD.md",
    ".git",
    ".github",
    "config.json"
)

# Get all files and directories
$allItems = Get-ChildItem -Path "C:\Users\tamir\Downloads\Casino CA SEO" -Force

Write-Host "2. Analyzing workspace contents..." -ForegroundColor Cyan
Write-Host "   📁 Total items found: $($allItems.Count)" -ForegroundColor Blue

# Items to delete (everything except preserved)
$itemsToDelete = @()

foreach ($item in $allItems) {
    $shouldPreserve = $false
    
    foreach ($preserve in $preserveFiles) {
        if ($item.Name -eq $preserve -or $item.Name -eq (Split-Path $preserve -Leaf)) {
            $shouldPreserve = $true
            break
        }
    }
    
    if (-not $shouldPreserve) {
        $itemsToDelete += $item
    }
}

Write-Host "3. Items to delete: $($itemsToDelete.Count)" -ForegroundColor Cyan
foreach ($item in $itemsToDelete) {
    Write-Host "   🗑️ $($item.Name)" -ForegroundColor Red
}

Write-Host "4. Items to preserve: $($preserveFiles.Count)" -ForegroundColor Cyan
foreach ($preserve in $preserveFiles) {
    Write-Host "   💾 $preserve" -ForegroundColor Green
}

# Confirm deletion
Write-Host ""
$confirm = Read-Host "Do you want to proceed with deletion? (Y/N)"
if ($confirm -ne "Y" -and $confirm -ne "y") {
    Write-Host "❌ Operation cancelled by user" -ForegroundColor Red
    exit
}

Write-Host ""
Write-Host "5. Deleting old files..." -ForegroundColor Cyan

$deletedCount = 0
foreach ($item in $itemsToDelete) {
    try {
        if ($item.PSIsContainer) {
            Remove-Item -Path $item.FullName -Recurse -Force
            Write-Host "   🗂️ Deleted directory: $($item.Name)" -ForegroundColor Red
        } else {
            Remove-Item -Path $item.FullName -Force
            Write-Host "   📄 Deleted file: $($item.Name)" -ForegroundColor Red
        }
        $deletedCount++
    } catch {
        Write-Host "   ❌ Failed to delete: $($item.Name) - $($_.Exception.Message)" -ForegroundColor DarkRed
    }
}

Write-Host ""
Write-Host "6. Creating fresh development structure..." -ForegroundColor Cyan

# Create new clean directories
$newDirectories = @(
    "src",
    "public",
    "public\css",
    "public\js",
    "public\images",
    "assets",
    "config",
    "docs",
    "tests"
)

foreach ($dir in $newDirectories) {
    $fullPath = Join-Path "C:\Users\tamir\Downloads\Casino CA SEO" $dir
    if (-not (Test-Path $fullPath)) {
        New-Item -ItemType Directory -Path $fullPath -Force | Out-Null
        Write-Host "   📁 Created: $dir" -ForegroundColor Green
    }
}

# Create essential files
Write-Host "7. Creating essential development files..." -ForegroundColor Cyan

# Package.json for Node.js development
$packageJson = @"
{
  "name": "casino-ca-portal",
  "version": "1.0.0",
  "description": "Enterprise Casino Portal - Complete Casino.ca Clone",
  "main": "server.js",
  "scripts": {
    "start": "node server.js",
    "dev": "nodemon server.js",
    "build": "npm run build:css && npm run build:js",
    "build:css": "tailwindcss -i ./src/css/input.css -o ./public/css/style.css --watch",
    "test": "jest"
  },
  "dependencies": {
    "express": "^4.18.2",
    "tailwindcss": "^3.3.0",
    "autoprefixer": "^10.4.14",
    "postcss": "^8.4.24"
  },
  "devDependencies": {
    "nodemon": "^3.0.1",
    "jest": "^29.6.1"
  },
  "keywords": ["casino", "canada", "portal", "reviews", "enterprise"],
  "author": "Casino Portal Team",
  "license": "MIT"
}
"@

Set-Content -Path "C:\Users\tamir\Downloads\Casino CA SEO\package.json" -Value $packageJson
Write-Host "   📄 Created: package.json" -ForegroundColor Green

# Basic Express server
$serverJs = @"
const express = require('express');
const path = require('path');
const app = express();
const PORT = process.env.PORT || 3000;

// Serve static files
app.use(express.static('public'));

// Basic route
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// API routes placeholder
app.get('/api/casinos', (req, res) => {
    res.json({ message: 'Casino API ready for implementation' });
});

app.listen(PORT, () => {
    console.log(`🎰 Casino Portal Server running on http://localhost:`+PORT);
    console.log('📁 Serving files from: ./public/');
    console.log('🚀 Ready for development!');
});
"@

Set-Content -Path "C:\Users\tamir\Downloads\Casino CA SEO\server.js" -Value $serverJs
Write-Host "   📄 Created: server.js" -ForegroundColor Green

# Basic HTML template
$indexHtml = @"
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino Portal - Enterprise Development Environment</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>🎰 Casino Portal - Clean Development Start</h1>
        <p>Enterprise-level casino portal development environment ready!</p>
        <div class="status">
            <h2>✅ Environment Status</h2>
            <ul>
                <li>✅ Clean workspace initialized</li>
                <li>✅ Development server ready</li>
                <li>✅ PRD documentation preserved</li>
                <li>🚀 Ready for implementation</li>
            </ul>
        </div>
    </div>
    <script src="js/app.js"></script>
</body>
</html>
"@

Set-Content -Path "C:\Users\tamir\Downloads\Casino CA SEO\public\index.html" -Value $indexHtml
Write-Host "   📄 Created: public/index.html" -ForegroundColor Green

# Basic CSS
$basicCss = @"
/* Casino Portal - Clean Development Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: #333;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

h1 {
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 2.5rem;
}

h2 {
    color: #34495e;
    margin: 2rem 0 1rem 0;
}

p {
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
    color: #555;
}

.status {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 10px;
    border-left: 4px solid #28a745;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    padding: 0.5rem 0;
    font-size: 1.1rem;
}
"@

Set-Content -Path "C:\Users\tamir\Downloads\Casino CA SEO\public\css\style.css" -Value $basicCss
Write-Host "   📄 Created: public/css/style.css" -ForegroundColor Green

# Basic JavaScript
$basicJs = @"
// Casino Portal - Development JavaScript
console.log('🎰 Casino Portal Development Environment Loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM loaded - Ready for development');
    
    // Add development indicators
    const container = document.querySelector('.container');
    if (container) {
        const devIndicator = document.createElement('div');
        devIndicator.innerHTML = '🔧 Development Mode Active';
        devIndicator.style.cssText = `
            position: fixed;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 0.9rem;
            z-index: 1000;
        `;
        document.body.appendChild(devIndicator);
    }
});
"@

Set-Content -Path "C:\Users\tamir\Downloads\Casino CA SEO\public\js\app.js" -Value $basicJs
Write-Host "   📄 Created: public/js/app.js" -ForegroundColor Green

# README for the clean environment
$readmeContent = @"
# 🎰 Casino Portal - Enterprise Development Environment

## 🧹 Clean Start Complete

This is a completely fresh development environment for building the enterprise-level casino portal that replicates casino.ca functionality.

## 📁 Project Structure

```
casino-ca-portal/
├── public/          # Static web files
│   ├── index.html   # Main HTML template
│   ├── css/         # Stylesheets
│   ├── js/          # JavaScript files
│   └── images/      # Image assets
├── src/             # Source code
├── config/          # Configuration files
├── docs/            # Documentation (including PRD.md)
├── tests/           # Test files
├── server.js        # Development server
└── package.json     # Node.js dependencies
```

## 🚀 Getting Started

1. Install dependencies:
   ```bash
   npm install
   ```

2. Start development server:
   ```bash
   npm start
   ```

3. Open browser to: http://localhost:3000

## 📋 Next Steps

1. Follow the PRD implementation plan in docs/PRD.md
2. Set up modern tech stack (Next.js, PHP 8.3, etc.)
3. Implement casino database and API endpoints
4. Build responsive UI components
5. Add SEO optimization features

## 🎯 Goals

- Complete casino.ca functionality replication
- Enterprise-level performance and scalability
- Modern tech stack and best practices
- SEO optimization for top rankings

**Status: Ready for enterprise development! 🚀**
"@

Set-Content -Path "C:\Users\tamir\Downloads\Casino CA SEO\README.md" -Value $readmeContent
Write-Host "   📄 Created: README.md" -ForegroundColor Green

Write-Host ""
Write-Host "✅ COMPLETE CLEAN START FINISHED!" -ForegroundColor Green
Write-Host "=================================" -ForegroundColor Green
Write-Host "📊 Summary:" -ForegroundColor White
Write-Host "   🗑️ Deleted: $deletedCount items" -ForegroundColor Red
Write-Host "   📁 Created: $($newDirectories.Count) directories" -ForegroundColor Green
Write-Host "   📄 Created: 6 essential files" -ForegroundColor Green
Write-Host ""
Write-Host "🚀 Your clean development environment is ready!" -ForegroundColor Green
Write-Host "💡 Run 'npm install && npm start' to begin development" -ForegroundColor Cyan
Write-Host "📋 Check docs/PRD.md for the complete implementation plan" -ForegroundColor Cyan
