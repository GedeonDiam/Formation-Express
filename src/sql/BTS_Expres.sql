DROP DATABASE IF EXISTS BTS_Express;
CREATE DATABASE BTS_Express;
USE BTS_Express;

-- Table des étudiants
CREATE TABLE etudiants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role VARCHAR(100) NOT NULL,
    specialite VARCHAR(100) NOT NULL,
    cours VARCHAR(250) NOT NULL,
    temps VARCHAR(100) NOT NULL,
    mdp VARCHAR(255) NOT NULL
);

-- Table des enseignants
CREATE TABLE enseignants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role VARCHAR(100) NOT NULL,
    diplome VARCHAR(100) NOT NULL,
    domaine VARCHAR(100) NOT NULL,
     mdp VARCHAR(255) NOT NULL
);

-- Table des cours
DROP TABLE IF EXISTS cours;
CREATE TABLE cours (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(155) NOT NULL,
    description TEXT,
    id_enseignant INT,
    categorie VARCHAR(100),
    image VARCHAR(105),  -- Nouveau champ pour stocker le chemin/nom de l'image
    fichier VARCHAR(100),  -- Nouveau champ pour stocker le chemin/nom du fichier
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_enseignant) REFERENCES enseignants(id)
);

CREATE TABLE inscriptions_cours (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_etudiant INT,
    id_cours INT, 
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    temps_total INT DEFAULT 0,  -- Ajout du champ temps_total en secondes
    FOREIGN KEY (id_etudiant) REFERENCES etudiants(id),
    FOREIGN KEY (id_cours) REFERENCES cours(id)
);

-- Table des quiz
CREATE TABLE quizz (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by_teacher INT,
    FOREIGN KEY (created_by_teacher) REFERENCES enseignants(id) ON DELETE CASCADE
);

-- Table des questions
CREATE TABLE questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content TEXT NOT NULL,
    quizz_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quizz_id) REFERENCES quizz(id) ON DELETE CASCADE
);

-- Table des réponses
CREATE TABLE answers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content TEXT NOT NULL,
    is_correct BOOLEAN NOT NULL,
    question_id INT,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Table des résultats des quiz
CREATE TABLE quizz_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quizz_id INT,
    student_id INT,
    score INT,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quizz_id) REFERENCES quizz(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES etudiants(id) ON DELETE CASCADE
);

-- Table du forum
CREATE TABLE forum (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by_teacher INT,
    FOREIGN KEY (created_by_teacher) REFERENCES enseignants(id) ON DELETE CASCADE
);

-- Table des réponses du forum
CREATE TABLE forum_replies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by_student INT,
    forum_id INT,
    FOREIGN KEY (created_by_student) REFERENCES etudiants(id) ON DELETE CASCADE,
    FOREIGN KEY (forum_id) REFERENCES forum(id) ON DELETE CASCADE
);
