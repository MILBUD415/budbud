<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Moja Sprzedaż</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 0; }
    .container-main { display: flex; }
    .sidebar {
      width: 220px;
      background: #252c39; /* Ujednolicony kolor */
      height: 100vh;
      color: #fff;
      padding: 0;
      position: fixed; left: 0; top: 0; bottom: 0;
      display: flex; flex-direction: column; align-items: flex-start;
      box-shadow: 2px 0 12px #0001;
      z-index: 50;
    }
    .sidebar .menu { list-style: none; padding: 0; margin: 0; width: 100%; }
    .sidebar .menu li {
      padding: 15px 30px;
      cursor: pointer;
      width: 100%; box-sizing: border-box;
      transition: background 0.15s;
      font-size: 1.05em;
      border-left: 4px solid transparent;
    }
    .sidebar .menu li.active, .sidebar .menu li:hover {
      background: #1e2430;
      color: #fff;
      font-weight: bold;
      border-left: 4px solid #2196f3;
    }
    .sidebar .menu li:not(.active):hover { background: #1e2430; }
    .sidebar .menu li.logout { margin-top: 24px; color: #bbb; padding: 0;}
    .sidebar .menu li.logout a { color: #ff8787; text-decoration: none; display: block; width: 100%; padding: 15px 30px; }
    .container-content {
      flex: 1;
      padding: 32px 0 32px 240px;
      min-height: 100vh;
      background: #f5f6fa;
    }
    .header-bar {
      display: flex;
      align-items: center;
      gap: 18px;
      margin-bottom: 24px;
      margin-top: 8px;
      margin-left: 16px;
    }
    .header-logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-right: 0;
    }
    .header-logo img {
      height: 58px;
      width: auto;
      display: block;
    }
    .header-logo-text {
      font-size: 1.22em;
      font-weight: bold;
      margin-top: 2px;
      letter-spacing: -1px;
      color: #191919;
      font-family: Arial, Helvetica, sans-serif;
    }
    .header-title {
      font-size: 2em;
      font-weight: 600;
      color: #191919;
      letter-spacing: -1px;
      margin-left: 20px;
      font-family: Arial, Helvetica, sans-serif;
      margin-top: 5px;
    }
    .add-ad-section, .ad-item {
      background: white; border: 1px solid #ddd; border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.08); padding: 20px;
      display: flex; align-items: center; justify-content: flex-start; margin-bottom: 24px;
      transition: box-shadow 0.1s;
    }
    .add-ad-section { cursor: pointer; gap: 14px; }
    .add-ad-section:hover { background-color: #fafafa; box-shadow: 0 6px 12px #ccc3; }
    .custom-image, .remove-ad-button {
      width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center;
      justify-content: center; font-size: 24px; color: white; font-weight: bold;
    }
    .custom-image { background-color: #4CAF50; flex-shrink: 0;}
    .add-ad-label {
      font-weight: bold;
      font-size: 1.22em;
      color: #222;
      margin-left: 4px;
    }
    .remove-ad-button {
      background-color: #f44336; position: relative; cursor: pointer; margin-right: 18px;
      transition: background 0.18s;
    }
    .remove-ad-button:hover { background: #c62828; }
    .remove-ad-button:hover::after {
      content: "Usuń ogłoszenie"; position: absolute; bottom: -30px; left: 50%;
      transform: translateX(-50%);
      background: white; color: black; padding: 5px 10px; border: 1px solid #ddd;
      border-radius: 5px; font-size: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      white-space: nowrap;
    }
    .ad-title-link { color: #222; text-decoration: none; font-size: 1.18em; }
    .ad-title-link:hover { text-decoration: underline; }
    .ad-image-thumb { width: 60px; height: 60px; border-radius: 8px; object-fit: cover; margin-right: 18px; border: 1px solid #ddd; }
    .ad-content { display: flex; align-items: center; flex: 1; gap: 16px; }
    @media (max-width: 900px) {
      .container-content { padding-left: 0; }
      .sidebar { display: none; }
    }
  </style>
</head>
<body>
  <div class="container-main">
<nav class="sidebar">
  <ul class="menu">
    <li onclick="window.location.href='panel-klienta.php'">Panel Klienta</li>
    <li class="active" onclick="window.location.href='moje-ogloszenia.php'">Moje Ogłoszenia Budowlane</li>
    <li onclick="window.location.href='moja-sprzedaz.php'">Sprzedaż</li>
    <li onclick="window.location.href='nieruchomosci.php'">Nieruchomości</li>
    <li onclick="window.location.href='moj-profil.php'">Mój Profil</li>
    <li onclick="window.location.href='fachowcy.php'">Fachowcy</li>
    <li onclick="window.location.href='poczta.php'">Poczta</li>
    <li onclick="window.location.href='kalkulator.php'">Kalkulator</li>
    <li onclick="window.location.href='promocje.php'">Promocje</li>
    <li onclick="window.location.href='pomoc.php'">Pomoc</li>
    <li class="logout"><a href="logout.php">Wyloguj się</a></li>
  </ul>
</nav>
    <div class="container-content">
      <!-- NAGŁÓWEK -->
      <div class="header-bar">
        <span class="header-logo">
          <img src="assets/tools-icon-login.png" alt="BudBud logo">
          <span class="header-logo-text">BudBud</span>
        </span>
        <span class="header-title">Moja Sprzedaż</span>
      </div>
      <!-- /NAGŁÓWEK -->

      <div class="add-ad-section" onclick="window.location.href='sprzedaz-form.html'">
        <div class="custom-image">+</div>
        <span class="add-ad-label">Dodaj Darmowe Ogłoszenie</span>
      </div>
      <div id="adsContainer"></div>
    </div>
  </div>
  <script>
    function renderAds() {
      const adsContainer = document.getElementById('adsContainer');
      adsContainer.innerHTML = '';
      const ads = JSON.parse(localStorage.getItem('sprzedaz') || '[]');
      if (!ads.length) {
        adsContainer.innerHTML = "<div style='text-align:center; color:#888; margin-top:40px;'>Brak ogłoszeń.</div>"
        return;
      }
      ads.slice().reverse().forEach((ad) => {
        const adItem = document.createElement('div');
        adItem.className = 'ad-item';
        adItem.innerHTML = `
          <div class="remove-ad-button" onclick="removeAd(${ad.id})">-</div>
          <div class="ad-content">
            <img class="ad-image-thumb" src="${ad.images && ad.images[0] ? ad.images[0] : 'https://via.placeholder.com/60x60?text=Foto'}" alt="miniaturka">
            <a class="ad-title-link" href="podglad-sprzedaz.html?id=${ad.id}">
              ${ad.title || '(Bez tytułu)'}
            </a>
          </div>
        `;
        adsContainer.appendChild(adItem);
      });
    }
    function removeAd(id) {
      let ads = JSON.parse(localStorage.getItem('sprzedaz')) || [];
      ads = ads.filter(ad => ad.id !== id);
      localStorage.setItem('sprzedaz', JSON.stringify(ads));
      renderAds();
    }
    window.onload = renderAds;
  </script>
</body>
</html>