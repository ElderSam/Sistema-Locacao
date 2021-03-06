-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14-Dez-2020 às 19:52
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `id12706030_db_locacao`
--
CREATE DATABASE IF NOT EXISTS `id12706030_db_locacao` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `id12706030_db_locacao`;

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_clientesUpdate_save` (IN `pidCliente` INT, IN `pcodigo` VARCHAR(3), IN `pnome` VARCHAR(45), IN `pstatus` TINYINT, IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pcomplemento` VARCHAR(150), IN `pcidade` VARCHAR(25), IN `pbairro` VARCHAR(25), IN `pnumero` INT(11), IN `puf` CHAR(2), IN `pcep` VARCHAR(45), IN `pcpf` VARCHAR(45), IN `prg` VARCHAR(45), IN `pcnpj` VARCHAR(45), IN `pie` VARCHAR(45), IN `ptipoCliente` VARCHAR(45))  BEGIN
  
    DECLARE vidCliente INT;
    
    SELECT idCliente INTO vidCliente
    FROM clientes
    WHERE idCliente = pidCliente;

    UPDATE clientes
    SET
        codigo = pcodigo,
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_clientes_save` (IN `pcodigo` VARCHAR(3), IN `pnome` VARCHAR(45), IN `pstatus` TINYINT, IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `pendereco` VARCHAR(45), IN `pcomplemento` VARCHAR(150), IN `pcidade` VARCHAR(25), IN `pbairro` VARCHAR(25), IN `pnumero` INT(11), IN `puf` CHAR(2), IN `pcep` VARCHAR(45), IN `pcpf` VARCHAR(45), IN `prg` VARCHAR(45), IN `pcnpj` VARCHAR(45), IN `pie` VARCHAR(45), IN `ptipoCliente` VARCHAR(45))  BEGIN

    DECLARE vidCliente INT;
    
  INSERT INTO clientes (codigo, nome, status, telefone1, telefone2, email1, email2, endereco, complemento, cidade, bairro, numero, uf, cep, cpf, rg, cnpj, ie, tipoCliente)
    VALUES(pcodigo, pnome, pstatus, ptelefone1, ptelefone2, pemail1, pemail2, pendereco, pcomplemento, pcidade, pbairro, pnumero, puf, pcep, pcpf, prg, pcnpj, pie, ptipoCliente);
    
    SET vidCliente = LAST_INSERT_ID();
    
    SELECT * FROM clientes WHERE idcliente = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contratosUpdate_save` (IN `pidContrato` INT, IN `pcodContrato` VARCHAR(12), IN `pnomeEmpresa` VARCHAR(50), IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `psolicitante` VARCHAR(50), IN `ptelefone` VARCHAR(15), IN `pemail` VARCHAR(40), IN `pdtAprovacao` DATETIME, IN `pnotas` VARCHAR(100), IN `pdtInicio` DATETIME, IN `pdtFim` DATE, IN `pstatusOrcamento` TINYINT(4), IN `ptemMedicao` TINYINT(1), IN `pregraFatura` INT(2), IN `psemanaDoMes` INT(1), IN `pdiaFatura` INT(2))  BEGIN
  
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
      dtInicio = pdtInicio,
      dtFim = pdtFim,
      statusOrcamento = pstatusOrcamento,
      codContrato = pcodContrato,
      nomeEmpresa = pnomeEmpresa,
      obra_idObra = pobra_idObra,
      temMedicao = ptemMedicao,
      regraFatura = pregraFatura,
      semanaDoMes = psemanaDoMes,
      diaFatura = pdiaFatura
    
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contratos_save` (IN `pcodContrato` VARCHAR(12), IN `pnomeEmpresa` VARCHAR(50), IN `pobra_idObra` INT, IN `pdtEmissao` DATETIME, IN `psolicitante` VARCHAR(50), IN `ptelefone` VARCHAR(15), IN `pemail` VARCHAR(40), IN `pdtAprovacao` DATETIME, IN `pnotas` VARCHAR(100), IN `pdtInicio` DATETIME, IN `pdtFim` DATE, IN `pstatusOrcamento` TINYINT(4), IN `ptemMedicao` TINYINT(1), IN `pregraFatura` INT(2), IN `psemanaDoMes` INT(1), IN `pdiaFatura` INT(2))  BEGIN
  
    DECLARE vidContrato INT;
    
  INSERT INTO contratos (codContrato, nomeEmpresa, obra_idObra, dtEmissao, solicitante, telefone, email, dtAprovacao, notas, dtInicio, dtFim, statusOrcamento, 
        temMedicao, regraFatura, semanaDoMes, diaFatura)
    VALUES(pcodContrato, pnomeEmpresa, pobra_idObra, pdtEmissao, psolicitante, ptelefone, pemail, pdtAprovacao, pnotas, pdtInicio, pdtFim, pstatusOrcamento, 
    ptemMedicao, pregraFatura, psemanaDoMes, pdiaFatura);
    
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_faturasUpdate_save` (IN `pidFatura` INT(11), IN `pidContrato` INT(11), IN `pnumFatura` VARCHAR(11), IN `pdtEmissao` DATE, IN `pdtInicio` DATE, IN `pdtFim` DATE, IN `penviarPorEmail` TINYINT(1), IN `pemailEnvio` VARCHAR(40), IN `pdtEnvio` DATE, IN `padicional` FLOAT, IN `pobservacoes` VARCHAR(100), IN `pformaPagamento` INT(1), IN `pdtVencimento` DATE, IN `pespecCobranca` VARCHAR(60), IN `pdtCobranca` DATE, IN `pstatusPagamento` INT(1), IN `pnumNF` INT(11), IN `pnumBoletoInt` INT(11), IN `pnumBoletoBanco` INT(11), IN `pvalorPago` FLOAT, IN `pdtPagamento` DATE, IN `pdtVerificacao` DATE)  BEGIN
  
    DECLARE vidFatura INT;

    SELECT idFatura INTO vidFatura
        FROM faturas
        WHERE idFatura = pidFatura;

    UPDATE faturas
        SET
            idContrato = pidContrato,
            numFatura = pnumFatura,
            dtEmissao = pdtEmissao,
            dtInicio = pdtInicio,
            dtFim = pdtFim,
            enviarPorEmail = penviarPorEmail,
            emailEnvio = pemailEnvio,
            dtEnvio = pdtEnvio,
            adicional = padicional,
            observacoes = pobservacoes

        WHERE idFatura = vidFatura;    

    UPDATE fatura_cobrancas
        SET
            idFatura = pidFatura,
            formaPagamento = pformaPagamento,
            dtVencimento = pdtVencimento,
            especCobranca = pespecCobranca,          
            dtCobranca = pdtCobranca,
            statusPagamento = pstatusPagamento, 
            numNF = pnumNF,
            numBoletoInt = pnumBoletoInt,
            numBoletoBanco = pnumBoletoBanco,
            valorPago = pvalorPago,
            dtPagamento = pdtPagamento,
            dtVerificacao = pdtVerificacao

        WHERE idFatura = vidFatura;    


    SELECT * FROM faturas a
    INNER JOIN fatura_cobrancas b ON(a.idFatura = b.idFatura)
    WHERE a.idFatura = pidFatura;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_faturas_delete` (IN `pidFatura` INT)  BEGIN
  
    DECLARE vidFatura INT;
    
  SELECT idFatura INTO vidFatura
    FROM faturas
    WHERE idFatura = pidFatura;
    
    DELETE FROM fatura_itens WHERE idFatura = pidFatura;
    DELETE FROM fatura_cobrancas WHERE idFatura = pidFatura;
    DELETE FROM faturas WHERE idFatura = pidFatura;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_faturas_save` (IN `pidContrato` INT(11), IN `pnumFatura` VARCHAR(11), IN `pdtEmissao` DATE, IN `pdtInicio` DATE, IN `pdtFim` DATE, IN `penviarPorEmail` TINYINT(1), IN `pemailEnvio` VARCHAR(40), IN `pdtEnvio` DATE, IN `padicional` FLOAT, IN `pobservacoes` VARCHAR(100), IN `pformaPagamento` INT(1), IN `pdtVencimento` DATE, IN `pespecCobranca` VARCHAR(60), IN `pdtCobranca` DATE, IN `pstatusPagamento` INT(1), IN `pnumNF` INT(11), IN `pnumBoletoInt` INT(11), IN `pnumBoletoBanco` INT(11), IN `pvalorPago` FLOAT, IN `pdtPagamento` DATE, IN `pdtVerificacao` DATE)  BEGIN
    
        DECLARE vidFatura, vidCobranca INT;

    INSERT INTO faturas (
        idContrato,
        numFatura,
        dtEmissao,
        dtInicio,
        dtFim,
        enviarPorEmail,
        emailEnvio,
        dtEnvio,
        adicional,
        observacoes
    )
    VALUES(
        pidContrato,
        pnumFatura,
        pdtEmissao,
        pdtInicio,
        pdtFim,
        penviarPorEmail,
        pemailEnvio,
        pdtEnvio,
        padicional,
        pobservacoes
    );

    SET vidFatura = LAST_INSERT_ID();



    INSERT INTO fatura_cobrancas (
        idFatura,
        formaPagamento,
        dtVencimento,
        especCobranca,       
        dtCobranca,
        statusPagamento,     
        numNF,
        numBoletoInt,
        numBoletoBanco,
        valorPago,
        dtPagamento,
        dtVerificacao
    )
    VALUES(
        vidFatura,
        pformaPagamento,
        pdtVencimento,
        pespecCobranca, 
        pdtCobranca,
        pstatusPagamento,
        pnumNF,
        pnumBoletoInt,
        pnumBoletoBanco,
        pvalorPago,
        pdtPagamento,
        pdtVerificacao
    );
    
    SET vidCobranca = LAST_INSERT_ID();
   
 
    SELECT * FROM faturas a
    LEFT JOIN fatura_cobrancas b ON(a.idFatura = b.idFatura)
    WHERE a.idFatura = LAST_INSERT_ID();
    
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fatura_itensUpdate_save` (IN `pidItemFatura` INT(11), IN `pidFatura` INT(11), IN `pidAluguel` INT(11), IN `pperiodoLocacao` INT(1), IN `pvlAluguelCobrado` FLOAT, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pdtInicio` DATE, IN `pdtFim` DATE)  BEGIN

    DECLARE vidItemFatura INT;
    
    SELECT idItemFatura INTO vidItemFatura
        FROM fatura_itens
        WHERE idItemFatura = pidItemFatura;

    UPDATE fatura_itens
        SET
            idFatura = pidFatura,
            idAluguel = pidAluguel,
            periodoLocacao = pperiodoLocacao,
            vlAluguelCobrado = pvlAluguelCobrado,
            custoEntrega = pcustoEntrega,
            custoRetirada = pcustoRetirada,
            dtInicio = pdtInicio,
            dtFim = pdtFim  
        WHERE idItemFatura = vidItemFatura;    

    SELECT * FROM fatura_itens WHERE idItemFatura = pidItemFatura;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fatura_itens_delete` (IN `pidItemFatura` INT)  BEGIN
  
    DECLARE vidItemFatura INT;
    
  SELECT idItemFatura INTO vidItemFatura
    FROM fatura_itens
    WHERE idItemFatura = pidItemFatura;
    
    DELETE FROM fatura_itens WHERE idItemFatura = pidItemFatura;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fatura_itens_save` (IN `pidFatura` INT(11), IN `pidAluguel` INT(11), IN `pperiodoLocacao` INT(1), IN `pvlAluguelCobrado` FLOAT, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pdtInicio` DATE, IN `pdtFim` DATE)  BEGIN
  
    DECLARE vidItemFatura INT;
    
  INSERT INTO fatura_itens (
    idFatura,
    idAluguel,
    periodoLocacao,
    vlAluguelCobrado,
    custoEntrega,
    custoRetirada,
    dtInicio,
    dtFim
  )
  VALUES(
    pidFatura,
    pidAluguel,
    pperiodoLocacao,
    pvlAluguelCobrado,
    pcustoEntrega,
    pcustoRetirada,
    pdtInicio,
    pdtFim
  );
    
    SET vidItemFatura = LAST_INSERT_ID();

    SELECT * FROM fatura_itens WHERE idItemFatura = LAST_INSERT_ID();
    
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fretesUpdate_save` (IN `pid` INT(11), IN `ptipo_frete` INT(1), IN `pstatus` INT(1), IN `pdata_hora` DATETIME, IN `pobservacao` VARCHAR(150))  BEGIN
  
    DECLARE vidFrete, vidLocacao, vtipo_frete, vstatusFrete, vstatusAluguel INT;
    
    SELECT id INTO vidFrete
        FROM fretes
        WHERE id = pid;

    UPDATE fretes
        SET
            tipo_frete = ptipo_frete,
            status = pstatus,
            data_hora = pdata_hora,
            observacao = pobservacao
        WHERE id = vidFrete;    

    
-- ------------- ALTERANDO O STATUS DO ALUGUEL QUANDO O FRETE É CONCLUÍDO  
    SELECT a.id, a.idLocacao, a.tipo_frete, a.status, b.status
        INTO vidFrete, vidLocacao, vtipo_frete, vstatusFrete, vstatusAluguel
        FROM fretes a
        INNER JOIN historicoalugueis b ON(a.idLocacao = b.idHistoricoAluguel)
        WHERE id = pid;


    IF (vstatusFrete = 1) THEN -- SE O STATUS DO FRETE FOR = 1 (CONCLUÍDO), ENTÃO DEVE MUDAR O STATUS DO ALUGUEL

            IF(vtipo_frete = 0) THEN -- SE O TIPO_FRETE FOR 0 (ENTREGA),
 
                -- ENTÃO MUDA O STATUS DO ALUGUEL PARA 1 (ATIVO)
                UPDATE historicoalugueis 
                    SET status = 1
                WHERE idHistoricoAluguel = vidLocacao;
            
            ELSE -- SENAO SE O TIPO_FRETE FOR 1 (RETIRADA),
            
                -- ENTÃO MUDA O STATUS DO ALUGUEL PARA 3 (ENCERRADO)
                UPDATE historicoalugueis 
                    SET status = 3
                WHERE idHistoricoAluguel = vidLocacao;
            END IF;
              
    END IF;
-- -------------

    SELECT * FROM fretes WHERE id = pid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fretes_delete` (IN `pid` INT)  BEGIN
  
    DECLARE vid INT;
    
  SELECT id INTO vid
    FROM fretes
    WHERE id = pid;
    
    DELETE FROM fretes WHERE id = pid;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_fretes_save` (IN `pidLocacao` INT(11), IN `ptipo_frete` INT(1), IN `pstatus` INT(1), IN `pdata_hora` DATETIME, IN `pobservacao` VARCHAR(150))  BEGIN
  
    DECLARE vidFrete INT;
    
  INSERT INTO fretes (idLocacao, tipo_frete, status, data_hora, observacao)
    VALUES(pidLocacao, ptipo_frete, pstatus, pdata_hora, pobservacao);
    
    SET vidFrete = LAST_INSERT_ID();
 
    SELECT * FROM fretes WHERE id = LAST_INSERT_ID();
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_historicoalugueisUpdate_save` (IN `pidHistoricoAluguel` INT(11), IN `pstatus` TINYINT(4), IN `pvlAluguel` FLOAT, IN `pdtInicio` DATE, IN `pdtFinal` DATE, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pobservacao` TEXT)  BEGIN
  
    DECLARE vidHistoricoAluguel, vidProdutoDevolvido INT;
    
    SELECT idHistoricoAluguel, produto_idProduto INTO vidHistoricoAluguel, vidProdutoDevolvido
        FROM historicoalugueis
        WHERE idHistoricoAluguel = pidHistoricoAluguel;

    UPDATE historicoalugueis
        SET
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
  
    DECLARE vidHistoricoAluguel, vidProdutoDevolvido INT;
    
  SELECT idHistoricoAluguel, produto_idProduto INTO vidHistoricoAluguel, vidProdutoDevolvido
    FROM historicoalugueis
    WHERE idHistoricoAluguel = pidHistoricoAluguel;
    
    DELETE FROM historicoalugueis WHERE idHistoricoAluguel = pidHistoricoAluguel;

    UPDATE produtos_esp
        SET status = 1
        WHERE (idProduto_esp = vidProdutoDevolvido);
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_historicoalugueis_save` (IN `pcodigo` VARCHAR(11), IN `pcontrato_idContrato` INT(11), IN `pproduto_idProduto` INT(11), IN `pstatus` TINYINT(4), IN `pvlAluguel` FLOAT, IN `pdtInicio` DATE, IN `pdtFinal` DATE, IN `pcustoEntrega` FLOAT, IN `pcustoRetirada` FLOAT, IN `pobservacao` TEXT)  BEGIN
  
    DECLARE vidHistoricoAluguel INT;
    
  INSERT INTO historicoalugueis (codigo, contrato_idContrato, produto_idProduto, status, vlAluguel, dtInicio, dtFinal, custoEntrega, custoRetirada, observacao)
    VALUES(pcodigo, pcontrato_idContrato, pproduto_idProduto, pstatus, pvlAluguel, pdtInicio, pdtFinal, pcustoEntrega, pcustoRetirada, pobservacao);
    
    SET vidHistoricoAluguel = LAST_INSERT_ID();

    UPDATE produtos_esp
        SET status = 0
        WHERE (idProduto_esp = pproduto_idProduto);
    
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_responsaveisUpdate_save` (IN `pidResp` INT, IN `pcodigo` VARCHAR(3), IN `prespObra` VARCHAR(45), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `ptelefone3` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `panotacoes` VARCHAR(150), IN `pid_fk_cliente` INT)  BEGIN
  
    DECLARE vidResp INT;
    
    SELECT idResp INTO vidResp
    FROM resp_obras
    WHERE idResp = pidResp;

    UPDATE resp_obras
    SET
    	  codigo = pcodigo,
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_responsaveis_save` (IN `pcodigo` VARCHAR(3), IN `prespObra` VARCHAR(45), IN `ptelefone1` VARCHAR(15), IN `ptelefone2` VARCHAR(15), IN `ptelefone3` VARCHAR(15), IN `pemail1` VARCHAR(45), IN `pemail2` VARCHAR(45), IN `panotacoes` VARCHAR(150), IN `pid_fk_cliente` INT)  BEGIN
DECLARE vidResp INT;
    
  INSERT INTO resp_obras (codigo, respObra, telefone1, telefone2, telefone3, email1, email2, anotacoes, id_fk_cliente)
    VALUES(pcodigo, prespObra, ptelefone1, ptelefone2, ptelefone3, pemail1, pemail2, panotacoes, pid_fk_cliente);
    
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
  `codigo` varchar(3) NOT NULL,
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

INSERT INTO `clientes` (`idCliente`, `codigo`, `nome`, `status`, `telefone1`, `telefone2`, `email1`, `email2`, `endereco`, `complemento`, `cidade`, `bairro`, `numero`, `uf`, `cep`, `cpf`, `rg`, `cnpj`, `ie`, `tipoCliente`, `dtCadastro`) VALUES
(1, '001', 'ConstPira', 1, '(19) 9953-13563', '', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', 'Dr. Arlindo Justos Baptistella', '', 'Limeira', '', 0, 'SP', '13481-659', '534.534.524-25', '35.353.425-3', '', '0', 'F', '2020-06-16 19:53:47'),
(2, '002', 'Construtora Forte', 1, '', '', '', '', '', '', '', '', 0, '', '', '', '', '42.341.123/423', '4234124234', 'J', '2020-06-16 20:12:50'),
(3, '003', 'ConstruDoug', 0, '(19) 9953-13563', '', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', 'Dr. Arlindo Justos Baptistella', '', 'Limeira', '', 0, 'SP', '13481-659', '312.312.312-31', '14.132.312-3', '', '0', 'F', '2020-06-16 20:15:40'),
(7, '004', 'TESTE', 0, '', '', '', '', '', '', '', '', 0, '', '', '345.654.645-64', '12.334.345-2', '', '0', 'J', '2020-11-22 20:47:38');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contratos`
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
  `dtFim` date DEFAULT NULL,
  `statusOrcamento` tinyint(4) NOT NULL COMMENT 'ORÇAMENTO: \r\n0-pendente \r\n1-arquivado,\r\n CONTRATO: \r\n2-vencido  \r\n3-aprovado  \r\n4-em andamento \r\n5-encerrado',
  `temMedicao` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0- não\r\n1- sim',
  `regraFatura` int(1) DEFAULT NULL COMMENT '1- dia fixo\r\n2- dia semana',
  `semanaDoMes` int(1) DEFAULT NULL COMMENT 'número da semana dentro do mês',
  `diaFatura` int(2) DEFAULT NULL COMMENT 'número do dia no mês OU\r\no número do dia dentro da semana;\r\n1- domingo\r\n2- segunda-feira\r\n...\r\n7-sábado',
  `notas` varchar(100) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `contratos`
