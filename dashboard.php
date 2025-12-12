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
    <title>BookShine Dashboard Clone</title>
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
            width: 20px;
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
                    <li>
                        <a href="#" class="flex items-center gap-3 p-3 hover:text-white transition-colors pl-8">
                            <i class="fa-regular fa-file"></i> Categories
                        </a>
                    </li>
                    <li class="relative">
                        <a href="#" class="menu-item active11 flex items-center gap-3 p-3 font-medium pl-8 w-[calc(100%+1.5rem)]">
                            <i class="fa-solid fa-file-lines"></i> Articles
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 p-3 hover:text-white transition-colors pl-8">
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
                <span class="text-gray-700">Articles</span>
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
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Articles</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-3xl font-bold text-gray-800 mb-1">20</div>
                    <div class="text-gray-500 text-sm">Articles</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-3xl font-bold text-gray-800 mb-1">27</div>
                    <div class="text-gray-500 text-sm">Comments</div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-plus"></i> Create
                    </button>
                    <button class="bg-white border border-gray-300 text-gray-600 w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-50">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-pencil"></i> Import
                    </button>
                    <button class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-filter"></i> Filters
                    </button>
                </div>
            </div>

            <div class="flex gap-2 mb-6">
                <button class="px-4 py-2 bg-white text-gray-700 text-sm rounded-lg border border-gray-200 hover:bg-gray-50">Article with author</button>
                <button class="px-4 py-2 bg-white text-gray-700 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-user-group text-gray-400"></i> Article without an author
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                            <th class="p-4 w-16">
                                <div class="flex items-center gap-1 cursor-pointer">ID <i class="fa-solid fa-sort"></i></div>
                            </th>
                            <th class="p-4">Author</th>
                            <th class="p-4">Title</th>
                            <th class="p-4">Thumbnail</th>
                            <th class="p-4">Count_vieu</th>
                            <th class="p-4">active1</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
    <?php foreach($articles as $article): ?>
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="p-4">
            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold">
                <?= $article['id_artc'] ?>
            </span>
        </td>

<td class="p-4">
    <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600 uppercase">
            <?= substr($article['username'] ?? 'U', 0, 1) ?>
        </div>
        <span class="text-gray-700 font-medium text-sm">
            <?= htmlspecialchars($article['username'] ?? 'Inconnu') ?>
        </span>
    </div>
</td>

        <td class="p-4"><?= $article['title'] ?></td>

        <td class="p-4">
            <img src="<?= $article['image_url'] ?>" class="w-10 h-10 rounded object-cover shadow-sm">
        </td>

        <td class="p-4 text-gray-300 text-xs">
            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold">
                <?= $article['view_count'] ?>
            </span>
        </td>

        <td class="p-4">
            <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                <input type="checkbox"
                       class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer transition-all duration-300 ease-in-out border-gray-300 top-0 left-0"
                       <?= $article['status'] ? 'checked' : '' ?> />
                <label class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer transition-colors duration-300 ease-in-out"></label>
            </div>
        </td>

        <td class="p-4 text-right">
            <div class="flex items-center justify-end gap-2">
                <button class="w-8 h-8 rounded border border-gray-200 text-gray-500 hover:bg-gray-100 flex items-center justify-center">
                    <i class="fa-solid fa-link"></i>
                </button>
                <button class="w-8 h-8 rounded border border-gray-200 text-gray-500 hover:bg-gray-100 flex items-center justify-center">
                    <i class="fa-regular fa-eye"></i>
                </button>
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
</tbody>

                </table>
            </div>

            <div class="mt-4 flex justify-between items-center text-xs text-gray-500">
                <span>Showing 1 to 4 of 153 results</span>
                <div class="flex gap-1">
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">Previous</button>
                    <button class="px-3 py-1 bg-purple-600 text-white rounded">1</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">3</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">Next</button>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Logique JS pour le toggle switch (visuel)
        document.querySelectorAll('.toggle-checkbox').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const label = this.nextElementSibling;
                if(this.checked) {
                    this.style.borderColor = '#7c3aed';
                    label.style.backgroundColor = '#7c3aed';
                    this.style.right = '0';
                    this.style.transform = 'translateX(100%)';
                } else {
                    this.style.borderColor = '#d1d5db';
                    label.style.backgroundColor = '#d1d5db';
                    this.style.transform = 'translateX(0)';
                }
            });
            // Init state
            toggle.dispatchEvent(new Event('change'));
        });
    </script>
</body>
</html>