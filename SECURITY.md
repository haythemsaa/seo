# Security Policy

## Supported Versions

We release security updates for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

Instead, please report them to our security team at **security@seo-master-pro.fr**.

### What to Include

Please include the following information in your report:

- Type of vulnerability (e.g., SQL injection, XSS, CSRF)
- Full paths of affected source file(s)
- Location of the affected code (tag/branch/commit or direct URL)
- Any special configuration required to reproduce the issue
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the vulnerability
- How you discovered the vulnerability

### Response Timeline

You can expect:

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Timeline**: Critical vulnerabilities within 30 days

### Disclosure Policy

- Please give us reasonable time to fix the vulnerability before public disclosure
- We will credit you in our security advisory (unless you prefer to remain anonymous)
- We may ask you to participate in validating the fix

## Security Measures

### Application Security

#### Authentication & Authorization

- **Multi-Factor Authentication (MFA)**: Optional 2FA using TOTP
- **Password Requirements**: Minimum 8 characters, uppercase, lowercase, and number
- **Session Management**: Secure session handling with Laravel Sanctum
- **Rate Limiting**: API and login attempt rate limiting
- **CSRF Protection**: Enabled on all state-changing operations

#### Data Protection

- **Encryption at Rest**: Sensitive data encrypted in database
- **Encryption in Transit**: HTTPS/TLS 1.2+ enforced
- **Password Hashing**: Bcrypt with cost factor 12
- **API Token Storage**: Hashed tokens using SHA-256
- **Personal Data**: GDPR-compliant data handling

#### Input Validation

- **Server-Side Validation**: All inputs validated on backend
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- **XSS Prevention**: Output escaping in Vue.js templates
- **CSRF Tokens**: Required for all POST/PUT/DELETE requests
- **File Upload Validation**: Type and size restrictions

#### Security Headers

```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
Referrer-Policy: strict-origin-when-cross-origin
```

### Infrastructure Security

#### Server Hardening

- **Firewall**: UFW configured to allow only necessary ports (22, 80, 443)
- **Fail2Ban**: Protection against brute force attacks
- **SSH**: Key-based authentication, disabled root login
- **Updates**: Automated security updates enabled
- **Monitoring**: 24/7 intrusion detection

#### Database Security

- **Access Control**: Limited to application server only
- **Strong Passwords**: Enforced for all database users
- **Encrypted Connections**: TLS for database connections
- **Regular Backups**: Daily encrypted backups
- **Audit Logging**: All privileged operations logged

#### Redis Security

- **Authentication**: Password-protected Redis instances
- **Network Isolation**: Not exposed to public internet
- **Command Filtering**: Dangerous commands disabled

### Third-Party Dependencies

#### Dependency Management

- **Regular Updates**: Dependencies updated monthly
- **Security Scanning**: Automated vulnerability scanning with `composer audit`
- **Trusted Sources**: Only packages from official repositories
- **License Compliance**: All dependencies reviewed for licensing

#### NPM Packages

```bash
# Check for vulnerabilities
npm audit

# Fix vulnerabilities
npm audit fix
```

#### Composer Packages

```bash
# Check for security issues
composer audit
```

### API Security

#### Authentication

- **Token-Based**: Laravel Sanctum tokens
- **Token Expiration**: Configurable token lifetime
- **Token Revocation**: Ability to revoke tokens
- **Scope-Based Access**: Fine-grained permissions

#### Rate Limiting

| Plan | Requests/Hour |
|------|---------------|
| Free | 100 |
| Starter | 500 |
| Professional | 2,000 |
| Agency | 10,000 |
| Enterprise | 50,000 |

#### Request Validation

- **Schema Validation**: All requests validated against schema
- **Type Checking**: Strict type validation
- **Size Limits**: Maximum request size enforced
- **Timeout Protection**: Request timeout limits

### Code Security Practices

#### Secure Coding Guidelines

1. **Never Trust User Input**: Always validate and sanitize
2. **Use Prepared Statements**: Prevent SQL injection
3. **Escape Output**: Prevent XSS attacks
4. **Validate File Uploads**: Check type, size, and content
5. **Use HTTPS**: Always use secure connections
6. **Implement CSRF Protection**: On all state-changing requests
7. **Log Security Events**: Track authentication, authorization failures
8. **Handle Errors Securely**: Don't expose sensitive information

