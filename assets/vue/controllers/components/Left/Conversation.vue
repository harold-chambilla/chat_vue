<template>
    <router-link :to="{name: 'conversation', params: {id: conversation.conversationId}}" class="list-group-item list-group-item-action rounded-0">
        <div class="media">
            <img src="https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg" alt="user" width="50" class="rounded-circle">
            <div class="media-body ml-4">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <h6 class="mb-0">{{ conversation.email }}</h6><small class="small font-weight-bold">{{ date }}</small>
                </div>
                <p class="font-italic mb-0 text-small">{{ conversation.content }}</p>
            </div>
        </div>
    </router-link>
</template>

<script setup>
import { computed } from 'vue';
const { conversation } = defineProps(['conversation']);
// console.log(conversation);


const date = computed(() => (new Date(conversation.created_at).toLocaleTimeString()));

// console.log('date', conversation.created_at)
</script>