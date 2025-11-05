<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Gérer les utilisateurs</h1>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscription</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="px-6 py-4 font-medium"><?= e($user['email']) ?></td>
                        <td class="px-6 py-4"><?= e($user['name'] ?? 'N/A') ?></td>
                        <td class="px-6 py-4">
                            <form action="<?= route('admin.user.update', ['id' => $user['id']]) ?>" method="POST" class="inline" onchange="this.submit()">
                                <?= CSRF::field() ?>
                                <select name="role" class="px-3 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-primary <?= $user['role'] === 'admin' ? 'bg-yellow-50 font-semibold' : '' ?>">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded <?= $user['active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= $user['active'] ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4"><?= date('d/m/Y', strtotime($user['createdAt'])) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($user['id'] !== Auth::id()): ?>
                                <form action="<?= route('admin.user.delete', ['id' => $user['id']]) ?>" method="POST" class="inline">
                                    <?= CSRF::field() ?>
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir désactiver cet utilisateur ?')" class="text-red-600 hover:text-red-800 text-sm">Désactiver</button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm">Vous</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


