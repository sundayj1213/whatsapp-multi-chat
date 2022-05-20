<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Scripts -->
    <script src="https://cdn.socket.io/4.0.1/socket.io.min.js"
        integrity="sha384-LzhRnpGmQP+lOvWruF/lgkcqD+WDVt9fU3H4BWmwP5u5LTmkUGafMcpZKNObVMLU"
        crossorigin="anonymous"></script>
</head>

<body class="font-sans antialiased">
    <div class="flex min-h-full my-32 container mx-auto max-w-4xl rounded-lg bg-white">
        <div class="flex p-10 justify-between w-full">
            <div class="container px-5 py-24 mx-auto flex">
                <div id="chatbox"
                    class="hidden bg-white rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0 relative z-10 shadow-md">
                    <h2 class="text-gray-900 text-lg mb-1 font-medium">Message</h2>
                    <div class="relative mb-4">
                        <label for="name" class="leading-7 text-sm text-gray-600">Name</label>
                        <input type="name" id="name" name="name"
                            class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative mb-4">
                        <textarea id="message" name="message"
                            class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                    </div>
                    <input type="file" id="file" />
                    <button id="send"
                        class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Send</button>
                </div>
            </div>

            <canvas class="bg-gray-400 w-64 h-64 animate-pulse rounded-lg" id="qr"></canvas>
        </div>

    </div>

    <script src="{{ asset('/js/bundle.js') }}"></script>
    <script src="https://cdn.socket.io/4.0.1/socket.io.min.js"
        integrity="sha384-LzhRnpGmQP+lOvWruF/lgkcqD+WDVt9fU3H4BWmwP5u5LTmkUGafMcpZKNObVMLU"
        crossorigin="anonymous"></script>
    <script>
        // socket.IO config
        const config = {!! json_encode(config('socketio')) !!}
        // instantiate connection
        const socket = io(config.IP + ':' + config.PORT);

        socket.emit('init', {
            userId: 'd1a2a642-481d-42c7-8779-03e74844ce17'
        });

        // listen to qr
        socket.on('qr', (qr) => {
            window.QRCode.toCanvas(document.getElementById("qr"), qr, function (error) {
                if (error) {
                    alert("Failed to render QR");
                    return console.error(error);
                }
            });
        });

        // listen to messages
        socket.on('message', (message) => {
            console.log(message);
        });

        // listen to message_ack
        socket.on('message_ack', (message) => {
            console.log(message);
        });

        // listen to media upload
        socket.on('media_uploaded', (message) => {
            console.log(message);
        });

        // listen destory
        socket.on('destroy', (data) => {
            console.log(data);
        });

        // update dom
        socket.on('ready', (data) => {
            console.log(data);
            // alert("Logged In successful!!!");
            document.getElementById('chatbox').classList.remove('hidden')
            document.getElementById('qr').classList.add('hidden')
            // remove the barcode and display
        });

        document.querySelector('#file').addEventListener('change', function() {
            const file = document.querySelector('input[type="file"]').files[0];

            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
                var startIndex = reader.result.indexOf("base64,") + 7;

                socket.emit('sendMessage', {
                    chatId: '2347030935403@c.us',
                    mimetype: file.type,
                    data: reader.result.substr(startIndex)
                })
            };
            reader.onerror = function (error) {
                console.log('Error: ', error);
            };
        });

        document.querySelector('#send').addEventListener('click', function() {
            socket.emit('sendMessage', {
                chatId: '2347030935403@c.us',
                data: document.querySelector('#message').value
            })
        });
    </script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ asset('js/bundle.js') }}"></script>
</body>

</html>