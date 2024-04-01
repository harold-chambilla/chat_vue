<template>
    <form action="#" class="bg-light" @submit.prevent="sendMessage">
        <div class="input-group">
            <input type="text" placeholder="Type a message" v-model="content" aria-describedby="button-addon2" class="form-control rounded-0 border-0 py-2 bg-light">
            <div class="input-group-append">
                <button id="button-addon2" type="submit" class="btn btn-link"> <i class="fa fa-paper-plane"></i></button>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { conversationStore } from '../../stores/counter';
import { useRoute } from "vue-router";

const content = ref('');
const route = useRoute();
const convStore = conversationStore();
// const contMessage = ref('');
// const sendMessage = computed(() => {return convStore.CONVERSATIONS});
const sendMessage = async () => {
    // const data = {
    //     content: content.value
    // }
    // console.log('Enviando mensaje...');
    // console.log('conversationId:', route.params.id);
    // console.log('content:', content.value);
    const newMessage = {
        content: content.value,
        mine: true,
        createdAt: new Date(),
    // Otras propiedades del mensaje...
    };
    convStore.messages.push(newMessage);

    // Limpia el contenido después de enviar el mensaje
    // content.value = '';
    await convStore.POST_MESSAGE({
        conversationId: route.params.id, 
        content: content.value
        })
    content.value = '';
    convStore.GET_MESSAGES(route.params.id)
};
onMounted(() => {
        convStore.GET_MESSAGES(route.params.id)
    })
// const sendMessage = () => {
//     console.log('Mensaje Enviado (', route.params.id, '): ', content.value)
//     content.value = ''
// }

</script>