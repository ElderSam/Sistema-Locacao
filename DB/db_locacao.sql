CREATE DATABASE IF NOT EXISTS id12706030_db_locacao;
USE id12706030_db_locacao;

-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Abr-2020 às 14:23
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
CREATE PROCEDURE `sp_clientesUpdate_save` (IN `pidCliente` INT, IN `pnome` VARCHAR(45), IN `pstatus` TINYINT, IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pcomplemento` VARCHAR(150), IN `pcidade` VARCHAR(25), IN `pbairro` VARCHAR(25), IN `pnumero` INT(11), IN `puf` CHAR(2), IN `pcep` VARCHAR(45), IN `pcpf` VARCHAR(45), IN `prg` VARCHAR(45), IN `pcnpj` VARCHAR(45), IN `pie` VARCHAR(45), IN `ptipoCliente` VARCHAR(45))  BEGIN
  
    DECLARE vidCliente INT;
    
    SELECT idCliente INTO vidCliente
    FROM clientes
    WHERE idCliente = pidCliente;

    UPDATE clientes
    SET
        nome = pnome, 
        status = pstatus,
        telefone1 = ptelefone1,
        telefone2 = ptelefone2,
        email1 = pemail1,
        email2 = pemail2,
        endereco = pendereco,
        complemento = pcomplemento,
        cidade = pcidade,
        bairro = pbairro,
        numero = pnumero,
        uf = puf,
        cep = pcep,
        cpf = pcpf,
        rg = prg,
        cnpj = pcnpj,
        ie = pie,
        tipoCliente = ptipoCliente
    WHERE idCliente = vidCliente;
    
    SELECT * FROM clientes WHERE idCliente = pidCliente;
    
END$$

CREATE PROCEDURE `sp_clientes_delete` (IN `pidCliente` INT)  BEGIN
  
    DECLARE vidCliente INT;
    
  SELECT idCliente INTO vidCliente
    FROM clientes
    WHERE idCliente = pidCliente;
    
    DELETE FROM clientes WHERE idCliente = pidCliente;
    
END$$

CREATE PROCEDURE `sp_clientes_save` (`pnome` VARCHAR(45), `pstatus` TINYINT, `ptelefone1` VARCHAR(15), `ptelefone2` VARCHAR(15), `pemail1` VARCHAR(45), `pemail2` VARCHAR(45), `pendereco` VARCHAR(45), `pcomplemento` VARCHAR(150), `pcidade` VARCHAR(25), `pbairro` VARCHAR(25), `pnumero` INT(11), `puf` CHAR(2), `pcep` VARCHAR(45), `pcpf` VARCHAR(45), `prg` VARCHAR(45), `pcnpj` VARCHAR(45), `pie` VARCHAR(45), `ptipoCliente` VARCHAR(45))  BEGIN

    DECLARE vidCliente INT;
    
  INSERT INTO clientes (nome, status, telefone1, telefone2, email1, email2, endereco, complemento, cidade, bairro, numero, uf, cep, cpf, rg, cnpj, ie, tipoCliente)
    VALUES(pnome, pstatus, ptelefone1, ptelefone2, pemail1, pemail2, pendereco, pcomplemento, pcidade, pbairro, pnumero, puf, pcep, pcpf, prg, pcnpj, pie, ptipoCliente);
    
    SET vidCliente = LAST_INSERT_ID();
    
    SELECT * FROM clientes WHERE idcliente = LAST_INSERT_ID();
    
END$$

CREATE PROCEDURE `sp_contratosUpdate_save` (IN `pidContrato` INT, IN `pcodContrato` INT, IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `pdtAprovacao` DATETIME, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pnotas` VARCHAR(100), IN `pvalorAluguel` FLOAT, IN `pdtInicio` DATETIME, IN `pdtFim` DATETIME, IN `pstatusOrcamento` TINYINT(4))  BEGIN
  
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

