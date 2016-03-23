<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Request;

class ExampleTest extends TestCase
{
    /**
     * Lets try first very basic unit test
     */
    public function testHome()
    {
        $this->visit('/')
            ->click('Home')
            ->seePageIs('/');
    }

    /*
     * Testing simple blog API using JWT : Retrieve all blog
     * */
    public function testGetAllBlog()
    {
        $user = \App\User::first();
        if (!empty($user)) {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
            $response = $this->call('GET', '/api/get_all_blog', ['token' => $token], [], [],
                ['HTTP_Authorization' => 'Bearer ' . $token], []
            );
            //Request should be accepted
            $this->assertEquals(200, $response->status());
            // return dump all data
            var_dump($response);
        }
    }

    /*
     * Testing simple blog API using JWT : Add new Blog
     * */
    public function testCreateBlog()
    {
        $user = \App\User::first();
        if (!empty($user)) {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
            $response = $this->call('POST', '/api/create', [
                'token' => $token,
                'user_id' => $user->id,
                'title' => str_shuffle('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla malesuada, nunc id placerat placerat.'),
                'content' => str_shuffle('Nullam sed ex sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla sodales, ligula et pellentesque commodo, quam metus tincidunt nibh, quis pharetra odio felis at elit.'),
            ], [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []
            );
            //Request should be accepted
            $this->assertEquals(200, $response->status());
            var_dump($response);
        }
    }

    /*
     * Testing simple blog API using JWT : Edit new Blog
     * */
    public function testEditBlog()
    {
        $user = \App\User::first();
        if (!empty($user)) {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
            $blog = \App\Blog::where('user_id', '=', $user->id)->first();
            if (!empty($blog)) {
                $response = $this->call('POST', '/api/edit/' . $blog->id, [
                    'token' => $token,
                    'user_id' => $user->id,
                    'title' => 'Edited - ' . str_shuffle('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla malesuada, nunc id placerat placerat.'),
                    'content' => str_shuffle('Nullam sed ex sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla sodales, ligula et pellentesque commodo, quam metus tincidunt nibh, quis pharetra odio felis at elit.'),
                ], [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []
                );
                //Request should be accepted
                $this->assertEquals(200, $response->status());
                var_dump($response);
            }
        }
    }

    /*
     * Testing simple blog API using JWT : Edit new Blog
     * */
    public function testDeleteBlog()
    {
        $user = \App\User::first();
        if (!empty($user)) {
            $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
            $blog = \App\Blog::where('user_id', '=', $user->id)->first();
            if (!empty($blog)) {
                $response = $this->call('POST', '/api/delete/' . $blog->id, [
                    'token' => $token,
                ], [], [], ['HTTP_Authorization' => 'Bearer ' . $token], []
                );
                //Request should be accepted
                $this->assertEquals(200, $response->status());
                var_dump($response);
            }
        }
    }

    /* CRUD unit testing for non api*/
    public function testAdminBlogIndex()
    {
        $user = \App\User::first();
        if (!empty($user)) {
            $this->withoutMiddleware();
            $this->call('GET', '/admin/blogs', ['user_id' => $user->id]);
            $this->assertResponseOk();
            $this->assertViewHas('posts');
        }
    }

    public function testAdminBlogCreate()
    {
        $user = \App\User::first();
        if (!empty($user)) {
            $this->withoutMiddleware();
            $this->call('POST', '/admin/blogs', [
                'user_id' => $user->id,
                'title' => 'Form - ' . str_shuffle('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla malesuada, nunc id placerat placerat.'),
                'content' => str_shuffle('Nullam sed ex sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla sodales, ligula et pellentesque commodo, quam metus tincidunt nibh, quis pharetra odio felis at elit.'),
            ]);
            $this->assertRedirectedTo('/admin/blogs?user_id=' . $user->id);
        }
    }

    public function testAdminBlogEdit()
    {
        $user = \App\User::first();
        if (!empty($user)) {
            $this->withoutMiddleware();
            $blog = \App\Blog::where('user_id', '=', $user->id)->first();
            if (!empty($blog)) {
                $this->call('POST', '/admin/blogs/' . $blog->id, [
                    '_method' => 'PATCH',
                    'user_id' => $user->id,
                    'title' => 'Form - ' . str_shuffle('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla malesuada, nunc id placerat placerat.'),
                    'content' => str_shuffle('Nullam sed ex sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla sodales, ligula et pellentesque commodo, quam metus tincidunt nibh, quis pharetra odio felis at elit.'),
                ]);
                $this->assertRedirectedTo('/admin/blogs?user_id=' . $user->id);
            }
        }
    }

    public function testAdminBlogDelete()
    {
        $user = \App\User::first();
        if (!empty($user)) {
            $this->withoutMiddleware();
            $blog = \App\Blog::where('user_id', '=', $user->id)->first();
            if (!empty($blog)) {
                $this->call('POST', '/admin/blogs/' . $blog->id, [
                    '_method' => 'DELETE',
                    'user_id' => $user->id,
                ]);
                $this->assertRedirectedTo('/admin/blogs?user_id=' . $user->id);
            }
        }
    }

    /*Testing Comments Form*/
    public function testSubmitCommentToBlog()
    {
        $user = \App\User::first();
        if (!empty($user)) {
            $blog = \App\Blog::where('user_id', '=', $user->id)->first();
            if (!empty($blog)) {
                $this->withoutMiddleware();
                $this->call('POST', '/comments', [
                    'blog_id' => $blog->id,
                    'user_id' => $user->id,
                    'content' => 'From Unit Test - ' . str_shuffle('Nullam sed ex sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla sodales.'),
                ]);
                $this->assertRedirectedTo('/contents/view/' . $blog->slug);
            }
        }
    }
}
