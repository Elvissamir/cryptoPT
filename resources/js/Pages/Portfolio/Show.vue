
<template>
    <layout>
          <!-- CONTENT -->
        <div class="bg-indigo-900 w-12/12 flex text-white">
            <div class="grid grid-cols-4 mx-auto mt-8 w-11/12">
               
               <!-- My portfolio top left -->
               <div class="col-span-2">
                  <div class="flex">
                     <p class="text-lg font-bold mr-1">My Portfolio</p>
                     <p>(Created {{ portfolio.created_at }})</p>
                  </div>
                  <p>Number of cryptos in portfolio: {{ cryptos.length }}</p>
               </div>

               <!-- My portfolio top right -->
               <div class="col-span-2 text-right">
                  <p class="text-lg font-bold">10 000 USD</p>
                  <div class="flex flex-row">
                     <p class="mr-1">Since Yesterday: </p>
                     <p class="mr-1">56%</p>
                     <p class="">+120 USD</p>
                  </div>
               </div>

               <!-- My cryptos -->
               <div class="col-span-4">
                  <!-- Section name & buttons -->
                  <div class="flex mt-2">
                     <p>My Cryptos</p>

                     <div class="ml-2">
                        <button>Add +</button>
                        <button>|</button>
                     </div>
                  </div>

                  <!-- Crypto list -->
                  <div class="">
                     <div v-for="(crypto, index) in cryptos" :key="index" class="flex">
                        <div>
                           <p>{{ crypto.symbol }}</p>
                           <p>{{ crypto.name }}</p>
                        </div>
                     </div>
                  </div>
               </div>

               <!-- Portfolio diversification chart -->
               <div class="col-span-2">
                  <p class="">Portfolio Diversification</p>
               </div>

               <!-- Cryptos performance -->
               <div class="col-span-2">
                  <p class="">Cryptos Performance</p>
               </div>
            </div>
        </div>

    </layout>
</template>

<script>
import Layout from '../../Layouts/AppLayout'
import { Inertia } from '@inertiajs/inertia'
import { ref, toRef } from 'vue'

export default {
  components: {
    Layout,
  },
  props: {
    portfolio: {
       type: Object,
       required: true
    },
    cryptos: {
       type: Array,
       required: true
    }
  },
  setup(props) {
     
  },
  mounted() {

  }
}
</script>