CREATE PROCEDURE `sp_contratos_delete` (IN `pidContrato` INT)  BEGIN
  
    DECLARE vidContrato INT;
    
  SELECT idContrato INTO vidContrato
    FROM contratos
    WHERE idContrato = pidContrato;
    
    DELETE FROM contratos WHERE idContrato = pidContrato;
    
END$$

CREATE PROCEDURE `sp_contratos_save` (IN `pcodContrato` INT, IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `pdtAprovacao` DATETIME, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pnotas` VARCHAR(100), IN `pvalorAluguel` FLOAT, IN `pdtInicio` DATETIME, IN `pdtFim` DATETIME, IN `pstatusOrcamento` TINYINT(4))  BEGIN
  
    DECLARE vidContrato INT;
    
  INSERT INTO contratos (codContrato, obra_idObra, dtEmissao, dtAprovacao, custoEntrega, custoRetirada, notas, valorAluguel, dtInicio, dtFim, statusOrcamento)
    VALUES(pcodContrato, pobra_idObra, pdtEmissao, pdtAprovacao, pcustoEntrega, pcustoRetirada, pnotas, pvalorAluguel, pdtInicio, pdtFim, pstatusOrcamento);
    
    SET vidContrato = LAST_INSERT_ID();
    
    SELECT * FROM contratos WHERE idContrato = LAST_INSERT_ID();
    
END$$

CREATE PROCEDURE `sp_fornecedoresUpdate_save` (IN `pidFornecedor` INT, IN `pcodFornecedor` VARCHAR(3), IN `pnome` VARCHAR(45), IN `pcnpj` VARCHAR(16), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pnumero` VARCHAR(45), IN `pbairro` VARCHAR(45), IN `pcidade` VARCHAR(45), IN `pcomplemento` VARCHAR(100), IN `puf` VARCHAR(45), IN `pcep` CHAR(2), IN `pstatus` TINYINT(4))  BEGIN
  
    DECLARE vidFornecedor INT;
    
    SELECT idFornecedor INTO vidFornecedor
    FROM fornecedores
    WHERE idFornecedor = pidFornecedor;

    UPDATE fornecedores
    SET
        codFornecedor = pcodFornecedor,
        nome = pnome,
        cnpj = pcnpj,
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

CREATE PROCEDURE `sp_fornecedores_delete` (IN `pidFornecedor` INT)  BEGIN
  
    DECLARE vidFornecedor INT;
    
  SELECT idFornecedor INTO vidFornecedor
    FROM fornecedores
    WHERE idFornecedor = pidFornecedor;
    
    DELETE FROM fornecedores WHERE idFornecedor = pidFornecedor;
    
END$$

CREATE PROCEDURE `sp_fornecedores_save` (IN `pcodFornecedor` VARCHAR(3), IN `pnome` VARCHAR(45), IN `pcnpj` VARCHAR(16), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pnumero` VARCHAR(45), IN `pbairro` VARCHAR(45), IN `pcidade` VARCHAR(45), IN `pcomplemento` VARCHAR(100), IN `puf` VARCHAR(45), IN `pcep` CHAR(2), IN `pstatus` TINYINT(4))  BEGIN
  
    DECLARE vidFornecedor INT;
    
  INSERT INTO fornecedores (codFornecedor, nome, cnpj, telefone1, telefone2, email1, email2, endereco, numero, bairro, cidade, complemento, uf, cep, status)
    VALUES(pcodFornecedor, pnome, pcnpj, ptelefone1, ptelefone2, pemail1, pemail2, pendereco, pnumero, pbairro, pcidade, pcomplemento, puf, pcep, pstatus);
    
    SET vidFornecedor = LAST_INSERT_ID();
    
    SELECT * FROM fornecedores WHERE idFornecedor = LAST_INSERT_ID();
    
END$$

CREATE PROCEDURE `sp_historicoalugueisUpdate_save` (IN `pidHistoricoAluguel` INT(11), IN `pcontrato_idContrato` INT(11), IN `pproduto_idProduto` INT(11), IN `pstatus` TINYINT(4), IN `pvlAluguel` FLOAT, IN `pdtInicio` DATE, IN `pdtFinal` DATE, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pobservacao` TEXT)  BEGIN
  
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

