-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05-Mar-2020 às 04:25
-- Versão do servidor: 10.4.10-MariaDB
-- versão do PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_locacao`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtosUpdate_save` (IN `pidProduto` INT, IN `pcodigo` VARCHAR(70), IN `pdescricao` VARCHAR(150), IN `pvalorCompra` FLOAT, IN `pvalorAluguel` FLOAT, IN `pstatus` TINYINT(4), IN `pdtFabricacao` DATE, IN `pvlBaseAluguel` FLOAT, IN `ptipo` VARCHAR(60), IN `panotacoes` VARCHAR(100), IN `pidFornecedor` INT(11), IN `pidCategoria` INT(11))  BEGIN
  
    DECLARE vidProduto INT;
    
    SELECT idProduto INTO vidProduto
    FROM produtos
    WHERE idProduto = pidProduto;

    UPDATE produtos
    SET
   codigo = pcodigo,
   descricao = pdescricao,
   valorCompra = pvalorCompra,
   valorAluguel = pvalorAluguel,
   status = pstatus,
   dtFabricacao = pdtFabricacao,
   vlBaseAluguel = pvlBaseAluguel,
   tipo = ptipo,
   anotacoes = panotacoes,
   idFornecedor = pidFornecedor,
   idCategoria = pidCategoria
    
    WHERE idProduto = vidProduto;
    
    SELECT * FROM produtos WHERE idProduto = pidProduto;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_delete` (IN `pidProduto` INT)  BEGIN
  
    DECLARE vidProduto INT;
    
  SELECT idProduto INTO vidProduto
    FROM produtos
    WHERE idProduto = pidProduto;
    
    DELETE FROM produtos WHERE idProduto = pidProduto;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_save` (IN `pcodigo` VARCHAR(70), IN `pdescricao` VARCHAR(150), IN `pvalorCompra` FLOAT, IN `pvalorAluguel` FLOAT, IN `pstatus` TINYINT(4), IN `pdtFabricacao` DATE, IN `pvlBaseAluguel` FLOAT, IN `ptipo` VARCHAR(60), IN `panotacoes` VARCHAR(100), IN `pidFornecedor` INT(11), IN `pidCategoria` INT(11))  BEGIN
  
    DECLARE vidProduto INT;
    
  INSERT INTO produtos (codigo, descricao, valorCompra, valorAluguel, status, dtFabricacao, vlBaseAluguel, tipo, anotacoes, idFornecedor, idCategoria)
    VALUES(pcodigo, pdescricao, pvalorCompra, pvalorAluguel, pstatus, pdtFabricacao, pvlBaseAluguel, ptipo, panotacoes, pidFornecedor, pidCategoria);
    
    SET vidProduto = LAST_INSERT_ID();
    
    SELECT * FROM produtos WHERE idProduto = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuariosUpdate_save` (IN `pidUsuario` INT, IN `pnomeCompleto` VARCHAR(60), IN `pfuncao` VARCHAR(45), IN `pnomeUsuario` VARCHAR(45), IN `pemail` VARCHAR(45), IN `padministrador` TINYINT, IN `pfoto` VARCHAR(100))  BEGIN
  
    DECLARE vidUsuario INT;
    
    SELECT idUsuario INTO vidUsuario
    FROM usuarios
    WHERE idUsuario = pidUsuario;

    UPDATE usuarios
    SET
        nomeCompleto = pnomeCompleto, 
        funcao = pfuncao,
        nomeusuario = pnomeusuario,
        email = pemail,
        administrador = padministrador,
        foto = pfoto
    WHERE idUsuario = vidUsuario;
    
    SELECT * FROM usuarios WHERE idusuario = pidUsuario;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuarios_delete` (IN `pidusuario` INT)  BEGIN
  
    DECLARE vidUsuario INT;
    
  SELECT idUsuario INTO vidUsuario
    FROM usuarios
    WHERE idusuario = pidusuario;
    
    DELETE FROM usuarios WHERE idUsuario = pidUsuario;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuarios_save` (`pnomeCompleto` VARCHAR(60), `pfuncao` VARCHAR(45), `pnomeUsuario` VARCHAR(45), `psenha` VARCHAR(250), `pemail` VARCHAR(45), `padministrador` TINYINT, `pfoto` VARCHAR(100))  BEGIN
  
    DECLARE vidUsuario INT;
    
  INSERT INTO usuarios (nomeCompleto, funcao, nomeusuario, senha, email, administrador, foto)
    VALUES(pnomeCompleto, pfuncao, pnomeusuario, psenha, pemail, padministrador, pfoto);
    
    SET vidUsuario = LAST_INSERT_ID();
    
    SELECT * FROM usuarios WHERE idusuario = LAST_INSERT_ID();
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `aditamentos`
--

CREATE TABLE `aditamentos` (
  `idAditamento` int(11) NOT NULL,
  `diaVencimento` int(11) NOT NULL,
  `diaSemana` int(11) DEFAULT NULL,
  `semanaMes` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `seqAditamento` int(11) NOT NULL,
  `contrato_idContrato` int(11) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `idCategoria` int(11) NOT NULL,
  `descricao` varchar(15) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`idCategoria`, `descricao`, `dtCadastro`) VALUES
(1, 'containers', '2020-03-03 23:01:57'),
(2, 'elétricos', '2020-03-05 00:17:03'),
(3, 'mecânicos', '2020-03-05 00:17:03');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `idCliente` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `telefone1` varchar(15) NOT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `email1` varchar(45) NOT NULL,
  `email2` varchar(45) DEFAULT NULL,
  `endereco` varchar(45) NOT NULL,
  `tipoEndereco` varchar(45) NOT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  `cidade` varchar(25) NOT NULL,
  `bairro` varchar(20) NOT NULL,
  `numero` int(11) NOT NULL,
  `uf` char(2) NOT NULL,
  `cep` varchar(45) NOT NULL,
  `cpf` varchar(45) DEFAULT NULL,
  `rg` varchar(45) DEFAULT NULL,
  `cnpj` varchar(45) DEFAULT NULL,
  `ie` varchar(45) DEFAULT NULL,
  `tipoCliente` varchar(15) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `containers`
--

CREATE TABLE `containers` (
  `idContainer` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `numContainer` int(11) NOT NULL,
  `medida` float NOT NULL,
  `tipoPorta` varchar(45) DEFAULT NULL,
  `janelaLat` int(11) DEFAULT NULL,
  `janelaCirc` int(11) DEFAULT NULL,
  `forrado` tinyint(4) DEFAULT NULL,
  `eletrificado` tinyint(4) NOT NULL,
  `tomada` int(11) DEFAULT NULL,
  `lampada` int(11) DEFAULT NULL,
  `entradaAC` int(11) DEFAULT NULL,
  `sanitario` int(11) DEFAULT NULL,
  `chuveiro` tinyint(4) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contratos`
