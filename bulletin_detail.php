<?php
// Sécurité : vérification que les variables existent
if (!isset($etudiant) || !isset($notes) || !isset($moyenne) || !isset($mention)) {
    echo "<div class='text-red-600 text-center'>Données manquantes pour générer le bulletin.</div>";
    return;
}
?>

<div class="max-w-4xl mx-auto bg-white text-gray-900 p-8 rounded-2xl shadow-lg mt-6">
    <h1 class="text-3xl font-bold text-center text-indigo-800 mb-6">🎓 Bulletin de notes</h1>

    <div class="mb-6 text-center text-lg text-gray-700 space-y-1">
        <p><strong>Nom :</strong> <?= htmlspecialchars($etudiant['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($etudiant['prenom']) ?></p>
        <p><strong>Sexe :</strong> <?= htmlspecialchars($etudiant['sexe']) ?></p>
        <p><strong>Date de naissance :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($etudiant['date_naissance']))) ?></p>
        <p><strong>Option :</strong> <?= htmlspecialchars($etudiant['option_etude']) ?></p>
        <p><strong>Niveau :</strong> <?= htmlspecialchars($etudiant['niveau_etude']) ?></p>
    </div>

    <div class="overflow-x-auto mt-4">
        <table class="w-full table-auto text-center border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-indigo-200 text-gray-800">
                <tr>
                    <th class="p-3 border">Matière</th>
                    <th class="p-3 border">Note (/20)</th>
                    <th class="p-3 border">Coefficient</th>
                    <th class="p-3 border">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($notes)): ?>
                    <tr>
                        <td colspan="4" class="p-4 text-red-600">Aucune note trouvée pour cet étudiant.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($notes as $note): ?>
                        <tr class="hover:bg-indigo-50 transition">
                            <td class="p-2 border"><?= htmlspecialchars($note['matiere']) ?></td>
                            <td class="p-2 border"><?= is_null($note['note']) ? '--' : number_format($note['note'], 2) ?></td>
                            <td class="p-2 border"><?= $note['coefficient'] ?></td>
                            <td class="p-2 border"><?= number_format($note['note'] * $note['coefficient'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot class="bg-indigo-100 font-semibold">
                <tr>
                    <td colspan="2" class="p-2 border">Moyenne générale</td>
                    <td class="p-2 border"><?= array_sum(array_column($notes, 'coefficient')) ?></td>
                    <td class="p-2 border"><?= number_format($moyenne, 2) ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="p-2 border">Mention</td>
                    <td class="p-2 border"><?= htmlspecialchars($mention) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
