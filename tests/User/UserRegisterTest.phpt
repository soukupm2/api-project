<?php declare(strict_types=1);

namespace Test\User;

use App\Model\User\Dto\UserCreateDto;
use App\Model\User\Exception\UserAlreadyExistsException;
use App\Model\User\Handler\UserCreateHandlerHelper;
use App\Model\User\Handler\UserRegisterHandler;
use App\Model\User\User;
use App\Model\User\UserRepository;
use App\Model\User\UserRole;
use Mockery;
use Nette\Security\Passwords;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class UserRegisterTest extends TestCase
{
    public function testRegister(): void
    {
        $userCreateDto = $this->getUserDto();

        $userRepository = Mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('findByEmail')->andReturn(null);
        $userRepository->shouldReceive('insert')->andReturn(new User(
            1,
            $userCreateDto->email,
            $userCreateDto->name,
            $userCreateDto->role,
            'alsjdflasjd'
        ));

        $helper = new UserCreateHandlerHelper($userRepository, new Passwords());

        $user = (new UserRegisterHandler($helper))->create($userCreateDto);

        Assert::same(1, $user->id);
        Assert::same('test@test.cz', $user->email);
    }

    public function testAlreadyRegistered(): void
    {
        $user = new User(
            1,
            'test@test.cz',
            'Test Test',
            UserRole::READER,
            'alsjdflasjd'
        );

        $userRepository = Mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('findByEmail')->andReturn($user);

        $helper = new UserCreateHandlerHelper($userRepository, new Passwords());

        Assert::throws(
            fn () => (new UserRegisterHandler($helper))->create($this->getUserDto()),
            UserAlreadyExistsException::class,
        );
    }


    private function getUserDto(): UserCreateDto
    {
        return new UserCreateDto(
            'test@test.cz',
            'Test Test',
            'test',
            UserRole::ADMIN,
        );
    }
}

(new UserRegisterTest)->run();
