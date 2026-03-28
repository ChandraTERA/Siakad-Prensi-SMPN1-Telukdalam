#!/bin/bash

# 🧹 Script untuk Membersihkan File Metadata macOS di SSD External
# Usage: ./cleanup-metadata.sh

echo "🧹 Cleaning up macOS metadata files from SSD External..."

# Masuk ke directory project
cd "/Volumes/ADATA SC750/Mac/Documents/Kerjaaan-ssd/web-siakad"

echo "📁 Current directory: $(pwd)"
echo ""

# Hapus file ._ di seluruh project
echo "🔍 Searching for ._* files..."
FOUND_DOTFILES=$(find . -name "._*" -type f | wc -l)
if [ $FOUND_DOTFILES -gt 0 ]; then
    echo "❌ Found $FOUND_DOTFILES ._* files"
    find . -name "._*" -type f -delete
    echo "✅ Removed all ._* files"
else
    echo "✅ No ._* files found"
fi

# Hapus file .DS_Store
echo "🔍 Searching for .DS_Store files..."
FOUND_DSSTORE=$(find . -name ".DS_Store" -type f | wc -l)
if [ $FOUND_DSSTORE -gt 0 ]; then
    echo "❌ Found $FOUND_DSSTORE .DS_Store files"
    find . -name ".DS_Store" -type f -delete
    echo "✅ Removed all .DS_Store files"
else
    echo "✅ No .DS_Store files found"
fi

# Hapus file temporary lainnya
echo "🔍 Searching for temporary files..."
find . -name "*.tmp" -type f -delete 2>/dev/null
find . -name "*.temp" -type f -delete 2>/dev/null
find . -name "*~" -type f -delete 2>/dev/null
echo "✅ Removed temporary files"

# Hapus dari git jika sudah ter-track
echo "🔍 Cleaning git cache..."
git rm --cached -r ._* .DS_Store 2>/dev/null || true
echo "✅ Removed from git cache"

# Update .gitignore jika belum ada
if ! grep -q "macOS metadata files" .gitignore 2>/dev/null; then
    echo "📝 Updating .gitignore..."
    cat >> .gitignore << 'EOF'

# macOS metadata files
.DS_Store
.DS_Store?
._*
.Spotlight-V100
.Trashes
ehthumbs.db
Thumbs.db

# Temporary files
*.tmp
*.temp
*~
.#*
#*#
EOF
    echo "✅ Updated .gitignore"
else
    echo "✅ .gitignore already configured"
fi

echo ""
echo "🎉 Cleanup completed!"
echo ""
echo "📋 Next steps:"
echo "1. Copy the updated settings.json to Cursor"
echo "2. Restart Cursor"
echo "3. Reload window: Cmd+Shift+P → 'Developer: Reload Window'"
echo ""
echo "🔧 To prevent future ._ files:"
echo "defaults write com.apple.desktopservices DSDontWriteNetworkStores true"
echo "defaults write com.apple.desktopservices DSDontWriteUSBStores true"

