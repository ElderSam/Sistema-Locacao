<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Sistema Locação</title>
        <link href="/res/css/styles.css" rel="stylesheet" />
        <link href="/res/plugins/dataTables/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <script src="/res/plugins/font-awesome/all.min.js"></script>
    </head>
   
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="/">Sistema Locação</a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button
            ><!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Procurar por..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Configurações</a><a class="dropdown-item" href="#">Log de Atividades</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/logout">Sair</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <!-- left menu -->
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">

                            <div class="sb-sidenav-menu-heading"></div>
                            <a class="nav-link" href="/index"
                                ><div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                                Início</a>

                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCostumers"
                                aria-expanded="false" aria-controls="collapseCostumers">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-friends"></i></div>
                                Clientes
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            
                            <div class="collapse" id="collapseCostumers" aria-labelledby="headingOne"
                                data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav bg-secondary" id="sideNavCostumers">
                                    <a class="nav-link" href="/costumers">Clientes</a>
                                    <a class="nav-link" href="/budgets">Orçamentos</a>
                                    <a class="nav-link" href="/budgets">Contratos</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProducts"
                                aria-expanded="false" aria-controls="collapseProducts">
                                <div class="sb-nav-link-icon"><i class="fas fa-box-open"></i></div>
                                Produtos
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse" id="collapseProducts" aria-labelledby="headingOne"
                                data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav bg-secondary">                
                                    <a class="nav-link" href="/products/containers">Containers</a>
                                    <a class="nav-link" href="/products">Outros</a>
                                </nav>
                            </div>

                            <a class="nav-link" href="/locations">
                                <div class="sb-nav-link-icon"><i class="fas fa-align-left"></i></div>
                                Locações
                            </a>

                            <a class="nav-link" href="/invoices">
                                <div class="sb-nav-link-icon"><i class="far fa-chart-bar"></i></div>
                                Faturas
                            </a>

                            <a class="nav-link" href="/reports">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Relatórios
                            </a>

                            <a class="nav-link" href="/users">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                Usuários
                            </a>

                            <a class="nav-link" href="fornecedores.html"
                            ><div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                            Fornecedores</a>

                            <a class="nav-link" href="configuracoes.html"
                            ><div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                            Configurações</a>

                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                                data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logado como:</div>
                        Admin
                    </div>
                </nav>
            </div>
            <!-- end left menu-->