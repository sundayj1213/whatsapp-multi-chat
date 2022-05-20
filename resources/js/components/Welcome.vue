<template>
    <div v-show="selectedChat" class="bg-slate-500 md:col-span-2 lg:col-span-3 md:block">
        <div class="w-full flex items-center justify-center h-full">
            <h1 class="font-bolder text-white text-7xl uppercase">Welcome</h1>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Welcome',

    props: ['selectedChat']
}
</script>