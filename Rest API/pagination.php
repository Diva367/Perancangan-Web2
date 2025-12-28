<?php
// Hanya jalan kalau variabel pagination tersedia
if (!isset($page) || !isset($totalPage)) {
    return;
}
?>

<style>
.pagination {
    text-align: center;
    margin-top: 25px;
    margin-bottom: 20px;
    font-family: 'Poppins', sans-serif;
}

.page-number, .page-btn {
    display: inline-block;
    padding: 8px 14px;
    margin: 3px;
    background: #ffb3d9;
    color: #660033;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: .3s;
    border: 1px solid #ff94c6;
}

.page-number:hover, .page-btn:hover {
    background: #ff85c2;
    transform: scale(1.07);
}

.active {
    background: #d63384 !important;
    color: white !important;
   box-shadow: 0 3px 6px rgba(255, 100, 150, 0.3);
    transform: scale(1.1);
}
</style>

<div class="pagination">

    <!-- Tombol Prev -->
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="page-btn">⬅ Prev</a>
    <?php endif; ?>

    <!-- Nomor Halaman -->
    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
        <a href="?page=<?= $i ?>" class="page-number <?= ($i == $page) ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <!-- Tombol Next -->
    <?php if ($page < $totalPage): ?>
        <a href="?page=<?= $page + 1 ?>" class="page-btn">Next ➡</a>
    <?php endif; ?>

</div>