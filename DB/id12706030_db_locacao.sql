-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05-Maio-2020 às 23:05
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
-- Banco de dados: `id12706030_db_locacao`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_clientesUpdate_save` (IN `pidCliente` INT, IN `pnome` VARCHAR(45), IN `pstatus` TINYINT, IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pcomplemento` VARCHAR(150), IN `pcidade` VARCHAR(25), IN `pbairro` VARCHAR(25), IN `pnumero` INT(11), IN `puf` CHAR(2), IN `pcep` VARCHAR(45), IN `pcpf` VARCHAR(45), IN `prg` VARCHAR(45), IN `pcnpj` VARCHAR(45), IN `pie` VARCHAR(45), IN `ptipoCliente` VARCHAR(45))  BEGIN
  
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_clientes_delete` (IN `pidCliente` INT)  BEGIN
  
    DECLARE vidCliente INT;
    
  SELECT idCliente INTO vidCliente
    FROM clientes
    WHERE idCliente = pidCliente;
    
    DELETE FROM clientes WHERE idCliente = pidCliente;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_clientes_save` (`pnome` VARCHAR(45), `pstatus` TINYINT, `ptelefone1` VARCHAR(15), `ptelefone2` VARCHAR(15), `pemail1` VARCHAR(45), `pemail2` VARCHAR(45), `pendereco` VARCHAR(45), `pcomplemento` VARCHAR(150), `pcidade` VARCHAR(25), `pbairro` VARCHAR(25), `pnumero` INT(11), `puf` CHAR(2), `pcep` VARCHAR(45), `pcpf` VARCHAR(45), `prg` VARCHAR(45), `pcnpj` VARCHAR(45), `pie` VARCHAR(45), `ptipoCliente` VARCHAR(45))  BEGIN

    DECLARE vidCliente INT;
    
  INSERT INTO clientes (nome, status, telefone1, telefone2, email1, email2, endereco, complemento, cidade, bairro, numero, uf, cep, cpf, rg, cnpj, ie, tipoCliente)
    VALUES(pnome, pstatus, ptelefone1, ptelefone2, pemail1, pemail2, pendereco, pcomplemento, pcidade, pbairro, pnumero, puf, pcep, pcpf, prg, pcnpj, pie, ptipoCliente);
    
    SET vidCliente = LAST_INSERT_ID();
    
    SELECT * FROM clientes WHERE idcliente = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contratosUpdate_save` (IN `pidContrato` INT, IN `pcodContrato` INT, IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `psolicitante` VARCHAR(50), IN `ptelefone` VARCHAR(15), IN `pemail` VARCHAR(40), IN `pdtAprovacao` DATETIME, IN `pnotas` VARCHAR(100), IN `pvalorTotal` FLOAT, IN `pdtInicio` DATETIME, IN `pprazoDuracao` VARCHAR(40), IN `pstatusOrcamento` TINYINT(4))  BEGIN
  
    DECLARE vidContrato INT;
    
    SELECT idContrato INTO vidContrato
    FROM contratos
    WHERE idContrato = pidContrato;

    UPDATE contratos
    SET
      idContrato = pidContrato,
      codContrato = pcodContrato,
      dtEmissao = pdtEmissao,
      solicitante = psolicitante,
      telefone = ptelefone,
      email = pemail,
      dtAprovacao = pdtAprovacao,
      notas = pnotas,
      valorTotal = pvalorTotal,
      dtInicio = pdtInicio,
      prazoDuracao = pprazoDuracao,
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contratos_save` (IN `pcodContrato` INT, IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `psolicitante` VARCHAR(50), IN `ptelefone` VARCHAR(15), IN `pemail` VARCHAR(40), IN `pdtAprovacao` DATETIME, IN `pnotas` VARCHAR(100), IN `pvalorTotal` FLOAT, IN `pdtInicio` DATETIME, IN `pprazoDuracao` VARCHAR(40), IN `pstatusOrcamento` TINYINT(4))  BEGIN
  
    DECLARE vidContrato INT;
    
  INSERT INTO contratos (codContrato, obra_idObra, dtEmissao, solicitante, telefone, email, dtAprovacao, notas, valorTotal, dtInicio, prazoDuracao, statusOrcamento)
    VALUES(pcodContrato, pobra_idObra, pdtEmissao, psolicitante, ptelefone, pemail, pdtAprovacao, pnotas, pvalorTotal, pdtInicio, pprazoDuracao, pstatusOrcamento);
    
    SET vidContrato = LAST_INSERT_ID();
    
    SELECT * FROM contratos WHERE idContrato = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contrato_itensUpdate_save` (IN `pidItem` INT(11), IN `pidContrato` INT(11), IN `pidProduto_gen` INT(11), IN `pvlAluguel` FLOAT, IN `quantidade` VARCHAR(4), IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pobservacao` TEXT)  BEGIN
  
    DECLARE vidItem INT;
    
    SELECT idItem INTO vidItem
    FROM contrato_itens
    WHERE idItem = pidItem;

    UPDATE contrato_itens
    SET
      idItem = pidItem,
      idContrato = pidContrato,
      idProduto_gen = pidProduto_gen,
      vlAluguel = pvlAluguel,
      quantidade = pquantidade,
      custoEntrega = pcustoEntrega,
      custoRetirada = pcustoRetirada,
      observacao = pobservacao

    
    WHERE idItem = vidItem;
    
    SELECT * FROM contrato_itens WHERE idItem = pidItem;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contrato_itens_delete` (IN `pidItem` INT)  BEGIN
  
    DECLARE vidItem INT;
    
  SELECT idItem INTO vidItem
    FROM contrato_itens
    WHERE idItem = pidItem;
    
    DELETE FROM contrato_itens WHERE idItem = pidItem;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contrato_itens_save` (IN `pidContrato` INT(11), IN `pidProduto_gen` INT(11), IN `pvlAluguel` FLOAT, IN `pquantidade` VARCHAR(4), IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pobservacao` TEXT)  BEGIN
  
    DECLARE vidItem INT;
    
  INSERT INTO contrato_itens (idContrato, idProduto_gen, vlAluguel, quantidade, custoEntrega, custoRetirada, observacao)
    VALUES(pidContrato, pidProduto_gen, pvlAluguel, pquantidade, pcustoEntrega, pcustoRetirada, pobservacao);
    
    SET vidItem = LAST_INSERT_ID();
    
    SELECT * FROM contrato_itens WHERE idItem = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fornecedoresUpdate_save` (IN `pidFornecedor` INT, IN `pcodFornecedor` VARCHAR(3), IN `pnome` VARCHAR(45), IN `pcnpj` VARCHAR(16), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pnumero` VARCHAR(45), IN `pbairro` VARCHAR(45), IN `pcidade` VARCHAR(45), IN `pcomplemento` VARCHAR(100), IN `puf` VARCHAR(45), IN `pcep` CHAR(2), IN `pstatus` TINYINT(4))  BEGIN
  
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fornecedores_delete` (IN `pidFornecedor` INT)  BEGIN
  
    DECLARE vidFornecedor INT;
    
  SELECT idFornecedor INTO vidFornecedor
    FROM fornecedores
    WHERE idFornecedor = pidFornecedor;
    
    DELETE FROM fornecedores WHERE idFornecedor = pidFornecedor;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fornecedores_save` (IN `pcodFornecedor` VARCHAR(3), IN `pnome` VARCHAR(45), IN `pcnpj` VARCHAR(16), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pnumero` VARCHAR(45), IN `pbairro` VARCHAR(45), IN `pcidade` VARCHAR(45), IN `pcomplemento` VARCHAR(100), IN `puf` VARCHAR(45), IN `pcep` CHAR(2), IN `pstatus` TINYINT(4))  BEGIN
  
    DECLARE vidFornecedor INT;
    
  INSERT INTO fornecedores (codFornecedor, nome, cnpj, telefone1, telefone2, email1, email2, endereco, numero, bairro, cidade, complemento, uf, cep, status)
    VALUES(pcodFornecedor, pnome, pcnpj, ptelefone1, ptelefone2, pemail1, pemail2, pendereco, pnumero, pbairro, pcidade, pcomplemento, puf, pcep, pstatus);
    
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_responsaveisUpdate_save` (IN `pidResp` INT, IN `prespObra` VARCHAR(45), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `ptelefone3` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `panotacoes` VARCHAR(150), IN `pid_fk_cliente` INT)  BEGIN
  
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_responsaveis_delete` (IN `pidResp` INT)  BEGIN
  
    DECLARE vidResp INT;
    
  SELECT idResp INTO vidResp
    FROM resp_obras
    WHERE idResp = pidResp;
    
    DELETE FROM resp_obras WHERE idResp = pidResp;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_responsaveis_save` (IN `prespObra` VARCHAR(45), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `ptelefone3` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `panotacoes` VARCHAR(150), IN `pid_fk_cliente` INT)  BEGIN
DECLARE vidResp INT;
    
  INSERT INTO resp_obras (respObra, telefone1, telefone2, telefone3, email1, email2, anotacoes, id_fk_cliente)
    VALUES(prespObra, ptelefone1, ptelefone2, ptelefone3, pemail1, pemail2, panotacoes, pid_fk_cliente);
    
    SET vidResp = LAST_INSERT_ID();
    
    SELECT * FROM resp_obras WHERE idResp = LAST_INSERT_ID();
    
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
  `solicitante` varchar(50) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `dtAprovacao` date DEFAULT NULL,
  `dtInicio` date DEFAULT NULL,
  `prazoDuracao` varchar(40) DEFAULT NULL,
  `statusOrcamento` tinyint(4) NOT NULL,
  `valorTotal` float DEFAULT NULL,
  `notas` varchar(100) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `contratos`
--

INSERT INTO `contratos` (`idContrato`, `codContrato`, `obra_idObra`, `dtEmissao`, `solicitante`, `telefone`, `email`, `dtAprovacao`, `dtInicio`, `prazoDuracao`, `statusOrcamento`, `valorTotal`, `notas`, `dtCadastro`) VALUES
(87, '1', 1, '2020-12-31', '', NULL, NULL, NULL, NULL, NULL, 0, 999, '', '2020-05-02 13:44:03'),
(88, '2', 1, '2020-12-31', '', NULL, NULL, NULL, NULL, NULL, 0, 32452, '', '2020-05-02 13:46:42'),
(90, '3', 1, '2020-12-31', '', NULL, NULL, NULL, NULL, NULL, 0, 800, '', '2020-05-02 13:50:24'),
(91, '4', 1, '2020-12-31', 'Elder', '19 99999999', 'eldersamuel98@gmail.com', NULL, NULL, NULL, 0, 3456, '', '2020-05-02 14:28:11'),
(92, '5', 1, '2020-12-31', 'elder', '19999999999', 'eldersamuel98@gmail.com', NULL, NULL, NULL, 0, 345, '', '2020-05-02 14:40:42'),
(94, '6', 1, '2020-12-31', '500', '19888000000', '5656@gmail.com', NULL, NULL, NULL, 0, 435, '', '2020-05-02 15:22:12'),
(95, '7', 1, '2020-12-31', '4353', '32423423423', 'eldersamuel98@gmail.com', NULL, NULL, NULL, 0, 3242, '', '2020-05-02 15:24:21'),
(96, '8', 1, '2020-12-31', 'rwr', '23435566545', 'eldersamuel98@gmail.com', NULL, NULL, NULL, 3, 324, '', '2020-05-02 15:55:07'),
(97, '9', 1, '2020-12-31', '453', '34534543453', 'eldersamuel98@gmail.com', NULL, NULL, NULL, 3, -1345, '', '2020-05-02 16:10:51'),
(98, '10', 1, '2020-12-31', '234', '45435343543', 'eldersamuel98@gmail.com', NULL, NULL, NULL, 0, 23452, '', '2020-05-02 16:12:21'),
(99, '10', 1, '2020-11-01', '2345', '45435343534', 'eldersamuel98@gmail.com', NULL, NULL, NULL, 3, 2453, '', '2020-05-02 16:14:51'),
(100, '10', 1, '2020-12-31', '4353', '45345433534', 'eldersamuel98@gmail.com', NULL, NULL, NULL, 0, 345, '', '2020-05-02 16:21:22'),
(101, '10', 1, '2020-12-31', '34535', '35345344534', '', NULL, NULL, NULL, 0, 345345, '', '2020-05-02 16:25:47'),
(102, '10', 1, '2020-12-31', 'fsf', '', '', NULL, NULL, NULL, 0, 345, '', '2020-05-02 16:28:06'),
(103, '10', 1, '2020-12-31', 'fwef', '', '', NULL, NULL, NULL, 0, -13453, '', '2020-05-02 16:32:37'),
(104, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 345, '', '2020-05-02 16:41:44'),
(105, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 3453, '', '2020-05-02 16:44:21'),
(106, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 345, '', '2020-05-02 16:58:03'),
(107, '10', 1, '2020-12-31', '3523', '', '', NULL, NULL, NULL, 0, 345, '', '2020-05-02 17:03:20'),
(108, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 345, '', '2020-05-02 17:04:56'),
(109, '10', 1, '2020-12-31', 'elder', '', '', NULL, NULL, NULL, 0, 3453, '', '2020-05-02 21:23:52'),
(110, '10', 1, '2020-12-31', 'elder', '23423434232', '234234@gmail.com', NULL, NULL, NULL, 0, 3434, '', '2020-05-03 20:00:56'),
(111, '10', 1, '2020-12-31', 'elder', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 20:14:42'),
(112, '10', 1, '2020-12-31', 'elder', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 20:15:01'),
(113, '10', 1, '2020-12-31', 'elder', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 20:21:09'),
(114, '10', 1, '2020-12-31', 'eder', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 20:27:05'),
(115, '10', 1, '2020-12-31', 'elder', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 20:36:22'),
(116, '10', 1, '2020-12-31', 'elder', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 21:05:17'),
(117, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 21:08:07'),
(118, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 21:11:31'),
(119, '10', 1, '2020-12-31', 'fds', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 22:53:31'),
(120, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 22:55:26'),
(121, '10', 1, '2020-12-31', '23', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 23:00:07'),
(122, '10', 1, '2020-12-31', 'TESF', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 23:08:18'),
(123, '10', 1, '2020-12-31', '32', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 23:11:14'),
(124, '10', 1, '2020-12-31', 'tes', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 23:26:02'),
(125, '10', 1, '2020-12-31', 'gse', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-03 23:28:12'),
(126, '10', 1, '2020-12-31', 'elder', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:08:37'),
(127, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:21:48'),
(128, '10', 1, '2020-12-31', 'tets', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:23:57'),
(129, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:26:33'),
(130, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:29:37'),
(131, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:32:26'),
(132, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:33:59'),
(133, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:35:23'),
(134, '10', 1, '2020-12-31', 'test', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:36:17'),
(135, '10', 1, '2020-12-31', 'test', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:36:56'),
(136, '10', 1, '2020-12-31', 'et', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:38:44'),
(137, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:40:33'),
(138, '10', 1, '2020-12-31', 'test', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:45:21'),
(139, '10', 1, '2020-12-31', 'test', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:50:49'),
(140, '10', 1, '2020-12-31', 'rt', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:53:04'),
(141, '10', 1, '2020-12-31', '245', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:55:30'),
(142, '10', 1, '2020-12-31', 'teste', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:56:27'),
(143, '10', 1, '2020-02-02', '23', '', 'eldersamuel98@gmail.com', NULL, NULL, NULL, 0, 0, '', '2020-05-05 08:58:57'),
(144, '10', 1, '2020-12-31', '234', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 09:28:03'),
(145, '10', 1, '2020-07-05', '23', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 09:31:50'),
(146, '10', 1, '2020-12-31', '4353', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 09:32:53'),
(147, '10', 1, '2020-12-31', 'te', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 09:34:19'),
(148, '10', 1, '2020-12-31', 'dsf', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 10:24:37'),
(149, '10', 1, '2020-12-31', '45', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 10:25:47'),
(150, '10', 1, '0423-03-24', '3243', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 10:43:40'),
(151, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 10:51:12'),
(152, '10', 1, '2020-12-31', '234', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 10:54:46'),
(153, '10', 1, '2020-12-31', '43', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 10:57:34'),
(154, '10', 1, '2020-03-03', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 11:02:30'),
(155, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 11:05:42'),
(156, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 11:09:01'),
(157, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 11:09:16'),
(158, '10', 1, '2020-12-31', '435', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 11:10:27'),
(159, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 11:12:04'),
(160, '10', 1, '2020-12-31', '435', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 11:12:50'),
(161, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:04:04'),
(162, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:11:39'),
(163, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:23:20'),
(164, '10', 1, '2020-12-31', '435', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:33:33'),
(165, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:37:09'),
(166, '10', 1, '2020-12-31', '43', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:38:36'),
(167, '10', 1, '2020-12-31', '435', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:43:58'),
(168, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:44:23'),
(169, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:46:32'),
(170, '10', 1, '2020-12-31', '345345', '34', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:50:36'),
(171, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:54:24'),
(172, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:55:03'),
(173, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 14:57:31'),
(174, '10', 1, '2020-12-31', '234', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 15:24:10'),
(175, '10', 1, '2020-12-31', '43', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 15:32:40'),
(176, '10', 1, '2020-12-31', '34', '33', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 15:40:52'),
(177, '10', 1, '2020-12-31', '4532', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 15:42:03'),
(178, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 15:57:46'),
(179, '10', 1, '2020-12-31', '234', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 16:04:38'),
(180, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 16:06:31'),
(181, '10', 1, '2020-12-31', '324', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 16:08:55'),
(182, '10', 1, '2020-12-31', '234', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 16:10:51'),
(183, '10', 1, '2020-12-31', '324', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 16:48:21'),
(184, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 16:51:47'),
(185, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 16:55:29'),
(186, '10', 1, '2020-12-31', '324', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 16:57:53'),
(187, '10', 1, '2020-12-31', '234', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-05 17:09:51'),
(188, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-05 17:13:02'),
(189, '10', 1, '2020-12-31', '234', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-05 17:15:28'),
(190, '10', 1, '2020-12-31', '324', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-05 17:18:56'),
(191, '10', 1, '2020-12-31', '234', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-05 17:20:43'),
(192, '10', 1, '2020-12-31', '32', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-05 17:24:51'),
(193, '10', 1, '2020-12-31', '32', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-05 17:28:16'),
(194, '10', 1, '2020-12-31', '324', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 17:32:34'),
(195, '10', 1, '2020-12-31', '43', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 17:33:39'),
(196, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 17:43:27'),
(197, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 17:46:38'),
(198, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 17:48:46'),
(199, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 17:49:35'),
(200, '10', 1, '2020-12-31', '435', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 17:51:24'),
(201, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 17:52:04'),
(202, '10', 1, '2020-12-31', '34', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 17:56:20'),
(203, '10', 1, '2020-12-31', '345', '', '', NULL, NULL, NULL, 0, 0, '', '2020-05-05 18:02:21');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contrato_itens`
--

CREATE TABLE `contrato_itens` (
  `idItem` int(11) NOT NULL,
  `idContrato` int(11) NOT NULL,
  `idProduto_gen` int(11) NOT NULL,
  `vlAluguel` float NOT NULL,
  `quantidade` varchar(4) NOT NULL,
  `custoEntrega` float DEFAULT NULL,
  `custoRetirada` float DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `contrato_itens`
--

INSERT INTO `contrato_itens` (`idItem`, `idContrato`, `idProduto_gen`, `vlAluguel`, `quantidade`, `custoEntrega`, `custoRetirada`, `observacao`, `dtCadastro`) VALUES
(1, 115, 180, 450, '1', 0, 0, '', '2020-05-03 20:38:23'),
(2, 115, 180, 450, '1', 0, 0, '', '2020-05-03 20:38:36'),
(3, 115, 180, 450, '1', 0, 0, '', '2020-05-03 20:47:06'),
(4, 115, 180, 450, '1', 0, 0, '', '2020-05-03 20:47:27'),
(5, 118, 187, 15470, '2', 0, 0, '', '2020-05-03 21:12:56'),
(6, 120, 180, 450, '1', 200, 200, '', '2020-05-03 22:55:44'),
(7, 121, 180, 4502, '2', 200, 200, '', '2020-05-03 23:00:23'),
(8, 122, 180, 450, '34', 200, 200, '', '2020-05-03 23:09:06'),
(9, 123, 180, 450, '2', 200, 200, '', '2020-05-03 23:11:56'),
(10, 124, 180, 450, '3', 300, 300, '', '2020-05-03 23:26:28'),
(11, 125, 180, 450, '2', 200, 200, '', '2020-05-03 23:28:31'),
(12, 126, 180, 450, '3', 200, 200, '', '2020-05-05 08:09:07'),
(13, 126, 187, 15470, '2', 500, 500, '', '2020-05-05 08:11:08'),
(14, 127, 187, 15470, '3', 250, 250, '', '2020-05-05 08:22:04'),
(15, 128, 187, 15470, '2', 200, 200, '', '2020-05-05 08:24:11'),
(16, 129, 187, 15470, '1', 140, 140, '', '2020-05-05 08:26:49'),
(17, 130, 187, 15470, '2', 230, 230, '', '2020-05-05 08:29:51'),
(18, 131, 187, 15470, '3', 560, 560, '', '2020-05-05 08:32:58'),
(19, 132, 187, 15470, '3', 304, 304, '', '2020-05-05 08:34:11'),
(20, 133, 187, 15470, '3', 340, 450, '', '2020-05-05 08:35:33'),
(21, 134, 187, 15470, '3', 3454, 45, '', '2020-05-05 08:36:27'),
(22, 135, 187, 15470, '3', 340, 4560, '', '2020-05-05 08:37:08'),
(23, 136, 180, 450, '3', 34, 34, '', '2020-05-05 08:39:06'),
(24, 137, 180, 450, '3', 450, 450, '', '2020-05-05 08:40:57'),
(25, 138, 181, 234, '3', 400, 400, '', '2020-05-05 08:45:43'),
(26, 139, 191, 200, '45', 230, 340, '', '2020-05-05 08:51:12'),
(27, 140, 191, 200, '23', 345, 435, '', '2020-05-05 08:53:28'),
(28, 141, 191, 200, '34', 34, 342, '', '2020-05-05 08:55:41'),
(29, 142, 191, 200, '4', 3433, 34, '', '2020-05-05 08:56:40'),
(30, 143, 191, 200, '45', 34, 34, '', '2020-05-05 09:00:28'),
(31, 144, 190, 125.66, '3', 334, 34, '', '2020-05-05 09:28:19'),
(32, 145, 190, 125.66, '3', 3, 3, '', '2020-05-05 09:32:12'),
(33, 146, 190, 125.66, '34', 34, 34, '', '2020-05-05 09:33:05'),
(34, 147, 190, 125.66, '34', 34, 345, '', '2020-05-05 09:34:33'),
(35, 148, 190, 125.66, '34', 34, 34, '', '2020-05-05 10:24:55'),
(36, 149, 190, 125.66, '3', 343, 343, '', '2020-05-05 10:26:18'),
(37, 150, 180, 450, '4', 234, 34, '', '2020-05-05 10:43:58'),
(38, 151, 180, 450, '3', 34, 34, '', '2020-05-05 10:51:22'),
(39, 152, 180, 450, '44', 45, 45, '', '2020-05-05 10:55:00'),
(40, 153, 180, 450, '34', 2, 2, '', '2020-05-05 10:57:47'),
(41, 154, 180, 450, '3', 3, 3, '', '2020-05-05 11:02:49'),
(42, 155, 180, 450, '4', 445, 445, '', '2020-05-05 11:05:56'),
(43, 157, 180, 450, '3', 344, 344, '', '2020-05-05 11:09:27'),
(44, 158, 180, 450, '3', 3, 3, '', '2020-05-05 11:10:36'),
(45, 159, 180, 450, '3', 344, 345, '', '2020-05-05 11:12:17'),
(46, 160, 180, 450, '3', 3, 34, '', '2020-05-05 11:12:59'),
(47, 160, 189, 250, '3', 430, 340, '', '2020-05-05 11:14:38'),
(48, 160, 190, 125.66, '4', 200, 200, '', '2020-05-05 13:55:20'),
(49, 160, 189, 250, '12', 140, 130, '', '2020-05-05 13:56:00'),
(50, 173, 187, 15470, '2', 0, 0, '', '2020-05-05 14:57:56'),
(51, 173, 187, 15470, '2', 0, 0, '', '2020-05-05 14:58:02'),
(52, 174, 180, 450, '5', 200, 200, '', '2020-05-05 15:31:14'),
(53, 175, 180, 450, '3', 344, 344, '', '2020-05-05 15:32:53'),
(54, 175, 180, 450, '3', 344, 344, '', '2020-05-05 15:36:46'),
(55, 175, 180, 450, '3', 344, 344, '', '2020-05-05 15:37:15'),
(56, 175, 180, 450, '3', 344, 344, '', '2020-05-05 15:37:15'),
(57, 177, 180, 450, '3', 200, 200, '', '2020-05-05 15:42:20'),
(58, 178, 180, 450, '34', 200, 200, '', '2020-05-05 15:59:54'),
(59, 179, 180, 450, '23', 200, 200, '', '2020-05-05 16:04:58'),
(60, 180, 180, 450, '2', 300, 300, '', '2020-05-05 16:06:46'),
(61, 181, 187, 15470, '2', 300, 300, '', '2020-05-05 16:09:09'),
(62, 182, 187, 15470, '23', 200, 200, '', '2020-05-05 16:11:04'),
(63, 186, 187, 15470, '23', 20, 200, '', '2020-05-05 16:58:11'),
(64, 186, 180, 450, '2', 450, 450, '', '2020-05-05 16:58:31'),
(65, 187, 180, 450, '2', 250, 250, '', '2020-05-05 17:10:11'),
(66, 187, 187, 15470, '3', 350, 350, '', '2020-05-05 17:12:18'),
(67, 188, 187, 15470, '2', 250, 250, '', '2020-05-05 17:13:29'),
(68, 189, 187, 15470, '1', 240, 240, '', '2020-05-05 17:15:41'),
(69, 190, 187, 15470, '2', 450, 450, '', '2020-05-05 17:19:11'),
(70, 191, 187, 15470, '2', 540, 540, '', '2020-05-05 17:20:59'),
(71, 192, 187, 15470, '2', 340, 340, '', '2020-05-05 17:25:05'),
(72, 193, 187, 15470, '2', 230, 340, '', '2020-05-05 17:28:30'),
(73, 194, 187, 15470, '2', 230, 230, '', '2020-05-05 17:32:48'),
(74, 195, 187, 15470, '2', 230, 230, '', '2020-05-05 17:33:54'),
(75, 201, 180, 450, '3', 3434, 344, '', '2020-05-05 17:52:19'),
(76, 203, 187, 15470, '2', 233, 233, '', '2020-05-05 18:02:41');

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

--
-- Extraindo dados da tabela `historicoalugueis`
--

INSERT INTO `historicoalugueis` (`idHistoricoAluguel`, `contrato_idContrato`, `produto_idProduto`, `status`, `vlAluguel`, `dtInicio`, `dtFinal`, `custoEntrega`, `custoRetirada`, `observacao`, `dtCadastro`) VALUES
(17, 95, 180, 1, 450, '0000-00-00', '0000-00-00', 0, 0, '', '2020-05-02 15:27:43');

-- --------------------------------------------------------

--
-- Estrutura da tabela `obras`
--

CREATE TABLE `obras` (
  `idObra` int(11) NOT NULL,
  `id_fk_cliente` int(11) NOT NULL,
  `id_fk_resp` int(11) NOT NULL,
  `codObra` int(11) NOT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  `tipoEndereco` varchar(45) NOT NULL,
  `cidade` varchar(15) NOT NULL,
  `bairro` varchar(20) NOT NULL,
  `numero` int(11) NOT NULL,
  `uf` char(2) NOT NULL,
  `cep` varchar(45) NOT NULL,
  `endereco` varchar(45) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `obras`
--

INSERT INTO `obras` (`idObra`, `id_fk_cliente`, `id_fk_resp`, `codObra`, `complemento`, `tipoEndereco`, `cidade`, `bairro`, `numero`, `uf`, `cep`, `endereco`, `dtCadastro`) VALUES
(1, 34, 3, 1, NULL, 'teste', 'Araras', 'Centro', 135, 'SP', '13600-000', 'Rua x', '2020-05-02 10:21:34');

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
  `periodoLocacao` varchar(15) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `prod_categorias`
--

INSERT INTO `prod_categorias` (`idCategoria`, `descCategoria`, `codCategoria`, `periodoLocacao`, `dtCadastro`) VALUES
(1, 'Container', '001', 'mensal', '2020-03-03 23:01:57'),
(2, 'Betoneira', '002', 'quinzenal', '2020-03-05 00:17:03'),
(3, 'Andaime', '003', '', '2020-03-05 00:17:03'),
(4, 'Escora metálica', '004', '', '2020-03-09 11:36:59');

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
  `id_fk_cliente` int(11) NOT NULL,
  `respObra` varchar(45) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `telefone3` varchar(15) DEFAULT NULL,
  `email1` varchar(45) DEFAULT NULL,
  `email2` varchar(45) DEFAULT NULL,
  `anotacoes` varchar(150) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `resp_obras`
--

INSERT INTO `resp_obras` (`idResp`, `id_fk_cliente`, `respObra`, `telefone1`, `telefone2`, `telefone3`, `email1`, `email2`, `anotacoes`, `dtCadastro`) VALUES
(3, 34, 'João', '8403804324', '43543543534', '980304250495', 'joao_exemplo@hotmail.com', '', '', '2020-04-16 07:28:56'),
(5, 34, 'Felipe', '(19) 8464-75345', '(19) 9844-75865', '(19) 8756-53453', 'felipe_exemplo@gmail.com', 'felipe_exemplo2@gmail.com', '', '2020-04-25 11:29:50'),
(7, 34, 'Danilo', '(19) 7483-84865', '', '', 'danilo_exemplo@gamil.com', '', '', '2020-04-25 11:42:39'),
(10, 35, 'Eduardo', '(19) 9832-73727', '', '', 'eduardo_exemplo@gmail.com', '', '', '2020-04-25 12:48:41'),
(12, 35, 'Antônio da Silva', '(19) 7737-38383', '', '', 'antonio_exemplo@hotmail.com', 'antonio_exemplo2@hotmail.com', 'Este é um texto de exemplo alterado', '2020-04-25 13:35:14'),
(13, 33, 'Luiz Antônio', '(11) 3445-6789', '(19) 9788-86888', '', 'luiz_antonio@gmail.com', '', '', '2020-04-25 13:38:11');

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
-- Índices para tabela `contrato_itens`
--
ALTER TABLE `contrato_itens`
  ADD PRIMARY KEY (`idItem`),
  ADD KEY `fk_contrato_has_produto_contrato2` (`idContrato`),
  ADD KEY `fk_contrato_has_produto_produto2` (`idProduto_gen`);

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
  ADD KEY `id_fk_cliente` (`id_fk_cliente`),
  ADD KEY `id_fk_resp` (`id_fk_resp`) USING BTREE;

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
  MODIFY `idContrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT de tabela `contrato_itens`
--
ALTER TABLE `contrato_itens`
  MODIFY `idItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

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
  MODIFY `idHistoricoAluguel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `obras`
--
ALTER TABLE `obras`
  MODIFY `idObra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Limitadores para a tabela `contrato_itens`
--
ALTER TABLE `contrato_itens`
  ADD CONSTRAINT `fk_contrato_has_produto_contrato` FOREIGN KEY (`idContrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contrato_has_produto_produto` FOREIGN KEY (`idProduto_gen`) REFERENCES `produtos_gen` (`idProduto_gen`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
