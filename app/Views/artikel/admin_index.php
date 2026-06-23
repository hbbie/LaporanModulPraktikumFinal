<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2><?= $title; ?></h2>

        <div>

            <a href="<?= base_url('/admin/artikel/add'); ?>"
               class="btn btn-primary">
               + Tambah Artikel
            </a>

            <a href="<?= base_url('/user/logout'); ?>"
               class="btn btn-danger">
               Logout
            </a>

        </div>

    </div>

    <!-- SEARCH -->
    <form id="search-form" class="row g-2 mb-4">

        <div class="col-md-5">

            <input type="text"
                   name="q"
                   id="search-box"
                   value="<?= $q; ?>"
                   placeholder="Cari judul artikel"
                   class="form-control">

        </div>

        <div class="col-md-4">

            <select name="kategori_id"
                    id="category-filter"
                    class="form-select">

                <option value="">
                    Semua Kategori
                </option>

                <?php foreach($kategori as $k): ?>

                    <option value="<?= $k['id_kategori']; ?>">

                        <?= $k['nama_kategori']; ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="col-md-3">

            <button type="submit"
                    class="btn btn-success w-100">

                Cari

            </button>

        </div>

    </form>

    <!-- LOADING -->
    <div id="loading"
         class="alert alert-info"
         style="display:none;">

        Loading data...

    </div>

    <!-- DATA AJAX -->
    <div id="article-container"></div>

    <!-- PAGINATION -->
    <div id="pagination-container"
         class="mt-3"></div>

</div>

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function(){

    const articleContainer =
        $('#article-container');

    const paginationContainer =
        $('#pagination-container');

    const loading = $('#loading');

    // =========================
    // FETCH DATA AJAX
    // =========================
    const fetchData = (url) => {

        loading.show();

        $.ajax({

            url: url,

            type: 'GET',

            dataType: 'json',

            headers: {
                'X-Requested-With':
                    'XMLHttpRequest'
            },

            success: function(data){

                renderArticles(data.artikel);

                renderPagination(
                    data.pager.links,
                    data.q,
                    data.kategori_id
                );

                loading.hide();
            }
        });
    };

    // =========================
    // RENDER TABLE
    // =========================
    const renderArticles = (articles) => {

        let html = '';

        html += `
        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>

                    <th>ID</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th width="200">Aksi</th>

                </tr>

            </thead>

            <tbody>
        `;

        if(articles.length > 0){

            articles.forEach(article => {

                html += `
                <tr>

                    <td>${article.id}</td>

                    <td>
                        <b>${article.judul}</b>
                    </td>

                    <td>
                        ${article.nama_kategori ?? '-'}
                    </td>

                    <td>
                        ${article.status}
                    </td>

                    <td>

                        <a href="/lab11_ci/ci4/public/admin/artikel/edit/${article.id}"
                           class="btn btn-warning btn-sm">

                           Edit

                        </a>

                        <a href="/lab11_ci/ci4/public/admin/artikel/delete/${article.id}"
                           onclick="return confirm('Yakin hapus data?')"
                           class="btn btn-danger btn-sm">

                           Delete

                        </a>

                    </td>

                </tr>
                `;
            });

        } else {

            html += `
            <tr>

                <td colspan="5"
                    class="text-center">

                    Data tidak ditemukan

                </td>

            </tr>
            `;
        }

        html += `
            </tbody>
        </table>
        `;

        articleContainer.html(html);
    };

    // =========================
    // PAGINATION
    // =========================
    const renderPagination = (
        links,
        q,
        kategori_id
    ) => {

        let html = `
        <nav>
            <ul class="pagination">
        `;

        links.forEach(link => {

            let active =
                link.active
                ? 'active'
                : '';

            let url = '#';

            if(link.uri){

                url = link.uri;

                url += '&q=' + q;

                url += '&kategori_id='
                       + kategori_id;
            }

            html += `
            <li class="page-item ${active}">

                <a class="page-link"
                   href="${url}">

                    ${link.title}

                </a>

            </li>
            `;
        });

        html += `
            </ul>
        </nav>
        `;

        paginationContainer.html(html);
    };

    // =========================
    // SEARCH
    // =========================
    $('#search-form').submit(function(e){

        e.preventDefault();

        const q =
            $('#search-box').val();

        const kategori_id =
            $('#category-filter').val();

        fetchData(
            '/lab11_ci/ci4/public/admin/artikel?q='
            + q +
            '&kategori_id='
            + kategori_id
        );
    });

    // =========================
    // FILTER
    // =========================
    $('#category-filter').change(function(){

        $('#search-form').submit();
    });

    // =========================
    // PAGINATION CLICK
    // =========================
    $(document).on(
        'click',
        '.page-link',
        function(e){

            e.preventDefault();

            fetchData(
                $(this).attr('href')
            );
        }
    );

    // =========================
    // LOAD AWAL
    // =========================
    fetchData(
        '/lab11_ci/ci4/public/admin/artikel'
    );

});

</script>