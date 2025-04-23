<?php declare(strict_types=1);

namespace Test\Article;

use App\Core\Api\Auth\Authorizator;
use App\Model\Article\Article;
use App\Model\Article\ArticleRepository;
use App\Model\Article\Dto\ArticleUpdateDto;
use App\Model\Article\Handler\ArticleUpdateHandler;
use App\Model\User\Exception\AuthorizationException;
use App\Model\User\User;
use App\Model\User\UserRole;
use Mockery;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class ArticleUpdateHandlerTest extends TestCase
{
    public function testOk(): void
    {
        $user = new User(
            1,
            'test@test.cz',
            'Test Test',
            UserRole::AUTHOR,
            'alsjdflasjd'
        );

        $article = new Article(
            1,
            $user->id,
            'test',
            'test test test',
        );

        $articleUpdateDto = new ArticleUpdateDto($article->id, 'foo', 'baz');

        $articleRepository = Mockery::mock(ArticleRepository::class);
        $articleRepository->shouldReceive('findById')
            ->andReturn($article);
        $articleRepository->shouldReceive('update')
            ->andReturn(1);
        $authorizator = Mockery::mock(Authorizator::class);
        $authorizator->shouldReceive('requireLoggedInUser')
            ->andReturn($user);
        $authorizator->makePartial();

        $result = (new ArticleUpdateHandler($articleRepository, $authorizator))->update($articleUpdateDto);

        Assert::same(1, $result);
    }


    public function testNoRights(): void
    {
        $user = new User(
            2,
            'test@test.cz',
            'Test Test',
            UserRole::AUTHOR,
            'alsjdflasjd'
        );

        $article = new Article(
            1,
            1,
            'test',
            'test test test',
        );

        $articleUpdateDto = new ArticleUpdateDto($article->id, 'foo', 'baz');

        $articleRepository = Mockery::mock(ArticleRepository::class);
        $articleRepository->shouldReceive('findById')
            ->andReturn($article);
        $authorizator = Mockery::mock(Authorizator::class);
        $authorizator->shouldReceive('requireLoggedInUser')
            ->andReturn($user);
        $authorizator->makePartial();

        Assert::throws(
            static fn () => (new ArticleUpdateHandler($articleRepository, $authorizator))->update($articleUpdateDto),
            AuthorizationException::class,
        );
    }
}

(new ArticleUpdateHandlerTest)->run();
