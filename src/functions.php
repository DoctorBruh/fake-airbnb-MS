<?php
/********************************************************************
 *  Fake Airbnb – shared helper library
 *******************************************************************/
require_once __DIR__ . '/../config/config.php';

function dbConnect(): PDO
{
    static $db = null;
    if ($db) { return $db; }

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', SERVER, PORT, DATABASE);
    $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $db  = new PDO($dsn, USERNAME, PASSWORD, $opts);
    return $db;
}

/* ---------- simple look-ups ------------------------------------- */
function getNeighborhoods(): array
{
    return dbConnect()
        ->query('SELECT id, neighborhood FROM neighborhoods ORDER BY neighborhood')
        ->fetchAll();
}

function getRoomTypes(): array
{
    return dbConnect()
        ->query('SELECT id, type FROM roomTypes ORDER BY type')
        ->fetchAll();
}

/* ---------- main listing search ---------------------------------
 *  $filters keys: neighborhood, roomType, guests, priceOrder
 *  $limit  -> int|null  (null = no limit)
 *-----------------------------------------------------------------*/
function getListings(array $filters = [], ?int $limit = null): array
{
    $sql = <<<SQL
        SELECT  l.id, l.name, l.pictureUrl,
                n.neighborhood,
                r.type          AS roomType,
                l.accommodates,
                l.rating,
                l.price
          FROM  listings l
          JOIN  neighborhoods n ON n.id = l.neighborhoodId
          JOIN  roomTypes     r ON r.id = l.roomTypeId
         WHERE  1
    SQL;

    $params = [];

    if (!empty($filters['neighborhood'])) {
        $sql .= ' AND l.neighborhoodId = :nID';
        $params[':nID'] = (int)$filters['neighborhood'];
    }
    if (!empty($filters['roomType'])) {
        $sql .= ' AND l.roomTypeId = :rID';
        $params[':rID'] = (int)$filters['roomType'];
    }
    if (!empty($filters['guests'])) {
        $sql .= ' AND l.accommodates >= :guests';
        $params[':guests'] = (int)$filters['guests'];
    }

    /* ---- ordering ------------------------------------------------ */
    $order = '';
    if (!empty($filters['priceOrder'])) {
        $dir   = strtoupper($filters['priceOrder']) === 'DESC' ? 'DESC' : 'ASC';
        $order = " ORDER BY l.price $dir";
    } else {
        $order = ' ORDER BY l.rating DESC, l.price ASC';
    }
    $sql .= $order;

    /* ---- optional limit ------------------------------------------ */
    if ($limit !== null) {
        $sql .= ' LIMIT :lim';
    }

    $db   = dbConnect();
    $stmt = $db->prepare($sql);

    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v, PDO::PARAM_INT);
    }
    if ($limit !== null) {
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchAll();
}

/* ---------- modal details helper (unchanged) -------------------- */
function getListingDetails(int $listingId): ?array
{
    $db = dbConnect();

    $coreSQL = <<<SQL
        SELECT  l.id, l.name, l.description, l.pictureUrl,
                l.accommodates, l.beds, l.bedrooms, l.bathrooms,
                l.price, l.rating,
                n.neighborhood,
                r.type        AS roomType,
                h.hostName
          FROM  listings       l
          JOIN  neighborhoods  n ON n.id = l.neighborhoodId
          JOIN  roomTypes      r ON r.id = l.roomTypeId
          JOIN  hosts          h ON h.id = l.hostId
         WHERE  l.id = :id
         LIMIT  1
    SQL;
    $stmt = $db->prepare($coreSQL);
    $stmt->execute([':id' => $listingId]);
    $row  = $stmt->fetch();
    if (!$row) { return null; }

    $amenSQL = <<<SQL
        SELECT a.amenity
          FROM amenities a
          JOIN listingAmenities la ON la.amenityID = a.id
         WHERE la.listingID = :id
         ORDER BY a.amenity
    SQL;
    $amen = $db->prepare($amenSQL);
    $amen->execute([':id' => $listingId]);
    $row['amenities'] = implode(', ', $amen->fetchAll(PDO::FETCH_COLUMN)) ?: '—';

    return $row;
}
