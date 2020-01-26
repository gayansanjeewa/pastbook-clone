<body>

<p>Hey {{ $name }}</p>

<p>Here's your best photos from last year. Enjoy!</p>

@foreach($photos as $photo)
    <img src="{{ $message->embed($photo) }}">
@endforeach

<p>Cheers,</p>
<p>PastBook Team</p>
</body>
