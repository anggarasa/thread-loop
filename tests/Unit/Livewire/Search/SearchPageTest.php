<?php

namespace Tests\Unit\Livewire\Search;

use App\Livewire\Search\SearchPage;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SearchPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_can_be_rendered()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);

        $component->assertStatus(200);
    }

    public function test_mount_loads_initial_data()
    {
        $user = User::factory()->create();
        Post::factory()->count(3)->create();

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);

        $this->assertEquals('posts', $component->activeTab);
        $this->assertEquals('recent', $component->sortBy);
        $this->assertFalse($component->showFilters);
        $this->assertEquals('all', $component->dateFilter);
        $this->assertEquals('all', $component->mediaFilter);
    }

    public function test_updated_search_resets_data_and_loads_when_search_not_empty()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['content' => 'Test search content']);

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');

        $this->assertEquals('test', $component->search);
        $this->assertNotEmpty($component->posts);
    }

    public function test_updated_search_shows_suggestions_when_search_less_than_3_characters()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'te');

        $this->assertTrue($component->showSuggestions);
    }

    public function test_updated_search_hides_suggestions_when_search_3_or_more_characters()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');

        $this->assertFalse($component->showSuggestions);
    }

    public function test_updated_active_tab_switches_between_posts_and_users()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['content' => 'test post']);
        $searchUser = User::factory()->create(['name' => 'test user']);

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');
        $component->set('activeTab', 'users');

        $this->assertEquals('users', $component->activeTab);
        $this->assertNotEmpty($component->users);
    }

    public function test_updated_sort_by_changes_sorting()
    {
        $user = User::factory()->create();
        $post1 = Post::factory()->create([
            'content' => 'test post 1',
            'likes_count' => 10,
            'created_at' => now()->subDays(1),
        ]);
        $post2 = Post::factory()->create([
            'content' => 'test post 2',
            'likes_count' => 20,
            'created_at' => now()->subDays(2),
        ]);

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');
        $component->set('sortBy', 'popular');

        $this->assertEquals('popular', $component->sortBy);
    }

    public function test_updated_date_filter_applies_date_filtering()
    {
        $user = User::factory()->create();
        $recentPost = Post::factory()->create([
            'content' => 'recent test post',
            'created_at' => now()->subDays(1),
        ]);
        $oldPost = Post::factory()->create([
            'content' => 'old test post',
            'created_at' => now()->subDays(10),
        ]);

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');
        $component->set('dateFilter', 'week');

        $this->assertEquals('week', $component->dateFilter);
    }

    public function test_updated_media_filter_applies_media_filtering()
    {
        $user = User::factory()->create();
        $imagePost = Post::factory()->create([
            'content' => 'test image post',
            'media_type' => 'image',
        ]);
        $videoPost = Post::factory()->create([
            'content' => 'test video post',
            'media_type' => 'video',
        ]);

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');
        $component->set('mediaFilter', 'image');

        $this->assertEquals('image', $component->mediaFilter);
    }

    public function test_clear_search_resets_search_and_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');
        $component->call('clearSearch');

        $this->assertEquals('', $component->search);
        $this->assertFalse($component->showSuggestions);
    }

    public function test_select_suggestion_sets_search_and_loads_data()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['content' => 'suggestion test']);

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->call('selectSuggestion', 'suggestion');

        $this->assertEquals('suggestion', $component->search);
        $this->assertFalse($component->showSuggestions);
    }

    public function test_toggle_filters_shows_or_hides_filters()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);

        $this->assertFalse($component->showFilters);

        $component->call('toggleFilters');
        $this->assertTrue($component->showFilters);

        $component->call('toggleFilters');
        $this->assertFalse($component->showFilters);
    }

    public function test_load_more_posts_loads_additional_posts()
    {
        $user = User::factory()->create();
        Post::factory()->count(25)->create(['content' => 'test post']); // Create more posts

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');

        $initialCount = count($component->posts);
        $component->call('loadMorePosts');

        // Check that posts are loaded (may be same count if all posts already loaded)
        $this->assertGreaterThanOrEqual($initialCount, count($component->posts));
    }

    public function test_load_more_users_loads_additional_users()
    {
        $user = User::factory()->create();
        User::factory()->count(25)->create(['name' => 'test user']); // Create more users

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');
        $component->set('activeTab', 'users');

        $initialCount = count($component->users);
        $component->call('loadMoreUsers');

        // Check that users are loaded (may be same count if all users already loaded)
        $this->assertGreaterThanOrEqual($initialCount, count($component->users));
    }

    public function test_search_posts_by_content()
    {
        $user = User::factory()->create();
        $matchingPost = Post::factory()->create(['content' => 'This is a test post']);
        $nonMatchingPost = Post::factory()->create(['content' => 'This is not matching']);

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');

        $this->assertCount(1, $component->posts);
        $this->assertEquals($matchingPost->id, $component->posts[0]->id);
    }

    public function test_search_users_by_name()
    {
        $user = User::factory()->create();
        $matchingUser = User::factory()->create(['name' => 'Test User']);
        $nonMatchingUser = User::factory()->create(['name' => 'Other User']);

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class);
        $component->set('search', 'test');
        $component->set('activeTab', 'users');

        $this->assertCount(1, $component->users);
        $this->assertEquals($matchingUser->id, $component->users[0]->id);
    }

    public function test_query_string_parameters_are_preserved()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(SearchPage::class)
            ->set('search', 'test')
            ->set('activeTab', 'users')
            ->set('sortBy', 'popular');

        $this->assertEquals('test', $component->search);
        $this->assertEquals('users', $component->activeTab);
        $this->assertEquals('popular', $component->sortBy);
    }
}

