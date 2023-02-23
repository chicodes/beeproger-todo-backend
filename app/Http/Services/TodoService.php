<?php

namespace App\Http\Services;

use App\Events\InvalidateCacheEvent;
use App\Exceptions\ImageNotUploadedException;
use App\Exceptions\NotFoundException;
use App\Exceptions\TodoPhotoException;
use App\Http\Dto\Requests\SaveTodoRequest;
use App\Http\Dto\Requests\StoreTodoRequest;
use App\Http\Dto\Requests\UpdateTodoRequest;
use App\Http\Dto\Requests\UpdateTodoStatusRequest;
use App\Models\Todo;
use App\Utils\Utilities;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TodoService
{
    private array $todoStatus = array("PENDING", "COMPLETE");

    /**
     * @param SaveTodoRequest $request
     * @return array
     * @throws \Exception
     */
    public function saveTodo(SaveTodoRequest $request): array
    {

        try {
            Log::info("CREATING NEW TODO");

            $statusUppercase = strtoupper($request->status);
            if (!in_array($statusUppercase, $this->todoStatus)) {
                throw new NotFoundException("Invalid TODO status!", Response::HTTP_EXPECTATION_FAILED);
            }

            if ($request->file('todoPhoto') == null) {
                throw new TodoPhotoException("todoPhoto", Response::HTTP_BAD_REQUEST);
            }

            $imageLink = cloudinary()->upload($request->file('todoPhoto')->getRealPath())->getSecurePath();

            //confirm if link is returned
            if (!filter_var($imageLink, FILTER_VALIDATE_URL)) {
                throw new ImageNotUploadedException("could not generate image link, image not uploaded", Response::HTTP_EXPECTATION_FAILED);
            }

            $todoData = [
                "description" => $request->description,
                "image_link" => $imageLink,
                "start_date" => $request->startDate,
                "end_date" => $request->endDate,
                "status" => $request->status
            ];
            $todo = Todo::create($todoData);
            Log::info("NEW TODO INSERTED: $todo");
            $todo->refresh();

            return Utilities::getResponse("success", true, Response::HTTP_CREATED, $todo);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return Utilities::getResponse($exception->getMessage(), false, $exception->getCode());
        }
    }


    /**
     * @return array
     */
    public function listAllTodos(): array
    {
        try {
            Log::info("LISTING ALL TODOS");
            $todos = Todo::paginate(10);
            return Utilities::getResponse("success", true, Response::HTTP_OK, $todos);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return Utilities::getResponse($exception->getMessage(), false, $exception->getCode());
        }
    }


    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function getSingle(int $id): array
    {
        try {
            Log::info("GETTING SINGLE TODO");
            $todo = Cache::remember("todo_" . $id, 86400, fn() => Todo::find($id));
            if (empty($todo)) {
                throw new NotFoundException("This todo does not exists!", Response::HTTP_NOT_FOUND);
            }
            return Utilities::getResponse("success", true, Response::HTTP_OK, $todo);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return Utilities::getResponse($exception->getMessage(), false, $exception->getCode());
        }
    }


    /**
     * @param string $status
     * @return array
     * @throws \Exception
     */
    public function getTodosByStatus(string $status): array
    {
        try {
            Log::info("GET TODO BY STATUS");
            $statusUppercase = strtoupper($status);
            if (!in_array($statusUppercase, $this->todoStatus)) {
                throw new NotFoundException("Invalid TODO status!", Response::HTTP_EXPECTATION_FAILED);
            }

            $fidTodoWithStatus = Cache::remember("todos_" . $statusUppercase, 86400, fn() => Todo::where(['status' => $statusUppercase])->get());

            if ($fidTodoWithStatus->isEmpty())
                throw new NotFoundException("Todo with status " . $statusUppercase . " does not exist", Response::HTTP_NOT_FOUND);
            return Utilities::getResponse("success", true, Response::HTTP_OK, $fidTodoWithStatus);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return Utilities::getResponse($exception->getMessage(), false, Response::HTTP_EXPECTATION_FAILED);
        }
    }


    /**
     * @param UpdateTodoRequest åç$request
     * @param int $id
     * @return array
     * @throws NotFoundException
     */
    public function updateTodo(UpdateTodoRequest $request, int $id): array
    {
        try {

            Log::info("UPDATING TODO WITH ID " . $id . " ",);
            $todo = Todo::query()->find($id);

            if (empty($todo)) {
                throw new NotFoundException("Can't find Todo with ID of " . $id . " ", Response::HTTP_NOT_FOUND);
            }

            if ($request->file('todoPhoto') != null) {
                // upload file and get file url
                $imageLink = cloudinary()->upload($request->file('todoPhoto')->getRealPath())->getSecurePath();
                if (!filter_var($imageLink, FILTER_VALIDATE_URL)) {
                    throw new ImageNotUploadedException("could not generate image link, image not uploaded", Response::HTTP_EXPECTATION_FAILED);
                }

                // upload file and get file url
                $imageLink = cloudinary()->upload($request->file('todoPhoto')->getRealPath())->getSecurePath();
                if (!filter_var($imageLink, FILTER_VALIDATE_URL)) {
                    throw new ImageNotUploadedException("could not generate image link, image not uploaded", Response::HTTP_EXPECTATION_FAILED);
                }
            }

            Log::info("Todo photo not provided");


            if ($request->description == null) {
                $request->description = $todo->description;
            } else {
                $request->description = $request->description;
            }

            if ($request->file('todoPhoto') == null) {
                $imageLink = $todo->image_link;
            } else {
                $request->description = $request->description;
            }

            if ($request->startDate == null) {
                $request->startDate = $todo->start_date;
            } else {
                $request->startDate = $request->startDate;
            }

            if ($request->endDate == null) {
                $request->endDate = $todo->end_date;
            } else {
                $request->endDate = $request->endDate;
            }

            if ($request->status == null) {
                $request->status = $todo->status;
            } else {
                $request->status = $request->status;
            }

            $todo->description = $request->description;
            $todo->image_link = $imageLink;
            $todo->start_date = $request->startDate;
            $todo->end_date = $request->endDate;
            $todo->status = $request->status;
            $todo->save();
            $todo->refresh();
            Cache::forget("todo_" . $id);
            Log::info("TODO WITH ID " . $id . " UPDATED SUCCESSFULLY ",);
            return Utilities::getResponse("success", true, Response::HTTP_OK, $todo);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return Utilities::getResponse($exception->getMessage(), false, Response::HTTP_EXPECTATION_FAILED);
        }
    }


    /**
     * @param int $id
     * @param UpdateTodoStatusRequest $request
     * @return array
     * @throws \Exception
     */
    public function updateTodoByStatus(UpdateTodoStatusRequest $request, int $id): array
    {
        try {

            Log::info("UPDATING STATUS OF TODO WITH ID " . $id . " ",);
            $todo = Todo::query()->find($id);

            if (empty($todo)) {
                throw new NotFoundException("Can't find Todo with ID of " . $id . " ", Response::HTTP_NOT_FOUND);
            }

            $statusUppercase = strtoupper($request->status);
            if (!in_array($statusUppercase, $this->todoStatus)) {
                throw new NotFoundException("Invalid TODO status!", Response::HTTP_EXPECTATION_FAILED);
            }
            $todo->status = $statusUppercase;
            $todo->save();

            Cache::forget("todos_" . $statusUppercase);
            Log::info("STATUS OF TODO WITH ID " . $id . " UPDATED SUCCESSFULLY ",);
            return Utilities::getResponse("Success", true, Response::HTTP_OK, $todo);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return Utilities::getResponse($exception->getMessage(), false, Response::HTTP_EXPECTATION_FAILED);
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function deleteTodo(int $id): array
    {

        try {

            Log::info("DELETING TODO WITH ID " . $id . " ",);
            $todo = Todo::query()->find($id);

            if (empty($todo) || $todo == 'null') {
                throw new NotFoundException("Can't find Todo with ID of " . $id . " ", Response::HTTP_NOT_FOUND);
            }

            $todo->delete();
            Cache::forget("todo_" . $id);
            Log::info("TODO WITH ID " . $id . " DELETED SUCCESSFULLY ",);
            return Utilities::getResponse("Todo deleted successfully", true, Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return Utilities::getResponse($exception->getMessage(), false, $exception->getCode());
        }
    }
}
