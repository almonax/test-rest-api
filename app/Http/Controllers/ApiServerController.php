<?php

namespace App\Http\Controllers;

use App\User;
use App\Classes\UserApi;
use Illuminate\Http\Request;
use App\Classes\ImageService;
use App\Classes\UploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiServerController extends Controller
{
    /**
     * Get users list with page separation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers()
    {
        $users = new User();
        $users = $users->getUsers();
        return response()->json(User::transformEntity($users));
    }

    /**
     * Get user data by id
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            throw new ModelNotFoundException(UserApi::USER_NOT_FOUND);
        }
        return response()->json(UserApi::successResponse(null, User::transformEntity($user)));
    }

    /**
     * Create user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        $dataArray = $request->all();
        /** @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = Validator::make($dataArray, User::RULES);
        if ($validator->fails()) {
            return response()->json(UserApi::validationError($validator->errors()), 400);
        }
        $dataArray['password'] = Hash::make(str_random(8));
        try {
            DB::beginTransaction();
            /** @var User $user */
            $user = User::create($dataArray);
            $uploadService = new UploadService();
            $imageService = new ImageService();
            $imageName = $uploadService->saveImage($request, $user->getAvatarPath(), 'avatar');
            $dataArray['avatar'] = $imageName;
            if ($imageName) {
                $imageService->createThumb($imageName, $user->getAvatarPath());
            }
            $user->update($dataArray);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(UserApi::serverError($e), 500);
        }
        return response()->json(UserApi::successResponse(UserApi::SUCCESS_CREATED, User::transformEntity($user)));
    }

    /**
     * Update user data
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        /** @var User $user */
        $user = User::find($id);
        if (!$user) {
            throw new ModelNotFoundException(UserApi::USER_NOT_FOUND);
        }

        $dataArray = $request->all();
        if (!count($dataArray)) {
            return response()->json(UserApi::noDataToUpdate());
        }

        /** @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = Validator::make($dataArray, $user->getRules($dataArray));
        if ($validator->fails()) {
            return response()->json(UserApi::validationError($validator->errors()), 400);
        }
        try {
            DB::beginTransaction();
            $user->fill($dataArray)->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(UserApi::serverError($e), 500);
        }
        return response()->json(UserApi::successResponse(UserApi::SUCCESS_UPDATED));
    }

    public function updateUserAvatar(Request $request, $id)
    {
        /** @var User $user */
        $user = User::find($id);
        if (!$user) {
            throw new ModelNotFoundException(UserApi::USER_NOT_FOUND);
        }
        if (!$request->avatar) {
            return response()->json(UserApi::noDataToUpdate());
        }
        $dataArray = $request->all();
        /** @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = Validator::make($dataArray, $user->getRules($dataArray));
        if ($validator->fails()) {
            return response()->json(UserApi::validationError($validator->errors()), 400);
        }

        try {
            $oldAvatar = $user->avatar;
            $uploadedService = new UploadService();
            $imageService = new ImageService();
            $imageName = $uploadedService->saveImage($request, $user->getAvatarPath(), 'avatar');
            if ($imageName) {
                $imageService->createThumb($imageName, $user->getAvatarPath());
            }
            $user->fill([
                'avatar' => $imageName
            ])->save();
            // remove old images
            $imageService->removeImageAndThumb($oldAvatar, $user->getAvatarPath());
            return response()->json(UserApi::successResponse(UserApi::SUCCESS_UPDATED));
        } catch (\Exception $e) {
            return response()->json(UserApi::serverError($e), 500);
        }
    }

    /**
     * Remove user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($id)
    {
        /** @var User $user */
        $user = User::find($id);
        if (!$user) {
            throw new ModelNotFoundException(UserApi::USER_NOT_FOUND);
        }
        $user->delete();
        return response()->json(UserApi::successResponse(UserApi::SUCCESS_DELETED));
    }
}