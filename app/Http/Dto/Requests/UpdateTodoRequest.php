<?php

    namespace App\Http\Dto\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Exceptions\HttpResponseException;

    class UpdateTodoRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize(): bool {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array {
            return [
                "description" => "max:255",
                "status"      => "in:PENDING,COMPLETE"
            ];
        }

        public function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
        {
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => 'Validation errors',
                'data'      => $validator->errors()
            ]));
        }

        public function messages(): array {
            return [
                "todoPhoto.max" => "Maximum upload file size exceeded. Max (10MB)",
                "todoPhoto.mimes" => "wrong mime type"
            ];
        }
    }
