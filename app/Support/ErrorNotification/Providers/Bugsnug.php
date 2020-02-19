<?php

declare(strict_types=1);

namespace App\Support\ErrorNotification\Providers;

use App\Support\ErrorNotification\Contracts\ErrorNotificationProvider;
use Bugsnag;
use Bugsnag\Report;
use Exception;

class Bugsnug implements ErrorNotificationProvider
{
    /**
     * {@inheritdoc}
     */
    public function notifyException(
        Exception $exception,
        array $data = [],
        string $severity = self::SEVERITY_ERROR
    ) : void {
        Bugsnag::notifyException($exception, function (Report $report) use ($severity, $data) : void {
            $this->attachAuthToReport($report);
            $report->setSeverity($severity);
            $report->setMetaData($data);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function notifyError(
        string $name,
        string $description = null,
        array $data = [],
        string $severity = self::SEVERITY_ERROR
    ) : void {
        Bugsnag::notifyError($name, $description, function (Report $report) use ($severity, $data) : void {
            $this->attachAuthToReport($report);
            $report->setSeverity($severity);
            $report->setMetaData($data);
        });
    }

    /**
     * @param \Bugsnag\Report $report
     */
    protected function attachAuthToReport(Report &$report) : void
    {
        if (
            ! \in_array((string) app('request')->segment(1), ['dashboard', 'service'], true)
            && auth(GUARD_FRONT)->check()
        ) {
            /** @var \App\Models\User $user */
            $user = auth(GUARD_FRONT)->user();
            $report->setUser([
                'type'  => 'user',
                'id'    => $user->id,
                'email' => $user->email
            ]);
        }
    }
}
