<?php
include "auth_check.php";
checkRole(['admin']); 
include "db.php";

if (isset($_POST['change_role_id']) && isset($_POST['new_role'])) {
    $id = intval($_POST['change_role_id']);
    $role = $_POST['new_role'];
    
    if(in_array($role, ['admin', 'editeur', 'utilisateur'])){
        $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE user_id = :id");
        $stmt->execute([':role' => $role, ':id' => $id]);
        header("Location: users.php?msg=role_updated");
        exit;
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id != $_SESSION['user_id']) {
     
        $pdo->prepare("DELETE FROM users WHERE user_id = :id")->execute([':id' => $id]);
    }
    header("Location: users.php?msg=deleted");
    exit;
}

$users = $pdo->query("SELECT * FROM users ORDER BY user_id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion Utilisateurs - BookShine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    
    <div class="max-w-6xl mx-auto mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Utilisateurs</h1>
        <a href="dashboard.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            <i class="fa-solid fa-arrow-left"></i> Retour Dashboard
        </a>
    </div>

    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Utilisateur</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-center">Rôle Actuel</th>
                    <th class="py-3 px-6 text-center">Changer Rôle</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach($users as $u): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium"><?= htmlspecialchars($u['username']) ?></span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left">
                        <?= htmlspecialchars($u['email']) ?>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <?php 
                            $badgeColor = match($u['role']) {
                                'admin' => 'bg-red-200 text-red-700',
                                'editeur' => 'bg-purple-200 text-purple-700',
                                default => 'bg-green-200 text-green-700'
                            };
                        ?>
                        <span class="<?= $badgeColor ?> py-1 px-3 rounded-full text-xs">
                            <?= $u['role'] ?>
                        </span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <form action="" method="POST" class="flex justify-center gap-2">
                            <input type="hidden" name="change_role_id" value="<?= $u['user_id'] ?>">
                            <select name="new_role" class="border rounded p-1 text-xs">
                                <option value="utilisateur" <?= $u['role'] == 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="editeur" <?= $u['role'] == 'editeur' ? 'selected' : '' ?>>Éditeur</option>
                                <option value="admin" <?= $u['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <?php if($u['user_id'] != $_SESSION['user_id']): ?>
                        <a href="users.php?delete=<?= $u['user_id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')" class="text-red-500 hover:text-red-700">
                            <i class="fa-regular fa-trash-can text-lg"></i>
                        </a>
                        <?php else: ?>
                            <span class="text-gray-400 text-xs">Vous</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>