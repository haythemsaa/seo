<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class ValidationTest extends TestCase
{
    /**
     * Test URL validation.
     */
    public function test_url_validation(): void
    {
        $validUrls = [
            'https://example.com',
            'http://example.com',
            'https://sub.example.com',
            'https://example.com/path',
        ];

        foreach ($validUrls as $url) {
            $validator = Validator::make(['url' => $url], ['url' => 'required|url']);
            $this->assertFalse($validator->fails(), "Failed to validate: {$url}");
        }

        $invalidUrls = [
            'not-a-url',
            'ftp://example.com',
            'example',
            '',
        ];

        foreach ($invalidUrls as $url) {
            $validator = Validator::make(['url' => $url], ['url' => 'required|url']);
            $this->assertTrue($validator->fails(), "Should have failed: {$url}");
        }
    }

    /**
     * Test email validation.
     */
    public function test_email_validation(): void
    {
        $validEmails = [
            'test@example.com',
            'user.name@example.com',
            'user+tag@example.co.uk',
        ];

        foreach ($validEmails as $email) {
            $validator = Validator::make(['email' => $email], ['email' => 'required|email']);
            $this->assertFalse($validator->fails(), "Failed to validate: {$email}");
        }

        $invalidEmails = [
            'not-an-email',
            '@example.com',
            'user@',
            'user',
        ];

        foreach ($invalidEmails as $email) {
            $validator = Validator::make(['email' => $email], ['email' => 'required|email']);
            $this->assertTrue($validator->fails(), "Should have failed: {$email}");
        }
    }

    /**
     * Test project data validation.
     */
    public function test_project_validation_rules(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'country' => 'required|string|size:2',
            'language' => 'required|string|size:2',
            'search_engine' => 'required|in:google,bing,yahoo',
        ];

        // Valid data
        $validData = [
            'name' => 'Test Project',
            'url' => 'https://example.com',
            'country' => 'FR',
            'language' => 'fr',
            'search_engine' => 'google',
        ];

        $validator = Validator::make($validData, $rules);
        $this->assertFalse($validator->fails());

        // Invalid data - missing required fields
        $invalidData = [
            'name' => '',
            'url' => 'not-a-url',
        ];

        $validator = Validator::make($invalidData, $rules);
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('name'));
        $this->assertTrue($validator->errors()->has('url'));
        $this->assertTrue($validator->errors()->has('country'));
    }

    /**
     * Test keyword validation.
     */
    public function test_keyword_validation_rules(): void
    {
        $rules = [
            'keyword' => 'required|string|max:255',
            'search_volume' => 'nullable|integer|min:0',
            'difficulty' => 'nullable|integer|min:0|max:100',
            'cpc' => 'nullable|numeric|min:0',
        ];

        // Valid keyword data
        $validData = [
            'keyword' => 'test keyword',
            'search_volume' => 1000,
            'difficulty' => 45,
            'cpc' => 2.50,
        ];

        $validator = Validator::make($validData, $rules);
        $this->assertFalse($validator->fails());

        // Invalid difficulty (over 100)
        $invalidData = [
            'keyword' => 'test',
            'difficulty' => 150,
        ];

        $validator = Validator::make($invalidData, $rules);
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('difficulty'));
    }

    /**
     * Test password validation.
     */
    public function test_password_validation(): void
    {
        $rules = [
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            'password_confirmation' => 'required|same:password',
        ];

        // Valid password
        $validData = [
            'password' => 'Test1234',
            'password_confirmation' => 'Test1234',
        ];

        $validator = Validator::make($validData, $rules);
        $this->assertFalse($validator->fails());

        // Too short
        $invalidData = [
            'password' => 'Test12',
            'password_confirmation' => 'Test12',
        ];

        $validator = Validator::make($invalidData, $rules);
        $this->assertTrue($validator->fails());

        // No uppercase
        $invalidData = [
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ];

        $validator = Validator::make($invalidData, $rules);
        $this->assertTrue($validator->fails());

        // Passwords don't match
        $invalidData = [
            'password' => 'Test1234',
            'password_confirmation' => 'Test5678',
        ];

        $validator = Validator::make($invalidData, $rules);
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('password_confirmation'));
    }
}
