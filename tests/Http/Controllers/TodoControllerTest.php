
<?php

use App\Http\Controllers\TodoController;
use App\Http\Dto\Requests\SaveTodoRequest;
use App\Http\Dto\Requests\UpdateTodoRequest;
use App\Http\Dto\Requests\UpdateTodoStatusRequest;
use App\Http\Services\TodoService;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class TodoControllerTest extends TestCase
{
    private $controller;
    private $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up a mock TodoService instance to be injected into the TodoController
        $this->serviceMock = $this->createMock(TodoService::class);
        $this->controller = new TodoController($this->serviceMock);
    }

    public function testSaveTodo()
    {
        // Create a mock SaveTodoRequest instance to be passed to the controller
        $requestMock = $this->createMock(SaveTodoRequest::class);

        // Set up the TodoService mock to return a mocked response
        $responseMock = ['id' => 1, 'title' => 'Test Todo', 'completed' => false];
        $this->serviceMock->method('saveTodo')->willReturn($responseMock);

        // Call the controller's saveTodo method with the mock request
        $response = $this->controller->saveTodo($requestMock);

        // Assert that the response matches the expected value
        $this->assertEquals($responseMock, $response);
        //$this->ass($responseMock, $response);
    }

    public function testListTodo()
    {
        // Set up the TodoService mock to return a mocked response
        $responseMock = [['id' => 1, 'title' => 'Test Todo 1', 'completed' => false],
            ['id' => 2, 'title' => 'Test Todo 2', 'completed' => true]];
        $this->serviceMock->method('listAllTodos')->willReturn($responseMock);

        // Call the controller's listTodo method
        $response = $this->controller->listTodo();

        // Assert that the response matches the expected value
        $this->assertEquals($responseMock, $response);
    }

    public function testGetTodo()
    {
        // Set up the TodoService mock to return a mocked response
        $responseMock = ['id' => 1, 'title' => 'Test Todo', 'completed' => false];
        $this->serviceMock->method('getSingle')->with(1)->willReturn($responseMock);

        // Call the controller's getTodo method with an ID of 1
        $response = $this->controller->getTodo(1);

        // Assert that the response matches the expected value
        $this->assertEquals($responseMock, $response);
    }

    public function testGetTodoByStatus()
    {
        // Set up the TodoService mock to return a mocked response
        $responseMock = [['id' => 1, 'title' => 'Test Todo 1', 'completed' => true],
                ['id' => 2, 'title' => 'Test Todo 2', 'completed' => true]];
        $this->serviceMock->method('getTodosByStatus')->with('completed')->willReturn($responseMock);

        // Call the controller's getTodoByStatus method with a status of 'completed'
        $response = $this->controller->getTodoByStatus('completed');

        // Assert that the response matches the expected value
        $this->assertEquals($responseMock, $response);
    }

    public function testUpdateTodo()
    {
        // Create a mock SaveTodoRequest instance to be passed to the controller
        $requestMock = $this->createMock(UpdateTodoRequest::class);

        // Set up the TodoService mock to return a mocked response
        $responseMock = ['id' => 1, 'title' => 'Test Todo', 'completed' => false];
        $this->serviceMock->method('updateTodo')->willReturn($responseMock);

        // Call the controller's saveTodo method with the mock request
        $response = $this->controller->updateTodo($requestMock, 1);

        // Assert that the response matches the expected value
        $this->assertEquals($responseMock, $response);
    }

    public function testUpdateTodoByStatus()
    {
        // Create a mock UpdateTodoStatusRequest instance to be passed to the controller
        $requestMock = $this->createMock(UpdateTodoStatusRequest::class);

        // Set up the TodoService mock to return a mocked response
        $responseMock = ['status' => 'in_progress'];
        $this->serviceMock->method('updateTodoByStatus')->willReturn($responseMock);

        // Call the controller's updateTodoByStatus method with the mock request
        $response = $this->controller->updateTodoByStatus($requestMock, 1);

        // Assert that the response matches the expected value
        $this->assertIsArray($response);
    }

    public function testDelete()
    {
        $response = $this->controller->delete(1);
        $this->assertIsArray($response);
    }

}
