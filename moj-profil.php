<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login-client.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Mój Profil | BudBud</title>
    <meta name="viewport" content="width=1100, initial-scale=1.0">
    <link rel="icon" href="assets/tools-icon-login.png" type="image/png">
    <style>
        body {
            margin: 0;
            background: #f7f8fa;
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
        }
        .container-main {
            display: flex;
            min-height: 100vh;
            background: #f7f8fa;
        }
        .sidebar {
            width: 230px;
            background: #1a2332;
            color: #fff;
            padding-top: 34px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .sidebar-menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .sidebar-menu li {
            padding: 13px 32px;
            font-size: 1.09em;
            cursor: pointer;
            border-left: 4px solid transparent;
            transition: background 0.18s, color 0.18s, border-color 0.18s;
        }
        .sidebar-menu li.active,
        .sidebar-menu li:hover {
            background: #242e42;
            color: #33aaff;
            border-left: 4px solid #33aaff;
        }
        .sidebar-menu li.logout {
            color: #ff7276;
            font-weight: bold;
            margin-top: 40px;
        }
        .sidebar-menu li.logout:hover {
            background: none;
            color: #e11;
            border-left: 4px solid transparent;
        }
        .main-content {
            flex: 1;
            padding: 0 0 0 0;
            min-width: 0;
        }
        .header-profile {
            display: flex;
            align-items: center;
            margin: 44px 0 35px 0;
            padding-left: 54px;
        }
        .header-profile img {
            width: 54px;
            height: 54px;
            margin-right: 20px;
        }
        .header-profile-title {
            font-size: 2em;
            font-weight: bold;
            color: #181818;
            letter-spacing: -1px;
        }
        .profile-actions {
            display: flex;
            gap: 32px;
            padding-left: 54px;
            margin-bottom: 44px;
        }
        .profile-action {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 8px #0001;
            padding: 34px 30px 28px 30px;
            min-width: 220px;
            min-height: 120px;
            flex: 1 1 220px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            transition: box-shadow 0.17s, transform 0.16s;
            /* Dodano transition transform */
            cursor: pointer;
        }
        .profile-action:not(:last-child) { margin-right: 0px; }
        .profile-action-title {
            font-size: 1.23em;
            font-weight: bold;
            margin-bottom: 12px;
            color: #181818;
        }
        .profile-action-desc {
            font-size: 1em;
            color: #232323;
            opacity: 0.75;
        }
        .profile-action.delete {
            color: #e11;
            font-weight: bold;
            justify-content: center;
            align-items: center;
        }
        .profile-action.delete .profile-action-title {
            color: #e11;
            margin-bottom: 0;
        }
        /* Efekt powiększenia dla kwadratowych przycisków */
        .profile-action:hover {
            transform: scale(1.045);
            box-shadow: 0 4px 18px #0002;
            z-index: 1;
        }

        .profile-comments-row {
            display: flex;
            flex-direction: column;
            gap: 18px;
            padding: 0 54px;
        }
        .profile-comment-box {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px #0001;
            font-size: 1.23em;
            font-weight: bold;
            padding: 22px 34px;
            margin-bottom: 0;
            color: #188be9;
            border-left: 8px solid #188be9;
            cursor: pointer;
            transition: box-shadow 0.17s, transform 0.16s;
        }
        .profile-comment-box.orange {
            color: #ff9900;
            border-left: 8px solid #ff9900;
        }
        /* Efekt powiększenia dla podłużnych przycisków */
        .profile-comment-box:hover {
            transform: scale(1.035);
            box-shadow: 0 4px 18px #0002;
            z-index: 1;
        }
        @media (max-width: 950px) {
            .profile-actions, .profile-comments-row { padding-left: 12px; padding-right: 8px;}
            .header-profile { padding-left: 12px; }
        }
        @media (max-width: 700px) {
            .container-main { flex-direction: column; }
            .sidebar { width: 100%; min-height: unset; flex-direction: row; overflow-x: auto; }
            .main-content { padding-top: 0; }
            .sidebar-menu { display: flex; flex-direction: row; }
            .sidebar-menu li { padding: 14px 18px; font-size: 1em; }
            .profile-actions { flex-direction: column; gap: 18px; }
            .profile-action { min-width: unset; width: 100%; }
            .profile-comments-row { padding-left: 8px; padding-right: 8px;}
        }
    </style>
</head>
<body>
<div class="container-main">
    <!-- PANEL BOCZNY -->
    <nav class="sidebar">
        <ul class="sidebar-menu">
            <li onclick="window.location.href='panel-klienta.php'">Panel Klienta</li>
            <li onclick="window.location.href='moje-ogloszenia.php'">Moje Ogłoszenia Budowlane</li>
            <li onclick="window.location.href='moja-sprzedaz.php'">Sprzedaż</li>
            <li onclick="window.location.href='nieruchomosci.php'">Nieruchomości</li>
            <li class="active" onclick="window.location.href='moj-profil.php'">Mój Profil</li>
            <li onclick="window.location.href='fachowcy.php'">Fachowcy</li>
            <li onclick="window.location.href='poczta.php'">Poczta</li>
            <li onclick="window.location.href='kalkulator.php'">Kalkulator</li>
            <li onclick="window.location.href='promocje.php'">Promocje</li>
            <li onclick="window.location.href='pomoc.php'">Pomoc</li>
            <li class="logout" onclick="window.location.href='logout.php'">Wyloguj się</li>
        </ul>
    </nav>

    <!-- ZAWARTOŚĆ STRONY -->
    <div class="main-content">
        <!-- Nagłówek z logo -->
        <div class="header-profile">
            <img src="assets/tools-icon-login.png" alt="BudBud logo">
            <span class="header-profile-title">Mój Profil</span>
        </div>
        <!-- 3 białe kwadratowe przyciski -->
        <div class="profile-actions">
            <div class="profile-action" onclick="window.location.href='zmien-dane.php'">
                <div class="profile-action-title">Zmień Dane</div>
                <div class="profile-action-desc">Imię i Nazwisko<br>Dane Kontaktowe<br>Adres</div>
            </div>
            <div class="profile-action" onclick="window.location.href='zmien-haslo.php'">
                <div class="profile-action-title">Zmień hasło</div>
                <div class="profile-action-desc">Hasło logowania</div>
            </div>
            <div class="profile-action delete" onclick="window.location.href='#usun-konto'">
                <div class="profile-action-title">Usuń konto</div>
            </div>
        </div>
        <!-- 2 białe podłużne przyciski -->
        <div class="profile-comments-row">
            <div class="profile-comment-box" onclick="window.location.href='#moje-komentarze'">
                Komentarze moje
            </div>
            <div class="profile-comment-box orange" onclick="window.location.href='#komentarze-o-mnie'">
                Komentarze o mnie
            </div>
        </div>
    </div>
</div>
</body>
</html>