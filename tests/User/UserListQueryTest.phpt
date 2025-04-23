<?php declare(strict_types=1);

namespace Test\User;

use App\Core\Api\Auth\Authorizator;
use App\Model\User\Exception\AuthorizationException;
use App\Model\User\Query\UserListQuery;
use App\Model\User\User;
use App\Model\User\UserRepository;
use App\Model\User\UserRole;
use Mockery;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class UserListQueryTest extends TestCase
{
    public function testOk(): void
    {
        $user = new User(
            1,
            'test@test.cz',
            'Test Test',
            UserRole::ADMIN,
            'alsjdflasjd'
        );

        $userRepository = Mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('findAll')
            ->andReturn([$user]);
        $authorizator = Mockery::mock(Authorizator::class);
        $authorizator->shouldReceive('requireLoggedInUser')
            ->andReturn($user);
        $authorizator->makePartial();

        $users = (new UserListQuery($userRepository, $authorizator))->fetch();

        Assert::count(1, $users);
    }

    public function testNotAuthorized(): void
    {
        $user = new User(
            1,
            'test@test.cz',
            'Test Test',
            UserRole::READER,
            'alsjdflasjd'
        );

        $userRepository = Mockery::mock(UserRepository::class);

        $authorizator = Mockery::mock(Authorizator::class);
        $authorizator->shouldReceive('requireLoggedInUser')->andReturn($user);
        $authorizator->makePartial();

        Assert::throws(
            static fn () => (new UserListQuery($userRepository, $authorizator))->fetch(),
            AuthorizationException::class,
        );
    }
}

(new UserListQueryTest)->run();
