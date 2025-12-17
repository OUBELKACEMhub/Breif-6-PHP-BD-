<?php
include "db.php";

// Récupération des utilisateurs pour le select
$users = $pdo->query("SELECT user_id, username FROM users")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $image_url = $_POST['image_url'];
    $user_id = $_POST['user_id'];
    
    try {
        $sql = "INSERT INTO postes (title, image_url, user_id, status, view_count) VALUES (:title, :image, :user, 1, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':image' => $image_url,
            ':user' => $user_id
        ]);
        header("Location: articles.php");
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShine - Add Article</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-[600px] p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Ajouter un Article</h1>
            <a href="articles.php" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-xmark text-xl"></i></a>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Titre</label>
                <input type="text" name="title" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Image URL</label>
                <input type="text" name="image_url" placeholder="https://..." required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2">Auteur</label>
                <select name="user_id" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none bg-white">
                    <option value="">Sélectionner un auteur</option>
                    <?php foreach($users as $user): ?>
                        <option value="<?= $user['user_id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="w-full bg-[#7c3aed] hover:bg-[#6d28d9] text-white font-medium py-3 rounded-lg transition-colors shadow-md">
                Enregistrer l'article
            </button>
        </form>
    </div>

</body>
</html>