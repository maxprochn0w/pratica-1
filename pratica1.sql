CREATE DATABASE SuporteTecnico;
USE SuporteTecnico;

CREATE TABLE Clientes (
    ClienteID INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Telefone VARCHAR(15)
);

CREATE TABLE Colaboradores (
    ColaboradorID INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE Chamados (
    ChamadoID INT AUTO_INCREMENT PRIMARY KEY,
    ClienteID INT NOT NULL,
    Descricao TEXT NOT NULL,
    Criticidade ENUM('baixa', 'média', 'alta') NOT NULL,
    Status ENUM('aberto', 'em andamento', 'resolvido') DEFAULT 'aberto',
    DataAbertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    ColaboradorID INT DEFAULT NULL,
    FOREIGN KEY (ClienteID) REFERENCES Clientes(ClienteID) ON DELETE CASCADE,
    FOREIGN KEY (ColaboradorID) REFERENCES Colaboradores(ColaboradorID) ON DELETE SET NULL
    );
    
