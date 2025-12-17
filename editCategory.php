<?php
include "db.php";

if (!isset($_GET['id'])) {
    header("Location: categorie.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM category WHERE id_cat = :id");
$stmt->execute([':id' => $id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Catégorie introuvable.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_cat = $_POST['nom_cat'];
    $description = $_POST['description'];
    
    try {
        $sql = "UPDATE category SET nom_cat = :nom, description = :desc WHERE id_cat = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom_cat,
            ':desc' => $description,
            ':id' => $id
        ]);
        header("Location: categorie.php");
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
    <title>BookShine - Edit Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-[600px] p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Modifier la Catégorie</h1>
            <a href="categorie.php" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-xmark text-xl"></i></a>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Nom de la catégorie</label>
                <input type="text" name="nom_cat" value="<?= htmlspecialchars($category['nom_cat']) ?>" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"><?= htmlspecialchars($category['description']) ?></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 rounded-lg transition-colors shadow-md">
                Mettre à jour
            </button>
        </form>
    </div>

</body>
</html>