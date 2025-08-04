<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - SaaS Management</title>
    
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="container" style="margin-top: 100px;">
        <div class="row">
            <div class="col s12 m8 offset-m2">
                <div class="card red lighten-5">
                    <div class="card-content">
                        <span class="card-title red-text">
                            <i class="material-icons left">error</i>
                            Error
                        </span>
                        <p class="red-text"><?php echo htmlspecialchars($error ?? 'An unknown error occurred'); ?></p>
                    </div>
                    <div class="card-action">
                        <a href="dashboard.php" class="waves-effect waves-light btn">
                            <i class="material-icons left">arrow_back</i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html> 