<?php
include "db.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Erreur");
}
session_start(); 
$login = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? null;
$id_article = intval($_GET['id']);
$edit_comment = null;
try {
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
        $contenu = htmlspecialchars($_POST['contenu']);
        
        if (!empty($contenu)) {
            $stmt = $pdo->prepare("INSERT INTO comments (Date_cr, contenu, statues, user_id, id_artc) VALUES (NOW(), ?, 'active', ?, ?)");
            $stmt->execute([$contenu, $login , $id_article]);
            
            header("Location: ?id=" . $id_article . "#comments-section");
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_comment'])) {
        $id_comnt = intval($_POST['id_comnt']);
        $contenu = htmlspecialchars($_POST['contenu']);
        
        $stmt = $pdo->prepare("UPDATE comments SET contenu = ? WHERE id_comnt = ? AND user_id = ?");
        $stmt->execute([$contenu, $id_comnt, null]);
        
        header("Location: ?id=" . $id_article . "#comments-section");
        exit;
    }


    $stmtView = $pdo->prepare("UPDATE postes SET view_count = view_count + 1 WHERE id_artc = ?");
    $stmtView->execute([$id_article]);

    $sql = "SELECT p.*, u.username, c.nom_cat as category_name 
            FROM postes p
            LEFT JOIN users u ON p.user_id = u.user_id
            LEFT JOIN category c ON p.id_cat = c.id_cat
            WHERE p.id_artc = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_article]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        die("Erreur: Article inrouvable");
    }

    $sqlCom = "SELECT c.*, u.username 
               FROM comments c 
               LEFT JOIN users u ON c.user_id = u.user_id 
               WHERE c.id_artc = ? 
               ORDER BY c.Date_cr DESC";
    $stmtCom = $pdo->prepare($sqlCom);
    $stmtCom->execute([$id_article]);
    $comments = $stmtCom->fetchAll(PDO::FETCH_ASSOC);


    if (isset($_GET['edit_id'])) {
        $edit_id = intval($_GET['edit_id']);
        $stmtEdit = $pdo->prepare("SELECT * FROM comments WHERE id_comnt = ? AND user_id = ?");
        $stmtEdit->execute([$edit_id, null ?? 0]);
        $edit_comment = $stmtEdit->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    die("Erreur SQL: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - BookShine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <nav class="bg-white border-b border-gray-200 py-4 px-6 mb-8">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="articles.php" class="text-purple-600 font-bold text-lg"><i class="fa-solid fa-book-open"></i> BookShine</a>
            <a href="<?= $login ? 'articles.php' : 'index.php' ?>" class="text-gray-500 hover:text-purple-600 text-sm flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Retour
            </a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 pb-12">
        
        <div class="text-center mb-8">
            <span class="inline-block bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full uppercase font-bold tracking-wide mb-3">
                <?= htmlspecialchars($article['category_name'] ?? 'General') ?>
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
                <?= htmlspecialchars($article['title']) ?>
            </h1>
            
            <div class="flex flex-wrap justify-center items-center gap-6 text-sm text-gray-500">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span class="font-medium text-gray-900"><?= htmlspecialchars($article['username'] ?? 'Anonyme') ?></span>
                </div>
                
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-calendar"></i>
                    <span><?= date('d M Y', strtotime($article['date_cr'])) ?></span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-eye text-purple-400"></i>
                    <span><?= $article['view_count'] ?> Vues</span>
                </div>
            </div>
        </div>

        <div class="mb-9 shadow-lg rounded-2xl overflow-hidden">
            <img src="<?= htmlspecialchars($article['image_url']) ?>" 
                 alt="<?= htmlspecialchars($article['title']) ?>" 
                 class="w-full h-[500px] object-cover hover:scale-105 transition-transform duration-700">
        </div>

        <article class="prose max-w-none text-lg leading-relaxed text-gray-700 bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100">
            <?= nl2br(htmlspecialchars($article['content'])) ?>
        </article>

        <div class="mt-8 border-t border-gray-200 pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-gray-400">
            <div class="mb-4 md:mb-0">
                <?php if($article['date_md'] && $article['date_md'] != $article['date_cr']): ?>
                    <span class="bg-yellow-50 text-yellow-700 px-2 py-1 rounded border border-yellow-100">
                        <i class="fa-solid fa-pen-to-square"></i> Modifié le: <?= date('d/m/Y H:i', strtotime($article['date_md'])) ?>
                    </span>
                <?php else: ?>
                    <span>Article original (Jamais modifié)</span>
                <?php endif; ?>
            </div>
            <div class="font-mono">
                ID Article: #<?= $article['id_artc'] ?>
            </div>
        </div>

        <div id="comments-section" class="mt-12 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            
            <div class="flex gap-2 items-center mb-6 border-b pb-4">
                <i class="fa-regular fa-comments text-purple-600"></i>
                <label class="text-gray-800 text-xl font-bold">
                    Commentaires (<?= count($comments) ?>)
                </label>
            </div>

            <div class="space-y-6 mb-10">
                <?php if(count($comments) > 0): ?>
                    <?php foreach($comments as $com): ?>
                        <div class="flex gap-4 items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold">
                                <?= strtoupper(substr($com['username'] ?? 'U', 0, 1)) ?>
                            </div>
                            
                            <div class="flex-grow bg-gray-50 p-4 rounded-lg rounded-tl-none relative group">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-bold text-sm text-gray-900"><?= htmlspecialchars($com['username'] ?? 'Anonyme') ?></span>
                                    <span class="text-xs text-gray-400"><?= date('d M Y à H:i', strtotime($com['Date_cr'])) ?></span>
                                </div>
                                <p class="text-gray-700 text-sm leading-relaxed">
                                    <?= nl2br(htmlspecialchars($com['contenu'])) ?>
                                </p>

                                <?php if($com['user_id'] == null): ?>
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="?id=<?= $id_article ?>&edit_id=<?= $com['id_comnt'] ?>#comments-form" 
                                           class="text-gray-400 hover:text-blue-600 text-xs bg-white px-2 py-1 rounded border shadow-sm">
                                            <i class="fa-solid fa-pen"></i> Modifier
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 italic text-center py-4">Soyez le premier à commenter cet article !</p>
                    
                <?php endif; ?>
            </div>

            <div id="comments-form" class="border-t pt-6">
                <h3 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">
                    <?= $edit_comment ? 'Modifier votre commentaire' : 'Laisser un commentaire' ?>
                </h3>
                
                <form method="POST" action="" class="relative">
                    <?php if ($edit_comment): ?>
                        <input type="hidden" name="id_comnt" value="<?= $edit_comment['id_comnt'] ?>">
                    <?php endif; ?>

                    <textarea name="contenu" rows="4" required
                        class="w-full px-4 py-3 pb-14 rounded-lg border border-gray-300 bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none resize-none transition-all placeholder-gray-400"
                        placeholder="Partagez votre avis..."
                    ><?= $edit_comment ? htmlspecialchars($edit_comment['contenu']) : '' ?></textarea>

                    <div class="absolute bottom-3 right-3 flex gap-2">
                        <?php if ($edit_comment): ?>
                            <a href="?id=<?= $id_article ?>#comments-section" 
                               class="bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                               Annuler
                            </a>
                            <button type="submit" name="update_comment" 
                                class="bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors shadow-md text-sm">
                                Mettre à jour
                            </button>
                        <?php else: ?>
                            <button type="submit" name="submit_comment" 
                                class="bg-[#6d28d9] text-white font-medium py-2 px-6 rounded-lg hover:bg-[#5b21b6] transition-colors shadow-md text-sm">
                                Publier
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

        </div>


    </main>
</body>
</html>