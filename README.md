# Sakura Ramen — Premiun Japanese Restaurant

Site web premium pour Sakura Ramen, avec une séparation claire entre Frontend et Backend.

## Architecture "Visible Split"
Le projet est organisé pour une clarté maximale et une scalabilité moderne :

```text
/Sakura-Ramen
├── /backend
│   ├── /src           # Logique PHP MVC (Noyau, Controllers, Models)
│   ├── /realtime      # Serveur Node.js (Socket.io)
│   └── /database      # Scripts SQL (init.sql)
├── /frontend
│   ├── /public        # WEB ROOT (index.php, CSS, JS, Images)
│   └── /templates     # Vues PHP (Frontend & Admin)
├── /docker            # Configuration Docker complète
├── docker-compose.yml
└── README.md
```

## Installation & Lancement

### 1. Mode Docker (Production / Dev Moderne)
1. `docker-compose up -d --build`
2. Accès Site : `http://localhost:8080`
3. Accès Admin : `http://localhost:8080/admin` (admin / password)

### 2. Mode GitHub (Export)
Le dépôt est déjà initialisé. Pour pousser vos modifications sur votre compte GitHub :
```bash
git push -u origin main
```

## Stack Technique
- **PHP 8.2 MVC** (Noyau sur mesure)
- **Node.js 18** (Temps réel)
- **MySQL 8.0** (Données relationnelles)
- **MongoDB 6.0** (Logs & Événements)
- **Vanilla CSS** (Thème Premium Sakura)

## Sécurité & SEO
- Protection CSRF et XSS.
- Schéma LocalBusiness JSON-LD inclus pour le SEO.
- Design responsive mobile-first.

---
**Développé avec excellence par Antigravity.**
