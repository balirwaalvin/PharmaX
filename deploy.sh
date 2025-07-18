#!/bin/bash

# Deployment script for Online Pharmacy Portal

echo "🚀 Starting deployment process..."

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "📦 Initializing Git repository..."
    git init
fi

# Add all files
echo "📁 Adding files to Git..."
git add .

# Commit changes
echo "💾 Committing changes..."
read -p "Enter commit message: " commit_message
git commit -m "$commit_message"

# Check if remote origin exists
if ! git remote | grep -q "origin"; then
    echo "🔗 Adding remote origin..."
    read -p "Enter your GitHub repository URL: " repo_url
    git remote add origin "$repo_url"
fi

# Push to GitHub
echo "🌐 Pushing to GitHub..."
git branch -M main
git push -u origin main

echo "✅ Deployment to GitHub completed!"
echo ""
echo "🎯 Next steps:"
echo "1. Visit your GitHub repository"
echo "2. Choose a deployment platform:"
echo "   - Railway: https://railway.app"
echo "   - Render: https://render.com"
echo "   - Heroku: https://heroku.com"
echo "3. Connect your GitHub repository"
echo "4. Add a MySQL database"
echo "5. Set environment variables"
echo "6. Deploy!"
