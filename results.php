<?php
require_once __DIR__ . '/src/functions.php';

$filters = [
    'neighborhood' => $_GET['neighborhood'] ?? null,
    'roomType'     => $_GET['roomType']     ?? null,
    'guests'       => $_GET['guests']       ?? null,
    'priceOrder'   => $_GET['priceOrder']   ?? null,
];

$listings = getListings($filters);     // no limit → show all
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fake Airbnb – Results</title>
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
    <div class="container py-5">
        <h1 class="mb-4">Results (<?= count($listings) ?>)</h1>

        <?php if (!$listings): ?>
            <div class="alert alert-warning">No matches – try different filters.</div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($listings as $l): ?>
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <img class="card-img-top" src="<?= htmlspecialchars($l['pictureUrl']); ?>"
                                 alt="<?= htmlspecialchars($l['name']); ?>">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($l['name']); ?></h5>
                                <p class="card-text mb-1">
                                    <?= htmlspecialchars($l['neighborhood']); ?> ·
                                    <?= htmlspecialchars($l['roomType']); ?>
                                </p>
                                <p class="card-text mb-1">Accommodates <?= $l['accommodates']; ?></p>
                                <p class="card-text mb-2">
                                    <i class="bi bi-star-fill"></i> <?= number_format($l['rating'], 2); ?>
                                </p>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <button class="btn btn-outline-secondary btn-sm viewListing"
                                            data-id="<?= $l['id']; ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#listingModal">
                                        View
                                    </button>
                                    <small class="text-muted">$<?= number_format($l['price'], 2); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer class="text-muted py-5">
    <div class="container text-center">
        <p class="mb-1">CS 293, Spring 2025 · Lewis & Clark College</p>
    </div>
</footer>

<!-- modal markup unchanged -->
<div class="modal fade" id="listingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Listing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" class="img-fluid d-block w-100 mb-3" src="" alt="">
                <p id="modalDescription"></p>
                <div id="modalMeta" class="mb-2 fw-bold"></div>
                <p id="modalAmenities" class="small"></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</body>
</html>
