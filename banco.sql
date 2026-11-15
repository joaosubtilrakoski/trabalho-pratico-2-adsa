CREATE DATABASE IF NOT EXISTS locadora_carros;
USE locadora_carros;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    endereco VARCHAR(200)
);

CREATE TABLE carros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    ano INT NOT NULL,
    placa VARCHAR(10) NOT NULL UNIQUE,
    valor_diaria DECIMAL(10,2) NOT NULL,
    status ENUM('Disponível','Alugado','Manutenção') DEFAULT 'Disponível'
);

CREATE TABLE locacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    carro_id INT NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL,
    status ENUM('Ativa','Finalizada','Cancelada') DEFAULT 'Ativa',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (carro_id) REFERENCES carros(id)
);