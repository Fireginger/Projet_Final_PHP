# ***Projet_Final_PHP***

## **Sommaire**

[TOC]

## I. **les membres du projet**
- *Eveillard Thomas*
- *Gadebille Baptiste*
- *Toribio Alexis*

## II. **installation du projet**

- Ouvrez l'explorateur de fichiers et accédez au dossier nommé **"xampp\htdocs"**.
- insérez le dossier **les membres du projet** dans le dossier **"htdocs"**.
- démarrez les Module **Apache** et **MySQL**.
- vous pouvez accéder au site web en ouvrant votre navigateur web et en saisissant l'adresse **"[localhost/Projet_Final_PHP](http://localhost/Projet_Final_PHP/connexion.php)"**.

## III. **la base de donnée** 

### 1. la table **user**:
```sql
CREATE TABLE User (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  solde DECIMAL(10,2),
  profile_picture VARCHAR(255),
  role VARCHAR(255)
);
```
### 2. la table **article**:
```sql
CREATE TABLE Article (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  publication_date DATE NOT NULL,
  author_id INT,
  image_link VARCHAR(255),
  FOREIGN KEY (author_id) REFERENCES User(id),
  catégorie VARCHAR(20)
);
```
### 3. la table **cart**:
```sql
CREATE TABLE Cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  article_id INT,
  FOREIGN KEY (user_id) REFERENCES User(id),
  FOREIGN KEY (article_id) REFERENCES Article(id),
  quantity INT
);
```
### 4. la table **stock**:
```sql
CREATE TABLE Stock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  article_id INT,
  quantity INT,
  FOREIGN KEY (article_id) REFERENCES Article(id)
);
```
### 5. la table **invoice**:
```sql
CREATE TABLE Invoice (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  transaction_date DATE NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  billing_address VARCHAR(255) NOT NULL,
  billing_city VARCHAR(255) NOT NULL,
  billing_postal_code VARCHAR(255) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES User(id)
);
```

## IV. **la page web** 

- les pages administrateurs
    - page gestion de la boutique création, modification et suppression
    - page gestion des membres modification des rols et suppression

- les pages des utilisateurs connécté
    - page profils
    - page boutique
    - page ajout d'un article
    - page modification d'un article
    - page du panier

- les pages des utilisateurs non connécté
    - page inscription
    - page connexion
    - page boutique