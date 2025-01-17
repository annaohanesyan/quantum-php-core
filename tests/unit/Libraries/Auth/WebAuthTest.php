<?php

namespace Quantum\Tests\Libraries\Auth;

use Quantum\Exceptions\AuthException;
use Quantum\Libraries\Hasher\Hasher;
use Quantum\Libraries\Auth\WebAuth;


class WebAuthTest extends AuthTestCase
{

    private $webAuth;

    public function setUp(): void
    {

        parent::setUp();

        $this->webAuth = WebAuth::getInstance($this->authService, $this->mailer, new Hasher);

        $admin = $this->webAuth->signup($this->adminUser);

        $this->webAuth->activate($admin->getFieldValue('activation_token'));
    }

    public function tearDown(): void
    {
        self::$users = [];

        $this->webAuth->signout();
    }

    public function testWebAuthConstructor()
    {
        $this->assertInstanceOf(WebAuth::class, $this->webAuth);
    }

    public function testWebSigninIncorrectCredentials()
    {
        $this->expectException(AuthException::class);

        $this->expectExceptionMessage(AuthException::INCORRECT_AUTH_CREDENTIALS);

        $this->webAuth->signin('admin@qt.com', '111111');
    }

    public function testWebSigninCorrectCredentials()
    {
        $this->assertTrue($this->webAuth->signin('admin@qt.com', 'qwerty'));

        $this->assertTrue($this->webAuth->signin('admin@qt.com', 'qwerty', true));
    }

    public function testWebSignout()
    {
        $this->assertFalse(session()->has('auth_user'));

        $this->webAuth->signin('admin@qt.com', 'qwerty');

        $this->assertTrue(session()->has('auth_user'));

        $this->webAuth->signout();

        $this->assertFalse(session()->has('auth_user'));
    }

    public function testWebUser()
    {
        $this->webAuth->signin('admin@qt.com', 'qwerty');

        $this->assertEquals('admin@qt.com', $this->webAuth->user()->getFieldValue('email'));

        $this->assertEquals('admin', $this->webAuth->user()->getFieldValue('role'));

        $this->webAuth->signin('admin@qt.com', 'qwerty', true);

        session()->delete('auth_user');

        $this->assertEquals('admin@qt.com', $this->webAuth->user()->getFieldValue('email'));
    }

    public function testWebCheck()
    {
        $this->assertFalse($this->webAuth->check());

        $this->webAuth->signin('admin@qt.com', 'qwerty');

        $this->assertTrue($this->webAuth->check());
    }

    public function testWebSignupAndSigninWithoutActivation()
    {

        $this->expectException(AuthException::class);

        $this->expectExceptionMessage(AuthException::INACTIVE_ACCOUNT);

        $this->webAuth->signup($this->guestUser);

        $this->assertTrue($this->webAuth->signin('guest@qt.com', '123456'));
    }

    public function testWebSignupAndActivteAccount()
    {
        $user = $this->webAuth->signup($this->guestUser);

        $this->webAuth->activate($user->getFieldValue('activation_token'));

        $this->assertTrue($this->webAuth->signin('guest@qt.com', '123456'));
    }

    public function testWebForgetReset()
    {
        $resetToken = $this->webAuth->forget('admin@qt.com', 'tpl');

        $this->webAuth->reset($resetToken, '123456789');

        $this->assertTrue($this->webAuth->signin('admin@qt.com', '123456789'));
    }

    public function testWebWithoutVerification()
    {
        config()->set('2SV', false);

        config()->set('otp_expiry_time', 2);

        $this->assertTrue($this->webAuth->signin('admin@qt.com', 'qwerty'));
    }

    public function testWebWithVerification()
    {
        config()->set('2SV', true);

        config()->set('otp_expires', 2);

        $this->assertIsString($this->webAuth->signin('admin@qt.com', 'qwerty'));
    }

    public function testWebVerify()
    {
        config()->set('2SV', true);

        config()->set('otp_expires', 2);

        $otp_token = $this->webAuth->signin('admin@qt.com', 'qwerty');

        $this->assertTrue($this->webAuth->verifyOtp(123456789, $otp_token));
    }

    public function testWebResendOtp()
    {
        config()->set('2SV', true);

        config()->set('otp_expires', 2);

        $otp_token = $this->webAuth->signin('admin@qt.com', 'qwerty');

        $this->assertIsString($this->webAuth->resendOtp($otp_token));
    }

}