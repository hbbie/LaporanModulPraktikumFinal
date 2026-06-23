<?= $this->include('template/header'); ?>

<h2><?= $title; ?></h2>

<form action="<?= base_url('/admin/artikel/edit/' . $data['id']); ?>" method="post">

    <p>
        <input type="text" name="judul" value="<?= $data['judul']; ?>" required>
    </p>

    <p>
        <textarea name="isi" cols="50" rows="10" required><?= $data['isi']; ?></textarea>
    </p>

    <p>
        <input type="submit" value="Update" class="btn btn-large">
    </p>

</form>

<?= $this->include('template/footer'); ?>