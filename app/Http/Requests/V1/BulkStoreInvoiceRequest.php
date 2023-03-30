<?php

// Define the namespace of this class
namespace App\Http\Requests\V1;

// Import the necessary classes
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

// Define the class that extends FormRequest
class BulkStoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Define the validation rules for each field
        return [
            '*.customerId' => ['required', 'integer'],
            '*.amount' => ['required', 'numeric'],
            '*.status' => ['required', Rule::in(['B', 'P', 'V', 'b', 'p', 'v'])],
            '*.billedDate' => ['required', 'date_format:Y-m-d H:i:s'],
            '*.paidDate' => ['date_format:Y-m-d H:i:s', 'nullable'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * This method modifies the request data before it is validated
     * by replacing snake_case field names with camelCase.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Initialize an empty array to store the modified data
        $data = [];

        // Loop through the request data and modify the field names
        foreach ($this->toArray() as $obj) {
            $obj['customer_id'] = $obj['customerId'] ?? null;
            $obj['billed_date'] = $obj['billedDate'] ?? null;
            $obj['paid_date'] = $obj['paidDate'] ?? null;

            // Add the modified object to the data array
            $data[] = $obj;
        }

        // Merge the modified data back into the request
        $this->merge($data);
    }
}
