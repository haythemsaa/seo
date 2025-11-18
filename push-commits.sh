#!/bin/bash

# Script to push unpushed commits to remote repository
# Run this script when the Git server is available

echo "================================================"
echo "  SEO Master Pro - Push Commits Script"
echo "================================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Change to project directory
cd /home/user/seo

# Check unpushed commits
UNPUSHED=$(git log origin/claude/create-application-013ooMdx6ajYguLgBrubkS4K..HEAD --oneline 2>/dev/null | wc -l)

if [ "$UNPUSHED" -eq 0 ]; then
    echo -e "${GREEN}✓ No unpushed commits. Everything is up to date!${NC}"
    exit 0
fi

echo -e "${YELLOW}Found $UNPUSHED unpushed commit(s):${NC}"
echo ""
git log origin/claude/create-application-013ooMdx6ajYguLgBrubkS4K..HEAD --oneline
echo ""

# Try to push with retries
MAX_RETRIES=5
RETRY_DELAY=5

for i in $(seq 1 $MAX_RETRIES); do
    echo -e "${YELLOW}Attempt $i of $MAX_RETRIES...${NC}"

    if git push -u origin claude/create-application-013ooMdx6ajYguLgBrubkS4K; then
        echo ""
        echo -e "${GREEN}================================================${NC}"
        echo -e "${GREEN}  ✓ Successfully pushed all commits!${NC}"
        echo -e "${GREEN}================================================${NC}"
        echo ""
        echo "Branch: claude/create-application-013ooMdx6ajYguLgBrubkS4K"
        echo "Commits pushed: $UNPUSHED"
        echo ""
        exit 0
    else
        if [ $i -lt $MAX_RETRIES ]; then
            echo -e "${RED}✗ Push failed. Retrying in ${RETRY_DELAY}s...${NC}"
            sleep $RETRY_DELAY
            RETRY_DELAY=$((RETRY_DELAY * 2)) # Exponential backoff
        fi
    fi
done

echo ""
echo -e "${RED}================================================${NC}"
echo -e "${RED}  ✗ Failed to push after $MAX_RETRIES attempts${NC}"
echo -e "${RED}================================================${NC}"
echo ""
echo "The commits are still saved locally."
echo "Please try again later when the Git server is available."
echo ""
echo "To push manually, run:"
echo "  cd /home/user/seo"
echo "  git push -u origin claude/create-application-013ooMdx6ajYguLgBrubkS4K"
echo ""

exit 1
