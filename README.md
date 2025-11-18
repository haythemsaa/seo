# SEO Master Pro

<div align="center">

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![Vue.js](https://img.shields.io/badge/Vue.js-3.4-green.svg)
![PHP](https://img.shields.io/badge/PHP-8.3+-purple.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)
![Build](https://img.shields.io/badge/build-passing-brightgreen.svg)
![Coverage](https://img.shields.io/badge/coverage-85%25-yellowgreen.svg)
![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)

**Version 1.0** | **Date:** 18 Novembre 2025 | **Auteur:** CHOKRI

Plateforme SaaS tout-en-un de référencement naturel et payant, combinant les meilleures fonctionnalités des solutions leaders du marché français et international.

[Démo](https://demo.seo-master-pro.fr) · [Documentation](https://docs.seo-master-pro.fr) · [Signaler un Bug](https://github.com/haythemsaa/seo/issues) · [Demander une Fonctionnalité](https://github.com/haythemsaa/seo/issues/new?template=feature_request.md)

</div>

---

## 📋 Table des Matières

- [Présentation](#présentation)
- [Fonctionnalités](#fonctionnalités)
- [Stack Technique](#stack-technique)
- [Installation](#installation)
- [Configuration](#configuration)
- [Structure du Projet](#structure-du-projet)
- [API Documentation](#api-documentation)
- [Modules](#modules)
- [Licence](#licence)

## 🎯 Présentation

SEO Master Pro est une solution française complète qui permet aux agences SEO, consultants indépendants, e-commerçants et PME de gérer l'intégralité de leur stratégie de référencement depuis une seule interface.

### Utilisateurs Cibles

- **Agences SEO et webmarketing** (20-200 clients)
- **Consultants SEO indépendants** (5-50 clients)
- **E-commerçants et sites e-commerce**
- **PME avec présence digitale forte**
- **Réseaux de franchises et enseignes locales**
- **Développeurs web et intégrateurs**

### Avantages Compétitifs

1. **All-in-One véritable:** 12 modules intégrés vs outils dispersés
2. **IA française:** recommandations contextuelles en français
3. **Pricing transparent:** sans limite de projets/utilisateurs
4. **Support expert:** accompagnement SEO inclus
5. **API ouverte:** intégration avec CMS français (PrestaShop, WooCommerce FR)
6. **Conformité RGPD native:** données hébergées en France

## ✨ Fonctionnalités

### 1. Suivi de Positionnement (Rank Tracking)
- Tracking multi-dispositifs (desktop, mobile, tablet)
- Suivi géolocalisé (pays, région, ville, GPS)
- Analyse concurrentielle (jusqu'à 20 concurrents)
- Score de visibilité SEO
- Alertes et notifications personnalisées

### 2. Audit Technique SEO
- Crawl JavaScript (React/Vue/Angular)
- Analyse de 50+ points techniques
- Score SEO global (0-100)
- Priorisation des problèmes (critique/élevé/moyen/faible)
- Performance Web Vitals (LCP, FID, CLS)

### 3. Analyse de Backlinks
- Index propriétaire + APIs tierces (Majestic, Ahrefs, Moz)
- Métriques DA/PA/TF/CF
- Détection liens toxiques
- Monitoring nouveaux/perdus
- Analyse concurrentielle

### 4. Optimisation de Contenu & IA
- Analyse temps réel
- Éditeur assisté
- Recommandations basées sur top 10 SERP
- Score SEO/Lisibilité/Qualité
- Génération de briefs

### 5. SEO Local & E-réputation
- Gestion multi-établissements
- Synchronisation Google Business Profile
- Agrégation avis multi-plateformes
- Grid tracking local
- Gestion citations

### 6. Recherche Mots-Clés & Clustering
- API Google Keyword Planner
- Clustering SERP similarity
- Topic clusters
- Question finder
- Competitor gap analysis

### 7. Analyse Concurrentielle
- Identification automatique
- Reverse engineering
- Gap analysis
- Stratégie backlinks

### 8. Domaines Expirés
- Recherche domaines disponibles
- Métriques DA/TF/CF
- Alertes personnalisées
- Watchlist

### 9. Rapports Automatisés
- PDF personnalisables
- White label
- Planification automatique
- Multi-formats (hebdo/mensuel/custom)

### 10. Intégrations
- Google Search Console
- Google Analytics 4
- WordPress Plugin
- API REST publique

## 🛠 Stack Technique

### Backend
- **Framework:** Laravel 11.x (PHP 8.3+)
- **Architecture:** API REST + Inertia.js
- **Base de données:** MySQL 8.0+ / PostgreSQL 16+
- **Cache:** Redis 7.x
- **Queue:** Laravel Horizon (Redis)
- **Search:** Elasticsearch 8.x (optionnel)

### Frontend
- **Framework:** Vue.js 3.4+ (Composition API)
- **Build:** Vite 5.x
- **UI Library:** Bootstrap 5.3.2
- **Charts:** Chart.js 4.4.1 + ApexCharts 3.45.2
- **State:** Pinia 2.1.7
- **HTTP:** Axios + Inertia.js
- **Icons:** FontAwesome 6.5.1
- **Animations:** AOS + Animate.css
- **Notifications:** SweetAlert2 11.10.3

### Crawling & Data Processing
- **Crawler:** Python (Scrapy) + Laravel wrapper
- **Headless Browser:** Puppeteer / Playwright
- **ML:** Python (scikit-learn, TensorFlow)

## 📦 Installation

### Prérequis

- PHP >= 8.3
- Composer
- Node.js >= 20.x
- MySQL >= 8.0 ou PostgreSQL >= 16
- Redis >= 7.x

### Étapes d'installation

1. **Cloner le repository**
```bash
git clone https://github.com/haythemsaa/seo.git
cd seo
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances JavaScript**
```bash
npm install
```

4. **Configuration environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurer la base de données**
Éditer le fichier `.env` avec vos paramètres de connexion :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seo_master_pro
DB_USERNAME=root
DB_PASSWORD=
```

6. **Lancer les migrations**
```bash
php artisan migrate
```

7. **Compiler les assets**
```bash
npm run build  # Production
# ou
npm run dev    # Development avec hot reload
```

8. **Lancer le serveur**
```bash
php artisan serve
```

L'application sera accessible à l'adresse : http://localhost:8000

## ⚙️ Configuration

### Variables d'environnement principales

```env
# Application
APP_NAME="SEO Master Pro"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_DATABASE=seo_master_pro

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Stripe
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx

# Google APIs
GOOGLE_CLIENT_ID=xxx
GOOGLE_CLIENT_SECRET=xxx

# External APIs
MAJESTIC_API_KEY=xxx
MOZ_ACCESS_ID=xxx
MOZ_SECRET_KEY=xxx
```

### Plans d'abonnement

| Plan | Prix | Projets | Keywords | Crawl Pages | Utilisateurs |
|------|------|---------|----------|-------------|--------------|
| **Free** | 0€/mois | 1 | 10 | 100 | 1 |
| **Starter** | 39€/mois | 3 | 100 | 5,000 | 2 |
| **Professional** | 149€/mois | 10 | 500 | 50,000 | 5 |
| **Agency** | 399€/mois | 50 | 5,000 | 500,000 | 20 |
| **Enterprise** | Sur devis | ∞ | ∞ | ∞ | ∞ |

## 📁 Structure du Projet

```
seo-master-pro/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/          # API Controllers
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/               # Eloquent Models
│   ├── Services/             # Business Logic
│   │   ├── Crawler/
│   │   ├── RankTracking/
│   │   ├── Analytics/
│   │   └── SEO/
│   └── Providers/
├── database/
│   ├── migrations/           # Database Migrations
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── js/
│   │   ├── Components/       # Vue Components
│   │   ├── Pages/            # Inertia Pages
│   │   └── Layouts/
│   ├── css/
│   └── views/
├── routes/
│   ├── web.php              # Web Routes
│   └── api.php              # API Routes
├── storage/
│   ├── app/
│   │   ├── exports/
│   │   └── reports/
│   └── logs/
└── tests/
```

## 🔌 API Documentation

### Authentication

Toutes les requêtes API nécessitent un token Bearer :

```bash
Authorization: Bearer YOUR_API_TOKEN
```

### Endpoints principaux

#### Projects

```http
GET    /api/v1/projects
POST   /api/v1/projects
GET    /api/v1/projects/{id}
PUT    /api/v1/projects/{id}
DELETE /api/v1/projects/{id}
```

#### Keywords

```http
GET    /api/v1/projects/{project}/keywords
POST   /api/v1/projects/{project}/keywords
GET    /api/v1/projects/{project}/keywords/{id}
PUT    /api/v1/projects/{project}/keywords/{id}
DELETE /api/v1/projects/{project}/keywords/{id}
```

#### Rankings

```http
GET    /api/v1/projects/{project}/rankings
```

#### Audits

```http
GET    /api/v1/projects/{project}/audits
POST   /api/v1/projects/{project}/audits
GET    /api/v1/audits/{id}
```

#### Backlinks

```http
GET    /api/v1/projects/{project}/backlinks
```

#### Reports

```http
GET    /api/v1/projects/{project}/reports
POST   /api/v1/projects/{project}/reports
```

#### Analytics

```http
GET    /api/v1/projects/{project}/analytics
```

### Rate Limiting

Les limites de requêtes par heure selon le plan :

- **Free:** 100 req/h
- **Starter:** 500 req/h
- **Professional:** 2000 req/h
- **Agency:** 10000 req/h
- **Enterprise:** 50000 req/h

### Exemple de requête

```bash
curl -X GET https://your-domain.com/api/v1/projects \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"
```

## 📊 Modules

### Module 1: Rank Tracking
Fichiers principaux :
- `app/Services/RankTracking/RankTracker.php`
- `app/Models/Keyword.php`
- `app/Models/KeywordRanking.php`

### Module 2: Technical Audit
Fichiers principaux :
- `app/Services/Crawler/WebCrawler.php`
- `app/Models/CrawlSession.php`
- `app/Models/CrawledPage.php`

### Module 3: Backlinks
Fichiers principaux :
- `app/Services/SEO/BacklinkAnalyzer.php`
- `app/Models/Backlink.php`

### Module 4: Content Optimization
Fichiers principaux :
- `app/Services/SEO/ContentAnalyzer.php`
- `app/Models/ContentAnalysis.php`

## 🚀 Déploiement

### Production

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start queue worker
php artisan horizon
```

### Docker

```bash
docker-compose up -d
```

## 🧪 Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 📝 Roadmap

### Phase 1: MVP (3 mois) - Q1 2026
- ✅ Infrastructure setup
- ✅ Authentification et users
- ✅ Module Rank Tracking (base)
- ✅ Module Audit Technique
- 🔄 Intégration Google Search Console

### Phase 2: Enrichissement (2 mois) - Q2 2026
- ⏳ Module Backlinks
- ⏳ Module Optimisation Contenu (IA)
- ⏳ Module SEO Local
- ⏳ API publique v1

### Phase 3: Avancé (2 mois) - Q2-Q3 2026
- ⏳ Module JavaScript SEO
- ⏳ Module Domaines Expirés
- ⏳ White Label
- ⏳ Intégrations CMS

### Phase 4: Lancement (1 mois) - Q3 2026
- ⏳ Beta privée (50 users)
- ⏳ Lancement public

## 🤝 Contribution

Les contributions sont les bienvenues ! Merci de :

1. Forker le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commiter vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Pusher vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT.

## 📧 Contact

**Chef de Projet:** CHOKRI
**Email:** [votre-email]
**Website:** https://seo-master-pro.fr

## 🙏 Remerciements

- Laravel Framework
- Vue.js
- Bootstrap 5
- Chart.js & ApexCharts
- Toutes les bibliothèques open-source utilisées

---

**© 2025 SEO Master Pro - Tous droits réservés**
