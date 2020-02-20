<?php if(!class_exists('Rain\Tpl')){exit;}?><!doctype html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8" />
	<link rel="icon" type="image/png" href="/res/assets/img/box.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Light Bootstrap Dashboard by Creative Tim</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="/res/assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="/res/assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="/res/assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="/res/assets/css/demo.css" rel="stylesheet" />


    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="/res/assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

    <link rel="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>  

   <!--script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script-->
   
   <!-- Google Charts -->
   <script type="text/javascript" src="/res/assets/js/chart/loader.js"></script>
</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="green" data-image="/res/assets/img/sidebar-5.jpg">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                    LocServ
                </a>
            </div>

            <ul class="nav">
                <li id="linkDashboard">
                    <a href="index.html">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li id="linkClientes">
                    <a href="clientes.html">
                        <i class="pe-7s-users"></i>
                        <p>Clientes</p>
                    </a>
                </li>

                <div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Dropdown button
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="#">Action</a><br>
    <a class="dropdown-item" href="#">Another action</a><br>
    <a class="dropdown-item" href="#">Something else here</a>
  </div>
</div>





                <li id="linkProdutos">
                    <a href="produtos.html">
                        <i class="pe-7s-ticket"></i>
                        <p>Produtos</p>
                    </a>
                </li>
                <li id="linkLocacoes">
                    <a href="locacoes.html">
                        <i class="pe-7s-refresh-2"></i>
                        <p>Locações</p>
                    </a>
                </li>
                <li id="linkFaturas">
                    <a href="faturas.html">
                        <i class="pe-7s-ribbon"></i>
                        <p>Faturas</p>
                    </a>
                </li>
                <li id="linkUsuario">
                    <a href="usuario.html">
                        <i class="pe-7s-user"></i>
                        <p>Perfil de Usuário</p>
                    </a>
                </li>
                <li id="linkRelatorios">
                    <a href="relatorios.html">
                        <i class="pe-7s-news-paper"></i>
                        <p>Relatórios</p>
                    </a>
                </li>
                
                <li id="linkConfig">
                    <a href="config.html">
                        <i class="pe-7s-config"></i>
                        <p>Configurações</p>
                    </a>
                </li>
                
				<li class="active">
                    <a href="melhorias.html">
                        <i class="pe-7s-rocket"></i>
                        <p>Melhorias</p>
                    </a>
                </li>
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Dashboard</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-dashboard"></i>
								<p class="hidden-lg hidden-md">Dashboard</p>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  <i class="fa fa-globe"></i>
                                  <b class="caret hidden-sm hidden-xs"></b>
                                  <span class="notification hidden-sm hidden-xs">5</span>
                                  <p class="hidden-lg hidden-md">
                                      5 Notifications
                                      <b class="caret"></b>
                                  </p>
                            </a>
                            <ul class="dropdown-menu">
                              <li><a href="#">Notification 1</a></li>
                              <li><a href="#">Notification 2</a></li>
                              <li><a href="#">Notification 3</a></li>
                              <li><a href="#">Notification 4</a></li>
                              <li><a href="#">Another notification</a></li>
                            </ul>
                      </li>
                        <li>
                           <a href="">
                                <i class="fa fa-search"></i>
								<p class="hidden-lg hidden-md">Search</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="">
                               <p>Conta</p>
                            </a>
                        </li>
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <p>
										Emails
										<b class="caret"></b>
									</p>

                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="#">Orçamentos</a></li>
                                <li><a href="#">Pedidos</a></li>
                           
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                              </ul>
                        </li>
                        <li>
                            <a href="#">
                                <p>Sair</p>
                            </a>
                        </li>
						<li class="separator hidden-lg"></li>
                    </ul>
                </div>
            </div>
        </nav>