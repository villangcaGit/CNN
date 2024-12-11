<?php
// NewsAPI key
$apiKey = 'f92a033945994d4da7462846f83d7581';

// Specify the domains
$domains = 'cnn.com'; // Multiple domains separated by a comma

$url = "https://newsapi.org/v2/everything?" . http_build_query([
    'q' => 'news', // Search query
    'language' => 'en', // Language set to English
    'sortBy' => 'publishedAt', // Sort by publication date
    'pageSize' => 15, // Limit to 10 articles
    'domains' => $domains, // Add the domains parameter
    'apiKey' => $apiKey, // Your API key
]);

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: YourAppName/1.0', // Replace with your app name
]);

// Execute request and check for connection issues
$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    echo "<p>Failed to connect to the NewsAPI service. Please check your network connection.</p>";
    exit;
}

// Decode JSON response
$newsData = json_decode($response, true);

// Check for JSON decoding errors
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<p>JSON decoding error: " . json_last_error_msg() . "</p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    exit;
}

// Check if data fetching was successful
if ($newsData['status'] !== 'ok' || empty($newsData['articles'])) {
    echo "<p>Error from NewsAPI: " . htmlspecialchars($newsData['message'] ?? 'No articles found.') . "</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1; /* This allows the content to grow and fill the space */
        }
        footer {
            margin-top: auto; /* Pushes the footer to the bottom */
        }
        .footer__link {
            margin-right: 15px; /* Adjust the space between links */
        }
    </style>
</head>
<body>

<header class="bg-dark text-white text-center py-3">
    <h1>CNN Latest Articles</h1>
    <nav>
        <a href="index.php" class="text-white mx-2">Home</a>
        <a href="about.php" class="text-white mx-2">About</a>
        <a href="contact.php" class="text-white mx-2">Contact</a>
    </nav>
</header>

<div class="container mt-5 content">
    <div id="newsCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
        <div class="carousel-inner">
            <?php foreach ($newsData['articles'] as $index => $article): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="<?= htmlspecialchars($article['urlToImage'] ?? 'default-image.jpg') ?>" class="d-block w-100" alt="News Image" style="max-height: 400px; object-fit: cover;">
                        </div>
                        <div class="col-md-6">
                            <h4><?= htmlspecialchars($article['title']) ?></h4>
                            <p><strong><?= htmlspecialchars($article['source']['name']) ?></strong> - <?= date('F j, Y', strtotime($article['publishedAt'])) ?></p>
                            <p><?= htmlspecialchars($article['description']) ?></p>
                            <a href="<?= htmlspecialchars($article['url']) ?>" target="_blank" class="btn btn-primary">Read more</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Controls -->
        <a class="carousel-control-prev" href="#newsCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#newsCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<div class="container mt-5 content">
    <!-- Additional news content -->
    <div class="additional-news mt-5">
        <h2>More Articles</h2>
        <div class="row">
            <?php foreach (array_slice($newsData['articles'], 1, 6) as $article): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($article['urlToImage'] ?? 'default-image.jpg') ?>" class="card-img-top" alt="News Image" style="max-height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($article['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($article['description']) ?></p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted"><?= htmlspecialchars($article['source']['name']) ?> - <?= date('F j, Y', strtotime($article['publishedAt'])) ?></small><br>
                            <a href="<?= htmlspecialchars($article['url']) ?>" target="_blank" class="btn btn-primary btn-sm mt-2">Read more</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="container mt-5 text-center">
    <h4>Follow CNN</h4>
    <ul class="list-inline">
        <!-- Facebook -->
        <li class="list-inline-item">
            <a href="https://facebook.com/CNN" target="_blank" title="Visit us on Facebook">
                <img src="images/facebook icon.png" alt="Facebook Icon" style="width: 30px; height: 30px;">
            </a>
        </li>
        <!-- Twitter -->
        <li class="list-inline-item">
            <a href="https://twitter.com/CNN" target="_blank" title="Visit us on X">
                <img src="images/x icon.png" alt="Twitter Icon" style="width: 30px; height: 30px;">
            </a>
        </li>
        <!-- Instagram -->
        <li class="list-inline-item">
            <a href="https://instagram.com/CNN" target="_blank" title="Visit us on Instagram">
                <img src="images/instagram icon.png" alt="Instagram Icon" style="width: 30px; height: 30px;">
            </a>
        </li>
        <!-- TikTok -->
        <li class="list-inline-item">
            <a href="https://www.tiktok.com/@cnn?lang=en" target="_blank" title="Visit us on TikTok">
                <img src="images/tiktok icon.png" alt="TikTok Icon" style="width: 30px; height: 30px;">
            </a>
        </li>
        <!-- LinkedIn -->
        <li class="list-inline-item">
            <a href="https://www.linkedin.com/company/cnn" target="_blank" title="Visit us on LinkedIn">
                <img src="images/linkedin icon.png" alt="LinkedIn Icon" style="width: 30px; height: 30px;">
            </a>
        </li>
    </ul>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <a href="https://edition.cnn.com/privacy" class="footer__link">
        Privacy Policy
    </a>
    <a href="https://edition.cnn.com/ad-choices" class="footer__link">
        Ad Choices
    </a>
    <a href="https://edition.cnn.com/terms" class="footer__link">
        Terms of Use
    </a>
    <a href="https://edition.cnn.com/accessibility" class="footer__link">
        Accessibility &amp; CC
    </a>
    <a href="https://edition.cnn.com/about" class="footer__link">
        About
    </a>
    <a href="https://edition.cnn.com/newsletters" class="footer__link">
        Newsletters
    </a>
    <a href="https://edition.cnn.com/transcripts" class="footer__link">
        Transcripts
    </a>
    <p>&copy; <?= date('Y') ?> Cable News Network. A Warner Bros. Discovery Company. All Rights Reserved. <br>
    CNN Sans ™ & © 2016 Cable News Network.</p>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

