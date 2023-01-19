<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

require_once './lib/index.php';

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];


?><!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta property="og:title" content="" />
    <meta property="og:type" content="landing" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="images/logotype.png" />

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/stylesheets/screen.css?r=10" rel="stylesheet" type="text/css" />
    <link href="assets/stylesheets/responsive.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <script src="https://kit.fontawesome.com/df77ef0ddd.js" crossorigin="anonymous"></script>
    <title>TMY Chain web wallet</title>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type='text/javascript'>

    window.t = {
        'noRecords':'<?php echo t('Записи не найдены')?>',
        'prevpage':'<?php echo t('Предыдущая')?>',
        'nextpage': '<?php echo t('Следующая')?>',
        'selecttoken':'<?php echo t('Выберите токен')?>',
        'mustbe': '<?php echo t('Кошелек должен быть в сети')?>',
        'mustbefirst':'<?php echo t('Переключить?')?>',
        'addressinvalid': "<?php echo t('не правильный адрес')?>",
        'send_balance_error': '<?php echo t('не правильная сумма, должна быть больше нуля и меньше чем весь ваш баланс')?>',
        'send_success':'<?php echo t('Транзакция отправлена')?>',
        'tx':'<?php echo t('транзакция')?>',
        'txrejected':'<?php echo t('была отклонена')?>',
        'choosetokenerror': '<?php echo t('Адрес токена не правильный или не выбран')?>',
        'invalidtokenaddress': '<?php echo t('Адрес токена не правильный')?>',
    }


    </script>
</head>

