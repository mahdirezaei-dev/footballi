<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class RestResponse implements Responsable
{
    private mixed $data;
    private string $message;
    private ?Throwable $exception;
    private int $code;
    private array $headers;

    /**
     * RestResponse constructor.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @param array $headers
     * @param Throwable|null $exception
     */
    public function __construct(
        mixed $data = null,
        string $message = '',
        int $code = Response::HTTP_OK,
        array $headers = [],
        ?Throwable $exception = null
    ) {
        $this->data = $data;
        $this->message = $message;
        $this->code = $code;
        $this->headers = $headers;
        $this->exception = $exception;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        $response = [
            'data' => $this->data,
            'message' => $this->message
        ];

        if (!is_null($this->exception) && config('app.debug')) {
            $response['debug'] = [
                'message' => $this->exception->getMessage(),
                'file' => $this->exception->getFile(),
                'line' => $this->exception->getLine(),
                'trace' => $this->exception->getTraceAsString()
            ];
        }

        return response()->json($response, $this->code, $this->headers);
    }

    /**
     * Static method for creating a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @param array $headers
     * @return JsonResponse
     */
    public static function success(mixed $data, string $message = '', int $code = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return (new self($data, $message, $code, $headers))->toResponse(request());
    }

    /**
     * Static method for creating an error response.
     *
     * @param string $message
     * @param int $code
     * @param array $headers
     * @param Throwable|null $exception
     * @return JsonResponse
     */
    public static function error(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR, array $headers = [], ?Throwable $exception = null): JsonResponse
    {
        return (new self(null, $message, $code, $headers, $exception))->toResponse(request());
    }
}
