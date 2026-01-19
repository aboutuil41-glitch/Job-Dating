<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .username {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .email {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .bio {
            color: #444;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome</h1>
        <div class="cards">
            
            <?php foreach($all as $user): ?>
                <div class="card">
                    <div class="username"><?= htmlspecialchars($user['username']) ?></div>
                    <div class="email"><?= htmlspecialchars($user['email']) ?></div>
                    <div class="bio"><?= htmlspecialchars($user['bio']) ?></div>
                    <form method="GET" action="/test/<?= $user['id'] ?>">
    <button type="submit">Update</button>
</form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <form method="GET "action="/users/new">
        <button type="submit">GOO!!!</button>
    </form>
</body>
</html>