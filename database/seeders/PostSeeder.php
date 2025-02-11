<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maxUser = User::max('id');

        Post::factory(100)
            ->create()
            ->each(function (Post $post) use ($maxUser) {
                $likes = [];
                for ($i = 1; $i <= rand(1, 10); $i++) {
                    $likes[] = new Like(['user_id' => rand(1, $maxUser)]);
                }

                // Create 4 tags and attach them to the post
                $tags = Tag::factory()->count(4)->create();
                $post->tags()->attach($tags->pluck('id')->toArray());
                
                $post->likes()->saveMany($likes);
            });
    }
}
