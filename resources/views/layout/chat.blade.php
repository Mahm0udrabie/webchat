<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="jumbotron text-center">
        <h1>Login Page</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div id="chat" style="border:solid 1px black; max-height:300px; overflow-y:scroll">
                </div>
                <div class="form-group">
                    <textarea id="message" name="message" class="form-control"
                        placeholder="Enter your Message"></textarea>
                    <button id="send" class="btn btn-primary" type="button">Send</button>
                </div>
            </div>

            <div class="col-md-4">
                @foreach ($users as $user)
                    <ul class="list-group">
                        <li class="list-group-item">
                            <input type="radio" name="user_id" onchange="setUser(this)" value="{{ $user->id }}">
                            {{ $user->name }}
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
        connectToWS();
        var to_user = 0;
        $('#send').on('click', function() {
            let message = $('#message').val();
            if (message.length < 5 || to_user == 0) {
                alert("message must be more than 5 char");
                // return;
            }
            $.ajax({
                url: '{{ url('/api/messages/add') }}',
                type: "post",
                data: {
                    message: message,
                    to_user: to_user
                },
                dataType: 'json',
                headers: {
                    "Authorization": "Bearer {{ auth()->user()->api_token }}"
                },
                success: function(data) {
                    console.log(data);
                    if (data.status == true) {
                        $("#chat").append(
                        "<div class='alert alert-danger'>" +data.data.message + "</div>");
                        $("#message").val("");
                    } else {
                        alert('error when send message');
                    }
                }
            })
        });

        function setUser(e) {
            to_user = e.value;
            readChat();
        }

        function connectToWS() {
            // Create WebSocket connection.
            const socket = new WebSocket('ws://localhost:3000/messages/conn?channel={{ auth()->user()->channel}}');

            // Connection opened
            socket.addEventListener('open', function(event) {
                // socket.send('Hello Server!');
                console.log("connection openned");
            });
            // Listen for messages
            socket.addEventListener('message', function(event) {
                console.log('Message from server ', event.data);
                var socket_message = JSON.parse(event.data);
                if(socket_message.type==="message") {
                    var message_data = JSON.parse(socket_message.data);
                    $("#chat").append(`
                        <div class='alert alert-primary'>
                            ${message_data.from_user.name} : ${message_data.message}
                        </div>
                    `);
                }

            });
            socket.addEventListener('close', function(event) {
                console.log('Connection closed');
            });
        }

        function readChat() {
            $.ajax({
                url: '{{ url('/api/messages/users') }}/'+to_user,
                type: "GET",
                dataType: 'json',
                headers: {
                    "Authorization": "Bearer {{ auth()->user()->api_token }}"
                },
                success: function(data) {
                    data.data.forEach(message => {
                        if (parseInt(to_user) === message.from_user.id) {
                            document.getElementById('chat').innerHTML = "";
                            $("#chat").append(`
                        ;  <div class='alert alert-danger'>
                            ${message.from_user.name} : ${message.message}
                           </div>
                          `)
                        } else {
                            $("#chat").append(`
                          <div class='alert alert-primary'>
                            ${message.from_user.name} : ${message.message}
                           </div>
                          `);
                        }
                    });
                }
            });
        }

    </script>
</body>

</html>
