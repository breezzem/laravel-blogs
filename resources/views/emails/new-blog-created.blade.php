<!DOCTYPE html>
<html>
<head>
    <title>New Blog Created</title>
</head>
<body>
    <h1>New Blog Created</h1>
    <p>Hello {{ $blog->owner->name }},</p>
    <p>Your blog "{{ $blog->name }}" has been created successfully.</p>
    <p>Domain: {{ $blog->domain }}</p>
</body>
</html>
