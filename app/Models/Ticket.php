<?php

namespace App\Models;

use App\Enums\Ticket\TicketPriorityEnums;
use App\Enums\Ticket\TicketStatusEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'title',
		'message',
		'status',
		'is_read',
		'priority',
		'user_id'
	];
	
	protected $casts = [
		'priority' => TicketPriorityEnums::class,
		'status' => TicketStatusEnums::class,
		'is_read' => 'boolean'
	];
	
	
	
	public function user() {
		return $this->belongsTo(User::class);
	}
	
	
	public function ticketReplies() {
		return $this->hasMany(TicketReply::class);
	}
	
	public function assginEmployeese() {
		return $this->belongsToMany(User::class,'user_tickets','ticket_id','user_id');
	}
}
