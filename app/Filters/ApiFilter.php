<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    // Defines a list of allowed parameters and their operators
    protected $safeParms = [];

    // Maps the parameter names to their corresponding database column names
    protected $columnMap = [
        // 'postalCode' => 'postal_code'
    ];

    // Maps operators to their corresponding SQL operators
    protected $operatorMap = [];

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
