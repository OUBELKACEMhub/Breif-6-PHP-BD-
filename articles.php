<?php
include "db.php";

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);
    try {
        $sql = "UPDATE postes SET status = :status WHERE id_artc = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $status, ':id' => $id]);
        echo "success";
        exit;
    } catch (PDOException $e) {
        echo "error";
        exit;
    }
}

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

        .toggle-checkbox:checked {
            right: 0;
            border-color: #7c3aed;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #7c3aed;
        }
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
            <div class="px-3">
                <button class="w-full flex items-center justify-between p-3 bg-purple-600 text-white rounded-lg mb-1">
                    <div class="flex items-center gap-3"><i class="fa-solid fa-newspaper"></i><span>Blog</span></div>
                </button>
                <ul class="pl-0 space-y-1">
                    <li><a href="categorie.php" class="flex items-center gap-3 p-3 hover:text-white transition-colors pl-8"><i class="fa-regular fa-file"></i> Categories</a></li>
                    <li class="relative"><a href="articles.php" class="menu-item active11 flex items-center gap-3 p-3 font-medium pl-8 w-[calc(100%+1.5rem)]"><i class="fa-solid fa-file-lines"></i> Articles</a></li>
                    <li><a href="comments.php" class="flex items-center gap-3 p-3 hover:text-white transition-colors pl-8"><i class="fa-regular fa-comments"></i> Comments</a></li>
                </ul>
            </div>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-100">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
            <div class="flex items-center text-sm text-gray-500">
                <span class="text-gray-700">Articles Management</span>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Articles</h1>
                <a href="addArticle.php" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus"></i> Create Article
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                            <th class="p-4 w-16">ID</th>
                            <th class="p-4">Author</th>
                            <th class="p-4">Title</th>
                            <th class="p-4">Image</th>
                            <th class="p-4">Views</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        <?php foreach($articles as $article): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4"><span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold"><?= $article['id_artc'] ?></span></td>
                            <td class="p-4"><?= htmlspecialchars($article['username'] ?? 'Inconnu') ?></td>
                            <td class="p-4"><?= htmlspecialchars($article['title']) ?></td>
                            <td class="p-4"><img src="<?= htmlspecialchars($article['image_url']) ?>" class="w-10 h-10 rounded object-cover shadow-sm"></td>
                            <td class="p-4"><?= $article['view_count'] ?></td>
                            
                            <td class="p-4">
                                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox"
                                           class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer transition-all duration-300 ease-in-out border-gray-300 top-0 left-0"
                                           onchange="updateStatus(<?= $article['id_artc'] ?>, this.checked)"
                                           <?= $article['status'] ? 'checked' : '' ?> />
                                    <label class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer transition-colors duration-300 ease-in-out"></label>
                                </div>
                            </td>

                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="editArticle.php?id=<?= $article['id_artc'] ?>" class="w-8 h-8 rounded bg-blue-500 text-white hover:bg-blue-600 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <a href="articles.php?delete=<?= $article['id_artc'] ?>" onclick="return confirm('Supprimer cet article ?')" class="w-8 h-8 rounded bg-pink-500 text-white hover:bg-pink-600 flex items-center justify-center shadow-sm">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="switchToogle.js"></script>
    <script>
        function updateStatus(id, isChecked) {
            const status = isChecked ? 1 : 0;
            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', status);

            fetch('articles.php', { method: 'POST', body: formData })
            .then(response => response.text())
            .then(data => { if(data.trim() !== 'success') alert('Error updating status'); });
        }
    </script>
</body>
</html>