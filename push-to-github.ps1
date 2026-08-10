Write-Host "===================================================" -ForegroundColor Cyan
Write-Host "Pushing Rentivo Project to GitHub: sanjayaback/rmm" -ForegroundColor Cyan
Write-Host "===================================================" -ForegroundColor Cyan

Set-Location -Path $PSScriptRoot

git init
git remote remove origin 2>$null
git remote add origin https://github.com/sanjayaback/rmm.git
git add .
git commit -m "feat: complete Rentivo platform with Admin CRM, Global Search, Security Hardening, Tests & Docker setup"
git branch -M main
git push -u origin main --force

Write-Host "===================================================" -ForegroundColor Green
Write-Host "Successfully pushed to https://github.com/sanjayaback/rmm.git" -ForegroundColor Green
Write-Host "===================================================" -ForegroundColor Green