<body>
    <div class="main-wrapper container">
        <div class="header-section">
            <div class="header-section_top-area d-lg-flex justify-content-between align-items-center">
                <div class="top_links hide">
                    <a href="#" class="activeTopNav">WhitePaper</a>
                    <a href="#">BlackPaper</a>
                    <a href="#">SilverPaper</a>
                    <a href="#">IndigoPaper</a>
                    <a href="#">Amber</a>
                </div>
                <div class="top_marketInfo-toolbar d-flex align-items-center" style="margin-left: auto;">
                    <div class=" top_marketInfo_item mr19"> </div>
                    <div class=" dropdown-element dropdown-element-lang">
                    <a href="javascript:;"> <?php if (i18n::getCurrentLang() == 'ru'): ?><span class="lang-icon"><img src="images/landing/ru.png" alt=""></span> на русском <?php else:?> <span class="lang-icon"><img src="images/landing/lang.png" alt=""></span> in English<?php endif?></a>
                        <ul>
                            <li><a href="?lang=en">in English</a></li>
                            <li><a href="?lang=ru">на русском</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="header-section_top-navbar d-flex justify-content-between align-items-center">
                <span>
                    <a href="https://tmychain.org">
                        <img src="/assets/images/wallet-logo.svg" alt="">
                    </a>
                    <a href="/" class="walletLogoEl">
                        /:Wallet
                    </a>
                </span>

                <nav class="MainNavbar">
                    <ul class="d-flex justify-content-between">
                        <li class="dropdown-element">
                            <a href="#"><?php echo t('Компания')?></a>
                            <ul>
                                <li><a target='_blank'
                                        href="https://drive.google.com/drive/folders/1_0JFxV1FSWDvAVZlmlA50nAFDgZsIyLN?usp=sharing "><?php echo t('Документы')?></a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#contacts"><?php echo t('Контакты')?></a></li>
                        <li><a target='_blank' href="https://tmyscan.com"><?php echo t('Scanner')?></a></li>
                        <li class="activeNav"><a href="https://wallet.tmychain.org"><?php echo t('Wallet')?></a></li>
                        <li target='_blank'class=""><a href="https://tmyswap.org">DEX</a></li>
                    </ul>
                </nav>
                <div class="d-flex align-items-center">
                    <label for="searchNav" class="searchInput MainNavbar__search">
                        <svg class="iconEl search-icon MainNavbar__searchBtn">
                            <use xlink:href="/assets/images/sprite.svg#search"></use>
                        </svg>
                        <input type="text" id="searchNav" placeholder="<?php echo t('Введите запрос..')?>">
                    </label>
                    <div class="userBox d-flex align-items-center">

                        <a id="connect-menu" class="btn btn-primary btn-lg px-4 btnEl"><?php echo t('Connect')?></a>
                        <a id="disconnect" class="hide btn btn-primary btn-lg px-4 btnEl"><?php echo t('Disconnect')?></a>

                    </div>
                </div>
            </div>
        </div>
        <div class="userFinanceBoxGradientBg">
            <span></span><span></span><span></span>
        </div>

        <main class="access">
            <div class="title"><?php echo t('Connect with metamask')?></div>
            <p class="desc"><?php echo t('For connect to tmy network you need install metamask')?></p>

            <div class="mb-5">
                <a id="connect" class="btn btn-primary btn-lg px-4 btnEl"><?php echo t('Connect')?></a>
            </div>

        </main>

        <div class="wallet hide">


            <div class="asideToolbar-wrapper">
                <div class="asideToolbar">
                    <div class="container">
                        <a href="#" onclick="return showWalletPane()"
                            class="asideToolbar__item asideToolbar__item-active" role="button">
                            <span class="asideToolbar__item-icon"> <svg width="18" height="17" viewBox="0 0 18 17"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14.2709 9.07623V6.76825C14.2709 6.46219 14.1493 6.16867 13.9329 5.95225C13.7165 5.73584 13.423 5.61426 13.1169 5.61426H2.15399C1.84793 5.61426 1.55441 5.73584 1.338 5.95225C1.12158 6.16867 1 6.46219 1 6.76825V14.8462C1 15.1522 1.12158 15.4458 1.338 15.6622C1.55441 15.8786 1.84793 16.0002 2.15399 16.0002H13.1169C13.423 16.0002 13.7165 15.8786 13.9329 15.6622C14.1493 15.4458 14.2709 15.1522 14.2709 14.8462V11.9612"
                                        stroke="white" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M4.85352 2.7291L11.3966 1.02119C11.4702 1.00047 11.5471 0.994871 11.6229 1.00472C11.6986 1.01456 11.7716 1.03966 11.8374 1.07848C11.9032 1.11731 11.9604 1.16906 12.0056 1.23061C12.0509 1.29217 12.0832 1.36225 12.1006 1.43663L12.4237 2.7291"
                                        stroke="white" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M15.4249 9.07617H12.5399C12.2212 9.07617 11.9629 9.3345 11.9629 9.65317V11.3842C11.9629 11.7028 12.2212 11.9612 12.5399 11.9612H15.4249C15.7435 11.9612 16.0019 11.7028 16.0019 11.3842V9.65317C16.0019 9.3345 15.7435 9.07617 15.4249 9.07617Z"
                                        stroke="white" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg></span>
                            <span class="asideToolbar__item-text"><?php echo t('Кошелёк')?></span>
                        </a>

                        <a href="#" onclick="return showTokensPane()" class="asideToolbar__item" role="button">
                            <span class="asideToolbar__item-icon"><svg width="17" height="17" viewBox="0 0 17 17"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15.4243 4.46191H9.65505C9.33642 4.46191 9.07812 4.72021 9.07812 5.03884V9.0773C9.07812 9.39592 9.33642 9.65422 9.65505 9.65422H15.4243C15.7429 9.65422 16.0012 9.39592 16.0012 9.0773V5.03884C16.0012 4.72021 15.7429 4.46191 15.4243 4.46191Z"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M10.2324 4.46154V3.30769C10.2324 2.69565 10.4756 2.10868 10.9083 1.67591C11.3411 1.24313 11.9281 1 12.5401 1C13.1522 1 13.7391 1.24313 14.1719 1.67591C14.6047 2.10868 14.8478 2.69565 14.8478 3.30769V4.46154"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M1 10.2305H4.46154C4.84035 10.2305 5.21546 10.3051 5.56543 10.45C5.91541 10.595 6.23341 10.8075 6.50127 11.0754C6.76913 11.3432 6.98161 11.6612 7.12658 12.0112C7.27154 12.3612 7.34615 12.7363 7.34615 13.1151V13.1151"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M3.88462 13.1152H9.65385C10.2659 13.1152 10.8529 13.3584 11.2856 13.7911C11.7184 14.2239 11.9615 14.8109 11.9615 15.4229C11.9615 15.5759 11.9008 15.7227 11.7926 15.8309C11.6844 15.9391 11.5376 15.9998 11.3846 15.9998H1"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg> </span>
                            <span class="asideToolbar__item-text"><?php echo t('Токены')?></span>
                        </a>

                        <a href="https://tmyscan.com" target="_blank" class="asideToolbar__item" role="button">
                            <span class="asideToolbar__item-icon"><svg width="12" height="12" viewBox="0 0 12 12"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.0001 8.69238V10.2308C11.0001 10.4349 10.919 10.6305 10.7748 10.7748C10.6305 10.919 10.4349 11.0001 10.2308 11.0001H8.69238"
                                        stroke="#B2B2B2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M8.69238 1H10.2308C10.4349 1 10.6305 1.08104 10.7748 1.2253C10.919 1.36956 11.0001 1.56522 11.0001 1.76923V3.30769"
                                        stroke="#B2B2B2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M1 3.30769V1.76923C1 1.56522 1.08104 1.36956 1.2253 1.2253C1.36956 1.08104 1.56522 1 1.76923 1H3.30769"
                                        stroke="#B2B2B2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M3.30769 11.0001H1.76923C1.56522 11.0001 1.36956 10.919 1.2253 10.7748C1.08104 10.6305 1 10.4349 1 10.2308V8.69238"
                                        stroke="#B2B2B2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M9.46116 6.00008C9.46116 6.00008 7.9227 8.30777 5.99962 8.30777C4.07655 8.30777 2.53809 6.00008 2.53809 6.00008C2.53809 6.00008 4.07655 3.69238 5.99962 3.69238C7.9227 3.69238 9.46116 6.00008 9.46116 6.00008Z"
                                        stroke="#B2B2B2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M5.99985 6.38447C6.21227 6.38447 6.38447 6.21227 6.38447 5.99985C6.38447 5.78743 6.21227 5.61523 5.99985 5.61523C5.78743 5.61523 5.61523 5.78743 5.61523 5.99985C5.61523 6.21227 5.78743 6.38447 5.99985 6.38447Z"
                                        stroke="#B2B2B2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg> </span>
                            <span class="asideToolbar__item-text"><?php echo t('Scanner')?></span>
                        </a>
                        <a target="_blank" href="https://tmyswap.org" class="asideToolbar__item" role="button">
                            <span class="asideToolbar__item-icon"><svg width="17" height="17" viewBox="0 0 17 17"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.5385 1H4.46154C2.54978 1 1 2.54978 1 4.46154V12.5385C1 14.4502 2.54978 16 4.46154 16H12.5385C14.4502 16 16 14.4502 16 12.5385V4.46154C16 2.54978 14.4502 1 12.5385 1Z"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M5.03906 9.65422L10.2314 4.46191H6.19291" stroke="#6D00F3" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M11.9618 7.3457L6.76953 12.538H10.808" stroke="#6D00F3" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg> </span>
                            <span class="asideToolbar__item-text">Dex</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane active" id="common" role="tabpanel" aria-labelledby="common-tab">

                    <div class="userFinanceBox">
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="BalanceBox">
                                    <div class="BalanceBox-amount" data-text="Доступно средств"><?php echo t('Баланс')?>: <span
                                            id="balance"></span>
                                    </div>
                                    <div class="BalanceBox-actions">
                                        <a href="#" class="depositLink"><?php echo t('Пополнить')?></a>
                                        <a href="#" class="exchangeLink"><?php echo t('Обменять')?></a>
                                        <a href="#" class="convertLink"><?php echo t('Конвертировать')?></a>
                                    </div>
                                </div>
                                <div class="securityBox d-sm-flex align-items-center">
                                    <svg class="iconEl shield-icon">
                                        <use xlink:href="/assets/images/sprite.svg#shield"></use>
                                    </svg>
                                    <div class="securityBox-text">
                                        <div class="securityBox-text-heading"><?php echo t('Ваши активы защищены')?></div>
                                        <div class="securityBox-text-caption"><?php echo t('Web-кошелек не хранит ваши приватные ключи. Все данные надежно защищены в ваших кошельках, через которых происходит подключение.')?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 d-flex">
                                <div class="scannerBox">
                                    <div class="scannerBox-scannerInfo">
                                        <div class="scanner-info fshrink0">

                                            <div class="iconEl scanner-icon d-sm-block d-none">
                                                <div title="click to show qr code" class="text-center align-middle">
                                                    <i onclick="$('#modalqr').show()"
                                                        class="fa-solid fa-qrcode fa-3x"></i>
                                                </div>
                                            </div>
                                            <div class="iconEl scanner-icon d-sm-none d-block">
                                                <div title="click to show qr code" class="text-center align-middle">
                                                    <i onclick="$('#modalqr').show()"
                                                        class="fa-solid fa-qrcode fa-3x"></i>
                                                </div>
                                            </div>

                                        </div>
                                        <div
                                            class="scannerBox-scannerInfo-addressArea d-flex justify-content-center flex-column">
                                            <div class="d-flex flex-column">
                                                <span class="scannerBox-scannerInfo-label"><?php echo t('Принять TMY')?>
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M8.25068 1.64299H6.76555V4.8509L7.51561 5.30867L8.24917 4.8509V1.64299H8.25068ZM0 0V1.64299H15.0012V0H0Z"
                                                            fill="#474C5F" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M7.50061 6.70567L13.6406 2.83594H15.0012V15.0005H13.6406V4.7433L7.50061 8.61303L1.40712 4.7433V15.0005H0V2.83594H1.36811L7.50061 6.70567Z"
                                                            fill="#474C5F" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M7.50964 10.582L12.4991 7.37588L12.5096 9.27614L8.2552 12.0086V15H6.77158V12.0139L2.49023 9.26372L2.50224 7.36523L7.50964 10.582Z"
                                                            fill="#474C5F" />
                                                    </svg> </span>
                                                <div class="d-flex">
                                                    <span id="account" class="scannerBox-scannerInfo-address"></span>
                                                    <input type="text" class="hide" id="account_copy">
                                                    <a title="<?php echo t('click to copy address')?>" id="copyto" href="javascript:;"
                                                        class="btnType dupBtn"><svg
                                                            class="iconEl duplicate-icon fshrink0 ml-4">
                                                            <use xlink:href="/assets/images/sprite.svg#duplicate"></use>
                                                        </svg> </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form autocomplete="off" id="sendtmy" action="" class="scannerBox-inputArea">

                                        <div
                                            class="scannerBox-scannerInfo-addressArea d-flex justify-content-center flex-column">
                                            <div class="d-flex flex-column">
                                                <span class="scannerBox-scannerInfo-label"><?php echo t('Отправить TMY')?>
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M8.25068 1.64299H6.76555V4.8509L7.51561 5.30867L8.24917 4.8509V1.64299H8.25068ZM0 0V1.64299H15.0012V0H0Z"
                                                            fill="#474C5F"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M7.50061 6.70567L13.6406 2.83594H15.0012V15.0005H13.6406V4.7433L7.50061 8.61303L1.40712 4.7433V15.0005H0V2.83594H1.36811L7.50061 6.70567Z"
                                                            fill="#474C5F"></path>
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M7.50964 10.582L12.4991 7.37588L12.5096 9.27614L8.2552 12.0086V15H6.77158V12.0139L2.49023 9.26372L2.50224 7.36523L7.50964 10.582Z"
                                                            fill="#474C5F"></path>
                                                    </svg> </span>

                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="alert alert-danger hide" id="send_error" role="alert">

                                            </div>

                                            <div class="alert alert-success hide" id="send_success" role="alert">

                                            </div>
                                        </div>

                                        <div class="mb-10">
                                            <div class="scannerBox-Input">
                                                <input placeholder="0x14CB17E7038B7Cc92804bFb13Da7728161c26e0A"
                                                    type="text" id="send_address">
                                            </div>
                                        </div>

                                        <div class="mb-10">
                                            <div class="scannerBox-Input">
                                                <svg class="logoIcon" width="15" height="15" viewBox="0 0 15 15"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M8.25068 1.64299H6.76555V4.8509L7.51561 5.30867L8.24917 4.8509V1.64299H8.25068ZM0 0V1.64299H15.0012V0H0Z"
                                                        fill="#474C5F" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M7.50061 6.70567L13.6406 2.83594H15.0012V15.0005H13.6406V4.7433L7.50061 8.61303L1.40712 4.7433V15.0005H0V2.83594H1.36811L7.50061 6.70567Z"
                                                        fill="#474C5F" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M7.50964 10.582L12.4991 7.37588L12.5096 9.27614L8.2552 12.0086V15H6.77158V12.0139L2.49023 9.26372L2.50224 7.36523L7.50964 10.582Z"
                                                        fill="#474C5F" />
                                                </svg>
                                                <!--<svg onclick="$('#modalqr').show()" class="iconEl scanner-icon">
                                                    <use xlink:href="/assets/images/sprite.svg#scanner-sm"></use>
                                                </svg>-->
                                                <input placeholder="0.00" id="send_amount" type="number">
                                            </div>
                                        </div>

                                        <div class="mb-10">
                                            <button style="width:100%" type="button" id="sendtmy_btn"
                                                class="btn btn-primary btn-lg px-4 btnEl"><?php echo t('Отправить')?></button>
                                        </div>
                                    </form>
                                    <div class="scannerTransactionInfo d-lg-flex justify-content-between">
                                        <span><?php echo t('Последний блок')?>: <span id="BlockNumber"></span></span>
                                        <span><?php echo t('Комиссия')?>: <span id="gasprice"></span> <svg width="9" height="9"
                                                viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M4.95041 0.985797H4.05933V2.91054L4.50937 3.1852L4.9495 2.91054V0.985797H4.95041ZM0 0V0.985797H9.00074V0H0Z"
                                                    fill="#B2B2B2" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M4.50037 4.02301L8.18437 1.70117H9.00074V8.9999H8.18437V2.84559L4.50037 5.16743L0.844269 2.84559V8.9999H0V1.70117H0.820867L4.50037 4.02301Z"
                                                    fill="#B2B2B2" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M4.50579 6.34902L7.49943 4.42533L7.50573 5.56549L4.95312 7.20494V8.99981H4.06295V7.20813L1.49414 5.55804L1.50134 4.41895L4.50579 6.34902Z"
                                                    fill="#B2B2B2" />
                                            </svg></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="asideToolbar asideToolbar-actions mobile-only">
                        <a href="#" class="asideToolbar__item" role="button">
                            <span class="asideToolbar__item-icon"><svg width="17" height="17" viewBox="0 0 17 17"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.8085 9.08145C13.676 9.08145 16.0007 8.04897 16.0007 6.77534C16.0007 5.50172 13.676 4.46924 10.8085 4.46924C7.94086 4.46924 5.61621 5.50172 5.61621 6.77534C5.61621 8.04897 7.94086 9.08145 10.8085 9.08145Z"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M5.61621 6.77539V13.6937C5.61621 14.9621 7.92387 15.9998 10.8085 15.9998C13.693 15.9998 16.0007 14.9621 16.0007 13.6937V6.77539"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M16.0007 10.2344C16.0007 11.5027 13.693 12.5405 10.8085 12.5405C7.92387 12.5405 5.61621 11.5027 5.61621 10.2344"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M10.6922 2.16317C9.34392 1.33168 7.77452 0.92954 6.19224 1.01012C3.3192 1.01012 1 2.04787 1 3.31623C1 3.99653 1.66922 4.60764 2.73075 5.0458"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M2.73075 11.9638C1.66922 11.5257 1 10.9145 1 10.2342V3.31592"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M2.73075 8.50497C1.66922 8.06681 1 7.45569 1 6.77539" stroke="#6D00F3"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg></span>
                            <span class="asideToolbar__item-text"><?php echo t('Пополнить')?></span>
                        </a>
                        <a href="#" class="asideToolbar__item" role="button">
                            <span class="asideToolbar__item-icon"><svg width="17" height="17" viewBox="0 0 17 17"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.5385 1H4.46154C2.54978 1 1 2.54978 1 4.46154V12.5385C1 14.4502 2.54978 16 4.46154 16H12.5385C14.4502 16 16 14.4502 16 12.5385V4.46154C16 2.54978 14.4502 1 12.5385 1Z"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M5.03906 9.65422L10.2314 4.46191H6.19291" stroke="#6D00F3" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M11.9618 7.3457L6.76953 12.538H10.808" stroke="#6D00F3" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg> </span>
                            <span class="asideToolbar__item-text"><?php echo t('Обменять')?></span>
                        </a>
                        <a href="#" class="asideToolbar__item" role="button">
                            <span class="asideToolbar__item-icon"><svg width="17" height="17" viewBox="0 0 17 17"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.2308 1H2.15385C1.84783 1 1.55434 1.12157 1.33795 1.33795C1.12157 1.55434 1 1.84783 1 2.15385V14.8462C1 15.1522 1.12157 15.4457 1.33795 15.662C1.55434 15.8784 1.84783 16 2.15385 16H14.8462C15.1522 16 15.4457 15.8784 15.662 15.662C15.8784 15.4457 16 15.1522 16 14.8462V6.76923L10.2308 1Z"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M10.2305 6.19231V1L15.9997 6.76923H10.8074C10.6544 6.76923 10.5076 6.70845 10.3994 6.60025C10.2913 6.49206 10.2305 6.34532 10.2305 6.19231Z"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M5.61523 5.61553V3.88477" stroke="#6D00F3" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M3.88477 10.2306C3.88477 11.096 4.65784 11.3845 5.61554 11.3845C6.57323 11.3845 7.3463 11.3845 7.3463 10.2306C7.3463 8.49985 3.88477 8.49985 3.88477 6.76908C3.88477 5.61523 4.65784 5.61523 5.61554 5.61523C6.57323 5.61523 7.3463 6.0537 7.3463 6.76908"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M5.61523 11.3848V13.1155" stroke="#6D00F3" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.2305 11.3848H13.692" stroke="#6D00F3" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg></span>
                            <span class="asideToolbar__item-text"><?php echo t('Конвертировать')?></span>
                        </a>
                        <a href="#" class="asideToolbar__item" role="button">
                            <span class="asideToolbar__item-icon"><svg width="15" height="14" viewBox="0 0 15 14"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5.5 5.5C6.74264 5.5 7.75 4.49264 7.75 3.25C7.75 2.00736 6.74264 1 5.5 1C4.25736 1 3.25 2.00736 3.25 3.25C3.25 4.49264 4.25736 5.5 5.5 5.5Z"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M10 13H1V12C1 10.8065 1.47411 9.66193 2.31802 8.81802C3.16193 7.97411 4.30653 7.5 5.5 7.5C6.69347 7.5 7.83807 7.97411 8.68198 8.81802C9.52589 9.66193 10 10.8065 10 12V13Z"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M9.5 1C10.0967 1 10.669 1.23705 11.091 1.65901C11.5129 2.08097 11.75 2.65326 11.75 3.25C11.75 3.84674 11.5129 4.41903 11.091 4.84099C10.669 5.26295 10.0967 5.5 9.5 5.5"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M11.0996 7.68994C11.9514 8.01399 12.6848 8.58905 13.2026 9.33903C13.7205 10.089 13.9984 10.9786 13.9996 11.8899V12.9999H12.4996"
                                        stroke="#6D00F3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg> </span>
                            <span class="asideToolbar__item-text"><?php echo t('Перевести')?></span>
                        </a>
                    </div>
                    <div class="tableEl-wrapper tableEl-shadows">
                        <div class="tableEl-pre-heading d-flex justify-content-between align-items-center">
                            <div class="tableEl-pre-heading__title"><?php echo t('История транзакций')?></div>
                            <label for="search" class="searchInput">
                                <svg class="iconEl search-icon">
                                    <use xlink:href="/assets/images/sprite.svg#search"></use>
                                </svg>
                                <input type="text" id="search" placeholder="<?php echo t('Введите запрос и нажмите Enter...')?>">
                            </label>
                        </div>

                        <div class="tableEl-header tableEl-header-template hide">
                            <div class="tableEl-header-col"><?php echo t('ДАТА / ВРЕМЯ')?></div>
                            <div class="tableEl-header-col"><?php echo t('ОТПРАВИТЕЛЬ / ПОЛУЧАТЕЛЬ')?></div>
                            <div class="tableEl-header-col"><?php echo t('ТИП ТРАНЗАКЦИИ')?></div>
                            <div class="tableEl-header-col"><?php echo t('СУММА')?></div>
                            <div class="tableEl-header-col"><?php echo t('КОМИССИЯ')?></div>
                        </div>

                        <div id="history" class="tableEl tableEl-center hideFristInRow">




                            <div class="tableEl-row text-center">

                                <div class="tableEl-row-cell">

                                </div>

                                <div class="tableEl-row-cell text-xl-right text-lg-right text-md-center text-sm-center">
                                    <i class="fa-solid fa-gear fa-lg fa-spin"></i>
                                </div>
                                <div class="tableEl-row-cell">
                                </div>

                                <div class="tableEl-row-cell">

                                </div>

                                <div class="tableEl-row-cell">

                                </div>
                            </div>



                        </div>

                        <div class="hide tableEl-row history-item hide history-template ">
                            <div class="history-item-date tableEl-row-cell dateTime data-cell-header-hide"
                                data-cell-header="ДАТА / ВРЕМЯ">
                                <a href="" target="_blank"></a>
                            </div>
                            <div class="tableEl-row-cell trAddress wlAddress data-cell-header-hide"
                                data-cell-header="ОТПРАВИТЕЛЬ / ПОЛУЧАТЕЛЬ">
                                <div class="d-inline-flex address-container align-items-center">
                                    <span class="transAddress overflow-str history-item-address">
                                        <span class="addr-full hide"></span><span class="addr-cut"></span>
                                    </span>
                                    <a onclick="return copyAddress(this)" href="javascript:;" role="button"
                                        class="tableBtn btnType duplicate-btn"><i class="duplicate-icon"></i></a>
                                </div>
                            </div>
                            <div class="tableEl-row-cell transType data-cell-header-hide"
                                data-cell-header="ТИП ТРАНЗАКЦИИ">
                                <span class="history-type taType">Out</span>
                            </div>
                            <div class="tbSeperator"></div>
                            <div class="tableEl-row-cell sumCol" data-cell-header="СУММА">
                                <span class="history-item-amount"></span>&nbsp;TMY <svg width="9" height="9"
                                    viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.95041 0.985797H4.05933V2.91054L4.50937 3.1852L4.9495 2.91054V0.985797H4.95041ZM0 0V0.985797H9.00074V0H0Z"
                                        fill="#474C5F" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.50037 4.02301L8.18437 1.70117H9.00074V8.9999H8.18437V2.84559L4.50037 5.16743L0.844269 2.84559V8.9999H0V1.70117H0.820867L4.50037 4.02301Z"
                                        fill="#474C5F" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.50579 6.34902L7.49943 4.42533L7.50573 5.56549L4.95312 7.20494V8.99981H4.06295V7.20813L1.49414 5.55804L1.50134 4.41895L4.50579 6.34902Z"
                                        fill="#474C5F" />
                                </svg>
                            </div>
                            <div class="tableEl-row-cell feeCol mb-align-right" data-cell-header="КОМИССИЯ">
                                <span class="history-item-fee"></span>&nbsp;TMY <svg width="9" height="9"
                                    viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.95041 0.985797H4.05933V2.91054L4.50937 3.1852L4.9495 2.91054V0.985797H4.95041ZM0 0V0.985797H9.00074V0H0Z"
                                        fill="#474C5F" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.50037 4.02301L8.18437 1.70117H9.00074V8.9999H8.18437V2.84559L4.50037 5.16743L0.844269 2.84559V8.9999H0V1.70117H0.820867L4.50037 4.02301Z"
                                        fill="#474C5F" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.50579 6.34902L7.49943 4.42533L7.50573 5.56549L4.95312 7.20494V8.99981H4.06295V7.20813L1.49414 5.55804L1.50134 4.41895L4.50579 6.34902Z"
                                        fill="#474C5F" />
                                </svg>
                            </div>
                        </div>

                        <div class="tableEl-footer-filters d-flex justify-content-between align-items-center">
                            <!--<div class="tableEl-footer-filters_paging d-lg-flex align-items-center">
                        <span>Отобразить по:</span>
                        <ul class="tableEl-footer-filters_paging-pages d-flex">
                            <li class="acitvePage"><a href="#">10</a></li>
                            <li><a href="#">20</a></li>
                            <li><a href="#">50</a></li>
                            <li><a href="#">70</a></li>
                            <li><a href="#">100</a></li>
                            <li><a href="#">500</a></li>
                        </ul>
                    </div>
                    <div class="tableEl-filter-items">
                        <a href="#" class="filter-btn"><svg class="iconEl filter-icon">
                                <use xlink:href="/assets/images/sprite.svg#filter"></use>
                            </svg> Фильтр</a>
                        <a href="" class="filter-rep">Расширенный отчёт</a>
                    </div>-->
                            <div class="tableEl-footer-filters_paging d-lg-flex align-items-center"
                                style="margin-left: auto;">
                                <span><?php echo t('Страницы')?></span>
                                <ul id="history_pager" class="tableEl-footer-filters_paging-pages d-flex">

                                </ul>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="tab-pane" id="tokens" role="tabpanel" aria-labelledby="tokens-tab">


                    <div class="userFinanceBox">
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="TokenBox">

                                    <div class="tableEl-pre-heading d-flex justify-content-between align-items-center">
                                        <div class="tableEl-pre-heading__title"><?php echo t('Баланс токенов')?></div>
                                    </div>

                                    <div id="tokenlist" class="mb-10">

                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6 col-12 d-flex">
                                <div class="scannerBox">
                                    <form id="sendtmy_tokens">

                                        <div
                                            class="tableEl-pre-heading d-flex justify-content-between align-items-center">
                                            <div class="tableEl-pre-heading__title"><?php echo t('Отправить токены')?></div>
                                        </div>


                                        <div class="col-12">
                                            <div class="alert alert-danger hide" id="send_token_error" role="alert">

                                            </div>

                                            <div class="alert alert-success hide" id="send_token_success" role="alert">

                                            </div>
                                        </div>


                                        <div class="col-12 mb-10">
                                            <input id="sendtoken_address" type="text" class="form-control"
                                                placeholder="0x14CB17E7038B7Cc92804bFb13Da7728161c26e0A">
                                        </div>

                                        <div class="col-12 mb-10">

                                            <input id="sendtoken_amount" type="text" class="form-control"
                                                aria-label="Text input with dropdown button" placeholder="0.0">
                                        </div>



                                        <div class="col-12 mb-10">
                                            <select id="sendtoken_list" class="form-select">
                                                <option value="-1"><?php echo t('Выбрать токен')?></option>
                                            </select>
                                        </div>

                                        <div class="col-12 mb-10">
                                            <button style="width:100%" id="sendtokens" type="button"
                                                class="btn btn-primary btn-lg px-4 btnEl"><?php echo t('Отправить токены')?></button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="tableEl-wrapper tableEl-shadows">
                        <div class="tableEl-pre-heading d-flex justify-content-between align-items-center">
                            <div class="tableEl-pre-heading__title"><?php echo t('История')?></div>
                            <label for="search" class="searchInput">
                                <svg class="iconEl search-icon">
                                    <use xlink:href="/assets/images/sprite.svg#search"></use>
                                </svg>
                                <input type="text" id="search" placeholder="<?php echo t('Введите запрос и нажмите Enter...')?>">
                            </label>
                        </div>

                        <div class="tableEl-header tokenhistory-header-template hide">
                            <div class="tableEl-header-col"><?php echo t('ДАТА / ВРЕМЯ')?></div>
                            <div class="tableEl-header-col"><?php echo t('ОТПРАВИТЕЛЬ')?></div>
                            <div class="tableEl-header-col"><?php echo t('ПОЛУЧАТЕЛЬ')?></div>
                            <div class="tableEl-header-col"><?php echo t('СУММА')?></div>
                            <div class="tableEl-header-col"><?php echo t('КОМИССИЯ')?></div>
                        </div>

                        <div id="tokenhistory" class="tableEl tableEl-Beetween hideFristInRow">


                            <div class="text-center">
                                <i class="fa-solid fa-gear fa-lg fa-spin"></i>
                            </div>

                        </div>


                        <div class="history-item hide historytoken-template tableEl-row convertRow">

                            <div class="history-item-date tableEl-row-cell dateTime data-cell-header-hide"
                                data-cell-header="ДАТА / ВРЕМЯ">
                                <a href="" target="_blank"></a>
                            </div>
                            <div class="history-item-address tableEl-row-cell trAddressFromTo wlAddress purpleCol data-cell-header-hide"
                                data-cell-header="ОТПРАВИТЕЛЬ">
                                <div class="d-flex address-container align-items-center">
                                    <span class="transAddress history-item-source"><span
                                            class="addr-full hide"></span><span class="addr-cut"></span></span>
                                    <a onclick="return copyAddress(this)" href="javascript:;" role="button"
                                        class="tableBtn btnType duplicate-btn">
                                        <i class="duplicate-icon"></i></a>
                                    <a onclick="return showFullAddress(this)" href="javascript:;" role="button"
                                        class="tableBtn btnType view-btn">
                                        <i class="view-icon"></i></a>
                                </div>
                            </div>
                            <div class="tableEl-row-cell trAddressFromTo wlAddress purpleCol data-cell-header-hide"
                                data-cell-header="ПОЛУЧАТЕЛЬ">
                                <div class="d-flex address-container align-items-center">
                                    <span class="transAddress history-item-destination"><span
                                            class="addr-full hide"></span><span class="addr-cut"></span></span>
                                    <a onclick="return copyAddress(this)" href="javascript:;" role="button"
                                        class="tableBtn btnType duplicate-btn">
                                        <i class="duplicate-icon"></i></a>
                                    <a onclick="return showFullAddress(this)" href="javascript:;" role="button"
                                        class="tableBtn btnType view-btn">
                                        <i class="view-icon"></i></a>
                                </div>
                            </div>
                            <div class="tbSeperator"></div>
                            <div class="history-item-amount tableEl-row-cell sumCol" data-cell-header="СУММА">

                            </div>
                            <div class="tableEl-row-cell mb-align-right" data-cell-header="КОМИССИЯ">
                                <span class="history-item-fee"></span>&nbsp;TMY <svg width="9" height="9"
                                    viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.95041 0.985797H4.05933V2.91054L4.50937 3.1852L4.9495 2.91054V0.985797H4.95041ZM0 0V0.985797H9.00074V0H0Z"
                                        fill="#474C5F"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.50037 4.02301L8.18437 1.70117H9.00074V8.9999H8.18437V2.84559L4.50037 5.16743L0.844269 2.84559V8.9999H0V1.70117H0.820867L4.50037 4.02301Z"
                                        fill="#474C5F"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.50579 6.34902L7.49943 4.42533L7.50573 5.56549L4.95312 7.20494V8.99981H4.06295V7.20813L1.49414 5.55804L1.50134 4.41895L4.50579 6.34902Z"
                                        fill="#474C5F"></path>
                                </svg>
                            </div>
                        </div>


                        <div class="tableEl-footer-filters d-flex justify-content-between align-items-center">


                            <div class="tableEl-footer-filters_paging d-lg-flex align-items-center"
                                style="margin-left: auto;">
                                <span><?php echo t('Страницы')?></span>
                                <ul id="tokenhistory_pager" class="tableEl-footer-filters_paging-pages d-flex">

                                </ul>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
    <!-- SCRIPTS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <script src="https://unpkg.com/web3@latest/dist/web3.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/web3modal"></script>
    <script type="text/javascript" src="https://unpkg.com/@walletconnect/web3-provider"></script>
    <script type="text/javascript" src="https://unpkg.com/fortmatic@2.0.6/dist/fortmatic.js"></script>
    <script type='module' src="../js/walletconnect.js?r=22" defer></script>
    <script src="assets/js/common.js?r=10"></script>

    <div class="modal" id="modalqr" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo t('Receive TMY')?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick='$("#modalqr").hide()'
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>
                        <img src="" id="qr_image" width="300px">
                    </p>

                </div>
            </div>
        </div>
    </div>
</body>

</html>