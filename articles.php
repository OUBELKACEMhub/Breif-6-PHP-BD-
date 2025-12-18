<?php


include "db.php";
session_start();
$role=$_SESSION['role'];
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $pdo->prepare("DELETE FROM comments WHERE id_artc = :id")->execute([':id' => $id]);
        
        $sql = "DELETE FROM postes WHERE id_artc = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        header("Location: articles.php"); 
        exit;
    } catch (PDOException $e) {
        die("Erreur de suppression : " . $e->getMessage());
    }
}


try {
    $sql = "SELECT postes.*, users.username                  
            FROM postes 
            LEFT JOIN users ON postes.user_id = users.user_id 
            ORDER BY postes.id_artc DESC";
            
    $stmt = $pdo->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de récupération des articles : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShine Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sidebar-bg: #1e1b2e;
            --purple-primary: #7c3aed;
            --pink-accent: #f43f5e;
            --bg-body: #f3f4f6;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); }
        .sidebar-bg { background-color: var(--sidebar-bg); }
        
        /* Styles du menu actif */
        .menu-item.active11 {
            background-color: #f3f4f6;
            color: #1f2937;
            border-top-left-radius: 30px;
            border-bottom-left-radius: 30px;
            position: relative;
        }
        .menu-item.active11::before, .menu-item.active11::after {
            content: '';
            position: absolute;
            right: 0;
            width: 21px;
            height: 20px;
            background: transparent;
            pointer-events: none;
        }
        .menu-item.active11::before { top: -20px; box-shadow: 10px 10px 0 #f3f4f6; border-bottom-right-radius: 20px; }
        .menu-item.active11::after { bottom: -20px; box-shadow: 10px -10px 0 #f3f4f6; border-top-right-radius: 20px; }

        /* Styles du Toggle Switch */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #7c3aed;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #7c3aed;
        }

    /*    .fa-chevron-up:before {
    content: "\f078";
}*/
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <aside class="w-64 sidebar-bg text-gray-300 flex flex-col transition-all duration-300 hidden md:flex">
        <div class="h-16 flex items-center px-6 border-b border-gray-700">
            <div class="flex items-center gap-2 font-bold text-white text-xl">
                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-moon"></i>
                </div>
                <span>Dashboard </span>
            </div>
        </div>
        <nav class="flex-1 py-4 overflow-y-auto">
            <div class="px-6 mb-2 text-xs uppercase text-gray-500 font-semibold">System</div>
                            <button id="blog-toggle" class="w-full flex items-center justify-between p-3 bg-purple-600 text-white rounded-lg mb-1 transition-colors hover:bg-purple-700">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-newspaper"></i>
                            <span>Blog</span>
                        </div>
                        <i id="blog-arrow" class="fa-solid fa-chevron-down transition-transform duration-300"></i>
                    </button>

                <ul id="blog-menu" class="pl-0 space-y-1 hidden transition-all duration-300">
                <li>
                    <a href="categorie.php" class="flex items-center gap-3 p-3 hover:text-white transition-colors pl-8">
                        <i class="fa-regular fa-file"></i> Categories
                    </a>
                </li>
                <li class="relative">
                    <a href="articles.php" class="menu-item active11 flex items-center gap-3 p-3 font-medium pl-8 w-[calc(100%+1.5rem)]">
                        <i class="fa-solid fa-file-lines"></i> Articles
                    </a>
                </li>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "author"): ?>
                    <li>
                        <a href="comments.php" class="flex items-center gap-3 p-3 hover:text-white transition-colors pl-8">
                            <i class="fa-regular fa-comments"></i> Comments
                        </a>
                    </li>
                    <?php  endif;  ?>
            </ul>
             <div class="mt-5 px-6 mb-2 text-xs uppercase text-gray-500 font-semibold">Modules</div>
             <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "admin"): ?>
                    <ul class="px-3">
                <li><a href="users.php" class="flex items-center gap-3 p-3 hover:text-white rounded-lg"><i class="fa-solid fa-users"></i> Users</a></li>
            </ul>
                    <?php  endif;  ?>
           
        </nav>
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="w-10 h-10 rounded-full bg-blue-100">
                <div class="overflow-hidden">
                    <h4 class="text-sm font-white text-white"><?= $role ?></h4>
                </div>
                <a href="logout.php">
                <button class="ml-auto text-gray-500 hover:text-red-400"><i class="fa-solid fa-power-off"></i></button>
                </a>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-100">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
            <div class="flex items-center text-sm text-gray-500">
                <span class="text-gray-700">Articles Management</span>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Articles Gallery</h1>
                 <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "author"): ?>
                <a href="addArticle.php" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus"></i> Create Article
                </a>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($articles as $article): ?>
                
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                    
                    <div class="relative h-48 w-full">
                       <a href="articleDetail.php?id=<?= $article['id_artc'] ?>">
                         <img  src="<?= htmlspecialchars($article['image_url']) ?>" 
                             alt="Cover" 
                             class="w-full h-full object-cover">
                        </a>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2" title="<?= htmlspecialchars($article['title']) ?>">
                            <?= htmlspecialchars($article['title']) ?>
                        </h3>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4 mt-auto">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-user-circle text-gray-400"></i>
                                <span><?= htmlspecialchars($article['username'] ?? 'Inconnu') ?></span>
                            </div>
                            <div class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded-full text-xs">
                                <i class="fa-solid fa-eye text-purple-500"></i>
                                <span><?= $article['view_count'] ?></span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 my-3"></div>

                        <div class="flex items-center justify-between pt-2">
                            <div class="flex items-center gap-2 text-xs text-gray-400 font-medium">
                            <i class="fa-regular fa-calendar text-gray-400"></i>
                            <span><?= date('d M Y', strtotime($article['date_cr'])) ?></span>
                        </div>
                         <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "author" && $_SESSION['user_id']==$article['user_id']  ):  ?>

                            <div class="flex items-center gap-2">
                                
                                <a href="editArticle.php?id=<?= $article['id_artc'] ?>" 
                                class="w-8 h-8 rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors flex items-center justify-center shadow-sm" 
                                title="Edit">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>

                                <a href="articles.php?delete=<?= $article['id_artc'] ?>" 
                                onclick="return confirm('Supprimer cet article ?')" 
                                class="w-8 h-8 rounded bg-pink-50 text-pink-500 hover:bg-pink-600 hover:text-white transition-colors flex items-center justify-center shadow-sm" 
                                title="Delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                                
                            </div>

                        <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>

      
    </main>

    <script src="blog.js"></script>
    
</body>
</html>