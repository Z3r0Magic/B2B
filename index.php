<?php
require_once 'includes/auth.php';
require_once 'includes/lang.php';
require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <base href="/">
  <title><?= t('site_title') ?></title>
  <link href="/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .hero-section {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/main-banner.jpg');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 100px 0;
      margin-bottom: 50px;
    }
    .feature-icon {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: #0d6efd;
    }
    .feature-card {
      transition: transform 0.3s;
      height: 100%;
    }
    .feature-card:hover {
      transform: translateY(-10px);
    }
    footer {
      background-color: #343a40;
      color: white;
      padding: 50px 0 20px;
    }
    footer a {
      color: #adb5bd;
      text-decoration: none;
    }
    footer a:hover {
      color: white;
    }
    .social-icons a {
      color: white;
      font-size: 1.5rem;
      margin-right: 15px;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container text-center">
      <h1 class="display-4 fw-bold mb-4"><?= t('site_title') ?></h1>
      <p class="lead mb-5"><?= t('welcome_message') ?></p>
      <div class="d-flex justify-content-center gap-3">
        <a class="btn btn-primary btn-lg px-4" href="/catalog.php"><?= t('catalog') ?></a>
        <a class="btn btn-outline-light btn-lg px-4" href="#contact"><?= t('contacts') ?></a>
      </div>
    </div>
  </section>

  <!-- первая секция -->
  <section class="container mb-5">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card feature-card p-4 text-center">
          <div class="feature-icon">
            <i class="fas fa-truck"></i>
          </div>
          <h3><?= t('fast_delivery') ?></h3>
          <p><?= t('delivery_description') ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card feature-card p-4 text-center">
          <div class="feature-icon">
            <i class="fas fa-medal"></i>
          </div>
          <h3><?= t('quality_products') ?></h3>
          <p><?= t('quality_description') ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card feature-card p-4 text-center">
          <div class="feature-icon">
            <i class="fas fa-percentage"></i>
          </div>
          <h3><?= t('good_conditions') ?></h3>
          <p><?= t('conditions_description') ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- вторая секция -->
  <section class="container mb-5">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h2 class="mb-4"><?= t('company_about') ?></h2>
        <p class="lead"><?= t('company_description') ?></p>
        <p><?= t('company_long_description') ?></p>
        <a href="#" class="btn btn-outline-primary"><?= t('learn_more') ?></a>
      </div>
      <div class="col-md-6">
        <img src="/images/warehouse.jpg" alt="<?= t('warehouse_alt') ?>" class="img-fluid rounded">
      </div>
    </div>
  </section>

  <!-- навбар низ -->
  <footer id="contact">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <h5><?= t('contacts') ?></h5>
          <address>
            <p><i class="fas fa-map-marker-alt me-2"></i> <?= t('address') ?></p>
            <p><i class="fas fa-phone me-2"></i> +7 (423) 123-45-67</p>
            <p><i class="fas fa-envelope me-2"></i> info@vladhimiya.ru</p>
          </address>
        </div>
        <div class="col-md-4 mb-4">
          <h5><?= t('about_company') ?></h5>
          <ul class="list-unstyled">
            <li><a href="#"><?= t('company_history') ?></a></li>
            <li><a href="#"><?= t('representatives') ?></a></li>
            <li><a href="#"><?= t('company_details') ?></a></li>
            <li><a href="#"><?= t('vacancies') ?></a></li>
            <li><a href="#contact"><?= t('contacts') ?></a></li>
          </ul>
        </div>
        <div class="col-md-4 mb-4">
          <h5><?= t('cooperation') ?></h5>
          <ul class="list-unstyled">
            <li><a href="#"><?= t('advantages') ?></a></li>
            <li><a href="#"><?= t('cooperation_terms') ?></a></li>
            <li><a href="#"><?= t('wholesale_discounts') ?></a></li>
            <li><a href="#"><?= t('for_suppliers') ?></a></li>
            <li><a href="#"><?= t('returns') ?></a></li>
          </ul>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-6">
          <p>© 2025 <?= t('site_title') ?>. <?= t('all_rights_reserved') ?></p>
          <p><a href="#"><?= t('personal_data') ?></a></p>
        </div>
        <div class="col-md-6 text-md-end">
          <div class="social-icons">
            <a href="#"><i class="fab fa-vk"></i></a>
            <a href="#"><i class="fab fa-telegram"></i></a>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
          </div>
          <p class="mt-2"><?= t('social_networks') ?></p>
        </div>
      </div>
    </div>
  </footer>

  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>