<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A feature test_required_fields_for_registration ensures that all fields for registration
     * process are filled accordingly.
     *
     * @return void
     */
    public function test_required_fields_for_registration()
    {
        $this->json('POST', 'api/register', [], ['Accept' => 'application/json'])

            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'first_name' => ['The first name field is required.'],
                    'last_name' => ['The last name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                    'nationality' => ['The nationality field is required.'],
                    'date_of_birth' => ['The date of birth field is required.'],
                    'user_phone' => ['The user phone field is required.'],
                    'next_of_kin' => ['The next of kin field is required.'],
                    'next_of_kin_phone' => ['The next of kin phone field is required.']
                ]
            ]);
    }

    /**
     * A feature test RepeatPassword mandates a user to repeat passwords. This repeated 
     * password must match the first one for this test to pass.
     *
     * @return void
     */
    public function test_repeat_password()
    {
        $userData = [
            "first_name" => "Emmanuel",
            "last_name" => "Atuhaire",
            "email" => "patuhaire@gmail.com",
            "password" => "pa12345678",
            'nationality' => 'Ugandan',
            'date_of_birth' => '1998-09-21',
            'user_phone' => '0773784676',
            'next_of_kin' => 'Andrew Rugamba',
            'next_of_kin_phone' => '0753578647',
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password confirmation does not match.']
                ]
            ]);
    }

    /**
     * A feature test_successful_registration which is populated with dummy data to ensure that
     * users can signup successfully.
     *
     * @return void
     */
    public function test_successful_registration()
    {
        $userData = [
            'first_name' => 'Emmanuel',
            'middle_name' => 'Nkundwa',
            'last_name' => 'Atuhaire',
            'email' => 'patungire@gmail.com',
            'password' => 'pa12345678',
            'password_confirmation' => 'pa12345678',
            'nationality' => 'Ugandan',
            'date_of_birth' => '1998-09-21',
            'user_phone' => '0773784676',
            'next_of_kin' => 'Andrew Rugamba',
            'next_of_kin_phone' => '0753578647',
            'id_number' => 'CF5y617563K7865',
            'id_type' => 'National ID',
            'created_with' => 'user',
            'updated_with' => 'app'
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "status",
                "data" => [
                    'id',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'email',
                    'nationality',
                    'date_of_birth',
                    'user_phone',
                    'next_of_kin',
                    'next_of_kin_phone',
                    'id_number',
                    'id_type',
                    'created_at',
                    'updated_at',
                ],
                "access_token",
                "message"
            ]);
    }

    /**
     * A feature test_must_enter_email_and_password ensures that the required fields are not left
     * empty by the user.
     *
     * @return void
     */
    public function test_must_enter_email_and_password()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }


    /**
     * A feature test_successful_login ensures a dummy user created is authenticated successfully.
     *
     * @return void
     */
    public function test_successful_login()
    {
        User::factory()->create([
            'email' => 'sample_user@test.com',
            'password' => bcrypt('sampleuser123'),
        ]);

        $loginData = ['email' => 'sample_user@test.com', 'password' => 'sampleuser123'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'email',
                    'email_verified_at',
                    'nationality',
                    'date_of_birth',
                    'user_phone',
                    'next_of_kin',
                    'next_of_kin_phone',
                    'id_number',
                    'id_type',
                    'created_with',
                    'updated_with',
                    'created_at',
                    'updated_at',
                ],
                'access_token',
                'message'
            ]);

        $this->assertAuthenticated();
    }
}