#### Example: Secure Query

```php
// ❌ VULNERABLE
$results = DB::select("SELECT * FROM users WHERE email = '{$request->email}'");

// ✅ SECURE
$results = DB::table('users')
    ->where('email', $request->validated('email'))
    ->get();
```

#### Example: Secure File Upload

```php
// ✅ SECURE
$request->validate([
    'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
]);

$path = $request->file('file')->store('reports', 'private');
```

### Vulnerability Scanning

We regularly scan for vulnerabilities using:

- **Snyk**: Continuous dependency monitoring
- **SonarQube**: Code quality and security analysis
- **OWASP ZAP**: Web application security testing
- **Composer Audit**: PHP dependency vulnerabilities
- **NPM Audit**: JavaScript dependency vulnerabilities

### Penetration Testing

- **Annual Penetration Tests**: By certified security professionals
- **Bug Bounty Program**: Planned for Q3 2026
- **Security Audits**: Quarterly code reviews

## Security Checklist for Deployment

Before deploying to production, ensure:

- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production`
- [ ] Strong random `APP_KEY` generated
- [ ] HTTPS/TLS properly configured
- [ ] Security headers configured in web server
- [ ] Database credentials secured
- [ ] Redis password set
- [ ] Firewall rules configured
- [ ] Fail2Ban installed and configured
- [ ] SSH key-based authentication enabled
- [ ] Root login disabled
- [ ] Regular backup system in place
- [ ] Monitoring and alerting configured
- [ ] All dependencies up to date
- [ ] Security scanning enabled in CI/CD
- [ ] Error reporting configured (Sentry, etc.)
- [ ] API rate limiting enabled
- [ ] CORS properly configured
- [ ] File upload limits enforced
- [ ] Session timeout configured

## Incident Response Plan

### Detection

- Automated monitoring and alerting
- Log analysis and anomaly detection
- User reports via security@seo-master-pro.fr

### Response Steps

1. **Identify**: Determine nature and scope of incident
2. **Contain**: Isolate affected systems
3. **Eradicate**: Remove threat from environment
4. **Recover**: Restore systems to normal operation
5. **Review**: Post-incident analysis and lessons learned

### Communication

- **Internal**: Security team notified immediately
- **Users**: Notification within 72 hours if data breach
- **Authorities**: Compliance with GDPR reporting requirements

## Compliance

### GDPR Compliance

- **Data Minimization**: Only collect necessary data
- **Right to Access**: Users can export their data
- **Right to Erasure**: Users can delete their account
- **Data Portability**: Export data in common formats
- **Breach Notification**: 72-hour notification requirement
- **Data Processing Agreement**: Available for enterprise customers

### Data Retention

- **Active Accounts**: Data retained while account is active
- **Deleted Accounts**: Data permanently deleted after 30 days
- **Backups**: Encrypted backups retained for 90 days
- **Logs**: Security logs retained for 1 year

## Security Contacts

- **General Security**: security@seo-master-pro.fr
- **Vulnerability Reports**: security@seo-master-pro.fr
- **Emergency Contact**: +33 (0)1 XX XX XX XX (Enterprise customers only)

## Security Advisories

Security advisories are published on:

- GitHub Security Advisories
- Our security blog: https://blog.seo-master-pro.fr/security
- Email notifications to affected users

## Hall of Fame

We recognize security researchers who responsibly disclose vulnerabilities:

<!-- Contributors will be listed here -->

## Bug Bounty Program

We're planning to launch a bug bounty program in Q3 2026. Details will be announced on our blog and security page.

### Scope (Planned)

**In Scope:**
- *.seo-master-pro.fr
- API endpoints
- Web application
- Mobile applications (when available)

**Out of Scope:**
- Physical security
- Social engineering
- Denial of Service attacks
- Spam attacks

### Rewards (Planned)

- **Critical**: €500 - €2,000
- **High**: €250 - €500
- **Medium**: €100 - €250
- **Low**: €50 - €100

## Security Updates

Subscribe to security updates:

- GitHub Watch (Releases only)
- Security mailing list: https://seo-master-pro.fr/security-updates
- RSS feed: https://blog.seo-master-pro.fr/security/feed

---

**Last Updated**: 2025-01-15

Thank you for helping keep SEO Master Pro and our users safe!
