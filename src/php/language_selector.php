<select onchange="if (this.value) window.location.href='?lang=' + this.value;">
    <?php foreach($langs as $index => $l): ?>
        <option value="<?php echo $l; ?>" <?php echo $lang == $l ? 'selected' : ''; ?>>
            <?= htmlspecialchars($langs_name[$index]); ?> (<?= strtoupper($l); ?>)
        </option>
    <?php endforeach; ?>
</select>