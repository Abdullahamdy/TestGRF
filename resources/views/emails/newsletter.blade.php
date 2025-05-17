<!DOCTYPE html>
<html>
<head>
    <title>{{ $subjectText ?? 'Newsletter' }}</title>
</head>
<body>
    <p>{!! nl2br(e($messageContent)) !!}</p>
</body>
</html>