CREATE PROCEDURE `sp_historicoalugueis_delete` (IN `pidHistoricoAluguel` INT)  BEGIN
  
    DECLARE vidHistoricoAluguel INT;
    
  SELECT idHistoricoAluguel INTO vidHistoricoAluguel
    FROM historicoalugueis
    WHERE idHistoricoAluguel = pidHistoricoAluguel;
    
    DELETE FROM historicoalugueis WHERE idHistoricoAluguel = pidHistoricoAluguel;
    
END$$

CREATE PROCEDURE `sp_historicoalugueis_save` (IN `pcontrato_idContrato` INT(11), IN `pproduto_idProduto` INT(11), IN `pstatus` TINYINT(4), IN `pvlAluguel` FLOAT, IN `pdtInicio` DATE, IN `pdtFinal` DATE, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pobservacao` TEXT)  BEGIN
  
    DECLARE vidHistoricoAluguel INT;
    
  INSERT INTO historicoalugueis (contrato_idContrato, produto_idProduto, status, vlAluguel, dtInicio, dtFinal, custoEntrega, custoRetirada, observacao)
    VALUES(pcontrato_idContrato, pproduto_idProduto, pstatus, pvlAluguel, pdtInicio, pdtFinal, pcustoEntrega, pcustoRetirada, pobservacao);
    
    SET vidHistoricoAluguel = LAST_INSERT_ID();
    
    SELECT * FROM historicoalugueis WHERE idHistoricoAluguel = LAST_INSERT_ID();
    
END$$

CREATE PROCEDURE `sp_produtos_espUpdate_save` (IN `pidProduto_esp` INT, IN `pidProduto_gen` INT, IN `pcodigoEsp` VARCHAR(70), IN `pvalorCompra` FLOAT, IN `pstatus` TINYINT(4), IN `pdtFabricacao` DATE, IN `pnumSerie` VARCHAR(4), IN `panotacoes` VARCHAR(100), IN `pidFornecedor` INT(11))  BEGIN
  
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

CREATE PROCEDURE `sp_produtos_esp_delete` (IN `pidProduto_esp` INT)  BEGIN
  
    DECLARE vidProduto_esp INT;
    
  SELECT idProduto_esp INTO vidProduto_esp
    FROM produtos_esp
    WHERE idProduto_esp = pidProduto_esp;
    
    DELETE FROM produtos_esp WHERE idProduto_esp = pidProduto_esp;
    
END$$

CREATE PROCEDURE `sp_produtos_esp_save` (IN `pidProduto_gen` INT, IN `pcodigoEsp` VARCHAR(70), IN `pvalorCompra` FLOAT, IN `pstatus` TINYINT(4), IN `pdtFabricacao` DATE, IN `pnumSerie` VARCHAR(4), IN `panotacoes` VARCHAR(100), IN `pidFornecedor` INT(11))  BEGIN
  
    DECLARE vidProduto_esp INT;
    
  INSERT INTO produtos_esp (idProduto_gen, codigoEsp, valorCompra, status, dtFabricacao, numSerie, anotacoes, idFornecedor)
    VALUES(pidProduto_gen, pcodigoEsp, pvalorCompra, pstatus, pdtFabricacao, pnumSerie, panotacoes, pidFornecedor);
    
    SET vidProduto_esp = LAST_INSERT_ID();
    
    SELECT * FROM produtos_esp WHERE idProduto_esp = LAST_INSERT_ID();
    
END$$

CREATE PROCEDURE `sp_produtos_genUpdate_save` (IN `pidProduto_gen` INT, IN `pcodigoGen` VARCHAR(70), IN `pdescricao` VARCHAR(150), IN `pvlBaseAluguel` FLOAT, IN `ptipo1` VARCHAR(3), IN `ptipo2` VARCHAR(3), IN `ptipo3` VARCHAR(3), IN `ptipo4` VARCHAR(3), IN `pidCategoria` INT(4))  BEGIN
  
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

CREATE PROCEDURE `sp_produtos_gen_delete` (IN `pidProduto_gen` INT)  BEGIN
  
    DECLARE vidProduto_gen INT;
    
  SELECT idProduto_gen INTO vidProduto_gen
    FROM produtos_gen
    WHERE idProduto_gen = pidProduto_gen;
    
    DELETE FROM produtos_gen WHERE idProduto_gen = pidProduto_gen;
    
END$$

CREATE PROCEDURE `sp_produtos_gen_save` (IN `pcodigoGen` VARCHAR(70), IN `pdescricao` VARCHAR(150), IN `pvlBaseAluguel` FLOAT, IN `ptipo1` VARCHAR(3), IN `ptipo2` VARCHAR(3), IN `ptipo3` VARCHAR(3), IN `ptipo4` VARCHAR(3), IN `pidCategoria` INT(4))  BEGIN
  
    DECLARE vidProduto_gen INT;
    
  INSERT INTO produtos_gen (codigoGen, descricao, vlBaseAluguel, tipo1, tipo2, tipo3, tipo4, idCategoria)
    VALUES(pcodigoGen, pdescricao, pvlBaseAluguel, ptipo1, ptipo2, ptipo3, ptipo4, pidCategoria);
    
    SET vidProduto_gen = LAST_INSERT_ID();
    
    SELECT * FROM produtos_gen WHERE idProduto_gen = LAST_INSERT_ID();
    
END$$

CREATE PROCEDURE `sp_prod_containersUpdate_save` (IN `pidProduto` INT(11), IN `ptipoPorta` VARCHAR(45), IN `pjanelasLat` INT(11), IN `pjanelasCirc` INT(11), IN `pforrado` TINYINT(4), IN `peletrificado` TINYINT(4), IN `ptomadas` INT(11), IN `plampadas` INT(11), IN `pentradasAC` INT(11), IN `psanitarios` INT(11), IN `pchuveiro` TINYINT(4))  BEGIN
  
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

CREATE PROCEDURE `sp_prod_containers_delete` (IN `pidProduto` INT)  BEGIN
  
    DECLARE vidProduto INT;
    
  SELECT idProduto INTO vidProduto
    FROM prod_containers
    WHERE idProduto = pidProduto;
    
    DELETE FROM prod_containers WHERE idProduto = pidProduto;
    
END$$

CREATE PROCEDURE `sp_prod_containers_save` (IN `pidProduto` INT(11), IN `ptipoPorta` VARCHAR(45), IN `pjanelasLat` INT(11), IN `pjanelasCirc` INT(11), IN `pforrado` TINYINT(4), IN `peletrificado` TINYINT(4), IN `ptomadas` INT(11), IN `plampadas` INT(11), IN `pentradasAC` INT(11), IN `psanitarios` INT(11), IN `pchuveiro` TINYINT(4))  BEGIN
  
    DECLARE vidContainer INT;
    
    INSERT INTO prod_containers (idProduto, tipoPorta, janelasLat, janelasCirc, forrado, eletrificado, tomadas, lampadas, entradasAC, sanitarios, chuveiro)
    VALUES(pidProduto, ptipoPorta, pjanelasLat, pjanelasCirc, pforrado, peletrificado, ptomadas, plampadas, pentradasAC, psanitarios, pchuveiro);

    SET vidContainer = LAST_INSERT_ID();
    SELECT * FROM prod_containers WHERE idContainer = LAST_INSERT_ID();
    
END$$

CREATE PROCEDURE `sp_prod_tiposUpdate_save` (IN `pid` INT, IN `pdescTipo` VARCHAR(100), IN `pidCategoria` INT(4), IN `pordem_tipo` INT(1), IN `pcodTipo` VARCHAR(2))  BEGIN
  
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

CREATE PROCEDURE `sp_prod_tipos_delete` (IN `pid` INT)  BEGIN
  
    DECLARE vid INT;
    
  SELECT id INTO vid
    FROM prod_tipos
    WHERE id = pid;
    
    DELETE FROM prod_tipos WHERE id = pid;
    
END$$

CREATE PROCEDURE `sp_prod_tipos_save` (IN `pdescTipo` VARCHAR(100), IN `pidCategoria` INT(4), IN `pordem_tipo` INT(1), IN `pcodTipo` VARCHAR(2))  BEGIN
  
    DECLARE vid INT;
    
  INSERT INTO prod_tipos (descTipo, idCategoria, ordem_tipo, codTipo)
    VALUES(pdescTipo, pidCategoria, pordem_tipo, pcodTipo);
    
    SET vid = LAST_INSERT_ID();
    
    SELECT * FROM prod_tipos WHERE id = LAST_INSERT_ID();
    
END$$

CREATE PROCEDURE `sp_responsaveisUpdate_save` (IN `pidResp` INT, IN `prespObra` VARCHAR(45), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `ptelefone3` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `panotacoes` VARCHAR(150), IN `pid_fk_cliente` INT)  BEGIN
  
    DECLARE vidResp INT;
    
    SELECT idResp INTO vidResp
    FROM resp_obras
    WHERE idResp = pidResp;

    UPDATE resp_obras
    SET
        respObra = prespObra, 
        telefone1 = ptelefone1,
        telefone2 = ptelefone2,
        telefone3 = ptelefone3,
        email1 = pemail1,
        email2 = pemail2,
        anotacoes = panotacoes,
        id_fk_cliente = pid_fk_cliente
    WHERE idResp = vidResp;
    
    SELECT * FROM resp_obras WHERE idResp = pidResp;
    
END$$

CREATE PROCEDURE `sp_responsaveis_delete` (IN `pidResp` INT)  BEGIN
  
    DECLARE vidResp INT;
    
  SELECT idResp INTO vidResp
    FROM resp_obras
    WHERE idResp = pidResp;
    
    DELETE FROM resp_obras WHERE idResp = pidResp;
    
END$$

CREATE PROCEDURE `sp_responsaveis_save` (IN `prespObra` VARCHAR(45), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `ptelefone3` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `panotacoes` VARCHAR(150), IN `pid_fk_cliente` INT)  BEGIN
DECLARE vidResp INT;
    
  INSERT INTO resp_obras (respObra, telefone1, telefone2, telefone3, email1, email2, anotacoes, id_fk_cliente)
    VALUES(prespObra, ptelefone1, ptelefone2, ptelefone3, pemail1, pemail2, panotacoes, pid_fk_cliente);
    
    SET vidResp = LAST_INSERT_ID();
    
    SELECT * FROM resp_obras WHERE idResp = LAST_INSERT_ID();
    
END$$

CREATE PROCEDURE `sp_usuariosUpdate_save` (IN `pidUsuario` INT, IN `pnomeCompleto` VARCHAR(60), IN `pfuncao` VARCHAR(45), IN `pnomeUsuario` VARCHAR(45), IN `pemail` VARCHAR(45), IN `padministrador` TINYINT, IN `pfoto` VARCHAR(100))  BEGIN
  
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

CREATE PROCEDURE `sp_usuarios_delete` (IN `pidusuario` INT)  BEGIN
  
    DECLARE vidUsuario INT;
    
  SELECT idUsuario INTO vidUsuario
    FROM usuarios
    WHERE idusuario = pidusuario;
    
    DELETE FROM usuarios WHERE idUsuario = pidUsuario;
    
END$$

CREATE PROCEDURE `sp_usuarios_save` (`pnomeCompleto` VARCHAR(60), `pfuncao` VARCHAR(45), `pnomeUsuario` VARCHAR(45), `psenha` VARCHAR(250), `pemail` VARCHAR(45), `padministrador` TINYINT, `pfoto` VARCHAR(100))  BEGIN
  
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
  `nome` varchar(45) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `email1` varchar(45) DEFAULT NULL,
  `email2` varchar(45) DEFAULT NULL,
  `endereco` varchar(45) DEFAULT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  `cidade` varchar(25) DEFAULT NULL,
  `bairro` varchar(20) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `uf` char(2) DEFAULT NULL,
  `cep` varchar(45) DEFAULT NULL,
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

INSERT INTO `clientes` (`idCliente`, `nome`, `status`, `telefone1`, `telefone2`, `email1`, `email2`, `endereco`, `complemento`, `cidade`, `bairro`, `numero`, `uf`, `cep`, `cpf`, `rg`, `cnpj`, `ie`, `tipoCliente`, `dtCadastro`) VALUES
(33, 'Construtora Guilhermina', 1, '(19) 5454-54545', '(54) 5454-54545', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', 'Rua Jorge Salibe Sobrinho', '', 'Limeira', 'Parque das Nações', 454, 'BA', '13481-659', '', '', '53.252.352/5252-55', '423432423523532', 'J', '2020-04-04 19:07:05'),
(34, 'Construtora do Matheusss', 1, '(19) 9953-13563', '', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', 'Dr. Arlindo Justos Baptistella', 'sdffsfsfdsfdsfsgsghrjjnffjf', 'Limeira', 'Jardim Botânicokjs', 424, 'AM', '13481-659', '', '', '86.867.867/8868-86', '425454543453', 'J', '2020-04-04 21:33:41'),
(35, 'Construtela', 1, '(19) 9438-74384', '(19) 5379-8537', 'construtela_exemplo@gmail.com', 'douglas.rnmeriano@gmail.com', 'Rua Thereza de Oliveira Lima ', 'Este é um exemplo de cliente', 'Piracicaba', 'Campos do Conde', 232, 'RN', '31737-361', '', '', '09.804.980/2804-34', '3283748983248', 'J', '2020-04-25 08:08:03'),
(36, 'Construtora Teste', 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', 'J', '2020-04-25 17:40:02'),
(37, 'CONSTRUTOR TESTE', 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', 'J', '2020-04-25 20:56:30'),
(38, 'CLIENTE TESET', 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', 'J', '2020-04-25 20:56:50');

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
  `cnpj` varchar(16) DEFAULT NULL,
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

INSERT INTO `fornecedores` (`idFornecedor`, `codFornecedor`, `nome`, `cnpj`, `telefone1`, `telefone2`, `email1`, `email2`, `endereco`, `numero`, `bairro`, `cidade`, `complemento`, `uf`, `cep`, `status`, `dtCadastro`) VALUES
(1, '001', 'Fornecedor X', '12654345345346', '(19) 9999-9999', '(99) 99999-9999', '', '', 'Rua x', 344, 'jd. do filtro', 'Araras', '', 'SP', '09', 1, '2020-03-03 22:09:00'),
(2, '002', 'Cargueiro Fornecedor de Containers', NULL, '11 99992344', '1255550000', 'cargueiro@grupo.com', 'adm@grupo.com', 'Av. do Louvre', 1009, 'Vila Litoral', 'Santos', '', 'SP', '10', 1, NULL),
(4, '003', 'Douglas Numeriano', NULL, '19 99999999', '19 99999999', 'douglas@gmail.com', '', 'Rua Maria Aparecida', 76, 'jd. das Flores', 'Limeira', 'prédio', 'SP', '13', 1, '2020-03-30 15:29:33'),
(5, '004', 'Fornecedor YXZ', '23423423423432', '1975363234', '19453656453', 'adm@bergamus.com', 'fincancas@bergamus.com', 'Avenida Brasil', 340, 'Centro', 'São Paulo', 'Barracão', 'SP', '10', 1, '2020-03-30 17:38:44');

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

-- --------------------------------------------------------

--
-- Estrutura da tabela `obras`
--

CREATE TABLE `obras` (
  `idObra` int(11) NOT NULL,
  `codObra` int(11) NOT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  `tipoEndereco` varchar(45) NOT NULL,
  `cidade` varchar(15) NOT NULL,
  `bairro` varchar(20) NOT NULL,
  `numero` int(11) NOT NULL,
  `uf` char(2) NOT NULL,
  `cep` varchar(45) NOT NULL,
  `endereco` varchar(45) NOT NULL,
  `id_fk_resp` int(11) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp(),
  `id_fk_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Estrutura da tabela `resp_obras`
--

CREATE TABLE `resp_obras` (
  `idResp` int(11) NOT NULL,
  `respObra` varchar(45) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `telefone3` varchar(15) DEFAULT NULL,
  `email1` varchar(45) DEFAULT NULL,
  `email2` varchar(45) DEFAULT NULL,
  `anotacoes` varchar(150) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp(),
  `id_fk_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `resp_obras`
--

INSERT INTO `resp_obras` (`idResp`, `respObra`, `telefone1`, `telefone2`, `telefone3`, `email1`, `email2`, `anotacoes`, `dtCadastro`, `id_fk_cliente`) VALUES
(3, 'João', '8403804324', '43543543534', '980304250495', 'joao_exemplo@hotmail.com', '', '', '2020-04-16 07:28:56', 34),
(5, 'Felipe', '(19) 8464-75345', '(19) 9844-75865', '(19) 8756-53453', 'felipe_exemplo@gmail.com', 'felipe_exemplo2@gmail.com', '', '2020-04-25 11:29:50', 34),
(7, 'Danilo', '(19) 7483-84865', '', '', 'danilo_exemplo@gamil.com', '', '', '2020-04-25 11:42:39', 34),
(10, 'Eduardo', '(19) 9832-73727', '', '', 'eduardo_exemplo@gmail.com', '', '', '2020-04-25 12:48:41', 35),
(12, 'Antônio da Silva', '(19) 7737-38383', '', '', 'antonio_exemplo@hotmail.com', 'antonio_exemplo2@hotmail.com', 'Este é um texto de exemplo alterado', '2020-04-25 13:35:14', 35),
(13, 'Luiz Antônio', '(11) 3445-6789', '(19) 9788-86888', '', 'luiz_antonio@gmail.com', '', '', '2020-04-25 13:38:11', 33);

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
  ADD KEY `fk_obra_cliente1` (`id_fk_resp`),
  ADD KEY `id_fk_cliente` (`id_fk_cliente`);

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
-- Índices para tabela `resp_obras`
--
ALTER TABLE `resp_obras`
  ADD PRIMARY KEY (`idResp`),
  ADD KEY `id_fkcliente` (`id_fk_cliente`);

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
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
  MODIFY `idFornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  MODIFY `idHistoricoAluguel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `obras`
--
ALTER TABLE `obras`
  MODIFY `idObra` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de tabela `resp_obras`
--
ALTER TABLE `resp_obras`
  MODIFY `idResp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- Restrições para despejos de tabelas
--

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
  ADD CONSTRAINT `fk_obra_cliente1` FOREIGN KEY (`id_fk_resp`) REFERENCES `clientes` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_fk_cliente` FOREIGN KEY (`id_fk_cliente`) REFERENCES `clientes` (`idCliente`),
  ADD CONSTRAINT `id_fkresp` FOREIGN KEY (`id_fk_resp`) REFERENCES `resp_obras` (`idResp`);

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

--
-- Limitadores para a tabela `resp_obras`
--
ALTER TABLE `resp_obras`
  ADD CONSTRAINT `id_fkcliente` FOREIGN KEY (`id_fk_cliente`) REFERENCES `clientes` (`idCliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
