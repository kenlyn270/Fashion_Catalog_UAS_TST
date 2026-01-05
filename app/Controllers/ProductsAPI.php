<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;

class ProductsAPI extends ResourceController
{
    public function __construct() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            header("HTTP/1.1 200 OK");
            die();
        }
    }

    private function isAdmin(): bool
    {
        $adminKey = getenv('ADMIN_API_KEY') ?: 'admin-123';
        $key = $this->request->getHeaderLine('x-api-key');
        return $key === $adminKey;
    }

    public function index()
    {
        $model = model(ProductModel::class);

        $page  = (int)($this->request->getGet('page') ?? 1);
        $limit = (int)($this->request->getGet('limit') ?? 50);

        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $items = $model->findAll($limit, $offset);

        return $this->respond([
            'message' => 'success',
            'page' => $page,
            'limit' => $limit,
            'products' => $items
        ], 200);
    }

    public function show($id = null)
    {
        $model = model(ProductModel::class);
        $item = $model->find($id);

        if (!$item) {
            return $this->respond(['message' => 'not_found'], 404);
        }
        return $this->respond(['message' => 'success', 'product' => $item], 200);
    }

    public function search()
    {
        $model = model(ProductModel::class);

        $query = $this->request->getGet('query');
        $category = $this->request->getGet('category');
        $tags = $this->request->getGet('tags'); 

        $builder = $model->builder();

        if ($query) {
            $builder->like('name', $query);
        }
        if ($category) {
            $builder->where('category', $category);
        }
        if ($tags) {
            $tagList = array_filter(array_map('trim', explode(',', $tags)));
            foreach ($tagList as $t) {
                $builder->like('tags', $t);
            }
        }

        $results = $builder->get()->getResultArray();

        return $this->respond(['message' => 'success', 'products' => $results], 200);
    }

    public function categories()
    {
        $model = model(ProductModel::class);
        $rows = $model->select('category')->distinct()->findAll();

        $categories = array_map(fn($r) => $r['category'], $rows);
        return $this->respond(['message' => 'success', 'categories' => $categories], 200);
    }

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

    public function recommendations()
    {
        $model = model(ProductModel::class);

        $body = $this->request->getGet('body_type'); 
        $style = $this->request->getGet('style');

        $builder = $model->builder();

        if ($body)  $builder->like('tags', $body);
        if ($style) $builder->like('tags', $style);

        $results = $builder->limit(50)->get()->getResultArray();

        return $this->respond([
            'message' => 'success',
            'input' => ['body_type' => $body, 'style' => $style],
            'products' => $results
        ], 200);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            return $this->respond(['message' => 'forbidden_admin_only'], 403);
        }

        $model = model(ProductModel::class);

        $data = $this->request->getJSON(true);
        if (!$data) {
            $data = $this->request->getPost();
        }

        $data['created_at'] = date('Y-m-d H:i:s');

        $id = $model->insert($data, true);

        return $this->respond(['message' => 'created', 'id' => $id], 201);
    }

    public function recommend()
    {
        $data = $this->request->getJSON(true);
        if (!$data) return $this->fail("Invalid JSON body");

        $shoulder = $data['shoulder'] ?? null;
        $waist    = $data['waist'] ?? null;
        $thigh    = $data['thigh'] ?? null;
        $style    = $data['style'] ?? 'casual';

        if (!$shoulder || !$waist) {
            return $this->failValidationErrors("Measurement incomplete (shoulder & waist required)");
        }

        $apiKey = getenv('GEMINI_API_KEY');
        if (!$apiKey) return $this->failServerError("GEMINI_API_KEY not set in .env");

        // Prompt: minta JSON biar gampang diparse
        $prompt = <<<PROMPT
    You are a fashion stylist assistant.
    User measurements:
    - Shoulder: {$shoulder} cm
    - Waist: {$waist} cm
    - Thigh: {$thigh} cm
    Preferred style: {$style}

    Return ONLY valid JSON with this exact shape:
    {
    "bodyType": "one short phrase",
    "tips": ["tip 1", "tip 2"],
    "recommendedTags": ["tag1","tag2","tag3","tag4","tag5"]
    }

    Rules:
    - tips must be exactly 2 items
    - recommendedTags must be 5 items, simple lowercase words (example: "oversized", "high-waist")
    - no markdown, no extra text outside JSON
    PROMPT;

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

        $payload = [
            "contents" => [
                ["parts" => [["text" => $prompt]]]
            ],
            "generationConfig" => [
                "temperature" => 0.7
            ]
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 30
        ]);

        $result = curl_exec($ch);

        if ($result === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return $this->failServerError("Curl error: {$err}");
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($result, true);

        if ($httpCode >= 400) {
            return $this->respond([
                "message" => "gemini_error",
                "http_code" => $httpCode,
                "raw" => $response
            ], 500);
        }

        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? "";

        // Coba parse JSON dari Gemini
        $aiJson = json_decode($text, true);

        // Kalau parse gagal, tetap balikin text mentah biar kamu lihat
        if (!$aiJson) {
            return $this->respond([
                "message" => "success_but_unparsed",
                "ai_text" => $text
            ], 200);
        }

        return $this->respond([
            "message" => "success",
            "ai" => $aiJson
        ], 200);
    }
}
