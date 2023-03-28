<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;

class CustomersFilter
{
    // Defines a list of allowed parameters and their operators
    protected $safeParms = [
        'name' => ['eq'],
        'type' => ['eq'],
        'email' => ['eq'],
        'address' => ['eq'],
        'city' => ['eq'],
        'state' => ['eq'],
        'postalCode' => ['eq', 'gt', 'lt']
    ];

    // Maps the parameter names to their corresponding database column names
    protected $columnMap = [
        'postalCode' => 'postal_code'
    ];

    // Maps operators to their corresponding SQL operators
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

    // Transforms the query parameters from the HTTP request into a query that can be used by the database
    public function transform(Request $req)
    {
        // Initialize an empty query
        $eloQuery = [];

        // Loop through each allowed parameter and its operators
        foreach ($this->safeParms as $parm => $operators) {
            // Get the query parameter value from the HTTP request
            $query = $req->query($parm);

            // If the query parameter is not set, skip to the next parameter
            if (!isset($query)) {
                continue;
            }

            // Map the parameter name to the corresponding column name
            $column = $this->columnMap[$parm] ?? $parm;

            // Loop through each operator for this parameter
            foreach ($operators as $operator) {
                // If the query parameter contains this operator, add it to the query
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        // Return the transformed query
        return $eloQuery;
    }
}
