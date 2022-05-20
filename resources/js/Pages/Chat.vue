<template>
    <div class="flex items-center min-h-screen">
        <div class="container mx-auto shadow-xl">
            <div class="min-w-full border rounded grid grid-cols-1 md:grid-cols-3">
                <div class="border-r border-gray-300 md:col-span-1">
                    <div class="mx-3 my-3 flex items-center space-x-5">
                        <Link :href="route('dash', 1)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                            </svg>
                        </Link>
                        <div class="relative text-gray-600 w-full">
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

                    <ul class="overflow-auto h-[40rem]">
                        <h2 class="py-2 pb-2 pl-2 text-lg text-gray-600 border-b">Chats</h2>
                        <li v-for="(c, index) in chats" :key="index">
                            <a @click="selectChat(c.id._serialized)"
                                class="flex items-center px-3 py-2 text-sm transition duration-150 ease-in-out border-b border-gray-300 cursor-pointer hover:bg-gray-100 focus:outline-none">
                                <img class="object-cover w-10 h-10 rounded-full"
                                    src="https://cdn.pixabay.com/photo/2018/01/15/07/51/woman-3083383__340.jpg"
                                    alt="username" />
                                <div class="w-full pb-2">
                                    <div class="flex justify-between">
                                        <span class="block ml-2 font-semibold text-gray-600">{{
                                                c.name
                                        }}</span>
                                        <span
                                            class="block ml-2 text-sm text-gray-600 bg-green-600 font-bold rounded-full">{{
                                                    c.unreadCount
                                            }}</span>
                                    </div>
                                    <span class="block ml-2 text-sm text-gray-600">Good
                                        Morning</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div v-show="selectedChat" class="hidden md:col-span-2 md:block">
                    <div class="w-full">
                        <div class="relative flex items-center p-3 border-b border-gray-300">
                            <img class="object-cover w-10 h-10 rounded-full"
                                src="https://cdn.pixabay.com/photo/2018/01/15/07/51/woman-3083383__340.jpg"
                                alt="username" />
                            <span class="block ml-2 font-bold text-gray-600">{{ selectedChat?.name
                            }}</span>
                            <span class="absolute w-3 h-3 bg-green-600 rounded-full left-10 top-3">
                            </span>
                        </div>
                        <div>
                            <div class="relative w-full p-6 overflow-y-auto h-[40rem]">
                                <ul class="space-y-2">
                                    <li v-for="(received, index) in receivedMessages" :key="index"
                                        class="flex justify-start">
                                        <div
                                            class="relative max-w-xl px-4 py-2 text-gray-700 rounded shadow">
                                            <span class="block">{{ received.body }}</span>
                                        </div>
                                    </li>

                                    <li v-for="(sent, index) in messages" :key="index"
                                        class="flex justify-end">
                                        <div
                                            class="relative max-w-xl px-4 py-2 text-gray-700 bg-gray-100 rounded shadow">
                                            <span class="block">{{ sent }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between w-full p-3 border-t border-gray-300">
                            <div class="relative flex w-10 items-center overflow-hidden">
                                <button class="absolute">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-8 text-gray-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </button>

                                <input type="file" ref="file"
                                    class="cursor-pointer h-full w-full opacity-0" name="">
                            </div>

                            <input type="text" placeholder="Message"
                                class="block w-full py-2 pl-4 mx-3 bg-gray-100 rounded-full outline-none focus:text-gray-700"
                                v-model="msgInput" required />
                            <button type="submit" @click="send(selectedChat?.id._serialized)">
                                <svg class="w-8 text-gray-500 origin-center transform rotate-90"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        Link,
        VueSocketIO
    },

    props: {
        config: Object,
        chats: Object,
    },

    data() {
        return {
            receivedMessages: [],
            // sentMessages: [],
            msgInput: null,
            selectedChat: null,
            messages: []
        };
    },

    methods: {
        selectChat: function (chatId) {
            const socket = window.SocketIO;

            const chat = this.chats.find(x => x.id._serialized === chatId);

            this.selectedChat = chat;

            // emit not working
            socket.emit('fetchMessages', { chatId: chatId })
        }
    }
}
</script>