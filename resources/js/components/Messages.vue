<template>
    <div class="md:col-span-2 lg:col-span-3 md:block">
        <div class="flex flex-col h-full">
            <div class="flex flex-1 flex-col">
                <div class="relative flex items-center p-3 border-b border-gray-300">
                    <img class="object-cover w-10 h-10 rounded-full"
                        :src="selectedChat?.profilePic"
                        :alt="selectedChat?.name" />
                    <span class="block ml-2 font-bold text-gray-600">{{ selectedChat?.name }}</span>
                    <!-- <span class="absolute w-3 h-3 bg-green-600 rounded-full left-10 top-3">
                    </span> -->
                </div>

                <div class="flex h-full text-gray-500 items-center justify-center p-2" v-if="loading">
                    Please hold on while we load messages for this chat
                </div>
                <div class="relative h-full w-full p-6" v-if="messages.length&&!loading">
                    <div class="inset-0 absolute overflow-y-auto overflow-x-hidden flex flex-col-reverse">
                        <ul ref="bottom" class="space-y-2 p-4 w-full">
                            <template  v-for="(msg, index) in messages" :key="index">
                                <li
                                    class="flex"
                                    :class="{ 'justify-end': msg.fromMe, 'justify-start': !msg.fromMe }"
                                    v-if="msg && msg.type !== 'document'">

                                    <template v-if="msg.type == 'chat'">
                                        <div class="relative max-w-xl px-4 py-2 rounded shadow"
                                            :class="{ 'text-white bg-cyan-500': msg.fromMe, 'text-gray-700': !msg.fromMe }">
                                            <span class="block">{{ msg.body }}</span>
                                        </div>
                                    </template>
                                    <template v-if="msg.type == 'revoked'">
                                        <div class="relative max-w-xl px-4 py-2 rounded shadow"
                                            :class="{ 'text-white bg-cyan-500': msg.fromMe, 'text-gray-700': !msg.fromMe }">
                                            <span class="block">This message was deleted</span>
                                        </div>
                                    </template>
                                    <template v-else-if="msg.type == 'image'">
                                        <img :src="msg.url" class="w-full" />
                                    </template>
                                    <template v-else-if="msg.type == 'video'">
                                        <video :src="msg.url"></video>
                                    </template>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
                <div class="flex h-full text-gray-500 items-center justify-center p-2" v-else-if="!loading&&!messages.length">
                    No messages in this chat, you can begin by sending one.
                </div>
            </div>

            <div class="p-2 border-t flex items-center" v-if="media != null">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 text-gray-500"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                <div class="text-gray-500 ml-1">
                    <span>{{ media.name }} &middot; {{ getSize(media.size) }}</span>
                </div>
            </div>
            <div
                class="flex flex-2 items-center justify-between w-full p-3 border-t border-gray-300">
                <div class="relative flex w-10 items-center overflow-hidden">
                    <button class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 text-gray-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>

                    <!-- <input type="file" ref="file" class="cursor-pointer h-full w-full opacity-0"
                        name="" accept="image/*,video/*"> -->
                     <input type="file" ref="file" class="cursor-pointer h-full w-full opacity-0"
                        name="" accept="image/*">
                </div>

                <input type="text" placeholder="Message"
                    class="block w-full py-2 pl-4 mx-3 bg-gray-100 rounded-full outline-none focus:text-gray-700"
                    v-model="msgInput" required />
                <button type="submit" @click="send(selectedChat?.id._serialized)">
                    <svg class="w-8 text-gray-500 origin-center transform rotate-90"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Messages',

    props: ['selectedChat', 'messages', 'loading'],

    data() {
        return {
            msgInput: '',
            media: null
        }
    },

    mounted() {
        this.listenFileUpload();
        console.log(this.messages);
    },

    methods: {
        getSize(size) {
            var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
        	i=0;while(size>900){size/=1024;i++;}
            return (Math.round(size*100)/100)+' '+fSExt[i];
        },
        listenFileUpload() {
            document.querySelector('input[type=file]')
            .addEventListener('change', (e) => {
                this.media = this.$refs.file.files[0];
            });
        },
        send: function (id) {
            const file = this.$refs.file.files[0];
            $.ajax({url});

            Echo.listen('\\App\\Events\\SendMess', () => {
                if (this.msgInput.length > 0 && file === undefined) {
                    this.$emit('sendToChatList', {
                        id,
                        text: this.msgInput
                    })
                    this.msgInput = ''
                }

                if (file !== undefined) {
                    this.$emit('sendToChatList', {
                        id,
                        caption: this.msgInput,
                        file
                    })
                    file.value = null
                    this.media = null;
                }
            });

            Echo.listen('does not have token', () => {
                thsi.emit();
                this.emit('blockUser')
            })

        },
    }
}
</script>