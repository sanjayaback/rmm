@echo off
echo ===================================================
echo Pushing Rentivo Project to GitHub: sanjayaback/rmm
echo ===================================================

cd /d "%~dp0"

echo [1/5] Initializing Git repository if needed...
git init

echo [2/5] Setting remote origin to https://github.com/sanjayaback/rmm.git...
git remote remove origin 2>nul
git remote add origin https://github.com/sanjayaback/rmm.git

echo [3/5] Staging files...
git add .

echo [4/5] Creating commit...
git commit -m "feat: complete Rentivo platform with Admin CRM, Global Search, Security Hardening, Tests & Docker setup"

echo [5/5] Pushing to main branch...
git branch -M main
git push -u origin main --force

echo ===================================================
echo Done! Project successfully pushed to:
echo https://github.com/sanjayaback/rmm.git
echo ===================================================
pause
