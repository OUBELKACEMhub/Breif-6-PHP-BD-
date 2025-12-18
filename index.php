<?php
session_start();
session_unset(); 
include "db.php";

$login_error = null;


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_btn'])) {
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ($password === $user['pass_word']) { 
                    
                
                    $_SESSION['user_id'] = $user['user_id']; 
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    header("Location: articles.php");
                    exit;
                } else {
                    $login_error = "Mot de passe incorrect.";
                }
            } else {
                $login_error = "Aucun compte trouvé avec cet email.";
            }
        } catch (PDOException $e) {
            $login_error = "Erreur de connexion : " . $e->getMessage();
        }
    } else {
        $login_error = "Veuillez remplir tous les champs.";
    }
}
try {
    $stmtCount = $pdo->query("SELECT COUNT(*) FROM postes");
    $totalArticles = $stmtCount->fetchColumn();

    $stmtViews = $pdo->query("SELECT SUM(view_count) FROM postes");
    $totalViews = $stmtViews->fetchColumn();
    
    $stmtCats = $pdo->query("SELECT COUNT(*) FROM category");
    $totalCats = $stmtCats->fetchColumn();
} catch (PDOException $e) {
    $totalArticles = 0; $totalViews = 0; $totalCats = 0;
}

try {
    $sql = "SELECT p.*, u.username, c.nom_cat 
            FROM postes p 
            LEFT JOIN users u ON p.user_id = u.user_id 
            LEFT JOIN category c ON p.id_cat = c.id_cat
            ORDER BY p.date_cr DESC";
    $stmt = $pdo->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $articles = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShine - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="text-gray-800">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-500">
                        <i class="fa-solid fa-book-open text-purple-600 mr-2"></i>BookShine
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <span class="text-sm text-gray-600 hidden md:block">Bonjour, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                        <?php if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'author'): ?>
                            <a href="articles.php" class="text-sm bg-purple-100 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-200 transition">Dashboard</a>
                        <?php endif; ?>
                        <a href="logout.php" class="text-gray-500 hover:text-red-500"><i class="fa-solid fa-power-off"></i></a>
                    <?php else: ?>
                        <a href="#login-section" class="text-sm font-medium text-purple-600 hover:text-purple-500 md:hidden">Se connecter</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gradient-to-r from-indigo-900 via-purple-900 to-pink-800 text-white py-12 px-4 shadow-lg">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 text-center">Bienvenue sur BookShine</h1>
            <p class="text-center text-purple-200 mb-10">Explorez, Lisez, Partagez.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                    <div class="text-4xl font-bold mb-1"><?= $totalArticles ?></div>
                    <div class="text-sm text-purple-200 uppercase tracking-wider">Articles Publiés</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                    <div class="text-4xl font-bold mb-1"><?= $totalViews ?? 0 ?></div>
                    <div class="text-sm text-pink-200 uppercase tracking-wider">Lectures Totales</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                    <div class="text-4xl font-bold mb-1"><?= $totalCats ?></div>
                    <div class="text-sm text-blue-200 uppercase tracking-wider">Catégories</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-purple-500 pl-3">Derniers Articles</h2>
                </div>

                <?php foreach($articles as $art): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col md:flex-row h-auto md:h-64">
                    <div class="md:w-2/5 h-48 md:h-full relative overflow-hidden">
                        <img src="<?= htmlspecialchars($art['image_url']) ?>" alt="Cover" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                        <span class="absolute top-2 left-2 bg-black/60 text-white text-xs px-2 py-1 rounded backdrop-blur-sm">
                            <?= htmlspecialchars($art['nom_cat'] ?? 'Général') ?>
                        </span>
                    </div>

                    <div class="p-6 md:w-3/5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                                <i class="fa-regular fa-calendar"></i> <?= date('d M Y', strtotime($art['date_cr'])) ?>
                                <span class="mx-1">•</span>
                                <i class="fa-regular fa-user"></i> <?= htmlspecialchars($art['username'] ?? 'Admin') ?>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3 leading-tight line-clamp-2">
                                <a href="articleDetail.php?id=<?= $art['id_artc'] ?>" class="hover:text-purple-600 transition-colors">
                                    <?= htmlspecialchars($art['title']) ?>
                                </a>
                            </h3>
                            <p class="text-gray-500 text-sm line-clamp-2">
                                Cliquez pour lire l'article complet et voir les commentaires...
                            </p>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-1 text-gray-500 text-sm">
                                <i class="fa-solid fa-eye text-purple-400"></i> <?= $art['view_count'] ?>
                            </div>
                            <a href="articleDetail.php?id=<?= $art['id_artc'] ?>" class="text-purple-600 font-semibold text-sm hover:underline">
                                Lire la suite <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="lg:col-span-1 space-y-8">
                
                <div id="login-section" class="sticky top-24">
                    
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <div class="bg-white rounded-2xl shadow-lg border border-purple-100 p-6">
                            <div class="text-center mb-6">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 text-purple-600 text-xl">
                                    <i class="fa-solid fa-right-to-bracket"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Espace Membre</h3>
                                <p class="text-sm text-gray-500">Connectez-vous pour commenter</p>
                            </div>

                            <?php if($login_error): ?>
                                <div class="bg-red-50 text-red-600 text-sm p-3 rounded-lg mb-4 text-center border border-red-100">
                                    <i class="fa-solid fa-circle-exclamation mr-1"></i> <?= $login_error ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-3 text-gray-400"><i class="fa-regular fa-envelope"></i></span>
                                            <input type="email" name="email" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-3 text-gray-400"><i class="fa-solid fa-lock"></i></span>
                                            <input type="password" name="password" required class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition-all">
                                        </div>
                                    </div>
                                    <button type="submit" name="login_btn" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2.5 rounded-lg shadow-md transition-colors duration-200">
                                        Se connecter
                                    </button>
                                </div>
                            </form>
                            
                            <div class="mt-6 text-center border-t pt-4">
                                <p class="text-sm text-gray-500">Pas encore de compte ?</p>
                                <a href="signup.php" class="text-purple-600 font-medium hover:underline">Créer un compte</a>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 text-center">
                            <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-purple-500 to-pink-500 p-1 mb-4">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-2xl font-bold text-gray-700 uppercase">
                                    <?= substr($_SESSION['username'], 0, 1) ?>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($_SESSION['username']) ?></h3>
                            <span class="inline-block bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full uppercase font-bold mt-2 mb-4">
                                <?= $_SESSION['role'] ?>
                            </span>
                            
                            <div class="space-y-2">
                                <?php if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'author'): ?>
                                <a href="articles.php" class="block w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-2 rounded-lg border border-gray-200 transition">
                                    <i class="fa-solid fa-gauge mr-2"></i> Dashboard
                                </a>
                                <?php endif; ?>
                                <a href="logout.php" class="block w-full bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 rounded-lg border border-red-100 transition">
                                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Déconnexion
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mt-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                        <h4 class="font-bold text-lg mb-2"><i class="fa-solid fa-star mr-2 text-yellow-300"></i>Rejoignez-nous!</h4>
                        <p class="text-sm text-blue-100 opacity-90">
                            BookShine est une communauté de passionnés. Connectez-vous pour partager vos avis.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
    
    <footer class="bg-white border-t border-gray-200 py-8 mt-12">
        <div class="text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> BookShine. Tous droits réservés.
        </div>
    </footer>

</body>
</html>