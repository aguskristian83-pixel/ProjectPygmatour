<?php
session_start();

if (!isset($_SESSION['comments'])) {
    $_SESSION['comments'] = array();
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function commentDate($date)
{
    return date('F d, Y \a\t g:i A', $date);
}

$replyId = isset($_GET['reply']) ? (int) $_GET['reply'] : 0;
$replyTarget = null;

foreach ($_SESSION['comments'] as $comment) {
    if ((int) $comment['id'] === $replyId) {
        $replyTarget = $comment;
        break;
    }
}

$status = '';
$statusClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(isset($_POST['name']) ? $_POST['name'] : '');
    $email = trim(isset($_POST['email']) ? $_POST['email'] : '');
    $website = trim(isset($_POST['website']) ? $_POST['website'] : '');
    $message = trim(isset($_POST['message']) ? $_POST['message'] : '');
    $parentId = isset($_POST['reply_id']) ? (int) $_POST['reply_id'] : 0;

    if ($name === '' || $email === '' || $message === '') {
        $status = 'Please fill in your name, email, and comment.';
        $statusClass = 'status-error';
    } else {
        $ids = array_map(function ($item) {
            return (int) $item['id'];
        }, $_SESSION['comments']);

        $_SESSION['comments'][] = array(
            'id' => empty($ids) ? 1 : max($ids) + 1,
            'name' => $name,
            'email' => $email,
            'website' => $website,
            'message' => $message,
            'created_at' => time(),
            'parent_id' => $parentId > 0 ? $parentId : 0
        );

        header('Location: index.php#contact');
        exit;
    }
}

$topComments = array();
$replies = array();
foreach ($_SESSION['comments'] as $comment) {
    if ((int) $comment['parent_id'] === 0) {
        $topComments[] = $comment;
    } else {
        $replies[(int) $comment['parent_id']][] = $comment;
    }
}

