<?php
include "db.php";
session_start();
$role=$_SESSION['role'];
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $sql = "DELETE FROM comments WHERE id_comnt = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        header("Location: comments.php"); 
        exit;
    } catch (PDOException $e) {
        die("Erreur de suppression : " . $e->getMessage());
    }
}

try {
    $sql = "SELECT comments.*, users.username, postes.title 
            FROM comments 
            LEFT JOIN users ON comments.user_id = users.user_id 
            LEFT JOIN postes ON comments.id_artc = postes.id_artc 
            ORDER BY comments.Date_cr DESC";
            
    $stmt = $pdo->query($sql);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $comments = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShine - Comments</title>
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
                    <li>
                        <a href="articles.php" class="flex items-center gap-3 p-3 hover:text-white transition-colors pl-8">
                            <i class="fa-solid fa-file-lines"></i> Articles
                        </a>
                    </li>
                    <li class="relative">
                        <a href="comments.php" class="menu-item active11 flex items-center gap-3 p-3 font-medium pl-8 w-[calc(100%+1.5rem)]">
                            <i class="fa-regular fa-comments"></i> Comments
                        </a>
                    </li>
                </ul>
                 <div class="mt-4 px-6 mb-2 text-xs uppercase text-gray-500 font-semibold">Modules</div>
             <ul class="px-3">
                <li><a href="users.php" class="flex items-center gap-3 p-3 hover:text-white rounded-lg"><i class="fa-solid fa-users"></i> Users</a></li>
            </ul>
        </nav>
        
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="w-10 h-10 rounded-full bg-blue-100">
                <div class="overflow-hidden">
                    <h4 class="text-sm font-white text-white"><?= $role ?></h4>
                </div>
                    <a href="index.php">
                    <button class="ml-auto text-gray-500 hover:text-red-400"><i class="fa-solid fa-power-off"></i></button>
                    </a>            
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-100">
        
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
            <div class="flex items-center text-sm text-gray-500">
                <a href="#" class="text-pink-500 hover:text-pink-600"><i class="fa-solid fa-house"></i></a>
                <span class="mx-2">/</span>
                <span class="text-gray-700">Comments</span>
            </div>

            <div class="flex items-center gap-4">
                <input type="text" placeholder="Search..." class="bg-gray-100 text-sm rounded-lg pl-4 pr-10 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Comments Management</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-3xl font-bold text-gray-800 mb-1"><?= count($comments) ?></div>
                    <div class="text-gray-500 text-sm">Total Comments</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                            <th class="p-4 w-16">ID</th>
                            <th class="p-4">Author</th>
                            <th class="p-4">Content</th>
                            <th class="p-4">Article</th>
                            <th class="p-4">Date</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        <?php if(empty($comments)): ?>
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-500">
                                    No comments found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($comments as $comment): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4">
                                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold">
                                        <?= $comment['id_comnt'] ?>
                                    </span>
                                </td>

                                <td class="p-4 font-medium text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-user text-gray-400"></i>
                                        <?= htmlspecialchars($comment['username'] ?? 'Guest') ?>
                                    </div>
                                </td>

                                <td class="p-4 text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($comment['contenu']) ?>">
                                    <?= htmlspecialchars(substr($comment['contenu'], 0, 50)) ?>
                                    <?= strlen($comment['contenu']) > 50 ? '...' : '' ?>
                                </td>

                                <td class="p-4 text-purple-600 font-medium text-xs">
                                    <?= htmlspecialchars($comment['title'] ?? 'Deleted Article') ?>
                                </td>

                                <td class="p-4 text-gray-400 text-xs">
                                    <?= date('M d, Y', strtotime($comment['Date_cr'])) ?>
                                </td>


                                <td class="p-4 text-right">
                                    <a href="comments.php?delete=<?= $comment['id_comnt'] ?>" 
                                       onclick="return confirm('Are you sure you want to delete this comment?')"
                                       class="inline-flex w-8 h-8 rounded bg-pink-500 text-white hover:bg-pink-600 items-center justify-center shadow-sm">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </main>
    
    <script src="blog.js"></script>
</body>
</html>