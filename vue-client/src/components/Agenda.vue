<template>
  <div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
          <calendrier-bis @date="recupererDate"></calendrier-bis>
        </div>
        <div class="col-md-6">
          <test-bis v-bind:date="date"></test-bis>
        </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import CalendrierBis from './CalendrierBis.vue'
import TestBis from './TestBis.vue'

export default {
    components: {
        CalendrierBis,
        TestBis
    },
    data() {
        return { 
          date: ""      
        }
    },
    methods: {        
        recupererDate (reponse) {            
           this.date = reponse
        },
    }
}

</script>

<style>  
</style>