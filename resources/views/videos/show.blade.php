<!DOCTYPE html>
	<html lang="fr">
		<head>
		    <meta charset="UTF-8">

		    <meta
			name="viewport"
			content="width=device-width, initial-scale=1.0"
		    >

		    <title>{{ $video->title }}</title>
		</head>

		<body>

		    <h1>{{ $video->title }}</h1>

		    <video
			controls
			width="400"
		    >
			<source
			    src="{{ $video->url }}"
			    type="video/mp4"
			>
		    </video>

		</body>
</html>
