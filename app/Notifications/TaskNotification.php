<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TaskNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $type;

    public function __construct($task, $type = 'created')
    {
        $this->task = $task;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'message' => "Task '{$this->task->title}' has been {$this->type}.",
            'url' => route('tasks.show', $this->task->id),
        ];
    }
}
