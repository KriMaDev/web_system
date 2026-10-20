<?php
require_once __DIR__ . '/../km_func.php';

send_security_headers();
start_secure_session();

const SLUG_MAX_CHARS = 500;

$text  = '';
$slug  = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = post_string('text');

    // 1-500 characters of valid UTF-8 (the "u" flag rejects invalid byte sequences)
    if ($text === '') {
        $error = 'Please enter some text.';
    } elseif (!preg_match('/^.{1,' . SLUG_MAX_CHARS . '}$/su', $text)) {
        $error = 'Text must be valid UTF-8 and at most ' . SLUG_MAX_CHARS . ' characters long.';
    } else {
        $slug = greekToEnglishChars($text);
        if ($slug === '') {
            $error = 'That text has no letters or numbers to keep.';
        }
    }
}

$pageTitle = 'Slug Generator';
$assetBase = '../';
require __DIR__ . '/../partials/header.php';
?>
        <div class="container py-5">
            <a href="../km_dashboard.php" class="back-link"><i class="bi bi-arrow-left me-1"></i>Back to dashboard</a>

            <header class="tool-header text-center mb-4">
                <h1 class="h3"><i class="bi bi-link-45deg text-primary me-2"></i>Slug Generator</h1>
                <p class="text-body-secondary mb-0">
                    Turn Greek or English text into a clean, URL- and filename-friendly slug.
                </p>
            </header>

            <div class="card tool-panel mx-auto">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="text" class="form-label">Text</label>
                            <textarea class="form-control" id="text" name="text" rows="3" maxlength="<?= SLUG_MAX_CHARS ?>"
                                      placeholder="e.g. Καλημέρα Κόσμε! or My Blog Post: Part 2" required autofocus><?= e($text) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-magic me-1"></i>Generate slug
                        </button>
                    </form>

                    <?php if ($error !== ''): ?>
                        <div class="alert alert-warning mt-4 mb-0" role="alert"><?= e($error) ?></div>
                    <?php elseif ($slug !== ''): ?>
                        <label for="slug-output" class="form-label mt-4">Slug</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace" id="slug-output" value="<?= e($slug) ?>" readonly>
                            <button type="button" class="btn btn-outline-primary" data-copy-target="#slug-output">
                                <i class="bi bi-clipboard me-1"></i>Copy
                            </button>
                        </div>
                    <?php endif; ?>

                    <hr class="my-4">
                    <h2 class="h6">How it works</h2>
                    <ul class="small text-body-secondary mb-0">
                        <li>Greek letters are transliterated (e.g. <code>θ → th</code>, <code>ψ → ps</code>, <code>ώ → o</code>).</li>
                        <li>Spaces and slashes become hyphens, other symbols are removed.</li>
                        <li>The result is lowercase with no repeated or trailing hyphens.</li>
                    </ul>
                </div>
            </div>
        </div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
