<template>
    <div v-show="!authenticated">
        <ModelsList ref="model" :users="users" :disconnected="disconnected" @navigate="navigate" />
    </div>

    <div v-show="authenticated">
        <ChatList ref="chat" :chats="chats" :user="user" :messages="messages"
            :loading="loading"
            @send-to-home="receivedFromChatList" @selected-chat="selectedChat"
            @auth="setAuthentication" />
    </div>
</template>

<script>
import ChatList from '@/components/ChatList.vue';
import ModelsList from '@/components/ModelsList.vue'
import { defineComponent } from '@vue/runtime-core';

// instantiate connection
const socket = window.SocketIO;

export default defineComponent({
    components: { ModelsList, ChatList },

    props: ['users'],

    data() {
        return {
            tries: 0,
            authenticated: false,
            disconnected: false,
            loading: true,
            chats: [],
            user: {},
            messages: [],
        };
    },

    created() {
        // populate messages
        socket.on('listMessages', (messages) => {
            this.messages = messages;
            this.loading = false;
        });

        // listen to message
        socket.on('message', (message) => {
            if(message.type == "e2e_notification") return;

            let index = this.messages.findIndex(x => x.chat.id._serialized === message.chat.id._serialized);

            if(index != -1) {
                this.messages.push(message);
            }

            this.updateChat(message);
        });

        // listen to message_ack
        socket.on('message_ack', (message) => {
            console.log(message);
            if (message.ack === 1) this.messages.push(message);

            let index = this.messages.findIndex(x => x.id._serialized === message.id._serialized);

            if(index != - 1) {
                this.messages[index] = message;
            }

            this.updateChat(message);
        });

        // listen to media upload
        socket.on('media_uploaded', (message) => {
            console.log('Media Uploaded: ' , message);
        });

        // listen destory
        socket.on('destroyed', (data) => {
            this.disconnected = true;
        });

        // on dsiconnected
        socket.on("connect_error", (err) => {
            this.disconnected = true;
            this.authenticated = false;
            this.user = {};
        });

        // on dsiconnected
        socket.on("connect", (err) => {
            this.disconnected = false;
        });
    },

    methods: {
        updateChat(message) {

            let index = this.chats.findIndex(x => x.id._serialized === message.chat.id._serialized);

            if(index == -1) {
                this.chats.unshift(message.chat);
            } else {
                this.chats[index] = message.chat;
            }
        },

        setAuthentication: function (user) {
            this.user = user;
            this.authenticated = false;

            // show message on home page
            this.$refs.model.$refs.msg.classList.remove('hidden');
            this.$refs.model.$refs.qr.classList.add('hidden');
            this.$refs.model.$refs.waitMsg.classList.add('hidden');
            this.$refs.model.$refs.success.classList.add('hidden');
            this.$refs.model.$refs.failed.classList.add('hidden');
        },

        navigate: function (user) {
            // remove message on home page and show barcode
            this.$refs.model.$refs.msg.classList.add('hidden');
            this.$refs.model.$refs.waitMsg.classList.remove('hidden');
            this.$refs.model.$refs.qr.classList.add('hidden');
            this.$refs.model.$refs.failed.classList.add('hidden');
            this.$refs.model.$refs.success.classList.add('hidden');


            // instantiate connection
            if (this.authenticated === false) {

                if (this.user.uuid !== user.uuid) {
                    this.tries = 0;
                }

                this.disconnected = false;
                this.user = user;

                // update tries
                this.tries++;

                if (this.user.uuid) {
                    socket.emit('destroy')
                }

                socket.emit('init', {
                    userId: user.uuid,
                    tries: this.tries
                });

                // listent to qr
                socket.on('qr', (qr) => {
                    this.disconnected = false;
                    this.tries = 0;
                    this.user = {};
                    if (this.authenticated) return;

                    window.QRCode.toCanvas(this.$refs.model.$refs.qr, qr, function (error) {
                        if (error) {
                            alert("Failed to render QR");
                            return console.error(error);
                        }
                    });

                    this.$refs.model.$refs.waitMsg.classList.add('hidden');
                    this.$refs.model.$refs.failed.classList.add('hidden');
                    this.$refs.model.$refs.qr.classList.remove('hidden');
                });

                socket.on('authenticated', () => {
                    this.$refs.model.$refs.success.classList.remove('hidden');
                    this.$refs.model.$refs.qr.classList.add('hidden');
                    this.$refs.model.$refs.waitMsg.classList.add('hidden');
                    this.$refs.model.$refs.failed.classList.add('hidden');
                });

                socket.on('auth_failure', () => {
                    this.$refs.model.$refs.success.classList.add('hidden');
                    this.$refs.model.$refs.qr.classList.add('hidden');
                    this.$refs.model.$refs.waitMsg.classList.add('hidden');
                    this.$refs.model.$refs.failed.classList.remove('hidden');

                    this.authenticated = false;
                });

                // update dom
                socket.on('ready', (chats) => {
                    this.authenticated = true;
                    this.chats = chats;
                    this.tries = 0;
                });
            }
        },

        // emitted event from ChatList to get selected chat
        selectedChat: function (id) {
            // get the selected chat id
            socket.emit('fetchMessage', { chatId: id });
            this.loading = true;
        },

        // emitted event from ChatList, coming from Messages to send messages to server
        receivedFromChatList: function (message) {
            // check if message is a file
            if (message.file instanceof File) {
                socket.emit('sendMessage', {
                    chatId: message.id,
                    mimetype: message.file.type,
                    file: message.file,
                    caption: message.caption
                });
            } else {
                socket.emit('sendMessage', {
                    chatId: message.id,
                    data: message.text
                })
            }
        },

        blockUser: () => {
            socket.emit('blockUser', {
                chatId: message.id,
            });
        }
    },

})
</script>