<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use App\Notifications\NewEmployeeAdded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@grtech.com')->first();
    }

    public function test_notification_is_sent_when_employee_is_created(): void
    {
        Notification::fake();

        $company = Company::factory()->create([
            'email' => 'company@example.com'
        ]);

        $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'Alip',
                'last_name' => 'Hamjah',
                'company_id' => $company->id,
                'email' => 'alip@example.com',
            ]);

        Notification::assertSentTo(
            $company,
            NewEmployeeAdded::class
        );
    }

    public function test_notification_is_not_sent_if_company_has_no_email(): void
    {
        Notification::fake();

        $company = Company::factory()->create([
            'email' => null
        ]);

        $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'Alip',
                'last_name' => 'Hamjah',
                'company_id' => $company->id,
            ]);

        Notification::assertNothingSent();
    }

    public function test_notification_contains_employee_details(): void
    {
        Notification::fake();

        $company = Company::factory()->create([
            'email' => 'company@example.com'
        ]);

        $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'Alip',
                'last_name' => 'Hamjah',
                'company_id' => $company->id,
                'email' => 'alip@example.com',
                'phone' => '1234567890',
            ]);

        Notification::assertSentTo($company, NewEmployeeAdded::class,
            function ($notification, $channels) {
                return $notification->employee->first_name === 'Alip' &&
                       $notification->employee->last_name === 'Hamjah';
            }
        );
    }

    public function test_notification_uses_mail_channel(): void
    {
        Notification::fake();

        $company = Company::factory()->create([
            'email' => 'company@example.com'
        ]);

        $this->actingAs($this->admin)
            ->post(route('employees.store'), [
                'first_name' => 'Alip',
                'last_name' => 'Hamjah',
                'company_id' => $company->id,
            ]);

        Notification::assertSentTo($company, NewEmployeeAdded::class,
            function ($notification, $channels) {
                return in_array('mail', $channels);
            }
        );
    }
}
