Write-Host "===================================================" -ForegroundColor Cyan
Write-Host "Cleaning Duplicates & Pushing to GitHub: sanjayaback/rmm" -ForegroundColor Cyan
Write-Host "===================================================" -ForegroundColor Cyan

Set-Location -Path $PSScriptRoot

# Clean up any leftover misnamed brace expansion folders
Get-ChildItem -Path . -Recurse -Directory -Filter "*`{*" -ErrorAction SilentlyContinue | ForEach-Object {
    Write-Host "Removing leftover duplicate folder: $($_.FullName)" -ForegroundColor Yellow
    Remove-Item -LiteralPath $_.FullName -Recurse -Force -ErrorAction SilentlyContinue
}

git init
git remote remove origin 2>$null
git remote add origin https://github.com/sanjayaback/rmm.git
git add .
git commit -m "feat: sync custom rentivo.css and update layouts for cPanel build"
git branch -M main
git push -u origin main

Write-Host "===================================================" -ForegroundColor Green
Write-Host "Code successfully pushed to GitHub repository: https://github.com/sanjayaback/rmm.git" -ForegroundColor Green
Write-Host "GitHub Actions deployment workflow triggered automatically!" -ForegroundColor Green
Write-Host "===================================================" -ForegroundColor Green
