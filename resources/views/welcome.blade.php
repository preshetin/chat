<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.5.1/css/bulma.min.css">
  </head>
  <body>

    <div id="app" class="container">

      <section class="section">
        <h1 class="title">
          Chat
        </h1>
      </section>

      <chat></chat>

    </div>



  <script src="{{ asset('js/app.js') }}"></script>

  </body>
</html>
