# 📘 Guide Utilisateur - SEO Master Pro

## Table des Matières

- [Introduction](#introduction)
- [Démarrage Rapide](#démarrage-rapide)
- [Gestion des Projets](#gestion-des-projets)
- [Suivi des Mots-clés](#suivi-des-mots-clés)
- [Analyse des Backlinks](#analyse-des-backlinks)
- [Audits SEO](#audits-seo)
- [Rapports et Exports](#rapports-et-exports)
- [Webhooks et Intégrations](#webhooks-et-intégrations)
- [Paramètres et Notifications](#paramètres-et-notifications)
- [FAQ](#faq)

---

## Introduction

Bienvenue sur **SEO Master Pro**, votre plateforme complète de suivi et d'optimisation SEO.

### Qu'est-ce que SEO Master Pro ?

SEO Master Pro est une solution SaaS professionnelle qui vous permet de :
- 📊 **Suivre vos positions** sur les moteurs de recherche
- 🔗 **Analyser vos backlinks** et leur qualité
- 🔍 **Auditer vos sites web** pour détecter les problèmes SEO
- 📈 **Générer des rapports** professionnels pour vos clients
- 🔔 **Recevoir des alertes** en temps réel sur vos performances

### Prérequis

- Un navigateur web moderne (Chrome, Firefox, Safari, Edge)
- Une connexion internet stable
- Un compte SEO Master Pro actif

---

## Démarrage Rapide

### 1. Création de votre compte

1. Accédez à [https://seo-master-pro.com/register](https://seo-master-pro.com/register)
2. Remplissez le formulaire d'inscription :
   - Nom complet
   - Adresse email professionnelle
   - Mot de passe sécurisé (min. 8 caractères)
3. Validez votre email via le lien de confirmation
4. Connectez-vous à votre tableau de bord

### 2. Premier Projet

**Créer votre premier projet en 3 étapes :**

1. Cliquez sur **"Nouveau Projet"** dans votre dashboard
2. Remplissez les informations :
   ```
   Nom du projet : Mon Site E-commerce
   URL : https://mon-site.fr
   Pays cible : France
   Langue : Français
   ```
3. Cliquez sur **"Créer le Projet"**

### 3. Ajouter vos Mots-clés

1. Dans votre projet, cliquez sur **"Ajouter des mots-clés"**
2. Entrez vos mots-clés (un par ligne) :
   ```
   chaussures femme en ligne
   boutique mode paris
   vêtements tendance 2024
   ```
3. Définissez les paramètres :
   - Moteur de recherche : Google
   - Pays : France
   - Device : Desktop/Mobile
4. Lancez le premier suivi

---

## Gestion des Projets

### Créer un Nouveau Projet

**Via l'interface web :**
1. Dashboard → Bouton **"Nouveau Projet"**
2. Remplissez le formulaire
3. Enregistrez

**Via l'API :**
```bash
curl -X POST https://seo-master-pro.com/api/v1/projects \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Mon Site",
    "url": "https://example.com",
    "target_country": "FR",
    "target_language": "fr"
  }'
```

### Modifier un Projet

1. Accédez au projet
2. Cliquez sur **⚙️ Paramètres**
3. Modifiez les informations
4. Enregistrez les modifications

### Archiver/Supprimer un Projet

**Archiver** (recommandé) :
- Préserve l'historique
- Peut être restauré
- Ne compte pas dans votre quota

**Supprimer** (définitif) :
- ⚠️ Suppression irréversible
- Perte de toutes les données
- Libère votre quota

---

## Suivi des Mots-clés

### Ajouter des Mots-clés

**Méthode 1 : Ajout manuel**
1. Projet → **"Mots-clés"** → **"Ajouter"**
2. Entrez le mot-clé
3. Définissez l'URL cible
4. Sélectionnez les paramètres

**Méthode 2 : Import CSV**
1. Préparez votre fichier CSV :
   ```csv
   keyword,target_url,search_engine,country,language
   "seo tools","https://example.com/tools","google","FR","fr"
   ```
2. Cliquez sur **"Importer CSV"**
3. Sélectionnez votre fichier
4. Validez l'import

### Vérifier les Positions

**Vérification manuelle :**
- Cliquez sur 🔄 à côté d'un mot-clé
- Ou utilisez **"Vérifier tout"** pour tous les mots-clés

**Vérification automatique :**
- Configurez dans **Paramètres → Automatisation**
- Fréquences disponibles : Quotidienne, Hebdomadaire, Mensuelle

### Analyser l'Évolution

**Graphiques de tendance :**
- Vue sur 7, 30, 90, ou 365 jours
- Filtres par mot-clé, position, volume

**Indicateurs clés :**
- 📈 **Position actuelle** : Votre position sur Google
- 🎯 **Meilleure position** : Votre meilleur classement historique
- 📉 **Évolution** : Changement depuis la dernière vérification
- 🔍 **Volume de recherche** : Nombre de recherches mensuelles

---

## Analyse des Backlinks

### Découverte de Backlinks

**Ajout automatique** (si configuré) :
- Intégration Ahrefs, Moz, ou Majestic
- Découverte quotidienne
- Notification des nouveaux liens

**Ajout manuel :**
1. Projet → **"Backlinks"** → **"Ajouter"**
2. Remplissez :
   ```
   URL source : https://blog-externe.com/article
   URL cible : https://mon-site.fr/page
   Texte d'ancre : cliquez ici
   Type de lien : DoFollow / NoFollow
   ```

### Évaluation de la Qualité

**Métriques importantes :**
- **Domain Authority (DA)** : 0-100 (plus = mieux)
- **Page Authority (PA)** : 0-100 (plus = mieux)
- **Spam Score** : 0-100 (moins = mieux)

**Classification automatique :**
- ✅ **Haute qualité** : DA > 60, Spam < 10
- ⚠️ **Moyenne qualité** : DA 30-60, Spam 10-30
- ❌ **Faible qualité** : DA < 30, Spam > 30

### Surveiller les Backlinks Perdus

1. Allez dans **"Backlinks"** → Filtre **"Perdus"**
2. Analysez pourquoi le lien a été perdu
3. Actions possibles :
   - Contacter le webmaster
   - Remplacer par un nouveau lien
   - Désavouer si toxique

---

## Audits SEO

### Lancer un Audit

1. Projet → **"Audits"** → **"Nouvel Audit"**
2. Configurez les paramètres :
   ```
   Profondeur d'analyse : 3 niveaux
   Pages maximum : 100
   Vérifications :
   ✓ Balises meta
   ✓ Structure des titres
   ✓ Images alt
   ✓ Liens cassés
   ✓ Vitesse de chargement
   ```
3. Lancez l'audit (durée : 5-30 minutes selon la taille)

### Interpréter les Résultats

**Score global : 0-100**
- 🟢 **85-100** : Excellent
- 🟡 **70-84** : Bon
- 🟠 **50-69** : Moyen
- 🔴 **0-49** : Nécessite des améliorations

**Catégories analysées :**
1. **SEO On-Page** (30%)
   - Balises title, meta description
   - Structure Hn
   - Contenu dupliqué

2. **Technique** (30%)
   - Vitesse de chargement
   - Mobile-friendly
   - HTTPS, Sitemap, Robots.txt

3. **Contenu** (20%)
   - Qualité et longueur
   - Mots-clés
   - Fraîcheur

4. **UX** (20%)
   - Navigation
   - Liens internes
   - Accessibilité

### Corriger les Erreurs

Pour chaque erreur détectée :
1. **Priorité** : Critique, Élevée, Moyenne, Faible
2. **Description** : Explication du problème
3. **Recommandation** : Comment le corriger
4. **Ressources** : Liens vers la documentation

---

## Rapports et Exports

### Générer un Rapport

1. Projet → **"Rapports"** → **"Générer un Rapport"**
2. Sélectionnez :
   - **Période** : 7 jours, 30 jours, 90 jours, personnalisée
   - **Type** : Hebdomadaire, Mensuel, Trimestriel
   - **Sections** : Rankings, Backlinks, Trafic, Audits
3. Choisissez le format : **PDF**, **Excel**, **CSV**
4. Cliquez sur **"Générer"**

### Exporter des Données

**Export Mots-clés :**
```
Projet → Mots-clés → Bouton "Exporter"
Formats : CSV, Excel, JSON
```

**Export Backlinks :**
```
Projet → Backlinks → Bouton "Exporter"
Formats : CSV, Excel, JSON
```

### Rapports Automatiques

**Programmation :**
1. Paramètres → **"Rapports Automatiques"**
2. Créez un nouveau programme :
   ```
   Fréquence : Hebdomadaire (chaque Lundi)
   Format : PDF
   Destinataires : client@example.com, manager@agency.com
   ```
3. Activez le programme

---

## Webhooks et Intégrations

### Configurer un Webhook

1. Paramètres → **"Webhooks"**
2. Cliquez sur **"Ajouter un Webhook"**
3. Configurez :
   ```
   URL : https://mon-app.com/webhook
   Événements :
   ✓ Changement de position
   ✓ Nouveau backlink
   ✓ Audit terminé
   Secret : votre_secret_webhook
   ```

### Format des Webhooks

**Exemple de payload :**
```json
{
  "event": "ranking.changed",
  "timestamp": "2024-01-15T10:30:00Z",
  "data": {
    "keyword_id": 123,
    "keyword": "seo tools",
    "old_position": 15,
    "new_position": 12,
    "change": +3,
    "project": {
      "id": 1,
      "name": "Mon Site"
    }
  }
}
```

### Intégrations Disponibles

**Outils SEO :**
- Google Search Console
- Google Analytics 4
- Ahrefs API
- Moz API
- SEMrush API

**Autres :**
- Slack notifications
- Zapier automation
- Make (Integromat)
- Custom API

---

## Paramètres et Notifications

### Préférences de Notification

1. Profil → **"Notifications"**
2. Configurez pour chaque type :

**Changements de Position :**
- ✅ Email immédiat si changement > 5 positions
- ✅ Notification in-app
- ❌ SMS (forfaits Pro et Agency uniquement)

**Nouveaux Backlinks :**
- ✅ Résumé quotidien par email
- ✅ Notification in-app

**Rapports :**
- ✅ Rapport hebdomadaire automatique
- ✅ Rapport mensuel automatique

### Gestion de l'Abonnement

**Voir votre forfait actuel :**
```
Paramètres → Abonnement
```

**Limites par forfait :**

| Forfait | Projets | Mots-clés | Backlinks | Rapports/mois |
|---------|---------|-----------|-----------|---------------|
| Free | 3 | 10 | 50 | 1 |
| Starter | 10 | 100 | 500 | 10 |
| Professional | 50 | 1,000 | 5,000 | 100 |
| Agency | 200 | 10,000 | 50,000 | 1,000 |
| Enterprise | Illimité | Illimité | Illimité | Illimité |

**Changer de forfait :**
1. Cliquez sur **"Mettre à niveau"**
2. Sélectionnez le nouveau forfait
3. Complétez le paiement
4. Activation immédiate

---

## FAQ

### Questions Générales

**Q : À quelle fréquence les positions sont-elles vérifiées ?**
R : Par défaut quotidiennement, mais vous pouvez configurer une vérification hebdomadaire ou mensuelle dans les paramètres du projet.

**Q : Les données historiques sont-elles conservées ?**
R : Oui, toutes vos données sont conservées indéfiniment, même si vous changez de forfait.

**Q : Puis-je transférer un projet à un autre utilisateur ?**
R : Oui, dans Paramètres du Projet → Transfert → Entrez l'email du destinataire.

### Mots-clés

**Q : Combien de mots-clés puis-je suivre ?**
R : Cela dépend de votre forfait. Voir le tableau ci-dessus.

**Q : Puis-je suivre des mots-clés dans plusieurs pays ?**
R : Oui, vous pouvez créer un mot-clé pour chaque pays ciblé.

**Q : Le volume de recherche est-il actualisé ?**
R : Oui, mensuellement via les données Google Keyword Planner.

### Backlinks

**Q : D'où proviennent les données de backlinks ?**
R : Vous pouvez les ajouter manuellement ou les importer via des intégrations (Ahrefs, Moz, Majestic).

**Q : Que faire avec les backlinks toxiques ?**
R : Exportez-les au format Google Disavow et soumettez-les via Search Console.

### Audits

**Q : Combien de temps prend un audit ?**
R : Entre 5 et 30 minutes selon la taille du site et la profondeur d'analyse.

**Q : Les audits consomment-ils mon quota ?**
R : Non, les audits sont illimités sur tous les forfaits.

### Rapports

**Q : Puis-je personnaliser les rapports ?**
R : Oui, dans les paramètres de génération de rapport, vous pouvez choisir les sections à inclure.

**Q : Puis-je ajouter mon logo aux rapports ?**
R : Oui, sur les forfaits Professional et supérieurs (Paramètres → White Label).

### Support

**Q : Comment contacter le support ?**
R :
- 📧 Email : support@seo-master-pro.com
- 💬 Chat en direct : Disponible dans l'application
- 📚 Documentation : https://docs.seo-master-pro.com

**Q : Quel est le délai de réponse du support ?**
R :
- Free/Starter : 48h ouvrées
- Professional : 24h ouvrées
- Agency/Enterprise : 4h ouvrées (support prioritaire)

---

## Ressources Supplémentaires

### Documentation

- [Guide de Démarrage Rapide](./QUICK_START.md)
- [Documentation API](./API.md)
- [Guide de Déploiement](./DEPLOYMENT.md)
- [Sécurité](./SECURITY.md)

### Tutoriels Vidéo

- [Créer votre premier projet](https://youtube.com/...)
- [Optimiser votre suivi de mots-clés](https://youtube.com/...)
- [Analyser vos backlinks](https://youtube.com/...)
- [Générer des rapports professionnels](https://youtube.com/...)

### Communauté

- [Forum Communautaire](https://community.seo-master-pro.com)
- [Blog SEO](https://blog.seo-master-pro.com)
- [Changelog](https://changelog.seo-master-pro.com)

---

**© 2024 SEO Master Pro - Tous droits réservés**

*Ce guide a été mis à jour le 19 novembre 2025*
