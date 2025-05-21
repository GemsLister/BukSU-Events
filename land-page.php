<?php
session_start();
include 'db.php';

// Get filter values from GET
$filter_time = $_GET['filter_time'] ?? 'all';
$filter_type = $_GET['filter_type'] ?? '';
$filter_venue = $_GET['filter_venue'] ?? '';
$search = trim($_GET['search'] ?? '');

// Build WHERE clause
$where = ["status = 'approved'"];
$params = [];
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');

if ($filter_time === 'past') {
    $where[] = "event_date_time < ?";
    $params[] = $now;
} elseif ($filter_time === 'ongoing') {
    $where[] = "DATE(event_date_time) = CURDATE()";
} elseif ($filter_time !== 'all') {
    $where[] = "DATE(event_date_time) > ?";
    $params[] = $today;
}

if ($filter_type !== '') {
    $where[] = "event_type = ?";
    $params[] = $filter_type;
}
if ($filter_venue !== '') {
    $where[] = "venue = ?";
    $params[] = $filter_venue;
}
if (!empty($_GET['filter_date'])) {
    $where[] = "DATE(event_date_time) = ?";
    $params[] = $_GET['filter_date'];
}
if ($search !== '') {
    $where[] = "(event_name LIKE ? OR venue LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$where_sql = implode(' AND ', $where);
$stmt = $pdo->prepare("SELECT * FROM events WHERE $where_sql ORDER BY event_date_time ASC");
$stmt->execute($params);
$approvedEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sort for "all" filter: Ongoing, Upcoming, Past
if ($filter_time === 'all') {
    $ongoing = $upcoming = $past = [];
    foreach ($approvedEvents as $event) {
        $eventDate = $event['event_date_time'];
        if (date('Y-m-d', strtotime($eventDate)) === $today) $ongoing[] = $event;
        elseif ($eventDate > $now) $upcoming[] = $event;
        else $past[] = $event;
    }
    $approvedEvents = array_merge($ongoing, $upcoming, $past);
}

$types = $pdo->query("SELECT DISTINCT event_type FROM events WHERE status = 'approved' AND event_type != ''")->fetchAll(PDO::FETCH_COLUMN);
$venues = $pdo->query("SELECT DISTINCT venue FROM events WHERE status = 'approved' AND venue != ''")->fetchAll(PDO::FETCH_COLUMN);

$user_logged_in = isset($_SESSION['user_id']);
$user_role = $_SESSION['roles'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukSU Events</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/land-page.css">
    <style>
        .card-img-top { height: 200px; object-fit: cover; }
    </style>
</head>
<body>
    <header class="text-white py-4" id="main-header" style="position: sticky; top: 0; z-index: 1050; transition: background 0.3s;">
    <script>
        window.addEventListener('scroll', function() {
            const header = document.getElementById('main-header');
            header.style.background = window.scrollY > 50 ? '#242565' : 'transparent';
        });
    </script>
        <div class="container d-flex justify-content-between align-items-center">
            <img src="../BukSU-Events/images/buksu_events_logo.png" alt="BukSU Events Logo" class="logo">
            <div class="menu-icon d-flex d-lg-none">
                <i class="fas fa-bars fa-2x" id="mobile-menu-icon"></i>
            </div>
            <div class="sign-in-buttons d-flex align-items-center gap-3">
                <?php if ($user_logged_in && $user_role === 'faculty'): ?>
                    <a href="faculty-dashboard.php" class="btn btn-outline-light d-none d-lg-flex">My Dashboard</a>
                    <a href="logout.php" class="btn btn-outline-light d-none d-lg-flex">Sign Out</a>
                <?php elseif ($user_logged_in && $user_role === 'student'): ?>
                    <a href="student-dashboard.php" class="btn btn-outline-light d-none d-lg-flex">My Dashboard</a>
                    <a href="logout.php" class="btn btn-outline-light d-none d-lg-flex">Sign Out</a>
                <?php else: ?>
                    <a href="php-forms/faculty-dashboard-sign-in.php" class="btn btn-primary d-none d-lg-flex">Faculty Sign in</a>
                    <a href="php-forms/student-dashboard-sign-in.php" class="btn btn-primary d-none d-lg-flex">Student Sign in</a>
                    <a href="php-forms/sign-up.php" class="btn btn-outline-light d-none d-lg-flex">Sign up</a>
                <?php endif; ?>
            </div>
    </header>
    <main>
        <section class="hero text-center py-5 d-flex">
            <div class="carousel-container container d-flex flex-lg-row">
                <div class="carousel-large mb-4 mb-lg-0 me-lg-4">
                    <div id="carousel-outer" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <figure class="carousel-item active">
                                <img src="../BukSU-Events/images/connect.png" class="d-block w-100" alt="...">
                            </figure>
                            <figure class="carousel-item">
                                <img src="../BukSU-Events/images/enjoy.png" class="d-block w-100" alt="...">
                            </figure>
                            <figure class="carousel-item">
                                <img src="../BukSU-Events/images/innovate.png" class="d-block w-100" alt="...">
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="tagline text-center text-lg-start">
                    <h1 class="display-4 fw-bold mb-2" style="color: #f9b233; text-shadow: 2px 2px 8px rgba(36,37,101,0.2);">Welcome to</h1>
                    <h1 class="display-3 fw-bolder mb-3" style="color: #f9b233; text-shadow: 2px 2px 8px rgba(36,37,101,0.2);">BukSU Events!</h1>
                    <p class="lead">Connect. Enjoy. Innovate. </p>
                    <p class="lead">Stay updated and discover exciting events at BukSU! Whether you're here to network, learn, or simply have fun, there’s something for everyone. Your next memorable experience starts here! </p>
                    <?php if ($user_logged_in && $user_role === 'faculty'): ?>
                        <a href="#upcoming-events" class="btn btn-warning me-2" onclick="document.getElementById('upcoming-events').scrollIntoView({behavior: 'smooth'}); return false;">Register Events</a>
                        <a href="php-forms/user-booking.php" class="btn btn-primary">Book an Event</a>
                    <?php elseif ($user_logged_in && $user_role === 'student'): ?>
                        <a href="#upcoming-events" class=" btn btn-warning" onclick="document.getElementById('upcoming-events').scrollIntoView({behavior: 'smooth'}); return false;">Register Events</a>
                    <?php else: ?>
                        <a href="php-forms/sign-up.php" class="btn btn-warning btn-lg">Register Now</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <section class="container py-3">
            <form method="get" class="row g-3 justify-content-center align-items-end mb-4 bg-white shadow-sm rounded-4 px-3 py-3" style="border: 1px solid #e9ecef;">
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold text-primary mb-1" for="filter_time"><i class="fa fa-clock me-1"></i>Event</label>
                    <select name="filter_time" id="filter_time" class="form-select rounded-pill" onchange="this.form.submit()">
                        <option value="all" <?= $filter_time=='all'?'selected':''; ?>>All Events</option>
                        <option value="ongoing" <?= $filter_time=='ongoing'?'selected':''; ?>>Ongoing</option>
                        <option value="upcoming" <?= $filter_time=='upcoming'?'selected':''; ?>>Upcoming</option>
                        <option value="past" <?= $filter_time=='past'?'selected':''; ?>>Past</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold text-primary mb-1" for="filter_type"><i class="fa fa-tag me-1"></i>Type</label>
                    <select name="filter_type" id="filter_type" class="form-select rounded-pill" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>" <?= $filter_type==$type?'selected':''; ?>><?= htmlspecialchars($type) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold text-primary mb-1" for="filter_venue"><i class="fa fa-map-marker-alt me-1"></i>Venue</label>
                    <select name="filter_venue" id="filter_venue" class="form-select rounded-pill" onchange="this.form.submit()">
                        <option value="">All Venues</option>
                        <option value="Gymnasium" <?= $filter_venue=='Gymnasium'?'selected':''; ?>>Gymnasium</option>
                        <option value="Auditorium" <?= $filter_venue=='Auditorium'?'selected':''; ?>>Auditorium</option>
                        <option value="Mini Theatre" <?= $filter_venue=='Mini Theatre'?'selected':''; ?>>Mini Theatre</option>
                        <option value="Oval" <?= $filter_venue=='Oval'?'selected':''; ?>>Oval</option>
                        <?php foreach ($venues as $venue): ?>
                            <?php if (!in_array($venue, ['Gymnasium','Auditorium','Mini Theatre','Oval'])): ?>
                                <option value="<?= htmlspecialchars($venue) ?>" <?= $filter_venue==$venue?'selected':''; ?>><?= htmlspecialchars($venue) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold text-primary mb-1" for="filter_date"><i class="fa fa-calendar-alt me-1"></i>Date</label>
                    <input type="date" name="filter_date" id="filter_date" class="form-control rounded-pill" value="<?= htmlspecialchars($_GET['filter_date'] ?? '') ?>" onchange="this.form.submit()">
                </div>
                <div class="col-12 col-md-3 mt-2 mt-md-0">
                    <label class="form-label fw-semibold text-primary mb-1" for="search"><i class="fa fa-search me-1"></i>Search</label>
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control rounded-start-pill" placeholder="Search events..." value="<?= htmlspecialchars($search) ?>" style="height: 38px; font-size: 1rem; padding: 0.375rem 0.75rem;">
                        <button class="btn btn-primary rounded-end-pill px-3" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        </section>
        <section id="upcoming-events" class="upcoming-events py-4" style="background: #f7f8fa;">
            <div class="upcoming-events-container container">
                <?php
                $eventsTitle = "Events";
                if ($filter_time === 'upcoming') $eventsTitle = "Upcoming Events";
                elseif ($filter_time === 'ongoing') $eventsTitle = "Today's Event";
                elseif ($filter_time === 'past') $eventsTitle = "Past Events";
                elseif ($filter_time === 'all') $eventsTitle = "All Events";
                ?>
                <h2 class="text-center mb-4 fw-bold" style="color: #242565; letter-spacing: 1px;"><?= $eventsTitle ?></h2>
                <div class="row g-4">
                <?php if (count($approvedEvents) > 0): ?>
                    <?php foreach ($approvedEvents as $event): ?>
                <?php
                    $showEvent = true;
                    if ($user_logged_in && $user_role === 'student' && $event['target_audience'] === 'Faculty Only') $showEvent = false;
                    if ($user_logged_in && $user_role === 'faculty' && $event['target_audience'] === 'Students Only') $showEvent = false;
                    $isRegistered = false;
                    $isPast = (strtotime($event['event_date_time']) < strtotime(date('Y-m-d H:i:s')));
                    if ($user_logged_in) {
                        $stmt = $pdo->prepare("SELECT 1 FROM attendees WHERE user_id = ? AND event_id = ?");
                        $stmt->execute([$_SESSION['user_id'], $event['event_id']]);
                        $isRegistered = $stmt->fetchColumn() ? true : false;
                    }
                ?>
                    <?php if ($showEvent): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
                            <div class="card h-100 shadow event-card border-0 position-relative overflow-hidden"
                                style="border-radius: 1.25rem; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; background: #fff; min-width: 260px; max-width: 100%; width: 100%;"
                                data-event='<?php echo htmlspecialchars(json_encode([
                                    "event_name" => $event["event_name"],
                                    "event_date_time" => $event["event_date_time"],
                                    "venue" => $event["venue"],
                                    "event_type" => $event["event_type"],
                                    "target_audience" => $event["target_audience"],
                                    "description" => $event["description"],
                                    "image_path" => !empty($event["image_path"]) ? $event["image_path"] : "../BukSU-Events/images/default-event.png",
                                    "event_id" => $event["event_id"]
                                ]), ENT_QUOTES, 'UTF-8'); ?>'
                                onmouseover="this.style.transform='translateY(-6px) scale(1.03)';this.style.boxShadow='0 8px 32px rgba(36,37,101,0.13)';"
                                onmouseout="this.style.transform='';this.style.boxShadow='';"
                            >
                                <div style="height: 180px; overflow: hidden; border-top-left-radius: 1.25rem; border-top-right-radius: 1.25rem;">
                                    <img src="<?= htmlspecialchars($event['image_path'] ?: '../BukSU-Events/images/default-event.png'); ?>" class="card-img-top" alt="<?= htmlspecialchars($event['event_name']); ?>" style="height: 100%; object-fit: cover;">
                                </div>
                                <div class="card-body d-flex flex-column" style="padding-bottom: 1.2rem; min-height: 270px;">
                                    <h5 class="card-title fw-bold mb-2" style="color: #242565; min-height: 48px; max-height: 48px; overflow: hidden; text-overflow: ellipsis; white-space: normal; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        <?= htmlspecialchars($event['event_name']); ?>
                                    </h5>
                                    <ul class="list-unstyled mb-2 small">
                                        <li class="mb-1">
                                            <i class="fa fa-calendar-alt text-primary me-1"></i>
                                            <span class="fw-semibold"><?= date('M d, Y', strtotime($event['event_date_time'])); ?></span>
                                            <span class="text-muted ms-1"><?= date('h:i A', strtotime($event['event_date_time'])); ?></span>
                                        </li>
                                        <li class="mb-1">
                                            <i class="fa fa-map-marker-alt text-danger me-1"></i>
                                            <span><?= htmlspecialchars($event['venue']); ?></span>
                                        </li>
                                        <li class="mb-1">
                                            <i class="fa fa-tag text-success me-1"></i>
                                            <span><?= htmlspecialchars($event['event_type']); ?></span>
                                        </li>
                                        <li class="mb-1">
                                            <i class="fa fa-users text-info me-1"></i>
                                            <span><?= htmlspecialchars($event['target_audience']); ?></span>
                                        </li>
                                    </ul>
                                    <div class="mb-2" style="min-height: 40px;">
                                        <span class="text-muted" style="font-size: 0.97em;">
                                            <?= htmlspecialchars(mb_strimwidth($event['description'], 0, 60, '...')); ?>
                                        </span>
                                    </div>
                                    <div class="mt-auto">
                                        <?php if ($isPast): ?>
                                            <button type="button" class="btn btn-secondary w-100" disabled>
                                                <i class="fa fa-flag-checkered me-1"></i>Finished Event
                                            </button>
                                        <?php elseif ($user_logged_in): ?>
                                            <?php if ($isRegistered): ?>
                                                <button type="button" class="btn btn-primary w-100" disabled>
                                                    <i class="fa fa-check-circle me-1"></i>Already Registered
                                                </button>
                                            <?php else: ?>
                                                <form action="register-attendance.php" method="POST" class="mt-2 register-form" onClick="event.stopPropagation();">
                                                    <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                                                    <button type="button" class="btn btn-warning w-100 register-btn fw-semibold">
                                                        <i class="fa fa-user-plus me-1"></i>Register
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="text-center mt-2">
                                                <span class="badge bg-secondary">Sign in to register</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <?php
                                        $badge = '';
                                        $eventDate = $event['event_date_time'];
                                        if ($filter_time === 'all') {
                                            if (date('Y-m-d', strtotime($eventDate)) === date('Y-m-d')) $badge = '<span class="badge rounded-pill bg-success shadow-sm px-3 py-2" style="font-size: 0.95em;">Ongoing</span>';
                                            elseif ($eventDate > $now) $badge = '<span class="badge rounded-pill bg-warning text-dark shadow-sm px-3 py-2" style="font-size: 0.95em;">Upcoming</span>';
                                            else $badge = '<span class="badge rounded-pill bg-secondary shadow-sm px-3 py-2" style="font-size: 0.95em;">Finished</span>';
                                        } elseif ($filter_time === 'upcoming') $badge = '<span class="badge rounded-pill bg-warning text-dark shadow-sm px-3 py-2" style="font-size: 0.95em;">Upcoming</span>';
                                        elseif ($filter_time === 'ongoing') $badge = '<span class="badge rounded-pill bg-success shadow-sm px-3 py-2" style="font-size: 0.95em;">Ongoing</span>';
                                        elseif ($filter_time === 'past') $badge = '<span class="badge rounded-pill bg-secondary shadow-sm px-3 py-2" style="font-size: 0.95em;">Finished</span>';
                                        echo $badge;
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center shadow-sm rounded-4 py-4">
                                <i class="fa fa-calendar-times fa-2x mb-2 text-primary"></i>
                                <div>No events found for your filter.</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        </main>
    <footer class="text-white py-4">
        <div class="container text-center">
            <img src="../BukSU-Events/images/buksu_events_logo.png" alt="BukSU Events Logo" class="mb-3">
            <p>&copy; <?php echo date('Y'); ?> BukSU Events. All rights reserved.</p>
            <div class="social-icons">
                <a href="#" class="text-white mx-2"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>
    <script src="../BukSU-Events/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event Details Modal population
        const eventDetailsModal = document.getElementById('eventDetailsModal');
        const eventDetailsModalLabel = document.getElementById('eventDetailsModalLabel');
        const eventDetailsImage = document.getElementById('eventDetailsImage');
        const eventDetailsDate = document.getElementById('eventDetailsDate');
        const eventDetailsVenue = document.getElementById('eventDetailsVenue');
        const eventDetailsType = document.getElementById('eventDetailsType');
        const eventDetailsAudience = document.getElementById('eventDetailsAudience');
        const eventDetailsDescription = document.getElementById('eventDetailsDescription');
        let modalEventId = null;
        document.querySelectorAll('.event-card').forEach(function(card) {
            card.addEventListener('click', function(e) {
                if (e.target.classList.contains('register-btn')) return;
                const data = JSON.parse(card.getAttribute('data-event'));
                eventDetailsModalLabel.textContent = data.event_name;
                eventDetailsImage.src = data.image_path;
                eventDetailsDate.textContent = new Date(data.event_date_time).toLocaleString();
                eventDetailsVenue.textContent = data.venue;
                eventDetailsType.textContent = data.event_type;
                eventDetailsAudience.textContent = data.target_audience;
                eventDetailsDescription.textContent = data.description;
                modalEventId = data.event_id;
                const modal = new bootstrap.Modal(eventDetailsModal);
                modal.show();
                const modalRegisterContainer = document.getElementById('modalRegisterContainer');
                const cardBtn = card.querySelector('.register-btn');
                modalRegisterContainer.innerHTML = '';
                if (cardBtn) {
                    if (cardBtn.disabled) {
                        modalRegisterContainer.innerHTML = '<button type="button" class="btn btn-primary w-100" disabled>Already Registered</button>';
                    } else {
                        modalRegisterContainer.innerHTML = `
                            <form id="modalRegisterForm">
                                <input type="hidden" name="event_id" value="${data.event_id}">
                                <button type="button" class="btn btn-warning w-100" id="modalRegisterBtn">Register</button>
                            </form>
                        `;
                        document.getElementById('modalRegisterBtn').onclick = function(ev) {
                            ev.stopPropagation();
                            formToSubmit = document.getElementById('modalRegisterForm');
                            bootstrap.Modal.getInstance(eventDetailsModal).hide();
                            confirmModal.show();
                        };
                    }
                }
            });
        });
        let formToSubmit = null;
        const registerButtons = document.querySelectorAll('.register-btn');
        const confirmModal = new bootstrap.Modal(document.getElementById('registerConfirmModal'));
        const successModal = new bootstrap.Modal(document.getElementById('registerSuccessModal'));
        registerButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                formToSubmit = btn.closest('form');
                const modalInstance = bootstrap.Modal.getInstance(eventDetailsModal);
                if (modalInstance) modalInstance.hide();
                confirmModal.show();
            });
        });
        document.getElementById('confirmRegisterBtn').addEventListener('click', function() {
            if (formToSubmit) {
                const formData = new FormData(formToSubmit);
                fetch('register-attendance.php', { method: 'POST', body: formData })
                .then(response => response.text())
                .then(() => {
                    confirmModal.hide();
                    successModal.show();
                    if (formToSubmit.id === 'modalRegisterForm' && modalEventId) {
                        document.querySelectorAll('.event-card').forEach(function(card) {
                            const data = JSON.parse(card.getAttribute('data-event'));
                            if (data.event_id == modalEventId) {
                                const btn = card.querySelector('.register-btn');
                                if (btn) {
                                    btn.classList.remove('btn-warning');
                                    btn.classList.add('btn-primary');
                                    btn.textContent = 'Already Registered';
                                    btn.disabled = true;
                                }
                            }
                        });
                        document.getElementById('modalRegisterContainer').innerHTML = '<button type="button" class="btn btn-primary w-100" disabled>Already Registered</button>';
                    } else if (formToSubmit.classList.contains('register-form')) {
                        const btn = formToSubmit.querySelector('.register-btn');
                        if (btn) {
                            btn.classList.remove('btn-warning');
                            btn.classList.add('btn-primary');
                            btn.textContent = 'Already Registered';
                            btn.disabled = true;
                        }
                    }
                });
            }
        });
        eventDetailsModal.addEventListener('hidden.bs.modal', function () {
            eventDetailsModalLabel.textContent = '';
            eventDetailsImage.src = '';
            eventDetailsDate.textContent = '';
            eventDetailsVenue.textContent = '';
            eventDetailsType.textContent = '';
            eventDetailsAudience.textContent = '';
            eventDetailsDescription.textContent = '';
        });
    });
    </script>
    <!-- Confirmation Modal -->
    <div class="modal fade" id="registerConfirmModal" tabindex="-1" aria-labelledby="registerConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerConfirmModalLabel">Confirm Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to register to this event?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-warning" id="confirmRegisterBtn">Yes</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Success Modal -->
    <div class="modal fade" id="registerSuccessModal" tabindex="-1" aria-labelledby="registerSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success w-100 text-center" id="registerSuccessModalLabel">Registered Successfully!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                    <p class="mb-2 fw-bold">You have successfully registered for this event!</p>
                    <p class="mb-0">Please check your dashboard for event details and proceed to the venue on the event date to confirm your attendance.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 1rem; overflow: hidden;">
            <div class="modal-header" style="background: #242565; color: #fff;">
                <h4 class="modal-title fw-bold" id="eventDetailsModalLabel"></h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-2 align-items-center" style="background: #f8f9fa;">
                <div class="col-md-5 mb-3 mb-md-0">
                    <div class="rounded shadow-sm overflow-hidden" style="background: #e9ecef;">
                        <img id="eventDetailsImage" src="" class="img-fluid w-100" alt="Event Image" style="border-radius: 0.5rem; object-fit: contain; max-height: 350px;">
                    </div>
                </div>
                <div class="col-md-7">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fa fa-calendar-alt me-2 text-primary"></i>
                            <strong>Date & Time:</strong> <span id="eventDetailsDate"></span>
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-map-marker-alt me-2 text-primary"></i>
                            <strong>Venue:</strong> <span id="eventDetailsVenue"></span>
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-tag me-2 text-primary"></i>
                            <strong>Type:</strong> <span id="eventDetailsType"></span>
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-users me-2 text-primary"></i>
                            <strong>Audience:</strong> <span id="eventDetailsAudience"></span>
                        </li>
                        <li>
                            <i class="fa fa-info-circle me-2 text-primary"></i>
                            <strong>Description:</strong><br>
                            <span id="eventDetailsDescription"></span>
                            <div id="modalRegisterContainer" class="mt-4"></div>
                        </li>
                    </ul>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>
