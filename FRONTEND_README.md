# SEO Master Pro - Frontend Documentation

## 🎨 Vue d'ensemble

Application SaaS complète de gestion SEO construite avec **Vue.js 3.4** et **Bootstrap 5.3.2**, utilisant **Inertia.js** pour une expérience SPA fluide.

## 📦 Stack Technique

### Frontend Core
- **Vue.js 3.4** - Framework JavaScript progressif
- **Vite 5.x** - Build tool ultra-rapide
- **Inertia.js** - Adaptateur SPA pour Laravel
- **Pinia 2.1.7** - State management
- **Bootstrap 5.3.2** - Framework CSS

### Bibliothèques UI/UX
- **FontAwesome 6.5.1** - Icônes
- **AOS (Animate On Scroll)** - Animations au scroll
- **Animate.css 4.1.1** - Animations CSS
- **SweetAlert2 11.10.3** - Modales et toasts élégants

### Data Visualization
- **Chart.js 4.4.1** - Graphiques simples et rapides
- **ApexCharts 3.45.2** - Graphiques avancés
- **vue-chartjs 5.3.0** - Wrapper Vue pour Chart.js
- **vue3-apexcharts 1.4.4** - Wrapper Vue pour ApexCharts

## 📁 Structure des Fichiers

```
resources/js/
├── Components/           # Composants réutilisables
│   ├── StatsCard.vue    # Carte de statistiques
│   ├── LoadingSpinner.vue
│   └── EmptyState.vue
├── Layouts/             # Layouts de l'application
│   └── AppLayout.vue    # Layout principal avec sidebar
├── Pages/               # Pages de l'application
│   ├── Auth/           # Authentification
│   │   ├── Login.vue
│   │   └── Register.vue
│   ├── Dashboard/
│   │   └── Index.vue
│   ├── Projects/
│   │   ├── Index.vue   # Liste des projets
│   │   └── Show.vue    # Détail d'un projet
│   ├── Keywords/
│   │   └── Index.vue
│   ├── Backlinks/
│   │   └── Index.vue
│   ├── Recommendations/
│   │   └── Index.vue
│   ├── Reports/
│   │   └── Index.vue
│   ├── Audits/
│   │   └── Index.vue
│   ├── Settings/
│   │   └── Index.vue
│   ├── Subscription/
│   │   └── Index.vue
│   ├── Errors/         # Pages d'erreur
│   │   ├── 404.vue
│   │   ├── 403.vue
│   │   └── 500.vue
│   └── Welcome.vue     # Page d'accueil
├── stores/             # Pinia stores
│   ├── auth.js        # Authentification
│   ├── notifications.js
│   └── projects.js
├── composables/        # Composables Vue
│   ├── useNotifications.js
│   └── useClipboard.js
├── utils/             # Utilitaires
│   └── formatters.js  # Formatage de dates, nombres, etc.
├── app.js            # Point d'entrée
└── bootstrap.js      # Configuration Axios

resources/sass/
└── app.scss          # Thème Bootstrap personnalisé
```

## 🎨 Thème et Design

### Palette de Couleurs

```scss
$primary: #6366f1;    // Indigo
$secondary: #8b5cf6;  // Purple
$success: #10b981;    // Emerald
$warning: #f59e0b;    // Amber
$danger: #ef4444;     // Red
$info: #3b82f6;       // Blue
```

### Gradients

Tous les composants utilisent des gradients cohérents :

```scss
.bg-gradient-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}
```

### Animations

Animations AOS configurées dans `app.js` :
```javascript
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    offset: 100
});
```

## 📊 Pages et Fonctionnalités

### 1. Dashboard (/)
- **Statistiques clés** : Mots-clés, Top 3, Visibilité, Tendances
- **Graphiques** :
  - Évolution de la visibilité (Chart.js line)
  - Distribution des positions (Chart.js doughnut)
- **Top mots-clés** avec positions
- **Recommandations IA** en aperçu

