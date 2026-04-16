<?php
// variables attendues :
// $etudiant (array), $notes (array), $moyenne (float), $mention (string)

// Ce fichier génère uniquement la partie bulletin sans menu ni header
?>

<h1>Bulletin de notes</h1>

<p><strong>Nom :</strong> <?= htmlspecialchars($etudiant['nom']) ?></p>
<p><strong>Prénom :</strong> <?= htmlspecialchars($etudiant['prenom']) ?></p>
<p><strong>Option :</strong> <?= htmlspecialchars($etudiant['option_etude']) ?></p>
<p><strong>Niveau :</strong> <?= htmlspecialchars($etudiant['niveau_etude']) ?></p>

<table>
    <thead>
        <tr>
            <th>Matière</th>
            <th>Note</th>
            <th>Coefficient</th>
            <th>Note x Coef</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($notes as $note): ?>
            <tr>
                <td><?= htmlspecialchars($note['nom_matiere']) ?></td>
                <td><?= number_format($note['note'], 2) ?></td>
                <td><?= (int)$note['coefficient'] ?></td>
                <td><?= number_format($note['note'] * $note['coefficient'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><strong>Moyenne générale</strong></td>
            <td><strong><?= number_format($moyenne, 2) ?></strong></td>
        </tr>
    </tbody>
</table>

<div class="mention">
    Mention : <?= htmlspecialchars($mention) ?>
</div>
