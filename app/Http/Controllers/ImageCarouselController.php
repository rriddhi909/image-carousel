<?php

namespace App\Http\Controllers;

use Exception;
use Faker\Core\Number;
use Illuminate\Http\Request;

class ImageCarouselController extends Controller
{

    private $response = [];
    private $data = [];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->response = $this->basicResponse();
        $this->data = $this->loadData();
    }

    private function basicResponse()
    {
        return [
            'status' => 200,
            'message' => '',
            'results' => []
        ];
    }

    private function loadData()
    {
        $path = storage_path('/app/carousel-data.json');
        $json = json_decode(file_get_contents($path), true);
        return $json;
    }

    public function getImages(Request $request)
    {
        try {
            if (count($request->all()) >= 1) {
                foreach ($request->all() as $key => $val) {
                    $result = $this->searchData($key, $val);
                }
            } else {
                $result = $this->data;
            }

            $this->response['status'] = 200;
            $this->response['message'] = $result;
        } catch (Exception $e) {
            $this->response['status'] = 422;
            $this->response['message'] =  $e->getMessage();
        }
        return response()->json($this->response);
    }

    private function searchData($key, $value)
    {
        $filtered_data = [];
        $result = array_filter($this->data, fn ($data) => $data[$key] === $value);
        $ids = array_column($filtered_data, 'id');
        foreach ($result as $res) {
            //Push only unique ids into result
            if (!in_array($res['id'], $ids)) {
                array_push($filtered_data, $res);
            }
        }
        return $filtered_data;
    }
}
