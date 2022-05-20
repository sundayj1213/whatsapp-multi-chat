const WhatsAppClient = require('./whatsapp_client');
const app = require('express')();
const express = require('express');
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
    cors: { origin: "*" }
});
const process = require('dotenv').config().parsed;
const fileUpload = require('express-fileupload');
const initWhatsApp = async (userId, tries, io) => {
    const client = new WhatsAppClient(userId, io);
    // create api
    const api = await client.setUpApi(tries);

    if(!api) return;

    // on qr
    api.client.on('qr', api.onQr);
    // on ready
    api.client.on('ready', api.onReady);
    // on message
    api.client.on('message', api.onMessage);
    // on message
    api.client.on('message_ack', api.onMessageAck);
    // on media uploaded
    api.client.on('media_uploaded', api.onMediaUploaded);
    // authenticated
    api.client.on('authenticated', api.onAuthenticated);
    // authenticated failed
    api.client.on('auth_failure', api.onAuthFailure);

    // init
    await api.initialize();

    // sendMessage
    socket.on('blockUser', api.blockUser);
    // sendMessage
    socket.on('sendMessage', api.sendMessage);
    // fetch Messages
    socket.on('fetchMessage', api.fetchMessage);
    // close
    socket.on('disconnect', api.onDestroy);
};

app.use(express.json());
app.use(express.urlencoded({
    extended: true
}));
app.use(fileUpload({
    debug: true
}));


io.on('connection', (socket) => {
    socket.on('init', async ({ userId, tries } = data) => {
        initWhatsApp(userId, tries, io);
    });
});

server.listen(process.SOCKETIO_PORT, () => {
    console.log('Server is running on port:', process.SOCKETIO_PORT);
});