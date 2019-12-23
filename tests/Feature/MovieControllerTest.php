<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Omdb\Omdb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    private const BATMAN_MOVIE_TITLE = 'Batman';
    private const BATMAN_MOVIE_YEAR = 1989;
    private const BATMAN_MOVIE_IMDB_ID = 'tt0096895';

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @group MovieIndex
     * @group Guest
     * @return void
     * @throws \Exception
     */
    public function testIndexAction()
    {
        $createCount = 4;
        factory(Movie::class, $createCount)->create();

        $response = $this->getJson(route('movies.index'));
        $response
            ->assertJsonStructure($this->getJsonStructureFile('movies.list'))
            ->assertJsonCount($createCount, 'data')
            ->assertJsonFragment(['success' => true])
            ->assertStatus(200);
    }

    /**
     * @group MovieIndex
     * @group Guest
     * @return void
     */
    public function testIndexActionOrderByTitleDesc()
    {
        $createCount = 4;
        factory(Movie::class, $createCount)->create();
        $requestData = ['order_by' => 'title', 'order_dir' => 'desc'];
        $moviesInOrder = Movie::query()->select(Movie::LIST_SELECT_COLUMNS)
            ->orderByDesc('title')->get()->toArray();

        $response = $this->json('GET', route('movies.index'), $requestData);
        $response
            ->assertJsonCount($createCount, 'data')
            ->assertExactJson(['data' => $moviesInOrder, 'success' => true, 'message' => 'ok'])
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @group MovieIndex
     * @group Guest
     * @return void
     */
    public function testIndexActionOrderByTitleAsc()
    {
        $createCount = 4;
        factory(Movie::class, $createCount)->create();
        $requestData = ['order_by' => 'title', 'order_dir' => 'asc'];
        $moviesInOrder = Movie::query()->select(Movie::LIST_SELECT_COLUMNS)
            ->orderBy('title')->get()->toArray();

        $response = $this->json('GET', route('movies.index'), $requestData);
        $response
            ->assertJsonCount($createCount, 'data')
            ->assertExactJson(['data' => $moviesInOrder, 'success' => true, 'message' => 'ok'])
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @group MovieIndex
     * @group Guest
     * @return void
     */
    public function testIndexActionOrderByYearDesc()
    {
        $createCount = 4;
        factory(Movie::class, $createCount)->create();
        $requestData = ['order_by' => 'title', 'order_dir' => 'desc'];
        $moviesInOrder = Movie::query()->select(Movie::LIST_SELECT_COLUMNS)
            ->orderByDesc('title')->get()->toArray();

        $response = $this->json('GET', route('movies.index'), $requestData);
        $response
            ->assertJsonCount($createCount, 'data')
            ->assertExactJson(['data' => $moviesInOrder, 'success' => true, 'message' => 'ok'])
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @group MovieIndex
     * @group Guest
     * @return void
     */
    public function testIndexActionOrderByYearAsc()
    {
        $createCount = 4;
        factory(Movie::class, $createCount)->create();
        $requestData = ['order_by' => 'title', 'order_dir' => 'asc'];
        $moviesInOrder = Movie::query()->select(Movie::LIST_SELECT_COLUMNS)
            ->orderBy('title')->get()->toArray();

        $response = $this->json('GET', route('movies.index'), $requestData);
        $response
            ->assertJsonCount($createCount, 'data')
            ->assertExactJson(['data' => $moviesInOrder, 'success' => true, 'message' => 'ok'])
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @group MovieSearch
     * @group Guest
     * @return void
     * @throws \Exception
     */
    public function testSearchAction()
    {
        $movie = factory(Movie::class)->create();
        $requestData = ['title' => $movie->title, 'year' => ''];

        $response = $this->json('GET', route('movies.search'), $requestData);
        $response
            ->assertJsonStructure($this->getJsonStructureFile('movies.list'))
            ->assertJsonFragment(['success' => true])
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @group MovieSearch
     * @group Guest
     * @group Validation
     * @return void
     * @throws \Exception
     */
    public function testSearchValidationYearMustBePresent()
    {
        $requestData = [];

        $response = $this->json('GET', route('movies.search'), $requestData);
        $response
            ->assertJsonValidationErrors([
                'year' => 'The year field must be present.',
                'title' => 'The title field is required.'
            ])
            ->assertJsonFragment(['success' => false])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @group MovieSearch
     * @group Guest
     * @return void
     */
    public function testGuestCanSearchCreatedMovie()
    {
        $omdbMock = Mockery::mock(Omdb::class);
        $movie = factory(Movie::class)->create();
        $requestData = ['title' => $movie->title, 'year' => $movie->year];

        $omdbMock->shouldReceive('search')->once()->with($requestData)->andReturn([]);
        $expectedMovie = $movie->select(movie::LIST_SELECT_COLUMNS)->get()->toArray();
        $this->app->instance(Omdb::class, $omdbMock);

        $response = $this->json('GET', route('movies.search'), $requestData);
        $response
            ->assertExactJson(['data' => $expectedMovie, 'success' => true, 'message' => 'ok'])
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @group MovieSearch
     * @group Guest
     * @return void
     */
    public function testGuestCanSearchOmdbMovie()
    {
        $requestData = ['title' => 'The Devil\'s Advocate', 'year' => 1997];

        $response = $this->json('GET', route('movies.search'), $requestData);
        $response
            ->assertJsonFragment([
                 'imdbId' => 'tt0118971',
                 'title' => 'The Devil\'s Advocate',
                 'year' => 1997
            ])
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @group MovieSearch
     * @group Guest
     * @return void
     */
    public function testSearchGuestDontSeeDuplicatedMovies()
    {
        factory(Movie::class)->state('batman')->create();
        $requestData = ['title' => 'Batman', 'year' => 1989];

        $response = $this->json('GET', route('movies.search'), $requestData);
        $response
            ->assertJsonCount(2, 'data')
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @group MovieGetFromOmdb
     * @group Guest
     * @return void
     * @throws \Exception
     */
    public function testGetFromOmdbAction()
    {
        $requestData = ['omdb_id' => self::BATMAN_MOVIE_IMDB_ID];
        $response = $this->json('GET', route('movies.get-from-omdb'), $requestData);

        $response
            ->assertJsonStructure($this->getJsonStructureFile('omdb.single'))
            ->assertJsonFragment(['success' => true])
            ->assertStatus(JsonResponse::HTTP_OK);
    }
}
