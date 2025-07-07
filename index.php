<?php
// Landing page Inventarisasi Peralatan, modern dan menarik
$title = 'Beranda - Inventarisasi Peralatan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .hero-section.pkp-hero {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            background: linear-gradient(135deg, #fff 60%, #e3f0ff 100%);
            padding: 0 12px;
        }
        .invent-card {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 6px 32px #0E5C7115;
            padding: 48px 32px 40px 32px;
            max-width: 700px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .invent-card:hover {
            box-shadow: 0 12px 48px #0E5C7130;
        }
        .invent-card img {
            height: 74px;
            margin-bottom: 20px;
            filter: drop-shadow(0 2px 8px #0E5C7120);
            background: #f8f8f8;
            border-radius: 16px;
            padding: 8px 18px;
        }
        .invent-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #223468;
            line-height: 1.3;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
        }
        .invent-sub {
            font-size: 1.13rem;
            color: #0E5C71;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .invent-sub2 {
            font-size: 1.08rem;
            color:rgb(100, 95, 78);
            font-weight: 600;
            margin-bottom: 28px;
        }
        .pkp-btn-cta {
            display: inline-block;
            color: #223468;
            font-weight: 700;
            border: none;
            border-radius: 24px;
            padding: 12px 36px;
            font-size: 1.08rem;
            text-decoration: none;
            box-shadow: 0 2px 8px #0E5C7120;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            margin-top: 8px;
        }
        .pkp-btn-cta:hover {
            color: #223468;
            box-shadow: 0 4px 16px #D3C28844;
        }
        @media (max-width: 600px) {
            .invent-card {
                padding: 28px 8px 24px 8px;
            }
            .invent-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <?php include "include/Navbar.php"; ?>
    <section class="hero-section pkp-hero">
        <div class="invent-card">
            <img src="assets/images/logo.png" alt="Logo Inventarisasi">
            <h1 class="invent-title">Sistem Informasi Inventarisasi Peralatan</h1>
            <p class="invent-sub">Balai Sains Bangunan Direktorat Bina Teknik Perumahan dan Pemukiman</p>
            <p class="invent-sub2">Kementerian Pekerjaan Umum dan Perumahan Rakyat</p>
            <a href="login.php" class="pkp-btn-cta">LOGIN &rarr;</a>
        </div>
    </section>
    <?php include "include/Footer.php"; ?>
</body>
</html>
