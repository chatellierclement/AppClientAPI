<template>
	<div class="planningJournalier">
		<div class="container-fluid">
	  		<div class="row">
	  			<div class="col-md-12">
	  				Planning du jour	
	  			</div>
		    	<div class="col-md-12">
		    		<test-bis></test-bis>
		    	</div>
		    </div>
	    </div>
	</div>
</template>

<script>
import Vue from 'vue'
import TestBis from './TestBis.vue'

export default {
    components: {
    	TestBis
    }    
}

</script>

<style>		
	.planningJournalier  {	
		/*fonctionne pas sur tel*/	
	    margin-top:-25%;
	    transform: translateY(25%);
	}	
</style>