<?php
include "db.php";
if (!isset($_GET['id'])) {
    header("Location: articles.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM postes WHERE id_artc = :id");
$stmt->execute([':id' => $id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    die("Article introuvable.");
}


$users = $pdo->query("SELECT user_id, username FROM users")->fetchAll();
$category =$pdo->query("SELECT nom_cat,id_cat FROM category")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $image_url = $_POST['image_url'];
    $user_id = $_POST['user_id'];
    $contenu = $_POST['content'];
    $category=$_POST['cat_id'];
    
    try {
$sql = "UPDATE postes SET title = :title, image_url = :image, content = :content, id_cat = :category WHERE id_artc = :id";   
     $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':image' => $image_url,
            ':content' => $contenu,
            ':category'=>$category,
            ':id' => $id
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
    <title>BookShine - Edit Article</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-[600px] p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Modifier l'Article</h1>
            <a href="articles.php" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-xmark text-xl"></i></a>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Titre</label>
                <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Image URL</label>
                <input type="text" name="image_url" value="<?= htmlspecialchars($article['image_url']) ?>" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none">
                <div class="mt-2 text-xs text-gray-500">Aperçu : <img src="<?= htmlspecialchars($article['image_url']) ?>" class="h-10 inline rounded"></div>
            </div>
             <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Contenu</label>
            <textarea name="content" rows="4" required
                        class="w-full px-4 py-3 pb-14 rounded-lg border border-gray-300 bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none resize-none transition-all placeholder-gray-400"
                        placeholder="Partagez votre avis..."><?= $article['content'] ?>
            </textarea>
          </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2">Category</label>
                <select name="cat_id" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none bg-white">
                    <option value="">Sélectionner un category</option>
                    <?php foreach($category as $cat): ?>
                        <option value="<?= $cat['id_cat'] ?>"><?= htmlspecialchars($cat['nom_cat']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div> 

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 rounded-lg transition-colors shadow-md">
                Mettre à jour
            </button>
        </form>
    </div>

</body>
</html>