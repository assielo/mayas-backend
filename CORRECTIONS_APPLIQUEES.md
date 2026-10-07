# Corrections appliquées — mayas-backend

## 1. Bugs bloquants corrigés

- **`routes/web.php`** : la route `/api/agents` était mal imbriquée (fermeture
  manquante), ce qui cassait le chargement de TOUT le fichier de routes.
  Réécrit proprement, avec :
  - Login/logout gérés par `Auth\LoginController` (au lieu de closures dupliquées).
  - `/dashboard`, `/profile` et l'API `/api/*` protégés par le middleware `auth`
    (avant, `/dashboard` était accessible sans être connecté).
- **`app/Http/Controllers/AgentController.php`** : contenait du code hors
  classe (erreur de syntaxe fatale). Réécrit avec toutes les méthodes
  (`index`, `show`, `store`, `update`, `destroy`, `exportWave`, `downloadWaveCsv`).
- **`app/Http/Controllers/Api/AgentController.php`** : namespace faux, classe
  mal nommée, méthodes hors classe. **Supprimé** — son contenu utile a été
  fusionné dans `AgentController.php` pour éviter la duplication.
- **Migration `agents`** (+ nouvelles migrations `sites`/`postes`) : déjà
  corrigées à l'étape précédente, alignées sur la vraie base `mayas_sgbd_db`.

## 2. Le dashboard est maintenant branché sur la vraie base

Avant : `index_dashboard.blade.php` simulait tout en mémoire avec un tableau
JS de 4 agents fictifs (`agentsData`), et la fonction censée charger les
vraies données (`chargerDonneesDepuisBDD`) n'était **jamais appelée**. Le
bouton "Nouvel Agent" ne faisait qu'ajouter une ligne locale, perdue au
rechargement de la page.

Changements :
- Chargement réel au démarrage (`/api/agents`, `/api/sites`) via une URL
  relative (fini le `http://localhost:8080/...` codé en dur qui cassait dès
  qu'on changeait de port/domaine).
- Le bouton "Nouvel Agent" envoie maintenant un vrai `POST /api/agents`
  (avec jeton CSRF), et la liste est rechargée depuis le serveur après
  création.
- Le matricule est généré côté serveur (cohérent avec les ~400 agents déjà
  en base), le champ du formulaire n'est plus qu'indicatif.
- La liste déroulante "Site" (formulaire d'ajout) et le filtre "Site"
  (barre de recherche) sont désormais peuplés dynamiquement depuis les 147
  sites réels, au lieu de 4 sites fictifs codés en dur à trois endroits
  différents dans le fichier.
- Correction d'un bug de nommage : le mapping chargeait `salaireBase` mais
  les tableaux affichaient `item.salaire` → les montants ne s'affichaient
  jamais correctement. Corrigé.

## 3. Nettoyage

- Suppression des 2 vues de login dupliquées (`resources/views/login.blade.php`
  et `resources/login.blade.php`) — seule `resources/views/auth/login.blade.php`
  est conservée, déjà correctement branchée sur `/login`.
- `.env` exclu du zip (contenait les identifiants MySQL en clair).

## 4. Limites connues, à traiter dans une prochaine étape

Ces points ne sont **pas** des bugs introduits par moi, mais des trous
fonctionnels qui existaient déjà dans la conception (le formulaire/l'UI
prévoyaient des champs que la vraie base ne stocke pas) :

- **Rôle Agent/Superviseur** : pas de colonne dédiée dans la vraie table
  `agents`. Tous les agents s'affichent comme "Agent" pour l'instant. À
  faire évoluer via la table `postes` (ex. poste "Superviseur") ou l'ajout
  d'une colonne `role`.
- **Vacation (Jour/Nuit)** : pas de colonne en base → la "Grille des Postes"
  affichera 0 partout tant qu'elle n'est pas ajoutée à la table `agents`.
- **Prime** : pas de colonne en base → toujours à 0 dans l'onglet "Pré-Paie
  & Wave". Si tu veux vraiment gérer des primes, il faut une colonne dédiée
  (et décider si elle est mensuelle, ponctuelle, etc.).
- **Date de début de contrat** : le formulaire "Nouvel Agent" n'a pas de
  champ pour ça ; la date du jour est utilisée par défaut. Facile à ajouter
  si besoin.
- **Modifier / Supprimer un agent** : les routes et le contrôleur backend
  sont prêts (`PUT`/`DELETE /api/agents/{id}`), mais l'interface (boutons
  d'édition/suppression dans les tableaux) n'a pas encore été ajoutée —
  actuellement seul "Voir Fiche" existe.
- **Onglets "Sites & Clients" et "Postes & Grille"** : ils listent bien les
  vrais sites maintenant, mais il n'y a pas encore de formulaire pour créer
  un site ou un poste depuis l'interface (uniquement en lecture).
- **`generer_decharge_dompdf.php`** (646 lignes, gestion des décharges de
  matériel) et **`routes/api.php`** (`/api/get_agents`, redondant) n'ont pas
  été touchés — ils fonctionnent de manière autonome, isolés du reste. À
  intégrer proprement dans Laravel si tu veux les garder.

Dis-moi lesquels de ces points tu veux qu'on attaque ensuite.
