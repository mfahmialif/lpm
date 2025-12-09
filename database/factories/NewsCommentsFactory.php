<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\NewsComments;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsCommentsFactory extends Factory
{
    protected $model = NewsComments::class;

    public function definition()
    {
        return [
            // biar aman di PHP 7, langsung pakai factory (akan auto-bikin News jika belum ada)
            'news_id'           => 1,
            'parent_comment_id' => null,
            'name'              => $this->faker->name,
            'email'             => $this->faker->safeEmail,
            'content'           => $this->faker->paragraphs(mt_rand(1, 3), true),
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }

    /**
     * State untuk membuat reply (balasan) dari parent tertentu.
     *
     * @param  \App\Models\NewsComments  $parent
     * @return static
     */
    public function reply(NewsComments $parent)
    {
        return $this->state(function () use ($parent) {
            return [
                'news_id'           => $parent->news_id,
                'parent_comment_id' => $parent->id,
            ];
        });
    }
}
