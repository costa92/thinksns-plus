<?php

namespace Ts\Test\Http\APIs\V1;

use App\Models\User;

class GetUserTest extends TestCase
{
    // test API.
    protected $uri = '/api/v1/users/1';

    protected $url_404 = '/api/v1/users/5';

    // test user data.
    protected $user_id = 1;

    public function testGetUser()
    {
        $this->getJson($this->uri, ['ACCESS-TOKEN' => 'dc22aae7774d9387ba766a1adce3165b']);
        $this->seeStatusCode(201);
        $profiles = User::find($this->user_id)->datas;
        $datas = [];
        foreach ($profiles as $value) {
            $datas[$value->profile] = $value->pivot->user_profile_setting_data;
        }

        $json = $this->createMessageResponseBody([
            'code'   => 0,
            'status' => true,
            'data'   => $datas,
        ]);
        $this->seeJsonEquals($json);
    }

    public function testUserNotExistedStep()
    {
        $this->getJson($this->url_404, ['ACCESS-TOKEN' => 'dc22aae7774d9387ba766a1adce3165b']);
        $this->seeStatusCode(404);
        $json = $this->createMessageResponseBody([
            'code'   => 1005,
            'status' => false,
        ]);
        $this->seeJsonEquals($json);
    }
}
