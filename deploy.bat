@echo off
echo 🚀 Starting deployment process...

REM Check if git is initialized
if not exist ".git" (
    echo 📦 Initializing Git repository...
    git init
)

REM Add all files
echo 📁 Adding files to Git...
git add .

REM Commit changes
echo 💾 Committing changes...
set /p commit_message="Enter commit message: "
git commit -m "%commit_message%"

REM Check if remote origin exists
git remote | findstr "origin" >nul
if errorlevel 1 (
    echo 🔗 Adding remote origin...
    set /p repo_url="Enter your GitHub repository URL: "
    git remote add origin "%repo_url%"
)

REM Push to GitHub
echo 🌐 Pushing to GitHub...
git branch -M main
git push -u origin main

echo ✅ Deployment to GitHub completed!
echo.
echo 🎯 Next steps:
echo 1. Visit your GitHub repository
echo 2. Choose a deployment platform:
echo    - Railway: https://railway.app
echo    - Render: https://render.com
echo    - Heroku: https://heroku.com
echo 3. Connect your GitHub repository
echo 4. Add a MySQL database
echo 5. Set environment variables
echo 6. Deploy!

pause
