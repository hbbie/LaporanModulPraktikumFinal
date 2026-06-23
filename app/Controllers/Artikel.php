<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
    public function index()
    {
        $title = 'Daftar Artikel';

        $model = new ArtikelModel();

        $artikel = $model->findAll();

        return view('artikel/index', compact('artikel', 'title'));
    }

    public function view($slug)
    {
        $model = new ArtikelModel();

        $artikel = $model->where(['slug' => $slug])->first();

        if (!$artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        $title = $artikel['judul'];

        return view('artikel/detail', compact('artikel', 'title'));
    }

    // =========================================
    // ADMIN INDEX AJAX PAGINATION + SEARCH
    // =========================================
    public function admin_index()
    {
        $title = 'Daftar Artikel (Admin)';

        $model = new ArtikelModel();

        $q = $this->request->getVar('q') ?? '';

        $kategori_id = $this->request->getVar('kategori_id') ?? '';

        $page = $this->request->getVar('page') ?? 1;

        $builder = $model->table('artikel')
            ->select('artikel.*, kategori.nama_kategori')
            ->join(
                'kategori',
                'kategori.id_kategori = artikel.id_kategori',
                'left'
            );

        // SEARCH
        if ($q != '') {
            $builder->like('artikel.judul', $q);
        }

        // FILTER KATEGORI
        if ($kategori_id != '') {
            $builder->where('artikel.id_kategori', $kategori_id);
        }

        $artikel = $builder->paginate(10, 'default', $page);

        $pager = $model->pager;

        $data = [
            'title'        => $title,
            'q'            => $q,
            'kategori_id'  => $kategori_id,
            'artikel'      => $artikel,
            'pager'        => $pager
        ];

        // JIKA REQUEST AJAX
        if ($this->request->isAJAX()) {

            return $this->response->setJSON($data);

        } else {

            $kategoriModel = new KategoriModel();

            $data['kategori'] = $kategoriModel->findAll();

            return view('artikel/admin_index', $data);
        }
    }

    // =========================================
    // TAMBAH DATA
    // =========================================
    public function add()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'judul' => 'required'
        ]);

        $isDataValid = $validation
            ->withRequest($this->request)
            ->run();

        if ($isDataValid) {

            $artikel = new ArtikelModel();

            $artikel->insert([

                'judul' => $this->request->getPost('judul'),

                'isi' => $this->request->getPost('isi'),

                'slug' => url_title(
                    $this->request->getPost('judul'),
                    '-',
                    true
                ),

                'id_kategori' =>
                    $this->request->getPost('id_kategori'),

                'status' => 1
            ]);

            return redirect()->to('/admin/artikel');
        }

        $kategoriModel = new KategoriModel();

        $data['kategori'] = $kategoriModel->findAll();

        $data['title'] = 'Tambah Artikel';

        return view('artikel/form_add', $data);
    }

    // =========================================
    // EDIT DATA
    // =========================================
    public function edit($id)
    {
        $artikel = new ArtikelModel();

        $validation = \Config\Services::validation();

        $validation->setRules([
            'judul' => 'required'
        ]);

        $isDataValid = $validation
            ->withRequest($this->request)
            ->run();

        if ($isDataValid) {

            $artikel->update($id, [

                'judul' => $this->request->getPost('judul'),

                'isi' => $this->request->getPost('isi'),

                'id_kategori' =>
                    $this->request->getPost('id_kategori'),
            ]);

            return redirect()->to('/admin/artikel');
        }

        $data['data'] = $artikel
            ->where('id', $id)
            ->first();

        $kategoriModel = new KategoriModel();

        $data['kategori'] = $kategoriModel->findAll();

        $data['title'] = 'Edit Artikel';

        return view('artikel/form_edit', $data);
    }

    // =========================================
    // DELETE
    // =========================================
    public function delete($id)
    {
        $artikel = new ArtikelModel();

        $artikel->delete($id);

        return redirect()->to('/admin/artikel');
    }
}