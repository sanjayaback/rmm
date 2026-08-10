@echo off
echo ===================================================
echo Cleaning Duplicates & Pushing to GitHub: sanjayaback/rmm
echo ===================================================

cd /d "%~dp0"

echo [1/6] Cleaning leftover duplicate shell folders...
if exist "app\{Models,Services,Policies,Http" rmdir /s /q "app\{Models,Services,Policies,Http" 2>nul
if exist "resources\views\{layouts,partials,auth,listings,unlocks,dashboard,admin" rmdir /s /q "resources\views\{layouts,partials,auth,listings,unlocks,dashboard,admin" 2>nul
if exist "database\{migrations,seeders,factories}" rmdir /s /q "database\{migrations,seeders,factories}" 2>nul

echo [2/6] Initializing Git repository if needed...
git init

echo [3/6] Setting remote origin to https://github.com/sanjayaback/rmm.git...
git remote remove origin 2>nul
git remote add origin https://github.com/sanjayaback/rmm.git

echo [4/6] Staging clean files...
git add .

echo [5/6] Creating commit...
git commit -m "fix: clean leftover shell brace folders and sync all features"

echo [6/6] Pushing clean repository to main branch...
git branch -M main
git push -u origin main --force

echo ===================================================
echo Done! Clean codebase successfully pushed to:
echo https://github.com/sanjayaback/rmm.git
echo ===================================================
pause
