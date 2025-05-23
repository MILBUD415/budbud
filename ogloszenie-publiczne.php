<?php
require_once 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    echo "<h2 style='color:red;text-align:center;'>Nie znaleziono ogłoszenia.</h2>";
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM ads WHERE id=? AND is_ad=1 AND published=1");
$stmt->execute([$id]);
$ad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ad) {
    echo "<h2 style='color:red;text-align:center;'>Ogłoszenie nie istnieje lub nie jest publiczne.</h2>";
    exit;
}
$images = @json_decode($ad['images'], true);
if(empty($images)) $images = [];
$mainImage = !empty($images) ? $images[0] : 'https://via.placeholder.com/300x300?text=Brak+zdjęcia';

function googleMapEmbed($address) {
    $q = urlencode($address . ', Polska');
    return '<iframe src="https://www.google.com/maps?q='.$q.'&output=embed" width="100%" height="180" style="border:0;border-radius:8px;" loading="lazy"></iframe>';
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($ad['title']); ?> - Ogłoszenie Budowlane</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { background: #f6f7fa; margin: 0; font-family: Arial,sans-serif; }
    .header-bar {
      display: flex; 
      align-items: center;
      justify-content: space-between; 
      padding: 32px 4vw 10px 4vw;
    }
    .header-left {
      display: flex;
      align-items: center;
      gap: 24px;
    }
    .header-logo {
      display: flex; 
      flex-direction: column; 
      align-items: flex-start;
    }
    .header-logo img { height: 40px; }
    .header-logo-text { font-size: 1.1em; font-weight: bold; color: #191919; }
    .header-title { 
      font-size: 2em; 
      font-weight: bold; 
      color: #222; 
      margin-left: 0.2em;
      letter-spacing: -1px;
      white-space: nowrap;
    }
    .back-btn {
      font-size: 1.3em;
      font-weight: bold;
      border: 3px solid #2196f3;
      color: #fff;
      background: #2196f3;
      padding: 8px 24px;
      border-radius: 7px;
      text-decoration: none;
      margin-left: 18px;
      cursor: pointer;
      display: inline-block;
      transition: background 0.13s, color 0.13s;
    }
    .back-btn:hover {
      background: #1769aa;
      color: #fff;
      border-color: #1769aa;
    }
    .title-box {
      width: 100%; max-width: 900px; margin: 0 auto 24px auto;
      background: #fff; border-radius: 15px; box-shadow: 0 4px 16px #0001;
      font-size: 1.25em; font-weight: bold; padding: 18px 36px;
      display: flex; align-items: center; letter-spacing: -1px;
    }
    .main-container {
      max-width: 1200px; margin: 0 auto; display: flex; gap: 28px;
      flex-wrap: wrap; justify-content: center;
    }
    .ad-image-box, .ad-info-box { background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #0001;}
    .ad-image-box { padding: 28px 36px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-width: 320px; min-height: 320px; position: relative;}
    .ad-image-box img.main { max-width: 320px; max-height: 320px; border-radius: 12px; border: 1px solid #e6e6e6; cursor: zoom-in; }
    .gallery-thumbs {
      display: flex; gap: 12px; margin-top: 18px; flex-wrap: wrap; justify-content: center;
    }
    .gallery-thumbs img {
      width: 64px; height: 64px; object-fit: cover;
      border-radius: 7px; border: 2px solid #eee;
      cursor: pointer; transition: border 0.18s;
      background: #fff;
      cursor: zoom-in;
    }
    .gallery-thumbs img.active, .gallery-thumbs img:hover {
      border: 2px solid #2196f3;
    }
    .gallery-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255,255,255,0.8);
      border: none;
      color: #333;
      font-size: 36px;
      font-weight: bold;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      cursor: pointer;
      z-index: 10;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.15s;
      user-select: none;
    }
    .gallery-arrow.left { left: -24px; }
    .gallery-arrow.right { right: -24px; }
    .gallery-arrow:hover { background: #2196f3; color: #fff;}
    .ad-info-box { padding: 26px 32px; min-width: 340px; max-width: 450px; flex:1; display: flex; flex-direction: column; gap: 7px;}
    .ad-info-box b { font-weight: bold; }
    .ad-info-box .label { font-weight: bold; color: #111; }
    .ad-info-box .icon { margin-right:6px;}
    .ad-info-box .val { color: #222; font-weight: 500;}
    .ad-info-box .row { margin-bottom: 7px; }
    .ad-info-box .map { margin-top: 13px; }
    .desc-box {
      width: 100%; max-width: 900px; margin: 34px auto 16px auto;
      background: #fff; border-radius: 15px; box-shadow: 0 4px 16px #0001;
      font-size: 1.15em; padding: 18px 36px; 
      font-weight: 400; color: #333;
    }
    .desc-box b { font-weight: bold; }
    /* Modal arrows */
    .zoom-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 10002;
      background: rgba(255,255,255,0.7);
      border: none;
      color: #333;
      font-size: 38px;
      font-weight: bold;
      width: 56px;
      height: 56px;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.15s;
      user-select: none;
    }
    .zoom-arrow:hover { background: #2196f3; color: #fff; }
    .zoom-arrow.left { left: 18px; }
    .zoom-arrow.right { right: 18px; }
    @media (max-width: 1000px) {
      .main-container { flex-direction: column; gap: 22px;}
      .ad-image-box, .ad-info-box { min-width: 0; width: 100%; max-width: 700px; }
      .header-title { font-size: 1.3em; }
      .header-logo img { height: 32px; }
    }
    @media (max-width: 700px) {
      .header-bar { flex-direction: column; align-items: flex-start; gap: 20px; }
      .header-left { gap: 10px; }
      .gallery-arrow.left { left: 2px; }
      .gallery-arrow.right { right: 2px; }
      .zoom-arrow.left { left: 2px; }
      .zoom-arrow.right { right: 2px; }
    }
  </style>
</head>
<body>
  <div class="header-bar">
    <div class="header-left">
      <div class="header-logo">
        <img src="assets/tools-icon-login.png" alt="BudBud logo">
        <span class="header-logo-text">BudBud</span>
      </div>
      <span class="header-title">Baza Ogłoszeń budowlanych</span>
    </div>
    <a href="ogloszenia-publiczne.php" class="back-btn" id="backToForum">Powrót do forum</a>
  </div>

  <div class="title-box"><?php echo htmlspecialchars($ad['title']); ?></div>

  <div class="main-container">
    <div class="ad-image-box">
      <button type="button" class="gallery-arrow left" id="galleryPrev" style="display:<?= count($images) > 1 ? 'flex':'none' ?>;">&#8592;</button>
      <img src="<?php echo htmlspecialchars($mainImage); ?>" alt="Zdjęcie ogłoszenia" id="mainAdImg" class="main zoomable-img">
      <button type="button" class="gallery-arrow right" id="galleryNext" style="display:<?= count($images) > 1 ? 'flex':'none' ?>;">&#8594;</button>
      <?php if (count($images) > 1): ?>
        <div class="gallery-thumbs">
          <?php foreach ($images as $idx => $img): ?>
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Miniatura <?php echo $idx+1; ?>" class="<?php echo $idx==0?'active':''; ?> zoomable-img" onclick="setMainImg('<?php echo htmlspecialchars($img); ?>', this)">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="ad-info-box">
      <div class="row"><span class="icon">👤</span><span class="label">inwestor:</span> <span class="val"><?php echo htmlspecialchars($ad['investor']); ?></span></div>
      <div class="row"><span class="icon">🔌</span><span class="label">Kategoria:</span> <span class="val"><?php echo htmlspecialchars($ad['job_type']); ?></span></div>
      <div class="row"><span class="icon">☎️</span><span class="label">Kontakt:</span> <span class="val"><?php echo htmlspecialchars($ad['contact']); ?></span></div>
      <div class="row"><span class="icon">📍</span><span class="label">Adres:</span> <span class="val"><?php echo htmlspecialchars($ad['address']); ?></span></div>
      <div class="row"><span class="icon">📅</span><span class="label">Termin rozpoczęcia:</span> <span class="val"><?php echo htmlspecialchars($ad['start_date']); ?></span></div>
      <div class="row"><span class="icon">📅</span><span class="label">Termin zakończenia:</span> <span class="val"><?php echo htmlspecialchars($ad['end_date']); ?></span></div>
      <div class="row"><span class="icon">🧰</span><span class="label">Nadzór budowlany:</span> <span class="val"><?php echo htmlspecialchars($ad['supervision']); ?></span></div>
      <div class="map"><?= googleMapEmbed($ad['address']); ?></div>
    </div>
  </div>
  <div class="desc-box"><b>opis:</b> <?php echo nl2br(htmlspecialchars($ad['description'])); ?></div>
  <!-- MODAL do powiększania zdjęć -->
  <div id="imgModal" style="display:none; position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.7);z-index:9999;justify-content:center;align-items:center;">
    <button class="zoom-arrow left" id="modalPrev" style="display:none;">&#8592;</button>
    <span id="imgModalClose" style="position:absolute;top:36px;right:46px;font-size:38px;font-weight:bold;color:#fff;cursor:pointer;z-index:10001;">&times;</span>
    <img id="imgModalImg" src="" alt="Duże zdjęcie" style="max-width:90vw;max-height:88vh;box-shadow:0 0 28px #000a;background:#fff;border-radius:10px;display:block;z-index:10000;">
    <button class="zoom-arrow right" id="modalNext" style="display:none;">&#8594;</button>
  </div>
  <script>
    // Galeria miniatur - podmiana głównego zdjęcia
    let images = <?php echo json_encode($images); ?>;
    let currentImgIdx = 0;
    function setMainImg(url, thumb) {
      document.getElementById('mainAdImg').src = url;
      document.querySelectorAll('.gallery-thumbs img').forEach(function(img,i){
        img.classList.remove('active');
        if(img === thumb) currentImgIdx = i;
        if(img.src === url) currentImgIdx = i;
      });
      thumb.classList.add('active');
    }
    // Strzałki galeria
    function showGalleryArrows() {
      document.getElementById('galleryPrev').style.display = (images.length > 1) ? 'flex' : 'none';
      document.getElementById('galleryNext').style.display = (images.length > 1) ? 'flex' : 'none';
    }
    document.getElementById('galleryPrev').onclick = function(e){
      e.stopPropagation();
      if(images.length < 2) return;
      currentImgIdx = (currentImgIdx-1+images.length)%images.length;
      let url = images[currentImgIdx];
      document.getElementById('mainAdImg').src = url;
      document.querySelectorAll('.gallery-thumbs img').forEach(function(img,i){
        img.classList.toggle('active', i===currentImgIdx);
      });
    }
    document.getElementById('galleryNext').onclick = function(e){
      e.stopPropagation();
      if(images.length < 2) return;
      currentImgIdx = (currentImgIdx+1)%images.length;
      let url = images[currentImgIdx];
      document.getElementById('mainAdImg').src = url;
      document.querySelectorAll('.gallery-thumbs img').forEach(function(img,i){
        img.classList.toggle('active', i===currentImgIdx);
      });
    }
    // Powrót do forum w to samo miejsce (scroll)
    document.getElementById('backToForum').addEventListener('click', function(e) {
      e.preventDefault();
      if (sessionStorage.forumScroll) {
        window.location = this.href + '#scroll';
      } else {
        window.location = this.href;
      }
    });

    // MODAL powiększania zdjęcia + przewijanie strzałkami
    document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('imgModal');
      const modalImg = document.getElementById('imgModalImg');
      const modalClose = document.getElementById('imgModalClose');
      const modalPrev = document.getElementById('modalPrev');
      const modalNext = document.getElementById('modalNext');
      let zoomImgs = Array.from(document.querySelectorAll('.zoomable-img'));
      let zoomIdx = 0;

      function updateModalImg(idx) {
        zoomIdx = (idx + zoomImgs.length) % zoomImgs.length;
        modalImg.src = zoomImgs[zoomIdx].src;
      }
      document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('zoomable-img')) {
          zoomImgs = Array.from(document.querySelectorAll('.zoomable-img'));
          zoomIdx = zoomImgs.findIndex(img => img === e.target);
          modalImg.src = e.target.src;
          modal.style.display = 'flex';
          document.body.style.overflow='hidden';
          modalPrev.style.display = (zoomImgs.length > 1) ? 'flex' : 'none';
          modalNext.style.display = (zoomImgs.length > 1) ? 'flex' : 'none';
        }
      });
      modalPrev.onclick = function(e){ e.stopPropagation(); updateModalImg(zoomIdx-1);}
      modalNext.onclick = function(e){ e.stopPropagation(); updateModalImg(zoomIdx+1);}
      modalClose.onclick = function(){ modal.style.display='none'; document.body.style.overflow=''; };
      modal.onclick = function(e){ if(e.target===modal) { modal.style.display='none'; document.body.style.overflow=''; } };
      document.addEventListener('keydown', function(e){
        if(modal.style.display !== 'flex') return;
        if(e.key==='Escape') { modal.style.display='none'; document.body.style.overflow=''; }
        if(e.key==='ArrowLeft') updateModalImg(zoomIdx-1);
        if(e.key==='ArrowRight') updateModalImg(zoomIdx+1);
      });
    });
  </script>
</body>
</html>