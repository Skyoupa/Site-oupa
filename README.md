# 🎮 Oupafamilly - Plateforme Esports

**Site officiel :** [oupafamilly.com](https://oupafamilly.com)

Plateforme communautaire dédiée à l'esport, spécialisée Counter-Strike 2, avec système de tournois, équipes, économie virtuelle et analytics avancés.

## 🚀 Technologies

- **Frontend :** React 18 + Tailwind CSS
- **Backend :** PHP 8+ + MySQL
- **Hébergement :** OVH Web Hosting
- **PWA :** Service Worker + Manifest

## ⚡ Fonctionnalités

### 👥 **Communauté**
- Système d'authentification JWT
- Profils utilisateurs personnalisables
- Gestion des rôles (admin, modérateur, membre)
- Chat communautaire en temps réel

### 🏆 **Tournois & Équipes**
- Création et gestion de tournois CS2
- Système d'équipes avec capitaine
- Inscription aux tournois solo ou équipe
- Planification et suivi des matchs

### 💰 **Économie Virtuelle**
- Monnaie virtuelle (coins)
- Marketplace intégré
- Système de paris sur tournois
- Quêtes quotidiennes et récompenses

### 🎯 **Gamification**
- Système ELO automatique
- 58+ badges et achievements
- Classements et statistiques
- Progression par niveaux

### 📊 **Administration**
- Dashboard analytics complet
- Gestion des utilisateurs
- Modération du contenu
- Statistiques détaillées

### 📚 **Contenu**
- 12+ tutoriels CS2 professionnels
- Guides stratégiques
- Ressources d'apprentissage
- Section news et actualités

## 📁 Structure du Projet

```
oupafamilly_production/
├── index.html              # Frontend React optimisé
├── static/                 # Assets CSS/JS minifiés
│   ├── css/
│   └── js/
├── logo.png               # Logo principal
├── icons/                 # Icônes PWA (8 tailles)
├── manifest.json          # Configuration PWA
├── sw.js                  # Service Worker
└── api/                   # Backend PHP
    ├── index.php          # API principale
    ├── config.php         # Configuration
    ├── database.php       # Base de données
    ├── auth.php           # Authentification
    └── .htaccess          # Configuration serveur
```

## 🔧 Configuration

### Base de Données MySQL
```php
DB_HOST: oupafaj803.mysql.db
DB_NAME: oupafaj803
DB_USER: oupafaj803
```

### Domaines Autorisés
```php
ALLOWED_ORIGINS: [
    'https://oupafamilly.com',
    'https://www.oupafamilly.com'
]
```

## 📋 APIs Disponibles

### Authentification
- `POST /api/auth/register` - Inscription
- `POST /api/auth/login` - Connexion
- `GET /api/auth/me` - Profil utilisateur

### Tournois
- `GET /api/tournaments/` - Liste des tournois
- `POST /api/tournaments/{id}/register` - Inscription

### Équipes
- `GET /api/teams/my-teams` - Équipes de l'utilisateur
- `POST /api/teams/create` - Créer une équipe

### Économie
- `GET /api/currency/balance` - Solde utilisateur
- `GET /api/currency/marketplace` - Marketplace

### Administration
- `GET /api/analytics/overview` - Analytics dashboard

## 🚀 Déploiement

### Prérequis OVH
- Hébergement Web (PHP 7.4+)
- Base de données MySQL
- Certificat SSL activé

### Installation
1. Télécharger tous les fichiers vers `www/`
2. Configurer la base de données MySQL
3. Activer HTTPS
4. Vérifier les permissions (755 pour dossiers, 644 pour fichiers)

### Identifiants Admin
- **Email :** admin@oupafamilly.com
- **Mot de passe :** Oupafamilly2024!

## 🛡️ Sécurité

- Authentification JWT sécurisée
- Validation et sanitisation des données
- Protection CORS configurée
- Logs d'erreurs activés
- Limitation de taux de requêtes

## 📱 PWA (Progressive Web App)

- Installation sur mobile
- Mode hors-ligne
- Notifications push
- Icônes adaptatives
- Expérience app native

## 🎨 Design

- Interface gaming moderne
- Mode sombre/clair
- Design responsive (mobile/desktop)
- Animations fluides
- UX optimisée

## 📈 Performance

- Build React optimisé (222KB JS, 27KB CSS)
- Images optimisées
- Mise en cache intelligente
- Service Worker pour offline
- Temps de chargement < 2s

## 📞 Support

- **Email :** contact@oupafamilly.com
- **Site :** [oupafamilly.com](https://oupafamilly.com)

## 📄 Licence

Tous droits réservés © 2024 Oupafamilly

---

**🎮 Rejoignez la communauté gaming d'élite !**