<?php

use Application\Model\User;
use Ouzo\Tests\ControllerTestCase;

class UsersControllerTest extends ControllerTestCase
{
    /**
     * @test
     */
    public function shouldRenderIndex()
    {
        //when
        $this->get('/users');

        //then
        $this->assertRenders('Users/index');
    }

    /**
     * @test
     */
    public function shouldRenderFreshWhenValidationFailedInCreate()
    {
        //when
        $this->post('/users', ['user' => [
            'login' => ''
        ]]);

        //then
        $this->assertRenders('Users/fresh');
    }

    /**
     * @test
     */
    public function shouldRedirectToIndexOnSuccessInCreate()
    {
        //when
        $this->post('/users', ['user' => [
            'login' => 'login'
        ]]);

        //then
        $this->assertRedirectsTo(usersPath());
    }

    /**
     * @test
     */
    public function shouldCreateUser()
    {
        //when
        $this->post('/users', ['user' => [
            'login' => 'login'
        ]]);

        //then
        $this->assertNotNull(User::where(['login' => 'login'])->fetch());
    }

    /**
     * @test
     */
    public function shouldRenderEditWhenValidationFailedInUpdate()
    {
        $user = User::create(['login' => 'login']);

        //when
        $this->put("/users/{$user->id}", ['user' => [
            'login' => ''
        ]]);

        //then
        $this->assertRenders('Users/edit');
    }

    /**
     * @test
     */
    public function shouldRedirectToShowOnSuccessInUpdate()
    {
        //given
        $user = User::create(['login' => 'login']);

        //when
        $this->put("/users/{$user->id}", ['user' => [
            'login' => 'new login'
        ]]);

        //then
        $this->assertRedirectsTo(userPath($user->id));
    }

    /**
     * @test
     */
    public function shouldUpdateUser()
    {
        //given
        $user = User::create(['login' => 'login']);

        //when
        $this->put("/users/{$user->id}", ['user' => [
            'login' => 'new login'
        ]]);

        //then
        $this->assertEquals('new login', $user->reload()->login);
    }

    /**
     * @test
     */
    public function shouldShowUser()
    {
        //given
        $user = User::create(['login' => 'login']);

        //when
        $this->get("/users/{$user->id}");

        //then
        $this->assertRenders('Users/show');
    }
}