--

INSERT INTO `contratos` (`idContrato`, `codContrato`, `nomeEmpresa`, `obra_idObra`, `dtEmissao`, `solicitante`, `telefone`, `email`, `dtAprovacao`, `dtInicio`, `dtFim`, `statusOrcamento`, `temMedicao`, `regraFatura`, `semanaDoMes`, `diaFatura`, `notas`, `dtCadastro`) VALUES
(2, '20201022-002', NULL, 17, '2020-10-22', 'TESTE', '19000000000', 'teste@gmail.com', '2020-10-23', '2020-10-23', '0000-00-00', 4, 0, NULL, NULL, NULL, '', '2020-05-24 08:26:52'),
(3, '19', '', NULL, '2020-05-24', 'TESTE', '', '', NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '', '2020-05-24 08:27:46'),
(4, '20201231-001', '', 16, '2020-12-31', 'JOÃO MIGUEL', '', 'eldersamuel98@gmail.com', '2020-10-26', '0000-00-00', '0000-00-00', 3, 0, NULL, NULL, NULL, '', '2020-05-24 08:44:10'),
(5, '21', '', NULL, '2020-05-20', 'TESTSE3', '', '', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, '', '2020-05-24 08:45:42'),
(6, '20200530-002', NULL, 16, '2020-05-30', 'Rodrigo Souza', '3235413242', 'rodrigo@construforte.com', '2020-07-02', '2020-09-07', '0000-00-00', 4, 1, 2, 1, 3, 'teste', '2020-05-25 08:16:14'),
(7, '22', 'construtora X', NULL, '2020-11-22', 'Pedro', '19999999999', 'pedro@gmail.com', NULL, '0000-00-00', '0000-00-00', 0, 0, NULL, NULL, NULL, 'cadastro teste', '2020-11-22 17:06:24'),
(8, '23', 'construtora Y', NULL, '2020-11-22', 'JOÃO', '19000000000', '', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, '', '2020-11-22 17:09:40'),
(9, '24', 'teste', NULL, '2020-11-22', 'pedro', '10000000000', '', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, '', '2020-11-22 17:16:03'),
(10, '25', '', NULL, '2020-11-15', 'joao', '', '', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, '', '2020-11-22 17:23:36'),
(11, '20201101-002', NULL, 18, '2020-11-01', 'João', '19999999999', 'eldersamuel98@gmail.com', '2020-11-22', '2020-11-22', '0000-00-00', 4, 0, 0, 0, 0, '', '2020-11-22 23:08:44'),
(12, '20201122-002', NULL, 18, '2020-11-22', 'João', '19999999999', 'eldersamuel98@gmail.com', '2020-11-22', '2020-11-22', '0000-00-00', 4, 0, 0, 0, 0, 'teste', '2020-11-22 23:22:58');

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
  `periodoLocacao` int(1) NOT NULL COMMENT '1-diário  2-semanal  3-quinzenal  4-mensal',
  `observacao` text DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `contrato_itens`