usort($topComments, function ($a, $b) {
    return $b['created_at'] - $a['created_at'];
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pygmatour | Incentive Tour Specialist</title>
    <link rel="icon" type="image/svg+xml" href="img/logo/pygmatour-logo.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --ink: #102b4e; --blue: #173454; --muted: #53657b; --gold: #f6c453; --paper: #f5f7f4; }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; color: var(--blue); font-family: Arial, sans-serif; }
        .navbar { min-height: 74px; border-top: 1px solid #dfe3e8; border-bottom: 1px solid #dfe3e8; background: #fff; }
        .navbar-brand { display: inline-flex; align-items: center; color: #111 !important; font-weight: 600; }
        .navbar-brand img { width: 52px; height: 52px; object-fit: contain; }
        .navbar-brand span { margin-left: 8px; }
        .navbar .nav-link { color: var(--blue); font-size: .78rem; padding: 27px 11px !important; }
        .navbar .nav-link:hover { color: #0d6efd; }
        .dropdown-menu { border: 0; border-radius: 0; box-shadow: 0 3px 12px rgba(0, 0, 0, .12); }
        .dropdown-item { color: var(--blue); font-size: .78rem; padding: 11px 12px; }
        .dropdown-item:hover { color: #0d6efd; background: #fff; }
        .home-intro { padding: 0 0 64px; background: var(--paper); }
        .home-hero { position: relative; min-height: 560px; display: flex; align-items: center; overflow: hidden; background: var(--blue); }
        .home-hero::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(9, 33, 52, .82), rgba(9, 33, 52, .58) 42%, rgba(9, 33, 52, .12)), url('img/home/home.png') center/cover; }
        .home-hero .container { position: relative; z-index: 1; }
        .hero-copy { max-width: 620px; color: #fff; padding: 72px 0 110px; }
        .kicker { color: var(--gold); font-size: .75rem; font-weight: 700; letter-spacing: .16em; margin-bottom: 18px; text-transform: uppercase; }
        .hero-copy h1 { color: #fff; font-size: clamp(2.6rem, 5vw, 4.8rem); font-weight: 600; line-height: 1.02; margin-bottom: 22px; max-width: 570px; }
        .hero-copy p { color: rgba(255, 255, 255, .88); font-size: 1.05rem; line-height: 1.7; max-width: 500px; }
        .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; margin-top: 30px; }
        .hero-actions a { display: inline-flex; align-items: center; justify-content: center; min-height: 46px; padding: 0 21px; border-radius: 3px; font-size: .86rem; font-weight: 600; text-decoration: none; }
        .action-primary { background: var(--gold); color: var(--blue); }
        .action-secondary { border: 1px solid rgba(255, 255, 255, .7); color: #fff; }
        .action-primary:hover, .action-secondary:hover { color: var(--blue); background: #fff; }
        .hero-stats { position: absolute; right: 0; bottom: 34px; display: flex; gap: 28px; color: #fff; }
        .hero-stat strong { display: block; font-size: 1.45rem; }
        .hero-stat span { color: rgba(255, 255, 255, .72); font-size: .72rem; }
        .background-copy { padding-top: 48px; line-height: 1.65; }
        .background-copy h2 { color: var(--ink); font-size: 1.5rem; margin: 0 0 2px; }
        .background-copy h3 { color: #d49b2c; font-size: .8rem; letter-spacing: .13em; margin: 0 0 16px; }
        .about-page, .portfolio-shell, .contact-page { padding: 54px 0; }
        .about-page { color: var(--blue); }
        .about-page h1, .section-heading, .contact-title { color: var(--ink); font-weight: 600; }
        .about-page h1, .contact-title { font-size: 2rem; margin-bottom: 34px; }
        .about-page h2 { text-align: center; font-size: 1.25rem; }
        .about-description, .about-point { max-width: 720px; margin: 0 auto 30px; text-align: center; line-height: 1.6; }
        .about-description { font-size: .9rem; }
        .about-point h3 { font-size: 1rem; }
        .about-point p { font-size: .9rem; }
        .services { padding: 64px 0; background: #fff; }
        .section-heading { text-align: center; font-size: 2rem; margin-bottom: 38px; }
        .feature-card { height: 100%; padding: 28px; border: 1px solid #e4e9ec; background: #fff; }
        .feature-icon { color: #d49b2c; font-size: 1.5rem; margin-bottom: 18px; }
        .feature-card h3 { color: var(--ink); font-size: 1.05rem; }
        .feature-card p { color: var(--muted); line-height: 1.65; margin: 0; }
        .portfolio-shell { border-top: 1px solid #dfe3e8; }
        .portfolio-heading { color: var(--ink); font-size: 1.45rem; margin-bottom: 16px; }
        .portfolio-shortcode { color: var(--blue); font-size: .72rem; line-height: 1.7; word-break: break-word; }
        .portfolio-2025 { padding-top: 0; }
        .portfolio-2025-image { display: block; width: min(100%, 478px); height: auto; margin: 0 auto; }
        .contact-page { padding-bottom: 0; }
        .contact-center { max-width: 620px; margin: 0 auto 36px; text-align: center; }
        .contact-center .lead { font-size: 1.1rem; font-style: italic; }
        .contact-form { max-width: 760px; margin: 0 auto; }
        .contact-form label { display: block; font-size: .8rem; font-weight: 600; margin: 0 0 8px; }
        .form-control { border-radius: 0; background: #f9f9f9; }
        .input-row { display: flex; gap: 14px; margin-bottom: 16px; }
        .input-row .field { flex: 1; }
        .reply-box { display: flex; justify-content: space-between; margin: 20px 0 10px; }
        .cancel-reply, .comment-reply-link { color: #0d6efd; text-decoration: none; }
        .checkbox-row { display: flex; align-items: center; gap: 8px; margin: 14px 0 18px; color: var(--muted); font-size: .82rem; }
        .btn-submit { background: #0d6efd; color: #fff; border: 0; border-radius: 4px; padding: 11px 18px; }
        .status-error { color: #b42318; margin-top: 16px; }
        .comment-thread { margin-top: 42px; }
        .comment-list { border-top: 1px solid #dfe5eb; }
        .comment-item { display: flex; gap: 16px; padding: 22px 0 18px; border-bottom: 1px solid #e7ebf0; }
        .comment-item-reply { margin-left: 44px; }
        .comment-avatar { width: 42px; height: 42px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #dfe5ec; color: #6d7d90; font-weight: 700; }
        .comment-content { flex: 1; }
        .comment-meta { display: flex; justify-content: space-between; gap: 12px; margin-bottom: 8px; }
        .comment-author { color: var(--ink); font-weight: 600; }
        .comment-date { color: #77849a; font-size: .9rem; }
        .comment-body { color: #2d3f52; line-height: 1.8; }
        footer { margin-top: 64px; padding: 30px 0; background: #102b4e; color: #c8d2dc; }
        @media (max-width: 767px) {
            .navbar .nav-link { padding: 10px 0 !important; }
            .hero-copy { padding: 68px 0 150px; }
            .hero-copy h1 { font-size: 2.75rem; }
            .hero-stats { right: auto; left: 0; bottom: 34px; gap: 20px; }
            .input-row { display: block; }
            .input-row .field { margin-bottom: 14px; }
            .comment-meta { display: block; }
            .comment-date { display: block; margin-top: 6px; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><img src="img/logo/pygmatour-logo.svg" alt="Pygmatour"><span>Pygmatour</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse justify-content-end" id="mainNav">
            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#clients">Our Clients</a></li>
                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#portfolio" data-bs-toggle="dropdown">Portfolio</a>
                    <ul class="dropdown-menu"><li><a class="dropdown-item" href="#tour-2013">Tour 2013</a></li><li><a class="dropdown-item" href="#tour-2025">Tour 2025</a></li></ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
<section class="home-intro">
    <div class="home-hero"><div class="container"><div class="hero-copy">
        <div class="kicker">Incentive Tour Specialist</div>
        <h1>Make every journey worth remembering.</h1>
        <p>Thoughtful travel experiences for teams, companies, and curious explorers, planned with local insight and delivered with care.</p>
        <div class="hero-actions"><a class="action-primary" href="#portfolio">Explore our journeys</a><a class="action-secondary" href="#contact">Plan a trip</a></div>
    </div><div class="hero-stats"><div class="hero-stat"><strong>2012</strong><span>Established</span></div><div class="hero-stat"><strong>30+</strong><span>Destinations</span></div><div class="hero-stat"><strong>360°</strong><span>Travel support</span></div></div></div></div>
    <div class="container"><div class="background-copy"><h2>Our Background</h2><h3>PYGMATOUR</h3><p>(founded in October 2012) is an Incentive Tour Operating Company, offering dedicated personalized service and worldwide trip planning experience. Since 2017, we also provide travel consulting for corporate and individual needs.</p></div></div>
</section>

<section id="about" class="about-page"><div class="container"><h1>About Us</h1><h2>Incentive Tour Specialist in Indonesia</h2><p class="about-description"><strong>PYGMATOUR</strong> organizes company trips, team building, thematic gala dinners, student study tours, sport events, medical events, and more.</p><div class="about-point"><h3>VISION</h3><p>To become the leading incentive tour operator and our client's best partner.</p></div><div class="about-point"><h3>MISSION</h3><p>To provide every client with the best product and service possible, combined with professionalism and integrity.</p></div></div></section>

<section id="clients" class="services"><div class="container"><h2 class="section-heading">Why Choose Us</h2><div class="row g-4"><div class="col-md-4"><div class="feature-card"><div class="feature-icon"><i class="fa-solid fa-route"></i></div><h3>Customized Planning</h3><p>Every itinerary is crafted around your goals, audience, and destination.</p></div></div><div class="col-md-4"><div class="feature-card"><div class="feature-icon"><i class="fa-solid fa-users"></i></div><h3>Team Coordination</h3><p>We manage logistics, schedules, and stakeholder needs with care.</p></div></div><div class="col-md-4"><div class="feature-card"><div class="feature-icon"><i class="fa-solid fa-medal"></i></div><h3>Premium Service</h3><p>Memorable experiences delivered with professionalism and integrity.</p></div></div></div></div></section>

<section id="portfolio" class="portfolio-shell"><div class="container"><h1 class="portfolio-heading" id="tour-2013">Tour 2013</h1><p class="portfolio-shortcode">[recent_works layout="grid" columns="3" cat_slug="2013" number_posts="100"]</p></div></section>
<section id="tour-2025" class="portfolio-shell portfolio-2025"><div class="container"><h1 class="portfolio-heading">Tour 2025</h1><img src="img/portfolio/tour-2025.png" class="portfolio-2025-image" alt="Pygmatour Tour 2025 in Australia"></div></section>

<section id="contact" class="contact-page"><div class="container"><h1 class="contact-title">Contact Us</h1><div class="contact-center"><p class="lead">We'd love to meet you in person or via the web!</p><strong>PT. ENAM DUNIA WISATA</strong><br>Jl. Bandung Selatan No.43, Jakarta Utara, Indonesia<br>Phone: +62 21 6667 0688<br>Email: <a href="mailto:info@pygmatour.com">info@pygmatour.com</a></div><div class="contact-form"><h3>Leave a Reply</h3><p>Your email address will not be published. Required fields are marked *</p><?php if ($status !== ''): ?><div class="<?php echo e($statusClass); ?>"><?php echo e($status); ?></div><?php endif; ?><form method="post" action="index.php#contact"><div class="mb-3"><label for="message">Comment *</label><textarea id="message" name="message" class="form-control" rows="6" required></textarea></div><div class="input-row"><div class="field"><label for="name">Name *</label><input id="name" name="name" class="form-control" required></div><div class="field"><label for="email">Email *</label><input id="email" name="email" type="email" class="form-control" required></div><div class="field"><label for="website">Website</label><input id="website" name="website" class="form-control"></div></div><?php if ($replyTarget): ?><div class="reply-box"><strong>Reply to <?php echo e($replyTarget['name']); ?></strong><a class="cancel-reply" href="index.php#contact">Cancel reply</a></div><input type="hidden" name="reply_id" value="<?php echo (int) $replyTarget['id']; ?>"><?php endif; ?><label class="checkbox-row"><input type="checkbox" name="save_info">Save my name, email, and website in this browser.</label><button class="btn-submit" type="submit">Post Comment</button></form><?php if (count($_SESSION['comments']) > 0): ?><div class="comment-thread"><h3><?php echo count($_SESSION['comments']); ?> thought<?php echo count($_SESSION['comments']) === 1 ? '' : 's'; ?> on "Home"</h3><div class="comment-list"><?php foreach ($topComments as $comment): ?><div class="comment-item"><div class="comment-avatar"><?php echo e(strtoupper(substr($comment['name'], 0, 1))); ?></div><div class="comment-content"><div class="comment-meta"><strong class="comment-author"><?php echo e($comment['name']); ?></strong><span class="comment-date"><?php echo e(commentDate($comment['created_at'])); ?></span></div><a class="comment-reply-link" href="index.php?reply=<?php echo (int) $comment['id']; ?>#contact">Reply</a><div class="comment-body"><?php echo nl2br(e($comment['message'])); ?></div><?php if (isset($replies[$comment['id']])): foreach ($replies[$comment['id']] as $reply): ?><div class="comment-item comment-item-reply"><div class="comment-avatar"><?php echo e(strtoupper(substr($reply['name'], 0, 1))); ?></div><div class="comment-content"><div class="comment-meta"><strong class="comment-author"><?php echo e($reply['name']); ?></strong><span class="comment-date"><?php echo e(commentDate($reply['created_at'])); ?></span></div><div class="comment-body"><?php echo nl2br(e($reply['message'])); ?></div></div></div><?php endforeach; endif; ?></div></div><?php endforeach; ?></div></div><?php endif; ?></div></div></section>
</main>
<footer class="text-center"><div class="container"><p class="mb-0">&copy; <?php echo date('Y'); ?> Pygmatour</p></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
