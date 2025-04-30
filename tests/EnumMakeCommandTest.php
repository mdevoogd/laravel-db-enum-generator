<?php

namespace mdevoogd\LaravelDatabaseEnum;

use Illuminate\Support\Str;

/**
 * The enum make command test.
 *
 */
class EnumMakeCommandTest extends TestCase
{
    /**
     * @test
     */
    public function requireArguments()
    {
        $this->artisan('make:enum')
            ->expectsQuestion('What should the enum be named?', 'UserRoleTypes')
            ;
    }

    /**
     * @test
     */
    public function generateEnum()
    {
        $this->artisan("make:enum", [
            "name" => "UserRoleTypes",
            "--model" => "mdevoogd\\LaravelDatabaseEnum\\Model\\UserRole",
            "--value" => "name",
        ])->assertExitCode(0);

        $this->assertMatchesSnapshot(file_get_contents($this->appPath('Enums/UserRoleTypes.php')));
    }

    /**
     * @test
     */
    public function generateCustomizedEnumKeys()
    {
        $this->artisan('make:enum', [
            "name" => "Languages",
            "--table" => "languages",
            "--slug" => "name",
            "--value" => "name",
        ])->assertExitCode(0);

        $this->assertMatchesSnapshot(file_get_contents($this->appPath('Enums/Languages.php')));
    }

    /**
     * @test
     */
//    public function generateMultipleValuesInMap()
//    {
//        $this->artisan('make:enum', [
//            "name" => "LanguagesEnum",
//            "--table" => "languages",
//            "--slug" => "name",
//            "--value" => "id,name,region",
//        ])->assertExitCode(0);
//
//        $this->assertMatchesSnapshot(file_get_contents($this->appPath('Enums/LanguagesEnum.php')));
//    }

    /**
     * @test
     */
    public function generateEnumInCustomDirectory()
    {
        $this->artisan("make:enum", [
            "name" => "UserRoleTypes",
            "--model" => "mdevoogd\\LaravelDatabaseEnum\\Model\\UserRole",
            "--path" => "Other/Directory",
        ])->assertExitCode(0);

        $this->assertFileExists($this->appPath('Other/Directory/UserRoleTypes.php'));
    }

    /**
     * @test
     */
    public function forceEnumGeneration()
    {
        $this->artisan("make:enum", [
            "name" => "UserRoles",
            "--model" => "mdevoogd\\LaravelDatabaseEnum\\Model\\UserRole",
            "--value" => "name",
        ])->assertExitCode(0);

        $this->artisan("make:enum", [
            "name" => "UserRoles",
            "--model" => "mdevoogd\\LaravelDatabaseEnum\\Model\\UserRole",
            "--value" => "name",
            "--force" => true,
        ])->assertExitCode(0);
    }

    /**
     * Retrieve the application path
     *
     * @param string $path
     * @return string
     */
    protected function appPath($path = '')
    {
        $appPath = TestCase::applicationBasePath() . '/app';

        return $appPath . Str::start($path, '/');
    }
}
