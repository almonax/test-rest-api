<?php
namespace App\Classes;

use Illuminate\Support\Facades\Log;

class UserApi
{
    const SUCCESS_CREATED = 'User has been success created';
    const SUCCESS_UPDATED = 'User has been success updated';
    const SUCCESS_DELETED = 'User has been success deleted';
    const USER_NOT_FOUND = 'User not found';
    const USER_EMAIL_TAKEN = 'The email has already been taken.';
    /**
     * @param string $message
     * @param array $extraData
     * @param int $code
     * @return array
     */
    public static function successResponse($message, $extraData = [], $code = 200)
    {
        $data = [
            'code'    => $code,
            'success'  => true,
            'message' => $message
        ];
        if ($extraData) {
            $data['data'] = $extraData;
        }
        return $data;
    }

    public static function validationError($errors)
    {
        return [
            'code'    => 400,
            'success'  => false,
            'message' => 'Request validation error',
            'errors'  => $errors
        ];
    }

    public static function noDataToUpdate()
    {
        return [
            'code'    => 400,
            'success'  => false,
            'message' => 'No data to update'
        ];
    }

    public static function emailAlreadyUsed()
    {
        return [
            'code'    => 400,
            'success'  => false,
            'message' => 'Request validation error',
            'errors'  => [
                'email' => ['The email has already been taken.']
            ]
        ];
    }

    /**
     * @param \Exception $exception
     * @param int $code
     * @return array
     */
    public static function serverError($exception, $code = 500)
    {
        Log::error($exception->getMessage());
        return [
            'code'    => $code,
            'success'  => false,
            'message' => 'Internal server error'
        ];
    }
}