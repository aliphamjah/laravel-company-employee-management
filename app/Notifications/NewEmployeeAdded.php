<?php

namespace App\Notifications;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeAdded extends Notification
{
    use Queueable;

    public $employee;
    public $company;

    /**
     * Create a new notification instance.
     */
    public function __construct(Employee $employee, Company $company)
    {
        $this->employee = $employee;
        $this->company = $company;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Employee Added to ' . $this->company->name)
            ->greeting('Hello ' . $this->company->name . '!')
            ->line('A new employee has been added to your company.')
            ->line('**Employee Details:**')
            ->line('Name: ' . $this->employee->first_name . ' ' . $this->employee->last_name)
            ->line('Email: ' . ($this->employee->email ?: 'Not provided'))
            ->line('Phone: ' . ($this->employee->phone ?: 'Not provided'))
            ->action('View Employees', url('/employees'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->first_name . ' ' . $this->employee->last_name,
            'company_id' => $this->company->id,
            'company_name' => $this->company->name,
        ];
    }
}
