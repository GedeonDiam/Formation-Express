DROP DATABASE IF EXISTS BTS_Express;
CREATE DATABASE BTS_Express;
USE BTS_Express;

-- Table des étudiants
CREATE TABLE etudiants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    specialite VARCHAR(100) NOT NULL,
    mdp VARCHAR(255) NOT NULL
);

-- Table des enseignants
CREATE TABLE enseignants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    diplome VARCHAR(100) NOT NULL,
    domaine VARCHAR(100) NOT NULL,
     mdp VARCHAR(255) NOT NULL
);

-- Table des cours
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    file_url VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    created_by_teacher INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by_teacher) REFERENCES enseignants(id) ON DELETE SET NULL
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
