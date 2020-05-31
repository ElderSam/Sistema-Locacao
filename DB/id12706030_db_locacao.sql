create database if not exists id12706030_db_locacao;
use id12706030_db_locacao;
-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2020 at 05:18 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id12706030_db_locacao`
--

DELIMITER $$
--
-- Procedures
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contratosUpdate_save` (IN `pidContrato` INT, IN `pcodContrato` VARCHAR(12), IN `pnomeEmpresa` VARCHAR(50), IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `psolicitante` VARCHAR(50), IN `ptelefone` VARCHAR(15), IN `pemail` VARCHAR(40), IN `pdtAprovacao` DATETIME, IN `pnotas` VARCHAR(100), IN `pvalorTotal` FLOAT, IN `pdtInicio` DATETIME, IN `pprazoDuracao` VARCHAR(40), IN `pstatusOrcamento` TINYINT(4))  BEGIN
  
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
      nomeEmpresa = pnomeEmpresa,
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contratos_save` (IN `pcodContrato` VARCHAR(12), IN `pnomeEmpresa` VARCHAR(50), IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `psolicitante` VARCHAR(50), IN `ptelefone` VARCHAR(15), IN `pemail` VARCHAR(40), IN `pdtAprovacao` DATETIME, IN `pnotas` VARCHAR(100), IN `pvalorTotal` FLOAT, IN `pdtInicio` DATETIME, IN `pprazoDuracao` VARCHAR(40), IN `pstatusOrcamento` TINYINT(4))  BEGIN
  
    DECLARE vidContrato INT;
    
  INSERT INTO contratos (codContrato, nomeEmpresa, obra_idObra, dtEmissao, solicitante, telefone, email, dtAprovacao, notas, valorTotal, dtInicio, prazoDuracao, statusOrcamento)
    VALUES(pcodContrato, pnomeEmpresa, pobra_idObra, pdtEmissao, psolicitante, ptelefone, pemail, pdtAprovacao, pnotas, pvalorTotal, pdtInicio, pprazoDuracao, pstatusOrcamento);
    
    SET vidContrato = LAST_INSERT_ID();
    
    SELECT * FROM contratos WHERE idContrato = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contrato_itensUpdate_save` (IN `pidItem` INT(11), IN `pidContrato` INT(11), IN `pidProduto_gen` INT(11), IN `pvlAluguel` FLOAT, IN `pquantidade` VARCHAR(4), IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pperiodoLocacao` VARCHAR(15), IN `pobservacao` TEXT)  BEGIN
  
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
      periodoLocacao = pperiodoLocacao,
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contrato_itens_save` (IN `pidContrato` INT(11), IN `pidProduto_gen` INT(11), IN `pvlAluguel` FLOAT, IN `pquantidade` VARCHAR(4), IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pperiodoLocacao` VARCHAR(15), IN `pobservacao` TEXT)  BEGIN
  
    DECLARE vidItem INT;
    
  INSERT INTO contrato_itens (idContrato, idProduto_gen, vlAluguel, quantidade, custoEntrega, custoRetirada, periodoLocacao, observacao)
    VALUES(pidContrato, pidProduto_gen, pvlAluguel, pquantidade, pcustoEntrega, pcustoRetirada, pperiodoLocacao, pobservacao);
    
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


CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obrasUpdate_save` (IN `pidObra` INT(11), IN `pcodObra` INT(11), IN `pcomplemento` VARCHAR(150), IN `pcidade` VARCHAR(15), IN `pbairro` VARCHAR(20), IN `pnumero` INT(11), IN `puf` CHAR(2), IN `pcep` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pid_fk_cliente` INT(11), IN `pid_fk_respObra` INT(11))  BEGIN
  
    DECLARE vidObra INT;
    
    SELECT idObra INTO vidObra
    FROM obras
    WHERE idObra = pidObra;

    UPDATE obras
    SET
        codObra = pcodObra, 
        complemento = pcomplemento,
        cidade = pcidade,
        bairro = pbairro,
        numero = pnumero,
        uf = puf,
        cep = pcep,
        endereco = pendereco,
        id_fk_cliente = pid_fk_cliente,
        id_fk_respObra = pid_fk_respObra
    WHERE idObra = vidObra;
    
    SELECT * FROM obras WHERE idObra = pidObra;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obras_delete` (IN `pidObra` INT(11))  BEGIN
  
    DECLARE vidObra INT;
    
  SELECT vidObra INTO vidObra
    FROM obras
    WHERE idObra = pidObra;
    
    DELETE FROM obras WHERE idObra = pidObra;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obras_save` (IN `pcodObra` INT(11), IN `pcomplemento` VARCHAR(150), IN `pcidade` VARCHAR(15), IN `pbairro` VARCHAR(20), IN `pnumero` INT(11), IN `puf` CHAR(2), IN `pcep` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pid_fk_cliente` INT(11), IN `pid_fk_respObra` INT(11))  BEGIN
DECLARE vidObra INT;
    
  INSERT INTO obras (codObra, complemento, cidade, bairro , numero, uf, cep, endereco, id_fk_cliente, id_fk_respObra)
    VALUES(pcodObra, pcomplemento, pcidade, pbairro, pnumero, puf, pcep, pendereco, pid_fk_cliente, pid_fk_respObra);
    
    SET vidObra = LAST_INSERT_ID();
    
   SELECT * FROM obras WHERE idObra = LAST_INSERT_ID();
    
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
-- Table structure for table `aditamentos`
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
-- Table structure for table `clientes`
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
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`idCliente`, `nome`, `status`, `telefone1`, `telefone2`, `email1`, `email2`, `endereco`, `complemento`, `cidade`, `bairro`, `numero`, `uf`, `cep`, `cpf`, `rg`, `cnpj`, `ie`, `tipoCliente`, `dtCadastro`) VALUES
(1, 'Construtora Guilhermina', 1, '(19) 5454-54545', '(54) 5454-54545', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', 'Rua Jorge Salibe Sobrinho', '', 'Limeira', 'Parque das Nações', 454, 'BA', '13481-659', '', '', '53.252.352/5252-55', '423432423523532', 'J', '2020-04-04 19:07:05'),
(2, 'Construtora do Matheus', 1, '(19) 9953-13563', '', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', 'Dr. Arlindo Justos Baptistella', 'sdffsfsfdsfdsfsgsghrjjnffjf', 'Limeira', 'Jardim Botânicokjs', 424, 'AM', '13481-659', '', '', '86.867.867/8868-86', '425454543453', 'J', '2020-04-04 21:33:41'),
(3, 'Construtela', 1, '(19) 9438-74384', '(19) 5379-8537', 'construtela_exemplo@gmail.com', 'douglas.rnmeriano@gmail.com', 'Rua Thereza de Oliveira Lima ', 'Este é um exemplo de cliente', 'Piracicaba', 'Campos do Conde', 232, 'RN', '31737-361', '', '', '09.804.980/2804-34', '3283748983248', 'J', '2020-04-25 08:08:03'),
(4, 'Construtora Teste', 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', 'J', '2020-04-25 17:40:02'),
(5, 'CONSTRUTOR TESTE', 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', 'J', '2020-04-25 20:56:30'),
(6, 'CLIENTE TESET', 0, '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', 'J', '2020-04-25 20:56:50');

-- --------------------------------------------------------

--
-- Table structure for table `contratos`
--

CREATE TABLE `contratos` (
  `idContrato` int(11) NOT NULL,
  `codContrato` varchar(12) NOT NULL,
  `nomeEmpresa` varchar(50) DEFAULT NULL,
  `obra_idObra` int(11) DEFAULT NULL,
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
-- Dumping data for table `contratos`
--

INSERT INTO `contratos` (`idContrato`, `codContrato`, `nomeEmpresa`, `obra_idObra`, `dtEmissao`, `solicitante`, `telefone`, `email`, `dtAprovacao`, `dtInicio`, `prazoDuracao`, `statusOrcamento`, `valorTotal`, `notas`, `dtCadastro`) VALUES
(1, '16', 'TESTE_ATUALIZAR', 1, '2020-02-20', 'teste_ATUALIZAR', '19000000000', 'teste@atualizar.com', '2020-05-25', '2020-05-25', '12 meses', 1, NULL, 'teste atualizar', '2020-05-24 08:26:46'),
(2, '17', '', NULL, '2020-05-24', 'TESTE', '', '', NULL, NULL, NULL, 5, NULL, '', '2020-05-24 08:26:52'),
(3, '19', '', NULL, '2020-05-24', 'TESTE', '', '', NULL, NULL, NULL, 2, NULL, '', '2020-05-24 08:27:46'),
(4, '20', '', NULL, '2020-12-31', 'TESTE2', '', '', NULL, NULL, NULL, 3, NULL, '', '2020-05-24 08:44:10'),
(5, '21', '', NULL, '2020-05-20', 'TESTSE3', '', '', NULL, NULL, NULL, 4, NULL, '', '2020-05-24 08:45:42'),
(6, '22', 'Construtora Forte', NULL, '2020-05-30', 'Rodrigo Souza', '3235413242', 'rodrigo@construforte.com', '2020-05-30', '0000-00-00', '', 1, NULL, 'teste', '2020-05-25 08:16:14'),
(7, '23', '', NULL, '2020-02-05', 'elder', '32423243242', 'e@gmail.com', '2020-05-25', NULL, NULL, 1, NULL, 'testee', '2020-05-25 08:16:16'),
(8, '24', '', 1, '2020-01-01', 'JOÃO', '', '', '2020-05-25', NULL, NULL, 1, NULL, 'TESTE', '2020-05-25 09:09:32'),
(9, '25', '', 1, '2020-12-31', 'Douglas', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-25 09:58:46'),
(10, '26', '', 1, '2020-12-31', 'Douglas', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-25 10:03:53'),
(11, '28', '', 1, '2020-05-25', 'elder', '', '', NULL, '0000-00-00', '', 0, NULL, '', '2020-05-25 10:43:49'),
(12, '29', '', NULL, '2020-05-31', 'elder', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-31 09:30:29'),
(13, '30', '', NULL, '2020-12-31', 'Douglas', '', '', NULL, NULL, NULL, 0, NULL, '', '2020-05-31 09:31:28');

-- --------------------------------------------------------

--
-- Table structure for table `contrato_itens`
--

CREATE TABLE `contrato_itens` (
  `idItem` int(11) NOT NULL,
  `idContrato` int(11) NOT NULL,
  `idProduto_gen` int(11) NOT NULL,
  `vlAluguel` float NOT NULL,
  `quantidade` varchar(4) NOT NULL,
  `custoEntrega` float DEFAULT NULL,
  `custoRetirada` float DEFAULT NULL,
  `periodoLocacao` varchar(15) NOT NULL,
  `observacao` text DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contrato_itens`
--

INSERT INTO `contrato_itens` (`idItem`, `idContrato`, `idProduto_gen`, `vlAluguel`, `quantidade`, `custoEntrega`, `custoRetirada`, `periodoLocacao`, `observacao`, `dtCadastro`) VALUES
(1, 3, 192, 600, '2', 200, 200, '', '', '2020-05-24 08:30:02'),
(2, 4, 191, 200, '2', 25, 25, '', '', '2020-05-24 08:44:47'),
(3, 5, 191, 200, '2', 25, 25, '', '', '2020-05-24 08:46:03'),
(4, 1, 180, 450, '2', 200, 200, '', 'Entrega em 15DD úteis', '2020-05-24 22:32:55'),
(5, 7, 191, 200, '12', 150, 120, '2', 'Entrega em 5DD úteis', '2020-05-25 08:17:37'),
(6, 1, 195, 500, '2', 170, 170, '', 'Entrega em 5DD úteis', '2020-05-25 08:35:47'),
(7, 1, 190, 125.66, '15', 234.54, 210, '1', 'Entrega em 3DD', '2020-05-25 08:57:41'),
(8, 1, 181, 234, '2', 199, 199, '4', '', '2020-05-25 09:01:09'),
(9, 1, 187, 15470, '5', 253.98, 225.37, '3', 'Entrega em 10DD', '2020-05-25 09:07:43'),
(10, 10, 193, 600, '1', 150, 150, '4', 'entrega em 4DD', '2020-05-25 10:45:31'),
(11, 8, 180, 1523.56, '7', 231.06, 214.91, '3', 'Entrega em 5DD úteis', '2020-05-25 16:00:08'),
(12, 9, 180, 450, '2', 230, 230, '4', '', '2020-05-29 00:28:11'),
(13, 7, 193, 600, '2', 200, 190, '4', 'Entrega em 5DD úteis', '2020-05-30 09:53:34'),
(14, 6, 180, 450.34, '3', 200.73, 185.98, '4', 'Entrega em Piracicaba-SP (em 5DD úteis)', '2020-05-30 18:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `faturas`
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
-- Table structure for table `fornecedores`
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
-- Dumping data for table `fornecedores`
--

INSERT INTO `fornecedores` (`idFornecedor`, `codFornecedor`, `nome`, `cnpj`, `telefone1`, `telefone2`, `email1`, `email2`, `endereco`, `numero`, `bairro`, `cidade`, `complemento`, `uf`, `cep`, `status`, `dtCadastro`) VALUES
(1, '001', 'Fornecedor X', '12654345345346', '(19) 9999-9999', '(99) 99999-9999', '', '', 'Rua x', 344, 'jd. do filtro', 'Araras', '', 'SP', '09', 1, '2020-03-03 22:09:00'),
(2, '002', 'Cargueiro Fornecedor de Containers', NULL, '11 99992344', '1255550000', 'cargueiro@grupo.com', 'adm@grupo.com', 'Av. do Louvre', 1009, 'Vila Litoral', 'Santos', '', 'SP', '10', 1, NULL),
(4, '003', 'Douglas Numeriano', NULL, '19 99999999', '19 99999999', 'douglas@gmail.com', '', 'Rua Maria Aparecida', 76, 'jd. das Flores', 'Limeira', 'prédio', 'SP', '13', 1, '2020-03-30 15:29:33'),
(5, '004', 'Fornecedor YXZ', '23423423423432', '1975363234', '19453656453', 'adm@bergamus.com', 'fincancas@bergamus.com', 'Avenida Brasil', 340, 'Centro', 'São Paulo', 'Barracão', 'SP', '10', 1, '2020-03-30 17:38:44');

-- --------------------------------------------------------

--
-- Table structure for table `historicoalugueis`
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
-- Table structure for table `obras`
--

CREATE TABLE `obras` (
  `idObra` int(11) NOT NULL,
  `codObra` int(11) NOT NULL,
  `complemento` varchar(150) DEFAULT NULL,
  `cidade` varchar(15) DEFAULT NULL,
  `bairro` varchar(20) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `uf` char(2) DEFAULT NULL,
  `cep` varchar(45) DEFAULT NULL,
  `endereco` varchar(45) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp(),
  `id_fk_cliente` int(11) NOT NULL,
  `id_fk_respObra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `obras`
--

INSERT INTO `obras` (`idObra`, `codObra`, `complemento`, `cidade`, `bairro`, `numero`, `uf`, `cep`, `endereco`, `dtCadastro`, `id_fk_cliente`, `id_fk_respObra`) VALUES
(1, 1, 'teste', 'Campinas', 'JD. Nova Limeira', 213, 'PA', '3213123', 'Av. Dom Pedro II', '2020-05-29 20:06:04', 39, 18),
(12, 2, '8', 'Miranguaba', '9798', 7, 'PB', '13.481-043', 'Rua Jorge Salibe Sobrinho', '2020-05-30 21:38:51', 39, 20),
(13, 3, 'casa', 'Araras', 'Centro', 3454, 'SP', '13600000', 'Av. Melvin jOnes', '2020-05-30 21:39:26', 39, 18),
(15, 1, 'fsfsd', 'Limeira', 'fsdfd', 212, 'CE', '13481659', 'Dr. Arlindo Justos Baptistella', '2020-05-31 13:38:27', 41, 19);

-- --------------------------------------------------------

--
-- Table structure for table `produtos_esp`
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
-- Dumping data for table `produtos_esp`
--

INSERT INTO `produtos_esp` (`idProduto_esp`, `idProduto_gen`, `codigoEsp`, `valorCompra`, `status`, `dtFabricacao`, `numSerie`, `anotacoes`, `idFornecedor`, `dtCadastro`) VALUES
(1, 1, '001.01.01.01.01.002-0001', 12500, 1, '2020-04-22', '0001', '', 2, '2020-04-22 09:49:52'),
(2, 1, '001.01.01.01.01.002-0002', 10000, 1, '2020-04-22', '0002', 'cadastro teste', 2, '2020-04-22 09:51:18'),
(3, 1, '001.01.01.01.01.002-0003', 13899.8, 1, '2020-04-22', '0003', 'teste cadastro', 2, '2020-04-22 09:57:53'),
(4, 2, '002.01.01.01.01.001-0001', 520.98, 1, '2020-04-22', '0001', 'cadastro teste', 1, '2020-04-22 10:45:04'),
(5, 2, '002.01.01.01.01.004-0002', 450.77, 1, '2020-04-22', '0002', 'cadastro teste', 5, '2020-04-22 10:47:10'),
(6, 2, '002.01.01.01.01.002-0003', 459.89, 1, '2020-04-22', '0003', 'cadastro teste', 2, '2020-04-22 10:49:38'),
(7, 2, '002.01.01.01.01.002-0004', 345.76, 1, '2020-04-22', '0004', 'CADASTRO TESTE', 2, '2020-04-22 10:50:40'),
(8, 1, '001.01.01.01.01.002-0004', 15477.2, 1, '2011-04-12', '0004', '', 2, '2020-04-22 11:47:06'),
(9, 3, '004.03.xx.xx.xx.002-xxxx', 500, 1, '2010-03-15', NULL, 'cadastro teste', 2, '2020-04-22 15:15:35'),
(10, 4, '005.02.01.02.xx.003-0001', 2520.02, 1, '2020-12-05', '0001', '', 4, '2020-05-23 22:02:04'),
(11, 3, '004.03.xx.xx.xx.002-xxxx', 530, 1, '2019-12-31', NULL, '', 2, '2020-05-23 22:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `produtos_gen`
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
-- Dumping data for table `produtos_gen`
--

INSERT INTO `produtos_gen` (`idProduto_gen`, `codigoGen`, `descricao`, `idCategoria`, `tipo1`, `tipo2`, `tipo3`, `tipo4`, `vlBaseAluguel`, `dtCadastro`) VALUES
(1, '001.01.01.01.01', '3M almoxarifado com lavabo DC', 1, 1, 5, 12, 14, 450, '2020-04-20 19:25:32'),
(2, '002.01.01.01.01', 'MARCA X MODELO X Elétrica 110V', 2, 16, 17, 18, 21, 234, '2020-04-20 19:54:06'),
(5, '001.04.05.02.02', '12M stand de vendas sem lavabo HC', 1, 4, 9, 13, 15, 15470, '2020-04-22 14:22:48'),
(6, '004.05.xx.xx.xx', '3,00m a 5,10m', 4, 52, NULL, NULL, NULL, 250, '2020-04-22 15:07:22'),
(3, '004.03.xx.xx.xx', '2,30m a 4,00m', 4, 50, NULL, NULL, NULL, 125.66, '2020-04-22 15:14:37'),
(7, '003.01.01.01.xx', 'Tubular Painel 1,00m', 3, 30, 33, 45, NULL, 200, '2020-04-22 15:17:37'),
(8, '001.03.01.02.01', '6M almoxarifado sem lavabo DC', 1, 3, 5, 13, 14, 600, '2020-05-23 20:58:53'),
(9, '001.03.03.xx.01', '6M sanitário DC', 1, 3, 7, NULL, 14, 600, '2020-05-23 21:03:20'),
(4, '005.02.01.02.xx', 'Janela 10.000 btu 220V', 5, 54, 55, 59, NULL, 500, '2020-05-23 21:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `prod_categorias`
--

CREATE TABLE `prod_categorias` (
  `idCategoria` int(4) NOT NULL,
  `descCategoria` varchar(15) NOT NULL,
  `codCategoria` varchar(3) NOT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prod_categorias`
--

INSERT INTO `prod_categorias` (`idCategoria`, `descCategoria`, `codCategoria`, `dtCadastro`) VALUES
(1, 'Container', '001', '2020-03-03 23:01:57'),
(2, 'Betoneira', '002', '2020-03-05 00:17:03'),
(3, 'Andaime', '003', '2020-03-05 00:17:03'),
(4, 'Escora metálica', '004', '2020-03-09 11:36:59'),
(5, 'Ar-condicionado', '005', '2020-05-23 21:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `prod_containers`
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
-- Dumping data for table `prod_containers`
--

INSERT INTO `prod_containers` (`idContainer`, `idProduto`, `tipoPorta`, `janelasLat`, `janelasCirc`, `forrado`, `eletrificado`, `tomadas`, `lampadas`, `entradasAC`, `sanitarios`, `chuveiro`, `dtCadastro`) VALUES
(1, 1, 'Marítima', 1, 0, 1, 1, 1, 1, 1, 0, 0, '2020-04-22 09:49:52'),
(2, 2, 'Marítma', 2, 0, 1, 1, 2, 2, 1, 0, 0, '2020-04-22 09:51:18'),
(3, 3, 'Marítma', 1, 0, 1, 0, 1, 1, 1, 0, 0, '2020-04-22 09:57:53'),
(4, 8, 'Marítima', 0, 0, 1, 1, 1, 2, 1, 1, 0, '2020-04-22 11:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `prod_tipos`
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
-- Dumping data for table `prod_tipos`
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
(52, '3,00m a 5,10m', 4, 1, '05', '2020-03-16 22:06:45'),
(53, 'Split', 5, 1, '01', '2020-05-23 21:32:57'),
(54, 'Janela', 5, 1, '02', '2020-05-23 21:33:52'),
(55, '10.000 btu', 5, 2, '01', '2020-05-23 21:35:33'),
(56, '12.000 btu', 5, 2, '02', '2020-05-23 21:35:34'),
(57, '15.000 btu', 5, 2, '03', '2020-05-23 21:35:34'),
(58, '110V', 5, 3, '01', '2020-05-23 21:35:34'),
(59, '220V', 5, 3, '02', '2020-05-23 21:35:34');

-- --------------------------------------------------------

--
-- Table structure for table `resp_obras`
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
-- Dumping data for table `resp_obras`
--

INSERT INTO `resp_obras` (`idResp`, `id_fk_cliente`, `respObra`, `telefone1`, `telefone2`, `telefone3`, `email1`, `email2`, `anotacoes`, `dtCadastro`) VALUES
(1, 1, 'João', '8403804324', '43543543534', '980304250495', 'joao_exemplo@hotmail.com', '', '', '2020-04-16 07:28:56'),
(2, 1, 'Felipe', '(19) 8464-75345', '(19) 9844-75865', '(19) 8756-53453', 'felipe_exemplo@gmail.com', 'felipe_exemplo2@gmail.com', '', '2020-04-25 11:29:50'),
(3, 1, 'Danilo', '(19) 7483-84865', '', '', 'danilo_exemplo@gamil.com', '', '', '2020-04-25 11:42:39'),
(4, 2, 'Eduardo', '(19) 9832-73727', '', '', 'eduardo_exemplo@gmail.com', '', '', '2020-04-25 12:48:41'),
(5, 2, 'Antônio da Silva', '(19) 7737-38383', '', '', 'antonio_exemplo@hotmail.com', 'antonio_exemplo2@hotmail.com', 'Este é um texto de exemplo alterado', '2020-04-25 13:35:14'),
(6, 2, 'Luiz Antônio', '(11) 3445-6789', '(19) 9788-86888', '', 'luiz_antonio@gmail.com', '', '', '2020-04-25 13:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
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
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nomeCompleto`, `funcao`, `nomeUsuario`, `senha`, `email`, `administrador`, `foto`, `dtCadastro`) VALUES
(1, 'Elder Samuel', 'Programador', 'elder', '$2y$12$6UBTMz.ZC3ZEf8ytouE5ReApu0tjrDPOjmb7/vY5ooh0coVFXHMPS', 'eldersamuel98@gmail.com', 1, '/res/img/users/1583106585_elder-profile.jpg', '2020-02-26 10:45:06'),
(2, 'Administrador', 'Teste', 'admin', '$2y$12$VN9ODzeRl2lKLhE84XmWF.lf5UbP9gfWFtEa7f1jEuyaeV9ILIhz6', 'eldersamuel98@gmail.com', 1, '/res/img/users/user-default.jpg', '2020-02-29 22:45:00'),
(3, 'Matheus Leite de Campos', 'Product Owner', 'matheus', '$2y$12$HgGxPtV/zZhse52m9Dc6HuE8bUiXeFWCW66AtdiUW2OB537qmhmrO', 'matheus@gmail.com', 1, '/res/img/users/user-default.jpg', '2020-03-05 17:22:07'),
(4, 'teste', 'teste', 'teste', '$2y$12$7sxu7KZZ5tNXyWgBlekeSudyonnOaUbkvcd8jRj.p2P1NPqRqqBx.', 'teste@teste.com', 0, '/res/img/users/user-default.jpg', '2020-03-30 09:50:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aditamentos`
--
ALTER TABLE `aditamentos`
  ADD PRIMARY KEY (`idAditamento`),
  ADD KEY `fk_aditamento_contrato1` (`contrato_idContrato`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCliente`);

--
-- Indexes for table `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`idContrato`),
  ADD KEY `fk_contrato_obra1` (`obra_idObra`);

--
-- Indexes for table `contrato_itens`
--
ALTER TABLE `contrato_itens`
  ADD PRIMARY KEY (`idItem`),
  ADD KEY `fk_contrato_has_produto_contrato2` (`idContrato`),
  ADD KEY `fk_contrato_has_produto_produto2` (`idProduto_gen`);

--
-- Indexes for table `faturas`
--
ALTER TABLE `faturas`
  ADD PRIMARY KEY (`idFatura`),
  ADD KEY `fk_fatura_contrato1` (`contrato_idcontrato`);

--
-- Indexes for table `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`idFornecedor`);

--
-- Indexes for table `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  ADD PRIMARY KEY (`idHistoricoAluguel`),
  ADD KEY `fk_contrato_has_produto_contrato2` (`contrato_idContrato`),
  ADD KEY `fk_contrato_has_produto_produto2` (`produto_idProduto`);

--
-- Indexes for table `obras`
--
ALTER TABLE `obras`
  ADD PRIMARY KEY (`idObra`),
  ADD KEY `obras_ibfk_1` (`id_fk_cliente`),
  ADD KEY `obras_ibfk_2` (`id_fk_respObra`);

--
-- Indexes for table `produtos_esp`
--
ALTER TABLE `produtos_esp`
  ADD PRIMARY KEY (`idProduto_esp`),
  ADD KEY `fk_fornecedor` (`idFornecedor`),
  ADD KEY `fk_produtos_esp` (`idProduto_gen`);

--
-- Indexes for table `produtos_gen`
--
ALTER TABLE `produtos_gen`
  ADD PRIMARY KEY (`idProduto_gen`),
  ADD KEY `fk_produto_categoria1` (`idCategoria`),
  ADD KEY `fk_produto_tipo1` (`tipo1`),
  ADD KEY `fk_produto_tipo2` (`tipo2`),
  ADD KEY `fk_produto_tipo3` (`tipo3`),
  ADD KEY `fk_produto_tipo4` (`tipo4`);

--
-- Indexes for table `prod_categorias`
--
ALTER TABLE `prod_categorias`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indexes for table `prod_containers`
--
ALTER TABLE `prod_containers`
  ADD PRIMARY KEY (`idContainer`),
  ADD KEY `fk_produto_container1` (`idProduto`) USING BTREE;

--
-- Indexes for table `prod_tipos`
--
ALTER TABLE `prod_tipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idCategoria` (`idCategoria`) USING BTREE;

--
-- Indexes for table `resp_obras`
--
ALTER TABLE `resp_obras`
  ADD PRIMARY KEY (`idResp`),
  ADD KEY `id_fkcliente` (`id_fk_cliente`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aditamentos`
--
ALTER TABLE `aditamentos`
  MODIFY `idAditamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contratos`
--
ALTER TABLE `contratos`
  MODIFY `idContrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `contrato_itens`
--
ALTER TABLE `contrato_itens`
  MODIFY `idItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `faturas`
--
ALTER TABLE `faturas`
  MODIFY `idFatura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `idFornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  MODIFY `idHistoricoAluguel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `obras`
--
ALTER TABLE `obras`
  MODIFY `idObra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `produtos_esp`
--
ALTER TABLE `produtos_esp`
  MODIFY `idProduto_esp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `produtos_gen`
--
ALTER TABLE `produtos_gen`
  MODIFY `idProduto_gen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `prod_categorias`
--
ALTER TABLE `prod_categorias`
  MODIFY `idCategoria` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prod_containers`
--
ALTER TABLE `prod_containers`
  MODIFY `idContainer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prod_tipos`
--
ALTER TABLE `prod_tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `resp_obras`
--
ALTER TABLE `resp_obras`
  MODIFY `idResp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `fk_contrato_obra1` FOREIGN KEY (`obra_idObra`) REFERENCES `obras` (`idObra`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `contrato_itens`
--
ALTER TABLE `contrato_itens`
  ADD CONSTRAINT `fk_contrato_has_produto_contrato` FOREIGN KEY (`idContrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contrato_has_produto_produto` FOREIGN KEY (`idProduto_gen`) REFERENCES `produtos_gen` (`idProduto_gen`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `faturas`
--
ALTER TABLE `faturas`
  ADD CONSTRAINT `fk_fatura_contrato1` FOREIGN KEY (`contrato_idcontrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  ADD CONSTRAINT `fk_contrato_has_produto_contrato2` FOREIGN KEY (`contrato_idContrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contrato_has_produto_produto2` FOREIGN KEY (`produto_idProduto`) REFERENCES `produtos_gen` (`idProduto_gen`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `obras`
--
ALTER TABLE `obras`
  ADD CONSTRAINT `obras_ibfk_1` FOREIGN KEY (`id_fk_cliente`) REFERENCES `clientes` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `obras_ibfk_2` FOREIGN KEY (`id_fk_respObra`) REFERENCES `resp_obras` (`idResp`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `produtos_esp`
--
ALTER TABLE `produtos_esp`
  ADD CONSTRAINT `fk_fornecedor` FOREIGN KEY (`idFornecedor`) REFERENCES `fornecedores` (`idFornecedor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produtos_esp` FOREIGN KEY (`idProduto_gen`) REFERENCES `produtos_gen` (`idProduto_gen`);

--
-- Constraints for table `produtos_gen`
--
ALTER TABLE `produtos_gen`
  ADD CONSTRAINT `fk_produto_categoria1` FOREIGN KEY (`idCategoria`) REFERENCES `prod_categorias` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_tipo1` FOREIGN KEY (`tipo1`) REFERENCES `prod_tipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_tipo2` FOREIGN KEY (`tipo2`) REFERENCES `prod_tipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_tipo3` FOREIGN KEY (`tipo3`) REFERENCES `prod_tipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_tipo4` FOREIGN KEY (`tipo4`) REFERENCES `prod_tipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prod_containers`
--
ALTER TABLE `prod_containers`
  ADD CONSTRAINT `fk_produto_container1` FOREIGN KEY (`idProduto`) REFERENCES `produtos_esp` (`idProduto_esp`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prod_tipos`
--
ALTER TABLE `prod_tipos`
  ADD CONSTRAINT `fk_tiposprodutos_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `prod_categorias` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `resp_obras`
--
ALTER TABLE `resp_obras`
  ADD CONSTRAINT `id_fkcliente` FOREIGN KEY (`id_fk_cliente`) REFERENCES `clientes` (`idCliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
