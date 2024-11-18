INSERT INTO Clientes (Nome, Email, Telefone) 
VALUES 
('João Silva', 'joao.silva@example.com', '11999999999'),
('Maria Oliveira', 'maria.oliveira@example.com', '11988888888');

INSERT INTO Colaboradores (Nome, Email) 
VALUES 
('Carlos Souza', 'carlos.souza@example.com'),
('Ana Lima', 'ana.lima@example.com');

INSERT INTO Chamados (ClienteID, Descricao, Criticidade) 
VALUES 
(1, 'Erro ao acessar o sistema.', 'alta'),
(2, 'Problema com configuração de e-mail.', 'média');


UPDATE Chamados 
SET Status = 'em andamento' 
WHERE ChamadoID = 1;


UPDATE Chamados 
SET ColaboradorID = 1 
WHERE ChamadoID = 1;

SELECT * FROM Chamados WHERE Status = 'aberto';

SELECT * FROM Chamados WHERE Criticidade = 'alta';

SELECT * FROM Chamados WHERE ColaboradorID = 1;

SELECT * FROM Clientes;
