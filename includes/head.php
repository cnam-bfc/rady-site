<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<link rel="stylesheet" href="css/header.css" />
<link rel="stylesheet" href="css/footer.css" />
<link rel="stylesheet" href="css/font.css" />
<link rel="stylesheet" href="css/main.css" />
<?php if (isset($_SESSION['REFRESH_PAGE']) && $_SESSION['REFRESH_PAGE'] > 0) : ?>
    <?php if ($_SESSION['REFRESH_PAGE'] == 1) : ?>
        <meta http-equiv="refresh" content="0">
    <?php endif; ?>
    <?php $_SESSION['REFRESH_PAGE']--; ?>
<?php endif; ?>