--

INSERT INTO `contrato_itens` (`idItem`, `idContrato`, `idProduto_gen`, `vlAluguel`, `quantidade`, `custoEntrega`, `custoRetirada`, `periodoLocacao`, `observacao`, `dtCadastro`) VALUES
(1, 6, 1, 450, '3', 190, 190, 4, '', '2020-09-11 18:27:04'),
(2, 6, 5, 15470, '2', 215, 215, 4, 'Entrega em 2DD úteis', '2020-09-20 12:45:06'),
(3, 4, 1, 450, '5', 190, 190, 4, '', '2020-09-20 15:43:21'),
(4, 2, 1, 450, '2', 190, 190, 4, 'Entrega em 2DD', '2020-09-21 18:45:10'),
(5, 2, 2, 234, '2', 150, 150, 2, '', '2020-10-23 19:00:30'),
(6, 7, 1, 450, '2', 200, 200, 4, '', '2020-11-22 17:07:20'),
(7, 7, 8, 600, '2', 100, 100, 4, '', '2020-11-22 17:08:23'),
(8, 8, 2, 234, '2', 100, 100, 4, '', '2020-11-22 17:10:19'),
(9, 8, 2, 234, '2', 0, 0, 4, '', '2020-11-22 17:10:33'),
(10, 8, 2, 234, '2', 100, 100, 4, '', '2020-11-22 17:11:16'),
(11, 8, 4, 500, '1', 40, 40, 4, '', '2020-11-22 17:12:54'),
(12, 9, 1, 450, '1', 30, 30, 4, '', '2020-11-22 17:16:17'),
(13, 10, 1, 450, '1', 100, 100, 4, '', '2020-11-22 17:23:52'),
(14, 11, 1, 450, '3', 334, 50, 4, '', '2020-11-22 23:09:19'),
(15, 12, 68, 500, '2', 50, 50, 2, 'teste', '2020-11-22 23:23:32');

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa`
--

CREATE TABLE `empresa` (
  `id` int(3) NOT NULL,
  `codigo` varchar(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `tipoEmpresa` varchar(45) NOT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  `rg` varchar(15) DEFAULT NULL,
  `cnpj` varchar(15) DEFAULT NULL,
  `ie` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `empresa`
--

INSERT INTO `empresa` (`id`, `codigo`, `nome`, `endereco`, `complemento`, `bairro`, `municipio`, `estado`, `cep`, `tipoEmpresa`, `cpf`, `rg`, `cnpj`, `ie`) VALUES
(1, '001', 'COMFAL - Locação de Máquinas LTDA-ME', 'Rua Hugo Rondelli, 66', NULL, 'Monte Carlo', 'Americana', 'SP', '13476692', 'Jurídica', NULL, NULL, '71528426000160', '165122184116');

-- --------------------------------------------------------

--
-- Estrutura da tabela `faturas`
--

CREATE TABLE `faturas` (
  `idFatura` int(11) NOT NULL,
  `idContrato` int(11) NOT NULL,
  `numFatura` varchar(11) NOT NULL,
  `dtEmissao` date NOT NULL,
  `dtInicio` date NOT NULL,
  `dtFim` date NOT NULL,
  `enviarPorEmail` tinyint(1) NOT NULL COMMENT '0-não 1-sim',
  `emailEnvio` varchar(40) DEFAULT NULL,
  `dtEnvio` date NOT NULL,
  `adicional` varchar(100) DEFAULT NULL,
  `observacoes` varchar(100) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `faturas`
--

INSERT INTO `faturas` (`idFatura`, `idContrato`, `numFatura`, `dtEmissao`, `dtInicio`, `dtFim`, `enviarPorEmail`, `emailEnvio`, `dtEnvio`, `adicional`, `observacoes`, `dtCadastro`) VALUES
(87, 6, '1-2020', '2020-11-28', '2020-09-07', '2020-12-07', 0, '', '2020-12-31', '0', NULL, '2020-12-14 18:43:16');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fatura_cobrancas`
--

