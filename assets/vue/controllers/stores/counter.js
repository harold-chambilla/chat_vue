import axios from "axios";
import { defineStore } from "pinia";
// export const useAlertsStore = defineStore('alerts', {

// })

// export const useCounterStore = defineStore('counter', {
//   state: () => ({ count: 0, name: 'Eduardo' })
// })

export const conversationStore = defineStore('conversation', {
    state: () => ({
        conversations: [],
        messages: [],
        // content: '',
        newMessage: '',
        productos: [],
    }),
    getters: {
        CONVERSATIONS(state){ return state.conversations },
        MESSAGES(state, conversationId){
            return state.messages.filter(i => i.conversationId === conversationId)
        },
        PRODUCTOS(state) {return state.productos},
    },
    actions: {
        async GET_CONVERSATIONS() {
            try {
              const data = await axios.get('/conversation')
                this.conversations = data.data
              }
              catch (error) {
                alert(error)
                console.log(error)
            }
        },
        async GET_PRODUCTOS() {
          try {
            const data = await axios.get('https://dummyjson.com/products')
              this.productos = data.data
              // console.log("Contenido", this.productos)
          } catch (error) {
            console.log(error)
          }
        },
        async GET_MESSAGES(conversationId) {
            try {
              const data = await axios.get(`/message/${conversationId}`)
                this.messages = data.data
            } catch (error) {
              alert(error)
              console.log(error)
            }
        },
        async POST_MESSAGE({conversationId, content}) {  
            try {
              console.log('content: ', content)
              console.log('id Conversacion: ', conversationId)
              let formData = new FormData();
              formData.append('content', content)
              // console.log('counter. Enviando mensaje desde la store...')
              // console.log('counter. Conversation id: ', conversationId)
              // console.log('counter. content: ', content)
              console.time('SendMessageStore')
              console.log('dadada', formData);
              const data = await axios.post(`/message/${conversationId}`, formData)
              // const conversation = this.conversations.find(conv => conv.conversationId === conversationId);
              console.log('data', data)
              console.time('SendMessageStore')

              //   conversation.content = payload.content;
              //   conversation.created_at = payload.created_at;
              await this.GET_CONVERSATIONS();
              // await this.GET_MESSAGES(conversationId);
                // this.newMessage = data.data;      
              // console.log('counter. Mensaje enviadisimo', data.data)
              // console.log('counter. FormData', formData)
              // this.newMessage = '';
            } catch (error) {
              alert(error)
              // console.log('KSPERU', error.response.data)
              console.log(error)
            }
        },
      //   async POST_MESSAGE({conversationId, content}) {  
      //     try {
      //       console.log('counter. Enviando mensaje desde la store...')
      //       console.log('counter. Conversation id: ', conversationId)
      //       console.log('counter. content: ', content)
      //       const data = await axios.post(`/message/${conversationId}`, content)
      //          this.messages = data.data;      
      //       console.log('counter. Mensaje enviadisimo', data.data)
      //       // this.newMessage = '';
      //     } catch (error) {
      //       alert(error)
      //       console.log('counter. Mensaje de error POST', error)
      //     }
      // },
    }
})