### 2. Projets (/projects)
- **Liste** : Cards avec stats (keywords, backlinks, visibilité)
- **Création** : Modal avec formulaire complet
- **Actions** : Voir, Modifier, Supprimer
- **Détail** (`/projects/:id`) :
  - Vue d'ensemble avec graphiques
  - Onglets : Keywords, Backlinks, Audits, Settings
  - Gestion complète du projet

### 3. Mots-clés (/keywords)
- **Stats** : Total, Top 3, Top 10, Position moyenne
- **Filtres** : Recherche, Projet, Position
- **Tableau** : Mot-clé, Position, Évolution, Volume, Difficulté
- **Ajout en masse** : Modal avec textarea (un par ligne)

### 4. Backlinks (/backlinks)
- **Stats** : Total, Domaines, DA moyen, Backlinks perdus
- **Filtres** : DoFollow, NoFollow, Perdus
- **Tableau** : Source, Cible, Type, DA, PA, Statut
- **Favicons** automatiques pour les domaines

### 5. Recommandations (/recommendations)
- **Filtres** : Technique, Contenu, Backlinks, Rankings
- **Quick Wins** : Impact élevé / Effort faible
- **Priorité haute** : Impact élevé
- **Cards détaillées** :
  - Impact/Effort avec progress bars
  - Action items (liste à puces)
  - Toggle statut (pending/completed)

### 6. Rapports (/reports)
- **Stats** : Trafic, CTR, Impressions, Position moyenne
- **Graphique** : Performance sur 30 jours (double axe)
- **Top keywords** et **Top pages**
- **Génération** : Modal avec options
  - Type : Mensuel, Trimestriel, Personnalisé
  - Sections : Keywords, Backlinks, Technique, Recommandations
- **Historique** : Liste avec téléchargement PDF

### 7. Audits (/audits)
- **Score SEO** : Indicateur circulaire (80/100)
- **Répartition** : Erreurs, Avertissements, Notices, Réussis
- **Liste détaillée** :
  - Titre et description
  - Pages affectées
  - **Solution recommandée**
- **Historique** : Table avec scores et statuts

### 8. Paramètres (/settings)
**Navigation latérale** avec 5 sections :
- **Profil** : Infos personnelles
- **Sécurité** : Mot de passe, 2FA, Sessions
- **Organisation** : Infos entreprise
- **Notifications** : Préférences email/app
- **API** : Clés API et intégrations externes

### 9. Abonnement (/subscription)
- **Plan actuel** : Détails et date de renouvellement
- **Usage** : Progress bars (projets, keywords, backlinks, rapports)
- **Plans disponibles** : 4 tiers (Starter, Pro, Agency, Enterprise)
- **Moyen de paiement** : Visa avec modification
- **Historique** : Factures avec téléchargement

### 10. Authentification
- **Login** : Formulaire élégant avec gradient
- **Register** : Multi-step avec organisation
- **Welcome** : Landing page marketing

### 11. Pages d'erreur
- **404** : Page non trouvée
- **403** : Accès refusé
- **500** : Erreur serveur

## 🧩 Composants Réutilisables

### StatsCard.vue
```vue
<StatsCard
    label="Total mots-clés"
    :value="127"
    icon="fas fa-key"
    icon-bg="primary"
    :change="12.5"
    change-unit="%"
/>
```

### LoadingSpinner.vue
```vue
<LoadingSpinner
    size="3rem"
    color="primary"
    message="Chargement en cours..."
/>
```

### EmptyState.vue
```vue
<EmptyState
    icon="fas fa-folder-open"
    title="Aucun projet"
    description="Créez votre premier projet"
    action-text="Créer un projet"
    action-icon="fas fa-plus"
    @action="createProject"
/>
```

## 🗄️ Pinia Stores

### auth.js
```javascript
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
auth.user              // Utilisateur connecté
auth.subscriptionPlan  // Plan d'abonnement
auth.hasFeature('api') // Vérifier une feature
auth.getLimits()       // Limites du plan
```

### notifications.js
```javascript
import { useNotificationsStore } from '@/stores/notifications';

const notifs = useNotificationsStore();
notifs.notifications   // Liste des notifications
notifs.unreadCount    // Nombre non lues
notifs.addNotification({ ... })
notifs.markAsRead(id)
```