--

CREATE TABLE `contratos` (
  `idContrato` int(11) NOT NULL,
  `dtEmissao` datetime NOT NULL,
  `dtAprovacao` date NOT NULL,
  `custoEntrega` float NOT NULL,
  `custoRetirada` float NOT NULL,
  `notas` varchar(100) DEFAULT NULL,
  `valorAluguel` float NOT NULL,
  `dtInicio` datetime NOT NULL,
  `dtFim` date NOT NULL,
  `statusOrcamento` tinyint(4) NOT NULL,
  `codContrato` int(11) NOT NULL,
  `obra_idObra` int(11) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `faturas`
--

CREATE TABLE `faturas` (
  `idFatura` int(11) NOT NULL,
  `formaPagamento` varchar(20) NOT NULL,
  `diaVencimento` date NOT NULL,
  `especCobranca` varchar(60) DEFAULT NULL,
  `valorTotal` float NOT NULL,
  `dtEmissao` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `valorFrete` float DEFAULT NULL,
  `adicional` varchar(100) DEFAULT NULL,
  `abservacoes` varchar(100) DEFAULT NULL,
  `dtEnvio` date NOT NULL,
  `numNF` int(11) NOT NULL,
  `numBoletoInt` int(11) NOT NULL,
  `numBoletoBanco` int(11) NOT NULL,
  `valorPago` float NOT NULL,
  `dtPagamento` date NOT NULL,
  `dtVerificacao` date NOT NULL,
  `contrato_idcontrato` int(11) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `idFornecedor` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `telefone1` varchar(15) NOT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `email1` varchar(45) NOT NULL,
  `email2` varchar(45) DEFAULT NULL,
  `endereco` varchar(45) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `cidade` varchar(20) NOT NULL,
  `bairro` varchar(45) NOT NULL,
  `numero` int(11) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `uf` char(2) NOT NULL,
  `cep` varchar(45) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `fornecedores`
--

INSERT INTO `fornecedores` (`idFornecedor`, `nome`, `telefone1`, `telefone2`, `email1`, `email2`, `endereco`, `status`, `cidade`, `bairro`, `numero`, `complemento`, `uf`, `cep`, `dtCadastro`) VALUES
(1, 'Fornecedor X', '19 99999999', NULL, 'fornec@gmail.com', NULL, 'Rua x', 1, 'Araras', 'jd. do filtro', 344, NULL, 'SP', '09000344', '2020-03-03 22:09:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `historicoalugueis`
--

CREATE TABLE `historicoalugueis` (
  `idHistoricoAluguel` int(11) NOT NULL,
  `dtInicio` datetime NOT NULL,
  `dtFinal` date NOT NULL,
  `status` tinyint(4) NOT NULL,
  `contrato_idContrato` int(11) NOT NULL,
  `produto_idProduto` int(11) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `historicoaluguel`
--

CREATE TABLE `historicoaluguel` (
  `idHistorico` int(11) NOT NULL,
  `produto_idProduto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `obras`
--

CREATE TABLE `obras` (
  `idObra` int(11) NOT NULL,
  `codObra` int(11) NOT NULL,
  `respObra` varchar(45) NOT NULL,
  `telefone1` varchar(15) NOT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `email1` varchar(45) NOT NULL,
  `email2` varchar(45) DEFAULT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  `tipoEndereco` varchar(45) NOT NULL,
  `cidade` varchar(15) NOT NULL,
  `bairro` varchar(20) NOT NULL,
  `numero` int(11) NOT NULL,
  `uf` char(2) NOT NULL,
  `cep` varchar(45) NOT NULL,
  `endereco` varchar(45) NOT NULL,
  `cliente_idCliente` int(11) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `idProduto` int(11) NOT NULL,
  `codigo` varchar(15) NOT NULL,
  `descricao` varchar(150) NOT NULL,
  `valorCompra` float NOT NULL,
  `valorAluguel` float NOT NULL,
  `status` tinyint(4) NOT NULL,
  `dtFabricacao` date DEFAULT NULL,
  `vlBaseAluguel` float NOT NULL,
  `tipo` varchar(60) NOT NULL,
  `anotacoes` varchar(100) DEFAULT NULL,
  `idFornecedor` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`idProduto`, `codigo`, `descricao`, `valorCompra`, `valorAluguel`, `status`, `dtFabricacao`, `vlBaseAluguel`, `tipo`, `anotacoes`, `idFornecedor`, `idCategoria`, `dtCadastro`) VALUES
(8, 'X001', ':descricao2', 2344, 450, 1, '2000-04-15', 455, 'P', 'é', 1, 1, '2020-03-04 21:48:42'),
(10, 'TESTE02', 'esse é um teste de cadastro pelo sistema', 4000, 330, 0, '2009-03-09', 297, 'M', 'anotação testes', 1, 1, '2020-03-04 22:05:44'),
(11, 'A0012', 'AR-CONDICIONADO', 5753, 120, 1, '2020-03-20', 233, 'P', '', 1, 2, '2020-03-04 23:46:09'),
(16, 'COD03', ':descricao', 123, 3434, 0, '2009-09-12', 540, 'P', NULL, 1, 1, '2020-03-05 00:08:30'),
(17, 'GE002', 'Gerador de Energia à Diesel', 5409, 540, 0, '2000-12-01', 409.34, 'M', '', 1, 2, '2020-03-05 00:09:04');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nomeCompleto` varchar(60) NOT NULL,
  `funcao` varchar(45) NOT NULL,
  `nomeUsuario` varchar(45) NOT NULL,
  `senha` varchar(250) NOT NULL,
  `email` varchar(45) NOT NULL,
  `administrador` tinyint(4) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nomeCompleto`, `funcao`, `nomeUsuario`, `senha`, `email`, `administrador`, `foto`, `dtCadastro`) VALUES
(1, 'Elder Samuel', 'Programador', 'elder', '$2y$12$6UBTMz.ZC3ZEf8ytouE5ReApu0tjrDPOjmb7/vY5ooh0coVFXHMPS', 'eldersamuel98@gmail.com', 1, '/res/img/users/1583106585_elder-profile.jpg', '2020-02-26 10:45:06'),
(173, 'Administrador (TESTE)', 'teste', 'admin', '$2y$12$VN9ODzeRl2lKLhE84XmWF.lf5UbP9gfWFtEa7f1jEuyaeV9ILIhz6', 'eldersamuel98@gmail.com', 1, '/res/img/users/user-default.jpg', '2020-02-29 22:45:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `aditamentos`
--
ALTER TABLE `aditamentos`
  ADD PRIMARY KEY (`idAditamento`),
  ADD KEY `fk_aditamento_contrato1` (`contrato_idContrato`);

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCliente`);

--
-- Índices para tabela `containers`
--
ALTER TABLE `containers`
  ADD PRIMARY KEY (`idContainer`),
  ADD KEY `fk_produto_container1` (`idProduto`) USING BTREE;

--
-- Índices para tabela `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`idContrato`),
  ADD KEY `fk_contrato_obra1` (`obra_idObra`);

--
-- Índices para tabela `faturas`
--
ALTER TABLE `faturas`
  ADD PRIMARY KEY (`idFatura`),
  ADD KEY `fk_fatura_contrato1` (`contrato_idcontrato`);

--
-- Índices para tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`idFornecedor`);

--
-- Índices para tabela `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  ADD PRIMARY KEY (`idHistoricoAluguel`),
  ADD KEY `fk_contrato_has_produto_contrato2` (`contrato_idContrato`),
  ADD KEY `fk_contrato_has_produto_produto2` (`produto_idProduto`);

--
-- Índices para tabela `historicoaluguel`
--
ALTER TABLE `historicoaluguel`
  ADD PRIMARY KEY (`idHistorico`,`produto_idProduto`),
  ADD KEY `fk_contrato_has_produto_produto1` (`produto_idProduto`);

--
-- Índices para tabela `obras`
--
ALTER TABLE `obras`
  ADD PRIMARY KEY (`idObra`),
  ADD KEY `fk_obra_cliente1` (`cliente_idCliente`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idProduto`),
  ADD KEY `fk_produto_fornecedor1` (`idFornecedor`),
  ADD KEY `fk_produto_categoria1` (`idCategoria`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aditamentos`
--
ALTER TABLE `aditamentos`
  MODIFY `idAditamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contratos`
--
ALTER TABLE `contratos`
  MODIFY `idContrato` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `faturas`
--
ALTER TABLE `faturas`
  MODIFY `idFatura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `idFornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  MODIFY `idHistoricoAluguel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `obras`
--
ALTER TABLE `obras`
  MODIFY `idObra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idProduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `aditamentos`
--
ALTER TABLE `aditamentos`
  ADD CONSTRAINT `fk_aditamento_contrato1` FOREIGN KEY (`contrato_idContrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `containers`
--
ALTER TABLE `containers`
  ADD CONSTRAINT `fk_produto_container1` FOREIGN KEY (`idContainer`) REFERENCES `produtos` (`idProduto`);

--
-- Limitadores para a tabela `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `fk_contrato_obra1` FOREIGN KEY (`obra_idObra`) REFERENCES `obras` (`idObra`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `faturas`
--
ALTER TABLE `faturas`
  ADD CONSTRAINT `fk_fatura_contrato1` FOREIGN KEY (`contrato_idcontrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  ADD CONSTRAINT `fk_contrato_has_produto_contrato2` FOREIGN KEY (`contrato_idContrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contrato_has_produto_produto2` FOREIGN KEY (`produto_idProduto`) REFERENCES `produtos` (`idProduto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `historicoaluguel`
--
ALTER TABLE `historicoaluguel`
  ADD CONSTRAINT `fk_contrato_has_produto_contrato1` FOREIGN KEY (`idHistorico`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contrato_has_produto_produto1` FOREIGN KEY (`produto_idProduto`) REFERENCES `produtos` (`idProduto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `obras`
--
ALTER TABLE `obras`
  ADD CONSTRAINT `fk_obra_cliente1` FOREIGN KEY (`cliente_idCliente`) REFERENCES `clientes` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produto_categoria1` FOREIGN KEY (`idCategoria`) REFERENCES `categorias` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_fornecedor1` FOREIGN KEY (`idFornecedor`) REFERENCES `fornecedores` (`idFornecedor`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
