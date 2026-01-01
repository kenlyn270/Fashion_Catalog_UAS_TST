<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;

class ProductsAPI extends ResourceController
{
    private function isAdmin(): bool
    {
        $adminKey = getenv('ADMIN_API_KEY') ?: 'admin-123';
        $key = $this->request->getHeaderLine('x-api-key');
        return $key === $adminKey;
    }

    // GET /products?page=1&limit=10
    public function index()
    {
        $model = model(ProductModel::class);

        $page  = (int)($this->request->getGet('page') ?? 1);
        $limit = (int)($this->request->getGet('limit') ?? 10);
        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 10;

        $offset = ($page - 1) * $limit;

        $items = $model->findAll($limit, $offset);

        return $this->respond([
            'message' => 'success',
            'page' => $page,
            'limit' => $limit,
            'products' => $items
        ], 200);
    }

    // GET /products/{id}
    public function show($id = null)
    {
        $model = model(ProductModel::class);
        $item = $model->find($id);

        if (!$item) {
            return $this->respond(['message' => 'not_found'], 404);
        }
        return $this->respond(['message' => 'success', 'product' => $item], 200);
    }

    // GET /products/search?query=...&category=...&tags=tag1,tag2
    public function search()
    {
        $model = model(ProductModel::class);

        $query = $this->request->getGet('query');
        $category = $this->request->getGet('category');
        $tags = $this->request->getGet('tags'); // comma separated

        $builder = $model->builder();

        if ($query) {
            $builder->like('name', $query);
        }
        if ($category) {
            $builder->where('category', $category);
        }
        if ($tags) {
            $tagList = array_filter(array_map('trim', explode(',', $tags)));
            // karena tags disimpan string, kita pakai LIKE untuk masing-masing tag
            foreach ($tagList as $t) {
                $builder->like('tags', $t);
            }
        }

        $results = $builder->get()->getResultArray();

        return $this->respond(['message' => 'success', 'products' => $results], 200);
    }

    // GET /products/categories
    public function categories()
    {
        $model = model(ProductModel::class);
        $rows = $model->select('category')->distinct()->findAll();

        $categories = array_map(fn($r) => $r['category'], $rows);
        return $this->respond(['message' => 'success', 'categories' => $categories], 200);
    }

    // GET /products/tags
    public function tags()
    {
        $model = model(ProductModel::class);
        $rows = $model->select('tags')->findAll();

        $all = [];
        foreach ($rows as $r) {
            $parts = array_filter(array_map('trim', explode(',', $r['tags'] ?? '')));
            foreach ($parts as $p) $all[] = $p;
        }

        $unique = array_values(array_unique($all));
        sort($unique);

        return $this->respond(['message' => 'success', 'tags' => $unique], 200);
    }

    // GET /products/recommendations?body_type=...&style=...
    // (versi sederhana rule-based: pakai tags style/body_type)
    public function recommendations()
    {
        $model = model(ProductModel::class);

        $body = $this->request->getGet('body_type'); // misal "petite"
        $style = $this->request->getGet('style');    // misal "casual"

        $builder = $model->builder();

        if ($body)  $builder->like('tags', $body);
        if ($style) $builder->like('tags', $style);

        $results = $builder->limit(10)->get()->getResultArray();

        return $this->respond([
            'message' => 'success',
            'input' => ['body_type' => $body, 'style' => $style],
            'products' => $results
        ], 200);
    }

    // POST /products (admin only)
    public function create()
    {
        if (!$this->isAdmin()) {
            return $this->respond(['message' => 'forbidden_admin_only'], 403);
        }

        $model = model(ProductModel::class);

        $data = $this->request->getJSON(true);
        if (!$data) {
            // fallback kalau form-data
            $data = $this->request->getPost();
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        // $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $model->insert($data, true);

        return $this->respond(['message' => 'created', 'id' => $id], 201);
    }
}
