<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ImageApiService;
use App\Services\Contracts\ImageApiService as IImageApiService;
use Illuminate\Support\Facades\Queue;
use App\Entities\User;
use App\Jobs\ImageJob;
use App\Values\Image;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ImageProcessedNotification;
use App\Notifications\ImageProcessingFailedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
	
    public function test_running_job()
    {
        Queue::fake();

		$user = factory(User::class)->make();
		$imageId = "image1";

        $response = $this->actingAs($user)->json("POST", "/api/images/${imageId}", [
			"src" => "image data",
			"filter" => "filter"
        ]);

		$response->assertStatus(200);

        Queue::assertPushed(ImageJob::class);
    }

	public function test_handle_api_job()
	{
		Notification::fake();

		$user = factory(User::class)->create();
		$image = new Image('image1', 'image data');
		$filter = 'filter';

		$this->mock(ImageApiService::class, function ($mock) use ($image, $filter) {
			$mock->shouldReceive('applyFilter')->with($image->getSrc(), $filter)->once()->andReturn($image->getSrc() . ' updated');
		});

		$response = $this->actingAs($user)->json("POST", "/api/images/{$image->getId()}", [
			"src" => "image data",
			"filter" => "filter"
		]);
		
		$response->assertStatus(200);

		Notification::assertSentTo(
            $user,
            ImageProcessedNotification::class,
            function ($notification, $channels) use ($image, $user) {
                $broadcastData = $notification->toBroadcast($user)->data;

                $this->assertEquals("success", $broadcastData["status"]);
                $this->assertEquals($image->getId(), $broadcastData["image"]["id"]);
                $this->assertEquals($image->getSrc() . ' updated', $broadcastData["image"]["src"]);

                return true;
            }
        );
	}

	public function test_job_failed()
	{
		Notification::fake();

		$user = factory(User::class)->create();
		$image = new Image('image1', 'image_data');
		$filter = 'filter';

		$this->mock(ImageApiService::class, function ($mock) use ($image, $filter) {
			$mock->shouldReceive('applyFilter')->andThrow(new \Exception('Applying filter failed'));
		});

		$response = $this->actingAs($user)->json("POST", "/api/images/{$image->getId()}", [
			"src" => $image->getSrc(),
			"filter" => "filter"
		]);
		
		Notification::assertSentTo(
            $user,
            ImageProcessingFailedNotification::class,
            function ($notification, $channels) use ($image, $user, $filter) {
                $mailData = (string) $notification->toMail($user)->render();

                $this->assertStringContainsString("Dear {$user->name},", $mailData);
                $this->assertStringContainsString("The applying the filter \"${filter}\" was failed to the image:", $mailData);
				$this->assertStringContainsString("<a href=\"{$image->getSrc()}\"", $mailData);
				$this->assertStringContainsString("<img src=\"{$image->getSrc()}\" alt=\"Image\"", $mailData);
                $this->assertStringContainsString("Best regards,", $mailData);
                $this->assertStringContainsString("Binary Studio Academy", $mailData);

                $broadcastData = $notification->toBroadcast($user)->data;

                $this->assertEquals("failed", $broadcastData["status"]);
                $this->assertEquals("Applying filter failed", $broadcastData["message"]);
                $this->assertEquals($image->getId(), $broadcastData["image"]["id"]);
                $this->assertEquals($image->getSrc(), $broadcastData["image"]["src"]);

                return true;
            }
        );
	}
}
