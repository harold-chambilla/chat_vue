<template>
    <div class="col-7 px-0">
      
        <div class="px-4 py-5 chat-box bg-white" ref="messagesBodyRef">
          <template v-for="message in messages">
                <Message :messages="message"/>
            </template>
        </div>

        <Input />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from "vue";
import { conversationStore } from '../../stores/counter';
import Input from "./Input";
import Message from "./Message"
import { useRoute } from "vue-router"
import axios from "axios";

const route = useRoute();
const messStore = conversationStore();
const messagesBodyRef = ref(null);
// const conversationId = ref(null);

onMounted(() => {
  // Accede al parámetro "id" de la ruta
//   conversationId.value = route.params.id;
  // console.log('ID de la conversación:', route.params.id);
});

const messages = computed(() => {
  if(route.params.id !== null){
    return messStore.MESSAGES
  }
})

onMounted(() => {
  messStore.GET_MESSAGES(route.params.id).then(() => {
    scrollToBottom();
  })
})
// console.log('Mensajess', messages)

watch(() => messStore.MESSAGES, async () => {
  await nextTick()
  scrollToBottom()
})

const scrollToBottom = () => {
  messagesBodyRef.value.scrollTop = messagesBodyRef.value.scrollHeight;
}

// onMounted(() => {
//   scrollToBottom();
// })

</script>