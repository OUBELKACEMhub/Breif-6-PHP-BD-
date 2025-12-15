<?php
include "db.php";

// Logic to update status (Keep this or remove if categories don't have status)
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);
    try {
        // CHANGE 'categories' AND 'id_cat' TO YOUR ACTUAL TABLE/COLUMN NAMES
        $sql = "UPDATE categories SET status = :status WHERE id_cat = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $status, ':id' => $id]);
        echo "success";
        exit;
    } catch (PDOException $e) {
        echo "error";
        exit;
    }
}

// Fetch Categories
try {
    // CHANGE 'categories' TO YOUR TABLE NAME
    $sql = "SELECT * FROM categories ORDER BY id_cat DESC";
    
    // Uncomment the lines below when you have created the table in your DB
    // $stmt = $pdo->query($sql);
    // $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // FOR NOW: Empty array so the page loads without error
    $categories = []; 

} catch (PDOException $e) {
    die("Erreur de récupération des catégories : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShine - Categories</title>
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
        
        /* Menu Item Active Style */
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
            <button class="ml-auto text-gray-500 hover:text-white"><i class="fa-solid fa-moon"></i></button>
        </div>

        <nav class="flex-1 py-4 overflow-y-auto">
            <div class="px-6 mb-2 text-xs uppercase text-gray-500 font-semibold">System</div>
            
            <div class="px-3">
                <button class="w-full flex items-center justify-between p-3 bg-purple-600 text-white rounded-lg mb-1">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>Blog</span>
                    </div>
                    <i class="fa-solid fa-chevron-up text-xs"></i>
                </button>
                
                <ul class="pl-0 space-y-1">
                    <li class="relative">
                        <a href="categories.php" class="menu-item active11 flex items-center gap-3 p-3 font-medium pl-8 w-[calc(100%+1.5rem)]">
                            <i class="fa-regular fa-file"></i> Categories
                        </a>
                    </li>
                    <li>
                        <a href="dashboard.php" class="flex items-center gap-3 p-3 hover:text-white transition-colors pl-8">
                            <i class="fa-solid fa-file-lines"></i> Articles
                        </a>
                    </li>
                    <li>
                        <a href="comments.php" class="flex items-center gap-3 p-3 hover:text-white transition-colors pl-8">
                            <i class="fa-regular fa-comments"></i> Comments
                        </a>
                    </li>
                </ul>
            </div>

            <div class="mt-4 px-6 mb-2 text-xs uppercase text-gray-500 font-semibold">Modules</div>
            <ul class="px-3">
                <li><a href="#" class="flex items-center gap-3 p-3 hover:text-white rounded-lg"><i class="fa-solid fa-users"></i> Users</a></li>
                <li><a href="#" class="flex items-center gap-3 p-3 hover:text-white rounded-lg"><i class="fa-solid fa-book"></i> Dictionary</a></li>
            </ul>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="w-10 h-10 rounded-full bg-blue-100">
                <div class="overflow-hidden">
                    <h4 class="text-sm font-white text-white">Admin</h4>
                    <p class="text-xs text-gray-500 truncate">BookShine@cutcode.dev</p>
                </div>
                <button class="ml-auto text-gray-500 hover:text-red-400"><i class="fa-solid fa-power-off"></i></button>
            </div>
            <button class="mt-4 flex items-center gap-2 text-gray-400 text-sm hover:text-white">
                <i class="fa-solid fa-circle-arrow-left"></i> Collapse menu
            </button>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-100">
        
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
            <div class="flex items-center text-sm text-gray-500">
                <a href="#" class="text-pink-500 hover:text-pink-600"><i class="fa-solid fa-house"></i></a>
                <span class="mx-2">/</span>
                <span class="text-gray-700">Categories</span>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" placeholder="Search (Ctrl+K)" class="bg-gray-100 text-sm rounded-lg pl-4 pr-10 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400 text-xs"></i>
                </div>
                <button class="border border-gray-300 rounded px-3 py-1 text-sm font-medium">en</button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Categories Management</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-3xl font-bold text-gray-800 mb-1"><?= count($categories) ?></div>
                    <div class="text-gray-500 text-sm">Total Categories</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-3xl font-bold text-gray-800 mb-1">0</div>
                    <div class="text-gray-500 text-sm">Active Categories</div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-plus"></i> Add Category
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-filter"></i> Filters
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                            <th class="p-4 w-16">
                                <div class="flex items-center gap-1 cursor-pointer">ID <i class="fa-solid fa-sort"></i></div>
                            </th>
                            <th class="p-4">Name</th>
                            <th class="p-4">Description</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        <?php if(empty($categories)): ?>
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">
                                    No categories found. Add one to get started.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($categories as $category): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4">
                                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold">
                                        <?= $category['id_cat'] ?>
                                    </span>
                                </td>

                                <td class="p-4 font-medium">
                                    <?= htmlspecialchars($category['name']) ?>
                                </td>

                                <td class="p-4 text-gray-500">
                                    <?= htmlspecialchars($category['description'] ?? '-') ?>
                                </td>

                                <td class="p-4">
                                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                        <input type="checkbox"
                                            class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer transition-all duration-300 ease-in-out border-gray-300 top-0 left-0"
                                            <?= ($category['status'] ?? 0) ? 'checked' : '' ?> />
                                        <label class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer transition-colors duration-300 ease-in-out"></label>
                                    </div>
                                </td>

                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="w-8 h-8 rounded bg-purple-600 text-white hover:bg-purple-700 flex items-center justify-center shadow-sm">
                                            <i class="fa-solid fa-pencil"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded bg-pink-500 text-white hover:bg-pink-600 flex items-center justify-center shadow-sm">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-between items-center text-xs text-gray-500">
                <span>Showing results</span>
                <div class="flex gap-1">
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">Previous</button>
                    <button class="px-3 py-1 bg-purple-600 text-white rounded">1</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">Next</button>
                </div>
            </div>

        </div>
    </main>

    <script src="switchToogle.js"> </script>
</body>
</html>