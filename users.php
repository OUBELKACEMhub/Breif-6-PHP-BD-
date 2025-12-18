<?php
include "db.php";
session_start();
$role=$_SESSION['role'];
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
       
        $sql = "DELETE FROM users WHERE user_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        header("Location: users.php"); 
        exit;
    } catch (PDOException $e) {
        $error = "Erreur de suppression : " . $e->getMessage();
    }
}

try {
    $sql = "SELECT * FROM users ORDER BY date_insc DESC";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users = [];
    $error = "Erreur de chargement : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShine - Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
  

<?php
include "db.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $newStatus = intval($_GET['status']);

    try {
        $sql = "UPDATE users SET status = :status WHERE user_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $newStatus, ':id' => $id]);
        
        header("Location: users.php"); 
        exit;
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $sql = "DELETE FROM users WHERE user_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        header("Location: users.php"); 
        exit;
    } catch (PDOException $e) {
        $error = "Erreur de suppression : " . $e->getMessage();
    }
}

try {
    $sql = "SELECT * FROM users ORDER BY date_insc DESC";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShine - Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sidebar-bg: #1e1b2e;
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
            border-color: #10b981; 
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #10b981; 
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
                <li class="relative">
                    <a href="articles.php" class="menu-item  flex items-center gap-3 p-3 font-medium pl-8 w-[calc(100%+1.5rem)]">
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
             <div class="mt-4 px-6 mb-2 text-xs uppercase text-gray-500 font-semibold">Modules</div>
            <a href="users.php" class="menu-item active11 flex items-center gap-3 p-3 font-medium pl-8 w-[calc(100%+1.5rem)]">
                        <i class="fa-solid fa-solid fa-users"></i> Users
                    </a>
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
                <a href="#" class="text-pink-500 hover:text-pink-600"><i class="fa-solid fa-house"></i></a>
                <span class="mx-2">/</span>
                <span class="text-gray-700">Users Management</span>
            </div>
            <div class="flex items-center gap-4">
                <input type="text" placeholder="Search user..." class="bg-gray-100 text-sm rounded-lg pl-4 pr-10 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Users List</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-gray-800 mb-1"><?= count($users) ?></div>
                        <div class="text-gray-500 text-sm">Total Users</div>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 text-xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <?php if(isset($error)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-6"><?= $error ?></div>
            <?php endif; ?>

            <div class="flex justify-between gap-4 mb-6">
                <a href="addUser.php" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus"></i> Add User
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                            <th class="p-4 w-16">ID</th>
                            <th class="p-4">User Info</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">Date Inscription</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        <?php if(empty($users)): ?>
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    No users found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($users as $user): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4">
                                    <span class="text-gray-500 font-mono">#<?= $user['user_id'] ?></span>
                                </td>

                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username']) ?>&background=random&color=fff" class="w-10 h-10 rounded-full">
                                        <div>
                                            <div class="font-medium text-gray-900"><?= htmlspecialchars($user['username']) ?></div>
                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($user['name'] ?? '') ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-4 text-gray-600">
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>

                                <td class="p-4">
                                    <?php if(strtolower($user['role']) === 'admin'): ?>
                                        <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold border border-purple-200">
                                            <?= htmlspecialchars($user['role']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold border border-blue-100">
                                            <?= htmlspecialchars($user['role']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="p-4 text-gray-500">
                                    <?= date('d M Y', strtotime($user['date_insc'])) ?>
                                </td>

                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="editUser.php?id=<?= $user['user_id'] ?>" class="w-8 h-8 rounded bg-gray-100 text-gray-600 hover:bg-purple-600 hover:text-white flex items-center justify-center transition-colors">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>

                                        <a href="users.php?delete=<?= $user['user_id'] ?>" 
                                        onclick="return confirm('Attention: Delete this user?')"
                                        class="w-8 h-8 rounded bg-pink-50 text-pink-500 hover:bg-pink-600 hover:text-white flex items-center justify-center transition-colors">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </a>
                                    </div>
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