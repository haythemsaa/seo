#!/bin/bash

# SEO Master Pro - Backup Script
# This script creates backups of database and files

set -e

echo "💾 SEO Master Pro Backup Script"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
BACKUP_DIR="/var/backups/seo-master-pro"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

# Load environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

# Create backup directory if it doesn't exist
mkdir -p "$BACKUP_DIR"

echo "${YELLOW}📁 Backup directory: $BACKUP_DIR${NC}"
echo ""

# Database backup
echo "${YELLOW}🗄️  Backing up database...${NC}"
DB_BACKUP_FILE="$BACKUP_DIR/database_${DATE}.sql"

case $DB_CONNECTION in
    mysql)
        mysqldump -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "$DB_BACKUP_FILE"
        ;;
    pgsql)
        PGPASSWORD="$DB_PASSWORD" pg_dump -h "$DB_HOST" -U "$DB_USERNAME" "$DB_DATABASE" > "$DB_BACKUP_FILE"
        ;;
    *)
        echo "${RED}✗ Unsupported database connection: $DB_CONNECTION${NC}"
        exit 1
        ;;
esac

# Compress database backup
gzip "$DB_BACKUP_FILE"
echo "${GREEN}✓ Database backup created: ${DB_BACKUP_FILE}.gz${NC}"

# Files backup
echo ""
echo "${YELLOW}📦 Backing up files...${NC}"
FILES_BACKUP_FILE="$BACKUP_DIR/files_${DATE}.tar.gz"

tar -czf "$FILES_BACKUP_FILE" \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='.git' \
    .

echo "${GREEN}✓ Files backup created: $FILES_BACKUP_FILE${NC}"

# Environment file backup
echo ""
echo "${YELLOW}🔐 Backing up .env file...${NC}"
ENV_BACKUP_FILE="$BACKUP_DIR/env_${DATE}.enc"

# Encrypt .env file (you should set BACKUP_ENCRYPTION_KEY in .env)
if [ -n "$BACKUP_ENCRYPTION_KEY" ]; then
    openssl enc -aes-256-cbc -salt -in .env -out "$ENV_BACKUP_FILE" -k "$BACKUP_ENCRYPTION_KEY"
    echo "${GREEN}✓ Encrypted .env backup created: $ENV_BACKUP_FILE${NC}"
else
    cp .env "$BACKUP_DIR/env_${DATE}"
    echo "${YELLOW}⚠️  .env backed up without encryption (set BACKUP_ENCRYPTION_KEY to encrypt)${NC}"
fi

# Calculate backup sizes
echo ""
echo "${YELLOW}📊 Backup Summary:${NC}"
echo "  Database: $(du -h ${DB_BACKUP_FILE}.gz | cut -f1)"
echo "  Files: $(du -h $FILES_BACKUP_FILE | cut -f1)"
if [ -f "$ENV_BACKUP_FILE" ]; then
    echo "  Environment: $(du -h $ENV_BACKUP_FILE | cut -f1)"
fi

# Clean old backups
echo ""
echo "${YELLOW}🧹 Cleaning old backups (older than $RETENTION_DAYS days)...${NC}"
find "$BACKUP_DIR" -name "database_*.sql.gz" -mtime +$RETENTION_DAYS -delete
find "$BACKUP_DIR" -name "files_*.tar.gz" -mtime +$RETENTION_DAYS -delete
find "$BACKUP_DIR" -name "env_*" -mtime +$RETENTION_DAYS -delete
echo "${GREEN}✓ Old backups cleaned${NC}"

# Upload to S3 (optional)
if command -v aws &> /dev/null && [ -n "$AWS_BACKUP_BUCKET" ]; then
    echo ""
    echo "${YELLOW}☁️  Uploading backups to S3...${NC}"
    aws s3 cp "${DB_BACKUP_FILE}.gz" "s3://$AWS_BACKUP_BUCKET/database/"
    aws s3 cp "$FILES_BACKUP_FILE" "s3://$AWS_BACKUP_BUCKET/files/"
    if [ -f "$ENV_BACKUP_FILE" ]; then
        aws s3 cp "$ENV_BACKUP_FILE" "s3://$AWS_BACKUP_BUCKET/env/"
    fi
    echo "${GREEN}✓ Backups uploaded to S3${NC}"
fi

echo ""
echo "${GREEN}========================================${NC}"
echo "${GREEN}✓ Backup completed successfully!${NC}"
echo "${GREEN}========================================${NC}"
echo ""
echo "Backup location: $BACKUP_DIR"
echo "To restore database:"
echo "  ${YELLOW}gunzip < ${DB_BACKUP_FILE}.gz | mysql -u root -p $DB_DATABASE${NC}"
echo ""
echo "To restore files:"
echo "  ${YELLOW}tar -xzf $FILES_BACKUP_FILE${NC}"
echo ""
