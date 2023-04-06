<?php
/**
 * Created by PhpStorm.
 * User: ssa41
 * Date: 2019-05-25
 * Time: 오후 5:35
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $statusCode = 200;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($message)
    {
        return $this->respond([
            "message" => $message,
            'status_code' => $this->getStatusCode()
        ]);
    }

    public function respondInvalid($data)
    {
        return $this->setStatusCode(422)->respond($data);
    }

    public function respondUnauthenticated($message)
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    public function respondNotFound($message)
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    public function respondInternalError($message)
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    public function respondImproperCondition($message)
    {
        return $this->setStatusCode(422)->respondWithError($message);
    }

    public function respondForbidden($message = "not allowed")
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    public function respondCreated($data = null, $message = null)
    {
        return $this->setStatusCode(201)->respond([
            'data' => $data,
            'message' => $message
        ]);
    }

    public function respondUpdated($data = null, $message = null)
    {
        return $this->respond([
            'data' => $data,
            'message' => $message
        ]);
    }

    public function respondDeleted($data = null, $message = null)
    {
        return $this->respond([
            "data" => $data,
            "message" => $message
        ]);
    }

    public function respondSuccessfully($data = null, $message = null)
    {
        return $this->respond([
            "data" => $data,
            "message" => $message
        ]);
    }

    protected function respondWithPagination($items, $data)
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $items->total(),
                'total_page' => ceil($items->total() / $items->perPage()),
                'current_page' => $items->currentPage(),
                'limit' => $items->perPage()
            ]
        ]);

        return $this->respond($data);
    }
}
