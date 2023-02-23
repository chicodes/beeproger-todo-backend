<?php

use App\Http\Services\TodoService;
use App\Http\Dto\Requests\SaveTodoRequest;
use App\Http\Dto\Requests\UpdateTodoRequest;
use App\Http\Dto\Requests\UpdateTodoStatusRequest;
use App\Models\Todo;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TodoServiceTest extends TestCase
{
    private TodoService $todoService;

    public function setUp(): void
    {
        parent::setUp();
        $this->todoService = new TodoService();
    }

    public function testSaveTodoWithInvalidStatus(): void
    {
        $request = new SaveTodoRequest([
            'description' => 'description',
            'todoPhoto' => UploadedFile::fake()->image('image.jpg'),
            'startDate' => '2023-02-01',
            'endDate' => '2023-02-28',
            'status' => 'invalid status',
        ]);

        $response = $this->todoService->saveTodo($request);

        $this->assertSame($response['code'], Response::HTTP_EXPECTATION_FAILED);
        $this->assertSame($response['status'], false);
        $this->assertSame($response['message'], 'Invalid TODO status!');
    }

    public function testSaveTodoWithInvalidImage(): void
    {
        $request = new SaveTodoRequest([
            'description' => 'description',
            'todoPhoto' => null,
            'startDate' => '2023-02-01',
            'endDate' => '2023-02-28',
            'status' => 'PENDING',
        ]);

        $response = $this->todoService->saveTodo($request);

        $this->assertSame($response['code'], Response::HTTP_EXPECTATION_FAILED);
        $this->assertSame($response['status'], 'false');
        $this->assertSame($response['message'], 'could not generate image link, image not uploaded');
    }

    public function testSaveTodo(): void
    {
        $request = new SaveTodoRequest([
            'description' => 'description',
            'todoPhoto' => UploadedFile::fake()->image('image.jpg'),
            'startDate' => '2023-02-01',
            'endDate' => '2023-02-28',
            'status' => 'PENDING',
        ]);

        $response = $this->todoService->saveTodo($request);

        $this->assertSame($response['statusCode'], Response::HTTP_OK);
        $this->assertSame($response['status'], 'success');
        $this->assertInstanceOf(Todo::class, $response['data']);
        $this->assertDatabaseHas('todos', [
            'id' => $response['data']->id,
            "image_link" => $imageLink,
            'description' => $request->description,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'status' => $request->status,
        ]);
    }

    public function testListAllTodos(): void
    {
        $response = $this->todoService->listAllTodos();

        $this->assertSame($response['code'], Response::HTTP_OK);
        $this->assertSame($response['status'], true);
        $this->assertSame($response['message'], 'success');
        //$this->assertInstanceOf(AnonymousResourceCollection::class, $response['data']);
    }

    public function testGetSingleTodoWithInvalidId(): void
    {
        $response = $this->todoService->getSingle(40);

        $this->assertSame($response['code'], Response::HTTP_NOT_FOUND);
        $this->assertSame($response['status'], false);
        $this->assertSame($response['message'], 'This todo no longer exists!');
    }

    public function testGetSingleTodoWithValidId(): void
    {
        $response = $this->todoService->getSingle(1);

        $this->assertSame($response['code'], Response::HTTP_OK);
        $this->assertSame($response['status'], true);
        $this->assertSame($response['message'], 'success');
    }
}