# SEO Master Pro - API Documentation

## Table of Contents

- [Authentication](#authentication)
- [Rate Limiting](#rate-limiting)
- [Response Format](#response-format)
- [Error Handling](#error-handling)
- [Endpoints](#endpoints)
  - [Projects](#projects)
  - [Keywords](#keywords)
  - [Rankings](#rankings)
  - [Backlinks](#backlinks)
  - [Audits](#audits)
  - [Reports](#reports)
  - [Analytics](#analytics)

## Authentication

All API requests require authentication using Laravel Sanctum tokens.

### Obtaining an API Token

```http
POST /api/v1/auth/token
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**

```json
{
  "token": "1|abcdef123456..."
}
```

### Using the Token

Include the token in the Authorization header:

```http
GET /api/v1/projects
Authorization: Bearer 1|abcdef123456...
```

## Rate Limiting

API requests are rate-limited based on your subscription plan:

| Plan | Requests per Hour |
|------|-------------------|
| Free | 100 |
| Starter | 500 |
| Professional | 2,000 |
| Agency | 10,000 |
| Enterprise | 50,000 |

Rate limit information is included in response headers:

```
X-RateLimit-Limit: 500
X-RateLimit-Remaining: 499
X-RateLimit-Reset: 1634567890
```

## Response Format

### Success Response

All successful responses follow this format:

```json
{
  "data": { ... },
  "meta": {
    "current_page": 1,
    "total": 100,
    "per_page": 15
  },
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  }
}
```

### Pagination

Paginated endpoints accept these query parameters:

- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15, max: 100)

Example:

```http
GET /api/v1/projects?page=2&per_page=25
```

## Error Handling

### Error Response Format

```json
{
  "message": "Error message",
  "errors": {
    "field_name": [
      "Validation error message"
    ]
  }
}
```

### HTTP Status Codes

- `200 OK` - Request successful
- `201 Created` - Resource created
- `204 No Content` - Resource deleted
- `400 Bad Request` - Invalid request
- `401 Unauthorized` - Authentication required
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation errors
- `429 Too Many Requests` - Rate limit exceeded
- `500 Internal Server Error` - Server error

## Endpoints

### Projects

#### List Projects

```http
GET /api/v1/projects
```

**Query Parameters:**

- `page` (integer) - Page number
- `per_page` (integer) - Items per page
- `search` (string) - Search by name or URL
- `country` (string) - Filter by country code
- `status` (string) - Filter by status (active, paused, archived)

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "name": "My Website",
      "url": "https://example.com",
      "country": "FR",
      "language": "fr",
      "search_engine": "google",
      "status": "active",
      "created_at": "2025-01-15T10:30:00Z",
      "updated_at": "2025-01-15T10:30:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 10,
    "per_page": 15
  }
}
```

#### Create Project

```http
POST /api/v1/projects
Content-Type: application/json

{
  "name": "My Website",
  "url": "https://example.com",
  "country": "FR",
  "language": "fr",
  "search_engine": "google"
}
```

**Response:** `201 Created`

```json
{
  "data": {
    "id": 1,
    "name": "My Website",
    "url": "https://example.com",
    ...
  }
}
```

#### Get Project

```http
GET /api/v1/projects/{id}
```

**Response:** `200 OK`

#### Update Project

```http
PUT /api/v1/projects/{id}
Content-Type: application/json

{
  "name": "Updated Name",
  "url": "https://example.com",
  "country": "FR",
  "language": "fr"
}
```

**Response:** `200 OK`

#### Delete Project

```http
DELETE /api/v1/projects/{id}
```

**Response:** `204 No Content`

#### Initiate Crawl

```http
POST /api/v1/projects/{id}/crawl
Content-Type: application/json

{
  "max_pages": 1000,
  "crawl_javascript": true
}
```

**Response:** `200 OK`

```json
{
  "data": {
    "crawl_session_id": 123,
    "status": "pending",
    "queued_at": "2025-01-15T10:30:00Z"
  }
}
```

### Keywords

#### List Keywords

```http
GET /api/v1/projects/{project_id}/keywords
```

**Query Parameters:**

- `page`, `per_page` - Pagination
- `search` - Search keywords
- `position_min`, `position_max` - Filter by position range
- `search_volume_min` - Minimum search volume
- `sort` - Sort by (keyword, position, search_volume, difficulty)
- `order` - Sort order (asc, desc)

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "keyword": "seo tools",
      "search_volume": 12000,
      "difficulty": 65,
      "cpc": 4.50,
      "current_position": 5,
      "previous_position": 7,
      "best_position": 3,
      "worst_position": 12,
      "url": "https://example.com/seo-tools",
      "created_at": "2025-01-15T10:30:00Z"
    }
  ]
}
```

#### Add Keyword

```http
POST /api/v1/projects/{project_id}/keywords
Content-Type: application/json

{
  "keyword": "seo tools",
  "search_volume": 12000,
  "difficulty": 65,
  "cpc": 4.50,
  "target_url": "https://example.com/seo-tools"
}
```

**Response:** `201 Created`

#### Bulk Add Keywords

```http
POST /api/v1/projects/{project_id}/keywords/bulk
Content-Type: application/json

{
  "keywords": [
    "seo tools",
    "best seo software",
    "seo analysis"
  ]
}
```

**Response:** `201 Created`

```json
{
  "data": {
    "imported": 3,
    "duplicates": 0,
    "failed": 0
  }
}
```

#### Check Rankings

```http
POST /api/v1/projects/{project_id}/keywords/check-rankings
```

**Response:** `200 OK`

```json
{
  "data": {
    "job_id": "abc123",
    "status": "queued",
    "keywords_count": 50
  }
}
```

### Rankings

#### Get Ranking History

```http
GET /api/v1/projects/{project_id}/rankings
```

**Query Parameters:**

- `keyword_id` - Filter by keyword
- `date_from` - Start date (YYYY-MM-DD)
- `date_to` - End date (YYYY-MM-DD)
- `device` - Filter by device (desktop, mobile, tablet)

**Response:**

```json
{
  "data": [
    {
      "keyword_id": 1,
      "keyword": "seo tools",
      "position": 5,
      "url": "https://example.com/seo-tools",
      "device": "desktop",
      "location": "Paris, France",
      "checked_at": "2025-01-15T10:30:00Z"
    }
  ]
}
```

### Backlinks

#### List Backlinks

```http
GET /api/v1/projects/{project_id}/backlinks
```

**Query Parameters:**

- `status` - Filter by status (active, lost, toxic)
- `type` - Filter by type (dofollow, nofollow)
- `da_min`, `da_max` - Domain authority range
- `sort` - Sort by (da, pa, tf, cf, created_at)

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "source_url": "https://referrer.com/article",
      "target_url": "https://example.com",
      "anchor_text": "best seo tools",
      "link_type": "dofollow",
      "da": 65,
      "pa": 45,
      "tf": 35,
      "cf": 40,
      "is_toxic": false,
      "status": "active",
      "first_seen": "2025-01-10T00:00:00Z",
      "last_checked": "2025-01-15T10:30:00Z"
    }
  ]
}
```

#### Disavow Backlink

```http
POST /api/v1/projects/{project_id}/backlinks/{id}/disavow
```

**Response:** `200 OK`

### Audits

#### List Audits

```http
GET /api/v1/projects/{project_id}/audits
```

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "score": 85,
      "pages_crawled": 250,
      "errors_count": 12,
      "warnings_count": 45,
      "notices_count": 78,
      "status": "completed",
      "started_at": "2025-01-15T10:00:00Z",
      "completed_at": "2025-01-15T10:30:00Z"
    }
  ]
}
```

#### Create Audit

```http
POST /api/v1/projects/{project_id}/audits
Content-Type: application/json

{
  "max_pages": 1000,
  "crawl_javascript": true,
  "check_broken_links": true,
  "check_meta_tags": true
}
```

**Response:** `201 Created`

#### Get Audit Details

```http
GET /api/v1/audits/{id}
```

**Response:**

```json
{
  "data": {
    "id": 1,
    "score": 85,
    "pages_crawled": 250,
    "issues": [
      {
        "type": "error",
        "category": "meta_tags",
        "title": "Missing meta description",
        "affected_pages": 12,
        "priority": "high",
        "recommendation": "Add unique meta descriptions to all pages"
      }
    ]
  }
}
```

### Reports

#### List Reports

```http
GET /api/v1/projects/{project_id}/reports
```

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "type": "monthly",
      "period_start": "2025-01-01",
      "period_end": "2025-01-31",
      "status": "generated",
      "download_url": "https://...",
      "created_at": "2025-02-01T00:00:00Z"
    }
  ]
}
```

#### Generate Report

```http
POST /api/v1/projects/{project_id}/reports
Content-Type: application/json

{
  "type": "custom",
  "date_from": "2025-01-01",
  "date_to": "2025-01-31",
  "include_keywords": true,
  "include_backlinks": true,
  "include_audit": true,
  "format": "pdf"
}
```

**Response:** `201 Created`

```json
{
  "data": {
    "id": 1,
    "status": "queued",
    "estimated_completion": "2025-02-01T00:05:00Z"
  }
}
```

#### Download Report

```http
GET /api/v1/reports/{id}/download
```

**Response:** `200 OK` (PDF file download)

### Analytics

#### Get Analytics Overview

```http
GET /api/v1/projects/{project_id}/analytics
```

**Query Parameters:**

- `date_from` - Start date
- `date_to` - End date
- `metrics` - Comma-separated list (clicks, impressions, ctr, position)

**Response:**

```json
{
  "data": {
    "summary": {
      "clicks": 12500,
      "impressions": 150000,
      "ctr": 8.33,
      "average_position": 5.2
    },
    "timeline": [
      {
        "date": "2025-01-15",
        "clicks": 450,
        "impressions": 5200,
        "ctr": 8.65,
        "position": 5.1
      }
    ],
    "top_pages": [
      {
        "url": "https://example.com/article",
        "clicks": 1200,
        "impressions": 15000,
        "ctr": 8.0,
        "position": 3.5
      }
    ]
  }
}
```

## Webhooks

Configure webhooks to receive real-time notifications:

### Webhook Events

- `crawl.completed` - Crawl session completed
- `keyword.ranking_changed` - Keyword position changed
- `backlink.found` - New backlink discovered
- `backlink.lost` - Backlink lost
- `audit.completed` - Audit completed
- `report.generated` - Report generated

### Webhook Payload Example

```json
{
  "event": "keyword.ranking_changed",
  "timestamp": "2025-01-15T10:30:00Z",
  "project_id": 1,
  "data": {
    "keyword_id": 1,
    "keyword": "seo tools",
    "previous_position": 7,
    "current_position": 5,
    "change": 2
  }
}
```

## Best Practices

1. **Use Pagination**: Always paginate large result sets
2. **Cache Responses**: Cache API responses when appropriate
3. **Handle Rate Limits**: Implement exponential backoff
4. **Validate Input**: Always validate before sending requests
5. **Monitor Usage**: Track API usage to avoid limits
6. **Handle Errors**: Implement proper error handling
7. **Use Webhooks**: Use webhooks instead of polling

## SDKs and Libraries

### PHP

```bash
composer require seo-master-pro/php-sdk
```

### JavaScript/Node.js

```bash
npm install @seo-master-pro/js-sdk
```

### Python

```bash
pip install seo-master-pro
```

## Support

For API support, contact:
- Email: api@seo-master-pro.fr
- Documentation: https://docs.seo-master-pro.fr
- Status Page: https://status.seo-master-pro.fr
