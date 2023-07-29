<?php

namespace Database\Factories;

use App\Enums\Ticket\TicketPriorityEnums;
use App\Enums\Ticket\TicketStatusEnums;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
		return [
			'title' => fake()->title(),
			'message' => fake()->word(),
			'is_read' => fake()->boolean(),
		];
    }
	
	
	public function setUser(int $userId): static
	{
		return $this->state(fn() => [
			'user_id' => $userId
		]);
	}
	public function setStatus(TicketStatusEnums $status): static
	{
		return $this->state(fn() => [
			'status' => $status
		]);
	}
	
	public function setPriority(TicketPriorityEnums $priority): static
	{
		return $this->state(fn() => [
			'priority' => $priority
		]);
	}
}
