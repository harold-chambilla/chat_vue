<template>
    <div class="container py-5 px-4">
       <div class="row rounded-lg overflow-hidden shadow">
            <!-- Users box-->
           <Left />
            <!-- Chat Box-->
            <router-view :key="$route.fullPath"></router-view>
        </div>
    </div>
    <tbody>
      
            <tr v-for="dato in productos.products">
                <td>{{ dato.title }}</td>
                <td>{{ dato.price }}</td>
            </tr>
        </tbody>

</template>

<script setup>
    import { onMounted, computed } from "vue";
    import {conversationStore} from "../stores/counter"
    import Left from "./Left/Left";
    import Right from "./Right/Right";

    const prodStore = conversationStore();
    const productos = computed(() => {
        return prodStore.PRODUCTOS
    })
    onMounted(() => {
        prodStore.GET_PRODUCTOS()
    })
</script>