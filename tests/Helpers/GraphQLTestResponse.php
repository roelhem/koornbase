<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 02-09-18
 * Time: 21:02
 */

namespace Tests\Helpers;


use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use PHPUnit\Framework\Assert as PHPUnit;

class GraphQLTestResponse extends TestResponse
{

    /**
     * GraphQLTestResponse constructor.
     * @param Response|TestResponse $response
     */
    public function __construct($response)
    {
        if($response instanceof Response) {
            parent::__construct($response);
        } elseif ($response instanceof TestResponse) {
            parent::__construct($response->baseResponse);
        } else {
            throw new \InvalidArgumentException("The constructor can only take a ".Response::class." or a ".TestResponse::class.".");
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RESPONSE ------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Returns if this GraphQLTestResponse is a valid GraphQL Response.
     *
     * @return bool
     */
    public function isGraphQLResponse()
    {
        try {
            $json = $this->decodeResponseJson();
            $keys = array_keys($json);
            // Check if data is available
            if(!in_array('data',$keys)) {
                return false;
            }
            // Check if all the keys are valid keys
            foreach ($keys as $key) {
                if(!in_array($key,['data','errors'])) {
                    return false;
                }
            }
            // Return the true value.
            return true;
        } catch (\Exception $exception) {
            PHPUnit::assertTrue(false,
                'Unable to decode the response: '.PHP_EOL.PHP_EOL.$exception->getMessage()
            );
        }
        return false;
    }

    /**
     * Asserts that this response is a valid GraphQL-response.
     *
     * @return $this
     */
    public function assertGraphQLResponse($message = '')
    {
        if(!empty($message)) {
            $message = $message.PHP_EOL.PHP_EOL;
        }
        $message .= 'The response is not a GraphQL-response.';
        PHPUnit::assertTrue($this->isGraphQLResponse(), $message);

        return $this;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ERRORS --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the Errors that are returned by the GraphQL API.
     *
     * @return array|null
     */
    public function graphQLErrors()
    {
        try {
            return $this->decodeResponseJson('errors');
        } catch (\Exception $exception) {
            PHPUnit::assertTrue(false,
                'Unable to get the errors from the GraphQL-response: '.PHP_EOL.PHP_EOL.$exception->getMessage()
            );
        }
    }

    /**
     * Returns if there are GraphQL errors in the response.
     *
     * @return bool
     */
    public function hasGraphQLErrors()
    {
        $errors = $this->graphQLErrors();
        return $errors !== null && count($errors) > 0;
    }

    /**
     * Returns the errors of the GraphQL-response in a human-readable string format.
     *
     * @return string
     */
    protected function graphQLErrorsMessage()
    {
        $errors = $this->graphQLErrors();
        if($errors === null) {
            return 'There are NO errors in the GraphQL-response.'.PHP_EOL.PHP_EOL;
        }

        $errorCount = count($errors);

        $res = "Has a total of $errorCount errors in the GraphQL response:".PHP_EOL.PHP_EOL;
        $res .= json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $res .= PHP_EOL.PHP_EOL;

        return $res;
    }

    /**
     * Asserts if the current GraphQLTestResponse has errors in the GraphQL-response.
     *
     * @return $this
     */
    public function assertGraphQLErrors()
    {
        $this->assertGraphQLResponse();

        PHPUnit::assertTrue($this->hasGraphQLErrors(), $this->graphQLErrorsMessage());

        return $this;
    }

    /**
     * Assert if the current GraphQLTestResponse has no errors in the GraphQL-response.
     *
     * @return $this
     */
    public function assertGraphQLNoErrors()
    {
        $this->assertGraphQLResponse();

        PHPUnit::assertFalse($this->hasGraphQLErrors(), $this->graphQLErrorsMessage());

        return $this;
    }

    /**
     * Returns if there are (user)errors in the response (Not in the error-request, and not in the GraphQL-response);
     *
     * @return bool
     */
    public function hasErrors()
    {
        return $this->isClientError() || $this->hasGraphQLErrors();
    }

    /**
     * Asserts that there are (user)errors in the response.
     *
     * @return $this
     */
    public function assertErrors()
    {
        PHPUnit::assertTrue($this->hasErrors(), 'There are NO Errors in this response.');

        return $this;
    }

    /**
     * Asserts that there are no errors in the response.
     *
     * @return $this
     */
    public function assertNoErrors()
    {
        $this->assertSuccessful();
        $this->assertGraphQLNoErrors();

        return $this;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- DATA ----------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the array object that creates a json-array of a GraphQL-response that corresponds with provided
     * data.
     *
     * @param mixed $data
     * @return array
     */
    protected function dataToJsonArray($data)
    {
        return ['data' => $data];
    }

    /**
     * Assert that the GraphQL-response is a superset of the given data-JSON.
     *
     * @param  array  $data
     * @param  bool  $strict
     * @return $this
     */
    public function assertData($data, $strict = false)
    {
        return $this->assertJson($this->dataToJsonArray($data), $strict);
    }

}