CREATE TABLE `fatura_cobrancas` (
  `idCobranca` int(11) NOT NULL,
  `idFatura` int(11) NOT NULL,
  `formaPagamento` int(1) NOT NULL COMMENT '1-boleto, 2-DOC, 3-transferência, 4-dinheiro, 5-cheque e 6-outros',
  `dtVencimento` date NOT NULL,
  `especCobranca` varchar(60) DEFAULT NULL,
  `dtCobranca` date DEFAULT NULL,
  `statusPagamento` int(1) NOT NULL COMMENT ' 0-pendente, 1-parcial, 2-pago, 3-cancelado, 4-perdido',
  `numNF` int(11) DEFAULT NULL,
  `numBoletoInt` int(11) DEFAULT NULL,
  `numBoletoBanco` int(11) DEFAULT NULL,
  `valorPago` float DEFAULT NULL,
  `dtPagamento` date DEFAULT NULL,
  `dtVerificacao` date DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `fatura_cobrancas`
--

INSERT INTO `fatura_cobrancas` (`idCobranca`, `idFatura`, `formaPagamento`, `dtVencimento`, `especCobranca`, `dtCobranca`, `statusPagamento`, `numNF`, `numBoletoInt`, `numBoletoBanco`, `valorPago`, `dtPagamento`, `dtVerificacao`, `dtCadastro`) VALUES
(87, 87, 2, '2020-12-08', '', '0000-00-00', 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '2020-12-14 18:43:16');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fatura_itens`
--

CREATE TABLE `fatura_itens` (
  `idItemFatura` int(11) NOT NULL,
  `idFatura` int(11) NOT NULL,
  `idAluguel` int(11) NOT NULL,
  `periodoLocacao` int(1) NOT NULL COMMENT '1-diário  2-semanal  3-quinzenal  4-mensal',
  `vlAluguelCobrado` decimal(11,2) NOT NULL COMMENT 'valor calculado em cima do período (dtInicio - dtFim)',
  `custoEntrega` float NOT NULL COMMENT 'valor de frete de entrega, se houver',
  `custoRetirada` float NOT NULL COMMENT 'valor de frete de retirada, se houver',
  `dtInicio` date NOT NULL COMMENT 'data de início do período cobrado',
  `dtFim` date NOT NULL COMMENT 'data final do período cobrado',
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabela de itens da fatura';

--
-- Extraindo dados da tabela `fatura_itens`
--

INSERT INTO `fatura_itens` (`idItemFatura`, `idFatura`, `idAluguel`, `periodoLocacao`, `vlAluguelCobrado`, `custoEntrega`, `custoRetirada`, `dtInicio`, `dtFim`, `dtCadastro`) VALUES
(124, 87, 7, 4, '160.00', 200, 0, '2020-09-07', '2020-09-15', '2020-12-14 18:43:16'),
(125, 87, 13, 4, '450.00', 190, 0, '2020-09-20', '2020-10-20', '2020-12-14 18:43:16'),
(126, 87, 16, 4, '31455.67', 215, 0, '2020-10-05', '2020-12-05', '2020-12-14 18:43:16'),
(127, 87, 15, 4, '19595.33', 215, 0, '2020-10-18', '2020-11-25', '2020-12-14 18:43:16');

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
-- Estrutura da tabela `fretes`
--

CREATE TABLE `fretes` (
  `id` int(11) NOT NULL,
  `idLocacao` int(11) NOT NULL,
  `tipo_frete` int(1) NOT NULL COMMENT '"0-Entrega" ou "1-Retirada"',
  `status` int(1) NOT NULL COMMENT '"0-Pendente" ou "1-Concluída"',
  `data_hora` datetime NOT NULL,
  `observacao` varchar(150) DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `fretes`
--

INSERT INTO `fretes` (`id`, `idLocacao`, `tipo_frete`, `status`, `data_hora`, `observacao`, `dtCadastro`) VALUES
(1, 7, 0, 1, '2020-09-07 09:00:00', 'Item: Container 3M almoxarifado com lavabo DC\r\nCod. produto: 001.01.01.01.01.002-0003\r\n', '2020-09-20 17:38:51'),
(5, 16, 0, 1, '2020-10-05 09:30:00', 'teste', '2020-10-12 09:23:37'),
(13, 7, 1, 1, '2020-11-05 16:38:00', 'Cod. Prod.: 001.01.01.01.01.002-0003,  \r\n<b>Produto: 3M almoxarifado com lavabo DC</b>,  TESTE ATUALIZAR\r\n', '2020-10-12 16:38:40'),
(14, 15, 0, 1, '2020-10-18 09:22:00', 'Cod. Prod.: 001.04.05.02.02.002-0002,  \r\n<b>Produto: 12M stand de vendas sem lavabo HC</b>,  \r\n', '2020-10-18 09:23:01'),
(15, 18, 0, 1, '2020-10-25 00:06:00', 'Cod. Prod.: 001.01.01.01.01.002-0001,  \r\n<b>Produto: 3M almoxarifado com lavabo DC</b>,  \r\n', '2020-10-25 00:06:55'),
(16, 20, 0, 1, '2020-11-23 08:00:00', 'Cod. Prod.: 001.01.01.01.01.002-0004,  \r\n<b>Produto: 3M almoxarifado com lavabo DC</b>,  \r\n', '2020-11-22 23:14:31'),
(17, 21, 0, 1, '2020-11-23 08:30:00', 'Cod. Prod.: 002.01.01.01.02.002-0006,  \r\n<b>Produto: MARCA X MODELO X Elétrica 220V</b>,  \r\n', '2020-11-22 23:25:51');

-- --------------------------------------------------------

--
-- Estrutura da tabela `historicoalugueis`
--

CREATE TABLE `historicoalugueis` (
  `idHistoricoAluguel` int(11) NOT NULL,
  `codigo` varchar(11) NOT NULL,
  `contrato_idContrato` int(11) NOT NULL,
  `produto_idProduto` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Entrega Pendente\r\n1-Ativo\r\n2-Retirada Pendente\r\n3-Encerrado    ',
  `vlAluguel` float NOT NULL,
  `dtInicio` date NOT NULL,
  `dtFinal` date DEFAULT NULL,
  `custoEntrega` float DEFAULT NULL,
  `custoRetirada` float DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `dtCadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `historicoalugueis`
--

INSERT INTO `historicoalugueis` (`idHistoricoAluguel`, `codigo`, `contrato_idContrato`, `produto_idProduto`, `status`, `vlAluguel`, `dtInicio`, `dtFinal`, `custoEntrega`, `custoRetirada`, `observacao`, `dtCadastro`) VALUES
(7, '3', 6, 3, 1, 600, '2020-09-07', '2020-09-15', 200, 200, '', '2020-09-11 23:47:57'),
(13, '4', 6, 1, 3, 450, '2020-09-20', '2020-10-20', 190, 190, '', '2020-09-20 09:30:03'),
(15, '5', 6, 2, 1, 15470, '2020-10-18', '2020-11-25', 215, 215, '', '2020-10-05 20:22:21'),
(16, '6', 6, 15, 1, 15470, '2020-10-05', '2020-12-05', 215, 215, '', '2020-10-05 20:22:21'),
(18, '7', 2, 1, 1, 450, '2020-10-25', '0000-00-00', 190, 190, '', '2020-10-25 00:03:44'),
(19, '8', 2, 3, 0, 450, '2020-10-25', '0000-00-00', 190, 190, '', '2020-10-25 00:03:44'),
(20, '9', 11, 8, 1, 450, '2020-11-23', '2020-12-15', 334, 50, 'teste', '2020-11-22 23:14:01'),
(21, '10', 12, 18, 1, 500, '2020-11-23', '0000-00-00', 50, 50, '', '2020-11-22 23:25:21');

-- --------------------------------------------------------

--
-- Estrutura da tabela `obras`
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
(16, 1, '', 'Araras', 'Centro', 123, 'SP', '13600000', 'Rua X', '2020-07-02 20:55:15', 1, 17),
(17, 1, 'teste', 'Araras', 'Jd. Santana', 343, 'AM', '12000000', 'Rua Zanchetta', '2020-07-02 20:57:09', 2, 18),
(18, 2, 'Teste', 'Rio Branco', 'T-rex', 450, 'AC', '13444000', 'Rua dos Dinos', '2020-07-02 20:58:08', 1, 16);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_esp`
--

CREATE TABLE `produtos_esp` (
  `idProduto_esp` int(11) NOT NULL,
  `idProduto_gen` int(11) NOT NULL,
  `codigoEsp` varchar(24) NOT NULL,
  `valorCompra` float DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-Alugado \r\n  1-Disponível \r\n  2-Manutenção',
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
(1, 1, '001.01.01.01.01.002-0001', 12500, 0, '2020-04-22', '0001', '', 2, '2020-04-22 09:49:52'),
(2, 5, '001.04.05.02.02.002-0002', 10000, 0, '2020-04-22', '0002', 'cadastro teste', 2, '2020-04-22 09:51:18'),
(3, 1, '001.01.01.01.01.002-0003', 13899.8, 0, '2020-04-22', '0003', 'teste cadastro', 2, '2020-04-22 09:57:53'),
(4, 2, '002.01.01.01.01.001-0001', 520.98, 1, '2020-04-22', '0001', 'cadastro teste', 1, '2020-04-22 10:45:04'),
(5, 2, '002.01.01.01.01.004-0002', 450.77, 1, '2020-04-22', '0002', 'cadastro teste', 5, '2020-04-22 10:47:10'),
(6, 2, '002.01.01.01.01.002-0003', 459.89, 1, '2020-04-22', '0003', 'cadastro teste', 2, '2020-04-22 10:49:38'),
(7, 2, '002.01.01.01.01.002-0004', 345.76, 1, '2020-04-22', '0004', 'CADASTRO TESTE', 2, '2020-04-22 10:50:40'),
(8, 1, '001.01.01.01.01.002-0004', 15477.2, 0, '2011-04-12', '0004', '', 2, '2020-04-22 11:47:06'),
(10, 4, '005.02.01.02.xx.003-0001', 2520.02, 1, '2020-12-05', '0001', '', 4, '2020-05-23 22:02:04'),
(12, 8, '001.03.01.02.01.002-0005', 9870.65, 1, '2020-09-10', '0005', '', 2, '2020-09-10 11:51:55'),
(13, 8, '001.03.01.02.01.001-0006', 9000, 1, '2019-08-10', '0006', '', 1, '2020-09-10 12:02:15'),
(14, 1, '001.01.01.01.01.002-0007', 5000, 1, '0000-00-00', '0007', '', 2, '2020-09-12 13:52:36'),
(15, 5, '001.04.05.02.02.002-0008', 15000, 0, '2020-02-01', '0008', '', 2, '2020-09-21 19:30:52'),
(16, 5, '001.04.05.02.02.002-0009', 15200, 1, '0000-00-00', '0009', '', 2, '2020-09-21 19:35:07'),
(17, 65, '002.01.01.02.xx.001-0005', 456, 1, '2020-11-01', '0005', 'teste', 1, '2020-11-22 23:07:53'),
(18, 68, '002.01.01.01.02.002-0006', 450, 0, '2020-11-16', '0006', 'teste', 2, '2020-11-22 23:22:18');

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
(1, '001.01.01.01.01', '3M almoxarifado com lavabo DC', 1, 1, 5, 12, 14, 450, '2020-04-20 19:25:32'),
(2, '002.01.01.01.01', 'MARCA X MODELO X Elétrica 110V', 2, 16, 17, 18, 21, 234, '2020-04-20 19:54:06'),
(3, '004.03.xx.xx.xx', '2,30m a 4,00m', 4, 50, NULL, NULL, NULL, 125.66, '2020-04-22 15:14:37'),
(4, '005.02.01.02.xx', 'Janela 10.000 btu 220V', 5, 54, 55, 59, NULL, 500, '2020-05-23 21:57:10'),
(5, '001.04.05.02.02', '12M stand de vendas sem lavabo HC', 1, 4, 9, 13, 15, 15470, '2020-04-22 14:22:48'),
(6, '004.05.xx.xx.xx', '3,00m a 5,10m', 4, 52, NULL, NULL, NULL, 250, '2020-04-22 15:07:22'),
(7, '003.01.01.01.xx', 'Tubular Painel 1,00m', 3, 30, 33, 45, NULL, 200, '2020-04-22 15:17:37'),
(8, '001.03.01.02.01', '6M almoxarifado sem lavabo DC', 1, 3, 5, 13, 14, 600, '2020-05-23 20:58:53'),
(9, '001.03.03.xx.01', '6M sanitário DC', 1, 3, 7, NULL, 14, 600, '2020-05-23 21:03:20'),
(10, '001.01.01.02.02', '3M almoxarifado sem lavabo HC', 1, 1, 5, 13, 15, 5000, '2020-11-22 21:31:57'),
(21, '001.02.06.02.02', '4M lanchonete sem lavabo HC', 1, 2, 10, 13, 15, 1300, '2020-11-22 21:48:11'),
(28, '005.02.01.01.xx', 'Janela 10.000 btu 110V', 5, 54, 55, 58, NULL, 578, '2020-11-22 22:11:10'),
(63, '003.01.06.02.xx', 'Tubular Rodapé 1,50m', 3, 30, 38, 46, NULL, 4564, '2020-11-22 23:00:04'),
(65, '002.01.01.02.xx', 'MARCA X MODELO X Combustão', 2, 16, 17, 19, NULL, 678, '2020-11-22 23:07:07'),
(66, '003.01.02.01.xx', 'Tubular Trava diagonal 1,00m', 3, 30, 34, 45, NULL, 567, '2020-11-22 23:18:30'),
(68, '002.01.01.01.02', 'MARCA X MODELO X Elétrica 220V', 2, 16, 17, 18, 22, 500, '2020-11-22 23:21:42');

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
(4, 'Escora metálica', '004', '2020-03-09 11:36:59'),
(5, 'Ar-condicionado', '005', '2020-05-23 21:22:43');

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
(1, 1, 'Marítima', 1, 0, 1, 1, 1, 1, 1, 0, 0, '2020-04-22 09:49:52'),
(2, 2, 'Marítma', 2, 0, 1, 1, 2, 2, 1, 0, 0, '2020-04-22 09:51:18'),
(3, 3, 'Marítma', 1, 0, 1, 0, 1, 1, 1, 0, 0, '2020-04-22 09:57:53'),
(4, 8, 'Marítima', 0, 0, 1, 1, 1, 2, 1, 1, 0, '2020-04-22 11:47:07'),
(5, 12, 'marítima', 0, 1, 1, 1, 1, 1, 1, 0, 0, '2020-09-10 11:51:55'),
(6, 13, 'normal', 2, 0, 0, 0, 1, 2, 1, 0, 0, '2020-09-10 12:02:15'),
(7, 14, 'normal', 0, 1, 0, 0, 0, 0, 0, 0, 0, '2020-09-12 13:52:36'),
(8, 15, 'normal', 1, 0, 0, 0, 1, 1, 1, 0, 0, '2020-09-21 19:30:52'),
(9, 16, 'normal', 0, 0, 0, 0, 0, 0, 0, 0, 0, '2020-09-21 19:35:07');

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
-- Estrutura da tabela `resp_obras`
--

CREATE TABLE `resp_obras` (
  `idResp` int(11) NOT NULL,
  `codigo` varchar(3) NOT NULL,
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

INSERT INTO `resp_obras` (`idResp`, `codigo`, `id_fk_cliente`, `respObra`, `telefone1`, `telefone2`, `telefone3`, `email1`, `email2`, `anotacoes`, `dtCadastro`) VALUES
(16, '001', 1, 'Roberto', '(19) 9890-35434', '', '', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', '', '2020-06-16 22:16:37'),
(17, '002', 1, 'Douglas', '(19) 9953-13563', '', '', '', '', '', '2020-06-21 21:19:56'),
(18, '001', 2, 'Resp 1', '(19) 9953-13563', '', '', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', '', '2020-06-21 22:02:14'),
(22, '002', 2, 'Resp 2', '(19) 9953-13563', '', '', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', '', '2020-06-21 22:03:24'),
(23, '001', 3, 'ddsfsdf', '(19) 9953-13563', '', '', 'douglas.rnmeriano@gmail.com', 'douglas.rnmeriano@gmail.com', '', '2020-06-21 22:11:37');

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
(0, 'DOUGLAS RODRIGUES NUMERIANO', 'Diretor', 'douglas', '$2y$12$LKuhzYgrLzatkMgmAmGNC.KYZWmIYmQMDFDGBe6Wek96MmVfE1YNy', 'douglas.rnmeriano@gmail.com', 1, '/res/img/users/1590973211_1583190423_avatar5.png', '2020-05-31 22:00:11'),
(1, 'Elder Samuel', 'Programador', 'elder', '$2y$12$6UBTMz.ZC3ZEf8ytouE5ReApu0tjrDPOjmb7/vY5ooh0coVFXHMPS', 'eldersamuel98@gmail.com', 0, '/res/img/users/user-default.jpg', '2020-02-26 10:45:06'),
(2, 'Administrador', 'Admin', 'admin', '$2y$12$Gn6FtBrS9EjKS8OFuhkjQOfgGfZTR2OJnToY8PC7y2fLoFGZU9g.i', 'eldersamuel98@gmail.com', 1, '/res/img/users/user-default.jpg', '2020-02-29 22:45:00'),
(3, 'Matheus Leite de Campos', 'Product Owner', 'matheus', '$2y$12$HgGxPtV/zZhse52m9Dc6HuE8bUiXeFWCW66AtdiUW2OB537qmhmrO', 'matheus@gmail.com', 1, '/res/img/users/user-default.jpg', '2020-03-05 17:22:07'),
(7, 'teste', 'teste', 'teste', '$2y$12$EEBilymLfWvZXlkIJU9AbuRzTk0RRRu8AgGFBCajpYCYVKI7B6kci', 'eldersamuel98@gmail.com', 0, '/res/img/users/user-default.jpg', '2020-11-22 23:05:24');

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
  ADD KEY `fk_contrato_itens_has_contrato` (`idContrato`),
  ADD KEY `fk_contrato_has_produto` (`idProduto_gen`) USING BTREE;

--
-- Índices para tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `faturas`
--
ALTER TABLE `faturas`
  ADD PRIMARY KEY (`idFatura`),
  ADD KEY `fk_fatura_contrato1` (`idContrato`);

--
-- Índices para tabela `fatura_cobrancas`
--
ALTER TABLE `fatura_cobrancas`
  ADD PRIMARY KEY (`idCobranca`),
  ADD KEY `fk_cobranca_fatura` (`idFatura`);

--
-- Índices para tabela `fatura_itens`
--
ALTER TABLE `fatura_itens`
  ADD PRIMARY KEY (`idItemFatura`),
  ADD KEY `fk_fatura_itens_has_fatura` (`idFatura`),
  ADD KEY `fk_fatura_itens_has_historicoalugueis` (`idAluguel`) USING BTREE;

--
-- Índices para tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`idFornecedor`);

--
-- Índices para tabela `fretes`
--
ALTER TABLE `fretes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_frete_locacao` (`idLocacao`);

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
  ADD KEY `obras_ibfk_1` (`id_fk_cliente`),
  ADD KEY `obras_ibfk_2` (`id_fk_respObra`);

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
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `contratos`
--
ALTER TABLE `contratos`
  MODIFY `idContrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `contrato_itens`
--
ALTER TABLE `contrato_itens`
  MODIFY `idItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `faturas`
--
ALTER TABLE `faturas`
  MODIFY `idFatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de tabela `fatura_cobrancas`
--
ALTER TABLE `fatura_cobrancas`
  MODIFY `idCobranca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de tabela `fatura_itens`
--
ALTER TABLE `fatura_itens`
  MODIFY `idItemFatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `idFornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `fretes`
--
ALTER TABLE `fretes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  MODIFY `idHistoricoAluguel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `obras`
--
ALTER TABLE `obras`
  MODIFY `idObra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `produtos_esp`
--
ALTER TABLE `produtos_esp`
  MODIFY `idProduto_esp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `produtos_gen`
--
ALTER TABLE `produtos_gen`
  MODIFY `idProduto_gen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de tabela `prod_categorias`
--
ALTER TABLE `prod_categorias`
  MODIFY `idCategoria` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `prod_containers`
--
ALTER TABLE `prod_containers`
  MODIFY `idContainer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `prod_tipos`
--
ALTER TABLE `prod_tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de tabela `resp_obras`
--
ALTER TABLE `resp_obras`
  MODIFY `idResp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `fk_contrato_has_produto` FOREIGN KEY (`idProduto_gen`) REFERENCES `produtos_gen` (`idProduto_gen`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contrato_itens_has_contrato` FOREIGN KEY (`idContrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `faturas`
--
ALTER TABLE `faturas`
  ADD CONSTRAINT `fk_fatura_contrato1` FOREIGN KEY (`idContrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `fatura_cobrancas`
--
ALTER TABLE `fatura_cobrancas`
  ADD CONSTRAINT `fk_cobranca_fatura` FOREIGN KEY (`idFatura`) REFERENCES `faturas` (`idFatura`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `fatura_itens`
--
ALTER TABLE `fatura_itens`
  ADD CONSTRAINT `fk_fatura_itens_has_fatura` FOREIGN KEY (`idFatura`) REFERENCES `faturas` (`idFatura`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_fatura_itens_has_historicoalugueis	` FOREIGN KEY (`idAluguel`) REFERENCES `historicoalugueis` (`idHistoricoAluguel`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `fretes`
--
ALTER TABLE `fretes`
  ADD CONSTRAINT `fk_frete_locacao` FOREIGN KEY (`idLocacao`) REFERENCES `historicoalugueis` (`idHistoricoAluguel`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `historicoalugueis`
--
ALTER TABLE `historicoalugueis`
  ADD CONSTRAINT `fk_contrato_has_produto_contrato2` FOREIGN KEY (`contrato_idContrato`) REFERENCES `contratos` (`idContrato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contrato_has_produto_produto2` FOREIGN KEY (`produto_idProduto`) REFERENCES `produtos_esp` (`idProduto_esp`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
