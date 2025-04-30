<?php
require_once __DIR__ . '/src/functions.php';
$neighborhoods = getNeighborhoods();
$roomTypes     = getRoomTypes();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fake Airbnb</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <i class="bi bi-house-heart-fill me-2"></i><strong>Fake Airbnb</strong>
            </a>
        </div>
    </nav>
</header>

<main>
    <div class="album py-5 bg-light">
        <div class="container">
            <h1 class="mb-4 text-center">Find your perfect stay</h1>

            <form action="results.php" method="get" class="row g-3 align-items-end justify-content-center">

                <!-- Neighborhood -->
                <div class="col-12 col-md-3">
                    <label for="neighborhood" class="form-label">Neighborhood</label>
                    <select id="neighborhood" name="neighborhood" class="form-select">
                        <option value="" selected>Any neighborhood</option>
                        <?php foreach ($neighborhoods as $n): ?>
                            <option value="<?= $n['id']; ?>"><?= htmlspecialchars($n['neighborhood']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Room type -->
                <div class="col-12 col-md-3">
                    <label for="roomType" class="form-label">Room type</label>
                    <select id="roomType" name="roomType" class="form-select">
                        <option value="" selected>Any type</option>
                        <?php foreach ($roomTypes as $r): ?>
                            <option value="<?= $r['id']; ?>"><?= htmlspecialchars($r['type']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Guests -->
                <div class="col-6 col-md-2">
                    <label for="guests" class="form-label">Guests</label>
                    <select id="guests" name="guests" class="form-select">
                        <option value="" selected>1+</option>
                        <?php for ($g = 1; $g <= 10; $g++): ?>
                            <option value="<?= $g; ?>"><?= $g; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Price order -->
                <div class="col-6 col-md-2">
                    <label for="priceOrder" class="form-label">Price order</label>
                    <select id="priceOrder" name="priceOrder" class="form-select">
                        <option value="" selected>None</option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                </div>

                <div class="col-6 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
                </div>
            </form>
        </div>
    </div>
</main>

<footer class="text-muted py-5">
    <div class="container text-center">
        <p class="mb-1">CS 293, Spring 2025 · Lewis & Clark College</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
