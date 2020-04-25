-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25-Abr-2020 às 18:55
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contratosUpdate_save` (IN `pidContrato` INT, IN `pcodContrato` INT, IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `pdtAprovacao` DATETIME, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pnotas` VARCHAR(100), IN `pvalorAluguel` FLOAT, IN `pdtInicio` DATETIME, IN `pdtFim` DATETIME, IN `pstatusOrcamento` TINYINT(4))  BEGIN
  
    DECLARE vidContrato INT;
    
    SELECT idContrato INTO vidContrato
    FROM contratos
    WHERE idContrato = pidContrato;

    UPDATE contratos
    SET
      idContrato = pidContrato,
      codContrato = pcodContrato,
      dtEmissao = pdtEmissao,
      dtAprovacao = pdtAprovacao,
      custoEntrega = pcustoEntrega,
      custoRetirada = pcustoRetirada,
      notas = pnotas,
      valorAluguel = pvalorAluguel,
      dtInicio = pdtInicio,
      dtFim = pdtFim,
      statusOrcamento = pstatusOrcamento,
      codContrato = pcodContrato,
      obra_idObra = pobra_idObra
    
    WHERE idContrato = vidContrato;
    
    SELECT * FROM contratos WHERE idContrato = pidContrato;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contratos_delete` (IN `pidContrato` INT)  BEGIN
  
    DECLARE vidContrato INT;
    
  SELECT idContrato INTO vidContrato
    FROM contratos
    WHERE idContrato = pidContrato;
    
    DELETE FROM contratos WHERE idContrato = pidContrato;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contratos_save` (IN `pcodContrato` INT, IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `pdtAprovacao` DATETIME, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pnotas` VARCHAR(100), IN `pvalorAluguel` FLOAT, IN `pdtInicio` DATETIME, IN `pdtFim` DATETIME, IN `pstatusOrcamento` TINYINT(4))  BEGIN
  
    DECLARE vidContrato INT;
    
  INSERT INTO contratos (codContrato, obra_idObra, dtEmissao, dtAprovacao, custoEntrega, custoRetirada, notas, valorAluguel, dtInicio, dtFim, statusOrcamento)
    VALUES(pcodContrato, pobra_idObra, pdtEmissao, pdtAprovacao, pcustoEntrega, pcustoRetirada, pnotas, pvalorAluguel, pdtInicio, pdtFim, pstatusOrcamento);
    
    SET vidContrato = LAST_INSERT_ID();
    
    SELECT * FROM contratos WHERE idContrato = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fornecedoresUpdate_save` (IN `pidFornecedor` INT, IN `pcodFornecedor` VARCHAR(3), IN `pnome` VARCHAR(45), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pnumero` VARCHAR(45), IN `pbairro` VARCHAR(45), IN `pcidade` VARCHAR(45), IN `pcomplemento` VARCHAR(100), IN `puf` VARCHAR(45), IN `pcep` CHAR(2), IN `pstatus` TINYINT(4))  BEGIN
  
    DECLARE vidFornecedor INT;
    
    SELECT idFornecedor INTO vidFornecedor
    FROM fornecedores
    WHERE idFornecedor = pidFornecedor;

    UPDATE fornecedores
    SET
        codFornecedor = pcodFornecedor,
        nome = pnome,
        telefone1 = ptelefone1,
        telefone2 = ptelefone2,
        email1 = pemail1,
        email2 = pemail2,
        endereco = pendereco,
        numero = pnumero,
        bairro = pbairro,
        cidade = pcidade,
        complemento = pcomplemento,
        uf = puf,
        cep = pcep,
        status = pstatus
    
    WHERE idFornecedor = vidFornecedor;
    
    SELECT * FROM fornecedores WHERE idFornecedor = pidFornecedor;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fornecedores_delete` (IN `pidFornecedor` INT)  BEGIN
  
    DECLARE vidFornecedor INT;
    
  SELECT idFornecedor INTO vidFornecedor
    FROM fornecedores
    WHERE idFornecedor = pidFornecedor;
    
    DELETE FROM fornecedores WHERE idFornecedor = pidFornecedor;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fornecedores_save` (IN `pcodFornecedor` VARCHAR(3), IN `pnome` VARCHAR(45), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pnumero` VARCHAR(45), IN `pbairro` VARCHAR(45), IN `pcidade` VARCHAR(45), IN `pcomplemento` VARCHAR(100), IN `puf` VARCHAR(45), IN `pcep` CHAR(2), IN `pstatus` TINYINT(4))  BEGIN
  
    DECLARE vidFornecedor INT;
    
  INSERT INTO fornecedores (codFornecedor, nome, telefone1, telefone2, email1, email2, endereco, numero, bairro, cidade, complemento, uf, cep, status)
    VALUES(pcodFornecedor, pnome, ptelefone1, ptelefone2, pemail1, pemail2, pendereco, pnumero, pbairro, pcidade, pcomplemento, puf, pcep, pstatus);
    
    SET vidFornecedor = LAST_INSERT_ID();
    
    SELECT * FROM fornecedores WHERE idFornecedor = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_historicoalugueisUpdate_save` (IN `pidHistoricoAluguel` INT(11), IN `pcontrato_idContrato` INT(11), IN `pproduto_idProduto` INT(11), IN `pstatus` TINYINT(4), IN `pvlAluguel` FLOAT, IN `pdtInicio` DATE, IN `pdtFinal` DATE, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pobservacao` TEXT)  BEGIN
  
    DECLARE vidHistoricoAluguel INT;
    
    SELECT idHistoricoAluguel INTO vidHistoricoAluguel
    FROM historicoalugueis
    WHERE idHistoricoAluguel = pidHistoricoAluguel;

    UPDATE historicoalugueis
    SET
      idHistoricoAluguel = pidHistoricoAluguel,
      contrato_idContrato = pcontrato_idContrato,
      produto_idProduto = pproduto_idProduto,
      status = pstatus,
      vlAluguel = pvlAluguel,
      dtInicio = pdtInicio,
      dtFinal = pdtFinal,
      custoEntrega = pcustoEntrega,
      custoRetirada = pcustoRetirada,
      observacao = pobservacao

    
    WHERE idHistoricoAluguel = vidHistoricoAluguel;
    
    SELECT * FROM historicoalugueis WHERE idHistoricoAluguel = pidHistoricoAluguel;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_historicoalugueis_delete` (IN `pidHistoricoAluguel` INT)  BEGIN
  
    DECLARE vidHistoricoAluguel INT;
    
  SELECT idHistoricoAluguel INTO vidHistoricoAluguel
    FROM historicoalugueis
    WHERE idHistoricoAluguel = pidHistoricoAluguel;
    
    DELETE FROM historicoalugueis WHERE idHistoricoAluguel = pidHistoricoAluguel;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_historicoalugueis_save` (IN `pcontrato_idContrato` INT(11), IN `pproduto_idProduto` INT(11), IN `pstatus` TINYINT(4), IN `pvlAluguel` FLOAT, IN `pdtInicio` DATE, IN `pdtFinal` DATE, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pobservacao` TEXT)  BEGIN
  
    DECLARE vidHistoricoAluguel INT;
    
  INSERT INTO historicoalugueis (contrato_idContrato, produto_idProduto, status, vlAluguel, dtInicio, dtFinal, custoEntrega, custoRetirada, observacao)
    VALUES(pcontrato_idContrato, pproduto_idProduto, pstatus, pvlAluguel, pdtInicio, pdtFinal, pcustoEntrega, pcustoRetirada, pobservacao);
    
    SET vidHistoricoAluguel = LAST_INSERT_ID();
    
    SELECT * FROM historicoalugueis WHERE idHistoricoAluguel = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_espUpdate_save` (IN `pidProduto_esp` INT, IN `pidProduto_gen` INT, IN `pcodigoEsp` VARCHAR(70), IN `pvalorCompra` FLOAT, IN `pstatus` TINYINT(4), IN `pdtFabricacao` DATE, IN `pnumSerie` VARCHAR(4), IN `panotacoes` VARCHAR(100), IN `pidFornecedor` INT(11))  BEGIN
  
    DECLARE vidProduto_esp INT;
    
    SELECT idProduto_esp INTO vidProduto_esp
    FROM produtos_esp
    WHERE idProduto_esp = pidProduto_esp;

    UPDATE produtos_esp
    SET
   idProduto_gen = pidProduto_gen,
   codigoEsp = pcodigoEsp,
   valorCompra = pvalorCompra,
   status = pstatus,
   dtFabricacao = pdtFabricacao,
   numSerie = pnumSerie,
   anotacoes = panotacoes,
   idFornecedor = pidFornecedor
    
    WHERE idProduto_esp = vidProduto_esp;
    
    SELECT * FROM produtos_esp WHERE idProduto_esp = pidProduto_esp;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_esp_delete` (IN `pidProduto_esp` INT)  BEGIN
  
    DECLARE vidProduto_esp INT;
    
  SELECT idProduto_esp INTO vidProduto_esp
    FROM produtos_esp
    WHERE idProduto_esp = pidProduto_esp;
    
    DELETE FROM produtos_esp WHERE idProduto_esp = pidProduto_esp;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_esp_save` (IN `pidProduto_gen` INT, IN `pcodigoEsp` VARCHAR(70), IN `pvalorCompra` FLOAT, IN `pstatus` TINYINT(4), IN `pdtFabricacao` DATE, IN `pnumSerie` VARCHAR(4), IN `panotacoes` VARCHAR(100), IN `pidFornecedor` INT(11))  BEGIN
  
    DECLARE vidProduto_esp INT;
    
  INSERT INTO produtos_esp (idProduto_gen, codigoEsp, valorCompra, status, dtFabricacao, numSerie, anotacoes, idFornecedor)
    VALUES(pidProduto_gen, pcodigoEsp, pvalorCompra, pstatus, pdtFabricacao, pnumSerie, panotacoes, pidFornecedor);
    
    SET vidProduto_esp = LAST_INSERT_ID();
    
    SELECT * FROM produtos_esp WHERE idProduto_esp = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_genUpdate_save` (IN `pidProduto_gen` INT, IN `pcodigoGen` VARCHAR(70), IN `pdescricao` VARCHAR(150), IN `pvlBaseAluguel` FLOAT, IN `ptipo1` VARCHAR(3), IN `ptipo2` VARCHAR(3), IN `ptipo3` VARCHAR(3), IN `ptipo4` VARCHAR(3), IN `pidCategoria` INT(4))  BEGIN
  
    DECLARE vidProduto_gen INT;
    
    SELECT idProduto_gen INTO vidProduto_gen
    FROM produtos_gen
    WHERE idProduto_gen = pidProduto_gen;

    UPDATE produtos_gen
    SET
   codigoGen = pcodigoGen,
   descricao = pdescricao,
   vlBaseAluguel = pvlBaseAluguel,
   tipo1 = ptipo1,
   tipo2 = ptipo2,
   tipo3 = ptipo3,
   tipo4 = ptipo4,
   idCategoria = pidCategoria
    
    WHERE idProduto_gen = vidProduto_gen;
    
    SELECT * FROM produtos_gen WHERE idProduto_gen = pidProduto_gen;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_gen_delete` (IN `pidProduto_gen` INT)  BEGIN
  
    DECLARE vidProduto_gen INT;
    
  SELECT idProduto_gen INTO vidProduto_gen
    FROM produtos_gen
    WHERE idProduto_gen = pidProduto_gen;
    
    DELETE FROM produtos_gen WHERE idProduto_gen = pidProduto_gen;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_produtos_gen_save` (IN `pcodigoGen` VARCHAR(70), IN `pdescricao` VARCHAR(150), IN `pvlBaseAluguel` FLOAT, IN `ptipo1` VARCHAR(3), IN `ptipo2` VARCHAR(3), IN `ptipo3` VARCHAR(3), IN `ptipo4` VARCHAR(3), IN `pidCategoria` INT(4))  BEGIN
  
    DECLARE vidProduto_gen INT;
    
  INSERT INTO produtos_gen (codigoGen, descricao, vlBaseAluguel, tipo1, tipo2, tipo3, tipo4, idCategoria)
    VALUES(pcodigoGen, pdescricao, pvlBaseAluguel, ptipo1, ptipo2, ptipo3, ptipo4, pidCategoria);
    
    SET vidProduto_gen = LAST_INSERT_ID();
    
    SELECT * FROM produtos_gen WHERE idProduto_gen = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_prod_containersUpdate_save` (IN `pidProduto` INT(11), IN `ptipoPorta` VARCHAR(45), IN `pjanelasLat` INT(11), IN `pjanelasCirc` INT(11), IN `pforrado` TINYINT(4), IN `peletrificado` TINYINT(4), IN `ptomadas` INT(11), IN `plampadas` INT(11), IN `pentradasAC` INT(11), IN `psanitarios` INT(11), IN `pchuveiro` TINYINT(4))  BEGIN
  
    DECLARE vidProduto INT;
    
    SELECT idProduto INTO vidProduto
    FROM prod_containers
    WHERE idProduto = pidProduto;

    UPDATE prod_containers
    SET
    tipoPorta = ptipoPorta,
    janelasLat = pjanelasLat,
    janelasCirc = pjanelasCirc,
    forrado = pforrado,
    eletrificado = peletrificado,
    tomadas = ptomadas,
    lampadas = plampadas,
    entradasAC = pentradasAC,
    sanitarios = psanitarios,
    chuveiro = pchuveiro

    
    WHERE idProduto = vidProduto;
    
    SELECT * FROM prod_containers WHERE idProduto = pidProduto;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_prod_containers_delete` (IN `pidProduto` INT)  BEGIN
  
    DECLARE vidProduto INT;
    
  SELECT idProduto INTO vidProduto
    FROM prod_containers
    WHERE idProduto = pidProduto;
    
    DELETE FROM prod_containers WHERE idProduto = pidProduto;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_prod_containers_save` (IN `pidProduto` INT(11), IN `ptipoPorta` VARCHAR(45), IN `pjanelasLat` INT(11), IN `pjanelasCirc` INT(11), IN `pforrado` TINYINT(4), IN `peletrificado` TINYINT(4), IN `ptomadas` INT(11), IN `plampadas` INT(11), IN `pentradasAC` INT(11), IN `psanitarios` INT(11), IN `pchuveiro` TINYINT(4))  BEGIN
  
    DECLARE vidContainer INT;
    
    INSERT INTO prod_containers (idProduto, tipoPorta, janelasLat, janelasCirc, forrado, eletrificado, tomadas, lampadas, entradasAC, sanitarios, chuveiro)
    VALUES(pidProduto, ptipoPorta, pjanelasLat, pjanelasCirc, pforrado, peletrificado, ptomadas, plampadas, pentradasAC, psanitarios, pchuveiro);

    SET vidContainer = LAST_INSERT_ID();
    SELECT * FROM prod_containers WHERE idContainer = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_prod_tiposUpdate_save` (IN `pid` INT, IN `pdescTipo` VARCHAR(100), IN `pidCategoria` INT(4), IN `pordem_tipo` INT(1), IN `pcodTipo` VARCHAR(2))  BEGIN
  
    DECLARE vid INT;
    
    SELECT id INTO vid
    FROM prod_tipos
    WHERE id = pid;

    UPDATE prod_tipos
    SET
        descTipo = pdescTipo, 
        idCategoria = pidCategoria,
        ordem_tipo = pordem_tipo,
        codTipo = pcodTipo
    WHERE id = vid;
    
    SELECT * FROM prod_tipos WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_prod_tipos_delete` (IN `pid` INT)  BEGIN
  
    DECLARE vid INT;
    
  SELECT id INTO vid
    FROM prod_tipos
    WHERE id = pid;
    
    DELETE FROM prod_tipos WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_prod_tipos_save` (IN `pdescTipo` VARCHAR(100), IN `pidCategoria` INT(4), IN `pordem_tipo` INT(1), IN `pcodTipo` VARCHAR(2))  BEGIN
  
    DECLARE vid INT;
    
  INSERT INTO prod_tipos (descTipo, idCategoria, ordem_tipo, codTipo)
    VALUES(pdescTipo, pidCategoria, pordem_tipo, pcodTipo);
    
    SET vid = LAST_INSERT_ID();
    
    SELECT * FROM prod_tipos WHERE id = LAST_INSERT_ID();
    
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

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`idCliente`, `nome`, `status`, `telefone1`, `telefone2`, `email1`, `email2`, `endereco`, `tipoEndereco`, `complemento`, `cidade`, `bairro`, `numero`, `uf`, `cep`, `cpf`, `rg`, `cnpj`, `ie`, `tipoCliente`, `dtCadastro`) VALUES
(1, 'CLIENTE TESTE 1', 1, '19 99999999', NULL, 'teste@teste.com', NULL, 'TESTE', 'barracao', NULL, 'Limeira', 'Centro', 123, 'SP', '0396356', NULL, NULL, '3243423423423', NULL, '?????', '2020-03-31 22:13:56');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contratos`
--

CREATE TABLE `contratos` (
  `idContrato` int(11) NOT NULL,
  `codContrato` varchar(11) NOT NULL,
  `obra_idObra` int(11) NOT NULL,
  `dtEmissao` date NOT NULL,
  `dtAprovacao` date DEFAULT NULL,
  `custoEntrega` float NOT NULL,
  `custoRetirada` float NOT NULL,
  `notas` varchar(100) DEFAULT NULL,
  `valorAluguel` float NOT NULL,
  `dtInicio` date DEFAULT NULL,
  `dtFim` date DEFAULT NULL,
  `statusOrcamento` tinyint(4) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `contratos`
--

INSERT INTO `contratos` (`idContrato`, `codContrato`, `obra_idObra`, `dtEmissao`, `dtAprovacao`, `custoEntrega`, `custoRetirada`, `notas`, `valorAluguel`, `dtInicio`, `dtFim`, `statusOrcamento`, `dtCadastro`) VALUES
(1, '1', 1, '2020-04-03', '0000-00-00', 500, 300, '', 3500, '2019-03-05', '2020-03-04', 2, '2020-03-31 22:27:25'),
(3, '2', 1, '2020-03-31', '2020-03-31', 200, 100, '', 4500, '2020-04-03', '2020-05-03', 4, '2020-03-31 23:04:24'),
(5, '3', 1, '2020-03-05', '2020-04-04', 456, 3453, '', 1556, '2020-04-04', '2021-04-03', 5, '2020-04-03 23:00:12'),
(6, '4', 1, '2020-02-01', '2020-04-03', 502, 362, '', 15200.8, '2020-04-03', '2020-04-04', 5, '2020-04-03 23:11:40'),
(7, '5', 1, '2020-03-28', '0000-00-00', 566.2, 350.99, '', 1500.99, '2020-02-12', '2020-02-11', 4, '2020-04-03 23:15:05'),
(8, '6', 1, '2020-04-03', NULL, 526, 362.66, 'Cliente negativado no SERASA', 3500.45, NULL, NULL, 2, '2020-04-03 23:23:38'),
(9, '7', 1, '2020-04-04', '2020-04-04', 500, 200, 'teste', 13000, NULL, NULL, 1, '2020-04-04 21:22:26'),
(10, '8', 1, '2020-04-03', '2020-04-04', 500, 200, '', 2000, NULL, NULL, 1, '2020-04-04 21:22:54'),
(11, '9', 1, '2020-04-03', '0000-00-00', 200, 50, '', 5000, '2020-04-04', '2021-04-03', 3, '2020-04-04 21:24:52'),
(34, '10', 1, '2200-04-11', NULL, 234, 23423, '', 2342, NULL, NULL, 0, '2020-04-14 16:20:06'),
(35, '10', 1, '2020-04-17', NULL, 500, 450, '', 5000, NULL, NULL, 0, '2020-04-17 10:23:51'),
(36, '10', 1, '2020-04-17', NULL, 500, 450, '', 5000, NULL, NULL, 0, '2020-04-17 10:24:01'),
(37, '10', 1, '2020-07-14', NULL, 200, 500, '', 600, NULL, NULL, 0, '2020-04-17 10:29:25'),
(38, '10', 1, '0000-00-00', NULL, 2342, 234, '', 234, NULL, NULL, 0, '2020-04-17 10:35:20'),
(39, '10', 1, '2020-04-20', NULL, 345, 3453, '', 4353, NULL, NULL, 0, '2020-04-17 10:37:55'),
(40, '10', 1, '2020-07-14', NULL, 234, 234, '', 234, NULL, NULL, 0, '2020-04-17 10:39:23'),
(41, '10', 1, '2020-04-17', NULL, 535, 234324, '', 234, NULL, NULL, 0, '2020-04-17 10:40:34'),
(42, '10', 1, '2020-04-17', NULL, 500, 200, '', 3000, NULL, NULL, 0, '2020-04-17 11:10:12'),
(43, '10', 1, '2020-04-17', NULL, 500, 450, '', 2000, NULL, NULL, 0, '2020-04-17 18:46:32'),
(44, '10', 1, '2020-04-23', '2020-04-17', 200, 200, '', 565, NULL, NULL, 1, '2020-04-17 18:48:04'),
(45, '10', 1, '2020-04-17', NULL, 500, 400, '', 2000, NULL, NULL, 0, '2020-04-17 18:51:01'),
(46, '10', 1, '2020-07-14', NULL, 500, 200, '', 500, NULL, NULL, 0, '2020-04-17 19:00:49'),
(47, '10', 1, '2020-07-14', NULL, 500, 200, '', 500, NULL, NULL, 0, '2020-04-17 19:11:38'),
(48, '10', 1, '2020-07-14', NULL, 800, 100, '', 400, NULL, NULL, 0, '2020-04-19 23:20:23'),
(49, '10', 1, '2020-04-22', NULL, 520, 436.88, 'cadastro teste', 2500.99, NULL, NULL, 0, '2020-04-22 15:26:36'),
(50, '10', 1, '2020-04-22', NULL, 520, 450, 'cadastro teste', 5200, NULL, NULL, 0, '2020-04-22 15:46:05'),
(51, '10', 1, '2020-04-22', NULL, 445, 400, 'cadastro ', 5000, NULL, NULL, 0, '2020-04-22 15:47:39'),
(52, '10', 1, '2020-12-22', NULL, 234, 234, '', 25, NULL, NULL, 3, '2020-04-22 15:50:56'),
(53, '10', 1, '2020-12-22', NULL, -1, 234, '234', 234, NULL, NULL, 3, '2020-04-22 15:52:47'),
(54, '10', 1, '2020-11-22', NULL, 32423, 2342, '', 2342, NULL, NULL, 0, '2020-04-22 15:53:37'),
(55, '10', 1, '2020-12-31', NULL, 43, 234, '', 234, NULL, NULL, 3, '2020-04-22 16:00:55'),
(56, '10', 1, '2020-12-31', NULL, 345, 34234, '23432', 2342, NULL, NULL, 0, '2020-04-22 16:02:23'),
(57, '10', 1, '2020-12-22', NULL, 800, 400, '', 5400, NULL, NULL, 0, '2020-04-22 16:18:19'),
(58, '10', 1, '2020-04-22', NULL, 500, 400, '', 200, NULL, NULL, 0, '2020-04-22 16:21:06'),
(59, '10', 1, '2020-12-31', NULL, 2453, 345, '', 345, NULL, NULL, 0, '2020-04-22 16:34:33'),
(60, '10', 1, '2322-02-22', NULL, 2342, 234, '', 234, NULL, NULL, 0, '2020-04-22 16:38:40'),
(61, '10', 1, '2020-12-31', NULL, 345, 345, '', 345, NULL, NULL, 0, '2020-04-22 16:43:36'),
(62, '10', 1, '2020-04-22', NULL, 5, 500, '', 50, NULL, NULL, 0, '2020-04-22 16:45:51'),
(63, '10', 1, '2020-12-31', NULL, 453, 345, '345', 345, NULL, NULL, 0, '2020-04-22 16:48:26'),
(64, '10', 1, '0234-04-23', NULL, 345, 3453, '', 34535, NULL, NULL, 0, '2020-04-22 16:48:52'),
(65, '10', 1, '2020-12-31', NULL, 345, 345, '45', 345, NULL, NULL, 0, '2020-04-22 16:53:50'),
(66, '10', 1, '2020-12-23', NULL, -1, -1, '23423', -1, NULL, NULL, 3, '2020-04-22 16:54:05'),
(67, '10', 1, '3232-02-23', NULL, 200, 234, '', 234, NULL, NULL, 0, '2020-04-22 17:07:41'),
(68, '10', 1, '2020-04-22', NULL, 520, 400, '', 15000, NULL, NULL, 0, '2020-04-22 23:27:22'),
(69, '10', 1, '2020-04-22', NULL, 520, 460, '120', 500, NULL, NULL, 0, '2020-04-22 23:30:45'),
(70, '10', 1, '2020-04-22', NULL, 520, 460, '120', 500, NULL, NULL, 0, '2020-04-22 23:30:56'),
(71, '10', 1, '2020-04-25', NULL, 500, 400, '', 3000, NULL, NULL, 0, '2020-04-25 07:52:07'),
(72, '10', 1, '2020-04-25', NULL, 500, 350, '', 23434, NULL, NULL, 0, '2020-04-25 08:05:34'),
(73, '10', 1, '2020-04-25', NULL, 500, 400, '', 2343, NULL, NULL, 0, '2020-04-25 08:07:21'),
(74, '10', 1, '2020-12-31', NULL, 23423, 23422, '', 34, NULL, NULL, 0, '2020-04-25 08:09:06'),
(75, '10', 1, '2020-12-31', NULL, 234, 2342, '', 23423, NULL, NULL, 0, '2020-04-25 08:10:39'),
(76, '10', 1, '2020-11-22', NULL, -1, -1, '', -1, NULL, NULL, 3, '2020-04-25 08:12:02'),
(77, '10', 1, '2020-12-31', NULL, 3453, 345, '', 3453, NULL, NULL, 0, '2020-04-25 08:20:21'),
(78, '10', 1, '2020-12-31', NULL, 500, 500, '', 500, NULL, NULL, 0, '2020-04-25 08:43:13');

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
  `codFornecedor` varchar(3) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `telefone1` varchar(15) NOT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `email1` varchar(45) NOT NULL,
  `email2` varchar(45) DEFAULT NULL,
  `endereco` varchar(45) NOT NULL,
  `numero` int(11) NOT NULL,
  `bairro` varchar(45) NOT NULL,
  `cidade` varchar(45) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `uf` char(2) NOT NULL,
  `cep` varchar(45) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `fornecedores`
--

INSERT INTO `fornecedores` (`idFornecedor`, `codFornecedor`, `nome`, `telefone1`, `telefone2`, `email1`, `email2`, `endereco`, `numero`, `bairro`, `cidade`, `complemento`, `uf`, `cep`, `status`, `dtCadastro`) VALUES
(1, '001', 'Fornecedor X', '19 99999999', '', '', '', 'Rua x', 344, 'jd. do filtro', 'Araras', '', 'SP', '09', 1, '2020-03-03 22:09:00'),
(2, '002', 'Cargueiro Fornecedor de Containers', '11 99992344', '1255550000', 'cargueiro@grupo.com', 'adm@grupo.com', 'Av. do Louvre', 1009, 'Vila Litoral', 'Santos', '', 'SP', '10', 1, NULL),
(4, '003', 'Douglas Numeriano', '19 99999999', '19 99999999', 'douglas@gmail.com', '', 'Rua Maria Aparecida', 76, 'jd. das Flores', 'Limeira', 'prédio', 'SP', '13', 1, '2020-03-30 15:29:33'),
(5, '004', 'Fornecedor YXZ', '19 75363234', '19453656453', 'adm@bergamus.com', 'fincancas@bergamus.com', 'Avenida Brasil', 340, 'Centro', 'São Paulo', 'Barracão', 'SP', '10', 1, '2020-03-30 17:38:44');

-- --------------------------------------------------------

--
-- Estrutura da tabela `historicoalugueis`
--

CREATE TABLE `historicoalugueis` (
  `idHistoricoAluguel` int(11) NOT NULL,
  `contrato_idContrato` int(11) NOT NULL,
  `produto_idProduto` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `vlAluguel` float NOT NULL,
  `dtInicio` date DEFAULT NULL,
  `dtFinal` date DEFAULT NULL,
  `custoEntrega` float DEFAULT NULL,
  `custoRetirada` float DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `historicoalugueis`
--

INSERT INTO `historicoalugueis` (`idHistoricoAluguel`, `contrato_idContrato`, `produto_idProduto`, `status`, `vlAluguel`, `dtInicio`, `dtFinal`, `custoEntrega`, `custoRetirada`, `observacao`, `dtCadastro`) VALUES
(10, 56, 180, 1, 345, '2020-12-31', '0000-00-00', 3453, 32452, '234', '2020-04-22 16:04:40'),
(11, 56, 180, 1, 345, '2020-12-31', '0000-00-00', 3453, 32452, '234', '2020-04-22 16:06:41'),
(12, 58, 180, 1, 5000, '0000-00-00', '0000-00-00', 230.88, 150, '', '2020-04-22 16:24:24'),
(13, 64, 180, 1, 500, '0000-00-00', '0000-00-00', 0, 0, '', '2020-04-22 16:52:01'),
(14, 66, 180, 1, 2345, '0000-00-00', '0000-00-00', 0, 0, '', '2020-04-22 16:54:31'),
(15, 76, 187, 1, 15470, '2020-04-25', '2021-04-24', 234, 234, '', '2020-04-25 08:14:12'),
(16, 77, 187, 1, 15470, '0000-00-00', '0000-00-00', 0, 0, '', '2020-04-25 08:20:35');

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

--
-- Extraindo dados da tabela `obras`
--

INSERT INTO `obras` (`idObra`, `codObra`, `respObra`, `telefone1`, `telefone2`, `email1`, `email2`, `complemento`, `tipoEndereco`, `cidade`, `bairro`, `numero`, `uf`, `cep`, `endereco`, `cliente_idCliente`, `dtCadastro`) VALUES
(1, 1, 'TESTE', '342445', NULL, 'teste@teste', NULL, NULL, '??', 'Limeira', 'Vila Dona Rosa', 50, 'SP', '324252532', 'RUA X', 1, '2020-03-31 22:15:21');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_esp`
--

CREATE TABLE `produtos_esp` (
  `idProduto_esp` int(11) NOT NULL,
  `idProduto_gen` int(11) NOT NULL,
  `codigoEsp` varchar(24) NOT NULL,
  `valorCompra` float DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `dtFabricacao` date DEFAULT NULL,
  `numSerie` varchar(4) DEFAULT NULL,
  `anotacoes` varchar(100) DEFAULT NULL,
  `idFornecedor` int(11) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produtos_esp`
--

INSERT INTO `produtos_esp` (`idProduto_esp`, `idProduto_gen`, `codigoEsp`, `valorCompra`, `status`, `dtFabricacao`, `numSerie`, `anotacoes`, `idFornecedor`, `dtCadastro`) VALUES
(17, 180, '001.01.01.01.01.002-0001', 12500, 1, '2020-04-22', '0001', '', 2, '2020-04-22 09:49:52'),
(18, 180, '001.01.01.01.01.002-0002', 10000, 1, '2020-04-22', '0002', 'cadastro teste', 2, '2020-04-22 09:51:18'),
(19, 180, '001.01.01.01.01.002-0003', 13899.8, 1, '2020-04-22', '0003', 'teste cadastro', 2, '2020-04-22 09:57:53'),
(20, 181, '002.01.01.01.01.001-0001', 520.98, 1, '2020-04-22', '0001', 'cadastro teste', 1, '2020-04-22 10:45:04'),
(21, 181, '002.01.01.01.01.004-0002', 450.77, 1, '2020-04-22', '0002', 'cadastro teste', 5, '2020-04-22 10:47:10'),
(22, 181, '002.01.01.01.01.002-0003', 459.89, 1, '2020-04-22', '0003', 'cadastro teste', 2, '2020-04-22 10:49:38'),
(23, 181, '002.01.01.01.01.002-0004', 345.76, 1, '2020-04-22', '0004', 'CADASTRO TESTE', 2, '2020-04-22 10:50:40'),
(28, 180, '001.01.01.01.01.002-0004', 15477.2, 1, '2011-04-12', '0004', '', 2, '2020-04-22 11:47:06'),
(29, 190, '004.03.xx.xx.xx.002-xxxx', 500, 1, '2010-03-15', NULL, 'cadastro teste', 2, '2020-04-22 15:15:35');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_gen`
--

CREATE TABLE `produtos_gen` (
  `idProduto_gen` int(11) NOT NULL,
  `codigoGen` varchar(24) NOT NULL,
  `descricao` varchar(150) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  `tipo1` int(11) NOT NULL,
  `tipo2` int(11) DEFAULT NULL,
  `tipo3` int(11) DEFAULT NULL,
  `tipo4` int(11) DEFAULT NULL,
  `vlBaseAluguel` float NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos_gen`
--

INSERT INTO `produtos_gen` (`idProduto_gen`, `codigoGen`, `descricao`, `idCategoria`, `tipo1`, `tipo2`, `tipo3`, `tipo4`, `vlBaseAluguel`, `dtCadastro`) VALUES
(180, '001.01.01.01.01', '3M almoxarifado com lavabo DC', 1, 1, 5, 12, 14, 450, '2020-04-20 19:25:32'),
(181, '002.01.01.01.01', 'MARCA X MODELO X Elétrica 110V', 2, 16, 17, 18, 21, 234, '2020-04-20 19:54:06'),
(187, '001.04.05.02.02', '12M stand de vendas sem lavabo HC', 1, 4, 9, 13, 15, 15470, '2020-04-22 14:22:48'),
(189, '004.05.xx.xx.xx', '3,00m a 5,10m', 4, 52, NULL, NULL, NULL, 250, '2020-04-22 15:07:22'),
(190, '004.03.xx.xx.xx', '2,30m a 4,00m', 4, 50, NULL, NULL, NULL, 125.66, '2020-04-22 15:14:37'),
(191, '003.01.01.01.xx', 'Tubular Painel 1,00m', 3, 30, 33, 45, NULL, 200, '2020-04-22 15:17:37');

-- --------------------------------------------------------

--
-- Estrutura da tabela `prod_categorias`
--

CREATE TABLE `prod_categorias` (
  `idCategoria` int(4) NOT NULL,
  `descCategoria` varchar(15) NOT NULL,
  `codCategoria` varchar(3) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `prod_categorias`
--

INSERT INTO `prod_categorias` (`idCategoria`, `descCategoria`, `codCategoria`, `dtCadastro`) VALUES
(1, 'Container', '001', '2020-03-03 23:01:57'),
(2, 'Betoneira', '002', '2020-03-05 00:17:03'),
(3, 'Andaime', '003', '2020-03-05 00:17:03'),
(4, 'Escora metálica', '004', '2020-03-09 11:36:59');

-- --------------------------------------------------------

--
-- Estrutura da tabela `prod_containers`
--

CREATE TABLE `prod_containers` (
  `idContainer` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `tipoPorta` varchar(45) DEFAULT NULL,
  `janelasLat` int(11) DEFAULT NULL,
  `janelasCirc` int(11) DEFAULT NULL,
  `forrado` tinyint(4) DEFAULT NULL,
  `eletrificado` tinyint(4) NOT NULL,
  `tomadas` int(11) DEFAULT NULL,
  `lampadas` int(11) DEFAULT NULL,
  `entradasAC` int(11) DEFAULT NULL,
  `sanitarios` int(11) DEFAULT NULL,
  `chuveiro` tinyint(4) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `prod_containers`
--

INSERT INTO `prod_containers` (`idContainer`, `idProduto`, `tipoPorta`, `janelasLat`, `janelasCirc`, `forrado`, `eletrificado`, `tomadas`, `lampadas`, `entradasAC`, `sanitarios`, `chuveiro`, `dtCadastro`) VALUES
(38, 17, 'Marítima', 1, 0, 1, 1, 1, 1, 1, 0, 0, '2020-04-22 09:49:52'),
(39, 18, 'Marítma', 2, 0, 1, 1, 2, 2, 1, 0, 0, '2020-04-22 09:51:18'),
(40, 19, 'Marítma', 1, 0, 1, 0, 1, 1, 1, 0, 0, '2020-04-22 09:57:53'),
(41, 28, 'Marítima', 0, 0, 1, 1, 1, 2, 1, 1, 0, '2020-04-22 11:47:07');

-- --------------------------------------------------------

--
-- Estrutura da tabela `prod_tipos`
--

CREATE TABLE `prod_tipos` (
  `id` int(11) NOT NULL,
  `descTipo` varchar(100) NOT NULL,
  `idCategoria` int(4) NOT NULL,
  `ordem_tipo` int(1) NOT NULL,
  `codTipo` varchar(2) NOT NULL,
  `dtCadastro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `prod_tipos`
--

INSERT INTO `prod_tipos` (`id`, `descTipo`, `idCategoria`, `ordem_tipo`, `codTipo`, `dtCadastro`) VALUES
(1, '3M', 1, 1, '01', '2020-03-06 00:01:32'),
(2, '4M', 1, 1, '02', '2020-03-09 10:17:49'),
(3, '6M', 1, 1, '03', '2020-03-09 10:18:12'),
(4, '12M', 1, 1, '04', '2020-03-09 10:18:39'),
(5, 'almoxarifado', 1, 2, '01', '2020-03-09 10:22:17'),
(6, 'escritório', 1, 2, '02', '2020-03-09 10:22:41'),
(7, 'sanitário', 1, 2, '03', '2020-03-09 10:22:55'),
(8, 'guarita', 1, 2, '04', '2020-03-09 10:24:33'),
(9, 'stand de vendas', 1, 2, '05', '2020-03-09 10:24:57'),
(10, 'lanchonete', 1, 2, '06', '2020-03-09 10:25:20'),
(11, 'especial', 1, 2, '07', '2020-03-09 10:25:32'),
(12, 'com lavabo', 1, 3, '01', '2020-03-09 10:26:42'),
(13, 'sem lavabo', 1, 3, '02', '2020-03-09 10:26:55'),
(14, 'DC', 1, 4, '01', '2020-03-09 10:27:17'),
(15, 'HC', 1, 4, '02', '2020-03-09 10:27:28'),
(16, 'MARCA X', 2, 1, '01', '2020-03-14 10:17:07'),
(17, 'MODELO X', 2, 2, '01', '2020-03-14 10:17:44'),
(18, 'Elétrica', 2, 3, '01', '2020-03-14 10:34:38'),
(19, 'Combustão', 2, 3, '02', '2020-03-14 15:07:35'),
(21, '110V', 2, 4, '01', '2020-03-16 14:23:30'),
(22, '220V', 2, 4, '02', '2020-03-16 14:25:23'),
(23, '380V', 2, 4, '03', '2020-03-16 14:25:38'),
(24, '110V/220V', 2, 4, '04', '2020-03-16 14:27:03'),
(30, 'Tubular', 3, 1, '01', '2020-03-16 14:46:56'),
(31, 'Fachadeiro', 3, 1, '02', '2020-03-16 14:47:38'),
(32, 'Multidirecional', 3, 1, '03', '2020-03-16 14:48:18'),
(33, 'Painel', 3, 2, '01', '2020-03-16 15:48:23'),
(34, 'Trava diagonal', 3, 2, '02', '2020-03-16 15:49:13'),
(35, 'Plataforma', 3, 2, '03', '2020-03-16 15:49:29'),
(36, 'Guarda corpo com porta', 3, 2, '04', '2020-03-16 15:49:49'),
(37, 'Guarda corpo sem porta', 3, 2, '05', '2020-03-16 15:50:09'),
(38, 'Rodapé', 3, 2, '06', '2020-03-16 15:50:45'),
(39, 'Barra de ligação', 3, 2, '07', '2020-03-16 15:51:23'),
(40, 'Sapata regulável', 3, 2, '08', '2020-03-16 15:51:34'),
(41, 'Sapata fixa', 3, 2, '09', '2020-03-16 15:51:44'),
(42, 'Rodízio com trava', 3, 2, '10', '2020-03-16 15:52:06'),
(43, 'Escada sem guarda corpo', 3, 2, '11', '2020-03-16 15:53:11'),
(44, 'Escada com guarda copo', 3, 2, '12', '2020-03-16 16:00:55'),
(45, '1,00m', 3, 3, '01', '2020-03-16 16:01:16'),
(46, '1,50m', 3, 3, '02', '2020-03-16 16:01:28'),
(47, '2,00m', 3, 3, '03', '2020-03-16 16:01:39'),
(48, '2,20m a 3,30m', 4, 1, '01', '2020-03-16 22:05:49'),
(49, '2,20m a 3,80m', 4, 1, '02', '2020-03-16 22:06:06'),
(50, '2,30m a 4,00m', 4, 1, '03', '2020-03-16 22:06:19'),
(51, '3,00m a 4,50m', 4, 1, '04', '2020-03-16 22:06:32'),
(52, '3,00m a 5,10m', 4, 1, '05', '2020-03-16 22:06:45');

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
(173, 'Administrador', 'Teste', 'admin', '$2y$12$VN9ODzeRl2lKLhE84XmWF.lf5UbP9gfWFtEa7f1jEuyaeV9ILIhz6', 'eldersamuel98@gmail.com', 1, '/res/img/users/user-default.jpg', '2020-02-29 22:45:00'),
(180, 'Matheus Leite de Campos', 'Product Owner', 'matheus', '$2y$12$HgGxPtV/zZhse52m9Dc6HuE8bUiXeFWCW66AtdiUW2OB537qmhmrO', 'matheus@gmail.com', 1, '/res/img/users/user-default.jpg', '2020-03-05 17:22:07'),
(185, 'teste', 'teste', 'teste', '$2y$12$7sxu7KZZ5tNXyWgBlekeSudyonnOaUbkvcd8jRj.p2P1NPqRqqBx.', 'teste@teste.com', 0, '/res/img/users/user-default.jpg', '2020-03-30 09:50:43');

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
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCliente`);

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
-- Índices para tabela `obras`
--
ALTER TABLE `obras`
  ADD PRIMARY KEY (`idObra`),
  ADD KEY `fk_obra_cliente1` (`cliente_idCliente`);

--
-- Índices para tabela `produtos_esp`
--
ALTER TABLE `produtos_esp`
  ADD PRIMARY KEY (`idProduto_esp`),
  ADD KEY `fk_fornecedor` (`idFornecedor`),
  ADD KEY `fk_produtos_esp` (`idProduto_gen`);

--
-- Índices para tabela `produtos_gen`
--
ALTER TABLE `produtos_gen`
  ADD PRIMARY KEY (`idProduto_gen`),
  ADD KEY `fk_produto_categoria1` (`idCategoria`),
  ADD KEY `fk_produto_tipo1` (`tipo1`),
  ADD KEY `fk_produto_tipo2` (`tipo2`),
  ADD KEY `fk_produto_tipo3` (`tipo3`),
  ADD KEY `fk_produto_tipo4` (`tipo4`);

--
-- Índices para tabela `prod_categorias`
--
ALTER TABLE `prod_categorias`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Índices para tabela `prod_containers`
--
ALTER TABLE `prod_containers`
  ADD PRIMARY KEY (`idContainer`),
  ADD KEY `fk_produto_container1` (`idProduto`) USING BTREE;

--
-- Índices para tabela `prod_tipos`
--
ALTER TABLE `prod_tipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idCategoria` (`idCategoria`) USING BTREE;

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
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `contratos`
--
ALTER TABLE `contratos`
  MODIFY `idContrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de tabela `faturas`
--
ALTER TABLE `faturas`
  MODIFY `idFatura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `idFornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  MODIFY `idHistoricoAluguel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `obras`
--
ALTER TABLE `obras`
  MODIFY `idObra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `produtos_esp`
--
ALTER TABLE `produtos_esp`
  MODIFY `idProduto_esp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `produtos_gen`
--
ALTER TABLE `produtos_gen`
  MODIFY `idProduto_gen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT de tabela `prod_categorias`
--
ALTER TABLE `prod_categorias`
  MODIFY `idCategoria` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `prod_containers`
--
ALTER TABLE `prod_containers`
  MODIFY `idContainer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `prod_tipos`
--
ALTER TABLE `prod_tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `aditamentos`
--
ALTER TABLE `aditamentos`
  ADD CONSTRAINT `fk_aditamento_contrato1` FOREIGN KEY (`contrato_idContrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
  ADD CONSTRAINT `fk_contrato_has_produto_produto2` FOREIGN KEY (`produto_idProduto`) REFERENCES `produtos_gen` (`idProduto_gen`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `obras`
--
ALTER TABLE `obras`
  ADD CONSTRAINT `fk_obra_cliente1` FOREIGN KEY (`cliente_idCliente`) REFERENCES `clientes` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `produtos_esp`
--
ALTER TABLE `produtos_esp`
  ADD CONSTRAINT `fk_fornecedor` FOREIGN KEY (`idFornecedor`) REFERENCES `fornecedores` (`idFornecedor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produtos_esp` FOREIGN KEY (`idProduto_gen`) REFERENCES `produtos_gen` (`idProduto_gen`);

--
-- Limitadores para a tabela `produtos_gen`
--
ALTER TABLE `produtos_gen`
  ADD CONSTRAINT `fk_produto_categoria1` FOREIGN KEY (`idCategoria`) REFERENCES `prod_categorias` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_tipo1` FOREIGN KEY (`tipo1`) REFERENCES `prod_tipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_tipo2` FOREIGN KEY (`tipo2`) REFERENCES `prod_tipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_tipo3` FOREIGN KEY (`tipo3`) REFERENCES `prod_tipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_tipo4` FOREIGN KEY (`tipo4`) REFERENCES `prod_tipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `prod_containers`
--
ALTER TABLE `prod_containers`
  ADD CONSTRAINT `fk_produto_container1` FOREIGN KEY (`idProduto`) REFERENCES `produtos_esp` (`idProduto_esp`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `prod_tipos`
--
ALTER TABLE `prod_tipos`
  ADD CONSTRAINT `fk_tiposprodutos_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `prod_categorias` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
