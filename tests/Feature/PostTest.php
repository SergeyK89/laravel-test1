<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_blog_post_when_db_empty(): void
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No post found!');
    }

    public function test_see_blogpost_1_when_there_is_1_with_no_comments(): void
    {
        $post = $this->createDummyBlogPost();

        $response = $this->get('/posts');

        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet.');

        $this->assertDatabaseHas(
            'blog_posts',
            ['title' => 'New title']
        );
    }

    public function test_see_1_blog_post_with_comments()
    {
        $post = $this->createDummyBlogPost();
        Comment::factory()->count(4)->create([
            'blog_post_id' => $post->id
        ]);

        $response = $this->get('/posts');

        $response->assertSeeText('4 comments');
    }

    public function test_store_valid(): void
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters'
        ];

        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'The blog post was created!');
    }

    public function test_store_invalid(): void
    {
        $params = [
            'title' => 'a',
            'content' => 'a'
        ];

        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $message = session('errors')->getMessages();
        $this->assertEquals($message['title'][0], 'The title field must be at least 5 characters.');
        $this->assertEquals($message['content'][0], 'The content field must be at least 10 characters.');
    }

    public function test_update_valid()
    {
        $post = $this->createDummyBlogPost();

        $this->assertDatabaseHas(
            'blog_posts',
            [
                'title' => 'New title',
                'content' => 'Content of the blog post'
            ]
        );

        $params = [
            'title' => 'A new named title',
            'content' => 'Content was changed'
        ];

        $this->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was updated!');
        $this->assertDatabaseMissing(
            'blog_posts',
            [
                'title' => 'New title',
                'content' => 'Content of the blog post'
            ]
        );
    }

    public function test_delete()
    {
        $post = $this->createDummyBlogPost();

        $this->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blogpost was deleted');
    }

    private function createDummyBlogPost(): BlogPost
    {
        $post = new BlogPost();
        $post->title = 'New title';
        $post->content = 'Content of the blog post';
        $post->save();

        return BlogPost::factory()->state([
            'title' => 'New title',
            'content' => 'Content of the blog post']
        )->create();

        //return $post;
    }
}
