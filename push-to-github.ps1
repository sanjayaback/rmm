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
git commit -m "feat: unify global search input with real-time interactive map filtering"
git branch -M main
git push -u origin main --force

Write-Host "===================================================" -ForegroundColor Green
Write-Host "Clean codebase successfully pushed to https://github.com/sanjayaback/rmm.git" -ForegroundColor Green
Write-Host "===================================================" -ForegroundColor Green
