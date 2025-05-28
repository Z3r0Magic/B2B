<?php
require_once __DIR__ . '/../includes/lang.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

restrictAccess('admin');

// Параметры периода
$period = $_GET['period'] ?? 'month';
$interval_map = [
    'month' => '1 MONTH',
    '3month' => '3 MONTH',
    'year' => '1 YEAR'
];

// Статистика заказов
$order_stats = $pdo->query("
    SELECT 
        status,
        COUNT(*) AS count,
        SUM(total_amount) AS amount
    FROM orders
    WHERE order_date >= NOW() - INTERVAL {$interval_map[$period]}
    GROUP BY status
")->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);

// Топ товаров
$top_products = $pdo->query("
    SELECT 
        p.title,
        SUM(oi.quantity) AS total_quantity,
        SUM(oi.quantity * oi.price) AS total_sales
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY p.id
    ORDER BY total_quantity DESC
    LIMIT 5
")->fetchAll();

// Топ категорий
$top_categories = $pdo->query("
    SELECT 
        c.name,
        COUNT(*) AS order_count,
        SUM(oi.quantity * oi.price) AS total_sales
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    JOIN categories c ON p.category_id = c.id
    GROUP BY c.id
    ORDER BY total_sales DESC
    LIMIT 5
")->fetchAll();

// Статистика пользователей
$user_stats = $pdo->query("
    SELECT 
        role,
        COUNT(*) AS count
    FROM users
    GROUP BY role
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Данные для графика
$chart_data = $pdo->query("
    SELECT
        DATE_FORMAT(order_date, '%Y-%m') AS month,
        SUM(total_amount) AS amount
    FROM orders
    WHERE status = 'completed'
    GROUP BY month
    ORDER BY month DESC
    LIMIT 12
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('dashboard') ?> - <?= t('site_title') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <style>
    .dashboard-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        text-shadow: 0 2px 2px rgba(0, 0, 0, 0.05);
    }

    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-badge {
        font-size: 1rem;
        padding: 8px 15px;
        border-radius: 8px;
        min-width: 70px;
        text-align: center;
    }

    .metric-title {
        color: #6c757d;
        font-weight: 500;
        letter-spacing: -0.2px;
        margin-bottom: 0.5rem;
    }

    .gradient-primary {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white !important;
    }

    .gradient-success {
        background: linear-gradient(135deg, #06d6a0 0%, #048a81 100%);
        color: white !important;
    }

    .gradient-warning {
        background: linear-gradient(135deg, #ff9e00 0%, #ff6d00 100%);
        color: white !important;
    }

    .gradient-info {
        background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%);
        color: white !important;
    }

    .gradient-danger {
        background: linear-gradient(135deg, #ef476f 0%, #d90429 100%);
        color: white !important;
    }
  </style>
</head>
<body>
  <?php include __DIR__ . '/../includes/navbar.php'; ?>

  <div class="container-fluid mt-5">
    <div class="row mb-4">
      <div class="col-md-6">
        <h1 class="fw-bold"><?= t('dashboard') ?></h1>
      </div>
      <div class="col-md-6 text-end">
        <select id="periodSelect" class="form-select w-auto d-inline-block shadow-sm">
          <option value="month" <?= $period == 'month' ? 'selected' : '' ?>><?= t('last_month') ?></option>
          <option value="3month" <?= $period == '3month' ? 'selected' : '' ?>><?= t('last_3month') ?></option>
          <option value="year" <?= $period == 'year' ? 'selected' : '' ?>><?= t('last_year') ?></option>
        </select>
      </div>
    </div>

    <!-- Основные метрики -->
    <div class="row g-4 mb-4">
      <div class="col-6 col-md-3">
        <div class="card dashboard-card">
          <div class="card-body text-center">
            <h5 class="metric-title"><?= t('total_orders') ?></h5>
            <div class="stat-number text-primary"><?= array_sum(array_column($order_stats, 'count')) ?></div>
          </div>
        </div>
      </div>
      
      <div class="col-6 col-md-3">
        <div class="card dashboard-card">
          <div class="card-body text-center">
            <h5 class="metric-title"><?= t('total_revenue') ?></h5>
            <div class="stat-number text-success">₽<?= number_format($order_stats['completed']['amount'] ?? 0) ?></div>
          </div>
        </div>
      </div>
      
      <div class="col-6 col-md-3">
        <div class="card dashboard-card">
          <div class="card-body text-center">
            <h5 class="metric-title"><?= t('active_users') ?></h5>
            <div class="stat-number text-info"><?= $user_stats['user'] ?? 0 ?></div>
          </div>
        </div>
      </div>
      
      <div class="col-6 col-md-3">
        <div class="card dashboard-card">
          <div class="card-body text-center">
            <h5 class="metric-title"><?= t('pending_profit') ?></h5>
            <div class="stat-number text-warning">₽<?= number_format($order_stats['processing']['amount'] ?? 0) ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Графики и аналитика -->
    <div class="row g-4">
      <div class="col-md-8">
        <div class="chart-container">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card dashboard-card">
          <div class="card-body">
            <h5 class="mb-3 fw-semibold"><?= t('order_statuses') ?></h5>
            <?php foreach ($order_stats as $status => $data): ?>
              <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                <span class="text-capitalize"><?= t($status) ?></span>
                <span class="badge stat-badge gradient-<?= 
                  $status == 'completed' ? 'success' : 
                  ($status == 'processing' ? 'warning' : 'danger') 
                ?>">
                  <?= $data['count'] ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Топ товаров и категорий -->
    <div class="row g-4 mt-4">
      <div class="col-md-6">
        <div class="card dashboard-card">
          <div class="card-body">
            <h5 class="mb-3 fw-semibold"><?= t('top_products') ?></h5>
            <?php foreach ($top_products as $product): ?>
              <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                <div>
                  <div class="fw-medium"><?= htmlspecialchars($product['title']) ?></div>
                  <small class="text-muted"><?= t('sold') ?>: <?= $product['total_quantity'] ?></small>
                </div>
                <span class="badge stat-badge gradient-primary">
                  ₽<?= number_format($product['total_sales']) ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="card dashboard-card">
          <div class="card-body">
            <h5 class="mb-3 fw-semibold"><?= t('top_categories') ?></h5>
            <?php foreach ($top_categories as $category): ?>
              <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                <div class="fw-medium"><?= htmlspecialchars($category['name']) ?></div>
                <span class="badge stat-badge gradient-info">
                  ₽<?= number_format($category['total_sales']) ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.getElementById('periodSelect').addEventListener('change', function() {
      window.location.search = '?period=' + this.value;
    });

    new Chart(document.getElementById('revenueChart').getContext('2d'), {
      type: 'line',
      data: {
        labels: <?= json_encode(array_column($chart_data, 'month')) ?>,
        datasets: [{
          label: '<?= t('revenue') ?>',
          data: <?= json_encode(array_column($chart_data, 'amount')) ?>,
          borderColor: '#4361ee',
          borderWidth: 3,
          tension: 0.4,
          fill: true,
          backgroundColor: 'rgba(67, 97, 238, 0.05)'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#2c3e50',
            titleFont: { size: 14 },
            bodyFont: { size: 14 },
            padding: 12
          }
        },
        elements: {
          line: {
            borderWidth: 3
          }
        },
        scales: {
          y: {
            grid: { color: '#e9ecef' },
            ticks: { color: '#6c757d' }
          },
          x: {
            grid: { display: false },
            ticks: { color: '#6c757d' }
          }
        }
      }
    });
  </script>
</body>
</html>