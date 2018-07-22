<template>
  <div class="container-fluid" id="marge">
    <div class="row">     
      <button v-on:click='del(id)' class="close col-lg-1" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>      
      <div class="col-lg-4">{{dateDebut}} - {{dateFin}}</div>
      <div class="col-lg-7">{{description}}</div>    
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {      
    props: ['id', 'dateDebut', 'dateFin', 'description'],
    data() {
        return { 
        	retour: ''
        }
    },
    methods: {
        async del(id) {
            axios.post("http://".concat(window.location.hostname).concat("/symfonyVueJS_v2/public/index.php/delete"), JSON.stringify(id))
                .then(response => {
                	this.retour = "Le rendez-vous a bien été supprimé."
                	this.$emit('completed', [response.data, this.retour])
                })
                .catch(error => {

                })
        }
    } 
       
}

</script>

<style>
  div div button span {
  	color:red;
    width:0.5px;
  }
  #marge {
    padding: 1em 0
  }
</style>