<script setup lang="ts">
import Button from "@/components/ui/button/Button.vue";
import Dropdown from '@storage/assets/drop-down.svg';
</script>
<template>
    <div class="h-screen grid grid-rows-[15%_85%]">
            <div class="flex flex-col justify-center items-center bg-white border-b p-4 gap-4">
                <Button class="rounded-full w-20 h-8 text-xl " variant="outline">ITA
                    <span>
                        <Dropdown class="inline-block mb-1"/>
                    </span>
                </Button>
                <h1 class="text-3xl font-bold">COLLEZIONE</h1>
            </div>
            <div class="p-8 ">
                <img src="@storage/assets/collections/1.png" alt="" class="bg-white mb-4 rounded-[40%]">
                <img src="@storage/assets/collections/1.png" alt="" class="bg-white mb-4 rounded-[40%]">
                <img src="@storage/assets/collections/3.png" alt="" class="bg-white mb-4 rounded-[40%]">
            </div>
        </div>
</template>
