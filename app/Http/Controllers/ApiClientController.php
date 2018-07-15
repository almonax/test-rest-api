<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class ApiClientController extends Controller
{
    /**
     * REST request methods
     */
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const API_URL = 'http://phptest.loc/api/v1/users';

    /** @var array Errors from requests */
    private $errors = [];

    /** @var string Message from request */
    private $responseMessage = '';

    /** @var string URL to API endpoint */
    private $apiUrl = self::API_URL;

    /**
     * Records list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $response = $this->callApi();
        return view('client.index', [
            'users' => $response->data
        ]);
    }

    /**
     * Get users collection (used by ajax)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $pageNumber = $request->get('page', 1);
        $this->apiUrl .= '?page=' . $pageNumber;
        $response = $this->callApi();
        return response()->json([
            'body' => view('client._users-list', ['users'=> $response->data])->render(),
            'totalPages' => $response->last_page
        ]);
    }

    public function create()
    {
        $errors = $this->getErrors();
        return view('client.create', ['errors' => $errors]);
    }

    public function store(Request $request)
    {
        $response = $this->callApi(self::METHOD_POST, $request->all());
        $this->checkErrors($response);
        if (isset($response->data) && $request->hasFile('avatar')) {
            $apiParam[] = [
                'name'     => 'avatar',
                'filename' => $request->file('avatar')->getClientOriginalName(),
                'contents' => file_get_contents($request->file('avatar')->getRealPath())
            ];
            $this->addEntityId($response->data->id);
            $this->apiUrl .= '/avatar';
            $saveAvatarResponse = $this->callApi(self::METHOD_POST, $apiParam, 'multipart');
            $this->checkErrors($saveAvatarResponse);
        }
        $this->saveErrors();
        if ($response->success) {
            return redirect()->route('clientEdit', ['id' => $response->data->id]);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $this->addEntityId($id);
        $response = $this->callApi();
        $errors = $this->getErrors();
        return view('client.edit', ['response' => $response, 'errors' => $errors]);
    }

    public function update($id, Request $request)
    {
        $this->addEntityId($id);
        $response = $this->callApi(self::METHOD_PUT, $request->all());
        $this->checkErrors($response);

        if ($request->hasFile('avatar')) {
            $apiParam[] = [
                'name'     => 'avatar',
                'filename' => $request->file('avatar')->getClientOriginalName(),
                'contents' => file_get_contents($request->file('avatar')->getRealPath())
            ];
            $this->apiUrl .= '/avatar';
            $saveAvatarResponse = $this->callApi(self::METHOD_POST, $apiParam, 'multipart');
            $this->checkErrors($saveAvatarResponse);
        }
        $this->saveErrors();
        return redirect()->route('clientEdit', ['id' => $id]);
    }

    public function delete($id)
    {
        $this->addEntityId($id);
        $this->callApi(self::METHOD_DELETE);
        return redirect()->route('clientIndex');
    }

    private function addEntityId($id)
    {
        $this->apiUrl .= '/' . $id;
    }

    /**
     * Get saved errors and message from session
     *
     * @return array
     */
    private function getErrors()
    {
        $errors = [];
        if (session('message')) {
            $errors['message'] = session('message');
            $errors['errors'] = session('errors');
            session()->forget('message');
            session()->forget('errors');
        }
        return $errors;
    }

    /**
     * Check response status and collect errors
     *
     * @param object $response
     */
    private function checkErrors($response)
    {
        if ($response && !$response->success) {
            if (isset($response->message)) {
                $this->responseMessage = $response->message;
            }
            if (isset($response->errors)) {
                $this->errors = array_merge($this->errors, (array) $response->errors);
            }
        }
    }

    /**
     * Save errors and message from API response in session
     */
    private function saveErrors()
    {
        session([
            'message' => $this->responseMessage,
            'errors' => $this->errors
        ]);
    }

    /**
     * Call to API endpoint
     *
     * @param string $method
     * @param array $params
     * @param string $bodyType
     * @return mixed
     */
    private function callApi($method = self::METHOD_GET, $params = [], $bodyType = 'form_params')
    {
        if ($params) {
            $params = [
                $bodyType => $params
            ];
        }
        try {
            $client = new Client();
            $response = $client->request($method, $this->apiUrl, $params);
            if ($response->getStatusCode() == 200) {
                $result = $response->getBody()->getContents();
                return json_decode($result);
            }
        } catch (RequestException $e) {
             return json_decode($e->getResponse()->getBody()->getContents());
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