### projects.js
```javascript
import { useProjectsStore } from '@/stores/projects';

const projects = useProjectsStore();
projects.activeProjects     // Projets actifs
projects.totalKeywords      // Total mots-clés
projects.addProject({ ... })
projects.updateProject(id, updates)
```

## 🛠️ Composables

### useNotifications()
```javascript
import { useNotifications } from '@/composables/useNotifications';

const { success, error, info, confirm } = useNotifications();

success('Projet créé!');
error('Erreur lors de la sauvegarde');

const confirmed = await confirm({
    title: 'Supprimer ?',
    text: 'Cette action est irréversible',
});
```

### useClipboard()
```javascript
import { useClipboard } from '@/composables/useClipboard';

const { copy } = useClipboard();

copy('texte à copier'); // Affiche un toast de confirmation
```

## 📐 Utilitaires

### formatters.js
```javascript
import {
    formatNumber,
    formatCurrency,
    formatDate,
    formatRelativeTime,
    formatPercentage,
    truncate,
    getDomain,
    formatFileSize
} from '@/utils/formatters';

formatNumber(1234567)           // "1 234 567"
formatCurrency(99.99)           // "99,99 €"
formatDate('2025-01-15')        // "15 janvier 2025"
formatRelativeTime('2025-01-15') // "il y a 2 jours"
formatPercentage(12.5)          // "12.5%"
truncate('Long text...', 20)    // "Long text..."
getDomain('https://example.com') // "example.com"
formatFileSize(1024000)         // "1000 KB"
```

## 🎯 Bonnes Pratiques

### 1. Utiliser les Composables
```vue
<script setup>
import { useNotifications } from '@/composables/useNotifications';

const { success } = useNotifications();

const save = () => {
    // ... logic
    success('Sauvegardé!');
};
</script>
```

### 2. Animations AOS
```vue
<div data-aos="fade-up" data-aos-delay="100">
    <!-- Contenu -->
</div>
```

### 3. Toast Notifications
```javascript
window.$toast?.success('Message de succès');
window.$toast?.error('Message d\'erreur');
window.$toast?.info('Information');
```

### 4. Modales SweetAlert2
```javascript
const result = await window.Swal.fire({
    title: 'Confirmation',
    text: 'Êtes-vous sûr ?',
    icon: 'warning',
    showCancelButton: true,
});

if (result.isConfirmed) {
    // Action confirmée
}
```

## 🚀 Commandes

```bash
# Installer les dépendances
npm install

# Développement (avec hot reload)
npm run dev

# Build de production
npm run build

# Aperçu du build
npm run preview
```

## 📱 Responsive

Toutes les pages sont optimisées pour :
- **Desktop** : Expérience complète
- **Tablet** : Layout adapté
- **Mobile** : Navigation simplifiée, sidebar pliable

## 🎨 Personnalisation

### Modifier les couleurs
Éditer `resources/sass/app.scss` :
```scss
$primary: #your-color;
```

### Modifier les animations
Éditer `resources/js/app.js` :
```javascript
AOS.init({
    duration: 1000, // Modifier la durée
    // ...
});
```

## 📝 Notes

- **Charts** : Chart.js pour simplicité, ApexCharts pour fonctionnalités avancées
- **Icons** : FontAwesome 6 avec préfixe `fas`, `fab`, `far`
- **Forms** : Validation Bootstrap 5 intégrée
- **Modals** : Bootstrap 5 + SweetAlert2 pour confirmations
- **Toasts** : SweetAlert2 en mode toast (top-right, 3s auto-close)

## 🔗 Ressources

- [Vue.js 3](https://vuejs.org/)
- [Bootstrap 5](https://getbootstrap.com/)
- [Inertia.js](https://inertiajs.com/)
- [Chart.js](https://www.chartjs.org/)
- [AOS](https://michalsnik.github.io/aos/)
- [SweetAlert2](https://sweetalert2.github.io/)
- [FontAwesome](https://fontawesome.com/)

---

**Version** : 1.0.0
**Dernière mise à jour** : Janvier 2025
