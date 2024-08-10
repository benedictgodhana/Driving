<!DOCTYPE html>
<html>
<head>
    <title>Application Approved</title>
</head>
<body>
    <h1>Your Application Has Been Approved</h1>
    <p>Hello {{ $application->user->name }},</p>
    <p>We are pleased to inform you that your application for
        @if($application->applicationType)
            {{ $application->applicationType->name }}
        @else
            Application Type Not Specified
        @endif
        has been approved.</p>
    <p>Details:</p>
    <ul>
        <li>Institution: {{ $application->institution ? $application->institution->name : 'Institution Not Specified' }}</li>
        <li>Activity: {{ $application->activity ? $application->activity->name : 'Activity Not Specified' }}</li>
        <li>Description: {{ $application->description }}</li>
    </ul>
    <p>Thank you for your application.</p>
    <p>Best regards,</p>
    <p>The Team</p>
</body>
</html>
