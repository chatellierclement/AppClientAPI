<template>
	<div class="parent">
		<div class="container-fluid">
	  		<div class="row">
		    	<div class="col-lg-2 collerAGauche">
		    		<navbar></navbar>
		    	</div>
			    <div class="col-lg enfant">           
			        <router-view></router-view>
			    </div>
		    </div>
	    </div>
	</div>
</template>

<script>
import Vue from 'vue'
import Navbar from './Navbar.vue'

export default {
    components: {
    	Navbar
    }    
}

</script>

<style>	
	.parent {
		display: flex;
	}	
	.enfant {
		margin:auto;
	}
	.collerAGauche {
		padding:0
	}		
</style>