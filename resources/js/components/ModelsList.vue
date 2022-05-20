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
                        <h2 class="py-2 pb-2 pl-2 text-lg text-gray-600 border-b border-gray-300">Accounts</h2>
                        <li v-for="(u, index) in users" :key="index">
                            <a @click="$emit('navigate', u)" class="flex items-center px-3 py-2 text-sm transition duration-150
                                ease-in-out border-b border-gray-300 cursor-pointer
                                hover:bg-gray-100 focus:outline-none">
                                <img class="object-cover w-10 h-10 rounded-full"
                                    src="/img/avatar.png"
                                    alt="username" />
                                <div class="w-full">
                                    <span class="ml-5 font-semibold text-gray-600">{{ u.name
                                    }}</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <div class="w-full">
                        <div
                            class="relative w-full p-6 overflow-y-auto h-[32rem] items-center flex flex-col justify-center">
                            <div class="relative w-64">
                                <canvas class="hidden bg-gray-600 w-64 h-64 rounded-lg relative"
                                    ref="qr"></canvas>
                            </div>
                            <template v-if="disconnected">
                                <h3 class="animate-bounce font-bold text-lg">
                                    Socket disconnected, please click on model to reconnect and continue...
                                </h3>
                            </template>
                            <template v-else>
                                <!-- display when user is authenticated/unauthenticated -->
                                <h3 ref="msg" class="font-bold text-lg">
                                    Click on profile to proceed...
                                </h3>

                                <!-- display when checking authentication status -->
                                <h3 ref="waitMsg" class="hidden animate-bounce font-bold text-lg">
                                    Please wait while we try to authenticate...
                                </h3>

                                <!-- display when authenticated succesfully -->
                                <h3 ref="success" class="hidden animate-bounce font-bold text-lg">
                                    Authenticated successfully, loading chats...
                                </h3>

                                <h3 ref="failed" class="hidden animate-bounce font-bold text-lg">
                                    Authentication failed, please click on chat to try again...
                                </h3>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'ModelsList',

    props: ['users', 'disconnected'],
}
</script>