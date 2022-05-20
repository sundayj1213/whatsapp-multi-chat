<template>
    <div class="flex min-h-screen">
        <div class="w-full p-2 mx-auto justify-center flex">
            <div class="w-full border-2 rounded grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
                <div class="border-r border-gray-300 md:col-span-1">
                    <div class="mx-3 my-3">
                        <div class="relative text-gray-600">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                    class="w-6 h-6 text-gray-300">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="search"
                                class="block w-full py-2 pl-10 bg-gray-100 rounded outline-none"
                                name="search" placeholder="Search" required />
                        </div>
                    </div>

                    <ul class="overflow-auto h-[32rem]">
                        <h2 class="my-2 mb-2 ml-2 text-lg text-gray-600">Accounts</h2>
                        <li>
                            <a @click="navigate()"
                                class="flex items-center px-3 py-2 text-sm transition duration-150 ease-in-out border-y border-gray-300 cursor-pointer hover:bg-gray-100 focus:outline-none">
                                <img class="object-cover w-10 h-10 rounded-full"
                                    src="https://cdn.pixabay.com/photo/2018/09/12/12/14/man-3672010__340.jpg"
                                    alt="username" />
                                <div class="w-full">
                                    <span class="ml-5 font-semibold text-gray-600">Jhon
                                        Don</span>
                                </div>
                            </a>
                            <a
                                class="flex items-center px-3 py-2 text-sm transition duration-150 ease-in-out border-b border-gray-300 cursor-pointer hover:bg-gray-100 focus:outline-none">
                                <img class="object-cover w-10 h-10 rounded-full"
                                    src="https://cdn.pixabay.com/photo/2016/06/15/15/25/loudspeaker-1459128__340.png"
                                    alt="username" />
                                <div class="w-full">
                                    <span class="ml-5 font-semibold text-gray-600">Jhon
                                        Don</span>
                                </div>
                            </a>
                            <a
                                class="flex items-center px-3 py-2 text-sm transition duration-150 ease-in-out border-b border-gray-300 cursor-pointer hover:bg-gray-100 focus:outline-none">
                                <img class="object-cover w-10 h-10 rounded-full"
                                    src="https://cdn.pixabay.com/photo/2018/01/15/07/51/woman-3083383__340.jpg"
                                    alt="username" />
                                <div class="w-full">
                                    <span class="ml-5 font-semibold text-gray-600">Jhon
                                        Don</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <div class="w-full">
                        <div
                            class="relative w-full p-6 overflow-y-auto h-[32rem] items-center flex flex-col justify-center">
                            <h3 ref="msg" class="animate-bounce font-bold text-lg">Hold on, trying
                                to connect
                                you......</h3>
                            <div class="relative w-64">
                                <canvas
                                    class="animate-pulse bg-gray-600 w-64 h-64 rounded-lg relative"
                                    ref="qr"></canvas>
                            </div>
                            <h3 ref="cnt" class="hidden animate-bounce font-bold text-lg">
                                Connecting......</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    
    // socket.IO config
    props: ['config'],

    data() {
        return {
            userId: '05edee08-9164-40b3-a3ce-170333b44dda'
        };
    },

    methods: {
        navigate: function () {
            // instantiate connection
            const socket = io(this.config.IP + ':' + this.config.PORT);

            socket.emit('init', {
                userId: '05edee08-9164-40b3-a3ce-170333b44dda'
            });

            // listent to qr
            socket.on('qr', (qr) => {
                window.QRCode.toCanvas(this.$refs.qr, qr, function (error) {
                    if (error) {
                        alert("Failed to render QR");
                        return console.error(error);
                    }
                });
                this.$refs.qr.classList.remove('animate-pulse');
                this.$refs.msg.classList.add('hidden');
                this.$refs.cnt.classList.remove('hidden');
            });

            // update dom
            socket.on('ready', (chats) => {
                console.log('works');
                this.$inertia.get(route('chat', { id: this.userId, chats: chats }));
            });
        }
    },
}
</script>