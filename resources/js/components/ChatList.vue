<template>
    <div class="flex min-h-screen">
        <div class="w-full p-2 mx-auto justify-center flex shadow-xl">
            <div class="min-w-full border rounded grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
                <div class="border-r border-gray-300 md:col-span-1">
                    <div class="mx-3 my-3 flex items-center space-x-5">
                        <button @click="back()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                            </svg>
                        </button>
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
                                <img class="object-cover w-10 h-10 rounded-full" :src="c.profilePic"
                                    alt="" />
                                <div class="w-full pb-2">
                                    <div class="flex justify-between">
                                        <span class="block ml-2 font-semibold text-gray-600">{{
                                                c.name
                                        }}</span>
                                        <!-- <span
                                            class="block ml-2 text-sm w-5 text-center font-black text-black rounded-full">{{
                                                    c.unreadCount
                                            }}</span> -->
                                    </div>
                                    <!-- <span class="block ml-2 text-sm text-gray-600">Good
                                        Morning</span> -->
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                <Welcome v-show="showWelcome" />

                <Messages ref="msg" v-show="!showWelcome" :selected-chat="selectedChat"
                    :messages="messages" :loading="loading" @send-to-chatList="receivedFromMessages" />
            </div>
        </div>
    </div>
</template>

<script>
import Messages from './Messages.vue';
import Welcome from './Welcome.vue';
export default {
    name: 'ChatList',

    components: {
        Messages,
        Welcome,
    },

    props: {
        user: Object,
        chats: Object,
        messages: Array,
        loading: Boolean
    },

    data() {
        return {
            showWelcome: true,
            selectedChat: '',
        };
    },

    methods: {
        back: function () {
            this.showWelcome = true;

            this.$emit('auth', this.user);
        },

        selectChat: function (chatId) {
            this.showWelcome = false;

            // show messages for each selected chat
            const selected = this.chats.find(x => x.id._serialized === chatId);

            this.selectedChat = selected;

            this.$emit('selectedChat', chatId);
        },

        // emitted event from Messages, intented to be emitted to Home,
        // to send messages to server
        receivedFromMessages: function (id, message) {
            // this.receivedMessages.push(message);
            this.$emit('sendToHome', id, message);
        },
    }
}
</script>