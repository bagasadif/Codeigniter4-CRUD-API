<?php

namespace App\Controllers;

use App\Models\PengunjungModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Pengunjung extends ResourceController
{
  use ResponseTrait;

  protected $model;

  public function __construct()
  {
    $this->model = new PengunjungModel();
  }

  protected function formatResponse(int $status, $data = null, $error = null, string $message)
  {
    return [
      'status' => $status,
      'data' => $data,
      'error' => $error,
      'message' => $message
    ];
  }

  public function index()
  {
    $data = $this->model->findAll();

    return $this->respond($this->formatResponse(200, $data, null, 'Mendapatkan semua data berhasil'), 200);
  }

  public function show($id = null)
  {
    $data = $this->model->find($id);

    if ($data)
      return $this->respond($this->formatResponse(200, $data, null, 'Data ditemukan'), 200);
    return $this->failNotFound('Data tidak ditemukan');
  }

  public function create()
  {
    $data = [
      'nama' => $this->request->getPost('nama'),
      'instansi' => $this->request->getPost('instansi'),
      'alasan' => $this->request->getPost('alasan'),
    ];

    if ($this->model->insert($data)) {
      return $this->respondCreated($this->formatResponse(201, null, null, 'Data berhasil ditambahkan'));
    }
    return $this->failServerError();
  }

  public function update($id = null)
  {
    $input = $this->request->getRawInput();
    $data = [
      'nama' => $input['nama'],
      'instansi' => $input['instansi'],
      'alasan' => $input['alasan'],
    ];

    if ($this->model->update($id, $data))
      return $this->respondUpdated($this->formatResponse(200, null, null, 'Data berhasil diperbarui'));
    return $this->failNotFound('Data tidak ditemukan');
  }

  public function delete($id = null)
  {
    if ($this->model->find($id)) {
      $this->model->delete($id);
      return $this->respondDeleted($this->formatResponse(200, null, null, 'Data berhasil dihapus'));
    }
    return $this->failNotFound('Data tidak ditemukan');
  }
}
