<template>
  <div id="t">
  	<div id="a" class="col-md-1">
  		<button v-on:click='del(id)' class="close" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
  	</div>
    <div id="g" class="col-md-3">{{dateDebut}} - {{dateFin}}</div>
    <div id="d" class="col-md-8">{{description}}</div>
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
            axios.post('http://localhost/symfonyVueJS_v2/public/index.php/delete', JSON.stringify(id))
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
	#t {
		padding: 2em 0
	}

  #g, #a {
  	float:left;
  }

  #d {
  	float:right
  }

  #a button {
  	color:red
  }
</style>