<?php

    namespace App\Http\Controllers;

    use App\Http\Dto\Requests\SaveTodoRequest;
    use App\Http\Dto\Requests\UpdateTodoStatusRequest;
    use App\Http\Dto\Response\TodoResponse;
    use App\Http\Dto\Requests\UpdateTodoRequest;
    use App\Http\Resources\TodoResource;
    use App\Http\Services\TodoService;
    use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

    class TodoController extends Controller
    {
        public TodoService $todoService;

        /**
         * @param TodoService $todoService
         */
        public function __construct(TodoService $todoService) {
            $this->todoService = $todoService;
        }


        /**
         * @param SaveTodoRequest $request
         * @return array
         * @throws \Exception
         */
        public function saveTodo(SaveTodoRequest $request): array {
            //dd($request);
            return $this->todoService->saveTodo($request);
        }

        /**
         * @param int $id
         * @return array
         * @throws \Exception
         */
        public function listTodo(): array{
            return $this->todoService->listAllTodos();
        }

        /**
         * @param int $id
         * @return array
         * @throws \Exception
         */
        public function getTodo(int $id): array {
            return $this->todoService->getSingle($id);
        }

        /**
         * @param string $status
         * @return array
         * @throws \Exception
         */
        public function getTodoByStatus(string $status): array {
            return $this->todoService->getTodosByStatus($status);
        }


        /**
         * @param UpdateTodoRequest $request
         * @param int $id
         * @return array
         * @throws \Exception
         */
        public function updateTodo(UpdateTodoRequest $request, int $id): array {
            return $this->todoService->updateTodo($request, $id);
        }


        /**
         * @param UpdateTodoStatusRequest $request
         * @param int $id
         * @return array
         * @throws \Exception
         */
        public function updateTodoByStatus(UpdateTodoStatusRequest $request, int $id): array {
            return $this->todoService->updateTodoByStatus($request, $id);
        }


        /**
         * @param int $id
         * @return array
         * @throws \Exception
         */
        public function delete(int $id): array {
            return $this->todoService->deleteTodo($id);
        }

    }
