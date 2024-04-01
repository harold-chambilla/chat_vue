<template>
    <div class="col-5 px-0">
        <div class="bg-white">

            <div class="bg-gray px-4 py-2 bg-light">
                <p class="h5 mb-0 py-1">Recent</p>
            </div>

            <div class="messages-box">
                <div class="list-group rounded-0">
                    <template v-for="conversation in conversations">
                        <Conversation :conversation="conversation" @conversacionSeleccionada="mostrarMensajes" />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { conversationStore } from '../../stores/counter';
import Conversation from './Conversation.vue'
// import Conversation from "./Conversation";
// import axios from "axios";
// import { useStore } from "vuex";

const mostrarMensajes = (conversacionId) => {
  // Aquí puedes manejar la lógica para mostrar los mensajes de la conversación seleccionada
//   console.log('ID de la conversación seleccionada:', conversacionId);
  
  // Realizar la solicitud para obtener los mensajes asociados a la conversaciónId
  // axios.get(`/conversations/${conversacionId}/messages`).then(response => { ... });
};

// const store = useCounterStore()
const convStore = conversationStore()
const conversations = computed(() => {return convStore.CONVERSATIONS});

onMounted(() => {
    convStore.GET_CONVERSATIONS()
})
// console.log('conver', conversations)
// console.log('ind', store.$state.name)
// let count = computed(() => store.state.count)
// const increment = () => {
//     store.commit('INCREMENT');
// }
// const decrement = () => {
//     store.commit('DECREMENT');
// }
// return {count, increment, decrement}
// console.log('conso', mapGetters['CONVERSATIONS'])

// const getters = store.getters
// const conversations = computed(() => store.getters.conversations);
// console.group('conversacion numero: ', conversations)
// const conversations = computed(() => store.state.conversations.all)
</script>
