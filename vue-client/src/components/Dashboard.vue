<template>
	<div>
		<div id="nav" class="col-md-2">
			<nav>               
	            <ul>
		            <li><router-link to="/"> Accueil </router-link></li>
		            <li><router-link to="/calendrier"> RDV </router-link></li>
		            <li><router-link to="/modele"> Modèle de courriers </router-link></li>
		        </ul>
	    	</nav>
	    </div>

	    <div id="section" class="col-md-10">
	        <div class="container">            
	            <router-view></router-view>
	        </div>
	    </div>
    </div>
</template>

<style>
	li {
		list-style-type:none;
	}

	a {
		text-decoration: none;
		color:black
	}

	a:hover {
		text-decoration: none;
		color:black
	}

	#nav {
		float:left;
		height: 100vh;
    	box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
	}

	#section {
		float:right;
		margin:auto;
	}
</style>